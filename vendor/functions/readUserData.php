<?php
function readUserData($fileName) {
    $data = file_get_contents($fileName);
    $lines = explode("\n", $data);
    $userData = [];

    foreach ($lines as $line) {
        $fields = explode('|', $line);
        if (count($fields) === 5) {
            $userData[] = [
                'id' => $fields[0],
                'username' => $fields[1],
                'email' => $fields[2],
                'password' => $fields[3],
                'phone' => $fields[4],
            ];
        }
    }

    return $userData;
}