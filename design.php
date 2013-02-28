<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
unset($_SESSION["size"]);

echo '<meta property="og:title" content="' . $product->title . '" />';
echo '<meta property="og:image" content="' . $product->image . '" />';
echo '<meta property="fb:app_id" content="' . $settings->app_id . '" />';
echo '<meta property="og:url" content="' . $settings->site_url_vars . '" />';
if ($daysLeft > 0)
{
    include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
if ($product->preorders >= $settings->goals[0])
    include $_SERVER["DOCUMENT_ROOT"] . "/block/design_view_shop.php";
    
else
{
    include $_SERVER["DOCUMENT_ROOT"] . "/block/design_view_preorder.php";
    
    }
}
else
{
    $selected = Array ("unselected","unselected","selected","unselected","unselected" );
    include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
    include $_SERVER["DOCUMENT_ROOT"] . "/block/design_view_ended.php";
    
}

include $_SERVER["DOCUMENT_ROOT"] . "/block/footer.php";
?>
