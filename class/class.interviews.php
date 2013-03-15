<?php
/*******************************************************************************
* Class Name:       interviews
* File Name:        class.interviews.php
* Generated:        Monday, Mar 11, 2013 - 11:42:39 CET
*  - for Table:     interviews
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "interviews"
class interviews {
	// Variable declaration
	public $id; // Primary Key
        public $title;
        public $image;
        public $body;
        public $body_ar;
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
                $this->database->OpenLink();
                $mID= mysqli_escape_string($this->database->link, $mID);
		$sSQL = "SELECT * FROM interviews WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		if ($this->database->rows > 0){
                    $oResult = $this->database->result;
                    $oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->title = $oRow->title;
                $this->image = $oRow->image;
                $this->body = $oRow->body;
                $this->body_ar = $oRow->body_ar;
                 }
                  else
                {   $this->id = null;
                    $this->database->result = Null;}
	}
	public function get_list()
        {
           $sSQL = "SELECT * FROM interviews";
           $oResult = $this->database->query($sSQL); 
        }
        public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO interviews () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE interviews SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM interviews WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "interviews"
?>