<?php
        
$pagetitle = "ikimuk: About Us";
include $_SERVER["DOCUMENT_ROOT"]."/block/header.php";
$selected = Array ("unselected","unselected","unselected","unselected","selected","unselected" );   
?>
<meta property="og:title" content="ikimuk: about us" />
<meta property="og:type" content="Website" />
<meta property="og:url" content="http://ikimuk.com/about-us.php" />
<meta property="og:image" content="http://ikimuk.com/images/ikimuk_logo_beta_hover.png" />
<meta property="og:site_name" content="ikimuk" />
<?php
include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";
 ?>
<div class="body">
    <div class="body_content aboutus">
<div class="page_header">
	THIS IS ikimuk
</div>

<div style="margin-top:15px">
<img src="/img/aboutus.gif" />
</div>
<?php echo _txt("about_text");?>
<div class="quote" style="float:left">
	<a href="http://www.dailystar.com.lb/Culture/Lifestyle/2013/Feb-14/206371-helping-design-dreams-become-fashion-reality.ashx#axzz2KrEVliH8" target="_blank">	
  <div><i>"ikimuk is helping design dreams become fashion reality"</i></div>
  <div style="float:right"><b>-The Daily Star</b></div>
	</a>
</div>

<div class="quote" style="float:right">
	<a href="http://ginosblog.com/?s=ikimuk" target="_blank">
  <div><i>"Awesome, affordable and crowd-sourced fashion"</i></div>
  <div style="float:right"><b>-Gino’s Blog</b></div>
	</a>
</div>

<div class="sq"><a href="http://seeqnce.com" target="_blank"><img src="/img/seeqnce.png"/></a></div>
</div>
</div>
<?php
include $_SERVER["DOCUMENT_ROOT"].'/block/footer.php';
?>