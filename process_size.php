<?php
if(!isset($_SESSION))
    session_start();
$error="";
$size="";
$cut="";
if (!isset($_POST["size"]))
    echo 'error';
else
{
    $size_input = explode("_", $_POST["size"]);
    if(count($size_input) < 2)
    {
        $error = "incorrect input";
    }
    else
    {
        $cut = $size_input[0];
        $size = $size_input[1];
    }
   //$_SESSION["size"] =$_POST["size"];
   //echo 'done';
    $json_response = json_encode(array ("error"=> $error, "size"=>$size, "cut"=>$cut));
    //header('Content-Type: application/json');
    print_r ($json_response);
}
?>
