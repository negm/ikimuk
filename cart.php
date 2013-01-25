<?php

/*
 * View the cart content and remove/update items
 */
session_start();
if (!isset($_SESSION["cart"]))
{
    $cart = null;
    $item_count = 0;
    
}
else
{
    $cart = $_SESSION["cart"];
    $item_count = $_SESSION["item_count"];
    $subtotal = $_SESSION["subtotal"];
}
if ($cart == null)
{
    //the cart is empty
}
else
{
    foreach ($cart as $key =>$item)
    {
        print_r($item);
    }
}
?>
