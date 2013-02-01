<?php
        //set the session
        if(!isset($_SESSION))
        {
            session_start();
        }
        
        if (!isset($_POST["number"]))
        {
            echo 'shit';
            return;
        }
        else 
            {
            $phone = '+'.trim($_POST["number"]);
     
            }
 //check if a code was sent in less than 5 minutes
        $five_minutes = 5*60;
        if (isset($_SESSION["sms_ts"]))
        {
         if (time() - $_SESSION["sms_ts"] <= $five_minutes)
         {
            if (isset($_SESSION["sms_count"]))
            {
                if ($_SESSION["sms_count"] > 2)
                {
                    echo "shit";
                    return;
                }
            }
         }
         if (isset($_SESSION["sms_count"]))
         {
             if ($_SESSION["sms_count"]>= 5)
             {
                 echo "shit";
                 return;
             }
         }
             
        }
        if (!isset($_SESSION["sms_code"]))
        {
            
            echo "shit";
            return;
        }
        else
            $random = $_SESSION["sms_code"];
        
        require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
	require_once ( $_SERVER["DOCUMENT_ROOT"]."/inc/NexmoMessage.php" );
        $settings = new settings();
        

	/**
	 * To send a text message.
	 *
	 */

	// Step 1: Declare new NexmoMessage.
	$nexmo_sms = new NexmoMessage($settings->nexmo_key, $settings->nexmo_secret);
        
        // Step 2: Use sendText( $to, $from, $message ) method to send a message. 
	$info = $nexmo_sms->sendText( $phone, 'Ikimuk', "Hello! Please use the following code to complete the preorder $random" );
        echo 'done';
	// Step 3: Display an overview of the message
	//echo $nexmo_sms->displayOverview($info);
        print_r($info);
        
        //Step 4: 
        //Set session variables
        $_SESSION["sms_ts"] = time();
        if(!isset($_SESSION["sms_count"]))
          $_SESSION["sms_count"]=1; 
        else $_SESSION["sms_count"]+=1;
	

	// Done!
?>
