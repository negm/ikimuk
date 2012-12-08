<?php
/*******************************************************************************
* Class Name:       submissions
* File Name:        class.submissions.php
* Generated:        Thursday, Nov 15, 2012 - 7:16:56 CET
*  - for Table:     submissions
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "submissions"
class submissions {
	// Variable declaration
	public $id; // Primary Key
	public $database;
        public $title;
        public $comment;
        public $user_id;
	
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
		$sSQL = "SELECT * FROM submissions WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->title = $oRow->title;
                $this->user_id = $oRow->user_id;
                }
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO submissions (user_id,title,comments) VALUES ($this->user_id,'$this->title','$this->comment');";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE submissions SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM submissions WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "submissions"
?>