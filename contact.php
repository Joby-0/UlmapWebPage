<?php

ini_set('log_errors', '1');
ini_set('error_reporting', (string) E_ALL);
ini_set('error_log', __DIR__ . '/../private/php-error.log');

error_log('========== CONTACT FORM STARTED ==========');

require_once __DIR__ . '/config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

try {
    initializeStorage();
} catch (Throwable $e) {
    logApplicationError('Initializing contact form storage', $e);
    http_response_code(500);
    exit('Ett tekniskt fel uppstod. Försök igen senare.');
}

function getInput(string $key): string
{
    $value = $_POST[$key] ?? '';

    return is_string($value) ? trim($value) : '';
}

// Honeypot
if (getInput('website') !== '') {
    renderPage(
        'Tack!',
        '<p>Vi har tagit emot ditt meddelande.</p>'
    );
    exit;
}

$name = getInput('name');
$email = getInput('email');
$phone = getInput('phone');
$subject = getInput('subject');
$message = getInput('message');

$errors = [];

if (textLength($name) < 2 || textLength($name) > 100) {
    $errors[] = 'Namnet måste vara mellan 2 och 100 tecken.';
}

if (
    strlen($email) > 254 ||
    preg_match('/[\r\n]/', $email) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    $errors[] = 'Ogiltig e-postadress.';
}

if (textLength($phone) > 30) {
    $errors[] = 'Telefonnumret är för långt.';
}

if ($subject === '' || textLength($subject) > 100) {
    $errors[] = 'Du måste välja ett område.';
}

if (textLength($message) < 10 || textLength($message) > 5000) {
    $errors[] = 'Meddelandet måste vara mellan 10 och 5000 tecken.';
}

if (!empty($errors)) {
    http_response_code(400);

    renderPage(
        'Kontrollera formuläret',
        '<ul><li>' .
        implode('</li><li>', array_map(
            fn($error) => htmlspecialchars($error, ENT_QUOTES, 'UTF-8'),
            $errors
        )) .
        '</li></ul><p><a href="/kontakt">Tillbaka till formuläret</a></p>'
    );
    exit;
}

// Rate limiting: IP + email
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

try {
    if (
        !rateLimit('ip:' . $ip) ||
        !rateLimit('email:' . strtolower($email))
    ) {
        http_response_code(429);

        renderPage(
            'För många försök',
            '<p>Du har skickat för många förfrågningar. Försök igen senare.</p>'
        );
        exit;
    }
} catch (Throwable $e) {
    logApplicationError('Applying contact form rate limit', $e);
    http_response_code(500);
    exit('Ett tekniskt fel uppstod.');
}

try {
    // Generate secure verification token
    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);

    $pendingFile = STORAGE_DIR . '/pending/' . $tokenHash . '.json';

    $data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'message' => $message,
        'created_at' => time(),
        'expires_at' => time() + TOKEN_LIFETIME
    ];

    $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($encodedData === false) {
        throw new RuntimeException(
            'Could not encode pending contact submission: ' . json_last_error_msg()
        );
    }

    if (file_put_contents($pendingFile, $encodedData, LOCK_EX) === false) {
        throw new RuntimeException('Could not write pending contact submission.');
    }

    if (!chmod($pendingFile, 0600)) {
        throw new RuntimeException('Could not restrict permissions on pending contact submission.');
    }
} catch (Throwable $e) {
    logApplicationError('Saving pending contact submission', $e);
    if (isset($pendingFile) && is_file($pendingFile) && !@unlink($pendingFile)) {
        error_log('[ULMAP] Could not remove incomplete pending contact submission.');
    }
    http_response_code(500);
    exit('Kunde inte spara meddelandet.');
}

// Create confirmation link
$confirmationLink = SITE_URL . '/confirm.php?token=' . $token;

$mailBody =
    "Hej!\n\n" .
    "Tack för att du kontaktar ULMAP.\n\n" .
    "För att bekräfta din e-postadress och skicka ditt meddelande, " .
    "klicka på länken nedan:\n\n" .
    $confirmationLink . "\n\n" .
    "Länken är giltig i 30 minuter.\n\n" .
    "Om du inte har skickat detta meddelande kan du ignorera detta mejl.\n\n" .
    "Med vänliga hälsningar,\nULMAP";

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: ULMAP <' . CONTACT_EMAIL . '>'
];

$sent = sendEmail(
    $email,
    'Bekräfta ditt meddelande till ULMAP',
    $mailBody
);

if (!$sent) {
    @unlink($pendingFile);

    http_response_code(500);
    renderPage(
        'Kunde inte skicka mejlet',
        '<p>Det gick inte att skicka bekräftelsemejlet. Försök igen senare.</p>'
    );
    exit;
}

renderPage(
    'Bekräfta din e-postadress',
    '<p>Vi har skickat ett bekräftelsemejl till din e-postadress.</p>
     <p>Klicka på länken i mejlet inom 30 minuter för att skicka ditt meddelande till ULMAP.</p>'
);