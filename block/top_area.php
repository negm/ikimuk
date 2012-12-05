<?php
include_once ($_SERVER["DOCUMENT_ROOT"].'/class/settings.php');
$settings = new settings();
if(isset($_GET["logout"]) && $_GET["logout"]==1)
{
//User clicked logout button, distroy all session variables.
$_GET["logout"] = 0;
unset($_GET["logout"]);
$_SESSION['logged_in']=false;
unset($_COOKIE);
session_destroy();
header('Location: '.$_SERVER['PHP_SELF']);
}
?>

</head>

<body>
<div class="log">
<div class="container-fluid">
    <div class="offset4 span4">Sorry we only deliver in Lebanon for now</div>
<?php
if(!isset($_SESSION['logged_in'])|| !$_SESSION['logged_in'])
{
?>
    <b id="results" class=""> </b>
    <div id="LoginButton" class="">
    <div class="fb-login-button" onlogin="javascript:CallAfterLogin();" size="medium" scope="<?php echo $settings->fbPermissions; ?>">Connect With Facebook</div> 
<?php
}
else
{
	echo '<b>Hi '. $_SESSION['user_name'].'!</b> <a href="../index.php?logout=1">Log Out</a>.';
        
}
?>
</div>
</div>
</div>

<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
FB.init({appId: '<?php echo $settings->app_id ?>',channelUrl: 'channel.php', status:true, cookie: true,xfbml: true,oath:true});

};
// Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];  if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;  js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref); }(document));
</script>
<div class="logoBg">
<div class="container-fluid">
<div class="row-fluid">
<a href="../index.php"><div class="offset1 span4 logo"></div></a>
</div>
<div class="menu row"><ul><li class="offset5">CHOOSE</li><li class="">BUY</li><li class=""><a href="../submit.php">SUBMIT</a></li><li class="">ABOUT</li></ul></div>
</div>
</div>

<!-- Modal -->
<div id="loginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <center><h3 id="myModalLabel">Connect Using Facebook</h3></center>
    
  </div>
  <div class="modal-body">
  <center><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" size="medium" scope="<?php echo $settings->fbPermissions; ?>">Connect With Facebook</div></center>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

