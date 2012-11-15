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
require_once 'class/settings.php';
$product = new product();
$image = new image();
$settings = new settings();
$product->select($mID);
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
    include "block/header.php";
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
        randomStart: false, // Start on a random slide
        beforeChange: function(){}, // Triggers before a slide transition
        afterChange: function(){}, // Triggers after a slide transition
        slideshowEnd: function(){}, // Triggers after all slides have been shown
        lastSlide: function(){}, // Triggers when last slide is shown
        afterLoad: function(){} // Triggers when slider has loaded
    });
});
    </script>
    <?php
    include "block/top_area.php";
    include "block/breadcrumb.php";
    echo '<div class="slider-wrapper theme-dark offset3 span7">';
    echo '<div id="slider" class="nivoSlider">';
    while ($image_row = mysqli_fetch_assoc($image->database->result))
    {
        echo '<img src="'.$image_row["url"].'" data-thumb="'.$image_row["url"].'" alt="" />';
        //echo '<img src="img/fennec.png" data-thumb="img/fennec.png" alt="" />';
        //echo '<img src="img/artist.png" data-thumb="img/artist.png" alt="" />';
    }
     echo '</div>';
      echo '<div class="preorderButton grid_4"><a href="preorder.php?product_id='.$product->id.'" class="preorderButton"> Preorder </a></div>';
     echo '<center class="grid_4"><div style="text-align: center;  margin:0 auto o auto;" class="fb-like" data-send="false" data-layout="button_count" data-width="400" data-show-faces="false" 
              data-href="'.urldecode($settings->site_url_vars).'"></div></center>';
   
     if($next)
         echo '<div class="preorderButton grid_4"><a href="design.php?product_id='.$next.'" class="preorderButton"> Next </a></div>';
        if($prev)
         echo '<div class="preorderButton grid_4"><a href="design.php?product_id='.$prev.'" class="preorderButton"> Prev </a></div>';


}
?>
