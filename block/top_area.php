<?php
include_once ($_SERVER["DOCUMENT_ROOT"].'/class/settings.php');
$settings = new settings();
if(isset($_GET["logout"]) && $_GET["logout"]==1)
{
//User clicked logout button, distroy all session variables.
$_GET["logout"] = 0;
unset($_GET["logout"]);
$_SESSION['logged_in']=false;
unset($_COOKIE);
session_destroy();
header('Location: '.$_SERVER['PHP_SELF']);
}
?>

</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="header">
<!--Start of Upper Section--> 
<div class="upper">
     
     <!--Start of Upper Content-->
                <div class="upper_content">
                    
                    
                    <div class="header_title">
                        We deliver internationally including <?php if (!isset($_SESSION["country_name"])|| strlen($_SESSION["country_name"]) < 2)echo 'your country'; else echo $_SESSION["country_name"];?> 
                    </div>
                    
                    
                    <div class="control_section">                          
<?php
if(!isset($_SESSION['logged_in'])|| !$_SESSION['logged_in'])
{
?>
   <div class="header_button login">Login</div>
   <div class="header_button joinus">Join Us</div>
<?php
}
else
{?>
    
	 <div class="login_menu">
             <div class="login_header">
                  <div class="login_arrow"></div>
                       <div class="login_name">Sevag Malkedjian</div>
                            <div class="login_avatar"><img src="images/avatar_30.png"/></div>
             </div> 
                       <div class="menu_drop">
                            <div class="empty_space"></div>
                       <div class="menu_entry"><span class="profile">My Profile</span></div>
                       <div class="menu_h_line"></div>
                       <div class="menu_entry"><span class="logout">Log Out</span></div>
                       </div>
         </div>
   
<?php } ?>

                    </div>
                   
                    
                </div>
<!--End of Upper content-->
     </div>
      <!--End of Upper section-->           
            
            <!--Start of lower section-->
            <div class="lower">
                
                <!--Start of upper content-->
                <div class="lower_content">
                    
                    <div class="logo_content">
                    <div class="lower_logo">
                        <a href="http://www.ikimuk.com">  <img src="images/ikimuk_logo_beta.png" alt="logo"/> </a>
                    </div>
                    </div>
                
                    
                         <div class="lower_menu">
                             
                             <div class="pipe"></div>
                             
                             <!--Start of Menu element-->
                             <div class="menu_element">
                                 <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                                 <input type="hidden" name="flag" value="selected"/>
                                 <!--This to set a link to go for when the user click-->
                                 <input type="hidden" name="link" value="preorder/index.php"/>
                                 
                                 <div class="menu_content">
                                     
                                     <div class="preorder">
                                     <div class="line"> pre-order </div>
                                     <div class="line"> a </div>
                                     <div class="line">t-shirt</div>
                                     </div>
                                     
                                 </div>
                             </div>
                             <!--End of menu element-->
                             
                             <div class="pipe"></div>
                             
                              <!--Start of Menu element-->
                             <div class="menu_element">
                                   <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                                 <input type="hidden" name="flag" value="unselected"/>
                                 <!--This to set a link to go for when the user click-->
                                 <input type="hidden" name="link" value="shop/index.php"/>
                                 
                                 <div class="menu_content">
                                 <div class="shop">
                                     <div class="line"> shop </div>
                                     </div>
                                 </div>
                             </div>
                              <!--End of menu element-->
                              
                             <div class="pipe"></div>
                             
                              <!--Start of Menu element-->
                             <div class="menu_element">
                                   <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                                 <input type="hidden" name="flag" value="unselected"/>
                                 <!--This to set a link to go for when the user click-->
                                 <input type="hidden" name="link" value="submit/index.php"/>
                                 
                                 <div class="menu_content">
                                  <div class="submit">
                                     <div class="line"> submit </div>
                                     <div class="line"> your </div>
                                     <div class="line">design</div>
                                     </div>
                                 </div>
                             </div>
                              <!--End of menu element-->
                              
                             <div class="pipe"></div>
                             
                              <!--Start of Menu element-->
                             <div class="menu_element">
                                   <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                                 <input type="hidden" name="flag" value="unselected"/>
                                 <!--This to set a link to go for when the user click-->
                                 <input type="hidden" name="link" value="competition/index.php"/>
                                 
                                 <div class="menu_content">
                                  <div class="competition">
                                     <div class="line"> previous </div>
                                     <div class="line"> competition </div>
                                     </div>
                                 </div>
                             </div>
                               <!--End of menu element-->
                               
                             <div class="pipe"></div>
                    
                    
                </div>
            </div>
            
        </div>
</div>
     
<!-- Modal -->

<?php //include $_SERVER["DOCUMENT_ROOT"]."/block/login.html"; ?>
<!--<div id="loginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <center><h3 id="myModalLabel">Connect Using Facebook</h3></center>
    
  </div>
  <div class="modal-body">
  <center><div class="fb-login-button" onlogin="javascript:CallAfterLogin();" data-width="600" data-max-rows="1" data-show-faces="false" scope="<?php echo $settings->fbPermissions; ?>">Connect With Facebook</div></center>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>-->

