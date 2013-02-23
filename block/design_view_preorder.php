<?php
/*
 * This is the design view with multiple images and thumbnail
 * 
 */
//show the goodies :D  
?>
<div class="body">

    <div class="body_content">
        
            <!--Start Of order progress-->
            <div class="order_progress">


                <!--Start Of order progress Container-->
                <div class="order_progress_container">


                    <!--Start of Flags Container-->
                    <div class="flags_container">
                        <div class="flag_container margin_l_100">
                            <div class="flag_medal"></div>
                        </div>

                        <div class="flag_container margin_l_50">
                            <div class="flag_medal"></div>
                        </div>

                        <div class="flag_container margin_l_50">
                            <div class="flag_medal"></div>
                        </div>

                        <div class="flag_container margin_l_200">
                            <div class="flag_medal"></div>
                        </div>

                        <div class="flag_container margin_l_400">
                            <div class="flag_medal"></div>
                        </div>
                    </div>
                    <!--End of Flags Container-->



                    <!--Start of Progress Bar Container-->
                    <div class="progress_bars_container"> 
                        <div class="progress progress_flag_1">
                            <div class="bar progress_cyan" style="width: <?php echo $product->preorders * (100 / $settings->first_goal); ?>%;"></div>
                        </div>
                        <div class="progress progress_flag_2">
                            <div class="bar progress_green" style="width:0%"></div>
                        </div>
                        <div class="progress progress_flag_3">
                            <div class="bar progress_yellow" style="width: 0%;"></div>
                        </div>
                        <div class="progress progress_flag_4">
                            <div class="bar progress_firebrick" style="width:0%;"></div>
                        </div>
                        <div class="progress progress_flag_5">
                            <div class="bar progress_magenta" style="width: 0%;"></div>
                        </div>
                        <div class="progress progress_flag_6">
                            <div class="bar progress_red" style="width: 0%;"></div>
                        </div>

                        <div class="progress_over">
                            <div class="progress_cube"></div>
                            <div class="progress_cube"></div>
                            <div class="progress_cube"></div>
                        </div>
                    </div>
                    <!--End of Progress Bar Container-->



                </div>
                <!--End Of order progress Container-->

                <div class="order_progress_count">
                    <div class="count_value"><?php echo $product->preorders; ?></div>
                    <div class="count_text">Ordered this design</div>
                </div>


            </div> 
            <!--End Of order Container-->
            <!--Start Of Social Column-->
            <div class="social_column">
                <div class="slider_section">


                    <div class="social_label">
                        <div class="social_label_content">

                            <div class="social_label_left"> 12 Days left</div>
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
                         <script type="text/javascript" src="/js/nivo-slider-custom-loader.js"></script>
                    </div> 
                </div>
                    <!--End of shop slider-->
               
                    <!--Start Of Social Share-->
                    <div class="social_share">


                        <!--Start of facebook share-->
                        <div class="share_facebook">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id)) return;
                                js = d.createElement(s); js.id = id;
                                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $settings->app_id?>";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>


                            <div class="fb-like" data-href="<?php echo $settings->root."/design.php?product_id=".$product->id; ?>" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
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
                            Drop your comment, thoughts, support, be nice
                        </div>

                        <div class="social_body">

                        <div class="fb-comments" data-width="576" data-num-posts="15" data-href="<?php echo $settings->root."design.php?product_id=".$product->id; ?>" data-colorscheme="light"></div>
                        </div>
                    </div>
                    <!--End Of Social Comment-->

                </div>
                <!--End Of Social Column-->
                <!--End of facebook commment section-->

                <!--Start Of Option Column-->
                <div class="option_column">

                    <!--Start Of Block Order-->
                    <div class="block_order">

                        <div class="order_description">
                            <?php echo $product->title; ?>
                        </div>

                        <div class="order_author">
                            by <?php echo $artist->name; ?>
                        </div>
                        <input type="hidden" id="product_id" value="<?php echo $product->id; ?>">
                        <div class="order_details">
                            The original Funkalicious design was created by Christopher Golebiowski in 2006. 
                            Since its launch, Funkalicious has appeared as a tank top, water bottle,
                            kids tee, and even a giant parade float.
                        </div>

                        <div class="order_progressbar">
                            <div class="progress">
                                <div class="bar progress_cyan"  style="width:<?php echo $product->preorders* (100/$settings->first_goal); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php echo $product->preorders."/".$settings->first_goal; ?>
                            </div>
                        </div>


                        <div class="order_remaining">
                                <?php echo $settings->first_goal-$product->preorders?> Orders till the T-shirt gets printed
                            </div>
                          <div id="preorder_in" class="order_submit">
                              <input type="submit" value="PRE-ORDER"/>
                          </div>

                            <div class="order_info">
                                Your Pre-Order is only confirmed if this T-Shirt Design gets 50 Pre-Orders before the end of the competition
                            </div>


                        </div>
                        <!--End Of Block Column-->
                        <!--Start Of Cart Body--> 
                        
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
                        <div class="profile_website"><?php echo $artist->website ?></div>
                        <div class="profile_twitter"><?php echo $artist->twitter ?></div>

                    </div>
                    <!--End Of Block Profile-->

                    <!----------------------------------------------------------------> 
                    <div class="block_goal block_goal_selected">

                        <div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_medal.png"/>
                                </div>
                            </div>
                        </div>

                        <div class="goal_header">GOAL 1</div>
                        <div class="goal_info">
                            First 50 orders will get THE BENEFACTOR: Any pack above that you want, PLUS: Another exclusive comic will go in everyone’s book. Your name and a mini-port..
                        </div>

                        <div class="goal_progressbar">

                            <div class="progress">
                                <div class="bar progress_cyan" style="width:<?php echo ($product->preorders) * (100 / $settings->first_goal); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php echo $product->preorders."/".$settings->first_goal; ?>
                            </div>

                        </div>


                        <div class="goal_remaining">
                            <?php echo $settings->first_goal-$product->preorders; ?> Orders till the T-shirt gets printed
                        </div>

                    </div>
                    <!---------------------------------------------------------------->

                    <!----------------------------------------------------------------> 

                    <div class="block_goal block_goal_green">

                        <div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_snowstar.png"/>
                                </div>
                            </div>
                        </div>

                        <div class="goal_header">GOAL 2</div>
                        <div class="goal_info">
                            First 50 orders will get THE BENEFACTOR: Any pack above that you want, PLUS: Another exclusive comic will go in everyone’s book. Your name and a mini-port..
                        </div>

                        <div class="goal_progressbar">

                            <div class="progress">
                                <div class="bar progress_green" style="width:<?php echo ($product->preorders) * (100 / $settings->second_goal); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php echo $product->preorders."/".$settings->second_goal; ?>
                            </div>

                        </div>


                        <div class="goal_remaining">
                            <?php echo $settings->first_goal-$product->preorders; ?>  Orders till the T-shirt gets printed
                        </div>

                    </div>
                    <!----------------------------------------------------------------> 


                    <!----------------------------------------------------------------> 
                    <div class="block_goal block_goal_yellow">

                        <div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_snowstar.png"/>
                                </div>
                            </div>
                        </div>

                        <div class="goal_header">GOAL 3</div>
                        <div class="goal_info">
                            First 50 orders will get THE BENEFACTOR: Any pack above that you want, PLUS: Another exclusive comic will go in everyone’s book. Your name and a mini-port..
                        </div>

                        <div class="goal_progressbar">

                            <div class="progress">
                                <div class="bar progress_yellow" style="width:<?php echo ($product->preorders) * (100 / $settings->third_goal); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                 <?php echo $product->preorders."/".$settings->third_goal; ?>
                            </div>

                        </div>


                        <div class="goal_remaining">
                            <?php echo $settings->first_goal-$product->preorders; ?>  Orders till the T-shirt gets printed
                        </div>

                    </div>
                    <!----------------------------------------------------------------> 

                    <!----------------------------------------------------------------> 

                    <div class="block_goal block_goal_firebrick">

                        <div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_snowstar.png"/>
                                </div>
                            </div>
                        </div>

                        <div class="goal_header">GOAL 4</div>
                        <div class="goal_info">
                            First 50 orders will get THE BENEFACTOR: Any pack above that you want, PLUS: Another exclusive comic will go in everyone’s book. Your name and a mini-port..
                        </div>

                        <div class="goal_progressbar">

                            <div class="progress">
                                <div class="bar progress_firebrick" style="width:<?php echo ($product->preorders) * (100 / $settings->fourth_goal); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php echo $product->preorders."/".$settings->fourth_goal; ?>
                            </div>

                        </div>


                        <div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>

                    </div>
                    <!----------------------------------------------------------------> 


                    <!---------------------------------------------------------------->     

                    <div class="block_goal block_goal_magenta">

                        <div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_snowstar.png"/>
                                </div>
                            </div>
                        </div>

                        <div class="goal_header">GOAL 5</div>
                        <div class="goal_info">
                            First 50 orders will get THE BENEFACTOR: Any pack above that you want, PLUS: Another exclusive comic will go in everyone’s book. Your name and a mini-port..
                        </div>

                        <div class="goal_progressbar">

                            <div class="progress">
                                <div class="bar progress_magenta" style="width:<?php echo ($product->preorders) * (100 / $settings->fifth_goal); ?>%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php echo $product->preorders."/".$settings->fifth_goal; ?>
                            </div>

                        </div>


                        <div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>

                    </div>
                    <!----------------------------------------------------------------> 

                    <!----------------------------------------------------------------> 
                    <div class="block_goal block_goal_red">

                        <div class="goal_label">
                            <div class="goal_content">
                                <div class="goal_thumbnail">                                           
                                    <img src="/img/ikimuk_snowstar.png"/>
                                </div>
                            </div>
                        </div>

                        <div class="goal_header">GOAL 5</div>
                        <div class="goal_info">
                            First 50 orders will get THE BENEFACTOR: Any pack above that you want, PLUS: Another exclusive comic will go in everyone’s book. Your name and a mini-port..
                        </div>

                    </div>
                    <!----------------------------------------------------------------> 




                    <!--End Of Block Goal-->


                </div>
                <!--End Of Option Column-->
         

        </div>
        <!--End of shop container-->
    </div>
    <!--End of body content-->
