<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!isset($_SESSION))
{
    session_start ();
}
if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]||!isset($_SESSION["role"]) || $_SESSION["role"] != 1)
{
    header("Location: ../index.php");
}
?>