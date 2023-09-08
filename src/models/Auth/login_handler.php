<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

if (!empty($_POST)) {

    $msg = false;
    $user = [];
    // Записываю в массив $user приходящие данные из $_POST.
    foreach ($_POST as $key => $val) {
        $user[$key] = htmlspecialchars(strip_tags(trim($val)));
    }

    // Валидация почты и пароля.

    if (empty($user['email'])) {
        $msg .= 'Заполните поле почты' . PHP_EOL;
    } elseif (!preg_match("/[0-9a-z]+@[a-z]/", $user['email'])) {
        $msg .= 'Почта содержит недопустимые данные' . PHP_EOL;
    }

    if (empty($user['password'])) {
        $msg .= 'Заполните поле пароль' . PHP_EOL;
    }

    if (!empty($msg)) {
        $_SESSION['error'] = $msg;
        header('Location: ../../views/login.php');
        die;
    } else {
        // Путь до файлов хранения данных пользователя.
        $pathUsersData = __DIR__ . '\..\..\..\storage_files\user.txt';
        $pathUsersWay = __DIR__ . '\..\..\..\storage_files\user_way.txt';

        // Получаю данные всех пользователей в виде строк.
        $dataUsers = file($pathUsersData, FILE_IGNORE_NEW_LINES);

        // Приходящие данные из $_POST
        $email = $user['email'];
        $password = $user['password'];

        // Получаю элемент массива с данными ввиде строки авторизированного пользователя.
        $filteredUsers = array_filter($dataUsers, function ($q) use ($email, $password) {
            $user = explode('|', $q);
            $verifyPassword = password_verify($password, $user[3]);
            return $user[2] === $email && $verifyPassword === true;
        });

        // Разбиваю строку на элементы массивов, создавая двумерный массив.
        if (!empty($filteredUsers)) {
            $foundUser = explode('|', reset($filteredUsers));
        } else {
            $_SESSION['msg'] = 'Неверные данные';
            header('Location: login.php');
            die;
        }

        // Получаю айди и пути до аватарок всех пользователей в виде строк.
        $dataWayToAvatar = file($pathUsersWay, FILE_IGNORE_NEW_LINES);

        // Получаю текущий айди авторизированного пользователя.
        $currentId = $foundUser[0];

        // Получаю айди и путь до аватарки авторизированного пользователя.
        $filteredAvatarUsers = array_filter($dataWayToAvatar, function ($q) use ($currentId) {
            $user = explode('|', $q);
            return $user[0] === $currentId;
        });

        // Разбиваю строку на элементы массивов, создавая двумерный массив.
        if (!empty($filteredAvatarUsers)) {
            $foundUserAvatar = explode('|', reset($filteredAvatarUsers));
        } else {
            $_SESSION['msg'] = 'Неверные данные';
            header('Location: ../../../views/login.php');
            die;
        }

        // Инициализирую все данные пользователя.
        $user['id'] = $foundUser[0];
        $user['name'] = $foundUser[1];
        $user['email'] = $foundUser[2];
        $user['phone'] = $foundUser[4];
        $user['avatar'] = $foundUserAvatar[1];

        // Записываю в сессию пользователя.
        $_SESSION['msg'] = 'Вы авторизировались!';
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'avatar' => $user['avatar'],
        ];

        // Завершение авторизации.
        header('Location: ../../../views/profile.php');
        die;

    }

}
