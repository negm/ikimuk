<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

/* retrieve cache as array, false on error */
function cache_retrieve($id,$time){
$cf = 'cache/'.dechex(crc32($id)).'.cache';
if (file_exists($cf) && (time()-$time <= filemtime($cf))){
$c = @file_get_contents($cf);
if(!$c){return false;}
if(substr($c,0,5)=='json:'){
return json_decode(substr($c,5),true);
}else{
return substr($c,5);
}
}else{
return false;
}
}

/* query if cache is still available */
function cache_query($id,$time){
$cf = 'cache/'.dechex(crc32($id)).'.cache';
if (file_exists($cf) && (time()-$time <= filemtime($cf))){
return true;
}else{
return false;
}
}

/* Clearout all old caches */
function cache_clearout($time){
$files = glob('cache/*.cache');
foreach($files as $cf){if (file_exists($cf) && (time()-$time > filemtime($cf))){@unlink($cf);}}
}

/* Clearout specific cache */
function cache_del($id){
$cf = 'cache/'.dechex(crc32($id)).'.cache';
return @unlink($cf);
}


/* save data to cache, false on error*/
function cache_save($id,$data){
$cf = 'cache/'.dechex(crc32($id)).'.cache';
if(is_array($data)){
$c = 'json:'.json_encode($data);
}else{
$c = 'strn:'.$data;
}
$r = @file_put_contents($cf,$c);
if($r){return true;}
return false;
}

?>