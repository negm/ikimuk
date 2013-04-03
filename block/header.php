<?php
if (!isset($_SESSION))
{
    session_start ();
}


$country_default_arabic = array("Egypt","Saudi Arabia", "United Arab Emirates",
    "Bahrain","Iraq","Jordan","Kuwait","Libya","Oman","Palestine","Qatar",
    "Sudan","Yemen");
include_once $_SERVER["DOCUMENT_ROOT"]."/class/settings.php";
$settings = new settings();

if(isset($_GET["utm_source"]))
{
$_SESSION["promo_code"]= $_GET["promo_code"];
}
if (!isset($_SESSION["country_name"])|| strlen($_SESSION["country_name"])<2)
{
    include $_SERVER["DOCUMENT_ROOT"]."/inc/ip2country.php";
    $ip2c=new ip2country();
    $_SESSION["country_name"] = $ip2c->get_country_name();
    $_SESSION["country_name_ar"] = $ip2c->country_name_ar;
    $_SESSION["delivery_charge"] = $ip2c->delivery_charge;
    $_SESSION["phone_code"]=$ip2c->phone_code;
    $country_name= $ip2c->get_country_name();
    $country_name_ar= $ip2c->country_name_ar;;
}
else
{
     $country_name= $_SESSION["country_name"];
     $country_name_ar= $_SESSION["country_name_ar"];
}
if (!isset($_SESSION["lang"]))
{
    if ( in_array($country_name, $country_default_arabic))
    {$lang = "ar";
    $_SESSION["lang"] = "ar";
    }
        else {
            $lang = "en";
            $_SESSION["lang"] = "en";
        }
}
else
{
    $lang = $_SESSION["lang"];
}
if(isset($_GET["lang"]))
{
    $lang = $_GET["lang"];
    $_SESSION["lang"] = $_GET["lang"];
    unset($_GET["lang"]);
    if (count($_GET)>0)
    header ("Location: ".$_SERVER["PHP_SELF"]."?".http_build_query($_GET));
    else
        header ("Location: ".$_SERVER["PHP_SELF"]);
}
$get = $_GET;
$get["lang"]="ar";
$ar_link = $_SERVER["PHP_SELF"]."?".http_build_query($get);
$get["lang"]="en";
$en_link = $_SERVER["PHP_SELF"]."?".http_build_query($get);

include_once $_SERVER["DOCUMENT_ROOT"]."/inc/localisation.php";
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
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
function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );
    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}

ob_start("sanitize_output");
ob_implicit_flush(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" version="XHTML+RDFa 1.0" <?php if(isset($lang) && $lang == "ar") echo 'dir="rtl"'?>>
<head  prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# ikimukapp: http://ogp.me/ns/fb/ikimukapp#">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome-1">
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
<title><?php echo $pagetitle; ?></title>
<meta name="title" content="Ikimuk">
<meta name="description" content="Preorder your favorite T-shirt designs from leading artists in the Arab World." />
<meta name="keywords" content="browse, catalog, collection, best, amazing, variety, guys, girls, men&#039;s, women&#039;s, t-shirts, tee shirts, tshirts, clothing, design, art, arab, gulf, lebanon, خليج ,عرب, ملابس , موضة , شباب" />
<meta property="fb:admins" content="134500527" />
<meta property="og:site_name" content="Ikimuk" />
<meta property="og:description" content="Cool T-shirt Design">
<meta property="og:type" content="ikimukapp:design" />
<meta property="og:determiner" content="a" />
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css" rel="stylesheet"/>
<link href="/css/styles2.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/css/nivo-slider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/nivo-light/light.css" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="/css/simplePagination.css"/>

<!--[if lt IE 7]><style>
/* style for IE6 + IE5.5 + IE5.0 */
.gainlayout { height: 0; }
</style><![endif]-->
 
<!--[if gt IE 7]><style>
.gainlayout { zoom: 1; }
</style><![endif]-->
<!--<link rel="stylesheet" href="/css/styles.min.css" type="text/css" media="screen" />-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/html5.js"></script>
<![endif]-->  
<?php if(isset($lang)&& $lang =="ar")
    echo '<link href="/css/ar-css.css" rel="stylesheet" type="text/css"/>';
 ?>