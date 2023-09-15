<?php
session_start();

$db_handler = __DIR__ .  '\..\..\..\storage_files\product.txt';

// Получаю данные о товарах.
$dataProducts = file($db_handler, FILE_IGNORE_NEW_LINES);

$incomingProduct = array_filter($dataProducts, function ($q) {
    $product = explode('|', $q);
    return $product[0] == $_GET['code'];
});

// Поменять функцию для поиска товаров *
