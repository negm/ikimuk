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
echo '<div class="container"><div class="row">we made it here fuckers';
echo ' this is our very basic administrative panel';
echo '<br><a href="preorder-list.php">See Preorders</a>';
echo '<br><a href="submission-list.php">See Submissions</a></div></div>';
?>
