<?php
/**
 * api/contact.php
 * Contact form handler for the portfolio.
 * Part of the DevCore Suite.
 *
 * Path: devcore-suite-clean/projects/portfolio/api/contact.php
 * Expects POST with JSON body: { name, email, work_type, budget, message }
 *
 * On success: appends a line to ../contact_log.txt
 * On failure: returns 422 with validation errors
 */

/* ─── Bootstrap ──────────────────────────────────────────────── */
require_once dirname(__DIR__) . '/core/bootstrap.php';

/* ─── Optional Composer autoload (PHPMailer) ────────────────── */
$vendorAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;

    if (class_exists('Dotenv\\Dotenv')) {
        try {
            Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
        } catch (Throwable $e) {
            error_log('Portfolio dotenv load warning: ' . $e->getMessage());
        }
    }
}

/* ─── Helpers ────────────────────────────────────────────────── */
function cfg(string $key, mixed $default = null): mixed {
    static $config = null;

    if ($config === null) {
        $configPath = dirname(__DIR__) . '/config.php';
        $config = is_file($configPath) ? (require $configPath) : [];
    }

    $envVal = getenv($key);
    if ($envVal !== false && $envVal !== '') {
        return $envVal;
    }

    if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
        return $_ENV[$key];
    }

    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
        return $_SERVER[$key];
    }

    $configKey = strtolower($key);
    if (array_key_exists($configKey, $config)) {
        return $config[$configKey];
    }

    return $default;
}

function clean_text(?string $value): string {
    $value = trim((string)$value);
    $value = strip_tags($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function is_rate_limited(string $ip, int $limit, int $windowSeconds, string $rateFile): bool {
    $dir = dirname($rateFile);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $now = time();
    $history = [];

    if (is_file($rateFile)) {
        $raw = file_get_contents($rateFile);
        $decoded = json_decode((string)$raw, true);
        if (is_array($decoded)) {
            $history = $decoded;
        }
    }

    if (!isset($history[$ip]) || !is_array($history[$ip])) {
        $history[$ip] = [];
    }

    $cutoff = $now - $windowSeconds;
    $history[$ip] = array_values(array_filter($history[$ip], static fn ($ts) => is_int($ts) && $ts >= $cutoff));

    $limited = count($history[$ip]) >= $limit;
    if (!$limited) {
        $history[$ip][] = $now;
    }

    file_put_contents($rateFile, json_encode($history, JSON_PRETTY_PRINT), LOCK_EX);

    return $limited;
}

function send_mail(string $to, string $subject, string $textBody, string $htmlBody = ''): array {
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        return [
            'sent' => false,
            'error' => 'PHPMailer class not found (vendor/autoload missing or dependency not installed)',
        ];
    }

    $host = (string)cfg('MAIL_HOST', '');
    $port = (int)cfg('MAIL_PORT', 587);
    $user = (string)cfg('MAIL_USER', '');
    $pass = (string)cfg('MAIL_PASS', '');
    $from = (string)cfg('MAIL_FROM', '');
    $fromName = (string)cfg('MAIL_FROM_NAME', (string)cfg('APP_NAME', 'Portfolio'));
    $encryption = strtolower((string)cfg('MAIL_ENCRYPTION', 'tls'));

    if ($host === '' || $user === '' || $pass === '' || $from === '') {
        error_log('Portfolio contact SMTP skipped: missing MAIL_HOST/MAIL_USER/MAIL_PASS/MAIL_FROM');
        return [
            'sent' => false,
            'error' => 'Missing MAIL_HOST/MAIL_USER/MAIL_PASS/MAIL_FROM',
        ];
    }

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->Port = $port;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;

        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody !== '' ? $htmlBody : nl2br($textBody);
        $mail->AltBody = $textBody;
        $mail->isHTML(true);
        $mail->send();

        return [
            'sent' => true,
            'error' => '',
        ];
    } catch (Throwable $e) {
        error_log('Portfolio contact SMTP error: ' . $e->getMessage());
        return [
            'sent' => false,
            'error' => $e->getMessage(),
        ];
    }
}

/* ─── Method guard ───────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Api::error('Method not allowed', 405);
}

/* ─── Basic rate limiting ────────────────────────────────────── */
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateLimit = (int)cfg('CONTACT_RATE_LIMIT', 5);
$rateWindow = (int)cfg('CONTACT_RATE_WINDOW', 600);
$rateFile = dirname(__DIR__) . '/tmp/contact_rate_limit.json';

if (is_rate_limited($ip, $rateLimit, $rateWindow, $rateFile)) {
    Api::error('Too many requests. Please try again later.', 429);
}

/* ─── Read JSON body ─────────────────────────────────────────── */
$body = Api::body();

/* ─── Validate ───────────────────────────────────────────────── */
$v = Validator::make($body, [
    'name'    => 'required|min:2|max:100',
    'email'   => 'required|email',
    'work_type' => 'max:30',
    'budget'  => 'max:60',
    'message' => 'required|min:10|max:2000',
]);

if ($v->fails()) {
    Api::error('Validation failed', 422, $v->errors());
}

/* ─── Sanitize values ────────────────────────────────────────── */
$name      = clean_text($body['name'] ?? '');
$email     = clean_text($body['email'] ?? '');
$workType  = clean_text($body['work_type'] ?? 'not specified');
$budget    = clean_text($body['budget'] ?? 'not specified');
$message   = clean_text($body['message'] ?? '');

/* ─── Log to file ────────────────────────────────────────────── */
$logFile = dirname(__DIR__) . '/contact_log.txt';
$logDir  = dirname($logFile);

// Ensure the directory exists and is writable
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$date    = date('Y-m-d H:i:s');
$ownerEmail = (string)cfg('CONTACT_EMAIL', '');

/* ─── Mail send (owner + acknowledgement) ───────────────────── */
$ownerSubject = "Portfolio contact from {$name}";
$ownerText = "Name: {$name}\nEmail: {$email}\nWork type: {$workType}\nBudget: {$budget}\n\nMessage:\n{$message}";
$ownerHtml = "<h3>New Portfolio Contact</h3><p><strong>Name:</strong> {$name}<br><strong>Email:</strong> {$email}<br><strong>Work type:</strong> {$workType}<br><strong>Budget:</strong> {$budget}</p><p><strong>Message:</strong><br>" . nl2br($message) . '</p>';

$ownerResult = $ownerEmail !== ''
    ? send_mail($ownerEmail, $ownerSubject, $ownerText, $ownerHtml)
    : ['sent' => false, 'error' => 'Missing CONTACT_EMAIL'];
$ownerSent = (bool)($ownerResult['sent'] ?? false);
$ownerError = (string)($ownerResult['error'] ?? '');

$ackSubject = 'We received your message';
$ackText = "Hi {$name},\n\nThanks for contacting me. I received your message and will reply within 24 hours.\n\nYour message:\n{$message}\n\nBest regards,\n" . (string)cfg('APP_NAME', 'Portfolio');
$ackHtml = "<p>Hi {$name},</p><p>Thanks for contacting me. I received your message and will reply within 24 hours.</p><p><strong>Your message:</strong><br>" . nl2br($message) . '</p><p>Best regards,<br>' . htmlspecialchars((string)cfg('APP_NAME', 'Portfolio'), ENT_QUOTES, 'UTF-8') . '</p>';
$ackResult = send_mail($email, $ackSubject, $ackText, $ackHtml);
$ackSent = (bool)($ackResult['sent'] ?? false);
$ackError = (string)($ackResult['error'] ?? '');

$ownerErrorLog = $ownerError !== '' ? str_replace(["\r", "\n", '|'], [' ', ' ', '/'], $ownerError) : '';
$ackErrorLog = $ackError !== '' ? str_replace(["\r", "\n", '|'], [' ', ' ', '/'], $ackError) : '';

$logLine = "[{$date}] | {$name} | {$email} | {$workType} | {$budget} | owner_mail=" . ($ownerSent ? 'sent' : 'not_sent') . " | ack_mail=" . ($ackSent ? 'sent' : 'not_sent') . " | owner_err={$ownerErrorLog} | ack_err={$ackErrorLog} | {$message}" . PHP_EOL;
$logLine .= str_repeat('-', 100) . PHP_EOL;

$written = file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);

if ($written === false) {
    // Log to PHP error log as fallback — don't fail the user over a disk issue
    error_log("Portfolio contact: [{$date}] {$name} <{$email}> — {$message}");
}

/* ─── Success response ───────────────────────────────────────── */
Api::success([
    'mail' => [
        'owner_notified' => $ownerSent,
        'sender_acknowledged' => $ackSent,
    ],
], 'Message received');
