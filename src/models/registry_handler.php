<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

if (!empty($_POST)) {
    $msg = false;
    $user = [];
    foreach ($_POST as $key => $val) {
        $user[$key] = htmlspecialchars(strip_tags(trim($val)));
    }


    // Валидация пришедших данных
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

    if (!empty($msg)) {
        $_SESSION['msg'] = $msg;
    } else {
        // Изменение номера перед отправкой в файл.

        $user['phone_number'] = str_replace(['+', '8'], '', $user['phone_number']);
        if (strlen($user['phone_number']) === 10 && substr($user['phone_number'], 0, 1) !== '7') {
            $user['phone_number'] = '7' . $user['phone_number'];
        }

        // Инициализируем данные пользователя

        $name = $user['user_name'];
        $email = $user['email'];
        $password = md5($user['password']);
        $phone = $user['phone_number'];
        $profileImage = $_FILES['avatar'];

        // Прописываем пути до файлов и директорий

        $fileUsersData = __DIR__ . '\usersData\users.txt';
        $fileUsersWay = __DIR__ . '\users\uploads\usersWay.txt';

        $directoryUsersData = __DIR__ . '\usersData';
        $directoryUsers = __DIR__ . '\users';
        $directoryUsersWay = __DIR__ . '\users\uploads\avatars\\';

        // Создаем все нужные файлы и директории, если их не существует

        $itemsDirectory = [$directoryUsersData, $directoryUsersWay];
        foreach ($itemsDirectory as $item) {
            if (!is_dir($item)) {
                mkdir($item, 0777, true);
            }
        }

        $itemsFile = [$fileUsersData, $fileUsersWay];
        foreach ($itemsFile as $item) {
            fclose(fopen($item, 'a+b'));
        }


        // Загружаем фото в профиль

        // Инициализируем данные аватарки

        if (isset($_FILES['avatar'])) {
            $file = $_FILES['avatar'];

            // Проверка ошибок загрузки
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Получение информации о файле
                $fileName = $file['name'];
                $fileSize = $file['size'];
                $fileTmp = $file['tmp_name'];
                $fileType = $file['type'];

                // Проверка типа файла (пример для изображений)
                $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['msg'] = 'Недопустимый тип файла.';
                    header('Location: ../../views/registry.php');
                    die;
                }

                // Проверка размера файла (пример для изображений, максимальный размер 1 МБ)
                $maxFileSize = 1 * 1024 * 1024;
                if ($fileSize > $maxFileSize) {
                    $_SESSION['msg'] = "Размер файла превышает допустимый.";
                    header('Location: ../../views/registry.php');
                    die;
                }
                $fileName = uniqid() . $fileName;
                // Переместить файл в папку назначения
                if (move_uploaded_file($fileTmp, $directoryUsersWay . $fileName)) {
                    $savePath = $directoryUsersWay . $fileName;

                    $savePath = strstr($savePath, 'src');
                    $savePath = '..\\' . $savePath;

                    // Перемещаем загруженный файл в указанное место
                    $_SESSION['msg'] = 'Файл успешно загружен!';
                } else {
                    $_SESSION['msg'] = 'Ошибка при перемещении файла.';
                    header('Location: ../../views/registry.php');
                    die;
                }
            } else {
                $_SESSION['msg'] = 'Ошибка загрузки файла: ' . $file['error'];
                header('Location: ../../views/registry.php');
                die;
            }
        }

        // Инициализируем данные всех пользователей

        $dataUsers = file($fileUsersData, FILE_IGNORE_NEW_LINES);

        // Получаем айди пользователя
        $newId = $dataUsers ? intval(count($dataUsers)) + 1 : $newId = 1;
        // Проверяем данные нового пользователя, если они уже есть то не регистрруем

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
        // Создаем строку с данными пользователя:
        $userData = "{$newId}|{$name}|{$email}|{$password}|{$phone}";
        if (!empty($savePath)) {
            $userAva = "{$newId}|{$savePath}";
        }

        // Блокировка файла:
        $handlerData = fopen($fileUsersData, 'a + b') or die('Не удалось открыть файл');
        $handlerAva = fopen($fileUsersWay, 'a + b') or die('Не удалось открыть файл путей');
        if (flock($handlerData, LOCK_EX)) {
            fwrite($handlerData, $userData . PHP_EOL);
            if (!empty($savePath)) {
                fwrite($handlerAva, $userAva . PHP_EOL);
            }
            flock($handlerData, LOCK_UN);
        } else {
            echo "Не удалось получить блокировку файла.";
        }
        fclose($handlerData);
        $_SESSION['msg'] = 'Регистрация успешно завершена!';

        header('Location: ../../views/registry.php');

    }
}
