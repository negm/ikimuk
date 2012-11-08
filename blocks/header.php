<?php
include_once ('settings.php');
if (!isset($_SESSION))
{session_start();}
include_once 'blocks/header_en.php';
if(isset($_GET["logout"]) && $_GET["logout"]==1)
{
//User clicked logout button, distroy all session variables.
unset($_GET["logout"]);
$_SESSION['logged_in']=false;
unset($_COOKIE);
session_destroy();
redirect($site_url);
}
?>
<!--[if lt IE 7]><style>
/* style for IE6 + IE5.5 + IE5.0 */
.gainlayout { height: 0; }
</style><![endif]-->
 
<!--[if gt IE 7]><style>
.gainlayout { zoom: 1; }
</style><![endif]-->
</head>

<body>
<div id="bar" class="">
<div class="top-area">
<div class="log">
<?php
if(!isset($_SESSION['logged_in'])|| !$_SESSION['logged_in'] || !isset($_SESSION["user_name"]) ||strlen(trim($_SESSION["user_name"])) < 1)
{
?>
    <b id="results" class=""> </b>
    <div id="LoginButton" class="">
    <div class="fb-login-button" onlogin="javascript:CallAfterLogin();" size="medium" scope="<?php echo $fbPermissions; ?>">Connect With Facebook</div> 
<?php
}
else
{
	echo '<b>Hi '. $_SESSION['user_name'].'!</b> <a href="index.php?logout=1">Log Out</a>.';
        
}
?>
</div>
</div>
</div>

<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
FB.init({appId: '<?php echo $app_id; ?>',channelUrl: 'channel.php', status:true, cookie: true,xfbml: true,oath:true});

};
// Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];  if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;  js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref); }(document));
</script>

<div class="logoBg">
<div class="container_12">
<div class="artist"><img src="img/artist.png" alt="artists"/></div>
<a href="index.php" class=""><div class="logo"> <!--<img src="img/logo.png" class="" alt="logo"/>--></div></a>
<div class="topText"> Awesome t-shirts designed by you!</div>
<div class="fennec"><img src="img/fennec.png" alt="fennec" /></div>
</div>
</div>
</div>
<!-- Modal -->
<div id="loginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <center><h3 id="myModalLabel">Connect Using Facebook</h3></center>
    
  </div>
  <div class="modal-body">
  <center><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" size="medium" scope="<?php echo $fbPermissions; ?>">Connect With Facebook</div></center>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
<div  class="container_12">

