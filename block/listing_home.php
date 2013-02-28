<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "ikimuk together we create!";
require_once $_SERVER["DOCUMENT_ROOT"]."/class/class.product.php";
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
<?php 
  if(isset($_GET["payment"]) and $_GET["payment"] == "success"){
    echo "<div class='alert alert-success'> <button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Success!</strong> We have processed your order.";
    if(isset($_GET["type"]) and $_GET["type"] == "preorder"){
      echo " You will be notified if this design gets printed.";
    }else{
      echo " Your T-shirt will be delivered to you soon.";
    }
    echo "</div>";
  }
if(isset($_GET["submit"]) and $_GET["submit"] == "success"){
    echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Success!</strong> You have successfully submitted your design</div>";
  }
?>
               <!--Start of Slider section-->
                    <div class="slider"> 
                        <div id="myCarousel" class="carousel slide">
                            <!-- Carousel items -->
                            <div class="carousel-inner">

                                <div class="active item"><img src="/images/bootstrap_1.png"/></div>
                                <div class="item"><img src="/images/bootstrap_2.png"/></div>

                            </div>
                            <!-- Carousel nav -->
                            <a class="carousel-control-iki left" href="#myCarousel" data-slide="prev">
                                <img src="/img/ikimuk_slider_left.png"/>
                            </a>
                            <a class="carousel-control-iki right" href="#myCarousel" data-slide="next">
                                <img src="/img/ikimuk_slider_right.png"/>
                            </a>
                        </div>
                    </div>
                    <!--End of Slider section-->
                 
                  
                
                 
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
$count = 0;
while($row= mysqli_fetch_assoc($product->database->result))
{   $daysLeft = floor((strtotime($row["end_date"]) - time())/(60*60*24));?>
    <div class="entry" style="<?php if($count%3==0) echo "margin-left:10px;"; $count++;?>">
    <!--Used to set a link when clicking-->
    <input type="hidden" name="user_id" value="/design/<?php echo $row["id"]."/".str_replace(" ","-",trim($row["title"])); ?>"/>
    <div class="entry_transparent">
         <div class="entry_order_now">
          ORDER NOW
         </div>
    </div>
    <div class="entry_option">
        <div class="option_price">
              <span class="entry_item_price"><?php echo $row["price"];?></span>
              <span class="entry_dollar_sign">$</span>
        </div>
        <div class="option_male"></div>
        <div class="option_female"></div>
    </div>
    
    <div class="entry_avatar">
        <a href="/design/<?php echo $row["id"]."/".str_replace(" ","-",trim($row["title"])); ?>">
                                 <img src="<?php echo $row["url"];?>"/>
        </a>
    </div>
        
             <div class="entry_control">

                                        <div class="entry_description">
                                            <?php echo $row["title"];?>
                                        </div>

                                        <div class="entry_author">
                                            by
                                            <span class="entry_author_name"><?php echo $row["name"];?></span>
                                        </div>

                                        <div class="entry_progressbar">

                                            <div class="progress">
                                                <div class="bar progress_cyan" style="width:<?php echo $row["preorders"] * (100/$settings->first_goal); ?>%"></div>
                                            </div>

                                            <div class="entry_remaining">
                                                <?php if ($row["preorders"] >= $settings->first_goal) { ?>
                                                    <span class="entry_remaining_hilight">Hooray !</span>
                                                    <span class="entry_remaining_value"> 
                                                        This T-shirt is Getting Printed
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="entry_remaining_value"> 
                                                        <?php echo $settings->first_goal - $row["preorders"];?> order till t-shirt get printed
                                                    </span>
                                                <?php } ?>
                                            </div>

                                        </div>


                                        <div class="progress_status">
                                            <?php if ($row["preorders"] < $settings->first_goal) { ?>
                                                <span class="entry_progress_percentage">
                                                    <?php echo $row["preorders"];?>
                                                    /<?php echo $settings->first_goal;?>
                                                </span>
                                            <?php } else { ?>
                                                <img src="img/ikimuk_blue_wow.png"/>
                                            <?php } ?>
                                        </div>

                                    </div>
                                    <!--End of entry control-->            
                             </div>
                             <!--End of entry-->
 <?php } ?>
</div>
                     <!--End of competition container-->

</div>
<!--End of competition section-->
                 
                 
                 
             </div> </div>