<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include "class/class.user.php";
$user = new user();
$user->getPreorderHistory(1);
$row = mysqli_fetch_assoc($user->database->result);
echo "result";
print_r($row);
?>
