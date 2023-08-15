<?php

error_reporting(-1);
session_start();

if (!empty($_POST)) {
    $msg = false;
    $user = [];
    foreach ($_POST as $key => $val) {
        $user[$key] = htmlspecialchars(strip_tags(trim($val)));
    }

    // Инициализируем почту и пароль

    $email = $user[$_POST['email']]; 
    // Забираем данные из users.txt

    $file = file_get_contents('../users/users.txt');
    $users = explode('|', $file);



    // Сравниваем данные из пост со строками из users.txt








//    if (vse_ok) {
//        ЗАПИСЫВАЮ АБСОЛЮТНО ВСЕ ДАННЫЕ В СЕССИЮ;
//    }
}
    ?>
<div>
    <?php if(!empty($_SESSION['msg'])): ?>
        <?php echo nl2br($_SESSION['msg']); ?>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    <?php if(!empty($_SESSION['usr_er'])): ?>
        <?php echo nl2br($_SESSION['usr_er']); ?>
        <?php unset($_SESSION['usr_er']); ?>
    <?php endif; ?>
    <form method="post">
        Email <input type="text" name="email"><br>
        Password <input type="text" name="password"><br>
        <input type="submit" value="Отправить">
    </form>
</div>





