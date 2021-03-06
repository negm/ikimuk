<?php
/*
 * Home page listing
 * 1- Retrieve desings listed in the current active competition 
 * 2- for each design get the pictures (primary and rollover)
 */
$pagetitle = "ikimuk together we create!";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.image.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.competition.php";
require_once $_SERVER["DOCUMENT_ROOT"] . '/class/settings.php';
$product = new product();
$product->CurrentCompetitionDesigns();
$competition = new competition();
$competition->selectCurrentCompetition();
$image = new image();
$settings = new settings();
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";
if (isset($_GET["payment"]) and $_GET["payment"] == "success") {
?>
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = '6008380954116';
fb_param.value = '0.00';
(function(){
  var fpw = document.createElement('script');
  fpw.async = true;
  fpw.src = '//connect.facebook.net/en_US/fp.js';
  var ref = document.getElementsByTagName('script')[0];
  ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6008380954116&amp;value=0" /></noscript>
<?php }  ?>
<meta property="og:title" content="ikimuk: cool T-shirt fashion" />
<meta property="og:type" content="Website" />
<meta property="og:url" content="http://ikimuk.com" />
<meta property="og:image" content="http://ikimuk.com/images/ikimuk_logo_beta_hover.png" />
<meta property="og:site_name" content="ikimuk" />
<?php
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
?>
<div xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns="http://www.w3.org/1999/xhtml"
     xmlns:foaf="http://xmlns.com/foaf/0.1/"
     xmlns:gr="http://purl.org/goodrelations/v1#"
     xmlns:vcard="http://www.w3.org/2006/vcard/ns#">
 
  <div about="#company" typeof="gr:BusinessEntity">
    <div property="gr:legalName" content="ikimuk."></div>
    <div rel="vcard:adr">
      <div typeof="vcard:Address">
        <div property="vcard:country-name" content="Lebanon"></div>
        <div property="vcard:locality" content="Beirut"></div>
      </div>
    </div>
    <div property="vcard:tel" content="+961 767 87606"></div>
    <div rel="foaf:depiction" resource="http://ikimuk.com/images/ikimuk_logo_beta_hover.png">
    </div>
    <div rel="foaf:page" resource=""></div>
  </div>
</div>
<div class="body">
    <div class="body_content">
        <?php
        if (isset($_GET["payment"]) and $_GET["payment"] == "success") {
            echo "<div class='alert alert-success'> <button type='button' class='close' data-dismiss='alert'>&times;</button>".  _txt("payment_success")."</div>";
        }
        if (isset($_GET["submit"]) and $_GET["submit"] == "success") {
            echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button>"._txt("submit_msg")."</div>";
        }
        if (isset($_GET["reset"]) and $_GET["reset"] == "email") {
            echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button>". _txt("pwd_reset_email")."</div>";
        }
        if (isset($_GET["reset"]) and $_GET["reset"] == "success") {
            echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button>". _txt("pwd_reset_complete")."</div>";
        }
        ?>
        <!--Start of Slider section-->
        <div class="slider"> 
            <div id="myCarousel" class="carousel slide">
                <!-- Carousel items -->
                <div class="carousel-inner">

                    <!--<div class="item"><img src="/images/slide_1.jpg"/></div>-->
                    <div class="active item"><img src="/images/slide_2.jpg"/></div>
                    <div class="item"><img src="/images/slide_3.jpg"/></div>

                </div>
                <!-- Carousel nav -->
                <a class="carousel-control-iki left" href="#myCarousel" data-slide="prev">
                    <img src="/img/ikimuk_slider_left.png"/>
                </a>
                <a class="carousel-control-iki right" href="#myCarousel" data-slide="next">
                    <img src="/img/ikimuk_slider_right.png"/>
                </a>
            </div>
        </div>
        <!--End of Slider section-->




        <!--Start of competition section-->
        <div class="competition_section">


            <div class="competition_header">
                <?php echo _txt("competitionno");?>
                <span class="competition_no"><?php echo $competition->competition_order; ?></span>
                (<?php echo _txt("endson");?>
                <span class="competition_end_date"><?php $date = new DateTime($competition->end_date);
        echo $date->format('d/m/Y'); ?></span>)
            </div>


            <div class="competition_banner">
                <!--to be removed and replaced with image-->
                <img  class="" src="<?php echo $competition->competition_header ?>" alt="competition header ikimuk"/>
            </div>
            <!--Start of competition container-->
            <div class="competition_container">

                <?php
                $count = 0;
                while ($row = mysqli_fetch_assoc($product->database->result)) {
                    $daysLeft = floor((strtotime($row["end_date"]) - time()) / (60 * 60 * 24));
                    ?>
                    <div class="entry" style="<?php if ($count % 3 == 0) echo "margin-left:10px;"; $count++; ?>">
                        <!--Used to set a link when clicking-->
                        <input type="hidden" name="user_id" value="/design/<?php echo $row["id"] . "/" . str_replace(".", "", str_replace(" ", "-", trim($row["title"]))); ?>"/>
                        <div class="entry_transparent">
                            <div class="entry_order_now">
                                <?php echo _txt("ordernow");?>
                            </div>
                        </div>
                        <div class="entry_option">
                            <div class="option_price">
                                <span class="entry_item_price"><?php echo $row["price"]; ?></span>
                                <span class="entry_dollar_sign">$</span>
                            </div>
<!--                            <div class="option_male"></div>
                            <div class="option_female"></div> -->
                        </div>

                        <div class="entry_avatar">
                            <a href="/design/<?php echo $row["id"] . "/" . str_replace(".","",str_replace(" ", "-", trim($row["title"]))); ?>">
                                <img src="<?php echo $row["url"]; ?>"/>
                            </a>
                        </div>

                        <div class="entry_control">

                            <div class="entry_description">
    <?php echo $row["title"]; ?>
                            </div>

                            <div class="entry_author">
                                by
                                <span class="entry_author_name"><?php echo urldecode($row["name"]); ?></span>
                            </div>

                            <div class="entry_progressbar">

                                <div class="progress">
                                    <div class="bar progress_cyan" style="width:<?php echo $row["preorders"] * (100 / $settings->goals[0]); ?>%"></div>
                                </div>

                                <div class="entry_remaining">
    <?php if ($row["preorders"] >= $settings->goals[0]) { ?>
                                        <span class="entry_remaining_hilight"><?php echo _txt("hooray");?></span>
                                        <span class="entry_remaining_value"> 
                                            <?php echo _txt("tobeprinted");?>
                                        </span>
                                        <?php } else { ?>
                                        <span class="entry_remaining_value"> 
                                        <?php echo $settings->goals[0] - $row["preorders"]; ?> <?php echo _txt("ordersremain");?>
                                        </span>
    <?php } ?>
                                </div>

                            </div>


                            <div class="progress_status">
                                    <?php if ($row["preorders"] < $settings->goals[0]) { ?>
                                    <span class="entry_progress_percentage">
        <?php echo $row["preorders"]; ?>
                                        /<?php echo $settings->goals[0]; ?>
                                    </span>
                                <?php } else { ?>
                                    <img src="img/ikimuk_blue_wow.png"/>
    <?php } ?>
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