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
                          <form action="http://ikimuk.us6.list-manage1.com/subscribe/post?u=57e5f439df736442cfc265f3e&amp;id=c47f3d4fcb" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
                          <div class="mail_subscribe">
                              <div class="subscribe_container">
                              <div class="input_field">
                                           <input type="email" name="email" value="Enter your e-mail" required/>  
                                  
                              </div>
                              <div class="subscribe_link">

                        <input class="input_submit" type="submit" value="subscribe">
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
<div class="fb-like" data-href="http://www.facebook.com/pages/I-am-ProgrammerI-have-no-life/241806149201604?ref=ts&fref=ts" data-send="false" data-width="260" data-show-faces="true"></div>
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
                      <div class="footer_link"> <a href="/">pre-order at t-shirt</a></div>
                      <div class="pipe"></div>
                     <div class="footer_link"> <a href="/submit-intro.php">submit your design</a></div>
                        <div class="pipe"></div>
                     <div class="footer_link"> <a href="/competitions.php">previous competition</a></div>
                      <div class="pipe"></div>
                     <div class="footer_link"> <a href="/about-us.php">about us</a></div>
                        <div class="pipe"></div>
                     <div class="footer_link"> <a href="/faq.php">faq</a></div>
                      <div class="pipe"></div>
                      <div class="footer_link"> <a href="/terms.php">terms &amp; conditions</a></div>
                        <div class="pipe"></div>
                      <div class="footer_link"> <a href="/privacy.php">privacy policy</a></div>  
                  </div>
                  <!--End of footer links-->
                  
                  <!--Start of round links-->
                  <div class="round_links">
                      <div class="round_link">
                          <a href="https://www.facebook.com/ikimukofficial" target="_BLANK" >  <img src="/images/ikimuk_facebook_footer.png" alt="facebook"/></a>
                      </div>
                      <div class="empty_space"></div>
                      
                      <div class="round_link">
                          <a href="https://twitter.com/IkimukTweets" target="_BLANK" >    <img src="/images/ikimuk_twitter_footer.png" alt="twitter"/></a>
                      </div>
                      <div class="empty_space"></div>
                      
                      <div class="round_link">
                           <a href="http://www.youtube.com/user/ikimukTV" target="_BLANK" >   <img src="/images/ikimuk_youtube_footer.png" alt="youtube"/></a>
                      </div>
                      <div class="empty_space"></div>
                      
                      <div class="round_link">
                           <a href="mailto:hello@ikimuk.com" target="_BLANK" >   <img src="/images/ikimuk_contact_footer.png" alt="contact"/></a>
                      </div>
                      <div class="empty_space"></div>
                      <div class="round_link">
                          <a href="http://www.tumblr.com" target="_BLANK" >    <img src="/images/ikimuk_tumblr_footer.png" alt="tumblr"/></a>
                      </div>
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
<img src="/images/ikimuk_facebook_footer.png"/>
<img src="/images/ikimuk_twitter_footer.png"/>
<img src="/images/ikimuk_youtube_footer.png"/>
<img src="/images/ikimuk_contact_footer.png"/>
<img src="/images/ikimuk_tumblr_footer.png"/>
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

<!--
<div class="clear"></div>
<div id="footer-wrap">
<div id="footer">
<div id="fb-root"></div>
<footer class="">
    <div class="container" >
        <div class="row">
            <div class="span4">
                <b class="tmedium blue">NEWSLETTER</b> Subscribe to stay updated
                <div class="newsletter">
                <form action="http://ikimuk.us6.list-manage1.com/subscribe/post?u=57e5f439df736442cfc265f3e&amp;id=c47f3d4fcb" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div class="input-append">
                <input type="email" value="" name="EMAIL" class="" id="mce-EMAIL" placeholder="Your E-mail" required>
                <input type="submit" value="SUBSCRIBE" name="subscribe" id="mc-embedded-subscribe" class="button">
                </div>
                </form>
                </div>
            </div>  
            <div class="span4">
                <div class="fb-like" data-href="https://www.facebook.com/ikimukofficial" data-send="false" data-width="300" data-show-faces="true"></div>
            </div>
            <div class="span4">
                <div id="footer-tweet">
                <ul class="tweet_list">
                    <span class="tweet_text"></span>
                </ul>
                </div>
                <div class="follow">
                    <a href="https://twitter.com/ikimukTweets" class="twitter-follow-button" data-show-count="true">Follow @ikimukTweets</a>
                </div>
            </div>
            
       </div>  
        <div class="row " >
            <div class="footer">
                <div class="socialContainer center">
               
                
             
                <a href="mailto:hello@ikimuk.com" target="_blank" class="contact_footer span1"></a>
                <a href="mailto:hello@ikimuk.com" target="_blank" class="contact_footer span1"></a>
                </div>
            </div>
        <div class="clear"></div>
        <div class="offset2">For more information see our <a id="tandcModal" href="#termsModal" data-toggle="modal">Terms & Conditions</a></div>
        <div class="offset4">Crafted with &#x2764; for you. &copy; 2012 ikimuk.com</div>
        </div>
    </div>
</footer>
</div>
</div>-->
<!-- Modal -->
<div id="termsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <center><h3 id="myModalLabel">General Website Terms and Conditions</h3></center>
    
  </div>
  <div class="modal-body">
  <?php include  $_SERVER["DOCUMENT_ROOT"]."/block/GeneralTerms";?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>