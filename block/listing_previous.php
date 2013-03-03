<?php

/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "ikimuk together we create!";
require_once $_SERVER["DOCUMENT_ROOT"]."/class/class.product.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/class/class.image.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/class/class.competition.php";
require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
$product = new product();
$competition = new competition();
$competition->getCompletedCompetitions();
$image= new image();
$settings = new settings();
$current_page = 1;
if(isset($_GET["page"])){
  $current_page = $_GET["page"];
}
$previous_count = 0;
$selection_options = "";
$current_competition = null;
while($row_competition= mysqli_fetch_object($competition->database->result)){
  $previous_count++;
  $selection_options .= "<option value='" . $previous_count  . "'";
  if($previous_count == $current_page){
    $selection_options .= " selected='selected'";
    $current_competition = $row_competition;
  }
  $selection_options .= ">" . $row_competition->title . "</option>";
}
$competition = $current_competition;
$product->selectByCompetition($competition->id);
include $_SERVER["DOCUMENT_ROOT"]."/block/header.php";
include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";
unset($_SESSION["size"]);
?>
<div class="body">
 <div class="body_content">
  <!-- Start of selector area -->
  <div class="page_header" style="margin-bottom:10px;">
    Previous Competitions
  </div>
  <div class="selectors_box">
    <div class="full_line" style="float:left">
    <div class="select_box round_corners combo">
     <div class="select_previous">
  <?php echo $competition->title; ?>
     </div>
  <select name="previous" data-animation-"true" data-trigger="focus" class="country_list hidden_input" onchange="location.href='/competitions.php?page='+$(this).find('option:selected').val();" style="margin-bottom:0px;">
  <?php echo $selection_options; ?>
     </select>  
    </div>
  </div>
    <div id="pagination_box" >
      
    </div>                 
                </div>
                 <div style="clear:both"></div>
                 <!--Start of competition section-->
                 <div class="competition_section previous_competition_section">
                     
                     
                      <div class="competition_header">
                            competition no
  <span class="competition_no"><?php echo $competition->competition_order;?></span>
  (ended on
   <span class="competition_end_date"><?php $date = new DateTime($competition->end_date); echo $date->format('d/m/Y'); ?></span>)
                        </div>
                     
                     
                     <div class="competition_banner">
                         <!--to be removed and replaced with image-->
                         <img  class="" src="<?php echo $competition->competition_header?>" alt="competition header ikimuk"/>
                     </div>
                     <!--Start of competition container-->
                       <div class="competition_container">

<?php
$count = 0;
while($row= mysqli_fetch_assoc($product->database->result))
{   $daysLeft = floor((strtotime($row["end_date"]) - time())/(60*60*24));?>
    <div class="entry" style="<?php if($count%3!=0) echo "margin-left:35px;"; else echo "margin-left:0px;"; $count++;?>">
    <!--Used to set a link when clicking-->
    <input type="hidden" name="user_id" value="/design/<?php echo $row["id"]. "/" . str_replace(".","",str_replace(" ","-",trim($row["title"]))); ?>"/>
    <div class="entry_transparent">
         <div class="entry_order_now">
          VIEW DESIGN
         </div>
    </div>
    
    <div class="entry_avatar">
        <a href="/design/<?php echo $row["id"]."/".str_replace(".","",str_replace("","-",trim($row["title"]))); ?>">
                                 <img src="<?php echo $row["url"];?>"/>
        </a>
    </div>
        
             <div class="entry_control">

                                        <div class="entry_description">
                                            <?php echo $row["title"];?>
                                        </div>

                                        <div class="entry_author">
                                            by
                                            <span class="entry_author_name"><?php echo $row["name"];?></span>
                                        </div>

                                    </div>
                                    <!--End of entry control-->            
                             </div>
                             <!--End of entry-->
 <?php } ?>
</div>
                     <!--End of competition container-->

</div>
<!--End of competition section-->
                 
                 
                 
             </div> </div>

<?php 
$inpage_script = '
<script type="text/javascript" src="/js/jquery.simplePagination.js"></script>
<script type="text/javascript">
  $(function() {
      $("#pagination_box").pagination({
        pages:'.$previous_count.',
	  hrefTextPrefix: "/competitions.php?page=",
	  currentPage:'.$current_page.',
	  cssStyle: "light-theme" ,prevText: "<", nextText: ">"

	    });
    });
</script>';
?>