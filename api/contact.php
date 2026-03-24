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

/* ─── Method guard ───────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Api::error('Method not allowed', 405);
}

/* ─── Read JSON body ─────────────────────────────────────────── */
$body = Api::body();

/* ─── Validate ───────────────────────────────────────────────── */
$v = Validator::make($body, [
    'name'    => 'required|min:2|max:100',
    'email'   => 'required|email',
    'message' => 'required|min:10|max:2000',
]);

if ($v->fails()) {
    Api::error('Validation failed', 422, $v->errors());
}

/* ─── Sanitize values ────────────────────────────────────────── */
$name      = htmlspecialchars(trim($body['name']    ?? ''), ENT_QUOTES, 'UTF-8');
$email     = htmlspecialchars(trim($body['email']   ?? ''), ENT_QUOTES, 'UTF-8');
$workType  = htmlspecialchars(trim($body['work_type'] ?? 'not specified'), ENT_QUOTES, 'UTF-8');
$budget    = htmlspecialchars(trim($body['budget']  ?? 'not specified'), ENT_QUOTES, 'UTF-8');
$message   = htmlspecialchars(trim($body['message'] ?? ''), ENT_QUOTES, 'UTF-8');

/* ─── Log to file ────────────────────────────────────────────── */
$logFile = dirname(__DIR__) . '/contact_log.txt';
$logDir  = dirname($logFile);

// Ensure the directory exists and is writable
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

$date    = date('Y-m-d H:i:s');
$logLine = "[{$date}] | {$name} | {$email} | {$workType} | {$budget} | {$message}" . PHP_EOL;
$logLine .= str_repeat('-', 80) . PHP_EOL;

$written = file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);

if ($written === false) {
    // Log to PHP error log as fallback — don't fail the user over a disk issue
    error_log("Portfolio contact: [{$date}] {$name} <{$email}> — {$message}");
}

/* ─── Optional: swap to SMTP here ───────────────────────────────
 *
 * To send email instead of (or in addition to) the log file,
 * uncomment and configure the block below.
 * Requires: composer require phpmailer/phpmailer
 *
 * require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
 * $mail = new PHPMailer\PHPMailer\PHPMailer(true);
 * $mail->isSMTP();
 * $mail->Host       = 'smtp.yourdomain.com';
 * $mail->SMTPAuth   = true;
 * $mail->Username   = 'you@yourdomain.com';
 * $mail->Password   = 'your-smtp-password';
 * $mail->SMTPSecure = 'tls';
 * $mail->Port       = 587;
 * $mail->setFrom('noreply@yourdomain.com', 'Portfolio Contact');
 * $mail->addAddress('you@yourdomain.com');
 * $mail->Subject = "Portfolio contact from {$name}";
 * $mail->Body    = "Name: {$name}\nEmail: {$email}\nWork type: {$workType}\nBudget: {$budget}\n\n{$message}";
 * $mail->send();
 *
 ──────────────────────────────────────────────────────────────── */

/* ─── Success response ───────────────────────────────────────── */
Api::success([], 'Message received');
