<?php
error_reporting(-1);
session_start();

if (!empty($_POST)){
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
    } elseif (!preg_match('/[A-Z]/', $user['password'])){
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
    // Проверка на уникальность логина 
    $files = glob('../users/*.txt'); 

    $username = $_POST['user_name']; 

    foreach ($files as $file) {
        $content = file_get_contents($file); 
        $users = unserialize($content);

        foreach ($users as $user) {
            if ($user['user_name'] === $username) {
                $_SESSION['usr_er'] = 'Ошибка: Данный пользователь уже зарегистрирован';
                die; 
            }
        }
    }
        if (!empty($msg)) {
            $_SESSION['msg'] = $msg;
        } else {
            // Проверка номера
            if (preg_match('/\+/', $user['number'])) {
                $user['number'] = preg_replace('/\+/', '', $user['number']);
            }
            if (!preg_match('/^(\d{1})(\d{10})$/', $user['number'])) {
                $user['number'] = '7' . $user['number'];
            }
            if (preg_match('/^8/', $user['number'])) {
                $user['number'] = preg_replace('/8/', '7', $user['number']);
            }

            if (!file_exists('../users')) {
                mkdir('users', 0777, true);
            }

            $string = implode('|', $user);
            $user = str_replace('|', '|', $string);

            // Работа файлов
            $directory = '../users';
            $files = glob($directory . '/*');
            usort($files, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });

            $latestFile = reset($files);
            preg_match('/(\d+)/', $latestFile, $matches);
            $lastCount = intval($matches[0]) + 1;
            $lastId = 'id' . $lastCount . '.txt';
            $fileName = '../users/' . $lastId;
            file_put_contents($fileName, $user);

            $_SESSION['msg'] = 'Регистрация завершена' . PHP_EOL;
        }
        header("Location: ../../views/registry.php");
        die;

}

?>
