<?php
function binarySearchUserData($userData, $userName, $password) {
    $lowIndex = 0;
    $highIndex = count($userData) - 1;

    while ($lowIndex <= $highIndex) {
        $middleIndex = ($lowIndex + $highIndex) / 2;
        $user = $userData[$middleIndex];

        if ($user['username'] == $userName && $user['password'] == $password) {
            return $user;
        } elseif ($user['username'] > $userName) {
            $highIndex = $middleIndex - 1;
        } else {
            $lowIndex = $middleIndex + 1;
        }
    }
    return null;
}