<?php
$idArticle = $_POST['article_id'];

$articlesData = file('../storage_files/article.txt');

foreach ($articlesData as $q) {
    $article = explode('|', $q);

    if ($idArticle == $article['0']) {
        $articles = [
            'id' => $article['0'],
            'category' => $article['1'],
            'name' => $article['2'],
            'text' => $article['3']
        ];
    }

}
?>

<h1>Статья с id <?=$_POST['article_id']; ?> </h1>
<hr>
<?= $articles['text']; ?>
<hr>
<a href="<?=$_SERVER['HTTP_REFERER']; ?>">Назад</a>
<?php

$commentData = file('../storage_files/comment.txt');

foreach ($commentData as $q) {
    $comment = explode('|', $q);
    if ($comment[1] == $idArticle) {
        $commentInfo[] = [
            'id' => $comment['0'],
            'idArticle' => $comment['1'],
            'username' => $comment['2'],
            'text' => $comment['3']
        ];
    }
}
?>
<!--<form action="?c=addComment&a=index&id="--><?php //= $idArticle ?><!-- method="POST">-->
<!--    Ник <input type="text" name="user"> <br>-->
<!--    Комментарий <input type="text" name="comment"> <br>-->
<!--    <input type="submit" value="Отправить">-->
<!--    </form>-->
<?php
foreach ($commentInfo as $comment) {
    echo '<br>';
    echo "Ник: {$comment['username']} <br>";
    echo "Комментарий: {$comment['text']} <br>";
    echo '---------------------------';
}

?>