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
{ 
    echo '<div class="body"><div class="body_content">';
    while($row = mysqli_fetch_object($interview_list->database->result)){
?>
<br><div class="well" dir="ltr">
    <b><?php echo $row->title?></b>
    <div class="small_part"> <?php echo substr($row->body, 0, 400).'><a href="/interviews/'.$row->id.'">.....read more </a>';?></div>
</div>
<?php }
echo '</div></div>';
} else {?>
<div class="body"><div class="body_content" dir="ltr">
        <h1><?php echo $interview->title; ?></h1>
        <div><?php echo $interview->body; ?></div>
        <div class="fb-comments" data-width="576" data-num-posts="15" data-href="<?php echo $settings->root."interviews.php?interview_id=".$interview->id; ?>" data-colorscheme="light"></div>
    </div></div>
<?php } echo "</div>"; include $_SERVER["DOCUMENT_ROOT"]."/block/footer.php";?>