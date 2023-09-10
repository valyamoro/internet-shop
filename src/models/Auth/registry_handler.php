<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

if (!empty($_POST)) {

    $msg = false;
    $user = [];

    extract($_POST);
    extract($_FILES);

    // Валидация пришедших данных из $_POST и $_FILES.
    if (empty($user_name)) {
        $msg .= 'Заполните поле имя' . PHP_EOL;
    } elseif (preg_match('#[^а-яa-z]#ui', $user_name)) {
        $msg .= 'Имя содержит недопустимые символы' . PHP_EOL;
    } elseif (mb_strlen($user_name) > 15) {
        $msg .= 'Имя содержит больше 15 символов' . $user_name . PHP_EOL;
    } elseif (mb_strlen($user_name) <= 3) {
        $msg .= 'Имя содержит менее 4 символов' . $user_name . PHP_EOL;
    }

    if (empty($email)) {
        $msg .= 'Заполните поле почты' . PHP_EOL;
    } elseif (!preg_match("/[0-9a-z]+@[a-z]/", $email)) {
        $msg .= 'Почта содержит недопустимые данные' . PHP_EOL;
    }

    if (empty($password)) {
        $msg .= 'Заполните поле пароль' . PHP_EOL;
    } elseif (!preg_match('/^(?![0-9]+$).+/', $password)) {
        $msg .= 'Пароль не должен содержать только цифры' . PHP_EOL;
    } elseif (!preg_match('/^[^!№;]+$/u', $password)) {
        $msg .= 'Пароль содержит недопустимые символы' . PHP_EOL;
    } elseif (!preg_match('/^(?![A-Za-z]+$).+/', $password)) {
        $msg .= 'Пароль не должен состоять только из букв' . PHP_EOL;
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $msg .= 'Пароль должен содержать минимум одну заглавную букву' . PHP_EOL;
    } elseif (strlen($password) <= 5) {
        $msg .= 'Пароль содержит меньше 5 символов ' . PHP_EOL;
    } elseif (strlen($password) > 15) {
        $msg .= 'Пароль больше 15 символов ' . PHP_EOL;
    }

    if (empty($phone_number)) {
        $msg .= 'Заполните поле номер' . PHP_EOL;
    } elseif (!preg_match('/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/',
        $phone_number)) {
        $msg .= 'Некоректный номер' . $phone_number . PHP_EOL;
    }

    // Инициализация одобреднных данных файла.
    $maxFileSize = 1 * 1024 * 1024;
    $allowedExtensions = ['jpeg', 'png', 'gif', 'webp', 'jpg'];
    // Получаю расширение пришедшего из $_FILES файла.
    $extension = pathinfo($avatar['name'], PATHINFO_EXTENSION);

    if (empty($avatar['name'])) {
        $msg .= 'Аватар обязателен.';
    } elseif (!in_array($extension, $allowedExtensions)) {
        $msg .= 'Недопустимый тип файла.';
    } elseif ($avatar['size'] > $maxFileSize) {
        $msg .= 'Размер файла превышает допустимый.';
    }

}

if (!empty($msg)) {
    $_SESSION['msg'] = $msg;
    header('Location: /reg-main.my/views/login.php');
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

    // Путь до файлов хранения данных пользователя.
    $usersData = __DIR__ . '\..\..\..\storage_files\user.txt';
    $usersAvatarData = __DIR__ . '\..\..\..\storage_files\user_way.txt';

    // Создаются новые файлы, если их не существует.
    $itemsFile = [$usersData, $usersAvatarData];
    foreach ($itemsFile as $item) {
        fclose(fopen($item, 'a+b'));
    }

    // Инициализации информации о пришедшем файле. Временный путь до файла и название файла.
    $tmpName = $avatar['tmp_name'];
    
    // Путь до места хранения аватара пользователя.
    $filePath = $pathDirectoryUploadAvatar . uniqid() . $avatar['name'];
    // Загрузка аватара пользователя из временного файла в постоянный.
    move_uploaded_file($tmpName, $filePath);

    // Инициализация пути в storage_files/user_way.txt .
    $filePath = '..\\' . strstr($filePath, 'src');

    // Получаем данные массивов всех пользователей из user.txt в виде строки.
    $dataUsers = file($usersData, FILE_IGNORE_NEW_LINES);

    // Создаем идентификатор новому пользователю.
    $userId = $dataUsers ? (intval(explode('|', end($dataUsers))[0]) + 1) : 1; // вернул обратно end *

    // Приходящие данные из $_POST.

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

    // Блокировка файла:
    $handlerDataUser = fopen($usersData, 'a + b');

    if (!flock($handlerDataUser, LOCK_EX)) {
        $_SESSION['msg'] = 'Не удалось зарегистрироваться, повторите попытку позже!';
        die;
    } else {
        // Приходящие данные из $_POST.
        $name = $user_name;
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Формируем строку с данными пользователя.
        $userData = "{$userId}|{$name}|{$email}|{$password}|{$phone_number}";
        // Записываем данные пользователя в user.txt.
        fwrite($handlerDataUser, $userData . PHP_EOL);

        flock($handlerDataUser, LOCK_UN);

    }

    $handlerAvatar = fopen($usersAvatarData, 'a + b');

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

