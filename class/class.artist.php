<?php
/*******************************************************************************
* Class Name:       artist
* File Name:        class.artist.php
* Generated:        Thursday, Nov 8, 2012 - 5:00:11 CET
*  - for Table:     artist
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("class.database.php");

// Begin Class "artist"
class artist {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $name;
        public $image;
        public $website;
        public $location;
        public $twitter;
        public $facebook;

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
		$sSQL = "SELECT * FROM artist WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
                if ($this->database->rows >0){
		// Assign results to class.
                
		$this->id = $oRow->id; // Primary Key
                
		$this->name       =$oRow->name    ;
                $this->image      =$oRow->image   ;
                $this->website    =$oRow->website ;
                $this->location   =$oRow->location;
                $this->twitter    =$oRow->twitter ;
                $this->facebook   =$oRow->facebook;
                }
                else
                {$this->database->result = Null;}
	}
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO artist () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
                
	}
	
	function update($mID) {
		$sSQL = "UPDATE artist SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM artist WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "artist"
?>