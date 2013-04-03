<?php
/*
 * This is the design view with multiple images and thumbnail
 * 
 */
//show the goodies :D  

?>
<div class="body">

    <div class="body_content">

                        <div class="links_section">
                        <div class="links_content">
                            <div class="link_deactive"><a class="link_deactive" href="/index.php">ikimuk</a></div>
                            <div class="link_deactive">/</div>
                            <div class="link_deactive"><a class="link_deactive" href="/competitions.php"> <?php echo _txt("past")." "._txt("competitions");?> </a></div>
                            <div class="link_deactive">/</div>
                            <div class="link_active">
                                <a href="#"><?php echo $product->title; ?></a>
                            </div>
                        </div>
                    </div>



        
            <!--Start Of order progress-->
            <!--End Of order Container-->
            <!--Start Of Social Column-->
            <div class="social_column">
                <div class="slider_section">


                    <div class="social_label">
                        <div class="social_label_content" style="width:190px">

                            <div class="social_label_left" style="width:180px"><?php echo _txt("pastcontestant")?></div>
                        </div>
                    </div>

                    <!--Start of shop slider-->
                    <div class="shop_slider">
                        <div id="wrapper">
                            <div class="slider-wrapper theme-light">
                                <div id="slider" class="nivoSlider nivo">
                                    <?php
                                    while ($image_row = mysqli_fetch_assoc($image->database->result)) {
                                        echo '<img src="' . $image_row["url"] . '" data-thumb="' . $image_row["url"] . '" alt="' . $product->title . ' ikimuk" />';
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

                            
                        </div>
                        <!--End of twitter share--> 

                        <!--Start of google share-->                             
                        <div class="share_google">
                            <g:plus annotation='bubble' action="share"></g:plus>
                          
                        </div>
                        <!--End of google share-->  
                        <!-- Start of Pinterest -->
                        <div class="share_pinterest">
                        <a data-pin-config="beside" href="//pinterest.com/pin/create/button/?url=<?php echo urlencode($settings->root."design/".$product->id."/".str_replace(".","",str_replace(" ","-",trim($product->title )))); ?>&media=<?php echo urlencode($product->image); ?>&description=<?php echo urlencode($product->title); ?>" data-pin-do="buttonPin" ><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
                        </div>
                        <!-- End of Pinterest -->
                    </div> 
                    <!--End Of Social Share-->

                    <!--Start Of Social Comment-->
                    <div class="social_comment">

                        <div class="comment_label"></div>

                        <div class="social_header">
                            <?php echo _txt("showsupport");?>
                        </div>
                         <div id="fb-root"></div>
                            
                        <div class="social_body">
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
                    <div class="block_order"  itemscope itemtype="http://schema.org/Product">

                        <div itemprop="name" class="order_description"  >
                            <span ><?php echo $product->title; ?></span>
                        </div>

                        <div class="order_author">
                            by <?php echo $artist->name; ?>
                        </div>
                        <input type="hidden" id="product_id" value="<?php echo $product->id; ?>">
                        <div class="order_details" itemprop="description">
			    <?php echo $product->desc; ?>
                        </div>
			
                <div class="order_ended_count" style="margin-top:20px;"  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <div class="count_value" itemprop="ratingValue"><?php echo $product->preorders; ?></div>
                    <div class="count_text"><?php echo _txt("tshirtsordered");?></div>
                </div>
		</div>
                        <!--End Of Block Column-->
                        <!--Start Of Cart Body--> 
                        
                          <!--Start Of Block Profile-->
                    <div class="block_profile">

                        <div class="profile_label">
                            <div class="label_content">
                                <div class="label_thumbnail">
                                    <img src="<?php echo $artist->image; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="profile_name"><?php echo $artist->name; ?></div> 
                        <div class="profile_address"><?php echo $artist->location; ?></div>
                        <?php if (strlen($artist->website)>1) {?>
                        <div class="profile_website"><a href="<?php echo $artist->website ?>" target="_blank"><?php echo $artist->website ?></a></div>
                        <?php } if (strlen($artist->twitter)>1) {?>
                        <div class="profile_twitter"><a href="http://twitter.com/<?php echo $artist->twitter ?>" target="_blank"><?php echo $artist->twitter; ?></a></div>
                        <?php }?>

                    </div>
                    <!--End Of Block Profile-->


                </div>
                <!--End Of Option Column-->
         

        </div>
        <!--End of shop container-->
    </div>
    <!--End of body content-->
