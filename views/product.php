<?php
include '../src/models/products/product_handler.php';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
    for ($i = $startIndex; $i < min($startIndex + $itemsPerPage, count($products)); $i++) {
        $product = $products[$i];
        echo '<div class="product">';
        echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
        echo '<h2>' . $product['name'] . '</h2>';
        echo '<p>' . $product['description'] . '</p>';
        echo '<p>Цена: ' . $product['price'] . '</p>';
        echo '</div>';
        echo '----------------------------------';
    }



    // Выведите ссылки для перехода между страницами
    echo '<div class="pagination">';
    if ($currentPage > 1) {
        echo '<a href="?page=' . ($currentPage - 1) . '">Предыдущая страница</a>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            echo '<span>' . $i . '</span>';
        } else {
            echo '<a href="?page=' . $i . '">' . $i . '</a>';
        }
    }
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '">Следующая страница</a>';
    }
    echo '</div>';

    ?>
</body>
</html>

