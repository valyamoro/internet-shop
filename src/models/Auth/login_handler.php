<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

// Валидация пришедных данных из $_POST.
include __DIR__ . '/validation/validation_login.php';

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

    // Получаю строку с данными найденного пользователя.
    $approvedUsers = array_filter($dataUsers, function ($q) use ($email, $password) {
        $user = explode('|', $q);
        return $user[2] === $email && password_verify($password, $user[3]);
    });

    if (empty($approvedUsers)) {
        $_SESSION['msg'] = 'Неверные данные';
        header('Location: login.php');
        die;
    } else {
        // Разбиваю строку с данными пользователя на элементы массива
        $currentUser = explode('|', reset($approvedUsers));
    }

    // Получаю данные из user_way.txt .
    $avatarData = file($pathUsersWay, FILE_IGNORE_NEW_LINES);

    // Получаю айди текущего пользователя.
    $currentId = $currentUser[0];

    // Получаю путь до аватара текущего пользователя.
    $approvedAvatarUsers = array_filter($avatarData, function ($q) use ($currentId) {
        $user = explode('|', $q);
        return $user[0] === $currentId;
    });

    // Разбиваю строку с данными аватара пользователя на элементы массива.
    $currentUserAvatar = explode('|', reset($approvedAvatarUsers));

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


