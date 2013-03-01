<div class="footer">
<!--Start of footer content-->       
<div class="footer_content">
                  
                  
                  <div class="footer_social">
                      
                      <!--start of social mail section--> 
                      <div class="social_mail">
                          <div class="mail_title">
                              <span class="blue_part">NEWSLETTER</span>
                              <span class="black_part">join for updates</span>
                          </div>
                          
                          
                          <!--start of mail container-->
                          <form action="http://ikimuk.us6.list-manage1.com/subscribe/post?u=57e5f439df736442cfc265f3e&id=c47f3d4fcb" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">

                          <div class="mail_subscribe">
                              <div class="subscribe_container">
                              <div class="input_field">
                                           <input type="email" id="mce-EMAIL" name="EMAIL" value="Enter your e-mail" required/>  
                                  
                              </div>
                              <div class="subscribe_link">

                        <input class="input_submit" type="submit" value="subscribe" id="mc-embedded-subscribe" name="subscribe">
                    </div>
                              </div>   
                          </div>
                          </form>
                          <!--End of mail container-->
                      </div>
                       <!--end of social mail section--> 
                       
                       <!--start of social facebook section--> 
                      <div class="social_facebook">
                          <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-href="https://www.facebook.com/ikimukofficial" data-send="false" data-width="260" data-show-faces="true"></div>
                      </div>
                       <!--end of social facebook section--> 
                       
                       
                      <div class="social_twitter">
                          
                          <div class="twitter_balloon round_corners">
                          
                          
                         <div class="balloon_tweet"><?php include($_SERVER["DOCUMENT_ROOT"]."/process_twitter.php"); echo latestTweet();?></div>
                          </div>
                          <div class="empty_space"></div>
                          <div class="twitter_plugin"> <a href="https://twitter.com/ikimukTweets" class="twitter-follow-button" data-show-count="true">Follow @ikimukTweets</a>
                      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
                      </div>
                          
                  </div>
                  <!--End of social section-->
                  
                  <div class="horizontal_line"></div>
                  
                  <!--Start of footer links-->
                  <div class="footer_links">
                      <div class="footer_link"> <a href="/">order a t-shirt</a></div>
                      <div class="pipe"></div>
                     <div class="footer_link"> <a href="/submit-intro.php">submit your design</a></div>
                        <div class="pipe"></div>
                     <div class="footer_link"> <a href="/competitions.php">past competitions</a></div>
                      <div class="pipe"></div>
                     <div class="footer_link"> <a href="/about-us.php">about us</a></div>
                        <div class="pipe"></div>
                     <div class="footer_link"> <a href="/faq.php">faq</a></div>
                      <div class="pipe"></div>
                      <div class="footer_link"> <a href="/terms.php">terms &amp; conditions</a></div>
                        <div class="pipe"></div>
                      <div class="footer_link"> <a href="/submission_terms.php">submission terms</a></div>  
                  </div>
                  <!--End of footer links-->
                  
                  <!--Start of round links-->
                  <div class="round_links">
		     <a href="https://www.facebook.com/ikimukofficial" target="_BLANK" >
                      <div class="round_link facebook" >
                          
                      </div>
										   </a>
                      <div class="empty_space"></div>
                          <a href="https://twitter.com/IkimukTweets" target="_BLANK">                      
                      <div class="round_link twitter">  
                      </div></a>
                      <div class="empty_space"></div>
                           <a href="http://www.youtube.com/user/ikimukTV" target="_BLANK">                      
                      <div class="round_link youtube">
                      </div></a>
                      <div class="empty_space"></div>
										   <a href="mailto:hello@ikimuk.com" target="_BLANK">
                      <div class="round_link mail">  
                      </div></a>
                      <div class="empty_space"></div>
                          <a href="http://ikimuk.tumblr.com" target="_BLANK">
                      <div class="round_link tumblr">   
                      </div></a>
                      <div class="empty_space"></div>
                  </div>
                  <!--End of round links-->
                  
                  
                  <div class="copyright">
                      CRAFTED WITH LOVE FOR YOU - &copy; 2012-2013 ikimuk.com
                  </div>
                  
                  
                  
              </div>
<!--End of footer content-->


<!--Used to store the images in the cache, to be used for high speed swap-->
<div style="display:none">
<img src="/images/ikimuk_logo_beta_hover.png"/>
</div>

<script>
$(document).ready(function(){
window.fbAsyncInit = function() {
FB.init({appId: '<?php echo $settings->app_id ?>',channelUrl: '/channel.php', status:true, cookie: true,xfbml: true,oath:true});
};
// Load the SDK's source Asynchronously
 !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
});
</script>

<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js" defer></script>
<script type="text/javascript" defer>
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/BRC1rw2k4RDoDGCKQGOQ.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>

</div>
</body>
</html>
<?php  print_gzipped_page(); ?>

<!-- Modal -->
