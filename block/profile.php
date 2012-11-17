<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include 'block/logged_in.php';
include "class/class.user.php";
$user = new user();
$user->select($_SESSION["user_id"]);
$user->name ;           
$user->email ;          
$user->validated_mobile;
$user->image;        
$user->points;          

$user->getPreorderHistory($_SESSION["user_id"]);
if ($user->database->rows >0)
{
    //Show the preorder history
    echo '<div id="products_container">';
    while ($row = mysqli_fetch_assoc($user->database->result))
    {
    /*
     * $row ( [id] => 1 [user_id] => 1 [product_id] => 1 [phone] => 913874013983 
     * [country] => Lebanon [region] => Beirut [address] => lhwdiokhalchsdjkasldujkl [size] => xxl [price] => 10 
     * [newsletter] => 1 [status_id] => 1 [last_modified] => 2012-11-09 00:00:00 [title] => Test img 
     * [artist_id] => 1 [competition_id] => 1 [shop] => 0 [desc] => test [preorders] => 0 [views] => 0 
     * [primary] => 1 [rollover] => 0 [url] => img/artist.png [status] => active competition [product_title] => Test 1 )
     */
    }
    echo '</div>';
}
else
{//show the other view
   echo '<div><p>You have not made any activity yet</p><a href="index.php">Check our latest designs</a></div>';
}
?>
