<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
$selected = Array ("unselected","selected","unselected","unselected","unselected" );
$pagetitle = "Submit Guidelines";
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/class.competition.php';
$competition = new competition();
$competition->select_open_submission();
$daysLeft = floor((strtotime($competition->submission_deadline) - time()) / (60 * 60 * 24));
?>
<div class="body">
    <div class="body_content submit">

        <div class="page_header">
            Participate in ikimuk design competition. our current themes are: 
        </div>


        <!--Start of Submit theme section-->
        <div class="submit_theme">
            <a href="/submit.php?competition=<?php echo $competition->id;?>">
            <div class="theme_content std_block">

                <div class="std_block_label">
                
		<div class="label_box"><span class="label_title"><?php echo $daysLeft; ?> Days Left</span></div>
		</div>
                <div class="theme_transparent"><div class="transparent_text">Submit your design now</div></div>
                <div class="theme_avatar"><img src="<?php echo $competition->submission_header?>"></div>
                <div class="theme_title"><?php echo $competition->title ?></div>
                <div class="theme_date">Submit before <?php echo date("d/m/Y", strtotime($competition->submission_deadline)); ?></div>
            </div>
            </a>
            <a href="/submit.php?competition=0">
            <div class="theme_content marginl20 std_block">

                <div class="std_block_label">
		<div class="label_box"><span class="label_title">Always Open</span></div>
		</div>
                <div class="theme_transparent"><div class="transparent_text">Submit your design now</div></div>
                <div class="theme_avatar"><img src="/images/submit_photo.png"></div>
                <div class="theme_title">Open Submission</div>
                <!--<div class="theme_date">Submit before 21/1/2013</div>-->
            </div>
                </a>    
        </div>
        <!--End Of Submit Theme Section-->


        <div class="body_theme">


            <div class="theme_how">
                <!--Start of body block-->
                <div class="std_block submit_body">

                    <!--Start of cart table header-->
                    <div class="std_block_label">
                        <div class="label_box">
                            <span class="label_title">How To Submit</span>
                                </div>
                    </div>
                    <!--End of cart table header-->



                    <!--Start of submit body-->
                    <div class="std_block_body" style="margin:20px">
		    <div style="margin:-20px 0px 20px -20px; width:570px" class="line_link">
                        <div class="link_holder">
                                     <a href="#">Legal Stuff</a>
                                    </div>
                                </div>
                        <!--Start of submit content-->
                        <div class="submit_content">
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
                               
                        </div>
                        <!--End of submit content-->

                        <div class="submit_button" style="margin-top: 10px;">
                            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) { ?>
                                <form action="/submit.php" method="post">
                                    <div style="width:300px; margin: auto">
				    <input type="submit" value="Submit your design"/>
				    </div>
                                </form>
                            <?php } else { ?>
                                <a href="#login" data-toggle="modal" style="text-decoration: none"><div class="fake_button">submit your design</div></a>
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