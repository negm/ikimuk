</div>
<div class="clear"></div>
<div id="footer-wrap">
<div id="footer">
<div id="fb-root"></div>
<script>
$(document).ready(function(){
window.fbAsyncInit = function() {
FB.init({appId: '<?php echo $settings->app_id ?>',channelUrl: 'channel.php', status:true, cookie: true,xfbml: true,oath:true});

};
// Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];  if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;  js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref); }(document));

//!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
 src="//assets.pinterest.com/js/pinit.js">
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
});
</script>
<footer class="graybg">
    <div class="container" >
        <div class="offset4 footer">
            <a href="https://www.facebook.com/ikimukofficial" target="_blank" class="facebook_footer span1"></a>
            <a href="https://twitter.com/IkimukTweets" target="_blank" class="twitter_footer span1"></a>
            <a href="http://www.youtube.com/user/ikimukTV" target="_blank" class="youtube_footer span1"></a>
            <a href="mailto:hello@ikimuk.com" target="_blank" class="contact_footer span1"></a>
        </div>
        <div class="span4 clear"></div>
        <div class="offset3">For more information see our <a id="tandcModal">Terms & Conditions</a></div>
        <br><div class="text_footer centert">Crafted with &#x2764; for you</div>
        <div class="centert">&copy; 2012 ikimuk.com</div>
    </div>
</footer>
</div>
</div>
<!-- Modal -->
<div id="termsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <center><h3 id="myModalLabel">Terms and Condition</h3></center>
    
  </div>
  <div class="modal-body">
  Terms and shit :D
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</body>
</html>
<?php  print_gzipped_page(); ?>
