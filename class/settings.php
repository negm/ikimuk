<?php

//This file is for DB connection settings and other configs shared accross the app
class settings {

    public $site_url;
    public $site_url_vars;
    public $pageUrl;
    public $config;
    public $app_id;
    public $app_secret;
    public $root;
    public $fbPermissions;
    public $nexmo_key;
    public $nexmo_secret;
    public $awsAccessKey;
    public $awsSecretKey;
    public $submissionBucketName = 'submission-pics';
    public $imageBucketName = 'product-pics';
    public $salt = '$6$rounds=5000$z@3dSa890LkWsiU<$';
    public $beta_base = 'http://beta.ikimuk.com/';
    public $prodction;
    public $audi_access_code = "payment gateway code for 1st account";
    public $audi_access_code_preorder = "payment gateway code for 2nd account";
    public $audi_secure_hash = "secure hash";
    public $audi_merchant_id = "merchant id for 1st account";
    public $audi_merchant_id_preorder = "merchant id for 2nd account";
    public $audi_secure_hash_preorder = "secure hash";
    public $size_names = array("XS" => "X Small", "S" => "Small", "M" => "Medium", "L" => "Large", "XL" => "X Large", "XXL" => "XX Large");
    public $goals = array(35, 75, 76);
    public $goals_perks_add = array(" + stickers + certificate of awesomness", " + stickers", ".");
    public $goals_colors = array("cyan" , "green", "yellow", "firebrick", "magenta", "red");
    public $goals_texts = array("Once this T-shirt reaches 35 orders it will get printed. You will receive your T-shirt, stickers and a certificate of awesomeness",
				"If you order this T-shirt when it has between 35 to 75 orders, you will receive your T-shirt and stickers ",
				"If you order this T-shirt after it has reached 75 orders, you will just receive your T-shirt",
				);
    public $goals_perks = array("<ul><li>T-shirt</li><li>Stickers</li><li>Certificate of Awesomeness</li></ul>",
				"<ul><li>T-shirt</li><li>Stickers</li></ul>",
				"<ul><li>T-shirt</li></ul>");
    public function __construct() {
        $this->awsAccessKey = 'aws key';
        $this->awsSecretKey = 'aws secret';
        $this->site_url = "";
        $this->site_url_vars = "";
        $this->pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $this->site_url_vars = $this->pageURL . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            $this->pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["PHP_SELF"];
        } else {
            $this->site_url_vars = $this->pageURL . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            $this->pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
        }
        $this->site_url = $this->pageURL;
        if (strpos($this->site_url, 'localhost')) {
            $this->config = array(
                'database' => 'ikimuk',
                'username' => 'root',
                'password' => '',
                'host' => 'localhost',
                'port' => '',
                'driver' => 'mysql',
                'prefix' => '',
            );
            $this->app_id = "facebook app id for local testing";
            $this->app_secret = "facebook app secret";
            $this->root = "http://localhost:8080/";
            $this->prodction = true;
        } else
        if (strpos($this->site_url, 'production url')) {
            $this->config = array(
                'database' => 'ikimukfinal',
                'username' => 'root',
                'password' => 'sqp.2012.sql++',
                'host' => 'localhost',
                'port' => '',
                'driver' => 'mysql',
                'prefix' => '',
            );
            $this->app_id = "production facebook app";
            $this->app_secret = "secret code";
            $this->root = 'production url';
            $this->prodction = true;
        } 

        $this->fbPermissions = 'publish_stream,email,publish_actions,offline_access';
        $this->nexmo_key = 'nexmo ket';
        $this->nexmo_secret = 'nexmo secret';
    }

}

?>
