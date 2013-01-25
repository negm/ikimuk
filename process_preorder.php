<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
include_once '/block/logged_in.php';
require "/class/class.preorder.php";
require "/class/class.competition.php";
require "/class/class.product.php";
require "/class/class.artist.php";
require "/class/class.message.php";
require_once("/inc/facebook.php" );
Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
if (isset($_POST["action"]))
{
    if ($_POST["action"] == 'add')
        return addPreorder();
    if ($_POST["action"] == 'cancel')
        return cancelPreorder();
    else
        return 'leave in peace bro :)';
}
function addPreorder()
{
$settings = new settings();
$message = new message();
$product = new product();
$artist = new artist();
$competition = new competition();

$param = $_POST;
$preorder = new preorder();
$preorder->user_id = $_SESSION["user_id"];
if (!$param["name"] || strlen(trim($param["name"])) < 5)
{return 'name error'; }
if (!$param["email"] || strlen(trim($param["email"])) < 9 )
{return 'email error'; }
if (!isset($_SESSION["validated_mobile"]))
{
if (!$param["ccode"] || strlen(trim($param["ccode"])) < 1 )
{return 'country coode error'; }
if (!$param["monum"] || strlen(trim($param["monum"])) < 6 || strlen(trim($param["monum"])) > 8 )
{return 'mobile error'; }
if (!$param["vcode"] || strlen(trim($param["vcode"])) < 4 ||trim($param["vcode"])!= $_SESSION["sms_code"])
{return 'verification error'; }
$preorder->phone = $param["ccode"].$param["monum"];
}
 else {
 $preorder->phone = $_SESSION["validated_mobile"];
 }
if (!$param["address"] || strlen(trim($param["address"])) < 4 )
{return 'address error'; }
$preorder->address = $param["address"];
if (isset($param["size"]))
{
if (!$param["size"] || strlen(trim($param["size"])) < 1 )
{return 'size error'; }
$preorder->size = $param["size"];
}
else
{return 'size error'; }
if (isset($param["agreement"]))
{
if (!$param["agreement"] )
{return 'agreement error'; }
}
else
{return 'agreement error'; }
if (strlen(trim($param["design_id"])) < 1 )
{return 'design error'; }
$preorder->product_id = $param["design_id"];
if ($preorder->alreadyPreordered())
{return 'already voted'; }
if (!isset($param["region"]))
{return 'region error';}
$preorder->region = $param["region"];
$preorder->newsletter = $param["newsletter"];
$preorder->country = 'Lebanon';
$preorder->insert();
$_SESSION["validated_mobile"] = $preorder->phone;

$product ->select(($param["design_id"]));
if($product->id == null) {return 'design error'; }
$artist->select($product->artist_id);
$competition->select($product->competition_id);
$datestr = date("l jS \of F Y",  strtotime($competition->end_date));
$subject = 'Your ikimuk preorder confirmation';
$body ="Cheers, ".$_SESSION["user_name"].". Thank you for preordering! Your participation is what makes ikimuk possible.We’ll let you know if $product->title by $artist->name wins the competition.\n\nSo just to RECAP: The competition finishes on $datestr. You will receive this t-shirt only if it gets the most preorders.\n\nIf you ever need anything, hit us up via email at hello@ikimuk.com, tweet us at @ikimuktweets or call us at (76) 787 606.\n\nLove,\nThe folks at ikimuk\n\nConnect with us,\n https://www.facebook.com/ikimukofficial \n http://www.twitter.com/@ikimukTweets \n http://www.youtube.com/user/ikimukTV";
$result = $message->send($_SESSION["user_email"], $subject, $body);
sleep(5);
try {postToFB($product->id, $settings->app_id, $settings->app_secret);}
catch (FacebookApiException $e) 
		{error_log($e);}
return 'done';
}

function postToFB($product_id,$api_key,$api_secret)
{
$facebook = new Facebook(array(
'appId'=>$api_key,
'secret'=>$api_secret
));
$params = array('design'=>'http://beta.ikimuk.com/design.php?product_id='.$product_id,'access_token'=>$_SESSION["access_token"]);
$out = $facebook->api('/me/ikimukapp:preorder','post',$params);
}
?>
