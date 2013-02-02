<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "ikimuk together we create!";
require_once __DIR__."../class/class.product.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/class/class.image.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
$product = new product();
$product->CurrentCompetitionDesigns();
$image= new image();
$settings = new settings();
include $_SERVER["DOCUMENT_ROOT"]."/block/header.php";
include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";
unset($_SESSION["size"]);
?>
<div class="body">
 <div class="body_content">
                 
                 <!--Start of Cart section-->
                 <div class="cart_section">
                     <div class="cart_content">
                         <div class="cart_icon"></div>
                         <div class="cart_details">
                             CART(<span class="cart_count"><span id="item_count"><?php if (!isset($_SESSION["item_count"])) echo '0'; else echo $_SESSION["item_count"]; ?></span></span>)
                         </div>
                     </div>
                 </div>
                  <!--end of Cart section-->
                 
                 
                 
                 <div class="slider"> 
                         <div id="myCarousel" class="carousel slide">
    <!-- Carousel items -->
    <div class="carousel-inner">
        <div class="active item"><img src="images/bootstrap_1.png"/></div>
        <div class="item"><img src="images/bootstrap_2.png"/></div>
       
    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div>
                     <script>    $('.carousel').carousel({
    interval: 2000
    });</script>
                 </div>
                 
                  
                 <div class="horizontal_line">  
                 </div>
                 
                 
                 <!--Start of competition section-->
                 <div class="competition_section">
                     
                     
                     <div class="competition_header">
                         competition no
                         <span class="competition_no">12</span>
                         (ends
                         <span class="competition_end_date">02/03/2013</span>)
                     </div>
                     
                     
                     <div class="competition_banner">
                         <!--to be removed and replaced with image-->
                         <img  class="" src="https://s3.amazonaws.com/competition-header/Header_Preorder_Zombie.png" alt="competition header ikimuk"/>
                     </div>
                     <!--Start of competition container-->
                       <div class="competition_container">

<?php
$count = 1;
while($row= mysqli_fetch_assoc($product->database->result))
{   $daysLeft = floor((strtotime($row["end_date"]) - time())/(60*60*24));?>
    <div class="entry" style="<?php if($count%4!=0) echo "margin-right:20px;".$count%4; $count++;?>">
    <!--Used to set a link when clicking-->
    <input type="hidden" name="user_id" value="/design/<?php echo $row["id"]."/".str_replace(" ","-",trim($row["title"])); ?>"/>
    
    <div class="avatar">
                                 <img src="<?php echo $row["url"]?>"/>
    </div>
    
    <div class="pre_order">     
        <div class="pre_order_content">
            Pre-Orders(<span class="pre_order_count"><?php echo $row["preorders"];?></span>)    
        </div>    
    </div>
    
    <div class="details">
                                 <div class="description"><?php echo $row["title"];?></div>
                                 <div class="author">by <span class="author_name"><?php echo $row["name"];?></span></div>
                             </div>
                             
                             
                             <div class="avatar_transparent">
                                 <div class="transparent_text">
                                  pre-order now   
                                 </div>
                             </div>
                             
                             
                                  </div>
                             <!--End of entry-->
 <?php } ?>
</div>
                     <!--End of competition container-->

</div>
<!--End of competition section-->
                 
                 
                 
             </div>