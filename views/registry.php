<?php

session_start();

if ($_SESSION['user']) {
    header('Location: ../index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title>Регистрация</title>
</head>
    <body>
        <div>
            <?php if(!empty($_SESSION['usr_er'])): ?>
                <?php echo nl2br($_SESSION['usr_er']); ?>
                <?php unset($_SESSION['usr_er']); ?> 
            <?php endif; ?> 
            <form action="../src/models/registry_handler.php" method="post" enctype="multipart/form-data">
                Name <input type="text" name="user_name" placeholder="Введите Логин"><br>
                Email <input type="text" name="email" placeholder="Введите почту"><br>
                Password <input type="password" name="password" placeholder="Введите пароль"><br>
                Number <input type="text" name="phone_number" placeholder="Введите номер телефона"><br>
                Фото <input type="file" name="avatar">
                <input type="submit" value="Отправить">
            </form>
            <p>
                У вас уже есть аккаунт? - <a href="login.php">авторизируйтесь</a>!
            </p>
            <?php if(!empty($_SESSION['msg'])): ?>
                <?php echo '<p class="msg"> ' . nl2br($_SESSION['msg']) . ' </p>'; ?>                   
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>
        </div>
    </body>
</html>


