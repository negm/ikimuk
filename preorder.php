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
$settings = new settings();
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
$pagetitle = $product->title;
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
 echo '<meta property="og:title" content="'.$product->title.'" />';
    echo '<meta property="og:image" content="'.$product->image.'" />';
    echo '<meta property="fb:app_id" content="'.$settings->app_id.'" />';
    echo '<meta property="og:url" content="'.$settings->root.'design.php?product_id='.$design_id.'" />';
?>
<script>
    function preordered()
  {
      FB.api(
        '/me/ikimukapp:preorder',
        'post',
        { design: '<?php echo $settings->root.'design.php?product_id='.$design_id; ?>' },
        function(response) {
           if (!response || response.error) {
             console.log("action wasn't posted to facebook"+response.error)
           } else {
              console.log('preorder was successful! Action ID: ' + response.id);
           }
        });
  }
</script>
<?php 
include "block/top_area.php";
//include "block/breadcrumb.php";
echo '<div class="container"><ul id="" class="brdc"><li class="span2"><a href="index.php">Preorder a T-shirt</a><span class="divid">/</span></li><li class="span3"><a href="design.php?product_id='.$product->id.'" >'.$product->title .'</a><span class="divid">/</span></li><li class= " tyellow">Preorder</li></ul></div>';
?>
<div class="clear"></div>
<div class="container">
<div class="row">
    <div class="clear"></div>
<div class="span8">
<div class="userInfo">
<h1 class="preTitle">Shipping Info</h1>
<div class="inputContainer center">
<form id="preorderForm" class="appnitro"  method="post" action="">						
    <label class="description" for="element_1"><strong>Name </strong></label>
<div>
    <input id="name" name="name" class="span6" type="text" maxlength="255" value="<?php echo $_SESSION["user_name"];?>"/> <br/><br/>
</div>
		
<label class="description" for="element_2"><strong>Email </strong></label>
<div>
<input id="email" name="email" class="span6" type="text" maxlength="255" value="<?php echo $_SESSION["user_email"];?>"/> <br/><br/>
</div> 
<label for="element_9"><strong>Area/Region</strong></label>
<p class="hidden" id="region_g">Please choose an area!</p>
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
<p id="address_g" class=" hidden">Do you live in an empty space box? If not, you gotta fill this up!</p>
<input id="address" name="address" class="span6" type="text" maxlength="255" value=""/> <br/><br/>
<input id="size" name="size" type="hidden" value="<?php echo $_SESSION["size"];?>" />
<input id="design_id" name="design_id" type="hidden" value="<?php echo $design_id;?>" />
<?php if (!isset($_SESSION['validated_mobile'])) {?>
<div class="">
<label  class="tlarge"><strong>Verification</strong></label>
<div class="line"></div>
<p>Almost done! We are gonna send you an SMS as soon as you give us your phone number just to make sure you are not a robot!</p><br>
<label  for="element_3"><strong>Mobile number </strong></label>
<p class="hidden" id="monum_g">Please fill in your 8-digit  Lebanese number!</p>
<div class="input-append">
<input id="ccode" name="ccode" class="ccode span1 centert" type="text" maxlength="4" value="+961"/>
<input id="monum" name="monum" class="monum span4" type="text" maxlength="8"  onkeyup="moveOnMax(this,'verify')" value=""/> 
<a href="" id="verify" class="btn btn-inverse" role="button">get SMS code</a>
</div> <br>
<p class="hidden" id="vcode_g2">Please check your mobile now!</p>
<p class="hidden" id="vcode_g3">You either requested more than five verification SMSz or made 3 requests in less than 5 minutes! </p>
<p class="hidden" id="vcode_g4">We could not complete your request now. please try again in a while! </p>
<label for="vcode"><strong>Verification code </strong></label>
<p class="hidden" id="vcode_g">Please enter the right verification  code you received </p>
<input id="vcode" name="vcode" type="text" maxlength="5" value="" class="span6"/> <br>
<br/>
</div>
<?php }?>
<p class="hidden" id="agreement_g"> You should read and agree on the terms</p>
<label class="checkbox" >
    <input id="agreement" name="agreement" class="" type="checkbox" value="1" /> 
    I agree to ikimuk's <a href="#myModal" role="button" style="color:#44c6e3" data-toggle="modal">Terms & Conditions</a>
</label>
<label class="checkbox" >
    <input id="newsletter" name="newsletter" class="" type="checkbox" value="1" /> 
    Keep me in the loop, sign me up for your newsletter 
</label>
<br><a id="preorderSubmit" class="offset1 span3 preorderButton" >Preorder</a><br><br>
</form>
</div></div></div>
<div class="clear"></div>
<div class="span4 ">
    <div class="clear"></div>
<div class="preSummary">
<h1 class="preTitle">Order Summary</h1>
<div class="span4 pleft">
  <?php echo '<br><div class="span3 thumb-big center"><a class="" href="design.php?product_id='.$design_id.
          '" ><img class="" src="'.$primary.'"  alt="'.$product->title.' ikimuk"/></a></div>';?></div>
<div class="span3 artistInfo pleft">
    <?php

        if($artist)
        {
        echo '<h1 class="designT">'.$product->title.' <b class ="tblack tnormal"> by </b><br><b class="tlblue tnormal">'.$artist->name.'</b></h1>';
        }
        echo '<p>Size ('.$_SESSION["size"].')</p>';
        echo '<div class="lineb"></div>';
        echo '<div class="tnheight">T-shirt<span class="right">'.$product->price.'.00$</span></div>';
        echo '<div class="tnheight">Delivery charge<span class="right">3.00$</span></div>';
        echo '<div class="lineb"></div>';
        echo '<div class="tnheight"><b>TOTAL<span class="right">'.($product->price+3) .'.00$</span></b></div>';
        unset($_SESSION["size"]);
?>
</div>
</div></div>

 <div id="orderComplete" class="span6 hidden">
     <br>
        <div class="txlarge">Awesome, Preorder complete</div><br>
        Thank you for preordering this  design! We will let you know if it wins the competition and gets printed.<br/><br/>
        Until then, <a href="index.php" class="tlblue">why not check out the other designs?</a>
        
 </div>
<div id="orderDuplicate" class="span6 hidden">
     <br>
        <div class="txlarge"></div><br>
        Oops! Sorry, you can't preorder the same design twice.You can preorder another one if you'd like.<br/><br/>
        For now, <a href="index.php" class="tlblue" >why not check out the other designs?</a>
        
    </div>
</div>
</div>
<!-- Button to trigger modal -->

 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Preorder Terms and Conditions</h3>
  </div>
  <div class="modal-body">
    <div class="span16" style="font-size:12; color:#4c4c4c;padding-bottom:10;">
      <p>By clicking on the preorder button, you expressly and irrevocably accept the following terms and
conditions, and hereby undertake and agree on the following:</p>

<p>1. You are fully aware that by preordering a design, two possibilities may arise:
a) If by the time the competition ends, the design you preordered has not won, the preorder will
be cancelled;</p>
<p>b) If by the time the competition ends, the design you preordered won, the preorder is
confirmed and you are deemed having irrevocably bought the product. In such case, no
cancellation of the preorder shall be made possible.</p>

<p>2. Upon the end of the competition, a notification shall be sent to all those who preordered the selected
design, and a confirmation of the address to which the product will be delivered shall be requested.</p>

<p>3. Unless Force Majeure occurs, the delivery time will not exceed a period of three weeks following the
end of the competition.
Ikimuk.com shall not be liable for any delay or loss imputable to the carrier.</p>

<p>4. Payment for the product shall take place on, and only on delivery.
The payment can only be made by cash, and should be handed over to whoever is delivering the
product.</p>

<p>5. Once the results are definitive and the design selected, said design will be available in the online shop,
and those who wish to obtain the product may order it therefrom.
In such case, delivery time will not exceed, unless Force Majeure occurs, three weeks following the
order taking place.</p>

<p>6. Any conflict arising from this agreement shall be governed by and construed in accordance with
Lebanese law and shall be subject to the exclusive jurisdiction of the competent court in Lebanon.</p>

<p>By preordering or ordering a product from ikimuk.com, you acknowledge that you have read and fully
understood these Terms and Conditions, and that you agree to be bound by them, and you wish to
preorder and order your product from this website in accordance with them.</p>
</br></br></br></br>
</div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<div class="clear"></div><br><br>
   


<?php
include 'block/footer.php';
?>