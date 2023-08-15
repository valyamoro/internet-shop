<?php

error_reporting(-1);
session_start();

if (!empty($_POST)) {
    $msg = false;
    $user = [];
    foreach ($_POST as $key => $val) {
        $user[$key] = htmlspecialchars(strip_tags(trim($val)));
    }

    // Инициализируем почту и пароль

    $email = $user['email'];
    $password = $user['password'];

    // Забираем данные из users.txt

    $file = file('../users/users.txt');
    $users = explode('|', $file);

    foreach ($file as $line) {
        $userData = explode('|', $line);
        $users[] = $userData;
    }

    // Перебор пользователей, получаем нужную строку
    // Проверяем все данные из $_POST с данными из файла

    foreach ($users as $user) {
        if ($email == $user[2] && $password == $user[3]) {
            $_SESSION['msg'] = 'Вы авторизировались!';
            $_SESSION['user'] = [
                'id' => $user[0],
                'username' => $user[1],
                'email' => $user[2],
                'phone' => $user[4],
            ];
            header('Location: ../../index.php'); 
            break;
        } else {
            $_SESSION['msg'] = 'Вы не авторизировались!';
            $flagAuth = false;
        }
    }

}







