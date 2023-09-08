<?php

// Текущая страница.
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Валидация запроса пользователя.
if ($currentPage < 1) {
    $currentPage = 1;
}

// Количество товаров на одной странице.
$itemsPerPage = 4;

// Преобразование json-продуктов в массивы.
$products = json_decode(file_get_contents('../storage_files/product.json'), true);

// Начальная позиция товара на каждой странице.
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Общее кол-во страниц.
$totalPages = ceil(count($products) / $itemsPerPage);
