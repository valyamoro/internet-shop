<?php
include '../src/models/blogs/blog_list.php';


?>



<?php foreach ($articles as $article): ?>
    <?= mb_strimwidth($article['text'], 0, 300, '...'); ?>
    <br>
    <table width="100%">
        <tr>
            <td width="70%">
                <b>Категория:</b> <i><?= $article['category']; ?></i>
            </td>
            <td width="30%">
                <form action="blog_show.php" method="post">
                    <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                    <input type="submit" value="Подробнее">
                </form>
            </td>
        </tr>
    </table>
    <hr>
<?php endforeach; ?>