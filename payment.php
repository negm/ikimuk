<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER["DOCUMENT_ROOT"]."/class/settings.php";
include $_SERVER["DOCUMENT_ROOT"]."/class/class.ip2nationcountries.php";
include $_SERVER["DOCUMENT_ROOT"]."/class/class.order.php";
include $_SERVER["DOCUMENT_ROOT"]."/class/class.order_details.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/block/enums.php";
$settings = new settings();
session_start();
$order_id = place_order();
if (!$order_id)
{
   header("Location: /checkout.php");
}
else
{
    $return_url = "www.ikimuk.bet.com/process_payment.php/xrf=".$_SESSION["csrf_code"];
    $vpc_secure = strtoupper(md5($settings->audi_access_code));
    echo $vpc_secure.'<br>'.$_SESSION["total"];
    
    
}
function place_order()
{
    global $size_enum, $cut_enum;
    $order= new order();  
    $order_details = new order_details();
    $country = new ip2nationcountries();
    $country->country_code = $_POST["country"];
    $country->select();
    $subtotal = 0;
    if ($country->country_code == null)
    {
        return false;
    }
    $order->user_id = $_SESSION["user_id"];
    $order->country = $_POST["country"];
    $order->region = $_POST["region"];
    $order->address = $_POST["address"];
    $order->phone = $_POST["phone"];
    $order->status_id = 2;
    $order->newsletter = isset($_POST["newsletter"])? 1:0;
    $order->insert();
    if(!$order->id)
    {
       return false;
    }
    else
    {
    $cart = $_SESSION["cart"];
    foreach ($cart as $key=>$cart_item)
    {
        //print_r($cart_item);
        $order_details->order_id = $order->id;
        $order_details->price = $cart_item["price"];
        $order_details->product_id = $cart_item["product_id"];
        $order_details->quantity = $cart_item["quantity"];
        $order_details->size = isset($size_enum[$cart_item["size"]]) ? $size_enum[$cart_item["size"]]:0;
        $order_details->cut = isset($cut_enum[$cart_item["cut"]]) ? $cut_enum[$cart_item["cut"]]:0;
        $subtotal+= $cart_item["price"]*$cart_item["quantity"];
        $order_details->insert();   
    }
    }
   $_SESSION["total"]= $subtotal + $country->delivery_charge;
   return $order->id;
}
?>
