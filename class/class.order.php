<?php
/*******************************************************************************
* Class Name:       order
* File Name:        class.order.php
* Generated:        Thursday, Nov 8, 2012 - 5:00:48 CET
*  - for Table:     order
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "order"
class order {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	
	// Class Constructor
	public function __construct() {
		$this->database = new database();
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
		$sSQL = "SELECT * FROM order WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO order () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
	}
	
	function update($mID) {
		$sSQL = "UPDATE order SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM order WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "order"
?>