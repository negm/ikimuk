<?php
include $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.product.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.image.php';
$product = new product();
$sub_img = new image();
//print_r($_POST);
//return;
if (!isset($_SESSION["user_id"]) || !isset($_POST["img_url"])||
         !isset($_POST["title"])|| !isset($_POST["artist"])|| !isset($_POST["competition"]))
{echo 'shit'; return;}
$product->title = $_POST["title"];
$product->artist_id = $_POST["artist"];
$product->competition_id = $_POST["competition"];
$product->desc = $_POST["desc"];
$product->price = $_POST["price"];
$img_arr = explode(',',$_POST["img_url"]);
if(count($img_arr) <1)
{
echo 'shit';
return;
}
$product->insert();
if ($product->id == NULL)
{echo'shit';return;}
else
{
    $sub_img->product_id = $product->id;
    foreach ($img_arr as $img)
    {
        $sub_img->url = $img;
        $sub_img->insert();
        if ($sub_img->id == null)
        {echo 'shit'; return;}
    }
    echo 'done';return;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
