<div>
    <?php require '../src/models/registry_handler.php' ?> 
    <?php if(!empty($_SESSION['msg'])): ?>
        <?php echo nl2br($_SESSION['msg']); ?>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    <form action="../src/models/registry_handler.php" method="post">
        Name <input type="text" name="user_name"><br>
        Email <input type="text" name="email"><br>
        Password <input type="text" name="password"><br>
        Number <input type="text" name="number"><br>
        <input type="submit" value="Отправить">
    </form>
</div>
