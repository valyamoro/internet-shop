<?php
session_start();
$filePath = __DIR__ .'/../../../../storage_files/order_product.txt';

$handlerOrder = fopen($filePath, 'a + b');
$dataOrder = file($filePath, FILE_IGNORE_NEW_LINES);

$orderData = $_SESSION['cart_item']; // Данные о товарах из сессии
$userId = $_SESSION['user']['id'];

$orderId = $dataOrder ? (intval(explode('|', end($dataOrder))[0]) + 1) : 1;
//order|user|product|count

foreach ($orderData as $item) {
    $formattedData = "{$orderId}|{$userId}|{$item['code']}|{$item['quantity']}";

    fwrite($handlerOrder, $formattedData . PHP_EOL);

}

