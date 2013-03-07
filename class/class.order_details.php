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
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/class/settings.php");
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
        protected $settings;

        // Class Constructor
	public function __construct() {
		$this->database = new Database();
                $this->settings = new settings();
	}
	
	// Class Destructor
	public function __destruct() {
		unset($this->database);
                unset($this->settings);
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
	public function select_by_order() { // SELECT Function
		// Execute SQL Query to get record.
                $this->database->OpenLink();    
                $this->order_id = mysqli_real_escape_string($this->database->link, $this->order_id);
		$sSQL = "SELECT * FROM order_details WHERE order_id = $this->order_id;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
                return $oResult;
	}
        public function get_refunds()
        {
            $sSQL = "SELECT order_id, SUM( quantity * price ) AS refund_amount FROM order_details WHERE product_id IN (SELECT p.id FROM product p INNER JOIN competition c ON p.competition_id = c.id WHERE end_date > DATE_SUB( NOW( ) , INTERVAL 10 DAY ) AND p.preorders >=". $this->settings->goals[0]." ) GROUP BY order_id order by order_id";
            $oResult = $this->database->query($sSQL);
        }
        public function update_order_count(){
            $sSQL = "UPDATE `product` SET preorders = preorders + $this->quantity WHERE id = $this->product_id;";
            $oResult = $this->database->query($sSQL);
            if ($this->database->rows != 0)
            {
                return true;
            }
            else
            {
                return false;
            }
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