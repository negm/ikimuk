<div class="body">

    <div class="body_content">

        <div class="shop_container">


            <!--Start Of order progress-->
            <div class="order_progress">


                <!--Start Of order progress Container-->
                <div class="order_progress_container">


                    <!--Start of Flags Container-->
                    <div class="flags_container">
   <?php
   
   $single_shirt_pixel_size = (805 - count($settings->goals)) / $settings->goals[count($settings->goals)-2];
for ($i=0; $i < count($settings->goals)-1; $i++){
  $goal = $settings->goals[$i];
  echo  '<a href="#block-goal-' . ($i + 1) . '"><div class="flag_container';
  if($product->preorders >= $goal){
    echo " flag_" . $settings->goals_colors[$i];
  }
  echo '" style="margin-left:';
  if($i == 0){
    echo ($goal * $single_shirt_pixel_size - 15);
  }else{
    echo (($goal -$settings->goals[$i -1]) * $single_shirt_pixel_size -30);
  }
  echo 'px"><div class="flag_medal"></div></div></a>';
}
?>
                    </div>
                    <!--End of Flags Container-->



                    <!--Start of Progress Bar Container-->
                    <div class="progress_bars_container"> 
<?php
  for ($i=0; $i < count($settings->goals); $i++){
    $goal = $settings->goals[$i];
    echo  '<a href="#block-goal-' . ($i + 1) . '"><div class="progress" style="width:';
    if($i == count($settings->goals) -1){
      echo 15;
    }else{
      if($i == 0){
	echo ($goal * $single_shirt_pixel_size);
      }else{
	echo (($goal-$settings->goals[$i-1]) * $single_shirt_pixel_size);
      }
    }
    echo 'px"><div class="bar progress_' . $settings->goals_colors[$i] . '" style="width:';
    if($i == 0){
      echo $product->preorders * (100 / $goal);
    }else{
      echo ($product->preorders - $settings->goals[$i-1]) * (100 / ($goal - $settings->goals[$i-1]));
    }
    echo '%"></div></div></a>';
  }
?>
                        <div class="progress_over over">
                            <div class="progress_cube"></div>
                            <div class="progress_cube"></div>
                            <div class="progress_cube"></div>
                        </div>
                    </div>
                    <!--End of Progress Bar Container-->

		    <div class="flags_container">
                    
<?php
  for ($i=0; $i < count($settings->goals)-1; $i++){
    $goal = $settings->goals[$i];
    echo  '<div class="flag_container flag_number" style="margin-left:';
    if($i == 0){
      echo ($goal * $single_shirt_pixel_size - 15);
    }else{
      echo (($goal - $settings->goals[$i -1]) * $single_shirt_pixel_size -30);
    }
    echo 'px">' . $goal . '</div>';
  }
?>
                    </div>

                </div>
                <!--End Of order progress Container-->

                <div class="order_progress_count" temprop="aggregateRating"
    itemscope itemtype="http://schema.org/AggregateRating">
                    <div class="count_value" itemprop="ratingValue"><?php echo $product->preorders; ?></div>
                    <div class="count_text">T-shirt ordered</div>
                </div>


            </div> 
            <!--End Of order Container-->
            <!--Start Of Social Column-->
            <div class="social_column">
                <div class="slider_section">


                    <div class="social_label">
                        <div class="social_label_content">

                            <div class="social_label_left">  <?php echo $daysLeft;?> Days left</div>
                        </div>
                    </div>

                    <!--Start of shop slider-->
                    <div class="shop_slider">
                        <div id="wrapper">
                            <div class="slider-wrapper theme-light">
                                <div id="slider" class="nivoSlider nivo" >
                                    <?php
                                    while ($image_row = mysqli_fetch_assoc($image->database->result)) {
                                        echo '<img itemprop="image" src="' . $image_row["url"] . '" data-thumb="' . $image_row["url"] . '" alt="' . $product->title . ' ikimuk" />';
                                    }
                                    ?>
                                </div>
                                <div class="nivo-directionNav nivo">
                                    <a class="nivo-prevNav nivo" style="float:right;">Prev</a>
                                    <a class="nivo-nextNav nivo" style="float:left;">Next</a>
                                </div>
                            </div>
                        </div>
         
                    </div> 
                </div>
                    <!--End of shop slider-->
               
                    <!--Start Of Social Share-->
                    <div class="social_share">


                        <!--Start of facebook share-->
                        <div class="share_facebook">
                            <div id="fb-root"></div>
                              <?php if ($product->id > 50){?>
                        <div class="fb-like" data-href="<?php echo $settings->root."design.php?product_id=".$product->id; ?>" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
                        <?php } else { ?>
                        <div class="fb-like" data-href="<?php echo $settings->beta_base."design.php?product_id=".$product->id; ?>" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
                        <?php }?>
                        </div>
                        <!--End of facebook share-->


                        <!--Start of twitter share-->  
                        <div class="share_twitter">
                            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>

                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
                            if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

                        </div>
                        <!--End of twitter share--> 

                        <!--Start of google share-->                             
                        <div class="share_google">
                            <g:plus annotation='bubble' action="share"></g:plus>
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

                    </div> 
                    <!--End Of Social Share-->

                    <!--Start Of Social Comment-->
                    <div class="social_comment">

                        <div class="comment_label"></div>

                        <div class="social_header">
                            Show your support
                        </div>

                        <div class="social_body">

                            <div id="fb-root"></div>
                         <?php if ($product->id > 50){?>
                        <div class="fb-comments" data-width="576" data-num-posts="15" data-href="<?php echo $settings->root."design.php?product_id=".$product->id; ?>" data-colorscheme="light"></div>
                        <?php } else { ?>
                        <div class="fb-comments" data-width="576" data-num-posts="15" data-href="<?php echo $settings->beta_base."design.php?product_id=".$product->id; ?>" data-colorscheme="light"></div>
                        <?php }?>
                        </div>
                    </div>
                    <!--End Of Social Comment-->

                </div>
                <!--End Of Social Column-->
                <!--End of facebook commment section-->

                <!--Start Of Option Column-->
                <div class="option_column">

                    <!--Start Of Block Order-->
                    <div class="block_order" itemscope itemtype="http://schema.org/Product">

                        <div class="order_description" itemprop="name">
                            <?php echo $product->title; ?>
                        </div>

                        <div class="order_author">
                            by <?php echo $artist->name; ?>
                        </div>
                        <input type="hidden" id="product_id" value="<?php echo $product->id; ?>">
                        <div class="order_details" itemprop="description">
			    <?php echo $product->desc; ?>
                        </div>
                        <?php if ($product->preorders > $settings->goals[0]){?>
                        
                        <div class="order_progressbar">
                            <div class="progress">
                                <div class="bar progress_cyan"  style="width:100%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <img src="/img/ikimuk_blue_wow.png"/>
                            </div>
                        </div>


                        <div class="order_remaining">
                            <span class="order_remaining_hilight">Hooray !</span>
                            <span class="order_remaining_value"> 
                                This T-shirt is Getting Printed
                            </span>
                        </div>
                        <?php } else {?>
                        <div class="order_progressbar" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                            <div class="progress">
                                <div class="bar progress_cyan"  style="width:<?php echo $product->preorders* (100/$settings->goals[0]); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage" itemprop="ratingValue">
                                <?php echo $product->preorders."/".$settings->goals[0]; ?>
                            </div>
                        </div>


                        <div class="order_remaining">
                                <?php echo $settings->goals[0]-$product->preorders?> Orders till the T-shirt gets printed
                            </div>
                        <?php }?>

                        <!--Start Of Cart Body--> 
                        <div class="cart_body">

                            <!--Start Of Cart Selection--> 
                            <div class="cart_size_selection">

                                <!--Start Of Male Part--> 
                                <div class="size_selection_header">GUY - $<?php echo number_format($product->price, 2); ?> 
                                    <!--<span class="order_size_info"><a href="#">Size Info</a></span>-->
                                </div>

                                <div class="selection_container male_part">

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="S"/>
                                        <div>S</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="M"/>
                                        <div>M</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="L"/>
                                        <div>L</div>
                                    </div>


                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="XL"/>
                                        <div>XL</div>
                                    </div>

                                    <div class="empty_space"></div>
                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="XXL"/>
                                        <div>XXL</div>
                                    </div>
                                </div>
                                <!--End Of Male Part--> 

                                <!--Start Of Female Part--> 
                                <!-- <div class="size_selection_header">GIRL - $<?php echo number_format($product->price, 2); ?>
                                    <span class="order_size_info">
                                        <a href="#">Size Info</a>
                                    </span> 
                                </div>
                                <div class="selection_container female_part">

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="XS"/>
                                        <div>XS</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="S"/>
                                        <div>S</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="M"/>
                                        <div>M</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="L"/>
                                        <div>L</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="XL"/>
                                        <div>XL</div>
                                    </div>

                                </div> -->
                                <!--End Of Male Part--> 



                            </div>
                            <!--End Of cart size selection--> 



                        </div>
                        <!--End Of Cart Selection--> 


                        <span id="size_error" class="hide"></span>
                        <div id="add_to_cart" class="order_submit">
                            <input type="hidden" name="category" value=""/>
                            <input type="hidden" name="size" value=""/>
                            <input type="submit" name="add_to_cart" value="ADD TO CART"/>
                        </div>


                    </div>
                    <!--End Of Block Column-->





                    <!--Start Of Block Profile-->
                    <div class="block_profile">

                        <div class="profile_label">
                            <div class="label_content">
                                <div class="label_thumbnail">
                                    <img src="<?php echo $artist->image ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="profile_name"><?php echo $artist->name ?></div> 
                        <div class="profile_address"><?php echo $artist->location ?></div>
                        <?php if (strlen($artist->website)>1) {?>
                        <div class="profile_website"><a href="<?php echo $artist->website ?>" target="_blank"><?php echo $artist->website ?></a></div>
                        <?php } if (strlen($artist->twitter)>1) {?>
                        <div class="profile_twitter"><a href="http://twitter.com/<?php echo $artist->twitter ?>" target="_blank"><?php echo $artist->twitter; ?></a></div>
                        <?php }?>
                    </div>
                    <!--End Of Block Profile-->




                    <!----------------------------------------------------------------> 
<?php
for ($i=0; $i < count($settings->goals); $i++){
  echo '<div class="block_goal';
  if($i == 0){
    echo ' block_goal_cyan';
  }else{
    if($product->preorders >= $settings->goals[$i]){
      echo " block_goal_" . $settings->goals_colors[$i]; 
    }else{
      if($product->preorders >= $settings->goals[$i -1]){
	echo " block_goal_selected selected_" . ($i + 1);
      }
    }
  }
  echo '" id="block-goal-' . ($i+1) . '"><div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_medal.png"/>
                                </div>
                            </div>
                        </div>
                        <div class="goal_header">GOAL ' . ($i+1) . '</div>
                        <div class="goal_info">' . $settings->goals_texts[$i] . '
                        </div>';

  if($i < count($settings->goals) -1){
    echo '<div class="goal_progressbar">

                            <div class="progress">
                                <div class="bar progress_';
    echo $settings->goals_colors[$i] . '" style="width:';
    if($i == 0){
      echo ($product->preorders) * (100 / $settings->goals[$i]);
    }else{
      if($product->preorders <= $settings->goals[$i-1]){
	echo 0;
      }else{
	echo ($product->preorders - $settings->goals[$i-1]) * (100 / ($settings->goals[$i] - $settings->goals[$i-1]));;
      }
    }
    echo '%;"></div></div>';
    echo '<div class="progress_percentage">';
    if($i == 0){
      echo $product->preorders;
    }else{
      if($product->preorders >= $settings->goals[$i]){
	echo ($settings->goals[$i] - $settings->goals[$i-1]);
      }else{
	if($product->preorders <= $settings->goals[$i-1]){
	  echo 0;
	}else{
	  echo $product->preorders - $settings->goals[$i-1];
	}
      }
    }
    echo "/";
    if($i == 0){
      echo $settings->goals[$i];
    }else{
      echo $settings->goals[$i] - $settings->goals[$i-1];
    }
    echo "</div></div>";
    
  }
  echo "</div>";
}
?> 

                    <!--End Of Block Goal-->


                </div>
                <!--End Of Option Column-->
            </div>

        </div>
        <!--End of shop container-->
    </div>
    <!--End of body content-->

    

    <!-- Start of Reset part-->
<div id="item_added" class="modal hide fade member" data-backdrop="static">
           
        <div class='alert alert-success'><strong>Success!</strong> You have successfully added an item to your cart</div>
              
</div>
    <!-- End of Reset part-->
