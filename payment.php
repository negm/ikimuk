<?php
require $_SERVER["DOCUMENT_ROOT"] . "/class/settings.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/class.order.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/class.order_details.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/class.payment_log.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php";
require $_SERVER["DOCUMENT_ROOT"] . "/inc/KLogger.php";
require $_SERVER["DOCUMENT_ROOT"] . "/inc/facebook.php";
require_once ($_SERVER["DOCUMENT_ROOT"]."/class/class.message.php");
require_once $_SERVER["DOCUMENT_ROOT"] . "/block/enums.php";

if (!isset($_SESSION)){
  session_start ();
}
$settings = new settings();
$logger = new payment_log();
if (isset($_POST["payment"]) && $_POST["payment"] == "cash"){
  $order_id = place_cash_order();  
  if (!$order_id) {
    header("Location: /checkout.php?payment=failure&error=cash_err1");
    return;
  } else {
    $order = new order();
    $order_details = new order_details();
    $order->id = $order_id;
    $x = $order->confirm_order();
    $order_details->order_id = $order->id;
    $x = $order_details->select_by_order();
    while ($row = mysqli_fetch_assoc($x)){
      echo $row["product_id"].$row["quantity"];
      $order_details->product_id = $row["product_id"];
      $order_details->quantity = $row["quantity"];
      $order_details->update_order_count();
    }
    $_SESSION["cart"]=null;
    $_SESSION["item_count"]=0;
    $_SESSION["subtotal"]=0;
    $_SESSION["total"]=0;
    header("Location: /index.php?payment=success&type=order");
    return;
  }
}
if (isset($_GET["action"])&& $_GET["action"]== "order" ){
  $order_id = place_order();
  if (!$order_id) {
    header("Location: ".$_SERVER["HTTP_REFERER"]);
    return;
  } else {
    $return_url = $settings->root . "payment.php?xrf=" . $_SESSION["csrf_code"]."&action=py&type=order";
    $order_info = $_SESSION["item_count"] . " items purchased from ikimuk.com";
    $vpc_secure = strtoupper(md5($settings->audi_secure_hash.$settings->audi_access_code .
				 $_SESSION["total"]*100 . "O".$order_id . $settings->audi_merchant_id . $order_info . $return_url));
    $redirect_url = "https://gw1.audicards.com/TPGWeb/payment/prepayment.action?" .
      "accessCode=" . urlencode($settings->audi_access_code) . "&amount=" . urlencode($_SESSION["total"]*100)
      . "&merchTxnRef=" . urlencode("O".$order_id) . "&merchant=" . urlencode($settings->audi_merchant_id) .
      "&orderInfo=" . urlencode($order_info) . "&returnURL=" . urlencode($return_url) . "&vpc_SecureHash=" . $vpc_secure;
    header("Location: " . $redirect_url);
    return;
  }
}
else{
  if (isset($_GET["vpc_TxnResponseCode"])){
    
    //validate response
    //get secure hash value of merchant	
    //get the secure hash sent from payment client
    $logger->response_code = $_GET["vpc_TxnResponseCode"];
    $logger->entire_url = http_build_query($_GET);
    $logger->log_request();
    $vpc_Txn_Secure_Hash = addslashes($_GET["vpc_SecureHash"]);
    unset($_GET["vpc_SecureHash"]); 
    ksort($_GET);
    // set a flag to indicate if hash has been validated
    $errorExists = false;
    //check if the value of response code is valid
    if (strlen($settings->audi_secure_hash) > 0 && strlen($settings->audi_secure_hash_preorder) > 0 &&addslashes($_GET["vpc_TxnResponseCode"]) != "7" && addslashes($_GET["vpc_TxnResponseCode"]) != "No Value Returned"){
      if ($_GET["type"]== "order"){
	//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	$md5HashData = $settings->audi_secure_hash;
	//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	$md5HashData_2 = $settings->audi_secure_hash;
      }
      $hash_value = "";
      // sort all the incoming vpc response fields and leave out any with no value
      foreach($_GET as $key => $value){
	  if ($key != "vpc_SecureHash" && strlen($value) > 0 && $key != 'action' && $key != 'xrf' && $key != 'type'){
	    $hash_value = str_replace(" ",'+',$value);
	    $hash_value = str_replace("%20",'+',$hash_value);
	    $md5HashData_2 .= $value;
	    $md5HashData .= $hash_value;
	  }
      }
      //if transaction secure hash is the same as the md5 variable created 
      if ((strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData)) || strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData_2)))){
	$hashValidated = "<b>CORRECT</b>";
      }else{
	$hashValidated = "<b>INVALID HASH</b>";
	$errorExists = true;
      }
    }
       // echo $hashValidated;
      //update order
    $order = new order();
    $order_details = new order_details();
    if (is_numeric($_GET["vpc_TxnResponseCode"]) && $_GET["vpc_TxnResponseCode"] == 0 && !$errorExists){
      $hoax = uniqid();
      if (isset($_GET["type"]) && $_GET["type"] == "order"){
	$order->id = str_replace("O", "",$_GET["merchTxnRef"]);
        $x = $order->confirm_order();
	$order_details->order_id = $order->id;
	$x = $order_details->select_by_order();
	while ($row = mysqli_fetch_assoc($x)){
	  echo $row["product_id"].$row["quantity"];
	  $order_details->product_id = $row["product_id"];
	  $order_details->quantity = $row["quantity"];
	  $order_details->update_order_count();
	}
        $_SESSION["cart"]=null;
        $_SESSION["item_count"]=0;
        $_SESSION["subtotal"]=0;
        $_SESSION["total"]=0;
        $hoax = uniqid();
        $message = new message();
        $message_body="Success! Your order has been processed. \n Expect your delivery in 1-2 weeks. \n Your tracking code is: $hoax - $order->id \n Should you have any concerns regarding your delivery, please email us at info@ikimuk.com \n Have a great day,\n The folks at ikimuk";
        $message->send($_SESSION['user_email'] , "ikimuk order confirmation", $message_body);
        if (isset($_SESSION["promo_code"]))
            header("Location: /index.php?payment=success&type=order&utm_source=".$_SESSION["promo_code"]);
        else
            header("Location: /index.php?payment=success&type=order&utm_source=".$_SESSION["promo_code"]);
        }
      return;
    }else{
      if (isset($_GET["type"]) && $_GET["type"] == "order"){
        header("Location: /checkout.php?payment=failure&error=".urlencode(getResponseDescription($_GET["vpc_TxnResponseCode"])));
	return;
      }       
    }
  }
  else{
    header ("Location: /index.php");
    return; 
  }
}


function place_cash_order() {
    if (!isset($_POST["country"])||!isset($_POST["first_name"])||
	!isset($_POST["last_name"])||!isset($_POST["address"])||
	!isset($_POST["city"])||!isset($_POST["region"])||
	!isset($_POST["tel"])||!isset($_POST["code"])){
      header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    $_SESSION["form_first_name"]=$_POST["first_name"];
    $_SESSION["form_last_name"]=$_POST["last_name"];
    $_SESSION["form_address"]=$_POST["address"];
    $_SESSION["form_city"]=$_POST["city"];
    $_SESSION["form_region"]=$_POST["region"];
    $_SESSION["form_tel"]=$_POST["tel"];
    if (isset($_POST["zip"]))
      $_SESSION["form_zip"] = $_POST["zip"];
    global $size_enum, $cut_enum,$settings;
    $order = new order();
    $order_details = array();
    $count = 0; 
    $commit = true; 
    $country = new ip2nationcountries();
    $country->country_code = $_POST["country"];
    $country->select();
    $subtotal = 0;
    if ($country->country_code == null) {
      return false;
    }else{
      if($country->cash_on_delivery == 0){
	return false;
      }
    }
    $order->user_id = $_SESSION["user_id"];
    $order->country = $_POST["country"];
    $order->region = $_POST["region"];
    $order->address = $_POST["last_name"]." ".$_POST["first_name"]
            ." ".$_POST["address"].", ".$_POST["region"].", ". $_POST["city"];
    if (isset($_POST["zip"]))
        $order->address .=" ".$_POST["zip"];
    $order->phone = $_POST["phone"];
    $order->status_id = 1;
    $order->type="cash";
    $order->newsletter = isset($_POST["newsletter"]) ? 1 : 0;
    $cart = $_SESSION["cart"];
    foreach ($cart as $key => $cart_item) {
      if($cart_item["quantity"] > 1){
	$commit = false;
      }else{
	$order_details[$count] = new order_details();
	$order_details[$count]->price = $cart_item["price"];
	$order_details[$count]->product_id = $cart_item["product_id"];
	$order_details[$count]->quantity = $cart_item["quantity"];
	$order_details[$count]->size = isset($size_enum[strtolower($cart_item["size"])]) ? $size_enum[strtolower($cart_item["size"])] : 0;
	$order_details[$count]->cut = isset($cut_enum[$cart_item["cut"]]) ? $cut_enum[$cart_item["cut"]] : 0;
	$discount = 1 - $settings->goals_discount[$cart_item["goal"]-1];
	if($order_details[$count]->select_cash_delivery_by_user_product_and_size($order->user_id)){
	  $commit=false;
	  
	}else{
	  $subtotal+= $cart_item["price"] * $cart_item["quantity"]*$discount;
	}
      }
      $count = $count +1;
    }
    if($commit){
      $order->insert();
      if (!$order->id) {
        return false;
      }
      for($i= 0; $i < $count; $i++){
	$order_details[$i]->order_id = $order->id;
	$order_details[$i]->insert();
      }     
    }else{
      return false;
    }
    $_SESSION["total"] = $subtotal + $country->delivery_charge;
    try {postToFB($order_details->product_id, $settings->app_id, $settings->app_secret);}
    catch (FacebookApiException $e) 
    {error_log($e);}
    return $order->id;
}



function place_order() {
    if (!isset($_POST["country"])||!isset($_POST["first_name"])||
    !isset($_POST["last_name"])||!isset($_POST["address"])||
    !isset($_POST["city"])||!isset($_POST["region"])||
    !isset($_POST["tel"])||!isset($_POST["code"]))
    {header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    $_SESSION["form_first_name"]=$_POST["first_name"];
    $_SESSION["form_last_name"]=$_POST["last_name"];
    $_SESSION["form_address"]=$_POST["address"];
    $_SESSION["form_city"]=$_POST["city"];
    $_SESSION["form_region"]=$_POST["region"];
    $_SESSION["form_tel"]=$_POST["tel"];
    if (isset($_POST["zip"]))
        $_SESSION["form_zip"] = $_POST["zip"];
    global $size_enum, $cut_enum,$settings;
    $order = new order();
    $order_details = new order_details();
    $country = new ip2nationcountries();
    $country->country_code = $_POST["country"];
    $country->select();
    $subtotal = 0;
    if ($country->country_code == null) {
        return false;
    }
    $order->user_id = $_SESSION["user_id"];
    $order->country = $_POST["country"];
    $order->region = $_POST["region"];
    $order->address = $_POST["last_name"]." ".$_POST["first_name"]
            ." ".$_POST["address"].", ".$_POST["region"].", ". $_POST["city"];
    if (isset($_POST["zip"]))
        $order->address .=" ".$_POST["zip"];
    $order->phone = $_POST["phone"];
    $order->status_id = 1;
    $order->type="credit";
    $order->newsletter = isset($_POST["newsletter"]) ? 1 : 0;
    $order->insert();
    if (!$order->id) {
        return false;
    } else {
        $cart = $_SESSION["cart"];
        foreach ($cart as $key => $cart_item) {
            
            $order_details->order_id = $order->id;
            $order_details->price = $cart_item["price"];
            $order_details->product_id = $cart_item["product_id"];
            $order_details->quantity = $cart_item["quantity"];
            $order_details->size = isset($size_enum[strtolower($cart_item["size"])]) ? $size_enum[strtolower($cart_item["size"])] : 0;
            $order_details->cut = isset($cut_enum[$cart_item["cut"]]) ? $cut_enum[$cart_item["cut"]] : 0;
$discount = 1 - $settings->goals_discount[$cart_item["goal"]-1];
            $subtotal+= $cart_item["price"] * $cart_item["quantity"]*$discount;
	    $order_details->insert();
        }
    }
    $_SESSION["total"] = $subtotal + $country->delivery_charge;
    try {postToFB($order_details->product_id, $settings->app_id, $settings->app_secret);}
    catch (FacebookApiException $e) 
    {error_log($e);}
    return $order->id;
}

//function to map each response code number to a text message	
function getResponseDescription($responseCode) {
    switch ($responseCode) {
        case "0" : $result = "Payment is successful.";
            break;
        case "?" : $result = "payment_err1";
           break;
          case "1" : $result = "payment_err2";
            break;
          case "2" : $result = "payment_err3";
            break;
          case "3" : $result = "payment_err4";
            break;
        case "4" : $result = "payment_err5";
            break;
        case "5" : $result = "payment_err6";
            break;
        case "6" : $result = "payment_err7";
            break;
        case "7" :{ 
            if (isset($_GET["vpc_Message"])&& strpos($_GET["vpc_Message"], "Invalid Card Expiry Date") > 0) 
                $result = "payment_err8";
                else $result = "payment_err9";
                    
                }
            break;
        case "8" : $result = "payment_err10";
            break;
        case "9" : $result = "payment_err11";
            break;
        case "A" : $result = "payment_err12";
            break;
        case "C" : $result = "payment_err13";
            break;
        case "D" : $result = "payment_err14";
            break;
        case "E" : $result = "payment_err15";
            break;
        case "F" : $result = "payment_err16";
            break;
        case "I" : $result = "payment_err17";
            break;
        case "G" : $result = "payment_err18";
            break;
        case "L" : $result = "payment_err19";
            break;
        case "N" : $result = "payment_err20";
            break;
        case "P" : $result = "payment_err21";
            break;
        case "R" : $result = "payment_err22";
            break;
        case "S" : $result = "payment_err23";
            break;
        case "T" : $result = "payment_err24";
            break;
        case "U" : $result = "payment_err25";
            break;
        case "V" : $result = "payment_err26";
            break;
        case "X" : $result = "payment_err27";
            break;
        case "Y" : $result = "payment_err28";
            break;
        case "B" : $result = "payment_err29";
           break;
        case "M" : $result = "payment_err30";
            break;
        case "J" : $result = "payment_err31";
            break;
        case "BL" : $result = "payment_err32";
            break;
        case "CL" : $result = "payment_err34";
            break;
        case "LM" : $result = "payment_err35";
            break;
        case "Q" : $result = "payment_err36";
            break;
        case "R" : $result = "payment_err37";
            break;
        case "Z" : $result = "payment_err38";
            break;

        default : $result = "payment_err39";
    }
    return $result; 
}

function postToFB($product_id,$api_key,$api_secret)
{
$facebook = new Facebook(array('appId'=>$api_key,'secret'=>$api_secret));
$params = array('design'=>'http://ikimuk.com/design.php?product_id='.$product_id,'access_token'=>$_SESSION["access_token"]);
$out = $facebook->api('/me/ikimukapp:preorder','post',$params);
}
?>
