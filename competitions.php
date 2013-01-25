<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "ikimuk together we create!";
require_once "class/class.product.php";
require_once "class/class.image.php";
require_once "class/class.competition.php";
require_once 'class/settings.php';
$product = new product();
$competition = new competition();
$competition->getCompletedCompetitions();
$image= new image();
$settings = new settings();
include "block/header.php";
include "block/top_area.php";
unset($_SESSION["size"]);
echo '<br><br><div class="container center">';
$count = 0;
if(!isset($_GET["competition_id"]))
{$row_competition= mysqli_fetch_object($competition->database->result);}
else{
    $competition->select($_GET["competition_id"]);
    $row_competition = $competition;
}
echo '<select class="span6" id="region" name="region">'; 
    
echo '</select>';
{
echo '<div class="container compHeader center"><div class="row"><img  class="span12" src="'.$row_competition->competition_header.'" alt="competition header ikimuk"/></div></div>';
$product->selectByCompetition($row_competition->id);
while($row= mysqli_fetch_assoc($product->database->result))
{ 
    if($count % 4 == 0)
        echo '<div class="row">';
    $count++;
    
        
    echo '<div class="span3 wrapper">';
    if ($row["shop"] == 1)
    {
    echo '<div class="wrapper"><div class="ribbon"><span><b class="tsmall">WINNER</b></span></div></div>';
    echo '<a class="home_list" href="/design/'.$row["id"].'/'.str_replace(" ","-",trim($row["title"])).'" >
            <div class="caption"><b>BUY NOW</b></div><img class="" src="'.$row["url"].'" alt="'.$row["title"].' ikimuk"/></a>';
    }
    else
    {
        
        echo '<a class="home_list" href="/design/'.$row["id"].'/'.str_replace(" ","-",trim($row["title"])).'" >
            <div class="caption"><b>VIEW DESIGN</b></div><img class="" src="'.$row["url"].'" alt="'.$row["title"].' ikimuk"/></a>';
    }
    echo '<div class="span3 countBox">Preorders ('.$row["preorders"].')</div>';
    echo '<a class="" href="design.php?product_id='.$row["id"].'" ><div class="span3 designTitle">'.$row["title"].'<br><b class ="tblack tnormal"> by </b><b class="tlblue tnormal">'.$row["name"].'</b></div></a>';
    echo '</div>';
    if($count % 4 == 0)
        echo '</div><br>';
 }
 $count=0;
}
 echo '</div></div></div><br>';
 include 'block/footer.php';
 ?>
