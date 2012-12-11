<?php
/*******************************************************************************
* Class Name:       preorder
* File Name:        class.preorder.php
* Generated:        Thursday, Nov 8, 2012 - 5:00:48 CET
*  - for Table:     preorder
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "preorder"
class preorder {
	// Variable declaration
	public $id; // Primary Key
	public $database;
        public $user_id;
        public $product_id;
        public $phone;
        public $price;
        public $country;
        public $region;
        public $address;
        public $size;
        public $status_id;
        public $newsletter;
        public $comments;

        // Class Constructor
	public function __construct() {
		$this->database = new Database();
	}
	
	// Class Destructor
	public function __destruct() {
		unset($this->database);
	}
	
	// GET Functions
	public function getid() {
		return($this->id);
	}
	
	// SET Functions
	public function setid($mValue) {
		$this->id = $mValue;
	}
	
	public function select($mID) { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM preorder WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	public function unconfirmed_incompetition() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT u.*,p.*,s.status,im.url  FROM preorder p INNER JOIN status s ON p.status_id = s.id INNER JOIN user u ON p.user_id = u.id INNER JOIN product pr ON p.product_id = pr.id INNER JOIN image im ON pr.id = im.product_id WHERE status_id = 1 AND `primary`=1;";
		$this->database->query($sSQL);
		}
	        
        public function alreadyPreordered() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM preorder WHERE user_id = $this->user_id AND product_id = $this->product_id;";
		$oResult = $this->database->query($sSQL);
		if ($this->database->rows > 0 )
                    return true;
                else return false;
	}
	
        
        public function insert() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->phone = mysqli_real_escape_string($this->database->link, $this->phone);
                $this->country = mysqli_real_escape_string($this->database->link, $this->country);
                $this->region = mysqli_real_escape_string($this->database->link, $this->region);
                $this->address = mysqli_real_escape_string($this->database->link, $this->address);
                $this->size= mysqli_real_escape_string($this->database->link, $this->size);
                $this->newsletter= mysqli_real_escape_string($this->database->link, $this->newsletter);
		$sSQL = "INSERT INTO preorder (user_id, product_id,phone, country, region, address,size,newsletter) VALUES ($this->user_id,$this->product_id,'$this->phone','$this->country','$this->region','$this->address','$this->size',$this->newsletter)";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
                if ($this->id)
                $sSQL = "update product set preorders=preorders+1 where id=$this->product_id";
                $this->database->query($sSQL);
                $sSQL = "update user set validated_mobile ='$this->phone' where id=$this->user_id";
                $this->database->query($sSQL);
	}
	
	function update($mID) {
		$sSQL = "UPDATE preorder SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM preorder WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "preorder"
?>