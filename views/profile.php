<?php

include '../src/routes/user_profile.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
<ul>
    <li><a href="../index.php">Хоум</a></li>
    <li><a href="login.php">Вход</a></li>
    <li><a href="registry.php">Регистрация</a></li>
    <li style="float:right"><a class="active" href="../src/models/Auth/logout_handler.php">Выход</a></li>
</ul>
</body>
<body>
<form>
    <a href="#"><?= $user['name'] ?></a>
    <a href="#"><?= $user['phone'] ?></a>
    <a href="#"><?= $user['email'] ?></a>
    <img width="200" height="200" src="<?= $user['avatar']?>" alt="">
</form>
</body>
</html>
