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
unset($_SESSION["size"]);
echo '<div class= "container openingContainer center"><div class="span8">';
echo '<h2 class="tlblue">YOUR PREORDER MAKES THE T-SHIRTS</h2>';
echo 'Your design is ready. Now all you have to do is pick a design challenge 
    to submit to. The Threadless challenge is our big, ongoing, challenge. All 
    the rest have themes, timelines, and different stuff up for grabs.</div>';
echo '<div class="triangles"></div>';
echo '</div><div class="clear"></div>';
echo '<div class="container compHeader center"><div class="row"><img  class="span12" src="img/header-comp.png" alt="competition header"/></div></div>';
echo '<div class="container center">';
$count = 0;
while($row= mysqli_fetch_assoc($product->database->result))
{   $daysLeft = floor((strtotime($row["end_date"]) - time())/(60*60*24));
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
    echo '<div class="span4 wrapper"><a class="home_list" href="design.php?product_id='.$row["id"].'" >
            <div class="caption"><b>PREORDER NOW</b></div><img class="" src="'.$primary.'" alt="'.$row["title"].'"/></a>';
    //echo '<div class="preorderButton"><a id="'.$row["id"].'" href="preorder.php?product_id='.$row["id"].'" class="preorderButton"> Preorder </a></div>';
    //echo '<center><div class="fb-like" data-send="false" data-layout="button_count" data-width="200" data-show-faces="false" 
    //          data-href="http://'.$settings->root.'/design.php?design_id='.$row['id'].'"></div></center>';
    echo '<div class="span4 countBox">Preorders ('.$row["preorders"].')</div>';
    echo '<a class="" href="design.php?product_id='.$row["id"].'" ><div class="span4 designTitle">'.$row["title"].'<b class ="tblack tnormal"> by </b><b class="tlblue tnormal">'.$row["name"].'</b></div></a>';
    echo '</div>';
    if($count % 3 == 0)
        echo '</div><br>';
 }  
 echo '</div>';
 ?>


            
