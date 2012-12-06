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
<footer>
</footer>
</div>
</div>
</body>
</html>
<?php  print_gzipped_page(); ?>
