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
        <p>By submitting your design on this website, you expressly and irrevocably accept the following terms and
conditions, and hereby undertake and agree that:</p>

<p>1.The design you are submitting is (i)your own original work, (ii)has not previously been sold, commercialized, 
published and its rights transferred in any way, (iii)does not contain any trademarks, logos, copyrighted, 
material or any other intellectual property owned by a third party; </p>

<p>2. The competition launched by ikimuk.com ends at a specified time and date pre-set by the latter, by
which the results are deemed definitive. Upon such event, ikimuk.com shall declare the Design that won
the competition.
Notwithstanding the provisions of clause 10, the winning design shall be that which received the most
preorders.</p>

<p>3. Starting from the day you submit your design until the competition ends, you shall not transfer, sell,
reproduce, submit or use said design or any rights pertaining to it for any commercial purpose, for
whatsoever reason, whether in written press or on the net.</p>

<p>4. If by the expiry of the deadline, your design has not won, you shall regain your rights to dispose of
such design, whether for a commercial or a non-commercial purpose. However, ikimuk.com will retain
its right to use (i.e. to upload, modify, reproduce, copy, exhibit, create derivative works, distribute, and
display) said design for the sole purpose of promoting it, and in no case shall it be used by ikimuk.com
for commercial use.</p>

<p>5. If by the expiry of the deadline, your design won, the design and all rights pertained to it shall
immediately and automatically be transferred to ikimuk.com in order to be promoted and/or
commercialized, mainly, but not limited to printing on apparel and any other products, noting that in all
cases, ikimuk.com will use reasonable efforts to ensure that the design is exploited in the best way, and
that you be always mentioned as the author of the design, unless agreed otherwise.</p>

<p>6. In case the design is selected, you shall be entitled to a compensation of USD 300, and to 4% royalties;
noting that such amounts are subject to change, in which case you shall be notified.
Any extra fees or taxes that arise as a result of transferring or receiving the payment from ikimuk.com or
any of its partners shall be at your charge;</p>

<p>7. Pursuant to such event by which your design won, ikimuk.com shall remain the exclusive owner of
said design, the concept and the ideas. Ikimuk.com shall have the right to reproduce the Design or
any parts, alterations or derivatives thereof, as well as to assign, publicly display, perform, exhibit or
distribute said Design. Any interest in patents, patent applications, inventions, trade names, trademarks,
service marks, copyrights, development designs shall (i) forthwith be brought to ikimuk.com’s attention
so it can take proper action, and (ii) belong exclusively to ikimuk.com;</p>

<p>8. Notwithstanding the provisions of the abovementioned, and for the sole purpose of showing your
work on a personal, non-commercial way such as posting it on your personal blog, mentioning it in an
article by or about you or including it in your portfolio, and in all cases, without causing any damage to
ikimuk.com, to its reputation, and to its rights on the selected design, you shall remain entitled to use
such design as long as ikimuk.com is expressly and clearly mentioned or credited as owner (in example:
this design was made for ikimuk.com);</p>

<p>9. The Design may be subject to adjustments or changes in order to be adapted to artwork and
manufacturing requirements without necessarily obtaining your prior approval. In such case, you shall
have the right to withdraw use of your name as a credit;</p>

<p>10. You are fully aware that ikimuk.com has the right, at all time, to decline to select a design, or to
remove it from any publication and/or dissemination for any reason whatsoever, including but not
limited to poor, obscene, vulgar, profane, offensive, inappropriate design, if the design is deemed to
create possible legal liabilities, etc;</p>

<p>11. Any conflict arising from this agreement shall be governed by and construed in accordance with
Lebanese law and shall be subject to the exclusive jurisdiction of the competent court in Lebanon.</p>

<p>By submitting your design to ikimuk.com, you acknowledge that you have read and fully understood
these Terms and Conditions, and that you agree to be bound by them, and you wish to submit your
Design on this website in accordance with them.</p>
</div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div><br>
<?php
include 'block/footer.php';
?>