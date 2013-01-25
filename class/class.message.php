<?php

require_once("/inc/ses.php");
require_once("/class/class.database.php");
// Begin Class "competition"
class message {
    public $message;
    public $type;
    public $to;
    public $from;
    private $settings;
    public function __construct() 
    {
        $this->database = new database();
        $this->message = new SimpleEmailServiceMessage();
        $this->type= "";
        $this->from= "ikimuk <noreply@ikimuk.com>";
        $this->settings = new settings();
        
    }
    
    public function __destruct() {
		unset($this->database);
                unset($this->message);
	}
    public function send ($to, $subject, $body)
    {
        
        $this->message->addTo($to);
        $this->message->setFrom($this->from);
        $this->message->setSubject($subject);
        $this->message->setMessageFromString($body);
        $ses = new SimpleEmailService($this->settings->awsAccessKey, $this->settings->awsSecretKey);
        $ses->enableVerifyPeer(false);
        $result =$ses->sendEmail($this->message);
        $this->log_message($result,$to,$subject,$body);
        return $result;
    }
    protected function log_message($result,$to,$subject,$body)
    {
     $this->database->OpenLink();
     $result["MessageId"]= mysqli_real_escape_string($this->database->link, $result["MessageId"]);
     $result["RequestId"]= mysqli_real_escape_string($this->database->link, $result["RequestId"]);
     $to= mysqli_real_escape_string($this->database->link, $to);
     $subject= mysqli_real_escape_string($this->database->link, $subject);
     $body= mysqli_real_escape_string($this->database->link, $body);
     $sSQL = "INSERT INTO `message_log`(`messageId`, `requestId`, `recepient`, `subject`, `body`) VALUES ('".$result["MessageId"]."','".$result["RequestId"]."','$to','$subject','$body')";
     $oResult = $this->database->query($sSQL);
    }
}
?>
