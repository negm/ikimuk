<?php

/*
 * This is the design view with multiple images and thumbnail
 * 
 */
unset($_SESSION["size"]);
if(isset($_GET["product_id"]))
{
$mID = $_GET["product_id"];
}
else
{
    header("Location: index.php");
}
require_once 'class/class.product.php';
require_once 'class/class.image.php';
require_once 'class/class.artist.php';
require_once 'class/class.competition.php';
require_once 'class/settings.php';
$regex = '/(?<!href=["\'])http:\/\//';
$product = new product();
$image = new image();
$competition = new competition();
$artist = new artist();
$settings = new settings();
$product->select($mID);
$competition->select($product->competition_id);
$artist->select($product->artist_id);
$image->selectByProduct($mID);
if ($product->database->result === NULL || $image->database->result === NULL)
{
  //Something went wrong either redirect or show something
   header("Location: /index.php");
}
else
{
    //show the goodies :D  
    $pagetitle = $product->title;
    $next = $product->GetNextInCompetitionID();
    $prev = $product->GetPrevInCompetitionID();
    $daysLeft = floor((strtotime($competition->end_date) - time())/(60*60*24));
    include "block/header.php";
    
    
    echo '<meta property="og:title" content="'.$product->title.'" />';
    echo '<meta property="og:image" content="'.$product->image.'" />';
    echo '<meta property="fb:app_id" content="'.$settings->app_id.'" />';
    echo '<meta property="og:url" content="'.$settings->site_url_vars.'" />';
    
    ?>
 <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
        effect: 'slideInLeft', // Specify sets like: 'fold,fade,sliceDown'
        animSpeed: 350, // Slide transition speed
        pauseTime: 4000, // How long each slide will show
        startSlide: 0, // Set starting Slide (0 index)
        directionNav: true, // Next & Prev navigation
        controlNav: true, // 1,2,3... navigation
        controlNavThumbs: true, // Use thumbnails for Control Nav
        pauseOnHover: true, // Stop animation while hovering
        manualAdvance: false, // Force manual transitions
        prevText: 'Prev', // Prev directionNav text
        nextText: 'Next', // Next directionNav text
        randomStart: false // Start on a random slide
 
    });
    $(".ribbon").removeClass("hidden");
    $(".commentheader").removeClass("hidden");
});

    </script>
    
    <?php
    include "block/top_area.php";
    include "block/breadcrumb.php";
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class= "span8"><div class="slider-wrapper theme-light">';
    if ($daysLeft >=0)
    echo '<div class="wrapper"><div class="ribbon hidden"><span><b>'.$daysLeft.'</b><br> DAYS left</span></div></div>';
    else
    echo '<div class="wrapper"><div class="ribbon hidden"><span><b><small>Compe-<br>tition Ended</small></b></span></div></div>';
    echo '<div id="slider" class="nivoSlider">';
    while ($image_row = mysqli_fetch_assoc($image->database->result))
    {
        echo '<img src="'.$image_row["url"].'" data-thumb="'.$image_row["url"].'" alt="'.$product->title.' ikimuk" />';
    }
     echo '</div></div>';
     echo '<div class="tlblue commentheader noindent hidden">DROP YOUR COMMENTS<div class="lineb"></div></div><br>';
     echo '<div class="fb-comments" data-href="'.urldecode($settings->root.'design.php?product_id='.$product->id).'" data-num-posts="2" data-width="620"></div></div>';
     echo '<div class="span4">';
     echo '<h1 class="designT">'.$product->title.' <b class ="tblack tnormal"> by </b><br><b class="tlblue tnormal">'.$artist->name.'</b></h1>';
     echo '<div class="lineb"></div><div class="clear"></div>'; 
     echo '<div class="countText tlblue"><b class="circle span1 centert twhite">'.$product->preorders.' </b> <b>PREORDERED THIS DESIGN</b></div>';
     echo '<div class="price">PRICE: '.$product->price.'.00$</div>';
      if ($daysLeft >=0)
      {
     ?>
    <div class="">(REMEMBER: Your pre-order is only confirmed if this T-shirt design gets the most preorders)</div>
    <div class="hidden" id="size_g"><br/>Please choose your Size!</div>
    <div class="">
    <a href="#" name="S" id="s" class="sizeIcon">S</a>
    <a href="#" name="M" id="M" class="sizeIcon">M</a>
    <a href="#" name="L" id="L" class="sizeIcon">L</a>
    <a href="#" name="XL" id="XL" class="sizeIcon">XL</a>
    <a href="#" name="XXL" id="XXL" class="sizeIcon nomargin">XXL</a>
    </div>
    <?php
   
     echo '<a href="/preorder/'.$product->id.'/'.str_replace(" ","-",trim($product->title)).'" class="preorderButton"><div class="preorderButton" >PREORDER NOW</div></a>';
      }
     echo '<div class=" lbluebg twhite boxheader">Share with friends</div><div class="  socialbox">';
     echo '<div class="span1 fb-like" data-send="false" data-layout="box_count" data-width="450" data-show-faces="true" data-font="arial" 
              data-href="'.urldecode($settings->root.'design.php?product_id='.$product->id).'"></div>';
     echo '<div class="span1"><a href="https://twitter.com/share" class="twitter-share-button" data-via="ikimukTweets" data-count="vertical" data-url="'.urlencode($settings->site_url_vars).'" data-text="'.$product->title.'  '.  urldecode($settings->site_url_vars).'">Tweet</a></div>';
     echo '<div class="span1" style="margin-top:10px;"><a href="http://pinterest.com/pin/create/button/?url='.urlencode($settings->site_url_vars).'&media='.urlencode($product->image).'&description='.urlencode($product->title).'" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>';
     echo '</div><div class="clear"></div><div class="clear"></div>';//end of social box
     echo '<div class="lbluebg twhite boxheader">Designer Profile</div><div class="socialbox">';
     echo '<div class="span1 thumb nomargin"><img src = "'.$artist->image.'" alt="'.$artist->name.' ikimuk"/></div>';
     echo '<div class="span2"><div class=" artistInfo "><b>'.$artist->name.'</b></div>';
     echo '<div class=" artistInfo">'.$artist->location.'</div>';
     echo '<div class=" artistInfo "><a class ="tlblue" href="'.$artist->website.'" target="_blank">'.preg_replace($regex, '',$artist->website).'</a></div>';
     echo '<div class=" artistInfo"><a class ="tlblue" href="http://www.twitter.com/'.$artist->twitter.'" target="_blank">'.$artist->twitter.'</a></div>';
     echo '</div></div>';//end of artist profile
     echo '</div>'; //end of row
     echo '</div>'; //end of container
     //if($next)
         //echo '<div class="preorderButton span4"><a href="design.php?product_id='.$next.'" class="preorderButton"> Next </a></div>';
     //if($prev)
         //echo '<div class="preorderButton span4"><a href="design.php?product_id='.$prev.'" class="preorderButton"> Prev </a></div>';


}

?>
    </div>
