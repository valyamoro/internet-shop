<?php

session_start();
// Тут в будущем будут ендпоинты для подключения сторонних сервисов к моему сайту.
// Человек на другом сайте авторизуется, вводит полученный на моем сайте API-ключ и теперь может на другом сайте получать
// свои заказы в виде json и дальше делать с ними всё что хочется.

if (empty($_POST)) {
    echo 'Что-то пошло не так...';
} else {

    $id = $_SESSION['user']['id'];

    $apiKey = uniqid();

    $apiData = "{$id}|{$apiKey}";

    $filePath = __DIR__ . '\..\..\storage_files\api_key.txt';

    $handlerApi = fopen($filePath, 'a + b');
    fwrite($handlerApi, $apiData . PHP_EOL);

}
