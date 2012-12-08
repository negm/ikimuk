<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
include_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in.php';
require $_SERVER["DOCUMENT_ROOT"]."/class/class.preorder.php";
require $_SERVER["DOCUMENT_ROOT"]."/class/class.message.php";
require_once('inc/facebook.php' );
$settings = new settings();
$message = new message();
//$config = array(
//    'appId' => $settings->app_id,
//    'secret' => $settings->app_secret,
//  );
//$facebook = new Facebook($config);
//$ret_obj = $facebook->api('/me/feed', 'POST',
//                                    array(
//                                      'message' => 'Offfffff'
//                                 ));
//print_r($ret_obj);
//return;
$param = $_POST;
$preorder = new preorder();
$preorder->user_id = $_SESSION["user_id"];
//if (isset($_SESSION["last_preorder_design_id"]))
//{$param["design_id"]= $_SESSION["last_preorder_design_id"];}
//else{echo 'design error'.$_SESSION["last_preorder_design_id"]; return;}
if (!$param["name"] || strlen(trim($param["name"])) < 5)
{echo 'name error'; return;}
if (!$param["email"] || strlen(trim($param["email"])) < 9 )
{echo 'email error'; return;}
if (!isset($_SESSION["validated_mobile"]))
{
if (!$param["ccode"] || strlen(trim($param["ccode"])) < 1 )
{echo 'country coode error'; return;}
if (!$param["monum"] || strlen(trim($param["monum"])) < 6 || strlen(trim($param["monum"])) > 8 )
{echo 'mobile error'; return;}
if (!$param["vcode"] || strlen(trim($param["vcode"])) < 4 ||trim($param["vcode"])!= $_SESSION["sms_code"])
{echo 'verification error'; return;}
$preorder->phone = $param["ccode"].$param["monum"];
}
 else {
 $preorder->phone = $_SESSION["validated_mobile"];
 }
if (!$param["address"] || strlen(trim($param["address"])) < 4 )
{echo 'address error'; return;}
$preorder->address = $param["address"];
if (isset($param["size"]))
{
if (!$param["size"] || strlen(trim($param["size"])) < 1 )
{echo 'size error'; return;}
$preorder->size = $param["size"];
}
else
{echo 'size error'; return;}
if (isset($param["agreement"]))
{
if (!$param["agreement"] )
{echo 'agreement error'; return;}
}
else
{echo 'agreement error'; return;}
if (strlen(trim($param["design_id"])) < 1 )
{echo 'design error'; return;}
$preorder->product_id = $param["design_id"];
if ($preorder->alreadyPreordered())
{echo 'already voted'; return;}
$preorder->country = 'Lebanon';
$preorder->insert();
$_SESSION["validated_mobile"] = $preorder->phone;

$subject = 'Confirming Your preorder on Ikimuk';
$body ="Hello ".$_SESSION["user_name"]." \n \n Your order has been received and we will contact you soon";
$result = $message->send($param["email"], $subject, $body);
sleep(5);
echo 'done';
?>
