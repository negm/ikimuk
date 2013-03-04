<?php
require_once  $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once  $_SERVER["DOCUMENT_ROOT"]."/class/class.batch_email.php";
require_once  $_SERVER["DOCUMENT_ROOT"]."/class/class.preorder.php";
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_POST["action"]) && isset($_POST["product_id"]))
{
$preorder = new preorder();

}



?>
