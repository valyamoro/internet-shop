<?php
// ВЫВОД ЗАКАЗОВ
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

        $userIdToDisplay = $_SESSION['user']['id'];


        if ($userId === $_SESSION['user']['id']) {
//            echo "Заказы для пользователя с ID $userIdToDisplay: ";
//            echo "Финальная цена: $totalPrice";
//            foreach () {
//                echo "$orderId, ";
//            }
            $countWhile = count($parts);
            $totalIdUser = $userId;
            echo "<br>";
        }

    }

    fclose($fileOrder);

} else {
    echo 'Не удалось открыть файл';
}


$orderProductPath = __DIR__ . '/../../../../storage_files/order_product.txt';

$orderProducts = [];

$fileOrderProduct = fopen($orderProductPath, 'r');

if ($fileOrderProduct) {
    while (($line = fgets($fileOrderProduct)) !== false) {

        $line = trim($line);
        $parts = explode('|', $line);

//        $userId = $parts[1];
        $orderId = $parts[0];
        $productId[] = $parts[2];
        $count[] = $parts[3];

//       if ($_SESSION['user']['id'] === $userId) {
//            $count = $count + $count;
//       }
        $countPerProduct = $count;
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
        if ($_SESSION['user']['id'] === $totalIdUser) {
            if (in_array($idProducts, $productId)) {
                $productInfo = [
                    'category' => $parts[1],
                    'nameProduct' => $parts[2],
                    'priceProduct' => $parts[4],
                ];


            }
        }
            $priceProducts[] = $parts[4]; // Добавляем только один раз
    }

    if (count($priceProducts) === count($countPerProduct)) {
        $result = [];

        for ($i = 0; $i < count($priceProducts); $i++) {
            $result[] = $priceProducts[$i] * $countPerProduct[$i];
        }

    } else {
        echo 'Массивы имеют разную длину';
    }

    $finalPrice = array_sum($result);


    fclose($fileProduct);
}

// Блок с выводом заказов:

$dataOrder = file($orderPath, FILE_IGNORE_NEW_LINES);

$countOrder = count($dataOrder);

while ($countOrder > 0) {
    echo "Заказы для пользователя с ID $userIdToDisplay: <br>" ;
    echo "Финальная цена: $finalPrice <br>";
    echo '---------------------------------';
    $countOrder--;
}


