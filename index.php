<?php

session_start();

if (!$_SESSION['user']) {
    header('Location: views/login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <form>
        <a href="#"><?= $_SESSION['user']['username'] ?></a>
        <a href="#"><?= $_SESSION['user']['phone'] ?></a>
        <a href="#"><?= $_SESSION['user']['email'] ?></a>
        <a href="src/models/logout_handler.php" class="logout">Выход</a>
    </form> 
</body>
</html>