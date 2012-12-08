<?php
/*******************************************************************************
* Class Name:       competition
* File Name:        class.competition.php
* Generated:        Thursday, Nov 8, 2012 - 5:00:17 CET
*  - for Table:     competition
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "competition"
class competition {
	// Variable declaration
	public $id; // Primary Key
	public $title;
        public $desc;
        public $end_date;
        public $start_date;
        public $database;
	
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
		$sSQL = "SELECT * FROM competition WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->title = $oRow->title;
                $this->desc = $oRow->desc;
                $this->end_date = $oRow->end_date;
                $this->start_date = $oRow->start_date;
	}
	public function selectActive() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM competition WHERE end_date > Now() LIMIT 1;";
		$this->database->query($sSQL);
		
		
	}
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO competition () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE competition SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM competition WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "competition"
?>