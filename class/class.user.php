<?php
/*******************************************************************************
* Class Name:       user
* File Name:        class.user.php
* Generated:        Thursday, Nov 8, 2012 - 5:01:22 CET
*  - for Table:     user
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("class.database.php");

// Begin Class "user"
class user {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $fbid;
        public $name;
        public $email;
        public $validated_mobile;
        public $role_id;
        public $image;
        public $newsletter;
        public $points;

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
		$sSQL = "SELECT * FROM user WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->fbid            = $oRow->fbid;
                $this->name            = $oRow->name;
                $this->email           = $oRow->email;
                $this->validated_mobile= $oRow->validated_mobile;
                $this->role_id         = $oRow->role_id;
                $this->image           = $oRow->image;
                $this->newsletter      = $oRow->newsletter;
                $this->points          = $oRow->points;
	}
	public function getPreorderHistory($mID) { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT *, product.title as product_title FROM preorder INNER JOIN product ON preorder.product_id = product.id INNER JOIN image ON image.product_id = product.id INNER JOIN STATUS ON preorder.status_id = status.id WHERE user_id =$mID AND image.`primary` =1";
		$oResult = $this->database->query($sSQL);
		//$oResult = $this->database->result;
		//$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		//$this->id = $oRow->id; // Primary Key
	}
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO user () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE user SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM user WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "user"
?>