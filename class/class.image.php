<?php
/*******************************************************************************
* Class Name:       image
* File Name:        class.image.php
* Generated:        Thursday, Nov 8, 2012 - 5:00:27 CET
*  - for Table:     image
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("class.database.php");

// Begin Class "image"
class image {
	// Variable declaration
	public $id; // Primary Key
	public $database;
        public $product_id;

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
		$sSQL = "SELECT * FROM image WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	public function selectByProduct($mID) { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM image WHERE product_id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
	}
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO image () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE image SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM image WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
        public function getBasicImages() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM `image` WHERE product_id = $this->product_id AND (`primary`=1 OR `rollover`=1);";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		//$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		//$this->id = $oRow->id; // Primary Key
	}
}
// End Class "image"
?>