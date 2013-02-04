<?php
/*******************************************************************************
* Class Name:       stock
* File Name:        class.stock.php
* Generated:        Saturday, Feb 2, 2013 - 10:35:37 CET
*  - for Table:     stock
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("class.database.php");

// Begin Class "stock"
class stock {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $product_id;
        public $size;
        public $cut;
        public $available_stock;

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
		$sSQL = "SELECT * FROM stock WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysql_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	public function selectByProduct()
        {
            if (!is_numeric($this->id))
                {
                  $this->database->result = null;
                  $this->id = null;
                  return;
                }
                $this->database->OpenLink();
                $this->id = mysqli_real_escape_string($this->database->link, $this->id);
                $sSQL = "SELECT * FROM `stock` WHERE product_id = $this->id;";
                $oResult = $this->database->query($sSQL);
        }
        public function decreaseStock($quantity)
        {
            if (!is_numeric($quantity))
                {
                  $this->database->result = null;
                  $this->id = null;
                  return;
                }
                $this->database->OpenLink();
                $quantity = mysqli_real_escape_string($this->database->link, $quantity);
                $this->product_id = mysqli_real_escape_string($this->database->link, $this->product_id);
                $this->size = mysqli_real_escape_string($this->database->link, $this->size);
                $this->cut = mysqli_real_escape_string($this->database->link, $this->cut);
                $sSQL= "UPDATE `stock` set avalable_stock = available_stock - $quantity WHERE product_id = $this->product_id AND size = $this->size AND cut = $this->cut";
                $this->database->query($sSQL);
                if ($this->database->rows > 0)
                    return true;
                else
                    return false;
        }
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO stock () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE stock SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM stock WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "stock"
?>