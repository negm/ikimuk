<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$pagetitle = "Submissions";
include $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.submissions.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.user.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/top_area.php';
?>
<div class="container"><div class="row">we made it here fuckers
this is our very basic administrative panel
<br><a href="preorder-list.php">See Preorders</a>
<br><a href="submission-list.php">See Submissions</a>
<br><a href="add_product.php">Add products</a>    
<br><a href="add_product.php">Batch emailing</a>
<br><a href="refund_list.php">Refund list</a>
    
</div></div>
