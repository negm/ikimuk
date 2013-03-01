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
    public $audi_access_code = "1E5AADCC";
    public $audi_secure_hash = "345F4F2AAB7FAE45B7DAD1DC025D47A9";
    public $audi_merchant_id = "TEST846401";
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
        $this->awsAccessKey = 'AKIAJTHFGNALHAXCUBZA';
        $this->awsSecretKey = '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';
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
            $this->app_id = "299742366741440";
            $this->app_secret = "2369fc592909a16047bbce32bd75e23d";
            $this->root = "http://localhost:8080/";
            $this->prodction = false;
        } else
        if (strpos($this->site_url, 'staging')) {
            $this->config = array(
                'database' => 'ikimuk',
                'username' => 'root',
                'password' => 'sqp.2012.sql++',
                'host' => 'localhost',
                'port' => '',
                'driver' => 'mysql',
                'prefix' => '',
            );
            $this->app_id = "151265838265960";
            $this->app_secret = "945fc91fd23a7e3cf56c9473fe813e63";
            $this->root = 'http://staging.phennec.com/';
            $this->prodction = false;
        } else {
            $this->config = array(
                'database' => 'ikimuk',
                'username' => 'root',
                'password' => 'sqp.2012.sql++',
                'host' => 'localhost',
                'port' => '',
                'driver' => 'mysql',
                'prefix' => '',
            );
            $this->app_id = "140388549312943";
            $this->app_secret = "d69fc7d02813ea962a959258e22adfde";
            $this->root = $_SERVER['HTTP_HOST'] . '/';
            $this->prodction = false;
        }

        $this->fbPermissions = 'publish_stream,email,publish_actions,offline_access';
        $this->nexmo_key = 'f20b967a';
        $this->nexmo_secret = '9d3daa40';
    }

}

?>