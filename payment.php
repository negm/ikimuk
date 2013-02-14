<?php


include $_SERVER["DOCUMENT_ROOT"] . "/class/settings.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.order.php";
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.order_details.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/block/enums.php";
$settings = new settings();
session_start();
if (isset($_POST["place_order"]))
{
$order_id = place_order();
if (!$order_id) {
    header("Location: /checkout.php");
} else {
    $return_url = "http://".$settings->root . "payment.php?xrf=" . $_SESSION["csrf_code"] . "&action=py";
    $order_info = $_SESSION["item_count"] . " items purchased from ikimuk.com";
    $vpc_secure = strtoupper(md5($settings->audi_access_code .
                    $_SESSION["total"] . $order_id . $settings->audi_merchant_id . $order_info . $return_url));
    $redirect_url = "https://gw1.audicards.com/TPGWeb/payment/prepayment.action?" .
            "accessCode=" . urlencode($settings->audi_access_code) . "&amount=" . urlencode($_SESSION["total"])
            . "&merchTxnRef=" . urlencode($order_id) . "&merchant=" . urlencode($settings->audi_merchant_id) .
            "&orderInfo=" . urlencode($order_info) . "&returnURL=" . urlencode($return_url) . "&vpc_SecureHash=" . $vpc_secure;
//    echo $vpc_secure . '<br>' . $redirect_url;
    header("Location: " . $redirect_url);
    //echo $redirect_url;
}
}
if (isset($_GET["vpc_TxnResponseCode"]))
{
    echo "we got back";
    print_r($_GET);
   /* //validate response
    //get secure hash value of merchant	
	//get the secure hash sent from payment client
	$vpc_Txn_Secure_Hash = addslashes($_GET["vpc_SecureHash"]);
	unset($_GET["vpc_SecureHash"]); 
	ksort($_GET);
	// set a flag to indicate if hash has been validated
	$errorExists = false;
	//check if the value of response code is valid
	if (strlen($settings->audi_secure_hash) > 0 && addslashes($_GET["vpc_TxnResponseCode"]) != "7" && addslashes($_GET["vpc_TxnResponseCode"]) != "No Value Returned") 
	{
		//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	    $md5HashData = $settings->audi_secure_hash;

		//creat an md5 variable to be compared with the passed transaction secure hash to check if url has been tampered with or not
	    $md5HashData_2 = $settings->audi_secure_hash;
            $hash_value = "";
	    // sort all the incoming vpc response fields and leave out any with no value
	    foreach($_GET as $key => $value) 
	    {
	        if ($key != "vpc_SecureHash" && strlen($value) > 0 && $key != 'action' ) 
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
            echo "zeeeee";
	} 
        echo "weeee";
      //update order
    $order = new order();
    if ($_GET["vpc_TxnResponseCode"] == 0)
    {
        $order->id = $_GET["merchTxnRef"];
        $order->confirm_order();
    }
       */ 
}

function place_order() {
    global $size_enum, $cut_enum;
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
            //print_r($cart_item);
            $order_details->order_id = $order->id;
            $order_details->price = $cart_item["price"];
            $order_details->product_id = $cart_item["product_id"];
            $order_details->quantity = $cart_item["quantity"];
            $order_details->size = isset($size_enum[$cart_item["size"]]) ? $size_enum[$cart_item["size"]] : 0;
            $order_details->cut = isset($cut_enum[$cart_item["cut"]]) ? $cut_enum[$cart_item["cut"]] : 0;
            $subtotal+= $cart_item["price"] * $cart_item["quantity"];
            $order_details->insert();
        }
    }
    $_SESSION["total"] = $subtotal + $country->delivery_charge;
    return $order->id;
}

//function to map each response code number to a text message	
function getResponseDescription($responseCode) {
    switch ($responseCode) {
        case "0" : $result = "Transaction Successful";
            break;
        case "?" : $result = "Transaction status is unknown";
            break;
        case "1" : $result = "Unknown Error";
            break;
        case "2" : $result = "Bank Declined Transaction";
            break;
        case "3" : $result = "No Reply from Bank";
            break;
        case "4" : $result = "Expired Card";
            break;
        case "5" : $result = "Insufficient funds";
            break;
        case "6" : $result = "Error Communicating with Bank";
            break;
        case "7" : $result = "Payment Server System Error";
            break;
        case "8" : $result = "Transaction Type Not Supported";
            break;
        case "9" : $result = "Bank declined transaction (Do not contact Bank)";
            break;
        case "A" : $result = "Transaction Aborted";
            break;
        case "C" : $result = "Transaction Cancelled";
            break;
        case "D" : $result = "Deferred transaction has been received and is awaiting processing";
            break;
        case "E" : $result = "Invalid Credit Card";
            break;
        case "F" : $result = "3D Secure Authentication failed";
            break;
        case "I" : $result = "Card Security Code verification failed";
            break;
        case "G" : $result = "Invalid Merchant";
            break;
        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)";
            break;
        case "N" : $result = "Cardholder is not enrolled in Authentication scheme";
            break;
        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed";
            break;
        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed";
            break;
        case "S" : $result = "Duplicate SessionID (OrderInfo)";
            break;
        case "T" : $result = "Address Verification Failed";
            break;
        case "U" : $result = "Card Security Code Failed";
            break;
        case "V" : $result = "Address Verification and Card Security Code Failed";
            break;
        case "X" : $result = "Credit Card Blocked";
            break;
        case "Y" : $result = "Invalid URL";
            break;
        case "B" : $result = "Transaction was not completed";
            break;
        case "M" : $result = "Please enter all required fields";
            break;
        case "J" : $result = "Transaction already in use";
            break;
        case "BL" : $result = "Card Bin Limit Reached";
            break;
        case "CL" : $result = "Card Limit Reached";
            break;
        case "LM" : $result = "Merchant Amount Limit Reached";
            break;
        case "Q" : $result = "IP Blocked";
            break;
        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed";
            break;
        case "Z" : $result = "Bin Blocked";
            break;

        default : $result = "Unable to be determined";
    }
    return $result;
    
    
}

?>
