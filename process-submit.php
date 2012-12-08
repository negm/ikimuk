<?php
include "block/logged_in.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submissions.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submission_image.php';
$submission = new submissions();
$sub_img = new submission_image();
if (!isset($_POST["design_title"])|| !isset($_SESSION["user_id"]) || !isset($_POST["img_url"]))
{echo 'shit'; return;}
$submission->title = $_POST["design_title"];
$submission->comment = $_POST["comment"];
$submission->user_id = $_SESSION["user_id"];
$img_arr = explode(',',$_POST["img_url"]);
if(count($img_arr) <1)
{
echo 'shit';
return;
}
$submission->insert();
if ($submission->id == NULL)
{echo'shit';return;}
else
{
    $sub_img->submission_id = $submission->id;
    foreach ($img_arr as $img)
    {
        $sub_img->url = $img;
        $sub_img->insert();
        if ($sub_img->id == null)
        {echo 'shit'; return;}
    }
    
    $subject = 'Confirming Your submission on Ikimuk';
    $body ="Hello ".$_SESSION["user_name"]." \n \n Your design has been received and we will contact you soon";
    $result = $message->send($_SESSION["user_email"], $subject, $body);
    sleep(5);
    echo 'done';return;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
