<?php
// Все заказы
$filePath = __DIR__ . '/../../../../storage_files/order_product.txt';

$ordersData = file($filePath, FILE_IGNORE_NEW_LINES);

foreach ($ordersData as $item) {
    $item = explode('|', $item);
    $orderId = $item[0];
}
print_r($orderId);
die;