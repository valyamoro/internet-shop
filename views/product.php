<?php
session_start();

if (!$_SESSION['user']) {
    die;
}
include '../src/models/products/product_handler.php';
include '../src/models/products/basket_handler.php';
include '../src/models/products/pagination.php';


?>

<div id="shopping-cart">
    <div class="txt-heading">Shopping Cart</div>

    <a id="btnEmpty" href="product.php?action=empty">Empty Cart</a>
    <?php
    if(!empty($_SESSION["cart_item"])){
        $total_quantity = 0;
        $total_price = 0;
        ?>
        <table class="tbl-cart" cellpadding="10" cellspacing="1">
            <tbody>
            <tr>
                <th style="text-align:left;">Name</th>
                <th style="text-align:left;">Code</th>
                <th style="text-align:right;" width="5%">Quantity</th>
                <th style="text-align:right;" width="10%">Unit Price</th>
                <th style="text-align:right;" width="10%">Price</th>
                <th style="text-align:center;" width="5%">Remove</th>
            </tr>
            <?php
            foreach ($_SESSION["cart_item"] as $item){
                $item_price = $item["quantity"] * $item["price"];
                ?>
                <tr>
                    <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
                    <td><?php echo $item["code"]; ?></td>
                    <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                    <td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
                    <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
                    <td style="text-align:center;"><a href="product.php?action=remove&code=<?php echo $item["code"]; ?>&quantity=1" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>

                </tr>
                <?php
                $total_quantity += $item["quantity"];
                $total_price += ($item["price"]*$item["quantity"]);
            }
            ?>

            <tr>
                <td colspan="2" align="right">Total:</td>
                <td align="right"><?php echo $total_quantity; ?></td>
                <td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <form action="../src/models/Auth/orders/order_handler.php">
            <input type="submit">Подтвердить</input>
        </form>
        <?php
    } else {
        ?>
        <div class="no-records">Your Cart is Empty</div>
        <?php
    }
    ?>
</div>

<?php

if (empty($products)) {
    echo 'Файл с продуктами пуст';
    die;
} else {
    foreach ($products as $key => $value) {
        $productData = explode('|', $q);
        $products[] = $productData;
        ?>

        <div class="product-item">
            <form method="post" action="product.php?action=add&code=<?php echo $products[$key][0]; ?>">
                <div class="product-image"><img src="<?php echo $products[$key][5]; ?>"></div>
                <div class="product-tile-footer">
                    <div class="product-title"><?php echo $products[$key][2] ?></div>
                    <div class="product-count"><?php echo $products[$key][3]; ?></div>
                    <div class="product-price"><?php echo "$".$products[$key][4] ?></div>
                    <div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
                </div>
            </form>
        </div>
        <?php
    }
}
?>

<!--ПАГИНАЦИЯ-->
<?php if ($totalPages > 1): // Проверяем, есть ли больше одной страницы для пагинации ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo ($currentPage - 1); ?>">Предыдущая страница</a>
        <?php endif ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span><?php echo $i; ?></span>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif ?>
        <?php endfor ?>
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo ($currentPage + 1); ?>">Следующая страница</a>
        <?php endif ?>
    </div>
<?php endif; ?>
</body>
</html>


