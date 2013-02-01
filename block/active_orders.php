<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER["DOCUMENT_ROOT"]."/class/class.user.php";
include $_SERVER["DOCUMENT_ROOT"]."/class/class.preorder.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/class/settings.php";
//{header("Location: /index.php");}
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
     print_r($row);
     echo '<div class="row">';
     echo '<a class="span3 thumb-big" href="../design.php?product_id='.$row["product_id"].'"> <img src="'.$row["url"].'" alt="'.$row["product_title"].'" /></a>';
     echo '<';
     echo '</div>';
    }
    echo '</div>';
}
else
{//show the other view
   echo '<div><p>You have not made any activity yet</p><a href="/index.php">Check our latest designs</a></div>';
}
?>
