<?php
if(!isset($_SESSION))
    session_start();

if (!isset($_POST["size"]))
    echo 'error';
else
{
   $_SESSION["size"] =$_POST["size"];
   echo 'done';
}
?>
