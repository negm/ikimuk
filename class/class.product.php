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
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

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
        public $image;

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
		$sSQL = "SELECT product . * , image.url FROM product INNER JOIN image ON image.product_id = product.id WHERE product.id =$mID AND image.`primary` =1;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
                if ($this->database->rows >0)
		{
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
                $this->image = $oRow->url;
                }
                else
                {$this->database->result = Null;}
	}
	public function CurrentCompetitionDesigns() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT pr.*,artist.name, competition.end_date FROM `product` pr INNER JOIN competition ON pr.competition_id = competition.id INNER JOIN artist ON pr.artist_id = artist.id WHERE competition.end_date > NOW() ;";
		$this->database->query($sSQL);
		
	}
	public function GetNextInCompetitionID() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT id FROM `product` WHERE competition_id = $this->competition_id AND id=$this->id+1";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
                if ($this->database->rows >0)
		{$oRow = mysqli_fetch_object($oResult);
                return $oRow->id;
                }
                else {return null;}
	}
        public function GetPrevInCompetitionID() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT id FROM `product` WHERE competition_id = $this->competition_id AND id=$this->id-1";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
                if ($this->database->rows >0)
		{$oRow = mysqli_fetch_object($oResult);
                return $oRow->id;
                }
                else {return null;}
	}
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO `product`(`title`, `artist_id`, `competition_id`, `price`, `desc`) VALUES ('$this->title',$this->artist_id,$this->competition_id,$this->price,'$this->desc');";
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