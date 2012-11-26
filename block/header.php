<?php
    // Include this function on your pages
if (!isset($_SESSION))
{
    session_start ();
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
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head  prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# phennec: http://ogp.me/ns/fb/phennec#">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome-1">
<link rel="shortcut icon" href="img/favicon.ico">
<title><? echo $pagetitle; ?></title>
<link rel="stylesheet" href="../css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/default/default.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/light/light.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/dark/dark.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/bar/bar.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/nivo-slider.css" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Arvo:400,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/jquery.nivo.slider.js"></script>
<script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="../js/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="../js/mainjs.js"></script>
<!--[if lt IE 7]><style>
/* style for IE6 + IE5.5 + IE5.0 */
.gainlayout { height: 0; }
</style><![endif]-->
 
<!--[if gt IE 7]><style>
.gainlayout { zoom: 1; }
</style><![endif]-->
<link rel="stylesheet" href="../css/style.css" type="text/css" media="screen" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/html5.js"></script>
<![endif]-->  
<script type="text/javascript">
$(document).ready(function() {

$(".preorderButton").click(function() 
{
if(<?php if(!isset($_SESSION["logged_in"])||!$_SESSION["logged_in"]) echo "false";else echo "true";?> === false)
{//show the modal
    target = $(this).attr("href");
    $('#loginModal').modal(); return false; } else {return true;} 
}
    
);})
</script>