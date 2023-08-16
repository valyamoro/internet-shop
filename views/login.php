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
    <title>Авторизация</title>
</head>
    <body>
        <div>
            <form action="../src/models/login_handler.php" method="post">
                Email <input type="text" name="email" placeholder="Введите почту"><br>
                Password <input type="password" name="password" placeholder="Введите пароль"><br>
                <input type="submit" value="Отправить">
                <p>
                   У вас нет аккаунта? - <a href="registry.php">зарегистрируйтесь</a>!
                </p>
                    <?php if (!empty($_SESSION['msg'])): ?>
                        <?php echo '<p class="msg"> ' . nl2br($_SESSION['msg']) . ' </p>'; ?>                   
                        <?php unset($_SESSION['msg']); ?>
                    <?php endif; ?>
            </form>
        </div>
    </body>
</html>
