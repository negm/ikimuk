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
$submissions_list = new submissions();
$submissions_list->selectAllSubmissions();
echo '<div class="container"><p>Unconfirmed in the current competition</p><div id="unconfirmed_incompetition">';
?>
<table class="table table-striped table-hover">
  <tbody class="">
    <!-- Results table headers -->
    <tr>
      <th></th>  
      <th>Artist Name</th>
      <th>Artist Email</th>
      <th>Facebook ID</th>
      <th>Design Title</th>
      <th>Inspiration</th>
      <th>Image</th>
      <th>Date</th>
      <!--<th></th>-->
    </tr>
    <?php 
$count=1;

while($row_preorder =  mysqli_fetch_object($submissions_list->database->result))
{
    $row_preorder->email = trim($row_preorder->email);
    echo '<tr>';
    echo "<td>$count</td>";
    echo "<td>$row_preorder->name</td>";
    echo "<td>$row_preorder->email</td>";
    echo "<td>$row_preorder->fbid</td>";
    echo "<td>$row_preorder->title</td>";
    echo "<td>$row_preorder->comments</td>";
    echo '<td class="thumb"><img src="'.htmlentities(urldecode($row_preorder->url)).'" /></td>';
    echo "<td>$row_preorder->submission_date</td>";
    //echo '<td><a class="btn" href="preorder-edit.php?preorder_id='.$row_preorder->id.'">Edit</a></td>';
    echo '</tr>';
    $count+=1;
 }

    
echo '</tbody></table> </div></div>';//unconfirmed in competition
?>
