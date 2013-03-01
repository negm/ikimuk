<?php
include $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.product.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.image.php';
$product = new product();
//print_r($_POST);
//return;
if (!isset($_SESSION["user_id"]) || !isset($_POST["img_url"])||
         !isset($_POST["title"])|| !isset($_POST["artist"])|| !isset($_POST["competition"]))
{echo 'shit0'; return;}
$product->title = $_POST["title"];
$product->artist_id = $_POST["artist"];
$product->competition_id = $_POST["competition"];
$product->desc = $_POST["desc"];
$product->price = $_POST["price"];
$img_arr = explode(',',$_POST["img_url"]);
if(count($img_arr) <3)
{
echo 'shit1';
return;
}
$product->insert();
if ($product->id == NULL)
{echo'shit2';return;}
else
{
    
    $count = 0;
    foreach ($img_arr as $img)
    {
        $sub_img = new image();
        $sub_img->product_id = $product->id;
        $sub_img->url = $img;
        if ($count == 0)
            $sub_img->small=1;
        if ($count == 1)
            $sub_img->primary=1;
        if ($count == 2)
            $sub_img->rollover=1;
        $sub_img->insert();
        if ($sub_img->id == null)
        {echo 'shit3'; return;}
        $count++;
    }
    echo 'done';return;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
