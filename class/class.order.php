<?php
/*******************************************************************************
* Class Name:       order
* File Name:        class.order.php
* Generated:        Thursday, Nov 8, 2012 - 5:00:48 CET
*  - for Table:     order
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "order"
class order {
	// Variable declaration
	public $id; // Primary Key
	public $database;
        public $user_id;
        public $phone;
        public $country;
        public $region;
        public $address;
        public $status_id;
        public $newsletter;
        public $comments;

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
            $this->database->OpenLink();
            $mID = mysqli_real_escape_string($this->database->link,$mID);
		$sSQL = "SELECT * FROM order WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
	}
        public function confirm_order()
        {
            $sSQL = "UPDATE `order` SET status = 2 WHERE id = $this->id";
            $this->database->query($sSQL);
            if ($this->database->rows != 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        public function active_orders($user_id)
                {
                $this->database->OpenLink();
                $user_id = mysqli_real_escape_string($this->database->link,$user_id);
                $sSQL="SELECT order.*, image.url, product.title as product_title FROM order INNER JOIN product ON order.product_id = product.id INNER JOIN image ON image.product_id = product.id". 
      " INNER JOIN STATUS ON order.status_id = status.id WHERE user_id = $user_id AND image.`small` =1 AND status_id in (1,2)";
                $this->database->Query($sSQL);
                } 
	public function unconfirmed_incompetition() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT u.*,p.*,s.status,im.url, p.last_modified as order_date  FROM order p INNER JOIN status s ON p.status_id = s.id INNER JOIN user u ON p.user_id = u.id INNER JOIN product pr ON p.product_id = pr.id INNER JOIN image im ON pr.id = im.product_id WHERE status_id = 1 AND `primary`=1  ORDER BY product_id, order_date ASC;";
		$this->database->Query($sSQL);
		}
	        
       /* public function already_ordered() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM order WHERE user_id = $this->user_id AND product_id = $this->product_id;";
		$oResult = $this->database->Query($sSQL);
		if ($this->database->rows > 0 )
                    return true;
                else return false;
	}*/
	
        
        public function insert() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->phone = mysqli_real_escape_string($this->database->link, $this->phone);
                $this->country = mysqli_real_escape_string($this->database->link, $this->country);
                $this->region = mysqli_real_escape_string($this->database->link, $this->region);
                $this->address = mysqli_real_escape_string($this->database->link, $this->address);
                $this->newsletter= mysqli_real_escape_string($this->database->link, $this->newsletter);
		$sSQL = "INSERT INTO `order` (user_id, phone, country, region, address,newsletter) VALUES ($this->user_id,'$this->phone','$this->country','$this->region','$this->address',$this->newsletter)";
		$oResult = $this->database->Query($sSQL);
		$this->id = $this->database->lastInsertId;
                if ($this->id)
                {
                    return true;
                }
                else {return false;}
	}
	public function cancel($order_id) {
            $this->database->OpenLink();
            $order_id = mysqli_real_escape_string($this->database->link, $order_id);
            $sSQL = "UPDATE order SET status_id = 3 where id = $order_id;";
            $oResult  =$this->database->Query($sSQL);
            if ($this->database->rows > 0)
                {$sSQL = "update product set orders=orders-1 where id=$this->product_id";
                $this->database->Query($sSQL);
                }
            else {return;}
            }
	function update($mID) {
		$sSQL = "UPDATE order SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM order WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "order"
?>