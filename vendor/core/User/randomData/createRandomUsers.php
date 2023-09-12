<?php
$filename = 'users.txt';
$numberOfUsers = 1000000;

$handle = fopen($filename, 'w');

for ($i = 1; $i <= $numberOfUsers; $i++) {
    $id = $i;
    $nickname = generateRandomString(8);
    $email = 'user' . $i . '@example.com';
    $password = password_hash(generateRandomString(10), PASSWORD_DEFAULT);
    $phoneNumber = generateRandomPhoneNumber();

    $line = "$id|$nickname|$email|$password|$phoneNumber\n";
    fwrite($handle, $line);
}

fclose($handle);

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function generateRandomPhoneNumber() {
    return '7' . mt_rand(100000000, 999999999);
}
