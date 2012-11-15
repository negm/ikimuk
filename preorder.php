<?php

/*
 * This is the page to handle the pre-order process
 * State the different verification cases here
 * 
 */

include 'block/logged_in.php';
require_once 'class/settings.php';
require_once 'class/class.product.php';
require_once 'class/class.artist.php';
require_once 'class/class.image.php';
$image = new image();
$design = new product();
$artist = new artist();
if (!isset($_GET["product_id"]))
{
 header("Location: index.php");
}
else
{ 
if(!isset($_SESSION['sms_code']) )
$_SESSION['sms_code'] = substr(number_format(time() * rand(),0,'',''),0,4);
$design_id = $_GET["product_id"];
$design->select($design_id);
if(!$design->database->result)
   redirect("index.php"); 
else
{
$pagetitle = "Preorder ".$design->title;
$image->product_id = $design_id;
$_SERVER["last_preorder_design_id"] = $design_id;
$image->getBasicImages();
while ($row_image = mysqli_fetch_assoc($image->database->result))
{
   if ($row_image["primary"])
    $primary = $row_image["url"];
   if ($row_image["rollover"])
    $rollover = $row_image["url"];
}
   
    $artist->select($design->artist_id);
    if ($artist->database->result)
    {
        $regex = '/(?<!href=["\'])http:\/\//';
        $website_label = preg_replace($regex,'',$artist->website);
    }
    else
        $artist = null;
  }
}
include_once "block/header.php";
?>
<script>

  
</script>
<?php 
include "block/top_area.php";
include "block/breadcrumb.php";
?>
<div class="mainContainer span12">
<div class="span7 userInfo nomargin">
<h1 class="preTitle">Shipping Info</h1>
<div class="inputContainer">
<form id="preorderForm" class="appnitro"  method="post" action="">						
    <label class="description" for="element_1"><strong>Name </strong></label>
<div>
    <input id="name" name="name" class="element text medium" type="text" maxlength="255" value="<?php echo $_SESSION["user_name"];?>"/> <br/><br/>
</div>
		
<label class="description" for="element_2"><strong>Email </strong></label>
<div>
<input id="email" name="email" class="element text medium" type="text" maxlength="255" value="<?php echo $_SESSION["user_email"];?>"/> <br/><br/>
</div><p class="guidelines" id="span2"></p> 
<label for="element_9"><strong>Area/Region</strong></label>
<select class="element select medium" id="region" name="region"> 
<option value="Beirut" >Beirut</option>
<option value="Beirut" >Bekaa</option>
<option value="Beirut" >Mount Lebanon</option>
<option value="Beirut" >Nabatieh</option>
<option value="Beirut" >North</option>
<option value="Beirut" >South</option>
</select><br/><br/>
<label class="description" for="element_2"><strong>Address </strong></label>
<p id="address_g"><small>Please write down your full  address so we can deliver to your  doorstep!</small></p>
<input id="address" name="address" class="" type="text" maxlength="255" value=""/> <br/><br/>
<label for="element_7" id="size_l"><strong>Choose your Size</strong> </label><br/>
<p class="hidden" id="size_g"><small>Please choose your Size!</small></p>
<a href="#" name="small" class="sizeIcon span1">S</a>
<a href="#" name="medium" class="sizeIcon span1">M</a>
<a href="#" name="large" class="sizeIcon span1">L</a>
<a href="#" name="xlarge" class="sizeIcon span1">XL</a>
<a href="#" name="xxlarge" class="sizeIcon span1">XXL</a><br/><br/><br/>

<?php if (!isset($_SESSION['validated_mobile'])) {?>

<p class="hidden" id="monum_g"><small>Please fill in your 8-digit  Lebanese number!</small></p>
<label  for="element_3"><strong>Mobile number </strong></label>
<input id="ccode" name="ccode" class="ccode" type="text" maxlength="3" value="961"/> 
<input id="monum" name="monum" class="monum" type="text" maxlength="9"  onkeyup="moveOnMax(this,'verify')" value=""/> 
<a href="" id="verify" class="btn btn-inverse" role="button">get SMS code</a>
<p class="hidden" id="vcode_g2"><small>Please check you mobile now! </small></p>
<p class="hidden" id="vcode_g3"><small>You either requested more than five verification SMSz or made two requests in less than 5 minutes! </small></p>
<p class="hidden" id="vcode_g4"><small>We could not complete your request now. please try again in a while! </small></p>
<label for="vcode"><strong>Verification code </strong></label>

<input id="vcode" name="vcode" type="text" maxlength="5" value=""/> 
<p id="guide_4"><small>The code you received via SMS</small></p><br/>
<p class="hidden" id="vcode_g"><small>Please enter the right verification  code you received </small></p>
<?php }else echo '<br/>';?>
<p class="guidelines hidden" id="agreement_g"><small> You should read and agree on the terms</small></p>
<label class="checkbox" >
    <input id="agreement" name="agreement" class="" type="checkbox" value="1" /> 
    I agree on Ikimuk's <a href="#myModal" role="button" style="color:#44c6e3" data-toggle="modal">Terms & Conditions</a>
</label>
<label class="checkbox" >
    <input id="newsletter" name="newsletter" class="" type="checkbox" value="1" /> 
    Keep me in the loop, sign me up for your newsletter 
</label>
<input type="hidden" name="size" value="" id="size"/>
<center><input id="saveForm" class="button_text" type="submit" name="submit" value="Preorder" /></center>
</div></div>
<div class="preSummary span4 ">
<h1 class="preTitle">Order Summary</h1>
<div class="span4 nomargin"><?php echo '<div class="home_list span3"><a class="home_list" href="design.php?product_id='.$design_id.'" ><img class="thumbnail" src="'.$primary.'" data-hover="'.$rollover.'" /></a></div>';?></div>
<div class="span3 artistInfo">
    <?php

        if($artist)
        {
        echo '<div class="artistName span3">'.$artist->name.'</div>';
        if(strlen(trim($artist->location)) > 1)
            echo '<div class="span3 location"><img src="img/location_icon.png" class="icon" alt="location"/>'.$artist->location.'</div>';
        if(strlen(trim($artist->website)) > 1)
            echo '<div class="span3 website"><a target="_blank" href="'.urldecode($artist->website).'"><img src="img/link_icon.png" class="icon" alt="location"/>'.$website_label.'</a></div>';
        if(strlen(trim($artist->twitter)) > 1)
            echo '<div class="span3 twitter"><a <a target="_blank" href="http&#58;//twitter.com/'.$artist->twitter.'"><img class="icon" src="img/twitter_icon.png" alt="location"/>'.str_replace ('@', '', $artist->twitter).'</a></div>';
        }

?>
<!-- Button to trigger modal -->

 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Terms & Conditions</h3>
  </div>
  <div class="modal-body">
    <div class="span16" style="font-size:12; color:#4c4c4c;padding-bottom:10;">
-You have the right to post or use the design for any non-commercial use (blog, portfolio, article, ...) as long as we are mentioned or credited as owners (in example: this design was made for)
</br></br>
-By submitting a design, you acknowledge and declare that the Design you have submitted is your own original work, has not been previously published, and does not contain any trademarks, logos, copyrighted material, or any other intellectual property belonging to any third party, may, at its sole discretion, disqualify any entry that contains any material, which, in its sole discretion, deems to be profane or offensive. 
</br></br>
-If your design is chosen we retain permanent full rights to that design for commercial use on apparel and other promotional products, and you will be known as the author of that work.
</br></br>
-We reserve the right to make necessary minor adjustments or changes to submitted designs in order to conform artwork to manufacturing requirements.
</br></br>
-You acknowledge that we reserve the right to decline to select a Design for consideration for any reason.
</br></br>
-When you submit your design you agree that for 90 days we have exclusivity rights over it, you don't have the right to reproduce it, sell it or submit it to any third party, and that if after 90 days your design isn't selected you regain full rights over it
</br></br>
-We retain the right to choose your design after 90 days only if the design hasn't been used for commercial use on any item or product.
</br></br></br></br>
</div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>
<div class="clear"></div>
</div>
    <div id="orderComplete" class="span7 hidden">
        <div class="preTitle">Preorder complete</div>
        Thank you for preordering this  design! We will notify you if it  gets printed.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
    </div>
</div>
</form>	