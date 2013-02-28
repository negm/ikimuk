<!-- Start of Login part-->
<div id="login" class="modal hide fade member" data-backdrop="static">
                                   
    
           <div class="member_content">
               
               <!--Start of member header-->
                 <div class="member_header">  
                                
                         <div class="member_title_box"><div class="member_title">LOGIN</div></div>
    
                          <div class="member_close">
                                <a href="#" data-dismiss="modal" aria-hidden="true">
                                <img src="/images/ikimuk_disabled_close.png"/></a>
                           </div>
                         
                 <div class="member_unjoin">
                    <span>Not a member?</span>
                    <a href="#join" data-toggle="modal" onclick="$('#login').modal('hide');">Join Us</a>
                  </div>     
                   </div>
               <!--End of member header-->
               
               <div class="member_body">
                  
                    <!--Start of member body section-->
                   <div class="member_body_content login">
        
                       <!--Start of email section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               E-mail
                           </div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="text" name="email"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title">Password</div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
                       
                       
                       <div class="member_details"><a href="#">Forgot Your Password?</a></div>
                       
                       
                       <div class="member_join">
                           <input type="submit" name="join" value="LOGIN"/>
                       </div>
                       
                       <div class="member_or_line">  
                           <div class="member_or">OR</div>
                       </div>
                       
                       <div class="member_facebook">
                           <a href="#"><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" data-width="600" data-max-rows="1" data-show-faces="false" scope="<?php echo $settings->fbPermissions; ?>">Connect With Facebook</div></a>
                       </div>
                       
                       
                       
                       
                   </div>
                   <!--End Of Member Body Content-->
                   
                        </div>
               
                </div>
          <!--End of member content-->
    
    
</div>
    <!-- End of Login part-->







    <!-- Start of Join part-->
<div id="join" class="modal hide fade member" data-backdrop="static">
                                   
    
           <div class="member_content">
               
               <!--Start of member header-->
                 <div class="member_header">  
                         <div class="member_title_box"><div class="member_title">JOIN US</div></div>       	      
                             
                          <div class="member_close">
                                <a href="#" data-dismiss="modal" aria-hidden="true">
                                <img src="/images/ikimuk_disabled_close.png"/></a>
                           </div>
                         
                 <div class="member_unjoin">
                    <span>Already a member?</span>
                    <a href="#login" data-toggle="modal"  onclick="$('#join').modal('hide');">Login</a>
                  </div>     
                   </div>
               <!--End of member header-->
              
               <div class="member_body">
                  
                    <!--Start of member body section-->
                   <div class="member_body_content join">
                      
                       <!--Start of name section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               Name
                           </div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="text" name="full_name"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of name section-->
                       
                       
                       
                       <!--Start of email section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               E-mail
                           </div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="text" name="email"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title">Password</div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
                      
                      <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title">Confirm Password</div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="confirm_password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
                       
                       
                       <div class="member_details">
                        By clicking "join ikimuk", you agree to our
                        <a href="#" target="_blank">Terms & Conditions</a>
                        and
                        <a href="#" target="_blank">Privacy Policy</a>
                       </div>
                       
                       
                       <div class="member_join">
                           <input type="submit" name="join" value="JOIN US"/>
                       </div>
              
                       <div class="member_or_line">  
                           <div class="member_or">OR</div>
                       </div>
                       
                       <div class="member_facebook">
                           <a href="#"><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" data-width="600" data-max-rows="1" data-show-faces="false" scope="<?php echo $settings->fbPermissions; ?>">Connect With Facebook</div></a>
                       </div>
                       
                       
                       
                       
                   </div>
                   <!--End Of Member Body Content-->
                   
                        </div>
               
                </div>
          <!--End of member content-->
</div>
   <!-- End of join part-->   
   