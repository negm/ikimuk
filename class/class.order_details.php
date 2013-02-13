<?php
/*******************************************************************************
* Class Name:       order_details
* File Name:        class.order_details.php
* Generated:        Saturday, Feb 2, 2013 - 10:35:45 CET
*  - for Table:     order_details
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once("class.database.php");

// Begin Class "order_details"
class order_details {
	// Variable declaration
	public $id; // Primary Key
	public $database;
        public $product_id;
        public $order_id;
        public $size;
        public $cut;
        public $price;
        public $quantity;

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
		$sSQL = "SELECT * FROM order_details WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysql_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->cut = mysqli_real_escape_string($this->database->link, $this->cut);
                $this->size = mysqli_real_escape_string($this->database->link, $this->size);
                $this->price = mysqli_real_escape_string($this->database->link, $this->price);
                $this->product_id = mysqli_real_escape_string($this->database->link, $this->product_id);
                $this->order_id = mysqli_real_escape_string($this->database->link, $this->order_id);
                $this->quantity = mysqli_real_escape_string($this->database->link, $this->quantity);
		$sSQL = "INSERT INTO `order_details`(`order_id`, `product_id`, `size`, `cut`, `price`, `quantity`) VALUES ($this->order_id,$this->product_id,$this->size,$this->cut,$this->price, $this->quantity);";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
	}
	
	function update($mID) {
		$sSQL = "UPDATE order_details SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM order_details WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "order_details"
?>