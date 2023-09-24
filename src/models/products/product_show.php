<?php

$productId = $_POST['product_id'];
$products = file('../../../storage_files/product.txt');
// Нужно получить 4 элемент массива

foreach ($products as $q) {
    $product = explode('|', $q);
    $productsData[] = $product;
}


$countProduct = \count($products);
for ($i = 0; $i < $countProduct; $i++) {
    $product = $productsData[$i];
    if ($productId == $product[0]) {
        $productName[] = $product[2];
        $productCategory[] = $product[1];
        $productPrice[] = $product[4];
    }
}

echo "Категория товара: {$productCategory[0]}<br>";
echo "Название товара: {$productName[0]}<br>";
echo "Цена Товара: {$productPrice[0]}<br>";
?>
    <form action="order_show.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $order[1]; ?>">
        <input type="submit" value="Добавить в корзину!">
    </form>

<?php
