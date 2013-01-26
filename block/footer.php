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
                    <span class="tweet_text"><?php include("/process_twitter.php"); echo latestTweet();?></span>
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
                <a href="https://www.facebook.com/ikimukofficial" target="_blank" class="facebook_footer span1"></a>
                <a href="https://twitter.com/IkimukTweets" target="_blank" class="twitter_footer span1"></a>
                <a href="http://www.youtube.com/user/ikimukTV" target="_blank" class="youtube_footer span1"></a>
                <a href="mailto:hello@ikimuk.com" target="_blank" class="contact_footer span1"></a>
                <a href="mailto:hello@ikimuk.com" target="_blank" class="contact_footer span1"></a>
                </div>
            </div>
        <div class="clear"></div>
        <!--<div class="offset2">For more information see our <a id="tandcModal" href="#termsModal" data-toggle="modal">Terms & Conditions</a></div>-->
        <div class="offset4">Crafted with &#x2764; for you. &copy; 2012 ikimuk.com</div>
        </div>
    </div>
</footer>
</div>
</div>
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


</body>
</html>
<?php  print_gzipped_page(); ?>
