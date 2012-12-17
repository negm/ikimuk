<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include "class/class.user.php";
include "class/class.preorder.php";
include "class/settings.php";
{header("Location: index.php");}
$user = new user();
$preorder = new preorder();
$user->id = $_SESSION["user_id"];
$user->select();
$preorder->activePreorders($user->id);

echo $preorder->database->rows;
if ($preorder->database->rows > 0)
{
    //Show the preorder history
    
    echo '<div id="products_container" class="container">';
    while ($row = mysqli_fetch_assoc($preorder->database->result))
    {
     echo '<div class="row">';
     echo '<a class="span3 thumb-big" href="../design.php?product_id='.$row["product_id"].'"> <img src="'.$row["url"].'" alt="'.$row["product_title"].'" /></a>';
     echo '<';
     echo '</div>';
    //print_r($row);
    /*
      [id] => 5 [user_id] => 1 [product_id] => 6 [phone] =>
     *  96179148999 [country] => Lebanon [region] => Beirut 
     * [address] => lasdklashasdklh [size] => XXL [price] => 0 
     * [newsletter] => 1 [status_id] => 1 [comments] => 
     * [last_modified] => 2012-12-11 09:34:28 [product_title] => VEDGZILLA 
     */
    }
    echo '</div>';
}
else
{//show the other view
   echo '<div><p>You have not made any activity yet</p><a href="/index.php">Check our latest designs</a></div>';
}
?>
