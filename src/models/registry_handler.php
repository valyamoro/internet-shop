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
        $msg .= 'Имя содержит менее 3 символов' . $user['name'] . PHP_EOL;
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

    if (empty($user['number'])) {
        $msg .= 'Заполните поле номер' . PHP_EOL;
    } elseif (!preg_match('/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/', $user['number'])) {
        $msg .= 'Некоректный номер' . $user['number'] . PHP_EOL;
    }

    if (!empty($msg)) {
        $_SESSION['msg'] = $msg;
    } else {
        // Изменение номера перед отправкой в файл.
        if (strpos($user['number'], '+') !== false) {
            $user['number'] = str_replace('+', '', $user['number']);
        } elseif (strlen($user['number']) === 10 && substr($user['number'], 0, 1) !== '7') {
            $user['number'] = '7' . $user['number'];
        } elseif (substr($user['number'], 0, 1) === '8') {
            $user['number'] = '7' . substr($user['number'], 1);
        }

        // Инициализируем данные пользователя

        $name = $user['user_name'];
        $email = $user['email'];
        $password = md5($user['password']);
        $number = $user['number'];

        // Прописываем относительные пути до файлов

        $fileUsersData = __DIR__ . '\usersData\users.txt';
        $directoryUsers = __DIR__ . 'models\users';

        // Создаем папку users, если ее нету

        if (!file_exists($directoryUsers)) {
            mkdir('usersData', 0777, true);
        }

        // Создаем все нужные файлы, если их не существует

        $items = [$fileUsersData];
        foreach ($items as $item) {
            !file_exists($item) && file_put_contents($item, '');
        }

        // Создаем новый айди пользователю

        $dataUsers = file($fileUsersData, FILE_IGNORE_NEW_LINES);

        $newId = !empty($dataUsers) ? (explode('|', end($dataUsers))[0] + 1) : 1;

        // Проверяем данные нового пользователя, если они уже есть то не регистрируем

        $isUserExists = false;
        foreach ($dataUsers as $line) {
            $userData = explode('|', $line);
            if ($userData[1] === $name || $userData[2] === $email || $userData[4] === $number) {
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

        $userData = "{$newId}|{$name}|{$email}|{$password}|{$number}";

        // Блокировка файла:
        $file = fopen($fileUsersData, 'a');
        if (flock($file, LOCK_EX)) {
            fwrite($file, $userData . PHP_EOL);
            flock($file, LOCK_UN);
        } else {
            echo "Не удалось получить блокировку файла.";
        }

        fclose($file);
        $_SESSION['msg'] = 'Регистрация успешно завершена!';

        header('Location: ../../views/registry.php');

    }
}
