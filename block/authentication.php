<!-- Start of Login part-->
<div id="login" class="modal hide fade member" data-backdrop="static">
                                   
    
           <div class="member_content">
               
               <!--Start of member header-->
                 <div class="member_header">  
                                
                         <div class="member_title_box"><div class="member_title"><?php echo _txt("login");?></div></div>
    
                          <div class="member_close">
                                <a href="#" data-dismiss="modal" aria-hidden="true">
                                <img src="/images/ikimuk_disabled_close.png"/></a>
                           </div>
                         
                 <div class="member_unjoin">
                    <span><?php echo _txt("notamember");?></span>
                    <a href="#join" data-toggle="modal" onclick="$('#login').modal('hide');"><?php echo _txt("signup");?></a>
                  </div>     
                   </div>
               <!--End of member header-->
               
               <div class="member_body">
                  <div class="alert alert-error hide"> <button type="button" class="close" data-dismiss="alert">×</button> <span id="login_error"></span></div>
                    <!--Start of member body section-->
                   <div class="member_body_content login">
        
                       <!--Start of email section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               <?php echo _txt("email");?>
                           </div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="text" name="email"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title"><?php echo _txt("password");?></div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
                       
                       
                       <div class="member_details"><a href="#reset" data-toggle="modal" onclick="$('#login').modal('hide');"><?php echo _txt("forgotpassword");?></a></div>
                       
                       
                       <div class="member_join">
                           <input type="submit" name="join" id="login-button" value="<?php echo _txt("login");?>"/>
                       </div>
                       
                       <div class="member_or_line">  
                           <div class="member_or"><?php echo _txt("or");?></div>
                       </div>
                       
                       <div class="member_facebook hide">
                           <a href="#" onclick="LoadingAnimate();"><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" data-width="600" data-max-rows="1" data-show-faces="false" scope="<?php echo $settings->fbPermissions; ?>"><?php echo _txt("connectwithfacebook");?></div></a>
                       </div>
                       
   <div class="loader_box hide">
   <img src="/img/loader.gif" /> <?php echo _txt("connectingtofb");?> </div>
                       
                       
                       
                       
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
                         <div class="member_title_box"><div class="member_title"><?php echo _txt("signup");?></div></div>       	      
                             
                          <div class="member_close">
                                <a href="#" data-dismiss="modal" aria-hidden="true">
                                <img src="/images/ikimuk_disabled_close.png"/></a>
                           </div>
                         
                 <div class="member_unjoin">
                    <span><?php echo _txt("alreadymember");?></span>
                    <a href="#login" data-toggle="modal"  onclick="$('#join').modal('hide');"><?php echo _txt("login");?></a>
                  </div>     
                   </div>
               <!--End of member header-->
              
               <div class="member_body">
                  <div class="alert alert-error hide"> <button type="button" class="close" data-dismiss="alert">×</button> <span id="join_error"></span></div>
                    <!--Start of member body section-->
                   <div class="member_body_content join">
                      
                       <!--Start of name section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               <?php echo _txt("name");?>
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
                               <?php echo _txt("email");?>
                           </div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="text" name="email"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title"><?php echo _txt("password");?></div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
                      
                      <!--Start of password section-->
                        <div class="member_line">
                           <div class="member_line_title"><?php echo _txt("cpassword");?></div>
                           
                            <div class="member_line_input">
                               <input class="round_corners" type="password" name="confirm_password"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                      <!--End of password section-->
                       
                       
                       <div class="member_details">
                        <?php echo _txt("termsmsg");?>
                        <a href="/terms.php" target="_blank"><?php echo _txt("termsanch");?></a>
                        </div>
                       
                       
                       <div class="member_join">
                           <input type="submit" name="join" value="<?php echo _txt("signup");?>" id='join-button'/>
                       </div>
              
                       <div class="member_or_line">  
                           <div class="member_or"><?php echo _txt("or");?></div>
                       </div>
                       
                       <div class="member_facebook hide">
                           <a href="#" onclick="LoadingAnimate();"><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" data-width="600" data-max-rows="1" data-show-faces="false" scope="<?php echo $settings->fbPermissions; ?>"><?php echo _txt("connectwithfacebook");?></div></a>
                       </div>
   <div class="loader_box hide">
   <img src="/img/loader.gif" /> <?php echo _txt("connectingtofb");?> ... </div>
                       
                       
                       
                   </div>
                   <!--End Of Member Body Content-->
                   
                        </div>
               
                </div>
          <!--End of member content-->
</div>
   <!-- End of join part-->   

   
   <!-- Start of Reset part-->
<div id="reset" class="modal hide fade member" data-backdrop="static">
                                   
    
           <div class="member_content">
               
               <!--Start of member header-->
                 <div class="member_header">  
                                
                         <div class="member_title_box"><div class="member_title">Reset!</div></div>
    
                          <div class="member_close">
                                <a href="#" data-dismiss="modal" aria-hidden="true">
                                <img src="/images/ikimuk_disabled_close.png"/></a>
                           </div>
               
               <div class="member_body">
                   <div class="alert alert-error hide"> <button type="button" class="close" data-dismiss="alert">×</button> <span id="reset_error"></span></div>
                    <!--Start of member body section-->
                   <div class="member_body_content login">
        
                       <!--Start of email section-->
                       <div class="member_line">
                           <div class="member_line_title">
                               E-mail
                           </div>
                           
                            <div class="member_line_input">
                               <input id="email_reset" class="round_corners" type="text" name="email"/>
                            </div>
                           
                        <div class="line_error"></div>
                         </div>
                        <!--End of email section-->
                       
                       <div class="member_join">
                           <input id="reset_password_submit" type="submit" name="reset" value="RESET PASSWORD"/>
                       </div>
                       
                       
                      
                       
                       
                       
                       
                   </div>
                   <!--End Of Member Body Content-->
                   
                        </div>
               
                </div>
          <!--End of member content-->
           </div>
    
</div>
    <!-- End of Reset part-->
