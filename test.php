<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER["DOCUMENT_ROOT"]."/block/enums.php";
$text = "23,xxl,m|23,xxl,m";
$details = ( explode("|", $text));
foreach ($details as $key=>$detail )
    {
        $details[$key] = explode(",", $detail);
    }
    print_r($details);
    $size = "s";
    echo $size_enum[$size];
    
    
    echo "<br>".uniqid()."<br>";
    echo uniqid("xxx")."<br>";
    echo uniqid(true)."<br>";
    echo uniqid("xxx",true)."<br>";
    echo date(DATE_RSS,time()+24*60*60);
?>
