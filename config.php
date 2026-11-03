<?php
declare(strict_types=1);
require_once __DIR__ . '/../private/vendor/autoload.php';

const SITE_URL = 'https://ulmap.se';
const CONTACT_EMAIL = 'ulrika.maars@ulmap.se';
const MAIL_FROM = 'ULMAP <noreply@send.ulmap.se>';

const TOKEN_LIFETIME = 1800; // 30 minutes
const RATE_LIMIT_WINDOW = 3600; // 1 hour
const MAX_REQUESTS_PER_HOUR = 3;

// Private storage, outside public_html
const STORAGE_DIR = __DIR__ . '/../private/contact-verification';

function initializeStorage(): void
{
    $directories = [
        STORAGE_DIR,
        STORAGE_DIR . '/pending',
        STORAGE_DIR . '/limits'
    ];

    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0700, true) && !is_dir($directory)) {
                throw new RuntimeException('Storage directory could not be created.');
            }
        }
    }

    $storage = realpath(STORAGE_DIR);
    $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

    if (
        $storage === false ||
        $documentRoot === false ||
        $storage === $documentRoot ||
        str_starts_with($storage, $documentRoot . DIRECTORY_SEPARATOR)
    ) {
        throw new RuntimeException('Private storage must be outside the public website directory.');
    }

    if (!is_writable(STORAGE_DIR . '/pending') ||
        !is_writable(STORAGE_DIR . '/limits')) {
        throw new RuntimeException('Private storage is not writable.');
    }
}

function textLength(string $value): int
{
    return function_exists('mb_strlen')
        ? mb_strlen($value, 'UTF-8')
        : strlen($value);
}

function rateLimit(string $identifier): bool
{
    $file = STORAGE_DIR . '/limits/' .
        hash('sha256', $identifier) . '.json';

    $handle = fopen($file, 'c+');

    if ($handle === false) {
        throw new RuntimeException('Rate limit storage unavailable.');
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            throw new RuntimeException('Could not lock rate limit storage.');
        }

        $contents = stream_get_contents($handle);
        $timestamps = json_decode($contents ?: '[]', true);

        if (!is_array($timestamps)) {
            $timestamps = [];
        }

        $now = time();

        $timestamps = array_filter(
            $timestamps,
            fn($timestamp) =>
                is_int($timestamp) &&
                $timestamp > $now - RATE_LIMIT_WINDOW
        );

        if (count($timestamps) >= MAX_REQUESTS_PER_HOUR) {
            return false;
        }

        $timestamps[] = $now;

        rewind($handle);
        ftruncate($handle, 0);

        fwrite($handle, json_encode(array_values($timestamps)));
        fflush($handle);

        return true;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function renderPage(string $title, string $content): void
{
    header('Content-Type: text/html; charset=UTF-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer');

    echo '<!DOCTYPE html>
    <html lang="sv">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>
    </head>
    <body>
        <main>
            <h1>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>
            ' . $content . '
        </main>
    </body>
    </html>';
}
function sendEmail(string $to, string $subject, string $body, ?string $replyTo = null
): bool {
    try {
        $settings = require __DIR__ . '/../private/mail-config.php';

        $resend = Resend::client($settings['api_key']);

        $email = [
            'from' => MAIL_FROM,
            'to' => [$to],
            'subject' => $subject,
            'text' => $body,
        ];

        if ($replyTo !== null) {
            $email['reply_to'] = $replyTo;
        }

        $resend->emails->send($email);

        return true;

    } catch (Throwable $e) {
        error_log('Resend email error: ' . $e->getMessage());
        return false;
    }
}