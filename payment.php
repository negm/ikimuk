<?php
include $_SERVER["DOCUMENT_ROOT"] . "/class/settings.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.order.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.order_details.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.preorder.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.preorder_details.php";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/KLogger.php";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/facebook.php";
require_once ($_SERVER["DOCUMENT_ROOT"]."/class/class.message.php");
require_once $_SERVER["DOCUMENT_ROOT"] . "/block/enums.php";
if (!isset($_SESSION))
{
    session_start ();
}
$settings = new settings();
if (isset($_GET["action"])&& $_GET["action"]== "order" )
{
$order_id = place_order();
if (!$order_id) {
    header("Location: ".$_SERVER["HTTP_REFERER"]);
} else {
    $return_url = $settings->root . "payment.php?xrf=" . $_SESSION["csrf_code"]."&action=py&type=order";
    $order_info = $_SESSION["item_count"] . " items purchased from ikimuk.com";
    $vpc_secure = strtoupper(md5($settings->audi_secure_hash.$settings->audi_access_code .
                    $_SESSION["total"]*100 . "O".$order_id . $settings->audi_merchant_id . $order_info . $return_url));
    $redirect_url = "https://gw1.audicards.com/TPGWeb/payment/prepayment.action?" .
            "accessCode=" . urlencode($settings->audi_access_code) . "&amount=" . urlencode($_SESSION["total"]*100)
            . "&merchTxnRef=" . urlencode("O".$order_id) . "&merchant=" . urlencode($settings->audi_merchant_id) .
            "&orderInfo=" . urlencode($order_info) . "&returnURL=" . urlencode($return_url) . "&vpc_SecureHash=" . $vpc_secure;
//    echo $vpc_secure . '<br>' . $redirect_url;
    header("Location: " . $redirect_url);
    //echo $redirect_url;
}
}
else
if (isset($_GET["action"])&& $_GET["action"]== "preorder" )
{
    
//just change the merchant_id to the second merchant account from AUDI
$total = 0;
$order_id = place_preorder();
if (!$order_id) {
{
    
    header("Location: ".$_SERVER["HTTP_REFERER"]);

}
} else {
    $return_url = $settings->root . "payment.php?xrf=" . $_SESSION["csrf_code"]."&action=py&type=preorder";
    $order_info = $_SESSION["item_count"] . " items purchased from ikimuk.com";
    $vpc_secure = strtoupper(md5($settings->audi_secure_hash_preorder.$settings->audi_access_code_preorder .
                    $total*100 . "P".$order_id . $settings->audi_merchant_id_preorder . $order_info . $return_url));
    $redirect_url = "https://gw1.audicards.com/TPGWeb/payment/prepayment.action?" .
            "accessCode=" . urlencode($settings->audi_access_code_preorder) . "&amount=" . urlencode($total*100)
            . "&merchTxnRef=" . urlencode("P".$order_id) . "&merchant=" . urlencode($settings->audi_merchant_id_preorder) .
            "&orderInfo=" . urlencode($order_info) . "&returnURL=" . urlencode($return_url) . "&vpc_SecureHash=" . $vpc_secure;
//    echo $vpc_secure . '<br>' . $redirect_url;
    header("Location: " . $redirect_url);
    //echo $redirect_url;
}
}
else
if (isset($_GET["vpc_TxnResponseCode"]))
{
    
    //print_r($_GET);
  //validate response
    //get secure hash value of merchant	
	//get the secure hash sent from payment client
	$vpc_Txn_Secure_Hash = addslashes($_GET["vpc_SecureHash"]);
	unset($_GET["vpc_SecureHash"]); 
	ksort($_GET);
	// set a flag to indicate if hash has been validated
	$errorExists = false;
	//check if the value of response code is valid
	if (strlen($settings->audi_secure_hash) > 0 && strlen($settings->audi_secure_hash_preorder) > 0 &&addslashes($_GET["vpc_TxnResponseCode"]) != "7" && addslashes($_GET["vpc_TxnResponseCode"]) != "No Value Returned") 
	{
		
            if ($_GET["type"]== "preorder")
            {
            //creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	    $md5HashData = $settings->audi_secure_hash_preorder;
		//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	    $md5HashData_2 = $settings->audi_secure_hash_preorder;
            }
            if ($_GET["type"]== "order")
            {
            //creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	    $md5HashData = $settings->audi_secure_hash;
		//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	    $md5HashData_2 = $settings->audi_secure_hash;
            }
            $hash_value = "";
	    // sort all the incoming vpc response fields and leave out any with no value
	    foreach($_GET as $key => $value) 
	    {
	        if ($key != "vpc_SecureHash" && strlen($value) > 0 && $key != 'action' && $key != 'xrf' && $key != 'type') 
	        {
				$hash_value = str_replace(" ",'+',$value);
				$hash_value = str_replace("%20",'+',$hash_value);
				$md5HashData_2 .= $value;
	            $md5HashData .= $hash_value;
	            
	        }
	    }

	    //if transaction secure hash is the same as the md5 variable created 
	    if ((strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData)) || strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData_2))))
	    {
	        $hashValidated = "<b>CORRECT</b>";
	    } 
	    else 
	    {
	        $hashValidated = "<b>INVALID HASH</b>";
	        $errorExists = true;
	    }
	}
       // echo $hashValidated;
      //update order
    $order = new order();
    $preorder = new preorder();
    $preorder_details = new preorder_details();
    $order_details = new order_details();
    if (is_numeric($_GET["vpc_TxnResponseCode"]) && $_GET["vpc_TxnResponseCode"] == 0 && !$errorExists)
    {
        $hoax = uniqid();
        if (isset($_GET["type"]) && $_GET["type"] == "order")
        {
        $order->id = str_replace("O", "",$_GET["merchTxnRef"]);
        $x = $order->confirm_order();
        
             $order_details->order_id = $order->id;
              $x = $order_details->select_by_order();
                while ($row = mysqli_fetch_assoc($x))
                    {
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
        if (isset($_GET["type"]) && $_GET["type"] == "preorder")
        {
            $preorder->id = str_replace("P", "",$_GET["merchTxnRef"]);
            $preorder->confirm_preorder();
            
                $preorder_details->preorder_id = $preorder->id;
                $preorder_details->select_by_preorder();
                while ($row = mysqli_fetch_assoc($preorder_details->database->result))
                {
                    $preorder_details->product_id = $row["product_id"];
                    $preorder_details->quantity = $row["quantity"];
                    $preorder_details->update_preorder_count();
                }
            $message = new message();
            $message_body="Success! Your order is confirmed. \n We will notify you if this T-shirt gets printed. \n If You have any questions, please email us at hello@ikimuk.com, we will reply pronto.\n Have a great day,\n The folks at ikimuk";
            $message->send($_SESSION['user_email'] , "ikimuk order confirmation", $message_body); 
            if (isset($_SESSION["promo_code"]))
                header("Location: /index.php?payment=success&type=preorder&utm_source=".$_SESSION["promo_code"]);
            else
                header("Location: /index.php?payment=success&type=preorder");
        }
    }
    else
    {
        if (isset($_GET["type"]) && $_GET["type"] == "order")
        {
        header("Location: /checkout.php?payment=failure&error=".urlencode(getResponseDescription($_GET["vpc_TxnResponseCode"])));
        }
         if (isset($_GET["type"]) && $_GET["type"] == "preorder")
        {
            $preorder->id = str_replace("P","",$_GET["merchTxnRef"]);
            $preorder->select($preorder->id);
            header("Location: /preorder.php?product_id=".$preorder->product_id."&payment=failure&error=".urlencode(getResponseDescription($_GET["vpc_TxnResponseCode"])));
        }
    }
}
else{
    header ("Location: /index.php");
}
function place_order() {
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
    $order->address = $_POST["address"];
    $order->phone = $_POST["phone"];
    $order->status_id = 2;
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
            $subtotal+= $cart_item["price"] * $cart_item["quantity"];
            $order_details->insert();
        }
    }
    $_SESSION["total"] = $subtotal + $country->delivery_charge;
    try {postToFB($order_details->product_id, $settings->app_id, $settings->app_secret);}
    catch (FacebookApiException $e) 
    {error_log($e);}
    return $order->id;
}
function place_preorder()
{
    global $size_enum, $cut_enum, $total,$settings;
    $country = new ip2nationcountries();
    $country->country_code = $_POST["country"];
    $country->select();
    if ($country->country_code == null) {
        return;
    }
    $preorder = new preorder();
    $preorder_detail = new preorder_details();
    $preoder_detail_arr = Array();
    if (!isset($_POST["country"])||!isset($_POST["first_name"])||
    !isset($_POST["last_name"])||!isset($_POST["address"])||
    !isset($_POST["city"])||!isset($_POST["region"])||
    !isset($_POST["tel"])||!isset($_POST["code"]))
    {header("Location: ".$_SERVER["HTTP_REFERER"]);
    
    }
    $product= new product();
    $product->select($_POST["product_id"]);
    if(!$product->id)
    {
    return;
    }
    $zip_code = (isset($_POST["zip"]))? $_POST["zip"] :"";
    $newsletter = (isset($_POST["newsletter"]))? "1" :"0";
    $preorder->user_id = $_SESSION["user_id"];
    $preorder->address = $_POST["last_name"]." ".$_POST["first_name"]
            ." ".$_POST["address"].", ".$_POST["region"].", ". $_POST["city"];
    $preorder->country = $_POST["country"];
    $preorder->phone = $_POST["code"].$_POST["tel"];
    $preorder->region = $_POST["region"];
    $preorder->newsletter = $newsletter;
    $preorder->product_id = $_POST["product_id"];
    $preorder->insert();
    if(!$preorder->id)
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    $preorder_summary = explode(",", trim($_POST["preorder_summary"]));
    if (count($preorder_summary) % 4 != 0)
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    else
    {
        
        for ($i = 0; $i<count($preorder_summary);$i+=4)
        {
            if (!is_numeric($preorder_summary[$i])|| !is_numeric($preorder_summary[$i+3]))
            {
                continue;
            }
           
            $preorder_detail->preorder_id = $preorder->id;
            $preorder_detail->cut = isset($cut_enum[$preorder_summary[$i+2]]) ? $cut_enum[$preorder_summary[$i+2]] : 0;
            $preorder_detail->size = isset($size_enum[strtolower($preorder_summary[$i+1])]) ? $size_enum[strtolower($preorder_summary[$i+1])] : 0;
            $preorder_detail->quantity = $preorder_summary[$i+3];
            $preorder_detail->product_id = $_POST["product_id"];
            $preorder_detail->price = $product->price;
            $preorder_detail->insert();
            $total+= $preorder_detail->price * $preorder_detail->quantity;
            
        }
        $total += $country->delivery_charge;
    }
    try {postToFB($_POST["product_id"], $settings->app_id, $settings->app_secret);}
    catch (FacebookApiException $e) 
    {error_log($e);}
    return $preorder->id;
}
//function to map each response code number to a text message	
function getResponseDescription($responseCode) {
    switch ($responseCode) {
        case "0" : $result = "Transaction Successful";
            break;
//        case "?" : $result = "Transaction status is unknown";
//            break;
//        case "1" : $result = "Unknown Error";
//            break;
//        case "2" : $result = "Bank Declined Transaction";
//            break;
//        case "3" : $result = "No Reply from Bank";
//            break;
        case "4" : $result = "Expired Card";
            break;
        case "5" : $result = "Insufficient funds";
            break;
//        case "6" : $result = "Error Communicating with Bank";
//            break;
//        case "7" : $result = "Payment Server System Error";
//            break;
//        case "8" : $result = "Transaction Type Not Supported";
//            break;
//        case "9" : $result = "Bank declined transaction (Do not contact Bank)";
//            break;
//        case "A" : $result = "Transaction Aborted";
//            break;
//        case "C" : $result = "Transaction Cancelled";
//            break;
//        case "D" : $result = "Deferred transaction has been received and is awaiting processing";
//            break;
        case "E" : $result = "Invalid Credit Card";
            break;
//        case "F" : $result = "3D Secure Authentication failed";
//            break;
        case "I" : $result = "Card Security Code verification failed";
            break;
//        case "G" : $result = "Invalid Merchant";
//            break;
//        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)";
//            break;
//        case "N" : $result = "Cardholder is not enrolled in Authentication scheme";
//            break;
//        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed";
//            break;
//        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed";
//            break;
//        case "S" : $result = "Duplicate SessionID (OrderInfo)";
//            break;
//        case "T" : $result = "Address Verification Failed";
//            break;
        case "U" : $result = "Card Security Code Failed";
            break;
//        case "V" : $result = "Address Verification and Card Security Code Failed";
//            break;
//        case "X" : $result = "Credit Card Blocked";
//            break;
//        case "Y" : $result = "Invalid URL";
//            break;
//        case "B" : $result = "Transaction was not completed";
//            break;
//        case "M" : $result = "Please enter all required fields";
//            break;
        //case "J" : $result = "Transaction already in use";
            //break;
        //case "BL" : $result = "Card Bin Limit Reached";
            //break;
        //case "CL" : $result = "Card Limit Reached";
            //break;
        //case "LM" : $result = "Merchant Amount Limit Reached";
            //break;
        //case "Q" : $result = "IP Blocked";
            //break;
        //case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed";
            //break;
        //case "Z" : $result = "Bin Blocked";
            //break;

        default : $result = "Transaction didn't pass through please try again";
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
