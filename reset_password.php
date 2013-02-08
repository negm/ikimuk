<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/class/settings.php"); //Include configuration file.
require_once ($_SERVER["DOCUMENT_ROOT"]."/class/class.user.php");
$user = new user();
/*if (!isset($_POST["code"]))
    header("Location: /index.php");
else{
    $code=trim($_POST["code"]);
if (!$user->check_reset_code($code))
     header("Location: /index.php");
}*/
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$pagetitle = "Reset Password";
include $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/top_area.php';
?>
<div class="body">
    <div class="body_content">

        <!--Start of email section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               Password
                           </div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title">Confirm Password</div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="confirm_password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
    
                        <div class="member_join">
                           <input type="submit" name="join" value="Reset Password"/>
                       </div>
    
    
    
    </div>
</div>
