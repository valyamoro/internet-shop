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
    // Записываю в массив $user приходящие данные из $_FILES.
    foreach ($_FILES as $key => $val) {
        $user[$key] = $val;
    }

    // Валидация пришедших данных из $_POST и $_FILES.
    if (empty($user['user_name'])) {
        $msg .= 'Заполните поле имя' . PHP_EOL;
    } elseif (preg_match('#[^а-яa-z]#ui', $user['user_name'])) {
        $msg .= 'Имя содержит недопустимые символы' . PHP_EOL;
    } elseif (mb_strlen($user['user_name']) > 15) {
        $msg .= 'Имя содержит больше 15 символов' . $user['name'] . PHP_EOL;
    } elseif (mb_strlen($user['user_name']) <= 3) {
        $msg .= 'Имя содержит менее 4 символов' . $user['name'] . PHP_EOL;
    }

    if (empty($user['email'])) {
        $msg .= 'Заполните поле почты' . PHP_EOL;
    } elseif (!preg_match("/[0-9a-z]+@[a-z]/", $user['email'])) {
        $msg .= 'Почта содержит недопустимые данные' . PHP_EOL;
    }

    if (empty($user['password'])) {
        $msg .= 'Заполните поле пароль' . PHP_EOL;
    } elseif (!preg_match('/^(?![0-9]+$).+/', $user['password'])) {
        $msg .= 'Пароль не должен содержать только цифры' . PHP_EOL;
    } elseif (!preg_match('/^[^!№;]+$/u', $user['password'])) {
        $msg .= 'Пароль содержит недопустимые символы' . PHP_EOL;
    } elseif (!preg_match('/^(?![A-Za-z]+$).+/', $user['password'])) {
        $msg .= 'Пароль не должен состоять только из букв' . PHP_EOL;
    } elseif (!preg_match('/[A-Z]/', $user['password'])) {
        $msg .= 'Пароль должен содержать минимум одну заглавную букву' . PHP_EOL;
    } elseif (strlen($user['password']) <= 5) {
        $msg .= 'Пароль содержит меньше 5 символов ' . PHP_EOL;
    } elseif (strlen($user['password']) > 15) {
        $msg .= 'Пароль больше 15 символов ' . PHP_EOL;
    }

    if (empty($user['phone_number'])) {
        $msg .= 'Заполните поле номер' . PHP_EOL;
    } elseif (!preg_match('/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/',
        $user['phone_number'])) {
        $msg .= 'Некоректный номер' . $user['phone_number'] . PHP_EOL;
    }

    // Инициализация одобреднных данных файла.
    $maxFileSize = 1 * 1024 * 1024;
    $allowedExtensions = ['jpeg', 'png', 'gif', 'webp', 'jpg'];
    // Получаю расширение пришедшего из $_FILES файла.
    $extension = pathinfo($user['avatar']['name'], PATHINFO_EXTENSION);

    if (empty($user['avatar'])) {
        $msg .= 'Аватар обязателен.';
    } elseif (!in_array($extension, $allowedExtensions)) {
        $msg .= 'Недопустимый тип файла.';
    } elseif ($user['avatar']['size'] > $maxFileSize) {
        $msg .= 'Размер файла превышает допустимый.';
    }

}

    if (!empty($msg)) {
        $_SESSION['msg'] = $msg;
        header('Location: /reg-main.my/views/login.php');
        die;
    } else {
        // Замена 8 на 7, в случаи отсутствия 7 подставляется 7.
        $user['phone_number'] = str_replace(['+', '8'], '', $user['phone_number']);
        if (strlen($user['phone_number']) === 10 && substr($user['phone_number'], 0, 1) !== '7') {
            $user['phone_number'] = '7' . $user['phone_number'];
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
        $pathUsersData = __DIR__ . '\..\..\..\storage_files\user.txt';
        $pathUsersWay = __DIR__ . '\..\..\..\storage_files\user_way.txt';

        // Создаются новые файлы, если их не существует.
        $itemsFile = [$pathUsersData, $pathUsersWay];
        foreach ($itemsFile as $item) {
            fclose(fopen($item, 'a+b'));
        }

        // Инициализации информации о пришедшем файле. Временный путь до файла и название файла.
        $tmpName = $user['avatar']['tmp_name'];
        $avatarName = $user['avatar']['name'];

        // Путь до места хранения аватара пользователя.
        $filePath = $pathDirectoryUploadAvatar . uniqid() . $user['avatar']['name'];
        // Загрузка аватара пользователя из временного файла в постоянный.
        move_uploaded_file($tmpName, $filePath);

        // Инициализация пути в storage_files/user_way.txt .
        $filePath = '..\\' . strstr($filePath, 'src'); // Тут уже новые пути, поменять *

        // Получаем данные массивов всех пользователей из user.txt в виде строки.
        $dataUsers = file($pathUsersData, FILE_IGNORE_NEW_LINES);

        // Создаем идентификатор новому пользователю.
        $userId = $dataUsers ? (intval(explode('|', end($dataUsers))[0]) + 1) : 1; // вернул обратно end *

        // Приходящие данные из $_POST.
        $email = $user['email'];
        $phone = $user['phone_number'];

        // Проверяем есть ли аккаунт с такой же почтой и телефоном в user.txt.
        $isUserExists = false;
        foreach ($dataUsers as $line) {
            $userData = explode('|', $line);
            if ($userData[2] === $email || $userData[4] === $phone) {
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
        $handlerDataUser = fopen($pathUsersData, 'a + b') or die('Не удалось открыть файл');

        $handlerAvatar = fopen($pathUsersWay, 'a + b') or die('Не удалось открыть файл путей');

        // Приходящие данные из $_POST.
        $name = $user['user_name'];
        $password = password_hash($user['password'], PASSWORD_DEFAULT);

        if (flock($handlerDataUser, LOCK_EX)) {
            $userData = "{$userId}|{$name}|{$email}|{$password}|{$phone}";
            fwrite($handlerDataUser, $userData . PHP_EOL);
            if (!empty($filePath)) {
                $avatar = "{$userId}|{$filePath}";
                fwrite($handlerAvatar, $avatar . PHP_EOL);
            }
            flock($handlerDataUser, LOCK_UN);
        } else {
            $msg = 'Ошибка регистрации, повторите позже...';
            error_log('Не удалось получить блокировку файла', 3, 'error_log.txt');
        }

        fclose($handlerDataUser);

        if (!empty($msg)) {
            $_SESSION['error'] = $msg;
            header('Location: ../../../views/register.php');
            die;
        }

        $_SESSION['msg'] = 'Регистрация успешно завершена!';
        header('Location: ../../../views/registry.php');
        die;
    }

