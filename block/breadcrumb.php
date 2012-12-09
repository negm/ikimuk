<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$ul_id='';
$bc=explode("/",$_SERVER["PHP_SELF"]);
echo '<div class="container" ><ul id="'.$ul_id.'" class="brdc"><li class="span1"><a href="index.php">Home</a><span class="divid">/</span></li>';
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
{echo '<li class="tyellow active span3"> '.$pagetitle.'</li>'; //To be defined in each page
}
echo '</ul></div>';

?>
