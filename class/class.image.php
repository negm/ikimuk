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
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "image"
class image {
	// Variable declaration
	public $id; // Primary Key
	public $database;
        public $product_id;
        public $primary;
        public $rollover;
        public $url;
        public $desc;
        public $title;
        public $small;

        // Class Constructor
	public function __construct() {
		$this->database = new Database();
                $this->primary=0;
                $this->rollover=0;
                $this->small =0;
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
		$sSQL = "SELECT * FROM image WHERE product_id = $mID AND small= 0;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
	}
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO `image`(`primary`, `rollover`, `small`,`url`, `title`, `desc`, `product_id`) VALUES ($this->primary,$this->rollover,$this->small,'$this->url','$this->title','$this->title',$this->product_id);";
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
        public function getBasicImage() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM `image` WHERE product_id = $this->product_id AND image.small=1;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		//$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		//$this->id = $oRow->id; // Primary Key
	}
}
// End Class "image"
?>