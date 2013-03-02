<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once  $_SERVER["DOCUMENT_ROOT"]."/block/logged_in_admin.php";
include "../class/class.artist.php";
include "../class/class.competition.php";
$pagetitle = "Add a design";
$artist = new artist();
$artist->selectAll();
$competition = new competition();
$competition->selectActive();
include_once '../block/header.php';
?>
<script>$(function(){
    var img_list = new Array();

var btnUpload=$('#upload_img');
var status=$('#status');
new AjaxUpload(btnUpload, {
action: 'process_upload.php',
name: 'uploadfile',
onSubmit: function(file, ext){
if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
// extension is not allowed 
status.text('Only JPG, PNG or GIF files are allowed');
return false;
}
status.text('Uploading...');
},
onComplete: function(file, response){
//On completion clear the status
status.text('');
//Add uploaded file to list
if(response != "error"){
$('<li></li>').appendTo('#files').html('<img class="img-rounded" src="'+response+'" alt="" /><br />'+file).addClass('success');
img_list.push(response);
$('#img_url').val(img_list);
uploaded = true;
//$('#upload').hide();
} else{$('<li></li>').appendTo('#files').text(file).addClass('error');}}});});
</script>
<?php include '../block/top_area.php';  ?>
<form id="addproduct" method="post" action="" class="span7">
    <p>Product title</p>
    <input type="text" id="title" name="title"/>
    <p>Price</p>
    <input type="text" id="price" name="price"/>
    <p>Description</p>
    <input type="text" id="desc" name="desc"/>
    <p>The Artist</p>
    <select name="artist" name="artist">
     <?php
     while ($row_artist = mysqli_fetch_object($artist->database->result))
     {echo '<option value="'.$row_artist->id.'">'.$row_artist->name.'</option>';}
     echo '</select>';
     echo '<p>Competition</p>';
     echo '<select name="competition" name="competition">';
     while ($row_competiton = mysqli_fetch_object($competition->database->result))
     {echo '<option value="'.$row_competiton->id.'">'.$row_competiton->title.'</option>';}
     
     ?>
    </select>
    <div id="upload_img" class="btn btn-primary"><span>Upload File<span></div><span id="status" ></span><br/>
                <input type="hidden" name="img_url" id="img_url"/>
    <input type="submit" id="addproduct" class="btn btn-success" value="Submit you design"/>
    
</form>
<div id="orderComplete" class="span5 hidden">
        <div class="preTitle span8">Preorder complete</div>
        Thank you for submitting this  design! We will update you soon.<br/>
        Until then, <a href="index.php" style="color:#44c6e3">browse our other designs</a>
        
</div>
<ul id="files" class ="span4"></ul>