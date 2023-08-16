<?php

declare(strict_types=1);
error_reporting(-1);
session_start();

if (!empty($_POST)) {

    $msg = false;

    $user['email'] = $_POST['email'];
    $user['password'] = $_POST['password'];

    // Валидация почты и пароля

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
    if (empty($msg)) {
        $user = [];
        foreach ($_POST as $key => $val) {
            $user[$key] = htmlspecialchars(strip_tags(trim($val)));
        }

        // Инициализируем почту и пароль

        $email = $user['email'];
        $password = md5($user['password']);

        // Забираем данные всех пользователей из users.txt

        $fileUsers = file('../users/users.txt');
        $users = explode('|', $fileUsers);

        foreach ($fileUsers as $line) {
            $userData = explode('|', $line);
            $users[] = $userData;
        }

        // Перебор пользователей, получаем нужную строку
        // Проверяем все данные из $_POST с данными из файла
        $flagAuth = false;

        foreach ($users as $user) {
            $user['id'] = $user[0];
            $user['username'] = $user[1];
            $user['email'] = $user[2];
            $user['password'] = $user[3];
            $user['phone'] = $user[4];

            if ($email == $user['email'] && $password == $user['password']) {
                $flagAuth = true; // Если почта с паролем совпадает
                break;
            }
        }

        if ($flagAuth) {
            // Запись данных в сессию
            $_SESSION['msg'] = 'Вы авторизировались!';
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
//              'password' => $user['password'],
                'phone' => $user['phone'],
            ];
            header('Location: ../../index.php');
            die;
        } else {
            $_SESSION['msg'] = 'Вы не авторизировались!';
            header('Location: ../../views/login.php');
            die;
        }
    } else {
        $_SESSION['msg'] = $msg;
        header('Location: ../../index.php');
        die;
    }
}







