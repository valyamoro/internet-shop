<?php
$currentProduct = explode('|', reset($incomingProduct));

if (!empty($_GET['action']))
    switch ($_GET['action']) {
        case "add":
            if (isset($_POST["quantity"]) && is_numeric($_POST["quantity"]) && $_POST["quantity"] > 0) {
                $quantity = intval($_POST["quantity"]);
                $code = $currentProduct[0];

                if (isset($_SESSION["cart_item"][$code])) {
                    // Товар с таким кодом уже есть в корзине
                    $_SESSION["cart_item"][$code]["quantity"] += $quantity;
                } else {
                    // Товара с таким кодом нет в корзине, добавляем его
                    $_SESSION["cart_item"][$code] = array(
                        'name' => $currentProduct[2],
                        'code' => $code,
                        'quantity' => $quantity,
                        'price' => $currentProduct[4],
                        'image' => $currentProduct[5],
                    );
                }
            }
            break;
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $key => $value) {
                    $quantity = intval($_GET["quantity"]);
                    $code = $_GET['code'];
                    if ($value["code"] == $code) {
                        if ($value["quantity"] > 1) {
                            // Уменьшаем количество товара на 1
                            $_SESSION["cart_item"][$key]["quantity"] -= $quantity;
                        } elseif($value['quantity'] <= 1) {
                            // Если количество товара равно 1, удаляем его из корзины
                            unset($_SESSION["cart_item"][$key]);
                        }
                    }
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;

    }
