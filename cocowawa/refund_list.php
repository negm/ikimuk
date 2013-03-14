<?php

$pagetitle = "Refund List";
require_once  $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.ip2nationcountries.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.product.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.order.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.order_details.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/header.php';
include $_SERVER["DOCUMENT_ROOT"].'/block/top_area.php';
$country = new ip2nationcountries();
$settings = new settings();
$orders_list = new order();
$orders_list->confirmed_in_competition();
$refund_list = array();
$order_details = new order_details();
$product = new product();
while($row_order =  mysqli_fetch_object($orders_list->database->result))
{
    $order_details->order_id = $row_order->id;
    $order_details->select_by_order();
    $subtract_delivery = true;
    $one_piece = false;
    $refund_amount = 0;
    while($row_order_detail =  mysqli_fetch_object($order_details->database->result))
    {
        $product->select($row_order_detail->product_id);
        if ($product->preorders > $settings->goals[0])
            $refund_amount += $row_order_detail->quantity * $product->price;
        else
        {$subtract_delivery = false;  
        $one_piece = true;
        }
    }
    if ($subtract_delivery && !$one_piece)
    {
        $country->country_code = $row_order->country;
        $country->select();
        $refund_amount += $country->delivery_charge;
    }
    if ($refund_amount > 0)
        $refund_list[]= array ("order_id"=>$row_order->id,"refund_amount"=>$refund_amount);
}
?>
<div class="container"><p>List of refunds Due for the latest competition</p><div id="unconfirmed_incompetition">
<table class="table table-hover table-striped">
  <tbody class="tbl">
    <!-- Results table headers -->
    <tr>
      <th></th>  
      <th>Order ID</th>
      <th>Amount Due</th>
      <!--<th></th>-->
    </tr>
    
<?php 
$count=1;
foreach($refund_list as $key=>$item)
{
    echo '<tr>';
    echo "<td>$count</td>";
    echo "<td>".$item["order_id"]."</td>";
    echo "<td>".$item["refund_amount"]."</td>";
    //echo '<td><a class="btn" href="preorder-edit.php?preorder_id='.$row_preorder->id.'">Edit</a></td>';
    echo '</tr>';
    $count+=1;
 }
?>
    
</tbody></table> </div></div>