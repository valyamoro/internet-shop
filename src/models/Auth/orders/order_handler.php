<?php
session_start();
$orderProductPath = __DIR__ .'/../../../../storage_files/order_product.txt';
$orderPath = __DIR__ .'/../../../../storage_files/order.txt';

$handlerOrderProduct = fopen($orderProductPath, 'a + b');
$handlerOrder = fopen($orderPath, 'a + b');

$dataOrderProduct = file($orderProductPath, FILE_IGNORE_NEW_LINES);
$dataOrder = file($orderPath, FILE_IGNORE_NEW_LINES);

$orderData = $_SESSION['cart_item']; // Данные о товарах из сессии
$userId = $_SESSION['user']['id'];

$orderProductId = $dataOrderProduct ? (intval(explode('|', end($dataOrderProduct))[0]) + 1) : 1;
$orderId = $dataOrder ? (intval(explode('|', end($dataOrder))[1]) + 1) : 1;

//order_product:
//orderId|userId|productId|count

//order:
// idUser|idOrder|

foreach ($orderData as $item) {
    $formattedOrderProductData = "{$orderId}|{$userId}|{$item['code']}|{$item['quantity']}";
    fwrite($handlerOrderProduct, $formattedOrderProductData . PHP_EOL);
}

$formattedOrderData = "{$userId}|{$orderId}";
fwrite($handlerOrder, $formattedOrderData . PHP_EOL);

fclose($handlerOrder);
fclose($handlerOrderProduct);


