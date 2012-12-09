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
$product = new product();
$artist = new artist();
if (!isset($_GET["product_id"]))
{
 header("Location: index.php");
}
else
{
$design_id = $_GET["product_id"];
if(!isset($_SESSION['size']) )
{
header("Location: design.php?product_id=$design_id");
}
if(!isset($_SESSION['sms_code']) )
{$_SESSION['sms_code'] = substr(number_format(time() * rand(),0,'',''),0,4);}

$product->select($design_id);
if(!$product->database->result)
   header("Location: index.php"); 
else
{
$pagetitle = "Preorder ".$product->title;
$image->product_id = $design_id;
$_SERVER["last_preorder_design_id"] = $design_id;
$image->getBasicImage();
while ($row_image = mysqli_fetch_assoc($image->database->result))
{
   if ($row_image["small"])
    $primary = $row_image["url"];
   
}
   
    $artist->select($product->artist_id);
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
    //$(document).ready(function() {$('#preorderForm').ajaxForm(preorder_options);})
</script>
<?php 
include "block/top_area.php";
include "block/breadcrumb.php";
?>
<div class="clear"></div>
<div class="container">
<div class="row">
    <div class="clear"></div>
<div class="span8">
<div class="userInfo">
<h1 class="preTitle">Shipping Info</h1>
<div class="inputContainer">
<form id="preorderForm" class="appnitro"  method="post" action="">						
    <label class="description" for="element_1"><strong>Name </strong></label>
<div>
    <input id="name" name="name" class="span6" type="text" maxlength="255" value="<?php echo $_SESSION["user_name"];?>"/> <br/><br/>
</div>
		
<label class="description" for="element_2"><strong>Email </strong></label>
<div>
<input id="email" name="email" class="span6" type="text" maxlength="255" value="<?php echo $_SESSION["user_email"];?>"/> <br/><br/>
</div><p class="guidelines" id="span2"></p> 
<label for="element_9"><strong>Area/Region</strong></label>
<p class="hidden" id="region_g"><small>Please choose an area!</small></p>
<select class="span6" id="region" name="region"> 
    <option value="" selected="selected" >I live in..</option>
    <option value="Beirut" >Beirut</option>
    <option value="Bekaa" >Bekaa</option>
    <option value="Mount Lebanont" >Mount Lebanon</option>
    <option value="Nabatieh" >Nabatieh</option>
    <option value="North" >North</option>
    <option value="South" >South</option>
</select><br/><br/>
<label class="description" for="element_2"><strong>Address </strong></label>
<p id="address_g"><small>Please write down your full  address so we can deliver to your  doorstep!</small></p>
<input id="address" name="address" class="span6" type="text" maxlength="255" value=""/> <br/><br/>
<input id="size" name="size" type="hidden" value="<?echo $_SESSION["size"]; //unset($_SESSION["size"]);?>" />
<input id="design_id" name="design_id" type="hidden" value="<?echo $design_id;?>" />
<?php if (!isset($_SESSION['validated_mobile'])) {?>
<div class="">
<p class="hidden" id="monum_g"><small>Please fill in your 8-digit  Lebanese number!</small></p>
<label  for="element_3"><strong>Mobile number </strong></label>
<div class="input-append">
<input id="ccode" name="ccode" class="ccode span1 centert" type="text" maxlength="3" value="961"/> 
<input id="monum" name="monum" class="monum span4" type="text" maxlength="8"  onkeyup="moveOnMax(this,'verify')" value=""/> 
<a href="" id="verify" class="btn btn-inverse" role="button">get SMS code</a>
</div>
<p class="hidden" id="vcode_g2"><small>Please check you mobile now! </small></p>
<p class="hidden" id="vcode_g3"><small>You either requested more than five verification SMSz or made two requests in less than 5 minutes! </small></p>
<p class="hidden" id="vcode_g4"><small>We could not complete your request now. please try again in a while! </small></p>
<label for="vcode"><strong>Verification code </strong></label>
<p class="hidden" id="vcode_g"><small>Please enter the right verification  code you received </small></p>
<input id="vcode" name="vcode" type="text" maxlength="5" value="" class="span6"/> 
<p id=""><small>The code you received via SMS</small></p><br/>
</div>
<?php }?>
<p class="hidden" id="agreement_g"><small> You should read and agree on the terms</small></p>
<label class="checkbox" >
    <input id="agreement" name="agreement" class="" type="checkbox" value="1" /> 
    I agree on Ikimuk's <a href="#myModal" role="button" style="color:#44c6e3" data-toggle="modal">Terms & Conditions</a>
</label>
<label class="checkbox" >
    <input id="newsletter" name="newsletter" class="" type="checkbox" value="1" /> 
    Keep me in the loop, sign me up for your newsletter 
</label>
<input type="hidden" name="size" value="" id="size"/>
<br><a id="preorderSubmit" class="offset1 span3 preorderButton" >Preorder</a><br>
</form>
</div></div></div>
<div class="clear"></div>
<div class="span4 ">
    <div class="clear"></div>
<div class="preSummary">
<h1 class="preTitle">Order Summary</h1>
<div class="span4">
  <?php echo '<div class="span3 thumb-big center"><a class="" href="design.php?product_id='.$design_id.
          '" ><img class="" src="'.$primary.'" /></a></div>';?></div>
<div class="span3 artistInfo">
    <?php

        if($artist)
        {
        echo '<div class="designT">'.$product->title.' <b class ="tblack tnormal"> by </b><b class="tlblue tnormal">'.$artist->name.'</b></div>';
        }
        echo '<p>Size ('.$_SESSION["size"].')</p>';
        echo '<div class="lineb"></div>';
        echo '<div>T-shirt<span class="right">'.$product->price.'.00$</span></div>';
        echo '<div>Deilivery charge<span class="right">3.00$</span></div>';
        echo '<div class="lineb"></div>';
        echo '<div><b>TOTAL<span class="right">'.($product->price+3) .'.00$</span></b></div>';
        unset($_SESSION["size"]);
?>
</div>
</div></div>
</div></div>
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

<div class="clear"></div>
    <div id="orderComplete" class="span7 hidden">
        <div class="preTitle">Preorder complete</div>
        Thank you for preordering this  design! We will notify you if it  gets printed.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
    </div>


<?php
include 'block/footer.php';
?>