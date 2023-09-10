<?php
//include '../src/models/errors/error_handler.php';
// Текущая страница.
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Валидация запроса пользователя.
if ($currentPage < 1) {
    $currentPage = 1;
}

// Количество товаров на одной странице.
$itemsPerPage = 4;

// Путь до товаров.

$pathProductData = __DIR__ . '/../../../storage_files/product.txt';

// Получаю данные о товарах.
$dataProducts = file($pathProductData, FILE_IGNORE_NEW_LINES);

foreach ($dataProducts as $q) {
    $productData = explode('|', $q);
    $products[] = $productData;
}

// Начальная позиция товара на каждой странице.
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Общее кол-во страниц.
$totalPages = ceil(count($products) / $itemsPerPage);

foreach ($products as &$product) {
    $product['id'] = $product[0];
    $product['image'] = $product[6];
    $product['name'] = $product[1];
    $product['count'] = $product[5];
    $product['description'] = $product[2];
    $product['price'] = $product[4];
}

