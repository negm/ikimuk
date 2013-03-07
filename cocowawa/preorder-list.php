<?php
$pagetitle = "Preorders";
require_once  $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.preorder.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.user.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/top_area.php';
$preorders_list = new preorder();
$preorders_list->unconfirmed_incompetition();
?>
<div class="container"><p>Unconfirmed in the current competition</p><div id="unconfirmed_incompetition">
<table class="table table-hover table-striped">
  <tbody class="tbl">
    <!-- Results table headers -->
    <tr>
      <th></th>  
      <th>User Name</th>
      <th>User Email</th>
      <th>Mobile Number</th>
      <th>Address</th>
      <th>product</th>
      <th>Size</th>
      <th>Status</th>
      <th>Date</th>
      <!--<th></th>-->
    </tr>
    
<?php 
$count=1;
while($row_preorder =  mysqli_fetch_object($preorders_list->database->result))
{
    $row_preorder->email = trim($row_preorder->email);
    echo '<tr>';
    echo "<td>$count</td>";
    echo "<td>$row_preorder->name</td>";
    echo "<td>$row_preorder->email</td>";
    echo "<td>$row_preorder->phone</td>";
    echo "<td>$row_preorder->address</td>";
    echo '<td><a href="../design.php?product_id='.$row_preorder->product_id.'" class="thumb"><img src="'.$row_preorder->url.'" /></a></td>';
    echo " <td>$row_preorder->size</td>";
    echo " <td>$row_preorder->status</td>";
    echo "<td> $row_preorder->preorder_date</td>";
    //echo '<td><a class="btn" href="preorder-edit.php?preorder_id='.$row_preorder->id.'">Edit</a></td>';
    echo '</tr>';
    $count+=1;
 }
?>
    
</tbody></table> </div></div>

 