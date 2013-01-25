<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "Buy the coolest T-Shirts!";
require_once "class/class.product.php";
require_once "class/class.image.php";
require_once 'class/settings.php';
$product = new product();
$product->CurrentShopDesigns();
$settings = new settings();
include "block/header.php";
include "block/top_area.php";
unset($_SESSION["size"]);
//echo '<div class="container compHeader center"><div class="row"><br><img  class="span12" src="/img/header_steps_ikimuk.png" alt="steps header ikimuk"/></div></div>';
echo '<div class="container center">';
//echo '<h2 class= "centert ogcomp">ONGOING COMPETITION</h2>';
echo '<div class="container compHeader center"><div class="row"><img  class="span12" src="https://s3.amazonaws.com/competition-header/Header_Preorder_Zombie.png" alt="competition header ikimuk"/></div></div>';
echo '<div class="container center">';//<b class="tlblue tlarge">OUR FOURTH COMPETITION </b> <b class="tpink tmedium">  (ends on 04/02/2013</b>)<div class="lineb"></div><br>';
$count = 0;
while($row= mysqli_fetch_assoc($product->database->result))
{   //$daysLeft = floor((strtotime($row["end_date"]) - time())/(60*60*24));
    if($count % 3 == 0)
        echo '<div class="row">';
    $count++;
    echo '<div class="span4 wrapper" itemscope itemtype="http://schema.org/CreativeWork" ><a itemprop="url" class="home_list" href="/design/'.$row["id"].'/'.str_replace(".","",str_replace(" ","-",trim($row["title"]))).'" >
            <div class="caption"><b>BUY NOW</b></div><img itemprop="image" class="" src="'.$row["url"].'" alt="'.$row["title"].' ikimuk"/></a>';
    echo '<div itemprop="contentRating" class="span4 countBox">Preorders ('.$row["preorders"].')</div>';
    echo '<a class="" href="/design/'.$row["id"].'/'.str_replace(" ","-",trim($row["title"])).'" ><div class="span4 designTitle" itemprop="name">'.$row["title"].'<br><b class ="tblack tnormal"> by </b><b itemprop="author" class="tlblue tnormal">'.$row["name"].'</b></div></a>';
    echo '</div>';
    if($count % 3 == 0)
        echo '</div><br>';
 }  
 echo '</div></div></div><br>';
 unset($_SESSION["size"]);
 ?>


            
