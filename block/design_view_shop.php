<?php

/*
 * This is the design view with multiple images and thumbnail
 * 
 */
    //show the goodies :D  
   ?>
    <div class="body">

             <div class="body_content specific_shop">
                  <!--Start of Cart section-->
                <?php include $_SERVER["DOCUMENT_ROOT"]."/block/cart_count.php"; ?>
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
</div>
<!--End of shop slider-->
<!--Start of facebook comment section-->
<div class="shop_facebook">
<div class="std_block block_expandable">
<div class="std_block_header"><div class="header_content">Did your comments, toughts, support, be nice</div></div>
<div class="std_block_body">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-comments" data-width="620" data-num-posts="4" data-href="http://www.elnashra.com/news/show/576619" data-colorscheme="light"></div>
</div>
</div>
</div>
                      <!--End of facebook commment section-->
</div>
                       <!--End Of left side section-->
                       <!--Start of right side section-->
<div class="shop_right_section">
<div class="cart_block">
<div class="std_block">
<div class="std_block_body">
<div class="cart_body">
<div class="cart_body_header">
<div class="cart_description"> <?php echo $product->title;?></div>
<div class="car_author">by <?php echo $artist->name;?></div>
</div>
<div class="cart_body_details">
The original Funkalicious design was created by Christopher Golebiowski in 2006. Since its launch, Funkalicious has appeared as a tank top, water bottle, kids tee, and even a giant parade float.
</div>
<div class="cart_size_selection">
<div class="size_selection_header">GUY's regular fit- $<?php echo number_format($product->price,2);?></div>
<div class="selection_container male_part">
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="s"/>
<div>S</div>
</div>
<div class="cart_left">
999 Left
</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="m"/>
<div>M</div>
</div>
<div class="cart_left">
999 Left
</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="l"/>
<div>L</div>
</div>
<div class="cart_left">999 Left</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="xl"/>
<div>XL</div>
</div>
<div class="cart_left">999 Left</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="xxl"/>
<div>XXL</div>
</div>
<div class="cart_left">999 Left</div>
</div>
</div>
<div class="size_selection_header">GIRL's regular fit- $<?php echo number_format($product->price,2);?></div>
<div class="selection_container female_part">
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="s"/>
<div>S</div>
</div>
<div class="cart_left">999 Left</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="m"/>
<div>M</div>
</div>
<div class="cart_left">999 Left</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="l"/>
<div>L</div>
</div>
<div class="cart_left">999 Left</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="xl"/>
<div>XL</div>
</div>
<div class="cart_left">999 Left</div>
</div>
<div class="empty_space"></div>
<div class="selection_container_block">
<div class="cart_no">
<input type="hidden" name="size" value="xxl"/>
<div>XXL</div>
</div>
<div class="cart_left">999 Left</div>
</div>
</div>
<div class="add_to_cart">
<input type="hidden" name="category" value=""/>
<input type="hidden" name="size" value=""/>
<input type="hidden" name="product_id" id="product_id" value="<?php echo $product->id;?>"/>
<input type="hidden" name="price" id="price" value="<?php echo $product->price;?>"/>
<input type="button" name="add_to_cart" value="ADD TO CART"/>
</div>
</div>
</div>
</div>
</div></div>
<!--Start of share block-->
<div class="share_block">
<div class="std_block">
<div class="std_block_header"><div class="header_content">Share with friends</div></div>
<div class="std_block_body">
<!--Start of share content-->
<div class="share_content">
<!--Start of facebook share-->
<div class="share_facebook">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=410515992368816";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-href="http://stackoverflow.com/questions/9516467/facebook-like-button-count-not-working-unless-logged-in" data-send="false" data-layout="box_count" data-width="450" data-show-faces="false"></div>
</div>
<!--End of facebook share-->
<!--Start of google share-->                             
<div class="share_google">
<g:plus annotation='vertical-bubble' action="share"></g:plus>
<script type="text/javascript">
      window.___gcfg = {
        lang: 'en-US'
      };

      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>
</div>
<!--End of google share-->  
<!--Start of twitter share-->  
<div class="share_twitter">
<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="vertical">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
<!--End of twitter share-->  
</div>
<!--End of share content-->
</div>
<!--End of standard body block-->
</div>
<!--End of standard block-->
</div>
<!--End of share block-->
<!--Start of Profile block-->
<div class="profile_block">
<div class="std_block">
<div class="std_block_header"><div class="header_content">Designer Profile</div></div>
<div class="std_block_body shop_profile">
<div class="profile_avatar"><img src="<?php echo $artist->image;?>"/></div>
<div class="profile_name"><?php echo $artist->name;?></div>
<div class="profile_location">
<img src="/images/ikimuk_balloon_gray.png"/>
<?php echo $artist->location;?>
</div>
<div class="profile_website">
<img src="/images/ikimuk_bag_gray.png"/>
<?php echo $artist->website;?>
</div>
<div class="profile_twitter">
<img src="/images/ikimuk_twitter_gray.png"/>
<a href="http://twitter.com/"<?php echo $artist->twitter;?>><?php echo $artist->twitter;?></a>
</div>
</div>
</div></div>
<!--End of Profile block-->
</div>
<!--End of right side section-->
</div>
<!--End of shop container-->
</div>
<!--End of body content-->
</div>
<!--End of class body-->