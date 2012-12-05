<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$ul_id='breadcrumbs-two';
$bc=explode("/",$_SERVER["PHP_SELF"]);
echo '<ul id="'.$ul_id.'" class=""><li><a href="index.php">Home</a></li>';
while(list($key,$val)=each($bc)){
 $dir='';
 if($key > 1){
  $n=1;
  while($n < $key){
   $dir.='/'.$bc[$n];
   $val=$bc[$n];
   $n++;
  }
  if($key < count($bc)-1) echo '<li><a href="'.$dir.'">'.$val.'</a></li>';
 }
}
if (strpos($pagetitle,"Awesome t-shirts designed by you!")=== FALSE)
{echo '<li ><a href="" class="current">'.$pagetitle.'</a></li>'; //To be defined in each page
}
echo '</ul>';

?>
