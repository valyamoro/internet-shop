<?php
// СОДЕРЖИМОЕ ЗАКАЗА
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
        $productId[] = $parts[2];
        $count = $parts[3];


        if ($_SESSION['user']['id'] === $userId) {
            $orderUser[] = $line;
        }

    }

    foreach ($orderUser as $line) {
        $orderUser = explode('|', $line);
        $orderData[] = $orderUser;
    }

    fclose($fileOrderProduct);
} else {
    echo 'не удалоцй';
}

$productPath = __DIR__ . '/../../../../storage_files/product.txt';

$products = [];

$fileProduct = fopen($productPath, 'r');

if ($fileProduct) {
    while (($line = fgets($fileProduct)) !== false) {
        $line = trim($line);

        $parts = explode('|', $line);
        // idProduct|category|name|count|price

        $idProducts = $parts[0];

        if (in_array($idProducts, $productId)) {

            $category = $parts[1];
            $nameProduct = $parts[2];
            $countProductOrder = $parts[3];
            $priceProduct = $parts[4];

            echo "Product ID: $idProducts\n";
            echo "Category: $category\n";
            echo "Name: $nameProduct\n";
            echo "Count: $countProductOrder\n";
            echo "Price: $priceProduct\n";
            echo '<form action="order_show.php" method="post">';
            echo '<input type="submit" value="Посмотреть товар">';
            echo '</form>';
        }

    }
}
