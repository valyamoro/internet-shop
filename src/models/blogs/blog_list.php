<?php

$articlesItem = file('../storage_files/article.txt');

foreach ($articlesItem as $q) {
    $article = explode('|', $q);

    $articles[] = [
        'id' => $article['0'],
        'category' => $article['1'],
        'name' => $article['2'],
        'text' => $article['3']
    ];
}

