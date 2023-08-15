<div>
    <?php if(!empty($_SESSION['msg'])): ?>
        <?php print_r($_SESSION['msg']); ?>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    <?php if(!empty($_SESSION['usr_er'])): ?>
        <?php echo nl2br($_SESSION['usr_er']); ?>
        <?php unset($_SESSION['usr_er']); ?>
    <?php endif; ?>
    <form action="../src/models/login_handler.php" method="post">
        Email <input type="text" name="email"><br>
        Password <input type="text" name="password"><br>
        <input type="submit" value="Отправить">
    </form>
</div>