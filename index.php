<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<ul>
    <li><a href="index.php">Хоум</a></li>
    <li><a href="views/login.php">Вход</a></li>
    <li><a href="views/registry.php">Регистрация</a></li>
    <li style="float:right"><a class="active" href="src/models/Auth/logout_handler.php">Выход</a></li>
</ul>
</body>

<?php
// Массив с данными продуктов (ваша база данных или какие-то тестовые данные)
$products = array(
    array('id' => 1, 'name' => 'Продукт 1', 'price' => 10.99),
    array('id' => 2, 'name' => 'Продукт 2', 'price' => 19.99),
    array('id' => 3, 'name' => 'Продукт 3', 'price' => 5.99),
);

// Проверяем, что запрос идет к URL "/person/products"
if ($_SERVER['REQUEST_URI'] === '/person/products') {
    // Устанавливаем заголовок для ответа в формате JSON
    header('Content-Type: application/json');

    // Отправляем данные в формате JSON
    echo json_encode($products);
} else {
    // Возвращаем ошибку 404, если URL не соответствует ожидаемому
    http_response_code(404);
    echo json_encode(array('message' => 'Страница не найдена'));
}

