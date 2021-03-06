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
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "artist"
class artist {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $name;
        public $name_ar;
        public $image;
        public $website;
        public $location;
        public $twitter;
        public $facebook;
        public $user_id;

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
		if (!is_numeric($mID))
                {
                    $this->id = null;
                    $this->database->result = null;
                    return false;
                }
                $this->database->OpenLink();
                $mID = mysqli_escape_string($this->database->link, $mID);
                $sSQL = "SELECT * FROM artist WHERE id = $mID;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
                if ($this->database->rows >0){
		// Assign results to class.
                
		$this->id = $oRow->id; // Primary Key
                
		$this->name       =urldecode($oRow->name);
                $this->name_ar       =urldecode($oRow->name_ar);
                $this->image      =htmlentities(urldecode($oRow->image))   ;
                $this->website    =htmlentities(urldecode($oRow->website)) ;
                $this->location   =htmlentities(urldecode($oRow->location));
                $this->twitter    =htmlentities(urldecode($oRow->twitter)) ;
                $this->facebook   =htmlentities(urldecode($oRow->facebook));
                $this->user_id   =$oRow->user_id;
                }
                else
                {$this->database->result = Null;}
	}
        public function select_by_user_id() { // SELECT Function
		// Execute SQL Query to get record.
                if (!is_numeric($this->user_id))
                {
                    $this->id = null;
                    $this->database->result = null;
                    return false;
                }
                 $this->database->OpenLink();
                $this->user_id = mysqli_escape_string($this->database->link, $this->user_id);
		$sSQL = "SELECT * FROM artist WHERE user_id = $this->user_id;";
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
                $this->user_id   =$oRow->user_id;
                }
                else
                {   $this->id = null;
                    $this->database->result = Null;}
	}
        public function selectAll()
        {
            $sSQL = "SELECT * FROM artist";
            $oResult = $this->database->query($sSQL);
        }
	
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->name = mysqli_escape_string($this->database->link, $this->name);
                $this->website = mysqli_escape_string($this->database->link, $this->website);
                $this->location = mysqli_escape_string($this->database->link, $this->location);
                $this->twitter = mysqli_escape_string($this->database->link, $this->twitter);
		$sSQL = "INSERT INTO artist (name, website,location,twitter,user_id) VALUES ('$this->name', '$this->website', '$this->location','$this->twitter',$this->user_id );";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
                
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