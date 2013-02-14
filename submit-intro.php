<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
$pagetitle = "Submit Guidelines";
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
?>
<div class="body">
    <div class="body_content submit">

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

        <div class="submit_header">
            Participate in ikimuk design competition. our current themes are: 
        </div>


        <!--Start of Submit theme section-->
        <div class="submit_theme">

            <div class="theme_content">

                <div class="theme_label"></div>
                <div class="theme_transparent"><div class="transparent_text">Submit your design now</div></div>
                <div class="theme_avatar"><img src="images/submit_photo.png"></div>
                <div class="theme_title">Hakwaji</div>
                <div class="theme_date">Submit before 21/1/2013</div>
            </div>


            <div class="theme_content marginl20">

                <div class="theme_label"></div>
                <div class="theme_transparent"><div class="transparent_text">Submit your design now</div></div>
                <div class="theme_avatar"><img src="images/submit_photo.png"></div>
                <div class="theme_title">For The Love of Zombies</div>
                <div class="theme_date">Submit before 21/1/2013</div>
            </div>
        </div>
        <!--End Of Submit Theme Section-->


        <div class="body_theme">


            <div class="theme_how">
                <!--Start of body block-->
                <div class="std_block submit_body">

                    <!--Start of cart table header-->
                    <div class="std_block_header">
                        <div class="header_content">
                            How To Submit
                            <div class="edit_link"><a href="#">Legal Stuff</a></div>
                        </div>
                    </div>
                    <!--End of cart table header-->



                    <!--Start of submit body-->
                    <div class="std_block_body">

                        <!--Start of submit content-->
                        <div class="submit_content"><pre>
<span>1- The Great T-shirt Idea</span>
Come up with an idea for what you want on a t-shirt. Be as creative as you possibly can!


<span>2- Make It Digital</span>
Turn the idea into a design by using either Photoshop(*.psd) or Illustrator (*.ai).

Just to make sure your design won’t get bounced back to you, make sure it’s compatible with these 
guidelines! 

	Guidelines: 

	-Your file should be in high resolution (300 dpi)
	-Canvas size of 40cm x 60cm 
	-Maximum of 6 colors (with each color on a separate layer)
	-Submit a jpeg web version of your design 


<span>3- Submit It!</span>
As soon as you’ve checked the guidelines, it’s time to send it to us at hello@ikimuk.com 
We will let you know if your design made it through to the contest via email!

You will only receive a rejection if:

	-Your design has copyright infringement or is a duplicate submission
	-Your design has offensive or inappropriate content 
	-The file you sent is damaged or the design needs more work


<span>4- Promote, Promote</span>
If your design made it through to the contest, get your friends to preorder it so that your design 
gets the attention it deserves


<span>5- Let The Best Design Win</span>
If your design gets the most preorders: 

	- It gets printed and sold!	
	- An upfront cash payment of 300$.
	- Royalties of 3% on every T-shirt sold
	- You get the T-Shirt for free, and sent to your front door

Good luck and remember to have fun! If you have ANY questions please let us know and we’ll get 
right back to you!  
                            </pre>   
                        </div>
                        <!--End of submit content-->

                        <div class="submit_button">
                            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) { ?>
                                <form action="/submit.php" method="post">
                                    <input type="submit" value="submit your design"/>
                                </form>
                            <?php } else { ?>
                                <a href="#login" data-toggle="modal" style="text-decoration: none"><div class="dummy_button">submit your design</div></a>
                            <?php } ?>

                        </div>       


                    </div>
                    <!--End of submit body-->
                </div> 
                <!--End of body block-->

            </div>

            <div class="theme_panel"></div>

        </div>

    </div>       
</div>
<?php
include $_SERVER["DOCUMENT_ROOT"] . '/block/footer.php';
?>