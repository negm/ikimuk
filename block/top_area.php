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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="log">
<div class="container">
    <div class="offset4 span4">Yes we deliver in <?php if (!isset($_SESSION["country_name"])|| strlen($_SESSION["country_name"]) < 2)echo 'your country'; else echo $_SESSION["country_name"];?> now!</div>
<?php
if(!isset($_SESSION['logged_in'])|| !$_SESSION['logged_in'])
{
?>
    <b id="results"> </b>
    <div id="LoginButton">
        <a href="#login" data-toggle="modal" class="btn btn-primary pull-right">Login</a>
<?php
}
else
{
	echo '<div class="pull-right"><b>Hi '. $_SESSION['user_name'].'!</b> <a href="/index.php?logout=1">Log Out</a></div>';
        
}
?>
</div>
</div>
</div>


<div class="logoBg">
<div class="container">
<div class="row"><br>
<div class="span4"><a href="/index.php"><img src="/img/logo.png" alt="ikimuk logo"/></a></div>
<?php
if (stristr($settings->site_url,"submit"))
echo '<div class="menu"><ul><li class="span3"><a class="active menuhover" href="/index.php">Preorder a T-shirt</a></li><li class="span3"><a class="tyellow menuhover" href="/submit-intro.php">Submit your Design</a></li><li class="span2"><a class="twhite menuhover" href="/competitions.php">Previous Competition</a></li></ul></div>';
else
    if(stristr($settings->site_url,"competitions"))
    echo '<div class="menu "><ul><li class="span3"><a class="twhite menuhover" href="/index.php">Preorder a T-shirt</a></li><li class="span3"><a class="twhite menuhover" href="/submit-intro.php">Submit your Design</a></li><li class="span2"><a class="active menuhover" href="/competitions.php">Previous Competition</a></li></ul></div>';
    else
    echo '<div class="menu "><ul><li class="span3"><a class="active menuhover" href="/index.php">Preorder a T-shirt</a></li><li class="span3"><a class="twhite menuhover" href="/submit-intro.php">Submit your Design</a></li><li class="span2"><a class="twhite menuhover" href="/competitions.php">Previous Competition</a></li></ul></div>';

    ?>
</div>
</div>
</div>
<span id="item_count"><?php if (!isset($_SESSION["item_count"])) echo '0'; else echo $_SESSION["item_count"]; ?></span>
<!-- Modal -->

<?php include $_SERVER["DOCUMENT_ROOT"]."/block/login.html"; ?>
<div id="loginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <center><h3 id="myModalLabel">Connect Using Facebook</h3></center>
    
  </div>
  <div class="modal-body">
  <center><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" data-width="600" data-max-rows="1" data-show-faces="false" scope="<?php echo $settings->fbPermissions; ?>">Connect With Facebook</div></center>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

