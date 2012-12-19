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
$("#img_size_g").removeClass("alertr").addClass("hidden");
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
if(response != "error" && response != "size_error"){
$('<li></li>').appendTo('#files').html('<img class="img-rounded" src="'+response+'" alt="" /><br />'+file).addClass('success');
$('#upload').html('Upload another file');
img_list.push(response);
$('#img_url').val(img_list);
uploaded = true;
//$('#upload').hide();
}
if (response === "size_error")
    {
        $("#img_size_g").removeClass("hidden").addClass("alertr").focus();  return false;
    }
else{
//$('<li></li>').appendTo('#files').text(file).addClass('error');
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
        
<div class="span3 tlblue tunderline"><br> <br><a href="../submit-intro.php">Back to submission guidelines</a></div>    
<div class="preSummary span6 ">
    

<h1 class="preTitle">Submit your design</h1>
<div class ="offset1 span4">
<form id="submitDesign"  method="post" action="" class="">

<h3 class="tlarge tnheight">ADD IMAGES</h3>
<div class="line"></div>
<p>Choose the file(s) to display your design. Submit a JPG version for your design
    (RGB mode not CMYK), 620 px wide x 500 px tall. Maximum file size 250 KB.</p>
<ul id="files" class ="thumb"></ul>
<p class="hidden" id="img_g">You forgot to upload your design</p>
<p class="hidden" id="img_size_g">The file is larger than 250k</p>
<div id="upload" class="btn btn-primary"><span>Upload File<span></div><span id="status" ></span><br/>
<br>
<h3 class="tlarge tnheight">DESIGN INFO</h3>
<div class="line"></div><br>
<p>Design title</p>
<p class="hidden" id="title_g">Please choose a title!</p>
<input type="text" name="design_title" id="design_title" class="span4"/>
<p>About your design</p>
<textarea class="span4" rows="6" name="comment" id="comment" placeholder="Tell us a little about what inspired you"></textarea>
<p class="hidden" id="agreement_g"><small> You should read and agree on the terms</small></p>
<label class="checkbox" >
    <input id="agreement" name="agreement" class="" type="checkbox" value="1" /> 
    I agree to ikimuk's <a href="#myModal" role="button" style="color:#44c6e3" data-toggle="modal">Terms & Conditions</a>
</label>
<label class="checkbox" >
    <input id="newsletter" name="newsletter" class="" type="checkbox" value="1" /> 
    Keep me in the loop, sign me up for your newsletter 
</label>

<input type="hidden" name="img_url" id="img_url"/>
 <br><br>
<a id="submit_design" class="subButton span4 nomargin" >Submit your design</a>
</form>
</div>
</div>
<div id="orderComplete" class="span4 hidden">
        <div class="preTitle">Submission complete</div>
        Thank you for submitting <span id="title_msg" class="tlblue"></span>! We will update you soon.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
</div>
<div id="submitFailed" class="span4 hidden">
        <div class="txlarge">Submission FAILED!</div>
        Thank you for submitting <span id="title_msg" class="tlblue"></span>! However, we weren't able to complete your submission<br/>
        Please try again in a while. Until then, <a href="index.php" class="tlblue">browse our other designs</a>
        
</div>

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
        <?php include '/block/SubmissionTerms';?>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div><br>
<?php
include 'block/footer.php';
?>