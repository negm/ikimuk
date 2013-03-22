<?php
if (!isset($_SESSION))
{
    session_start ();
}
if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"])
{
    header("Location: /submit-intro.php");
}
include $_SERVER["DOCUMENT_ROOT"] . "/block/logged_in.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.competition.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.artist.php";
$pagetitle = "Submit your design";
$competition = new competition();
$competition->select_open_submission();
$artist = new artist();
$artist->user_id = $_SESSION["user_id"];
$artist->select_by_user_id();
$selected_comp = isset($_GET["competition"])? $_GET["competition"]:0;
include_once $_SERVER["DOCUMENT_ROOT"] . '/block/header.php';
$inpage_script='
<script>
    var uploaded = false;
var img_list = new Array();
    $(function(){
        var btnUploadSubmit=$("#upload");
        var status=$("#status");
        new AjaxUpload(btnUploadSubmit, {
            action: "process_upload.php",
            name: "uploadfileSubmit",
            onSubmit: function(file, ext){
                $("#img_size_g").removeClass("alertr").addClass("hidden");
                if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 

                    $("#img_url").parent().parent().find(".line_error").text("Only JPG, PNG files are allowed");
                    return false;
                }
                status.text("Uploading...");
            },
            onComplete: function(file, response){
                
                status.text("");
                
                if(response != "error" && response != "size_error"){
                    $("<li></li>").appendTo("#files").html(\'<img class="img-rounded" src="\'+response+\'" alt="" />\').addClass("success");
                    $("#upload").html("Upload another file");
                    img_list.push(response);
                    $("#img_url").val(img_list);
                    uploaded = true;
                }
                if (response === "size_error")
                {
                    $("#img_url").parent().parent().find(".line_error").text("Image is larger than 250 KB");  return false;
                }
                else{
                    /*$("<li></li>).appendTo("#files").text(file).addClass("error");*/
                }
            }
        });
    }); </script> ';

$selected = Array ("unselected","selected","unselected","unselected","unselected" );
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
?>
<div class="body">
    <div class="body_content">
      <div class='alert alert-error hide'> 
<button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Oops!</strong> Something went wrong. Please try submitting your design again.</div>

        <div class="submit_design">

            <div class="page_header">
                <?php echo _txt("submit")." "._txt("yourdesign");?>
            </div>


            <!--Start of Choose Competition-->
            <div class="choose_competition">
                <div class="std_block">
      <div id="error_link"></div>
      <!--Start of cart table header-->
                    <div class="std_block_label">
                        <div class="label_box">
			     <span class="label_title">1. <?php echo _txt("choosecompetition");?></span>
                        </div>
                    </div>
                    <!--End of cart table header-->

                    <!--Start of submit body-->
                    <div class="std_block_body type_body">

                        <?php
                            if ($selected_comp == $competition->id){
                            echo '<div class="type_select"><input type="radio" name="competition_type" value="'
                            . $competition->id . '" checked="checked"/>   ' . $competition->title .
                            "  ( " . _txt("submitbefore")." ".date("d/m/Y", strtotime($competition->submission_deadline)) . ')</div>';
                                ?>
                            <div class="type_select"><input type="radio" name="competition_type" value="0" > Open Submission</div>
                            <?php } else {
                                echo '<div class="type_select"><input type="radio" name="competition_type" value="'
                            . $competition->id . '" />   ' . $competition->title .
                            " "._txt("submitbefore")." " . date("d/m/Y", strtotime($competition->submission_deadline)) . '</div>';
                                ?>
                            <div class="type_select"><input type="radio" name="competition_type" value="0" checked="checked"> Open Submission</div>
                            <?php }   ?>
                    </div>
                    <!--End of submit body-->
                </div> 
            </div>
            <!--End of Choose Competition-->


            <!--Start of Add Images-->
            <div class="add_images">
                <div class="std_block block_expandable">

                    <!--Start of cart table header-->
                    <div class="std_block_label">
                        <div class="label_box">
			     <span class="label_title">2. <?php echo _txt("addimages");?></span>
                        </div>
                    </div>
                    <!--End of cart table header-->

                    <!--Start of submit body-->
                    <div class="std_block_body">
                        <div class="upload_body_content">
                            <span class="upload_info"><?php echo _txt("designguide");?> </span>
                            <ul id="files" class="thumb"></ul>
                            <div class="upload_holder">
                                <input type="button" id="upload" value="<?php echo _txt("uploadfile");?>" style=""/>
                                <div class="line_info">
                                    <input type="hidden" id="img_url">
                                    <div class="line_error"></div></div>
                            </div>

                        </div>

                    </div>
                    <!--End of submit body-->
                </div> 
            </div>
            <!--End of Add Images-->


            <!--Start of Design Info-->
            <div class="design_info">
                <div class="std_block block_expandable">

                    <!--Start of cart table header-->
                    <div class="std_block_label">
                        <div class="label_box">
                            <span class="label_title">3. <?php echo _txt("designinfo");?></span>
                        </div>
                    </div>
                    <!--End of cart table header-->

                    <!--Start of submit body-->
                    <div class="std_block_body info_body">

                        <div class="line_info">   
                            <div class="line_header"><?php echo _txt("designtitle");?></div>
                            <div class="line_input">
                                <input type="text" class="round_corners" name="design_title"/>
                            </div>
                            <div class="line_error"></div>
                        </div>


                        <div class="line_info">   
                            <div class="line_header"><?php echo _txt("designdesc");?></div>
                            <div class="line_input">
                                <textarea  class="round_corners" name="design_details"></textarea>
                            </div>
                            <div class="line_error"></div>
                        </div>


                    </div>
                    <!--End of submit body-->
                </div> 
            </div> 
            <!--End of Design Info-->



            <!--Start of Self Info-->   
            <div class="self_info">
                <div class="std_block block_expandable">

                    <!--Start of cart table header-->
                    <div class="std_block_label">
                        <div class="label_box">
                            <span class="label_title">4. <?php echo _txt("yourinfo");?></span>
                        </div>
                    </div>
                    <!--End of cart table header-->

                    <!--Start of submit body-->
                    <div class="std_block_body self_info_body">

                        <div class="line_info">   
                            <div class="line_header"><?php echo _txt("city");?></div>
                            <div class="line_input">
                                <input type="text" class="round_corners" name="city" value="<?php echo htmlentities(urldecode($artist->location)); ?>"/>
                            </div>
                            <div class="line_error"></div>
                        </div>


                        <div class="line_info">   
                            <div class="line_header"><?php echo _txt("blog");?></div>
                            <div class="line_input">
                                <input type="text" class="round_corners" name="website_blog_1" value="<?php echo htmlentities(urldecode($artist->website)); ?>"/>
                            </div>
                            <div class="line_error"></div>
                        </div>


                        <div class="line_info">   
                            <div class="line_header"><?php echo _txt("twitter");?></div>
                            <div class="line_input">
                                <input type="text" class="round_corners" name="website_blog_2" value="<?php echo htmlentities(urldecode($artist->twitter)); ?>"/>
                            </div>
                            <div class="line_error"></div>
                        </div>


                    </div>
                    <!--End of submit body-->
                </div> 
            </div>
            <!--End of self Info-->


            <!--Start of agreeement Section-->
            <div class="agreement_submit_section">


                <div class="agreement">
                    <div class="terms_conditions">
                        <input type="checkbox" name="agree"/>
                        <span><?php echo _txt("agree");?></span>
                        <a href="/terms.php" target="_blank"><?php echo _txt("termsanch");?></a>
                    </div>
                    <div class="line_error"></div>
                </div>

                <div class="newsletter">
                    <input type="checkbox" name="subscribe"/>
                    <span><?php echo _txt("newsletteragree");?></span>
                </div>


                <div class="submit_personal_design">
                    <input type="button" name="submit_design" value="<?php echo _txt("submit");?>" />
                </div>

            </div>      
            <!--End Of agreement section-->

        </div>
        <!--End of submit design-->

    </div>    

    <!--End of body content-->

</div>
<!--End of body-->
<?php
include $_SERVER["DOCUMENT_ROOT"] . '/block/footer.php';
?>