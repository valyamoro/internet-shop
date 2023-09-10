<?php
$msg = false;

extract($_POST);

// Валидация почты и пароля.

if (empty($email)) {
    $msg .= 'Заполните поле почты' . PHP_EOL;
} elseif (!preg_match("/[0-9a-z]+@[a-z]/", $email)) {
    $msg .= 'Почта содержит недопустимые данные' . PHP_EOL;
}

if (empty($password)) {
    $msg .= 'Заполните поле пароль' . PHP_EOL;
}
