<?php

/*
 * handling requests to cart
 * product_id-size-cut-quantity-subtotal
 * 
 */
/*
 */

session_start();

if (!isset($_POST["action"]) || $_POST["action"] == "") {//header ("Location: /index.php");return;
}
if ($_POST["action"] == "add") {
    add_to_cart();
}
if ($_POST["action"] == "remove") {
    remove_from_cart();
}
if ($_POST["action"] == "update") {
    update_cart();
}

function add_to_cart() {
    //if already in cart then increment number and update subtotal
    //if not in cart then add it and update subtotal
    $error = "";
    if (!isset($_POST["product_id"]) || !isset($_POST["size"]) || !isset($_POST["cut"])) {
        $error = "invalid request";
        $cart = null;
        $item_count = 0;
    } else {
        include (__DIR__ . "/class/class.product.php");
        $product = new product();
        //$product->id = $_POST["product_id"];
        $product->select($_POST["product_id"]);
        if ($product->id == null) {
            $error = "invalid request";
            $cart = null;
            if (isset($_SESSION["item_count"]))
                $item_count = $_SESSION["item_count"];
            else
                $item_count = 0;
        }
        else {

            $product_id = $product->id;
            $product_title = $product->title;
            $size = $_POST["size"];
            $cut = $_POST["cut"];
            $quantity = 1;
            $price = $product->price;
            $found = false;
            if (isset($_SESSION["cart"])) {
                $cart = $_SESSION["cart"];
                $item_count = $_SESSION["item_count"];
                foreach ($cart as $key => $cart_item) {
                    if ($cart_item["product_id"] == $product_id && $cart_item["size"] == $size && $cut == $cart_item["cut"]) {
                        $cart[$key]["quantity"]+= $quantity;
                        $cart[$key]["subtotal"] = $cart[$key]["quantity"] * $cart[$key]["price"];
                        $_SESSION["subtotal"] += $quantity * $cart[$key]["price"];
                        $found = true;
                    }
                }
                if (!$found) {
                    $cart[] = array("product_id" => $product_id, "product_title" => $product_title, "quantity" => $quantity, "size" => $size, "cut" => $cut, "price" => $price,
                        "subtotal" => $price * $quantity, "subtotal" => $price * $quantity);
                    $_SESSION["subtotal"] += $quantity * $price;
                    $item_count += 1;
                }
            } else {
                $item_count = 0;
                $cart = array();
                $cart[] = array("product_id" => $product_id, "product_title" => $product_title, "quantity" => $quantity, "size" => $size, "cut" => $cut, "price" => $price,
                    "subtotal" => $price * $quantity, "subtotal" => $price * $quantity);
                $_SESSION["subtotal"] = $quantity * $price;
                $item_count = 1;
            }
        }
    }
    $_SESSION["cart"] = $cart;
    $_SESSION["item_count"] = $item_count;
    $cart_content = cart_content();
    $json_response = json_encode(array("cart_content" => $cart_content, "subtotal" => $_SESSION["subtotal"], "item_count" => $_SESSION["item_count"], "error" => $error));
    echo($json_response);
}

function remove_from_cart() {
    //if the cart is empty return
    //if the product isn't there return
    //remove the product
    //empty the cart
    //retrun 
    $product_id = (int) $_POST["product_id"];
    $size = $_POST["size"];
    $cut = $_POST["cut"];
    $error = null;
    if (!isset($_SESSION["cart"]) || $_SESSION["cart"] == null)
        $error = "cart is empty";
    else {
        $subtotal = $_SESSION["subtotal"];
        $item_count = $_SESSION["item_count"];
        $cart = $_SESSION["cart"];
        foreach ($cart as $key => $cart_item) {
            if ($cart_item["product_id"] == $product_id && $cart_item["size"] == $size && $cut == $cart_item["cut"]) {
                $subtotal -= $cart[$key]["subtotal"];
                unset($cart[$key]);
            }
        }
        $_SESSION["cart"] = $cart;
        $_SESSION["subtotal"] = $subtotal;
        $_SESSION["item_count"]-= 1;
    }
    $json_response = json_encode(array("item_count" => $_SESSION["item_count"], "error" => $error));
    print_r($json_response);
}

function update_cart() {
    //if cart is empty return
    //if product isn't there return
    //update the product quantity in cart
    //update subtotal
    //return
    $subtotal = 0;
    $item_count = 0;
    $quantity = $_POST["quantity"];
    $product_id = $_POST["product_id"];
    $size = $_POST["size"];
    $cut = $_POST["cut"];
    $cart = null;
    $error = null;
    if ($quantity < 0 || !is_numeric($quantity))
        $error = "quantity cannot be negative";
    else
    if ($quantity == 0)
    {remove_from_cart();
    return;
    }
    else
    if (!isset($_SESSION["cart"])) {
        $error = "cart is empty";
        $cart = array();
    } else {
        $cart = $_SESSION["cart"];
        $item_count = $_SESSION["item_count"];
        $key = array_search(array("size"=>$size, "cut"=>$cut, "product_id"=>$product_id), $cart, false);
        
        //foreach ($cart as $key => $cart_item) {
            //if ($cart_item["product_id"] == $product_id && $cart_item["size"] == $size && $cut == $cart_item["cut"]) {
                //$cart[$key]["quantity"] = $quantity;
                //$cart[$key]["subtotal"] = $quantity * $cart[$key]["price"];
            //}
            $subtotal += $cart[$key]["quantity"] * $cart[$key]["price"];
            $item_count += 1;
        //}
    }
    $_SESSION["cart"] = $cart;
    $_SESSION["subtotal"] = $subtotal;
    $_SESSION["item_count"] = $item_count;
    $json_response = json_encode(array("quantity" => $quantity, "subtotal" => $subtotal, "item_count" => $item_count, "error" => $error));
    print_r($json_response);
}

function cart_content() {
// return cart contents as a string of HTML elements

    $output = "";
    $cart = $_SESSION["cart"];
    foreach ($cart as $cart_item) {
        $output .= $cart_item["size"] . $cart_item["cut"] . $cart_item["price"] . $cart_item["quantity"];
    }
    return $output;
}
?>

