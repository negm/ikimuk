<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$inpage_script = '<script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName(\'SCRIPT\')[0], p = d.createElement(\'SCRIPT\');
  p.type = \'text/javascript\';
  p.async = true;
  p.src = \'//assets.pinterest.com/js/pinit.js\';
  f.parentNode.insertBefore(p, f);
}(document));
</script>
 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
                            if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
 <script type="text/javascript">
                        window.___gcfg = {
                            lang: \'en-US\'
                        };

                        (function() {
                            var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
                            po.src = \'https://apis.google.com/js/plusone.js\';
                            var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
                        })();
                            </script>
    <script type="text/javascript" src="/js/jquery.nivo.slider.js"></script>
    <script type="text/javascript" src="/js/nivo-slider-custom-loader.min.js"></script>';
$selected = Array ("selected","unselected","unselected","unselected","unselected" );
if (isset($_GET["product_id"])) {
    $mID = (int) $_GET["product_id"];
} else {
    header("Location: index.php");
}
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/class.product.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/class.image.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/class.artist.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/class.competition.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/settings.php';
$regex = '/(?<!href=["\'])http:\/\//';
$product = new product();
$image = new image();
$competition = new competition();
$artist = new artist();
$settings = new settings();
$product->select($mID);
$competition->select($product->competition_id);
$artist->select($product->artist_id);
$image->selectByProduct($mID);
$pagetitle = $product->title;
$next = $product->GetNextInCompetitionID();
$prev = $product->GetPrevInCompetitionID();
$daysLeft = floor((strtotime($competition->end_date) - time()) / (60 * 60 * 24));
if ($product->database->result === NULL || $image->database->result === NULL) {
    //Something went wrong either redirect or show something
    header("Location: /index.php");
}
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";

echo '<meta property="og:title" content="' . $product->title . '" />';
echo '<meta property="og:image" content="' . $product->image . '" />';
echo '<meta property="fb:app_id" content="' . $settings->app_id . '" />';
echo '<meta property="og:url" content="' . $settings->root."design/".$product->id."/".str_replace(".","",str_replace(" ","-",trim($product->title ))).'" />';
if ($daysLeft >= 0)
{
    include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
if ($product->preorders >= $settings->goals[0])
    include $_SERVER["DOCUMENT_ROOT"] . "/block/design_view_shop.php";
    
else
{
    include $_SERVER["DOCUMENT_ROOT"] . "/block/design_view_shop.php";
    
    }
}
else
{
    $selected = Array ("unselected","unselected","selected","unselected","unselected" );
    include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
    include $_SERVER["DOCUMENT_ROOT"] . "/block/design_view_ended.php";
    
}
?>
<div xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns="http://www.w3.org/1999/xhtml"
     xmlns:foaf="http://xmlns.com/foaf/0.1/"
     xmlns:gr="http://purl.org/goodrelations/v1#"
     xmlns:xsd="http://www.w3.org/2001/XMLSchema#">
 
  <div about="#offering" typeof="gr:Offering">
    <div rev="gr:offers" resource="http://ikimuk.com/#company"></div>
    <div property="gr:name" content="<?php echo $product->title." $".$product->price; ?>" xml:lang="en"></div>
    <div property="gr:description" content="<?php echo $product->desc; ?>" xml:lang="en"></div>
    <div rel="foaf:depiction"
         resource="<?php echo $product->image; ?>">
    </div>
    <div rel="gr:hasBusinessFunction" resource="http://purl.org/goodrelations/v1#Sell">
    </div>
    <div rel="gr:hasPriceSpecification">
      <div typeof="gr:UnitPriceSpecification">
        <div property="gr:hasCurrency" content="USD" datatype="xsd:string"></div>
        <div property="gr:hasCurrencyValue" content="<?php echo $product->price; ?>" datatype="xsd:float"></div>
      </div>
    </div>
    <div rel="gr:acceptedPaymentMethods"
         resource="http://purl.org/goodrelations/v1#VISA"></div>
    <div rel="gr:acceptedPaymentMethods"
         resource="http://purl.org/goodrelations/v1#MasterCard"></div>
    <div rel="foaf:page" resource="<?php echo $settings->root."design/".$product->id."/".str_replace(".","",str_replace(" ","-",trim($product->title )));  ?>"></div>
  </div>
</div>
<?php
include $_SERVER["DOCUMENT_ROOT"] . "/block/footer.php";
?>
