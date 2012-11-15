<?php
include "block/logged_in.php";
require_once 'class/class.submissions.php';
$submission = new submissions();
$submission->image_url = $_POST["img_url"];
$submission->title = $_POST["design_title"];
$submission->user_id = $_SESSION["user_id"];
$submission->insert();
if ($submission->database->result == 1)
{echo'done';return;}
else
{echo 'shit';}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
