<?php
include "block/logged_in.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submissions.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submission_image.php';
$submission = new submissions();
$sub_img = new submission_image();
if (!isset($_POST["design_title"])|| !isset($_SESSION["user_id"]) || !isset($_POST["img_url"]))
{echo 'shit'; return;}
$submission->title = $_POST["design_title"];
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
    echo 'done';return;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
