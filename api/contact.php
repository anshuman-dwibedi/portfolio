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
$appName = htmlspecialchars((string)cfg('APP_NAME', 'Portfolio'), ENT_QUOTES, 'UTF-8');
$appUrl = htmlspecialchars((string)cfg('APP_URL', '#'), ENT_QUOTES, 'UTF-8');
$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safeWorkType = htmlspecialchars($workType, ENT_QUOTES, 'UTF-8');
$safeBudget = htmlspecialchars($budget, ENT_QUOTES, 'UTF-8');
$safeMessageHtml = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

/* ─── Mail send (owner + acknowledgement) ───────────────────── */
$ownerSubject = "Portfolio contact from {$name}";
$ownerText = "Name: {$name}\nEmail: {$email}\nWork type: {$workType}\nBudget: {$budget}\n\nMessage:\n{$message}";
$ownerHtml = '<div style="background:#0b1220;padding:28px 0;font-family:Segoe UI,Arial,sans-serif;color:#e8edf4;">'
    . '<div style="max-width:640px;margin:0 auto;background:#111a2b;border:1px solid #22344f;border-radius:14px;overflow:hidden;">'
    . '<div style="padding:18px 22px;background:linear-gradient(135deg,#0f1728 0%,#16243b 100%);border-bottom:1px solid #22344f;">'
    . '<div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#88a2c8;">' . $appName . '</div>'
    . '<div style="font-size:20px;font-weight:700;color:#f5f8ff;margin-top:6px;">New Contact Request</div>'
    . '</div>'
    . '<div style="padding:20px 22px;">'
    . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">'
    . '<tr><td style="padding:8px 0;color:#8ea5c9;width:130px;">Name</td><td style="padding:8px 0;color:#f5f8ff;">' . $safeName . '</td></tr>'
    . '<tr><td style="padding:8px 0;color:#8ea5c9;">Email</td><td style="padding:8px 0;color:#f5f8ff;">' . $safeEmail . '</td></tr>'
    . '<tr><td style="padding:8px 0;color:#8ea5c9;">Work Type</td><td style="padding:8px 0;color:#f5f8ff;">' . $safeWorkType . '</td></tr>'
    . '<tr><td style="padding:8px 0;color:#8ea5c9;">Budget</td><td style="padding:8px 0;color:#f5f8ff;">' . $safeBudget . '</td></tr>'
    . '</table>'
    . '<div style="margin-top:14px;background:#0d1525;border:1px solid #22344f;border-radius:10px;padding:14px;">'
    . '<div style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#88a2c8;margin-bottom:8px;">Message</div>'
    . '<div style="font-size:14px;line-height:1.6;color:#dce6f5;">' . $safeMessageHtml . '</div>'
    . '</div>'
    . '</div>'
    . '</div>'
    . '</div>';

$ownerResult = $ownerEmail !== ''
    ? send_mail($ownerEmail, $ownerSubject, $ownerText, $ownerHtml)
    : ['sent' => false, 'error' => 'Missing CONTACT_EMAIL'];
$ownerSent = (bool)($ownerResult['sent'] ?? false);
$ownerError = (string)($ownerResult['error'] ?? '');

$ackSubject = 'We received your message';
$ackText = "Hi {$name},\n\nThanks for contacting me. I received your message and will reply within 24 hours.\n\nYour message:\n{$message}\n\nBest regards,\n" . (string)cfg('APP_NAME', 'Portfolio');
$ackHtml = '<div style="background:#0b1220;padding:28px 0;font-family:Segoe UI,Arial,sans-serif;color:#e8edf4;">'
    . '<div style="max-width:640px;margin:0 auto;background:#111a2b;border:1px solid #22344f;border-radius:14px;overflow:hidden;">'
    . '<div style="padding:22px;background:linear-gradient(135deg,#0d1423 0%,#173055 100%);border-bottom:1px solid #22344f;">'
    . '<div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#9fc6ff;">' . $appName . '</div>'
    . '<div style="font-size:22px;font-weight:700;color:#ffffff;margin-top:8px;">Message Received</div>'
    . '<div style="font-size:14px;color:#c9dbf8;margin-top:6px;">Thanks for reaching out, ' . $safeName . '.</div>'
    . '</div>'
    . '<div style="padding:22px;">'
    . '<p style="margin:0 0 14px 0;font-size:14px;line-height:1.7;color:#dce6f5;">I have received your message and will get back to you within 24 hours.</p>'
    . '<div style="background:#0d1525;border:1px solid #22344f;border-radius:10px;padding:14px;margin:16px 0;">'
    . '<div style="font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:#88a2c8;margin-bottom:8px;">Your Message</div>'
    . '<div style="font-size:14px;line-height:1.6;color:#dce6f5;">' . $safeMessageHtml . '</div>'
    . '</div>'
    . '<a href="' . $appUrl . '" style="display:inline-block;background:#3b82f6;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:600;">Visit Portfolio</a>'
    . '<p style="margin:18px 0 0 0;font-size:13px;color:#98adcf;">Best regards,<br>' . $appName . '</p>'
    . '</div>'
    . '</div>'
    . '</div>';
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
