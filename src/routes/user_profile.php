<?php

$username = $_GET['name'];

$pathUsersData = __DIR__ . '\..\..\storage_files\user.txt';
$pathUsersWay = __DIR__ . '\..\..\storage_files\user_way.txt';

$dataUsers = file($pathUsersData, FILE_IGNORE_NEW_LINES); // поменять на users *

$filteredUsers = array_filter($dataUsers, function ($q) use ($username) {
    $user = explode('|', $q);
    return $user[1] === $username;
});


if (!empty($filteredUsers)) {
    $foundUser = explode('|', reset($filteredUsers));
}

$dataWayToAvatar = file($pathUsersWay, FILE_IGNORE_NEW_LINES);

$currentId = $foundUser[0];

$filteredAvatarUsers = array_filter($dataWayToAvatar, function ($q) use ($currentId) {
    $user = explode('|', $q);
    return $user[0] === $currentId;
});

if (!empty($filteredAvatarUsers)) {
    $foundUserAvatar = explode('|', reset($filteredAvatarUsers));
}


$user['id'] = $foundUser[0];
$user['name'] = $foundUser[1];
$user['email'] = $foundUser[2];
$user['phone'] = $foundUser[4];
$user['avatar'] = $foundUserAvatar[1];


