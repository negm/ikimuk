<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/block/logged_in_admin.php';
// Menu
define("EW_MENUBAR_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EW_MENUBAR_ITEM_CLASSNAME", "", TRUE);
define("EW_MENUBAR_ITEM_LABEL_CLASSNAME", "", TRUE);
define("EW_MENU_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EW_MENU_ITEM_CLASSNAME", "", TRUE);
define("EW_MENU_ITEM_LABEL_CLASSNAME", "", TRUE);
?>
<?php

// Menu Rendering event
function Menu_Rendering(&$Menu) {

	// Change menu items here
}

// MenuItem Adding event
function MenuItem_Adding(&$Item) {

	//var_dump($Item);
	// Return FALSE if menu item not allowed

	return TRUE;
}
?>
<!-- Begin Main Menu -->
<div class="phpmaker">
<?php

// Generate all menu items
$RootMenu = new cMenu("RootMenu");
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "artistlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "competitionlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "imagelist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "ip2nation_countrieslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "message_loglist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "orderlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "order_detailslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10, $Language->MenuPhrase("10", "MenuText"), "password_resetlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "preorderlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "preorder_detailslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "productlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "rolelist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, $Language->MenuPhrase("16", "MenuText"), "statuslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, $Language->MenuPhrase("17", "MenuText"), "submission_imagelist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "submissionslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(19, $Language->MenuPhrase("19", "MenuText"), "userlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
