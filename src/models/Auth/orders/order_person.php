<?php
include __DIR__ . '/../../../../debug/debug_functions/functions.php';
include __DIR__ . '/help_functions.php';
// ВЫВОД ЗАКАЗОВ
session_start();
// Нужно получить общую стоимость и айди заказа.
/*
Для этого нужно получить айди самого заказа, через него айди продуктов, а через айди продуктов получить стоимость.
*/
$orders = file('../../../../storage_files/order.txt');

foreach ($orders as $q) {
    $order = explode('|', $q);
    $ordersData[] = $order;

}

$ordersProduct = file('../../../../storage_files/order_product.txt');
// Нужно получить второй элемент массива, а через него цену продукта

foreach ($ordersProduct as $q) {
    $orderProduct = explode('|', $q);
    $ordersProductData[] = $orderProduct;
}

$products = file('../../../../storage_files/product.txt');
// Нужно получить 4 элемент массива

foreach ($products as $q) {
    $product = explode('|', $q);
    $productsData[] = $product;
}

$countOrders = count($orders);

// Получаем айди товаров и количество в заказе.
$countOrdersProduct = \count($ordersProductData);
$productId = [];
// изменить имена переменных, например на $orderProductId, они ведь оттуда и берутся *
for ($i = 0; $i < $countOrdersProduct; $i++) {
    $orderProduct = $ordersProductData[$i];

    if ($orderProduct[1] === $_SESSION['user']['id']) {
        $productId[] = $orderProduct[2];
        $productCount[] = $orderProduct[3];
    }
}

$countProduct = \count($products);
for ($i = 0; $i < $countProduct; $i++) {
    $product = $productsData[$i];
    if ($productId[$i] == $product[0]) {
        $productPrice[] = $product[4];
    }
}

if (!\count($productPrice) == \count($productCount)) {
    echo 'Массивы не равны!';
} else {
    $count = \count($productPrice);
    for ($i = 0; $i < $count; $i++) {
        $totalPriceForOne[] = $productPrice[$i] * $productCount[$i];
    }
}

$totalPrice = \array_sum($totalPriceForOne);

$countProduct = \count($products);

for ($i = 0; $i < $countOrders; $i++) {
    $order = $ordersData[$i];
    if ($order[0] === $_SESSION['user']['id']) {
        echo "Айди заказа: {$order[1]}<br>";
        echo "Цена заказа: {$totalPrice}<br>";
        ?>
        <form action="order_show.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo $order[1]; ?>">
            <input type="submit" value="Показать содержимое">
        </form>

        <?php
    }
}