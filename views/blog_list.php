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
                <a href="?c=articles&a=view&id=<?=$article['id']; ?>">
                    Далее...
                </a>
            </td>
        </tr>
    </table>
    <hr>
<?php endforeach; ?>