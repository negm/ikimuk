<?php
include "block/logged_in.php";
$pagetitle = "Submit your design";
include_once 'block/header.php';
?>
<!--<script type="text/javascript" src="js/jquery-1.8.1.min.js" ></script>-->
<script type="text/javascript" src="js/ajaxupload.3.5.js" ></script>
<script type="text/javascript" >

</script>
<?php
include "block/top_area.php";
include "block/breadcrumb.php";
?>
   	
<h1>Submit your design</h1>
<form id="submitDesign"  method="post" action="">
<p>Give it a title (if you haven't thought of something cool yet just put a simple title)</p>
<p class="hidden" id="title_g"><small>Please choose a title!</small></p>
<input type="text" name="design_title" id="design_title" />
<input type="hidden" name="img_url" id="img_url"/>
<p>Now upload an image file of your design</p>
<p class="hidden" id="img_g"><small>You forgot to upload your design :D</small></p>   	
	<div id="upload" class="btn btn-primary"><span>Upload File<span></div><span id="status" ></span>
	<ul id="files" ></ul>
<input type="submit" id="submit_design" value="Submit you design"/>
</form>
<div id="orderComplete" class="span7 hidden">
        <div class="preTitle">Preorder complete</div>
        Thank you for preordering this  design! We will notify you if it  gets printed.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
</div>
</body>
</html>