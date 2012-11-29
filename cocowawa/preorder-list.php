<?php
include $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.preorder.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.user.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/top_area.php';
$preorders_list = new preorder();
$preorders_list->unconfirmed_incompetition();
echo '<p>Unconfirmed in the current competition</p><div id="unconfirmed_incompetition">';
?>
<table>
  <tbody>
    <!-- Results table headers -->
    <tr>
      <th>User Name</th>
      <th>User Email</th>
      <th>Mobile Number</th>
      <th>Address</th>
      <th>Size</th>
      <th>Status</th>
      <th>Comments</th>
      <th>Edit</th>
    </tr>
    
<?php 
while($row_preorder = mysqli_fetch_object($preorders_list->database->result))
{
    echo '<tr>';
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo " <td></td>";
    echo " <td></td>";
    echo " <td></td>";
    echo '<td><a href="">Edit</a></td>';
    echo '</tr>';
}
echo '</div>';//unconfirmed in competition
?>
 </tbody>
</table>