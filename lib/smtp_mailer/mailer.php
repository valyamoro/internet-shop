<?php
$smtpServer = 'smtp.gmail.com';
$smtpPort = 587;
$smtpUsername = 'your_email@gmail.com';
$smtpPassword = 'your_password';

$socket = fsockopen($smtpServer, $smtpPort, $errno, $errstr, 30);
if (!$socket) {
    die("Не удалось установить соединение с SMTP-сервером: $errno - $errstr");
}

function sendCommand($socket, $command)
{
    fwrite($socket, $command . "\r\n");
    $response = fgets($socket, 1024);
    return $response;
}

sendCommand($socket, 'EHLO localhost');

sendCommand($socket, 'STARTTLS');

stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

sendCommand($socket, 'EHLO localhost');

sendCommand($socket, "AUTH LOGIN");
sendCommand($socket, base64_encode($smtpUsername));
sendCommand($socket, base64_encode($smtpPassword));

sendCommand($socket, "MAIL FROM: <$smtpUsername>");
sendCommand($socket, "RCPT TO: <recipient_email@example.com>");

sendCommand($socket, "DATA");

$message = "Subject: Тема письма\r\n";
$message .= "From: Your Name <your_email@gmail.com>\r\n";
$message .= "To: Recipient Name <recipient_email@example.com>\r\n";
$message .= "\r\n";
$message .= "Текст сообщения\r\n";
$message .= ".\r\n";

sendCommand($socket, $message);

sendCommand($socket, "QUIT");

fclose($socket);

echo 'Письмо успешно отправлено!';
