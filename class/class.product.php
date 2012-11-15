<?php
/*******************************************************************************
* Class Name:       product
* File Name:        class.product.php
* Generated:        Thursday, Nov 8, 2012 - 5:01:04 CET
*  - for Table:     product
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("class.database.php");

// Begin Class "product"
class product {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $title;
        public $artist_id;
        public $competition_id;
        public $shop;
        public $price;
        public $desc;
        public $preorders;
        public $views;

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
	
	//Select functions
        public function select($mID) { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM product WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->title = $oRow->title;
                $this->artist_id = $oRow->artist_id;
                $this->competition_id = $oRow->competition_id;
                $this->shop = $oRow->shop;
                $this->price = $oRow->price;
                $this->desc = $oRow->desc;
                $this->preorders = $oRow->preorders;
                $this->views = $oRow->views;
	}
	public function CurrentCompetitionDesigns() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT product.* FROM `product` inner join competition on product.competition_id = competition.id WHERE competition.end_date > NOW();";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
	}
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO product () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE product SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM product WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "product"
?>