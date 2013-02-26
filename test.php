<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER["DOCUMENT_ROOT"]."/block/enums.php";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/KLogger.php";
$text = "23,xxl,m|23,xxl,m";
$details = ( explode("|", $text));
foreach ($details as $key=>$detail )
    {
        $details[$key] = explode(",", $detail);
    }
  /*  print_r($details);
    $size = "s";
    //echo $size_enum[$size];
    
    
    echo "<br>".uniqid()."<br>";
    echo uniqid("xxx")."<br>";
    echo uniqid(true)."<br>";
    echo uniqid("xxx",true)."<br>";
    echo date(DATE_RSS,time()+24*60*60);*/
    
    //$d5 = Array ( Array ( [product_id" => 6 "product_title" => "VEDGZILLA" "quantity" => 1 "size" => "s" "cut" => "m" "price" => 25 "subtotal" => 25 "url" => "https://s3.amazonaws.com/product-pics/1354942544-68-spe1_mohamad_rifaii_vedgzilla_ikimuk_620x500.jpg" "artist_name" => "Mohamad Rifaii" ) , Array ( "product_id" => 6, "product_title" => "VEDGZILLA", "quantity" => 1, "size" => "xxl","cut" => "w", "price" => 25 ,"subtotal" => 25, "url" => "https://s3.amazonaws.com/product-pics/1354942544-68-spe1_mohamad_rifaii_vedgzilla_ikimuk_620x500.jpg", "artist_name" => "Mohamad Rifaii" ) );
    //$d5["["["[]= 
    $log = new KLogger($_SERVER["DOCUMENT_ROOT"], KLogger::INFO);
    $log->logFatal('Oh dear.');
?>
