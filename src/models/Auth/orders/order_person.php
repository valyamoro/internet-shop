<?php
session_start();

$orderPath = __DIR__ . '/../../../../storage_files/order.txt';

$userOrders = [];

// Получаем информацию о заказах пользователя.
$fileOrder = fopen($orderPath, 'r');
if ($fileOrder) {
    while (($line = fgets($fileOrder)) !== false) {
        $line = trim($line);

        $parts = explode('|', $line);

        $userId = $parts[0];
        $orderId = $parts[1];

        if (!isset($userOrders[$userId])) {
            $userOrders[$userId] = [];
        }
        $userOrders[$userId][] = $orderId;
    }
    fclose($fileOrder);

} else {
    echo 'Не удалось открыть файл';
}

//print_r($userOrders);

$orderProductPath = __DIR__ . '/../../../../storage_files/order_product.txt';

$orderProducts = [];

$fileOrderProduct = fopen($orderProductPath, 'r');

if ($fileOrderProduct) {
    while (($line = fgets($fileOrderProduct)) !== false) {
        $line = trim($line);

        $parts = explode('|', $line);

        $userId = $parts[1];
        $orderId = $parts[0];
        $productId = $parts[2];
        $count = $parts[3];

        print_r($orderProducts);
        if (isset($orderProducts[$orderId])) {
            $orderProducts[$orderId] = [];
        }
        $orderProducts[$orderId][] = $productId;
//        print_r($orderProducts);

    }
    fclose($fileOrderProduct);
} else {
    echo 'не удалоцй';
}


