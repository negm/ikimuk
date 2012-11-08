<?php
//This file is for DB connection settings and other configs shared accross the app
$site_url="";
$site_url_vars="";
$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
include_once 'inc/library.inc.php';
if ($_SERVER["SERVER_PORT"] != "80")
{
    $site_url_vars = $pageURL.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["PHP_SELF"];
} 
else 
{
    $site_url_vars = $pageURL.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
}
$site_url= $pageURL; 
if (strpos($site_url,'localhost'))
{
$database = array (
      'database' => 'ikimuk',
      'username' => 'root',
      'password' => '',
      'host' => 'localhost',
      'port' => '',
      'driver' => 'mysql',
      'prefix' => '',
    );
$app_id = "299742366741440";
$app_secret = "2369fc592909a16047bbce32bd75e23d";
$root = "localhost:8080/ikimuk";

}
else
{
$database = array (
      'database' => 'app',
      'username' => 'root',
      'password' => 'sqp.2012.sql++',
      'host' => 'localhost',
      'port' => '',
      'driver' => 'mysql',
      'prefix' => '',
    );
$app_id		= "140388549312943"; 
$app_secret	= "d69fc7d02813ea962a959258e22adfde";
$root = $_SERVER['HTTP_HOST'];
}
$mysqli = new mysqli($database['host'], $database['username'], $database['password'], $database['database']);

/* check connection */
if (mysqli_connect_errno()) {
    log("Connect failed: %s\n", mysqli_connect_error());
    redirect("maintanance.html");
}

$fbPermissions = 'publish_stream,email'; 
$nexmo_key = 'f20b967a';
$nexmo_secret = '9d3daa40';
?>