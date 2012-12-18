<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "ikimuk together we create!";
require_once "class/class.product.php";
require_once "class/class.image.php";
require_once 'class/settings.php';
$product = new product();
$product->PastCompetitionDesigns();
$image= new image();
$settings = new settings();
include "block/header.php";
include "block/top_area.php";
unset($_SESSION["size"]);
//echo '<div class="container compHeader center"><div class="row"><br><img  class="span12" src="/img/header_steps_ikimuk.png" alt="steps header ikimuk"/></div></div>';
echo '<div class="container center">';
echo '<div class="container compHeader center"><div class="row"><img  class="span12" src="/img/1stfruitvsvgt.png" alt="competition header ikimuk"/></div></div>';
echo '<div class="container center"><b class="tlblue tlarge">OUR FIRST COMPETITION </b> <b class="tpink tmedium">  (ended on 18/12/2012</b>)<div class="lineb"></div><br>';
$count = 0;
while($row= mysqli_fetch_assoc($product->database->result))
{ 
    if($count % 3 == 0)
        echo '<div class="row">';
    $count++;
    $image->product_id = $row["id"];
    $image->getBasicImage();
    while ($row_image = mysqli_fetch_assoc($image->database->result))
    {
        if ($row_image["small"])
        $primary = $row_image["url"];
    }
    echo '<div class="span4 wrapper"><a class="home_list" href="/design/'.$row["id"].'/'.str_replace(" ","-",trim($row["title"])).'" >
            <div class="caption"><b>PREORDER NOW</b></div><img class="" src="'.$primary.'" alt="'.$row["title"].' ikimuk"/></a>';
    //echo '<div class="preorderButton"><a id="'.$row["id"].'" href="preorder.php?product_id='.$row["id"].'" class="preorderButton"> Preorder </a></div>';
    //echo '<center><div class="fb-like" data-send="false" data-layout="button_count" data-width="200" data-show-faces="false" 
    //          data-href="http://'.$settings->root.'/design.php?design_id='.$row['id'].'"></div></center>';
    echo '<div class="span4 countBox">Preorders ('.$row["preorders"].')</div>';
    echo '<a class="" href="design.php?product_id='.$row["id"].'" ><div class="span4 designTitle">'.$row["title"].'<br><b class ="tblack tnormal"> by </b><b class="tlblue tnormal">'.$row["name"].'</b></div></a>';
    echo '</div>';
    if($count % 3 == 0)
        echo '</div><br>';
 }  
 echo '</div></div></div><br>';
 include 'block/footer.php';
 ?>
