<?php
session_start();
include __DIR__ . '/../../../vendor/algorithms/binarySearch.php';
$db_handler = __DIR__ .  '\..\..\..\storage_files\product.txt';

// Получаю данные о товарах.
$dataProducts = file($db_handler, FILE_IGNORE_NEW_LINES);

if (empty($dataProducts)) {
    echo 'Файл с продуктами пуст';
} else {
//    binarySearch($dataProducts, $_GET['code']);
    $incomingProduct = array_filter($dataProducts, function ($q) {
        $product = explode('|', $q);
        return $product[0] == $_GET['code'];
    });
}



// Поменять функцию для поиска товаров *
