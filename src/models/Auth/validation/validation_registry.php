<?php
declare(strict_types=1);
error_reporting(-1);
session_start();

$msg = false;

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

