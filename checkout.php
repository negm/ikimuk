<?php

/*
 * Final step befor submitting the order to Audi
 * The user would enter Shipping details and that would determine 
 * the shupping cost
 */
session_start();
include (__DIR__."/class/class.product.php");
$product = new product();
if(isset($_SESSION["cart"]))
    validate_cart_items ();
else
    $_SESSION["cart"]=null;

function validate_cart_items()
{
    $subtotal = 0;
    $item_count = 0;
    $cart = $_SESSION["cart"];
    $product = new product();
     foreach ($cart as $key=> $cart_item)
    {
     $product->id = $cart_item["product_id"];
     $product->select($cart_item["product_id"]);
     if ($product->id == null)
     {
         unset($cart[$key]);
     }
     else
     {
         $cart[$key]["price"]= $product->price;
         $cart[$key]["subtotal"]= $product->price*$cart_item["quantity"];
         $cart[$key]["product_title"]=$product->title;
         $subtotal += $cart[$key]["subtotal"];
         $item_count += 1;
     }
    }
    $_SESSION["cart"]= $cart;
    $_SESSION["item_count"]=$item_count;
    $_SESSION["subtotal"]=$subtotal;
}

?>
