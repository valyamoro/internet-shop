<?php

$username = $_GET['name'];

$pathUsersData = __DIR__ . '\..\..\storage_files\user.txt';
$pathUsersWayData = __DIR__ . '\..\..\storage_files\user_way.txt';

$dataUsers = file($pathUsersData, FILE_IGNORE_NEW_LINES);
// поменять на users *

$approvedUsers = array_filter($dataUsers, function ($q) use ($username) {
    $user = explode('|', $q);
    return $user[1] === $username;
});


if (!empty($approvedUsers)) {
    $approvedUser = explode('|', reset($approvedUsers));
}

$avatarData = file($pathUsersWayData, FILE_IGNORE_NEW_LINES);

$currentId = $approvedUser[0];

$approvedUserAvatar = array_filter($avatarData, function ($q) use ($currentId) {
    $user = explode('|', $q);
    return $user[0] === $currentId;
});

if (!empty($approvedUserAvatar)) {
    $approvedUserAvatar = explode('|', reset($approvedUserAvatar));
}


$user['id'] = $approvedUser[0];
$user['name'] = $approvedUser[1];
$user['email'] = $approvedUser[2];
$user['phone'] = $approvedUser[4];
$user['avatar'] = $approvedUserAvatar[1];

