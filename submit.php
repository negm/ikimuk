<?php
include "block/logged_in.php";
$pagetitle = "Submit your design";
include_once 'block/header.php';
?>
<script>//upload ajax
$(function(){
var btnUploadSubmit=$('#upload');
var status=$('#status');
new AjaxUpload(btnUploadSubmit, {
action: 'process-upload.php',
name: 'uploadfileSubmit',
onSubmit: function(file, ext){
if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
// extension is not allowed 
status.text('Only JPG, PNG or GIF files are allowed');
return false;
}
status.text('Uploading...');
},
onComplete: function(file, response){
//On completion clear the status
status.text('');
//Add uploaded file to list
if(response != "error"){
$('<li></li>').appendTo('#files').html('<img class="img-rounded" src="'+response+'" alt="" /><br />'+file).addClass('success');
img_list.push(response);
$('#img_url').val(img_list);
uploaded = true;
//$('#upload').hide();
} else{
$('<li></li>').appendTo('#files').text(file).addClass('error');
}
}
});
});</script>
<?php
include "block/top_area.php";
include "block/breadcrumb.php";
?>
   	
<h1>Submit your design</h1>
<form id="submitDesign"  method="post" action="" class="span7">
<p>Give it a title (if you haven't thought of something cool yet just put a simple title)</p>
<p class="hidden" id="title_g"><small>Please choose a title!</small></p>
<input type="text" name="design_title" id="design_title" />
<input type="hidden" name="img_url" id="img_url"/>
<p>Now upload an image file of your design</p>
<p class="hidden" id="img_g"><small>You forgot to upload your design :D</small></p>   	
<div id="upload" class="btn btn-primary"><span>Upload File<span></div><span id="status" ></span><br/>
	
<input type="submit" id="submit_design" class="btn btn-success" value="Submit you design"/>
</form>
<div id="orderComplete" class="span5 hidden">
        <div class="preTitle span8">Preorder complete</div>
        Thank you for submitting this  design! We will update you soon.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
</div>
<ul id="files" class ="span4"></ul>

</body>
</html>