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
$daysLeft = $daysLeft<0 ? 0: $daysLeft;
?>
<div class="body">
    <div class="body_content submit">

        <div class="page_header">
            <?php echo _txt("participate");?>
        </div>


        <!--Start of Submit theme section-->
        <div class="submit_theme">
            <?php if($competition->id!= null){if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) { ?>
                               <a href="/submit.php?competition=<?php echo $competition->id;?>">
                            <?php } else { ?>
                                <a href="#login" data-toggle="modal" style="text-decoration: none">
                            <?php } ?>
            
            <div class="theme_content std_block">

                <div class="std_block_label">
                
		<div class="label_box"><span class="label_title"><?php echo $daysLeft." "._txt("daysleft"); ?></span></div>
		</div>
                <div class="theme_transparent"><div class="transparent_text"><?php echo _txt("submitnow");?></div></div>
                <div class="theme_avatar"><img src="<?php echo $competition->submission_header?>"></div>
                <div class="theme_title"><?php echo $competition->title ?></div>
                <div class="theme_date"> <?php echo _txt("submitbefore")." ".date("d/m/Y", strtotime($competition->submission_deadline)); ?></div>
            </div>
            </a><?php } ?>
             <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) { ?>
                               <a href="/submit.php?competition=0">
                            <?php } else { ?>
                                <a href="#login" data-toggle="modal" style="text-decoration: none">
                            <?php } ?>
            
             <div class="theme_content marginl20 std_block">

                <div class="std_block_label">
		<div class="label_box"><span class="label_title"><?php echo _txt("ongoing");?></span></div>
		</div>
                <div class="theme_transparent"><div class="transparent_text"><?php echo _txt("submitnow");?></div></div>
                <div class="theme_avatar"><img src="/images/open_theme_banner_sub_ikimuk.png"></div>
                <div class="theme_title">Open Submission</div>
                <!--<div class="theme_date">Submit before 21/1/2013</div>-->
            </div>
                </a>    
        </div>
        <!--End Of Submit Theme Section-->


        <div class="body_theme">


            <div class="theme_how">
                <?php echo _txt("submit_text");?> 
                
                        <div class="submit_button" style="margin-top: 10px;">
                            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) { ?>
                                <form action="/submit.php" method="post">
                                    <div style="width:300px; margin: auto">
				    <input type="submit" value="<?php echo _txt("submit")." "._txt("yourdesign"); ?>"/>
				    </div>
                                </form>
                            <?php } else { ?>
                            <a href="#login" data-toggle="modal" style="text-decoration: none"><div class="fake_button"><?php echo _txt("submit")." "._txt("yourdesign"); ?></div></a>
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