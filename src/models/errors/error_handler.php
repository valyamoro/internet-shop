<?php

//require_once __DIR__ . '/../../../classes/Autoloader.php';
//Autoloader::register();

$filePath = __DIR__ . '/../../../env.txt';

if (!file_exists($filePath)) {
    echo 'Файла env.txt не существует!';
    die;
} else {
    // Получаю содержимое файла env.txt.
    $result = file($filePath, FILE_IGNORE_NEW_LINES);
    $env = [];

    // Получаю значение типа проекта.
    foreach ($result as $value) {
        $v = explode('=', $value);
        $env[$v[0]] = trim($v[1]);
    }
}

error_reporting($env['TYPE_OF_PROJECT'] === 'prod' ? 0: -1);


