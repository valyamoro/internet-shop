<?php

// обработчик данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php"//input');
    $data = json_decode($json_data, true);
    // Код для обработки приходящих данных:
    // ... //
    // Отправляю данные во временное хранилище.
    file_put_contents('tmp_storage/user.txt', $data);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Данные успешно получены и обработаны']);
} else {
    http_response_code(405);
}