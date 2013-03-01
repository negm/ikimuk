<?php
/*******************************************************************************
* Class Name:       user
* File Name:        class.user.php
* Generated:        Thursday, Nov 8, 2012 - 5:01:22 CET
*  - for Table:     user
*   - in Database:  ikimuk
* Created by: table2class (http://www.stevenflesch.com/projects/table2class/)
********************************************************************************/

// Files required by class:
require_once($_SERVER["DOCUMENT_ROOT"]."/class/class.database.php");

// Begin Class "user"
class user {
	// Variable declaration
	public $id; // Primary Key
	public $database;
	public $fbid;
        public $name;
        public $email;
        public $password;
        public $validated_mobile;
        public $role_id;
        public $image;
        public $newsletter;
        public $points;

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
	
	public function select() { // SELECT Function
		// Execute SQL Query to get record.
            	$sSQL = "SELECT * FROM user WHERE id = $this->id;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->fbid            = $oRow->fbid;
                $this->name            = $oRow->name;
                $this->email           = $oRow->email;
                $this->validated_mobile= $oRow->validated_mobile;
                $this->role_id         = $oRow->role_id;
                $this->image           = $oRow->image;
                $this->newsletter      = $oRow->newsletter;
                $this->points          = $oRow->points;
	}
        public function select_by_email() { // SELECT Function
		// Execute SQL Query to get record.
            	$this->database->OpenLink();
                $this->email = mysqli_escape_string($this->database->link, strtolower($this->email));
                $sSQL = "SELECT * FROM user WHERE email = '$this->email';";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		 if ($this->database->rows >0)
		{
                $oRow = mysqli_fetch_object($oResult);
		// Assign results to class.
		$this->id = $oRow->id; // Primary Key
                $this->fbid            = $oRow->fbid;
                $this->name            = $oRow->name;
                $this->email           = $oRow->email;
                $this->validated_mobile= $oRow->validated_mobile;
                $this->role_id         = $oRow->role_id;
                $this->image           = $oRow->image;
                $this->newsletter      = $oRow->newsletter;
                $this->points          = $oRow->points;
                }
                else
                    $this->id = null;
	}
        public function selectbyfb() { // SELECT Function
		// Execute SQL Query to get record.
		$sSQL = "SELECT * FROM user WHERE fbid = $this->fbid;";
		$oResult = $this->database->query($sSQL);
		$oResult = $this->database->result;
		$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
                if ($this->database->rows >0)
		{
		$this->id = $oRow->id; // Primary Key          
                $this->name            = $oRow->name;
                $this->email           = $oRow->email;
                $this->validated_mobile= $oRow->validated_mobile;
                $this->role_id         = $oRow->role_id;
                $this->image           = $oRow->image;
                $this->newsletter      = $oRow->newsletter;
                $this->points          = $oRow->points;
                }
                else
                {$this->database->result = Null;}
	}
	public function getPreorderHistory($mID) { // SELECT Function
		// Execute SQL Query to get record.
                $this->database->OpenLink();
                
		$sSQL = "SELECT preorder.*, product.title as product_title FROM preorder INNER JOIN product ON preorder.product_id = product.id INNER JOIN image ON image.product_id = product.id INNER JOIN STATUS ON preorder.status_id = status.id WHERE user_id =$mID AND image.`primary` =1";
		$oResult = $this->database->query($sSQL);
		//$oResult = $this->database->result;
		//$oRow = mysqli_fetch_object($oResult);
		
		// Assign results to class.
		//$this->id = $oRow->id; // Primary Key
	}
	public function insert_fb() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->fbid = mysqli_real_escape_string($this->database->link, $this->fbid);
                $this->email = mysqli_real_escape_string($this->database->link, strtolower($this->email));
		$sSQL = "INSERT INTO user (fbid, name, email) VALUES ($this->fbid,'$this->name','$this->email');";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
                $this->validated_mobile = "";
                $this->role_id = 2;
	}
	public function insert() {
		$this->id = NULL; // Remove primary key value for insert
                $this->database->OpenLink();
                $this->password = mysqli_real_escape_string($this->database->link, $this->password);
                $this->email = mysqli_real_escape_string($this->database->link, strtolower($this->email));
                $this->name = mysqli_real_escape_string($this->database->link, $this->name);
		$sSQL = "INSERT INTO user (name, email,password) VALUES ('$this->name','$this->email','$this->password');";
		$oResult = $this->database->query($sSQL);
		$this->id = $this->database->lastInsertId;
                $this->validated_mobile = "";
                $this->role_id = 2;
	}
        public function login()
        {
            $this->database->OpenLink();
            $this->password = mysqli_real_escape_string($this->database->link, $this->password);
            $this->email = mysqli_real_escape_string($this->database->link, strtolower($this->email));
            $sSQL = "select * FROM user WHERE email='$this->email' AND password='$this->password';";
            $oResult = $this->database->query($sSQL);
            $oResult = $this->database->result;
            $oRow = mysqli_fetch_object($oResult);
            if ($this->database->rows == 1)
            {
                $this->id = $oRow->id;
                $this->name            = $oRow->name;
                $this->email           = $oRow->email;
                $this->validated_mobile= $oRow->validated_mobile;
                $this->role_id         = $oRow->role_id;
                $this->image           = $oRow->image;
                $this->newsletter      = $oRow->newsletter;
                $this->points          = $oRow->points;
                return true;
                
                }
            else
                return false;
        }
        public function change_password()
        {
            $this->database->OpenLink();
            $this->password = mysqli_real_escape_string($this->database->link, $this->password);
            $sSQL = "UPDATE user SET password='$this->password' WHERE id=$this->id;";
            $oResult = $this->database->query($sSQL);
            $sSQL = "Select * from  user Where password='$this->password' and id=$this->id;";
            $oResult = $this->database->query($sSQL);
            if ($this->database->rows > 0)
                return true;
            else
                return false;
        }
        public function insert_reset_code()
        {
            $code = uniqid($this->id, true);
            $expire_date = time()+24*60*60;
            $expire_date = date("Y-m-d H:i:s", $expire_date);
            $sSQL="INSERT INTO `password_reset` (code, expire_date, user_id) VALUES('$code','$expire_date',$this->id);";
            $this->database->query($sSQL);
            return $code;
        }
        public function check_reset_code($code)
        {
            $sSQL = "SELECT * from `password_reset` pwd INNER JOIN `user` u ON pwd.user_id = u.id WHERE pwd.code='$code' AND expire_date > now() LIMIT 1;";
            $oResult = $this->database->query($sSQL);
            $oResult = $this->database->result;
            if ($this->database->rows == 1)
            {
                $oRow = mysqli_fetch_object($oResult);
                $this->id = $oRow->user_id;
                $this->name            = $oRow->name;
                $this->email           = $oRow->email;
                $this->validated_mobile= $oRow->validated_mobile;
                $this->role_id         = $oRow->role_id;
                $this->image           = $oRow->image;
                $this->newsletter      = $oRow->newsletter;
                $this->points          = $oRow->points;
                return $this->database->rows;
            }
            else
                return $this->database->rows;
          
        }

        public function is_email_used()
        {
            $this->database->OpenLink();
            $this->email = mysqli_real_escape_string($this->database->link, $this->email);
            $sSQL = "SELECT * FROM user WHERE email='$this->email'";
            $this->database->query($sSQL);
            if ($this->database->rows == 1)
                return true;
            else
                return false;
        }
        function update($mID) {
		$sSQL = "UPDATE user SET (id = '$this->id') WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}
	
	public function delete($mID) {
		$sSQL = "DELETE FROM user WHERE id = $mID;";
		$oResult = $this->database->Query($sSQL);
	}

}
// End Class "user"
?>