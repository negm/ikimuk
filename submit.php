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
if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
// extension is not allowed 
status.text('Only JPG, PNG files are allowed');
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
$('#upload').html('Upload another file');
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
<div class="container">
    <div class="row">
        
    
<div class="offset3 preSummary span6 ">
    

<h1 class="preTitle">Submit your design</h1>
<div class ="offset1 span4">
<form id="submitDesign"  method="post" action="" class="">

<h3 class="header">ADD IMAGES</h3>
<div class="line"></div>
<p>Choose the file(s) to display your design. Use up to 3 images in JPG or PNG  format
    (RGB mode not CMYK), 620 px wide x 500 px tall. Maximum file size 250 KB.</p>
<ul id="files" class ="thumb"></ul>
<p class="hidden" id="img_g">You forgot to upload your design</p>
<div id="upload" class="btn btn-primary"><span>Upload File<span></div><span id="status" ></span><br/>
<br>
<div class="line"></div>
<h3 class="header">DESIGN INFO</h3>
<p>Design title</p>
<p class="hidden" id="title_g">Please choose a title!</p>
<input type="text" name="design_title" id="design_title" class="span4"/>
<p>About your design</p>
<textarea class="span4" rows="6" name="comment" id="comment" placeholder="tell us your inspiration"></textarea>
<p class="hidden" id="agreement_g"><small> You should read and agree on the terms</small></p>
<label class="checkbox" >
    <input id="agreement" name="agreement" class="" type="checkbox" value="1" /> 
    I agree on Ikimuk's <a href="#myModal" role="button" style="color:#44c6e3" data-toggle="modal">Terms & Conditions</a>
</label>
<label class="checkbox" >
    <input id="newsletter" name="newsletter" class="" type="checkbox" value="1" /> 
    Keep me in the loop, sign me up for your newsletter 
</label>

<input type="hidden" name="img_url" id="img_url"/>
 <br><br>
<a id="submit_design" class="subButton span4" >Submit your design</a>
</form>
</div>
</div>
<div id="orderComplete" class="span4 hidden">
        <div class="preTitle">Submission complete</div>
        Thank you for submitting <span id="title_msg" class="tlblue"></span>! We will update you soon.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
</div>
<ul id="files" class ="thumb"></ul>
</div>
</div>
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Terms & Conditions</h3>
  </div>
  <div class="modal-body">
    <div class="span16" style="font-size:12; color:#4c4c4c;padding-bottom:10;">
-You have the right to post or use the design for any non-commercial use (blog, portfolio, article, ...) as long as we are mentioned or credited as owners (in example: this design was made for)
</br></br>
-By submitting a design, you acknowledge and declare that the Design you have submitted is your own original work, has not been previously published, and does not contain any trademarks, logos, copyrighted material, or any other intellectual property belonging to any third party, may, at its sole discretion, disqualify any entry that contains any material, which, in its sole discretion, deems to be profane or offensive. 
</br></br>
-If your design is chosen we retain permanent full rights to that design for commercial use on apparel and other promotional products, and you will be known as the author of that work.
</br></br>
-We reserve the right to make necessary minor adjustments or changes to submitted designs in order to conform artwork to manufacturing requirements.
</br></br>
-You acknowledge that we reserve the right to decline to select a Design for consideration for any reason.
</br></br>
-When you submit your design you agree that for 90 days we have exclusivity rights over it, you don't have the right to reproduce it, sell it or submit it to any third party, and that if after 90 days your design isn't selected you regain full rights over it
</br></br>
-We retain the right to choose your design after 90 days only if the design hasn't been used for commercial use on any item or product.
</br></br></br></br>
</div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
<?php
include 'block/footer.php';
?>