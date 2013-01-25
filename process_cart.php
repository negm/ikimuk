<?php

/*
 * handling requests to cart
 * product_id-size-cut-quantity-subtotal
 * 
 */
/*
$array = array(array("12" => "green", "54" => "brown", "672" => "blue"), array("12" => "green", "54" => "brown", "672" => "blue"));
foreach ($array as $key => $value)
    if ($value["54"] == "brown")
        $array[$key]["54"] = "waiiiiiir";
  $array[]=  array("12" => "green", "54" => "brown", "672" => "blue");  
 print_r($array);
 $array[]=  array("12" => "green", "54" => "brown", "672" => "blue");  
 print_r($array);
 */

session_start();

if (!isset($_POST["action"]) || $_POST["action"] =="")
{//header ("Location: /index.php");return;
    
}
if ($_POST["action"]== "add")
{
    add_to_cart();
}
if ($_POST["action"]== "remove")
{
    remove_from_cart();
}
if ($_POST["action"]== "update")
{
    update_cart();
}
function add_to_cart()
{
    //if already in cart then increment number and update subtotal
    //if not in cart then add it and update subtotal
    $found = false;
    if (isset($_SESSION["cart"]))
    {
       $cart = $_SESSION["cart"];
     foreach ($cart as $key=> $cart_item)
    {
     if ($cart_item["product_id"] == $product_id && $cart_item["size"] == $size && $cut == $cart_item["cut"])
     {
         $cart[$key]["quantity"]+= $quantity;
         $cart[$key]["subtotal"] = $quantity*$cart[$key]["price"];
         $_SESSION["subtotal"] += $quantity*$cart[$key]["price"];
         $found = true;        
     }
    }
     if (!$found)
    {
        $cart[] = array ("product_id"=> $product_id, "quantity"=> $quantity,"size"=>$size, "cut"=>$cut, "price"=>$price,
            "subtotal"=>$price*$quantity, "subtotal"=>$price*$quantity );
        $_SESSION["subtotal"] += $quantity*$price;
    }
    $_SESSION["itme_count"] += 1;
    }
    else{
        $cart = array();
        $cart[] = array ("product_id"=> $product_id, "quantity"=> $quantity,"size"=>$size, "cut"=>$cut, "price"=>$price,
            "subtotal"=>$price*$quantity, "subtotal"=>$price*$quantity );
        $_SESSION["subtotal"] = $quantity*$price;
        $_SESSION["itme_count"] = 1;
    }
   
    
}
function remove_from_cart()
{
    //if the cart is empty return
    //if the product isn't there return
    //remove the product
    //empty the cart
    //retrun 
    
    if(!isset($_SESSION["cart"]))
        $error = "cart is empty";
    else
        {
    $subtotal = $_SESSION["subtotal"];
    $item_count = $_SESSION["item_count"];
    $cart= $_SESSION["cart"];
    foreach ($cart as $key=> $cart_item)
    {
     if ($cart_item["product_id"] == $product_id && $cart_item["size"] == $size && $cut == $cart_item["cut"])
     {
         $subtotal -= $cart[$key]["subtotal"];
         unset($cart[$key]);
      }
    }
    $_SESSION["cart"]= $cart;
    $_SESSION["subtotal"]= $subtotal;
    $_SESSION["item_count"]-= 1;
        }
}
function update_cart()
{
    //if cart is empty return
    //if product isn't there return
    //update the product quantity in cart
    //update subtotal
    //return
    $subtotal = 0;
    $item_count = 0;
    if ($quantity < 0)
        $error = "quantity cannot be negative";
    else 
       if ($quantity == 0)
        remove_from_cart ();
    else 
        if(!isset($_SESSION["cart"]))
        {$error = "cart is empty";
         $cart=array();
        }
        else{
             $cart= $_SESSION["cart"];
             $subtotal = $_SESSION["subtotal"];
             $item_count=$_SESSION["item_count"];
             foreach ($cart as $key => $cart_item)
             {
               if ($cart_item["product_id"] == $product_id && $cart_item["size"] == $size && $cut == $cart_item["cut"])
                   {
                   $cart[$key]["quantity"] = $quantity;
                   $cart[$key]["subtotal"]=$quantity*$cart[$key]["price"];
                   }
               $subtotal += $cart[$key]["quantity"]* $cart[$key]["price"];
               $item_count += 1;
             }
            }
    $_SESSION["cart"]= $cart;
    $_SESSION["subtotal"] = $subtotal;
    $_SESSION["item_count"]= $item_count;
    $cart_content = cart_content();
    $json_response = json_encode( array("cart_content" =>$cart_content, "subtotal"=>$subtotal,"item_count"=>$item_count, "error"=>$error));
}

function cart_content()
{
// return cart contents as a string of HTML elements
    
    $output = "";
    $cart= $_SESSION["cart"];
    foreach ($cart as $cart_item)
    {
     $output .= "";
    }
    
}

?>

