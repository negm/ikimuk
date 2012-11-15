<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
require_once 'settings.php';
include_once 'block/logged_in.php';
$param = $_POST;
if (isset($_SESSION["last_preorder_design_id"]))
{
    $param["design_id"]= $_SESSION["last_preorder_design_id"];
}
else
{
    echo 'design error'; return;
}
//print_r($param);
if (!$param["name"] || strlen(trim($param["name"])) < 5)
{echo 'name error'; return;}
if (!$param["email"] || strlen(trim($param["email"])) < 9 )
{echo 'email error'; return;}
if (!isset($_SESSION["validated_mobile"]))
{
if (!$param["ccode"] || strlen(trim($param["ccode"])) < 1 )
{echo 'country coode error'; return;}
if (!$param["monum"] || strlen(trim($param["monum"])) < 6 || strlen(trim($param["monum"])) > 8 )
{echo 'mobile error'; return;}
if (!$param["vcode"] || strlen(trim($param["vcode"])) < 4 ||trim($param["vcode"])!= $_SESSION["sms_code"])
{echo 'verification error'; return;}
$phone = $param["ccode"].$param["monum"];
}
 else {
 $phone = $_SESSION["validated_mobile"];
 }
if (!$param["address"] || strlen(trim($param["address"])) < 4 )
{echo 'address error'; return;}
if (isset($param["size"]))
{
if (!$param["size"] || strlen(trim($param["size"])) < 1 )
{echo 'size error'; return;}
}
else
{echo 'size error'; return;}
if (isset($param["agreement"]))
{
if (!$param["agreement"] )
{echo 'agreement error'; return;}
}
else
{echo 'agreement error'; return;}
if (strlen(trim($param["design_id"])) < 1 )
{echo 'design error'; return;}
if (alreadyPreordered($_SESSION["user_id"],$param["design_id"]))
{echo 'already voted'; return;}

$mysqli->query("insert into preorder (user_id, design_id,phone, country, region, address,size) values 
 (".$_SESSION["user_id"].",".$param["design_id"].",'".$phone."','Lebanon','".$param["region"]."','".$param["address"]."','".$param["size"]."')");
$mysqli->query("update product set preorders=preorders+1 where id=". $param["design_id"]);
if(!isset($_SESSION["validated_mobile"]))
{ 
$mysqli->query("update user set validated_mobile ='$phone' where id=".$_SESSION["user_id"]);
$_SESSION["validated_mobile"] = $phone;
}
echo 'done';
function alreadyPreordered($user_id, $design_id)
{
    global $mysqli;
    $result = $mysqli->query("select * from preorder where user_id= $user_id and design_id = $design_id");
    if ($result->num_rows >0)
        return true;
    else
        return false;
}
?>
