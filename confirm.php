<?php
require_once __DIR__ . '/config.php';

try {
    initializeStorage();
} catch (Throwable $e) {
    logApplicationError('Initializing confirmation storage', $e);
    http_response_code(500);
    exit('Ett tekniskt fel uppstod.');
}

$token = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['token'] ?? '')
    : ($_GET['token'] ?? '');

if (
    !is_string($token) ||
    !preg_match('/^[a-f0-9]{64}$/', $token)
) {
    http_response_code(400);
    renderPage('Ogiltig länk', '<p>Bekräftelselänken är ogiltig.</p>');
    exit;
}

$tokenHash = hash('sha256', $token);

$file = STORAGE_DIR . '/pending/' . $tokenHash . '.json';

// Check that the pending submission exists
if (!is_file($file)) {
    http_response_code(404);
    renderPage(
        'Länken fungerar inte',
        '<p>Bekräftelselänken har redan använts eller är ogiltig.</p>'
    );
    exit;
}

// GET: Show confirmation button
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $contents = file_get_contents($file);
    if ($contents === false) {
        error_log('[ULMAP] Could not read pending confirmation file.');
        http_response_code(500);
        renderPage(
            'Kunde inte läsa meddelandet',
            '<p>Ett tekniskt fel uppstod. Försök igen senare.</p>'
        );
        exit;
    }

    $data = json_decode($contents, true);

    if (
        !is_array($data) ||
        json_last_error() !== JSON_ERROR_NONE ||
        !isset($data['expires_at']) ||
        $data['expires_at'] < time()
    ) {
        @unlink($file);

        http_response_code(410);
        renderPage(
            'Länken har gått ut',
            '<p>Bekräftelselänken har gått ut. Skicka gärna formuläret igen.</p>'
        );
        exit;
    }

    renderPage(
        'Bekräfta ditt meddelande',
        '<p>Vill du bekräfta din e-postadress och skicka ditt meddelande till ULMAP?</p>

        <form method="POST" action="/confirm.php">
            <input type="hidden" name="token" value="' .
                htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">

            <button type="submit">Bekräfta och skicka meddelandet</button>
        </form>'
    );
    exit;
}

// Only POST can send the actual message
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

$handle = fopen($file, 'r+b');

if ($handle === false || !flock($handle, LOCK_EX)) {
    if (is_resource($handle)) {
        fclose($handle);
    }

    error_log('[ULMAP] Could not open or lock pending confirmation file.');
    http_response_code(500);
    exit('Kunde inte verifiera meddelandet.');
}

try {
    $contents = stream_get_contents($handle);
    if ($contents === false) {
        error_log('[ULMAP] Could not read locked pending confirmation file.');
        http_response_code(500);
        renderPage(
            'Kunde inte läsa meddelandet',
            '<p>Ett tekniskt fel uppstod. Försök igen senare.</p>'
        );
        exit;
    }

    $data = json_decode($contents, true);

    if (
        !is_array($data) ||
        json_last_error() !== JSON_ERROR_NONE ||
        !isset(
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message'],
            $data['expires_at']
        ) ||
        $data['expires_at'] < time()
    ) {
        http_response_code(410);
        renderPage(
            'Länken har gått ut',
            '<p>Bekräftelselänken är inte längre giltig.</p>'
        );
        exit;
    }

    $body =
        "Namn: " . $data['name'] . "\n\n" .
        "E-post: " . $data['email'] . "\n" .
        "Telefon: " . $data['phone'] . "\n\n" .
        "Ämne: " . $data['subject'] . "\n\n" .
        "Meddelande:\n" . $data['message'] . "\n";

    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'From: ULMAP <' . CONTACT_EMAIL . '>',
        'Reply-To: ' . $data['email']
    ];

    $sent = sendEmail(
        CONTACT_EMAIL,
        'Nytt meddelande från hemsidan',
        $body,
        $data['email']
    );

    if (!$sent) {
        error_log(
            '[ULMAP] Sending confirmed contact message failed; see email provider error above.'
        );
        http_response_code(500);

        renderPage(
            'Kunde inte skicka meddelandet',
            '<p>Ett tekniskt fel uppstod. Försök igen senare.</p>'
        );
        exit;
    }

    // Remove pending message after successful sending
    if (!unlink($file)) {
        error_log('[ULMAP] Contact message was sent but pending file could not be removed.');
    }

    renderPage(
        'Tack för ditt meddelande!',
        '<p>Din e-postadress är bekräftad och ditt meddelande har skickats till ULMAP.</p>
         <p>Vi återkommer så snart vi kan.</p>
         <p><a href="/">Tillbaka till hemsidan</a></p>'
    );

} finally {
    flock($handle, LOCK_UN);
    fclose($handle);
}