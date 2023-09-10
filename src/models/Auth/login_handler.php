<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

// Валидация пришедных данных из $_POST.
include 'validation/validation_login.php';

if (!empty($msg)) {
    $_SESSION['msg'] = $msg;
    header('Location: ../../../views/login.php');
    die;
} else {
    // Путь до файлов хранения данных пользователя.
    $pathUsersData = __DIR__ . '\..\..\..\storage_files\user.txt';
    $pathUsersWay = __DIR__ . '\..\..\..\storage_files\user_way.txt';

    // Получаю данные всех пользователей в виде строк.
    $dataUsers = file($pathUsersData, FILE_IGNORE_NEW_LINES);

    // Получаю элемент массива с данными ввиде строки авторизированного пользователя.
    $approvedUsers = array_filter($dataUsers, function ($q) use ($email, $password) {
        $user = explode('|', $q);
        return $user[2] === $email && password_verify($password, $user[3]);
    });

    // Разбиваю строку на элементы массивов, создавая двумерный массив.
    if (!empty($approvedUsers)) {
        $currentUser = explode('|', reset($approvedUsers));
    } else {
        $_SESSION['msg'] = 'Неверные данные';
        header('Location: login.php');
        die;
    }
    // Получаю данные из user_way.txt .
    $avatarData = file($pathUsersWay, FILE_IGNORE_NEW_LINES);

    // Получаю текущий айди авторизированного пользователя.
    $currentId = $currentUser[0];

    // Получаю айди и путь до аватарки авторизированного пользователя.
    $approvedAvatarUsers = array_filter($avatarData, function ($q) use ($currentId) {
        $user = explode('|', $q);
        return $user[0] === $currentId;
    });

    // Разбиваю строку с данными пользователя на элементы массива.
    $currentUserAvatar = explode('|', reset($approvedAvatarUsers));

    // Если нет данных о пути до аватара, то первый элемент массива $currentUserAvatar определяется как not_found

    // Записываю в сессию пользователя.
    $_SESSION['msg'] = 'Вы авторизировались!';
    $_SESSION['user'] = [
        'id' => $currentUser[0],
        'name' => $currentUser[1],
        'email' => $currentUser[2],
        'phone' => $currentUser[4],
        'avatar' => $currentUserAvatar[1],
    ];

    // Завершение авторизации.
    header('Location: ../../../views/my_profile.php');
    die;

}


