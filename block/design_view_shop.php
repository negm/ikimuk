<?php
/*
 * This is the design view with multiple images and thumbnail
 * 
 */
//show the goodies :D  
?>
<div class="body">

    <div class="body_content">

        <div class="shop_container">


            <!--Start Of order progress-->
            <div class="order_progress">


                <!--Start Of order progress Container-->
                <div class="order_progress_container">


                    <!--Start of Flags Container-->
                    <div class="flags_container">
                        <a href="#block-goal-1">
			<div class="flag_container margin_l_100 flag_cyan">
                            <div class="flag_medal"></div>
                        </div>
			</a>

			<a href="#block-goal-2">
                        <div class="flag_container margin_l_50 flag_green">
                            <div class="flag_medal"></div>
                        </div>
			</a>

			<a href="#block-goal-3">
                        <div class="flag_container margin_l_50 flag_yellow">
                            <div class="flag_medal"></div>
                        </div>
			</a>

			<a href="#block-goal-4">
                        <div class="flag_container margin_l_200 flag_firebrick">
                            <div class="flag_medal"></div>
                        </div>
			</a>

			<a href="#block-goal-5">
                        <div class="flag_container margin_l_400 flag_magenta">
                            <div class="flag_medal"></div>
                        </div>
			</a>
                    </div>
                    <!--End of Flags Container-->



                    <!--Start of Progress Bar Container-->
                    <div class="progress_bars_container"> 

			<a href="#block-goal-1">
                        <div class="progress progress_flag_1">
                            <div class="bar progress_cyan" style="width: <?php echo $product->preorders * (100 / $settings->first_goal); ?>%;"></div>
                        </div>
			</a>

			<a href="#block-goal-2">
                        <div class="progress progress_flag_2">
                            <div class="bar progress_green" style="width: <?php echo ($product->preorders - $settings->first_goal) * (100 / $settings->second_goal); ?>%;"></div>
                        </div>
			</a>

			<a href="#block-goal-3">
                        <div class="progress progress_flag_3">
                            <div class="bar progress_yellow" style="width: <?php echo ($product->preorders - $settings->second_goal) * (100 / $settings->third_goal); ?>%;"></div>
                        </div>
			</a>

			<a href="#block-goal-4">
                        <div class="progress progress_flag_4">
                            <div class="bar progress_firebrick" style="width:<?php echo ($product->preorders - $settings->third_goal) * (100 / $settings->fourth_goal); ?>%;"></div>
                        </div>
			</a>

			<a href="#block-goal-5">
                        <div class="progress progress_flag_5">
                            <div class="bar progress_magenta" style="width: <?php echo ($product->preorders - $settings->fourth_goal) * (100 / $settings->fifth_goal); ?>%;"></div>
                        </div>
			</a>

			<a href="#block-goal-6">
                        <div class="progress progress_flag_6">
                            <div class="bar progress_red" style="width: <?php echo ($product->preorders - $settings->fifth_goal) * (100 / $settings->sixth_goal); ?>%;"></div>
                        </div>
			</a>
                        <div class="progress_over over">
                            <div class="progress_cube"></div>
                            <div class="progress_cube"></div>
                            <div class="progress_cube"></div>
                        </div>
                    </div>
                    <!--End of Progress Bar Container-->

		    <div class="flags_container">
                    
			<div class="flag_container margin_l_100 flag_cyan flag_number">
                            <div>50</div>
                        </div>
		
                        <div class="flag_container margin_l_50 flag_green flag_number">
                            75
                        </div>
                        <div class="flag_container margin_l_50 flag_yellow flag_number">
                            100
                        </div>
                        <div class="flag_container margin_l_200 flag_firebrick flag_number">
                            200
                        </div>
                        <div class="flag_container margin_l_400 flag_magenta flag_number">
                            500
                        </div>
                    </div>

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

                            <div class="social_label_left">  <?php echo $daysLeft;?> Days left</div>
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


                            <div class="fb-like" data-href="<?php echo $settings->root; ?>" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
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
                            Drop your comment, toughts, support, be nice
                        </div>

                        <div class="social_body">

                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $settings->app_id?>";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-comments" data-width="576" data-num-posts="4" data-href="http://www.elnashra.com/news/show/576619" data-colorscheme="light"></div>
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

                        <!--Start Of Cart Body--> 
                        <div class="cart_body">

                            <!--Start Of Cart Selection--> 
                            <div class="cart_size_selection">

                                <!--Start Of Male Part--> 
                                <div class="size_selection_header">GUY's - $<?php echo number_format($product->price, 2); ?> 
                                    <span class="order_size_info"><a href="#">Size Info</a></span>
                                </div>

                                <div class="selection_container male_part">

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="s"/>
                                        <div>S</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="m"/>
                                        <div>M</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="l"/>
                                        <div>L</div>
                                    </div>


                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="xl"/>
                                        <div>XL</div>
                                    </div>

                                    <div class="empty_space"></div>
                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="xxl"/>
                                        <div>XXL</div>
                                    </div>
                                </div>
                                <!--End Of Male Part--> 

                                <!--Start Of Female Part--> 
                                <div class="size_selection_header">GIRL's - $<?php echo number_format($product->price, 2); ?>
                                    <span class="order_size_info">
                                        <a href="#">Size Info</a>
                                    </span>
                                </div>
                                <div class="selection_container female_part">

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="s"/>
                                        <div>S</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="m"/>
                                        <div>M</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="l"/>
                                        <div>L</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="xl"/>
                                        <div>XL</div>
                                    </div>

                                    <div class="empty_space"></div>

                                    <div class="cart_no">
                                        <input type="hidden" name="size" value="xxl"/>
                                        <div>XXL</div>
                                    </div>

                                </div>
                                <!--End Of Male Part--> 



                            </div>
                            <!--End Of cart size selection--> 



                        </div>
                        <!--End Of Cart Selection--> 



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
                        <div class="profile_website"><?php echo $artist->website ?></div>
                        <div class="profile_twitter"><?php echo $artist->twitter ?></div>

                    </div>
                    <!--End Of Block Profile-->




                    <!----------------------------------------------------------------> 
                    <div class="block_goal block_goal_cyan" id="block-goal-1">

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
                                <div class="bar progress_cyan" style="width:100%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php echo $settings->first_goal."/".$settings->first_goal; ?>
                            </div>

                        </div>


                        <!--<div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>-->

                    </div>
                    <!---------------------------------------------------------------->

                    <!----------------------------------------------------------------> 

                    <div class="block_goal block_goal_green" id="block-goal-2">

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
                                <div class="bar progress_green" style="width:100%;"></div>
                            </div>                            
                            <div class="progress_percentage">
                                <?php if($product->preorders> $settings->second_goal)  echo $settings->second_goal."/".$settings->second_goal; 
                                else echo $product->preorders."/".$settings->second_goal; 
                                ?>
                            </div>

                        </div>


                        <!--<div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>-->

                    </div>
                    <!----------------------------------------------------------------> 


                    <!----------------------------------------------------------------> 
                    <div class="block_goal block_goal_yellow" id="block-goal-3">

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
                                <?php if($product->preorders> $settings->third_goal)  echo $settings->third_goal."/".$settings->third_goal; 
                                else echo $product->preorders."/".$settings->third_goal; 
                                ?>
                            </div>

                        </div>


                        <!--<div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>-->

                    </div>
                    <!----------------------------------------------------------------> 

                    <!----------------------------------------------------------------> 

                    <div class="block_goal block_goal_firebrick" id="block-goal-4">

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
                                <?php if($product->preorders> $settings->fourth_goal)  echo $settings->fourth_goal."/".$settings->fourth_goal; 
                                else echo $product->preorders."/".$settings->fourth_goal; 
                                ?>
                            </div>

                        </div>


                        <!--<div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>-->

                    </div>
                    <!----------------------------------------------------------------> 


                    <!---------------------------------------------------------------->     

                    <div class="block_goal block_goal_magenta" id="block-goal-5">

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
                                <?php if($product->preorders> $settings->fifth_goal)  echo $settings->fifth_goal."/".$settings->fifth_goal; 
                                else echo $product->preorders."/".$settings->fifth_goal; 
                                ?>
                            </div>

                        </div>


                        <!--<div class="goal_remaining">
                            50 Orders till the T-shirt gets printed
                        </div>-->

                    </div>
                    <!----------------------------------------------------------------> 

                    <!----------------------------------------------------------------> 
                    <div class="block_goal block_goal_red" id="block-goal-6">

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

        </div>
        <!--End of shop container-->
    </div>
    <!--End of body content-->
