<?php

/*
 * This is the design view with multiple images and thumbnail
 * 
 */
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
   header("Location: index.php");
}
else
{
    //show the goodies :D  
    $pagetitle = $product->title;
    $next = $product->GetNextInCompetitionID();
    $prev = $product->GetPrevInCompetitionID();
    $daysLeft = floor((strtotime($competition->end_date) - time())/(60*60*24));
    include "block/header.php";
    
    echo '<meta property="og:site_name" content="Ikimuk" />';
    echo '<meta property="og:title" content="'.$product->title.'" />';
    echo '<meta property="og:image" content="'.$product->image.'" />';
    echo '<meta property="og:description" content="Cool T-shirt Design">';
    echo '<meta property="og:determiner" content="a" />';
    echo '<meta property="fb:app_id" content="'.$settings->app_id.'" />';
    echo '<meta property="og:url" content="'.$settings->site_url_vars.'" />';
    echo '<meta property="og:type" content="ikimukapp:design" />';
    ?>
 <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
        effect: 'slideInLeft', // Specify sets like: 'fold,fade,sliceDown'
        animSpeed: 200, // Slide transition speed
        pauseTime: 3000, // How long each slide will show
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
});
    </script>
    <script src="//assets.pinterest.com/js/pinit.js"></script>
    <?php
    include "block/top_area.php";
    include "block/breadcrumb.php";
    
    echo '<div class= "span7"><div class="slider-wrapper theme-light">';
     echo '<div class="span4 wrapper"><div class="ribbon"><span>'.$daysLeft.' DAYS left</span></div></div>';
    echo '<div id="slider" class="nivoSlider">';
    while ($image_row = mysqli_fetch_assoc($image->database->result))
    {
        echo '<img src="'.$image_row["url"].'" data-thumb="'.$image_row["url"].'" alt="" />';
    }
     echo '</div></div>';
     echo '<div class="fb-comments" data-href="'.$settings->site_url_vars.'" data-num-posts="2" data-width="670"></div></div>';
     echo '<div class>';
     echo '<div class="span5 designT">'.$product->title.' <b class ="tblack tnormal"> by </b><b class="tlblue tnormal">'.$artist->name.'</b></div>';
     echo '<div class="span5  dwidth lineb"></div><div class="clear"></div>'; 
     echo '<div class="span5  countText tlblue"><b class="circle span1 centert twhite">'.$product->preorders.' </b> PREORDERED THIS DESIGN</div>';
     echo '<div class="span5  price">PRICE: '.$product->price.'.00$</div>';
     ?>
    <div class="span5 ">(You only pay if T-shirt gets printed)</div>
    <div class="hidden span5" id="size_g"><small><br/>Please choose your Size!</small></div>
    <div class="span5 ">
    <a href="#" name="small" id="s" class="sizeIcon">S</a>
    <a href="#" name="medium" id="M" class="sizeIcon">M</a>
    <a href="#" name="large" id="L" class="sizeIcon">L</a>
    <a href="#" name="xlarge" id="XL" class="sizeIcon">XL</a>
    <a href="#" name="xxlarge" id="XXL" class="sizeIcon">XXL</a>
    </div>
    <?php
     echo '<div class="span5 preorderButton "><a href="preorder.php?product_id='.$product->id.'" class="preorderButton block"> Preorder Now</a></div><br/>';
     echo '<div class="span5  socialbox dwidth"><b class="span5 dwidth lbluebg twhite nomargin">Share with friends</b>';
     echo '<div class="span1 fb-like" data-send="false" data-layout="box_count" data-width="450" data-show-faces="true" data-font="arial" 
              data-href="'.urldecode($settings->site_url_vars).'"></div>';
     echo '<a href="https://twitter.com/share" class="span1 twitter-share-button" data-via="ikimukTweets" data-url="'.urlencode($settings->site_url_vars).'" data-text="'.$product->title.'  '.  urldecode($settings->site_url_vars).'" data-lang="en" data-related="HuNegm" data-count="vertical">Tweet</a>';
     echo '<a class="span1" data-pin-config="above" data-pin-do="buttonPin" href="//pinterest.com/pin/create/button/?url='.urlencode($settings->site_url_vars).'media='.$product->image.'&description='.$product->title.'"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>';
     echo '</div>';//end of social box
     echo '<div class="span5  socialbox dwidth"><b class="span5 dwidth lbluebg twhite nomargin">Artist Profile</b>';
     echo '<div class="span1 thumb"><img src = "'.$artist->image.'" /></div>';
     echo '<div class="span2 nomargin artistInfo"><b>'.$artist->name.'</b></div>';
     echo '<div class="span2 nomargin artistInfo">'.$artist->location.'</div>';
     echo '<div class="span2 nomargin artistInfo"><a class ="tlblue" href="'.$artist->website.'" target="_blank">'.$artist->website.'</a></div>';
     echo '<div class="span2 nomargin artistInfo"><a class ="tlblue" href="http://twitter/'.$artist->twitter.'" target="_blank">'.$artist->twitter.'</a></div>';
     echo '</div>';//end of artist profile
     
     //if($next)
         //echo '<div class="preorderButton span4"><a href="design.php?product_id='.$next.'" class="preorderButton"> Next </a></div>';
     //if($prev)
         //echo '<div class="preorderButton span4"><a href="design.php?product_id='.$prev.'" class="preorderButton"> Prev </a></div>';


}
?>
    </div>
