<?php

$smtpServer = 'smtp.gmail.com';
$smtpPort = 587;
$userName = 'my_mail';
$password = 'my_pass';
$to = 'adress_another_guy';
$subject = 'Тема письма';
$message = 'Текст письма';

$socket = fsockopen($smtpServer, $smtpPort, $errno, $errstr, 30);

if (!$socket) {
    echo "Не удалось соединиться с сервером ($errno) : $errstr";
} else {
    $response = fgets($socket);
    if (substr($response, 0, 3) != '220') {
        echo "Сервер не отвечает корректно: $response";
    } else {
        // Отправка EHLO команды
        fputs($socket, "EHLO example.com\r\n");
        $response = fgets($socket);

        // Начало шифрования
        fputs($socket, "STARTTLS\r\n");
        $response = fgets($socket);

        // Установка шифрованного соединения
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

        // Повторное EHLO
        fputs($socket, "EHLO example.com\r\n");
        $response = fgets($socket);

        // Аутентификация
        fputs($socket, "AUTH LOGIN\r\n");
        $response = fgets($socket);

        fputs($socket, base64_encode($userName) . "\r\n");
        $response = fgets($socket);

        fputs($socket, base64_encode($password) . "r\n");
        $response = fgets($socket);

        // Отправка письма
        fputs($socket, "MAIL FROM: <$userName>\r\n");
        $response = fgets($socket);

        fputs($socket, "RCPT TO: <$to>\r\n");
        $response = fgets($socket);

        fputs($socket, "DATA\r\n");
        $response = fgets($socket);

        fputs($socket, "Subject: $subject\r\n");
        fputs($socket, "To: $to\r\n");
        fputs($socket, "From: $userName\r\n");

        fputs($socket, $message . "\r\n");
        fputs($socket, ".\r\n");

        $response = fgets($socket);

        fputs($socket, "QUIT\r\n");
        fclose($socket);

        echo "Письмо успешно отправлено!";

    }
}