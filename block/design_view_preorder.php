<?php

/*
 * This is the design view with multiple images and thumbnail
 * 
 */


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
    
    <div class="body">

             <div class="body_content specific_shop">
                 
                 <!--Start of Cart section-->
                 <div class="cart_section">
                     <div class="cart_content">
                         <div class="cart_icon"></div>
                         <div class="cart_details">
                             CART(<span class="cart_count">0</span>)
                         </div>
                     </div>
                 </div>
                  <!--end of Cart section-->
                  
                  <div class="shop_container">
                      
                      
                      <!--Start Of left side section-->
                 <div class="shop_left_section">
                     
                     <!--Start of shop slider-->
                     <div class="shop_slider">
                         <div id="wrapper">

        <div class="slider-wrapper theme-light">
            <div id="slider" class="nivoSlider">
      <?php
    while ($image_row = mysqli_fetch_assoc($image->database->result))
    {
        echo '<img src="'.$image_row["url"].'" data-thumb="'.$image_row["url"].'" alt="'.$product->title.' ikimuk" />';
    }?>
     </div>
        </div>
    </div>
     echo '<div class="tlblue commentheader noindent hidden">DROP YOUR COMMENTS<div class="lineb"></div></div><br>';
     echo '<div class="fb-comments" data-href="'.urldecode($settings->root.'design.php?product_id='.$product->id).'" data-num-posts="2" data-width="620"></div></div>';
     echo '<div class="span4">';
     //echo '';
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
    <label>Men</label>
    <a href="#" name="M_S" id="M_S" class="sizeIcon">S</a>
    <a href="#" name="M_M" id="M_M" class="sizeIcon">M</a>
    <a href="#" name="M_L" id="M_L" class="sizeIcon">L</a>
    <a href="#" name="M_XL" id="M_XL" class="sizeIcon">XL</a>
    <a href="#" name="M_XXL" id="M_XXL" class="sizeIcon nomargin">XXL</a>
    <label class="tmedium">Women</label>
    <a href="#" name="W_S" id="W_S" class="sizeIcon">S</a>
    <a href="#" name="W_M" id="W_M" class="sizeIcon">M</a>
    <a href="#" name="W_L" id="W_L" class="sizeIcon">L</a>
    <a href="#" name="W_XL" id="W_XL" class="sizeIcon">XL</a>
    </div>
    <?php
   echo '<a href="/preorder/'.$product->id.'/'.str_replace(".","",str_replace(" ","-",trim($product->title))).'" class="preorderButton"><div class="preorderButton" >PREORDER NOW</div></a>';
      
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



?>
    </div>
