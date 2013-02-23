<?php
/*******************************************************************************
* Class Name:       ip2nationcountries
* File Name:        class.ip2nationcountries.php
* Generated:        Tuesday, Feb 12, 2013 - 12:50:30 CET
*  - for Table:     ip2nationcountries
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "ip2nationcountries"
class ip2nationcountries {
	// Variable declaration
	public $country_code; // Primary Key
        public $delivery_charge;
        public $database;
	public $phone_code;

        // Class Constructor
	public function __construct() {
		$this->database = new Database();
	}
	
	// Class Destructor
	public function __destruct() {
		unset($this->database);
	}
	
	// GET Functions
	public function getcountry_code() {
		return($this->country_code);
	}
	
	// SET Functions
	public function setcountry_code($mValue) {
		$this->country_code = $mValue;
	}
	
	public function select() { // SELECT Function
		// Execute SQL Query to get record.
                $this->database->OpenLink();
                $this->country_code = mysqli_escape_string($this->database->link, $this->country_code);
		$sSQL = "SELECT * FROM ip2nation_countries WHERE country_code = '$this->country_code' LIMIT 1;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		if ($this->database->rows == 1)
                {$oRow = mysqli_fetch_object($oResult);
                $this->delivery_charge = $oRow->delivery_charge;
                $this->phone_code = $oRow->phone_code; // Primary Key
		$this->country_code = $oRow->country_code; // Primary Key
                }
                else
                {
                    $this->country_code = null;
                 }
	}
	public function select_all_countries() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM ip2nation_countries WHERE delivery_charge <> 0";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		
	}
	public function insert() {
		$this->country_code = NULL; // Remove primary key value for insert
		$sSQL = "INSERT INTO ip2nationcountries () VALUES ();";
		$oResult = $this->database->query($sSQL);
		$this->country_code = $this->database->lastinsertid;
	}
	
	function update($mID) {
		$sSQL = "UPDATE ip2nation_countries SET (country_code = '$this->country_code') WHERE country_code = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM ip2nation_countries WHERE country_code = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "ip2nationcountries"
?>