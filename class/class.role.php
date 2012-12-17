<?php
/*******************************************************************************
* Class Name:       role
* File Name:        class.role.php
* Generated:        Thursday, Nov 8, 2012 - 5:01:10 CET
*  - for Table:     role
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "role"
class role {
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
		$sSQL = "SELECT * FROM role WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO role () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
	}
	
	function update($mID) {
		$sSQL = "UPDATE role SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM role WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "role"
?>