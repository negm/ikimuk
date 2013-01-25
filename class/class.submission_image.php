<?php
/*******************************************************************************
* Class Name:       submission_image
* File Name:        class.submission_image.php
* Generated:        Thursday, Nov 22, 2012 - 22:54:45 CET
*  - for Table:     submission_image
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("/class/class.database.php");

// Begin Class "submission_image"
class submission_image {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $submission_id;
        public $url;

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
		$sSQL = "SELECT * FROM submission_image WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysql_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->submission_id = mysqli_real_escape_string($this->database->link, $this->submission_id);
                $this->url = mysqli_real_escape_string($this->database->link, $this->url);
                $sSQL = "INSERT INTO submission_image (submission_id,url) VALUES ($this->submission_id, '$this->url');";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
	}
	
	function update($mID) {
		$sSQL = "UPDATE submission_image SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM submission_image WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "submission_image"
?>