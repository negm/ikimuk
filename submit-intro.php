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
            Participate in an ikimuk competition 
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
		<div class="label_box"><span class="label_title">Ongoing</span></div>
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
  <a href="/submission_terms.php" target="_blank">Terms &amp; Conditions</a>
                                    </div>
                                </div>
                        <!--Start of submit content-->
                        <div class="submit_content">

	<p>
		After picking a theme, it's time to create your design.
	</p>

	<div>
		<h2 class="submit_title">
			1. Eureka, the T-shirt idea!
		</h2> 

		<div>
			<p>
				Come up with a mind-blowing idea for a T-shirt.
			</p>
		</div>
	</div> 

	<div>
		<h2 class="submit_title">
			2. Make it Digital
		</h2>

		<div>
			<p>
				Turn the idea into a design by using either Photoshop (*.psd) or Illustrator (*.ai).
			</p>

			<p>
				Guidelines:
			</p>

			<ul>
				<li>Your file should be high resolution (300 dpi).</li>
				<li>Canvas size of 40 cm x 60 cm.</li>
				<li>Maximum of 6 colors (with each color on a separate layer).</li>
			</ul>
		</div>
	</div>


	<div>
		<h2 class="submit_title">
			3. Submit to a competition
		</h2>

		<div> 

			<p>
				After you've made sure that your design is compatible with the guidelines, here's what's going to happen:
			<p>

		<ul>
			<li>Submit a JPG web version of your design (RGB 300 px wide x 450 px tall).</li>
			<li>We will confirm receipt of your design.</li>
			<li>You will know if you made it through to the contest via email.</li>
		</ul>

			<p>
				Your design will be rejected if:
			</p>	

			<ul>
				<li>It has copyright infringement or is a duplicate submission.</li>
				<li>It has offensive or inappropriate content.</li>
				<li>The file you sent is damaged or the design needs more work.</li>
			</ul>
		</div>
	</div> 

	<div>
		<h2 class="submit_title">
			4. Promote your design
		</h2>

		<div>
								<p>To get more orders, you can:</p>
								<ul>
				<li>Tweet about it.</li>  
				<li>Share it.</li> 
				<li>Mention it on your blog.</li>
				<li>Tell your friends about it!</li>
			</p>
		</div>
	</div> 


	<div>
		<h2 class="submit_title">
		5. Reach for goals
		</h2>

		<div>
			<ul>
				<li>
						Goal 1 - 35 orders: Once you reach this goal, your design will get printed, and you will receive $50. If you are the first one to reach this goal in the competition, you receive an additional $50.
				</li>
				<li>
		    Goal 2 - 75 orders: Once you reach this goal, you get an extra $100. If you are the first one to reach this goal in the competition, you receive an additional $50.
				</li>
			</ul>

			<p>
				By the end of the competition, if your design receives the most orders, you earn $100 on top of the money you made.
			</p>
		</div>
	</div>
                               
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

            <div class="theme_panel"><img src="/images/submission-side-image.jpg"></div>

        </div>

    </div>       
</div>
<?php
include $_SERVER["DOCUMENT_ROOT"] . '/block/footer.php';
?>