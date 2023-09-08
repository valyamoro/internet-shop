<?php

$projectType = null;
$envFilePath = '../../../env.txt';

if (file_exists($envFilePath)) {
    // Чтение содержимого файла .env
    $envContents = file_get_contents($envFilePath);


    // Парсинг переменных окружения
    $envLines = explode("\n", $envContents);
    foreach ($envLines as $line) {
        list($key, $value) = explode('=', $line, 2);
        if ($key === 'TYPE_OF_PROJECT') {
            $projectType = trim($value);
            break;
        }
    }
}

if ($projectType === 'debug') {
    ini_set('display_errors', '1'); // Ошибки выводятся в режиме отладки
    error_reporting(E_ALL); // Все ошибки выводятся в режиме отладки
} elseif ($projectType === 'production') {
    ini_set('display_errors', '0'); // Ошибки выводятся в режиме отладки
    error_reporting(0); // Все ошибки выводятся в режиме отладки
}

