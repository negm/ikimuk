<?php
include $_SERVER["DOCUMENT_ROOT"]."/block/logged_in.php";
$pagetitle = "Submit your design";
include_once $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
?>
<script>//upload ajax
$(function(){
var btnUploadSubmit=$('#upload');
var status=$('#status');
new AjaxUpload(btnUploadSubmit, {
action: 'process_upload.php',
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
$('<li></li>').appendTo('#files').html('<img class="img-rounded" src="'+response+'" alt="" />').addClass('success');
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
include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";
//include $_SERVER["DOCUMENT_ROOT"]."block/breadcrumb.php";
?>
<div class="body">
             <div class="body_content">
                 
                                 
                 <!--Start of Cart section-->
                 <div class="cart_section">
                     <div class="cart_content">
                         <div class="cart_icon"></div>
                         <div class="cart_details">
                             CART(<span class="cart_count">0</span>)
                         </div>
                     </div>
                 </div>
                  <!--end of Cart section-->
                  
                  
                  
                  
                  <div class="submit_design">
                      
                   <div class="design_header">
                     SUBMIT YOUR DESIGN
                  </div>
                  
                      
                      <!--Start of Choose Competition-->
                      <div class="choose_competition">
                        <div class="std_block">
                              
                              <!--Start of cart table header-->
                              <div class="std_block_header">
                                  <div class="header_content">
                                      1. Choose Competition
                                  </div>
                              </div>
                                <!--End of cart table header-->
                           
                                  <!--Start of submit body-->
                              <div class="std_block_body type_body">
                                  <div class="type_select">
                                  <input type="radio" name="competition_type" value="zombie" checked="checked"/>
                                  For the Love Of Zombies Submit before 21/01/2013
                                  </div>
                                  <div class="type_select">
                                  <input type="radio" name="competition_type" value="hakwaji"/>
                                  HAKWAJI submit before 01/02/2013
                                  </div>
                              </div>
                                    <!--End of submit body-->
                          </div> 
                      </div>
                      <!--End of Choose Competition-->
                      
                      
                      <!--Start of Add Images-->
                       <div class="add_images">
                        <div class="std_block">
                              <input type="hidden" id="img_url">
                              <!--Start of cart table header-->
                              <div class="std_block_header">
                                  <div class="header_content">
                                      2. Add Images
                                  </div>
                              </div>
                                <!--End of cart table header-->
                           
                                  <!--Start of submit body-->
                              <div class="std_block_body">
                                  <div class="upload_body_content">
                                     <span class="upload_info"> Choose the file(s) to display your design. Submit a JPG version for your design (RGB mode not
                                      CMYK), 300 px wide x 450 px tall. Maximum file size 250 KB.</span>
                                   <ul id="files" class="thumb"></ul>
                                   <div class="upload_holder">
                                    <input type="button" id="upload" value="UPLOAD FILE" style=""/>
                                   </div>
                                      
                                      </div>
                                      
                              </div>
                                    <!--End of submit body-->
                          </div> 
                      </div>
                      <!--End of Add Images-->
                      
                     
                      <!--Start of Design Info-->
                  <div class="design_info">
                        <div class="std_block block_expandable">
                              
                              <!--Start of cart table header-->
                              <div class="std_block_header">
                                  <div class="header_content">
                                      3. Design Info
                                  </div>
                              </div>
                                <!--End of cart table header-->
                           
                                  <!--Start of submit body-->
                              <div class="std_block_body info_body">
                                 
                                      <div class="line_info">   
                                      <div class="line_header">Design Title</div>
                                      <div class="line_input">
                                            <input type="text" class="round_corners" name="design_title"/>
                                      </div>
                                      <div class="line_error"></div>
                                      </div>
                                      
                                  
                                      <div class="line_info">   
                                      <div class="line_header">About your design</div>
                                      <div class="line_input">
                                          <textarea  class="round_corners" name="design_details"></textarea>
                                      </div>
                                      <div class="line_error"></div>
                                      </div>
                                  
                                   
                              </div>
                                    <!--End of submit body-->
                          </div> 
                      </div> 
                      <!--End of Design Info-->
                      
                    
                      
                   <!--Start of Self Info-->   
                <div class="self_info">
                        <div class="std_block block_expandable">
                              
                              <!--Start of cart table header-->
                              <div class="std_block_header">
                                  <div class="header_content">
                                      4. Your Info
                                  </div>
                              </div>
                                <!--End of cart table header-->
                           
                                  <!--Start of submit body-->
                              <div class="std_block_body self_info_body">
                                 
                                      <div class="line_info">   
                                      <div class="line_header">City</div>
                                      <div class="line_input">
                                            <input type="text" class="round_corners" name="city"/>
                                      </div>
                                      <div class="line_error"></div>
                                      </div>
                                      
                                  
                                     <div class="line_info">   
                                      <div class="line_header">Website, Blog Or Portfolio</div>
                                      <div class="line_input">
                                            <input type="text" class="round_corners" name="website_blog_1"/>
                                      </div>
                                      <div class="line_error"></div>
                                      </div>
                                  
                                  
                                  <div class="line_info">   
                                      <div class="line_header">Website, Blog Or Portfolio</div>
                                      <div class="line_input">
                                           <input type="text" class="round_corners" name="website_blog_2"/>
                                      </div>
                                      <div class="line_error"></div>
                                      </div>
                                  
                                   
                              </div>
                                    <!--End of submit body-->
                          </div> 
                      </div>
                      <!--End of self Info-->
                      
                  
                      <!--Start of agreeement Section-->
                    <div class="agreement_submit_section">
                        
                        
                      <div class="agreement">
                             <div class="terms_conditions">
                                 <input type="checkbox" name="agree"/>
                                <span>I agree on ikimuk's </span>
                                 <a href="#">Terms & Conditions</a>
                             </div>
                             <div class="line_error"></div>
                      </div>
                         
                         <div class="newsletter">
                             <input type="checkbox" name="subscribe"/>
                             <span>Keep me in the loop, sign me up for your newsletter</span>
                         </div>

                                         
                           <div class="submit_personal_design">
                               <input type="button" name="submit_design" value="SUBMIT YOUR DESIGN" />
                           </div>
          
                     </div>      
                      <!--End Of agreement section-->

                  </div>
                 <!--End of submit design-->
   
             </div>    
             
             <!--End of body content-->
            
        </div>
        <!--End of body-->
<?php
include $_SERVER["DOCUMENT_ROOT"].'/block/footer.php';
?>