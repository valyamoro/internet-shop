<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

// Валидация пришедших данных из $_POST и $_FILES.
include __DIR__ . '/validation/validation_registry.php';

if (!empty($msg)) {
    $_SESSION['msg'] = $msg;
    header('Location: /reg-main.my/views/registry.php');
    die;
} else {
    // Замена 8 на 7, в случаи отсутствия 7 подставляется 7.
    $phone_number = str_replace(['+', '8'], '', $phone_number);
    if (strlen($phone_number) === 10 && substr($phone_number, 0, 1) !== '7') {
        $phone_number = '7' . $phone_number;
    }
    // Путь до файлов хранения различных данных.
    $pathDirectoryStorage = __DIR__ . '\..\..\..\storage_files';
    $pathDirectoryUpload = __DIR__ . '\..\..\..\uploads';
    $pathDirectoryUploadAvatar = __DIR__ . '\..\..\..\uploads\avatars\\';

    // Создаются новые директории, если их не существует.
    $itemsDirectory = [$pathDirectoryStorage, $pathDirectoryUploadAvatar, $pathDirectoryUpload];
    foreach ($itemsDirectory as $item) {
        if (!is_dir($item)) {
            mkdir($item, 0777, true);
        }
    }

    // Путь до файлов c данными пользователя.
    $usersDataFilePath = __DIR__ . '\..\..\..\storage_files\user.txt';
    $usersAvatarDataFilePath = __DIR__ . '\..\..\..\storage_files\user_way.txt';

    // Создаются новые файлы, если их не существует.
    $itemsFile = [$usersDataFilePath, $usersAvatarDataFilePath];
    foreach ($itemsFile as $item) {
        fclose(fopen($item, 'a+b'));
    }

    // Путь до файла с данными аватара пользователя.
    $filePath = $pathDirectoryUploadAvatar . uniqid() . $avatar['name'];
    // Загрузка аватара пользователя из временного файла в постоянный.
    move_uploaded_file($avatar['tmp_name'], $filePath);

    // Записываю путь до изображения в storage_files/user_way.txt .
    $filePath = '..\\' . strstr($filePath, 'src');

    // Получаем данные массивов всех пользователей из user.txt в виде строки.
    $dataUsers = file($usersDataFilePath, FILE_IGNORE_NEW_LINES);

    // Создаем идентификатор новому пользователю.
    $userId = $dataUsers ? (intval(explode('|', end($dataUsers))[0]) + 1) : 1;

    // Проверяем есть ли аккаунт с такой же почтой и телефоном в user.txt.
    $isUserExists = false;
    foreach ($dataUsers as $line) {
        $userData = explode('|', $line);
        if ($userData[2] === $email || $userData[4] === $phone_number) {
            $isUserExists = true;
            break;
        }
    }

    if ($isUserExists) {
        $_SESSION['msg'] = 'Пользователь с этими данными уже зарегистрирован!';
        header('Location: ../../views/registry.php');
        die;
    }

    // Блокировка файлов:
    $handlerDataUser = fopen($usersDataFilePath, 'a + b');

    if (!flock($handlerDataUser, LOCK_EX)) {
        $_SESSION['msg'] = 'Не удалось зарегистрироваться, повторите попытку позже!';
        die;
    } else {
        // Приходящие данные из $_POST.
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Формируем строку с данными пользователя.
        $userData = "{$userId}|{$user_name}|{$email}|{$password}|{$phone_number}";
        // Записываем данные пользователя в user.txt.
        fwrite($handlerDataUser, $userData . PHP_EOL);

        flock($handlerDataUser, LOCK_UN);
    }

    $handlerAvatar = fopen($usersAvatarDataFilePath, 'a + b');

    if (!flock($handlerAvatar, LOCK_EX)) {
        $_SESSION['msg'] = 'Не удалось зарегистрироваться, повторите попытку позже!';
        die;
    } else {
        // Формируем строку с данными о аватаре пользователя.
        $avatar = "{$userId}|{$filePath}";

        // Записываем данные об аватаре пользователя в user_way.txt.
        fwrite($handlerAvatar, $avatar . PHP_EOL);
        flock($handlerAvatar, LOCK_UN);
    }

    fclose($handlerDataUser);
    fclose($handlerAvatar);

    if (!empty($msg)) {
        $_SESSION['error'] = $msg;
        header('Location: ../../../views/register.php');
        die;
    } else {
        $_SESSION['msg'] = 'Регистрация успешно завершена!';
        header('Location: ../../../views/registry.php');
        die;
    }


}

