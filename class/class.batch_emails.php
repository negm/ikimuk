<?php
/*******************************************************************************
* Class Name:       batch_emails
* File Name:        class.batch_emails.php
* Generated:        Monday, Mar 4, 2013 - 6:50:34 CET
*  - for Table:     batch_emails
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "batch_emails"
class batch_emails {
	// Variable declaration
	public $id; // Primary Key
	public $product_id;
        public $initial_count;
        public $actual_count;
        public $date;
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
		$sSQL = "SELECT * FROM batch_emails WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysql_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	public function select_by_product() { // SELECT Function
		// Execute SQL Query to get record.
                $this->database->OpenLink();
                $this->product_id = mysqli_real_escape_string($this->database->link,$this->product_id);
		$sSQL = "SELECT * FROM batch_emails WHERE id = $this->product_id;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		if ($this->database->rows >0)
		{
                $oRow = mysql_fetch_object($oResult);
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->product_id = $oRow->product_id;
                $this->actual_count = $oRow->actual_count;
                $this->initial_count = $oRow->initial_count;
                $this->date = $oRow->date;
                return true;
                }
                else{
                    $this->id = null;
                    return false;
                }
	}
	public function insert() {
                 $this->database->OpenLink();
                 $this->product_id = mysqli_real_escape_string($this->database->link,$this->product_id);
                 $this->initial_count = mysqli_real_escape_string($this->database->link,$this->initial_count);
                 $this->actual_count = mysqli_real_escape_string($this->database->link,$this->actual_count);
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO batch_emails () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE batch_emails SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM batch_emails WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "batch_emails"
?>