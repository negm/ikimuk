<?php
    // Include this function on your pages
include_once $_SERVER["DOCUMENT_ROOT"]."/class/settings.php";
$settings = new settings();
if (!isset($_SESSION))
{
    session_start ();
}
if (!isset($_SESSION["country_name"])|| strlen($_SESSION["country_name"])<2)
{
    include $_SERVER["DOCUMENT_ROOT"]."/inc/ip2country.php";
    $ip2c=new ip2country();
    $_SESSION["country_name"] = $ip2c->get_country_name();
    $_SESSION["delivery_charge"] = $ip2c->delivery_charge;
}
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
else
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
if(!isset($_SESSION["csrf_code"]))
{
    $_SESSION["csrf_code"] = uniqid("ikimuk", true);
}
function print_gzipped_page() {

    global $HTTP_ACCEPT_ENCODING;
    if( headers_sent() ){
        $encoding = false;
    }elseif( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false ){
        $encoding = 'x-gzip';
    }elseif( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false ){
        $encoding = 'gzip';
    }else{
        $encoding = false;
    }

    if( $encoding ){
        $contents = ob_get_contents();
        ob_end_clean();
        header('Content-Encoding: '.$encoding);
        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $size = strlen($contents);
        $contents = gzcompress($contents, 9);
        $contents = substr($contents, 0, $size);
        print($contents);
        exit();
    }else{
        ob_end_flush();
        exit();
    }
}
// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head  prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# ikimukapp: http://ogp.me/ns/fb/ikimukapp#">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome-1">
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
<title><?php echo $pagetitle; ?></title>
<meta name="title" content="Ikimuk">
<meta name="description" content="Preorder your favorite T-shirt designs from leading artists in the Arab World." />
<meta name="keywords" content="browse, catalog, collection, best, amazing, variety, guys, girls, men&#039;s, women&#039;s, t-shirts, tee shirts, tshirts, clothing, design, art, arab, gulf, lebanon, خليج ,عرب, ملابس , موضة , شباب" />
<meta property="og:title" content="" />
<meta property="og:type" content="" />
<meta property="og:url" content="" />
<meta property="og:image" content="" />
<meta property="og:site_name" content="" />
<meta property="fb:admins" content="134500527" />
<meta property="og:site_name" content="Ikimuk" />
<meta property="og:description" content="Cool T-shirt Design">
<meta property="og:type" content="ikimukapp:design" />
<meta property="og:determiner" content="a" />
<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/default/default.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/light/light.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/nivo-slider.css" type="text/css" media="screen" />
<script type="text/javascript" src="/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/js/jquery.form.min.js"></script>
<script type="text/javascript" src="/js/jquery.nivo.slider.min.js"></script>
<script type="text/javascript" src="/js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="/js/ajaxupload.3.5.min.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/javascript.js"></script>
<script type="text/javascript" src="/js/main.js"></script>
<link href="/css/bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/css/styles.css" rel="stylesheet" type="text/css"/>

<!--[if lt IE 7]><style>
/* style for IE6 + IE5.5 + IE5.0 */
.gainlayout { height: 0; }
</style><![endif]-->
 
<!--[if gt IE 7]><style>
.gainlayout { zoom: 1; }
</style><![endif]-->
<!--<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" />-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/html5.js"></script>
<![endif]-->  
<script type="text/javascript">
$(document).ready(function() {

$(".preorderButton").click(function() 
{
if(<?php if(!isset($_SESSION["logged_in"])||!$_SESSION["logged_in"]) echo "false";else echo "true";?> === false)
{//show the modal
    target = $(this).parent().attr("href");
    $('#login').modal(); return false; } else {return true;} 
}
    
);
$(".subButton").click(function() 
{
if(<?php if(!isset($_SESSION["logged_in"])||!$_SESSION["logged_in"]) echo "false";else echo "true";?> === false)
{//show the modal
    target = $(this).parent().attr("href");
    $('#loginModal').modal(); return false; } else {return true;} 
}
    
);

})
</script>
<!-- start Mixpanel -->
<script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,
e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("e867a294154ea2179f35f9bdef93b979");</script>
<!-- end Mixpanel -->