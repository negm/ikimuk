<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.message.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");
class payment_log {
    public $message;
    public $response_code;
    public $entire_url;
    public $codes_for_email;
    public $date;
    private $settings;
    public function __construct() 
    {
        $this->database = new database();
        $this->message = new message();
        $this->message->type= "";
        $this->message->from= "ikimuk payment issue <noreply@ikimuk.com>";
        $this->message->to = array("mackram@seeqnce.com","accounts@ikimuk.com", "hussein@ikimuk.com");
        $this->codes_for_email = array("M", "1", "7", "8", "D", "p","R","S","Y","B","J", "BL","G", "LM","Q","Z","?");
        $this->settings = new settings();
        
    }
    
    public function __destruct() {
		unset($this->database);
                unset($this->message);
                unset ($this->settings);
	}
   
    public function log_request()
    {
     $this->database->OpenLink();
     $this->entire_url= mysqli_real_escape_string($this->database->link, $this->entire_url);
     $this->response_code= mysqli_real_escape_string($this->database->link, $this->response_code);
     $sSQL = "INSERT INTO `payment_log`(`response_code`, `entire_url`) VALUES ('$this->response_code', '$this->entire_url')";
     $oResult = $this->database->query($sSQL);
     if (in_array($this->response_code,$this->codes_for_email)!= false)
     {
         $subject = "Audi payment issue";
         $body = "Sup Nigga, \n The following issue has occured and u need to take care of it \n 
             The response code captured was: $this->response_code \n Full response from audi had the following parameters:\n $this->entire_url ";
         $this->message->send($this->message->to, $subject, $body);
     }
    }
}
?>
