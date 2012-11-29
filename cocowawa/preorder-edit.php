<?php

include $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.preorder.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.user.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/top_area.php';
if (!isset($_GET["preorder_id"]))
    header('Location: index.php');
?>
