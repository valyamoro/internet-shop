<?php

error_reporting(-1);
session_start();

if (!empty($_POST)) {
    $msg = false;
    $user = [];
    foreach ($_POST as $key => $val) {
        $user[$key] = htmlspecialchars(strip_tags(trim($val)));
    }
    $email = $user['email'];
    $password = md5($user['password']);



    // Сначала я ищу через email id пользователя, затем через id я получаю всю строку
    // Ищем айди по email

    $path = '../users/users.txt';

    $fileContent = file_get_contents($path);

    $lines = explode("\n", $fileContent);
    $id = null;
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if ($parts[2] == $email && $parts[3] == $password) {
            $id = $parts[0];
            print_r$id);
        }
    }


    // Ищем остальные данные по айди
    $number = $id; // Цифра, с которой строки должны начинаться
    $result = '';

    foreach ($lines as $line) {
        if (strpos($line, $number . '|') === 0) {
//          $_SESSION['msg'] = $result .= $line;
            $_SESSION['user'] = [
                "email" => $user['email'],
                "password" => $user['password'],
            ];
//            header('Location: ../../index.php');
//            die;
        }
    }
    // Надо будет записать все данные в сессию









//    if (vse_ok) {
//        ЗАПИСЫВАЮ АБСОЛЮТНО ВСЕ ДАННЫЕ В СЕССИЮ;
//    }
}
    ?>
<div>
    <?php if(!empty($_SESSION['msg'])): ?>
        <?php echo nl2br($_SESSION['msg']); ?>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    <?php if(!empty($_SESSION['usr_er'])): ?>
        <?php echo nl2br($_SESSION['usr_er']); ?>
        <?php unset($_SESSION['usr_er']); ?>
    <?php endif; ?>
    <form method="post">
        Email <input type="text" name="email"><br>
        Password <input type="text" name="password"><br>
        <input type="submit" value="Отправить">
    </form>
</div>





