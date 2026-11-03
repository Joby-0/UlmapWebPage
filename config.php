<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('display_errors', '0');

function logApplicationError(string $context, Throwable $error): void
{
    error_log(sprintf(
        '[ULMAP] %s: %s: %s in %s:%d',
        $context,
        get_class($error),
        $error->getMessage(),
        $error->getFile(),
        $error->getLine()
    ));
}

try {
    require_once __DIR__ . '/../private/vendor/autoload.php';
} catch (Throwable $e) {
    logApplicationError('Loading email dependencies', $e);
    throw $e;
}

const SITE_URL = 'https://ulmap.se';
const CONTACT_EMAIL = 'ulrika.maars@ulmap.se';
const MAIL_FROM = 'ULMAP <noreply@ulmap.se>';

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
                throw new RuntimeException(
                    'Storage directory could not be created: ' . $directory
                );
            }
        }
    }

    $storage = realpath(STORAGE_DIR);
    $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

    if ($storage === false) {
        throw new RuntimeException('Private storage path could not be resolved.');
    }

    if ($documentRoot === false) {
        throw new RuntimeException('Website document root could not be resolved.');
    }

    if (
        $storage === $documentRoot ||
        str_starts_with($storage, $documentRoot . DIRECTORY_SEPARATOR)
    ) {
        throw new RuntimeException('Private storage must be outside the public website directory.');
    }

    foreach (['pending', 'limits'] as $directory) {
        $path = STORAGE_DIR . '/' . $directory;
        if (!is_writable($path)) {
            throw new RuntimeException('Private storage directory is not writable: ' . $path);
        }
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
        throw new RuntimeException('Could not open rate limit file: ' . $file);
    }

    $locked = false;
    try {
        if (!flock($handle, LOCK_EX)) {
            throw new RuntimeException('Could not lock rate limit file: ' . $file);
        }
        $locked = true;

        $contents = stream_get_contents($handle);
        if ($contents === false) {
            throw new RuntimeException('Could not read rate limit file: ' . $file);
        }

        $timestamps = json_decode($contents ?: '[]', true);
        if (!is_array($timestamps) || json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Invalid rate limit data in ' . $file . ': ' . json_last_error_msg()
            );
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
        if (!ftruncate($handle, 0)) {
            throw new RuntimeException('Could not truncate rate limit file: ' . $file);
        }

        $encoded = json_encode(array_values($timestamps));
        if (
            $encoded === false ||
            fwrite($handle, $encoded) === false ||
            !fflush($handle)
        ) {
            throw new RuntimeException('Could not write rate limit file: ' . $file);
        }

        return true;
    } finally {
        if ($locked) {
            flock($handle, LOCK_UN);
        }
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
        error_log('[ULMAP] Sending email to ' . $to . ' with subject: ' . $subject);
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
        logApplicationError('Sending email through Resend', $e);
        return false;
    }
}