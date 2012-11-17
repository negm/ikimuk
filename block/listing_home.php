<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "Awesome t-shirts designed by you!";
require_once "class/class.product.php";
require_once "class/class.image.php";
require_once 'class/settings.php';
$product = new product();
$product->CurrentCompetitionDesigns();
$image= new image();
$settings = new settings();
include "block/header.php";
include "block/top_area.php";
include "block/breadcrumb.php";
while($row= mysqli_fetch_assoc($product->database->result))
{
    $image->product_id = $row["id"];
    $image->getBasicImages();
    while ($row_image = mysqli_fetch_assoc($image->database->result))
    {
        if ($row_image["primary"])
        $primary = $row_image["url"];
        if ($row_image["rollover"])
        $rollover = $row_image["url"];
    }
    echo '<div class="home_list span3"><a class="home_list" href="design.php?product_id='.$row["id"].'" ><img class="thumbnail" src="'.$primary.'" data-hover="'.$rollover.'" /></a></div>';
    echo '<div class="preorderButton"><a id="'.$row["id"].'" href="preorder.php?product_id='.$row["id"].'" class="preorderButton"> Preorder </a></div>';
        echo '<center><div class="fb-like" data-send="false" data-layout="button_count" data-width="200" data-show-faces="false" 
              data-href="http://'.$settings->root.'/design.php?design_id='.$row['id'].'"></div></center>';
 } 
 ?>


            
