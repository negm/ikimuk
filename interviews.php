<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER["DOCUMENT_ROOT"]."/class/class.interviews.php";
//include $_SERVER["DOCUMENT_ROOT"]."/class/settings.php";
$selected = Array ("unselected","unselected","unselected","selected","unselected","unselected" );
$settings = new settings();
if(isset($_GET["interview_id"]) && is_numeric($_GET["interview_id"]))
{
    $list = false;
    $interview = new interviews();
    $interview->select($_GET["interview_id"]);   
    $pagetitle = $interview->title;
}
 else {
$list = true;
$pagetitle = "Artist Interviews";
$interview_list = new interviews();
$interview_list->get_list();
}
include $_SERVER["DOCUMENT_ROOT"]."/block/header.php";
include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";
if ($list)
{ $count = 0; ?>
    <div class="body" dir="ltr">
        <div class="body_content">
            <!--Start of stories section-->
                    <div class="stories_section">
          <div class="stories_header">
                            Meet ikimuk's designers
                        </div>
                         <!--Start of stories container-->
                        <div class="stories_container">
<?php    while($row = mysqli_fetch_object($interview_list->database->result)){
?>
 
<div class="designer" style="<?php if ($count % 3 == 0) echo 'margin-left:10px;'; ?>">
    <!--Used to set a link when clicking-->
                                    <input type="hidden" name="designer_id" value="/interviews/<?php echo $row->id;?>"/>

                                    <div class="designer_transparent">
                                        <div class="designer_interview_details">
                                            Read Full Interview
                                        </div>
                                    </div>


                                    <div class="designer_name">
                                        <div class="name_content">
                                            <?php echo $row->title; ?>
                                        </div>
                                    </div>



                                    <div class="designer_avatar">
                                        <img src="<?php echo $row->image; ?>"/>
                                    </div>

                                    <div class="designer_control">

                                        <div class="designer_description">
                                            <?php echo $row->title; ?>
                                        </div>
                                        <div class="designer_no_print">
                                            Previous ikimuk winner
                                        </div>
                                    </div>
                                    <!--End of designer control-->

                                </div> 
                                <!-- End of designer item-->
<?php $count+=1; }?>
</div></div></div></div>
<?php } else {?>
<div class="body">
    <div class="body_content" dir="ltr">
        <div class="interview_section">
        <div class="interview_header"><?php echo $interview->title; ?></div>
         <div class="interview_body">
             <div class="std_block">
        <?php echo $interview->body; ?>
        <div class="fb-comments" data-width="900" data-num-posts="15" data-href="<?php echo $settings->root."interviews.php?interview_id=".$interview->id; ?>" data-colorscheme="light"></div>
    </div>
         </div>
        </div>
    </div>
</div>
<?php } echo "</div>"; include $_SERVER["DOCUMENT_ROOT"]."/block/footer.php";?>