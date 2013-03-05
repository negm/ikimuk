<?php
if (!isset($_SESSION))
{
    session_start ();
}
require_once($_SERVER["DOCUMENT_ROOT"]."/class/settings.php"); //Include configuration file.
require_once ($_SERVER["DOCUMENT_ROOT"]."/class/class.user.php");
$user = new user();
if (!isset($_GET["code"]))
    header("Location: /index.php");
else{
    $code=  urldecode(trim($_GET["code"]));
if (!$user->check_reset_code($code))
     header("Location: /index.php");
else{
    $_SESSION["user_id"] = $user->id;
}
}
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
<div class="alert alert-error hide"> <button type="button" class="close" data-dismiss="alert">×</button> <span id="reset_error"></span></div>
        <!--Start of email section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               Password
                           </div>
                           
                            <div class="member_line_input">
                               <input id="password_reset" class="round_corners" type="password" name="password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title">Confirm Password</div>
                           
                            <div class="member_line_input">
                               <input id="password_reset_confirm" class="round_corners" type="password" name="confirm_password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
    
                        <div class="member_join">
                           <input id="change_password_reset" type="submit" name="join" value="Reset Password"/>
                       </div>
    
    
    
    </div>
</div>
<?php include $_SERVER["DOCUMENT_ROOT"]."/block/footer.php";?>