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
        echo '<p>Количество: ' . $product['count'] . '</p>';
        echo '</div>';
        echo '<form action="../src/controllers/BasketController.php" method="post">
                    <input type="submit" name="id_product" value="Добавить товар в козину">
              </form>';
        echo '----------------------------------';
    }



    ?>
    <?php if ($totalPages > 1): // Проверяем, есть ли больше одной страницы для пагинации ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo ($currentPage - 1); ?>">Предыдущая страница</a>
        <?php endif ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span><?php echo $i; ?></span>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif ?>
        <?php endfor ?>
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo ($currentPage + 1); ?>">Следующая страница</a>
        <?php endif ?>
    </div>
    <?php endif; ?>
</body>
</html>

