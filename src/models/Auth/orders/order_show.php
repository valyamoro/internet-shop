<?php
include __DIR__ . '/../../../../debug/debug_functions/functions.php';
include __DIR__ . '/help_functions.php';

// СОДЕРЖИМОЕ ЗАКАЗА
session_start();

$ordersProduct = file('../../../../storage_files/order_product.txt');
// Нужно получить второй элемент массива, а через него цену продукта

foreach ($ordersProduct as $q) {
    $orderProduct = explode('|', $q);
    $ordersProductData[] = $orderProduct;
}

$countOrdersProduct = \count($ordersProductData);
$orderId = $_POST['order_id'];

foreach ($ordersProductData as $orderProduct) {
    $orderProductId = $orderProduct[0];

    if ($orderProductId == $orderId) {
        $productId[] = $orderProduct[2];
        $productCount[] = $orderProduct[3];
    }
}

$products = file('../../../../storage_files/product.txt');
// Нужно получить 4 элемент массива

foreach ($products as $q) {
    $product = explode('|', $q);
    $productsData[] = $product;
}

$countProduct = \count($products);
for ($i = 0; $i < $countProduct; $i++) {
    $product = $productsData[$i];
    if ($productId[$i] == $product[0]) {
        $productId[] = $product[0];
        $productName[] = $product[2];
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

$productCount = count($productCount);
for ($i = 0; $i < $productCount; $i++) {
        echo "Название товара: {$productName[$i]}<br>";
        echo "Цена одного товара: {$productPrice[$i]}<br>";
        echo "Цена всех товаров: {$totalPriceForOne[$i]}<br>";
        ?>
        <form action="../../products/product_show.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $productId[$i]; ?>">
            <input type="submit" value="Показать товар">
        </form>
        <?php
}

echo "Общая цена: {$totalPrice}";