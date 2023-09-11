<?php

session_start();
$db_handler = 'product.txt';

// Получаю данные о товарах.
$dataProducts = file($db_handler, FILE_IGNORE_NEW_LINES);

//foreach ($dataProducts as $q) {
//    $productData = explode('|', $q);
//    $products[] = $productData;
//}

$incomingProduct = array_filter($dataProducts, function ($q) {
    $product = explode('|', $q);
    return $product[0] == $_GET['code'];
});

$currentProduct = explode('|', reset($incomingProduct));

//$action = $_GET['action'];

if (!empty($_GET['action']))
    switch ($_GET['action']) {
        case "add":
            if (!empty($_POST["quantity"])) {

                $itemArray = array(
                    $currentProduct[0] => array(
                        'name' => $currentProduct[2],
                        'code' => $currentProduct[0],
                        'quantity' => $_POST["quantity"],
                        'price' => $currentProduct[4],
                        'image' => $currentProduct[5]
                    )
                );

                if (!empty($_SESSION["cart_item"])) {
                    if (in_array($currentProduct[0], array_keys($_SESSION["cart_item"]))) {
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            if ($currentProduct[0] == $k) {
                                if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                                    $_SESSION["cart_item"][$k]["quantity"] = 0;
                                }
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                            }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["code"] == $k) {
                        unset($_SESSION["cart_item"][$k]);
                    }
                    if (empty($_SESSION["cart_item"])) {
                        unset($_SESSION["cart_item"]);
                    }
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;

    }
?>

    <div id="shopping-cart">
        <div class="txt-heading">Shopping Cart</div>

        <a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
        <?php
        if(isset($_SESSION["cart_item"])){
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
                    $item_price = $item["quantity"]*$item["price"];
                    ?>
                    <tr>
                        <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
                        <td><?php echo $item["code"]; ?></td>
                        <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                        <td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
                        <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
                        <td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
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
            <?php
        } else {
            ?>
            <div class="no-records">Your Cart is Empty</div>
            <?php
        }
        ?>
    </div>

<?php
foreach ($dataProducts as $q) {
    $productData = explode('|', $q);
    $products[] = $productData;
}

if (empty($products)) {
    echo 'Файл с продуктами пуст';
    die;
} else {
    foreach ($products as $key => $value) {
        $productData = explode('|', $q);
        $products[] = $productData;
        ?>

        <div class="product-item">
            <form method="post" action="index.php?action=add&code=<?php echo $products[$key][0]; ?>">
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