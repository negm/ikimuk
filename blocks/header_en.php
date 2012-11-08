<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome-1">
<link rel="shortcut icon" href="img/favicon.ico">
<title>Awesome, original t-shirts designed  by you</title>    
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/960_12_col.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />

<link href='http://fonts.googleapis.com/css?family=Arvo:400,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/mainjs.js"></script>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/html5.js"></script>
<![endif]-->  
<script type="text/javascript">
function AjaxResponse()
{
var myData = 'connect= 1';
jQuery.ajax({
type: "POST",
url: "process_facebook.php",
dataType:"html",
data:myData,
cache: false,
success:function(response){
$("#results").html(response); //Result
if ("<?php if (isset($_SESSION["user_name"]))echo $_SESSION["user_name"];?>".length < 2)
{ location.reload(true);}
 },
error:function (xhr, ajaxOptions, thrownError){
//$("#results").html('<fieldset style="padding:20px;color:red;">'+thrownError+'</fieldset>'); //Error
    }
 });
 }
 
$(function(){
$(".preorderButton").click(function() 
{
if("<?php if(isset($_SESSION["user_name"])) echo $_SESSION["user_name"];?>".length < 3)
{//show the modal
    $('#loginModal').modal(); return false; } else {return true;} }
    
);})
</script>