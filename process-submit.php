<?php
include "block/logged_in.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submissions.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submission_image.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.message.php';
$submission = new submissions();
$message = new message();
$sub_img = new submission_image();
if (!isset($_POST["design_title"])|| !isset($_SESSION["user_id"]) || !isset($_POST["img_url"]))
{echo 'shit'; return;}
$submission->title = $_POST["design_title"];
$submission->comment = $_POST["comment"];
$submission->user_id = $_SESSION["user_id"];
$submission->newsletter = $_POST["newsletter"];
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
    $body ="Cheers, ".$_SESSION["user_name"].". Thank you for submitting $submission->title to the competition, your design is up for review as we speak!\n\n.If your design doesn’t make it through, we’ll let you know the little tweaks you gotta make so that it does.In the meantime, why not check out the designs in this competition?\n\nIf you ever need anything, hit us up via email at hello@ikimuk.com, tweet us at @ikimuktweets or call us at (76) 787 606.\n\nLove,\nThe folks at ikimuk\n\nConnect with us,\n https://www.facebook.com/ikimukofficial \n http://www.twitter.com/@ikimukTweets \n http://www.youtube.com/user/ikimukTV";
    $result = $message->send($_SESSION["user_email"], $subject, $body);
    sleep(5);
    echo 'done';return;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
