<?php
/*
 * Final step befor submitting the order to Audi
 * The user would enter Shipping details and that would determine
 * the shupping cost
 */
$selected = Array ("unselected","unselected","unselected","unselected","selected" );
include ($_SERVER["DOCUMENT_ROOT"] . "/block/logged_in.php");
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php");
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php");
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";

$countries = new ip2nationcountries();
$countries->select_all_countries();
$countries_array = array();
while ($country = mysqli_fetch_object($countries->database->result))
{
$countries_array[]=$country;
}
$product = new product();
if (isset($_SESSION["cart"])) {
    validate_cart_items();
    $cart = $_SESSION["cart"];
} else {
    $_SESSION["cart"] = null;
    header("Location: /cart.php");
}

function validate_cart_items() {
  global $settings;  
  $subtotal = 0;
    $item_count = 0;
    $cart = $_SESSION["cart"];
    $product = new product();
    foreach ($cart as $key => $cart_item) {
        $product->id = $cart_item["product_id"];
        $product->select($cart_item["product_id"]);
        if ($product->id == null) {
            unset($cart[$key]);
        } else {
            $cart[$key]["price"] = $product->price;
	    $discount = 1 - $settings->goals_discount[$cart_item["goal"] - 1];
            $cart[$key]["subtotal"] = $product->price * $cart_item["quantity"] * $discount;
            $cart[$key]["product_title"] = $product->title;
            $cart[$key]["url"] = $product->image;
            $cart[$key]["artist_name"] = $product->artist_name;
            $subtotal += $cart[$key]["subtotal"];
            $item_count += 1;
        }
    }
    $_SESSION["cart"] = $cart;
    $_SESSION["item_count"] = $item_count;
    $_SESSION["subtotal"] = $subtotal;
}

$pagetitle = "ikimuk| Checkout";
if(isset($_GET["payment"]) and $_GET["payment"] == "failure"){
        ?>
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = '6008382078716';
fb_param.value = '0.00';
(function(){
var fpw = document.createElement('script');
fpw.async = true;
fpw.src = '//connect.facebook.net/en_US/fp.js';
var ref = document.getElementsByTagName('script')[0];
ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6008382078716&amp;value=0" /></noscript>
    <?php }
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
?>
<div class="body">
    <form action="/payment.php?action=order" method="post">
                <div class="body_content">
                    <div class="links_section">
                        <div class="links_content">
                            <div class="link_deactive"><a class="link_deactive" href="/index.php"> ikimuk</a></div>
                            <div class="link_deactive">/</div>
                            <div class="link_deactive"><a class="link_deactive" href="/cart.php"> Cart</a></div>
                            <div class="link_deactive">/</div>
                            <div class="link_active">
                                <a href="#">Checkout</a>
                            </div>
                        </div>
                    </div>

<?php 
  if(isset($_GET["payment"]) and $_GET["payment"] == "failure"){
    echo "<div class='alert alert-error'> <button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Oops!</strong> Something went wrong. ";
    if(isset($_GET["error"])){
      echo $_GET["error"];
    }else{
      echo "Your payment could not be processed, please try again later.";
    }
    echo "</div>";
  }
?>


                    <div class="checkout_column_left">

                        <!--Start Of Shipping info Section-->
                        <div class="std_block shipping_info">
  <div id="error_link"></div>
                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">1. Shipping</span>
                                </div>
                            </div>



                            <!--Start Of Block Body-->
                            <div class="std_block_body">


                                <div class="line_element">
                                    <div class="full_line">
                                        <div class="line_header">Country</div>

                                        <div class="line_input round_corners combo">
                                            <div class="select_country">
                                                <?php if (isset($_SESSION["country_name"])) echo $_SESSION["country_name"]; else echo 'Select Country';?>
                                            </div>
                                            <select name="country" class="country_list hidden_input" data-animation="true" data-trigger="focus">
                                               <?php
                                                   foreach($countries_array as $key=>$country)
                                                        if($country->country_name == $_SESSION["country_name"])
                                                    echo '<option selected="selected" value="' . $country->country_code . '" data-delivery="'.$country->delivery_charge.'">' . $country->country_name . '</option>';
                                                        else
                                                            echo '<option value="' . $country->country_code . '" data-delivery="'.$country->delivery_charge. '">' . $country->country_name . '</option>';
                                                 ?>
                                            </select>
                                        </div>

                                        <div class="line_error"></div>
                                    </div>
                                </div>

                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">First Name</div>
                                        <div class="line_input">
                                            <input id="first_name" type="text" name="first_name" data-animation="true" data-trigger="focus" value="<?php if(isset($_SESSION["form_first_name"])) echo $_SESSION["form_first_name"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div>
                                    </div>

                                    <div class="half_line marginl20">
                                        <div class="line_header">Last Name</div>
                                        <div class="line_input">
                                            <input id="last_name" type="text" name="last_name" data-animation="true" data-trigger="focus"value="<?php if(isset($_SESSION["form_last_name"])) echo $_SESSION["form_last_name"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div> 
                                    </div>

                                </div>



                                <div class="line_element">

                                    <div class="full_line">
                                        <div class="line_header">Address</div>
                                        <div class="line_input">
                                            <input type="text" name="address" data-content="Please write down your full address so we can deliver to your doorstep." data-animation="true" data-trigger="focus" value="<?php if(isset($_SESSION["form_address"])) echo $_SESSION["form_address"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div>
                                    </div>

                                </div>


                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">City</div>
                                        <div class="line_input">
                                            <input type="text" name="city" data-animation="true" data-trigger="focus" value="<?php if(isset($_SESSION["form_city"])) echo $_SESSION["form_city"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div>
                                    </div>


                                    <div class="half_line marginl20">
                                        <div class="line_header">State, Region or Province</div>
                                        <div class="line_input">
                                            <input type="text" name="region" data-animation="true" data-trigger="focus" value="<?php if(isset($_SESSION["form_region"])) echo $_SESSION["form_region"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div> 
                                    </div>

                                </div>

                                <div class="line_element">
                                    <div class="half_line">
                                        <div class="line_header">Zip Code (if Applicable)</div>
                                        <div class="line_input">
                                            <input type="text" name="zip" data-animation="true" data-trigger="focus" value="<?php if(isset($_SESSION["form_zip"])) echo $_SESSION["form_zip"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div>
                                    </div> 
                                </div>

                            </div>
                            <!--End Of Block Body-->
                        </div>
                        <!--End Of Shipping info Section-->




                        <!--Start of contact info-->      
                        <div class="std_block contact_info">

                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">2. Contact info</span>
                                </div>
                            </div>

                            <!--Start of body block-->
                            <div class="std_block_body">

                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">Country Code</div>

                                        <div class="line_input round_corners combo">

                                            <div class="country_code">
                                                <?php if (isset($_SESSION["country_name"])) echo $_SESSION["country_name"]." +".$_SESSION["phone_code"]; else echo 'Select Country Code';?>
                                            </div>

                                            <select name="code" class="code_list hidden_input" data-animation="true" data-trigger="focus">
                                                <?php
                                                    foreach($countries_array as $key=>$country)
                                                        if($country->country_name == $_SESSION["country_name"])
                                                            echo '<option selected="selected" value="' . $country->country_code . '">' . $country->country_name . " ".$country->phone_code.'</option>';
                                                        else
                                                            echo '<option value="' . $country->country_code . '">' . $country->country_name." ".$country->phone_code. '</option>';
                                                 ?>
                                            </select>
                                        </div>

                                        <div class="line_error"></div>
                                    </div>


                                    <div class="half_line marginl20">
                                        <div class="line_header">Telephone Number</div>
                                        <div class="line_input">
                                            <input type="text" name="tel" data-animation="true" data-trigger="focus" value="<?php if(isset($_SESSION["form_tel"])) echo $_SESSION["form_tel"]; ?>"/>
                                        </div>
                                        <div class="line_error"></div> 
                                    </div>

                                </div>

                            </div>
                            <!--End of Block Body-->

                        </div>
                        <!--End of Contact info-->



                        <!--Start of shipping method-->
                        <div class="std_block shipping_method">


                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">3. Delivery Type</span>
                                </div>
                            </div>

                            <!-- Start of block body-->
                            <div class="std_block_body">

                                <div class="shipping_content">
                                    <div class="radio_holder"><input type="radio" checked="checked"/></div>
                                    <div class="ads_text">  Aramex International Priority</div>
                                    <!--<div class="logo_holder"><img src="img/ikimuk_aramex.png"/></div>-->
                                    <div class="fee_content">
                                        Custom fees and additional fees may apply for international shipments. Please contact your local customs office for more information. We will start shipping your order one week after the competition has ended.
                                        It will then take (1 -5) business days to arrive to your home.
                                    </div>
                                </div>

                            </div>
                            <!--End of Block Body-->

                        </div>
                        <!--End of shipping method-->



                        <!--Start of Place Order-->
                        <div class="place_order">


                            <div class="agreement">
                                <div class="terms_conditions">
                                    <input type="checkbox" name="agree"/>I agree to ikimuk's 
                                    <a href="/terms.php" target="_blank">Terms &amp; Conditions</a>
                                </div>
                                <div class="line_error"></div>
                            </div>

                            <div class="newsletter">
                                <input type="checkbox" name="subscribe"/>Keep me in the loop, sign me up for your newsletter
                            </div>

                            <div class="proceed">

                                <div class="payment_type">
                                    <div class="payment_visa"><img src="img/ikimuk_visa.png"></div>
                                    <div class="payment_master"><img src="img/ikimuk_master.png"></div>
                                </div>
                                <div class="payment_checkout">
                                    <input type="submit" value="PLACE ORDER" name="place">
                                        <div class="gateway">
                                            <div class="gateway_icon"><img src="img/ikimuk_lock.png"/>  </div>  
                                            <div class="gateway_text">You will be redirected to Bank Audi's payment gateway </div>
                                        </div>
                                </div>

                            </div>

                        </div>
                        <!--End of Place Order-->

                    </div>
                    <!--End of Left column-->


                    <!--Start of right column-->
                    <div class="checkout_column_right">

                        <!--Start of cart summary-->
                        <div class="std_block cart_summary">

                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">Cart Summary</span>
                                </div>
                            </div>



                            <!--Start of block body-->
                            <div class="std_block_body">

                                <div class="line_link" style="margin-bottom:10px;">
                                    <div class="link_holder">
                                        <a href="/cart.php">Edit</a>
                                    </div>
                                </div>
                                <?php foreach ($cart as $key => $cart_item) { ?>
                                    <div class="cart_element">

                                        <div class="cart_element_avatar">
                                            <img src="<?php echo $cart_item["url"]; ?>"/>
                                        </div>    

                                        <div class="cart_element_description">
                                            <?php echo "<b>" . $cart_item["product_title"] . "</b><br/>" .$cart_item["quantity"] . " " . $settings->size_names[$cart_item["size"]] . " " . $cart_item["cut"]."s T-shirt " . $settings->goals_perks_add[$cart_item["goal"]-1]; ?>
                                        </div>

                                        <div class="cart_element_option cart_element_option_<?php echo $settings->goals_colors[$cart_item["goal"]-1]; ?>">
         
                                        </div>

                                        <div class="cart_element_price">
                                            $ <?php echo number_format($cart_item["price"], 2); ?>
                                        </div>

                                    </div>
                                <?php } ?>


                                <div class="summary_sub_total">
                                   
                                    <div class="sub_total_line">
                                        <span class="line_type">Subtotal :</span>
                                        <span  id="subtotal_text" class="line_value">$ <?php echo number_format($_SESSION["subtotal"],2);?></span>
                                        <input id="checkout_subtotal" type="hidden" name="checkout_subtotal" value="<?php echo $_SESSION["subtotal"];?>"/>
                                    </div>
                                       
                                    <div class="sub_total_line">
                                        <span class="line_type">Aramex Shipping</span>
                                        <span  id="shipping_text" class="line_value">$ <?php echo number_format($_SESSION["delivery_charge"],2);?></span>
                                        <input id="checkout_shipping" type="hidden" name="checkout_shipping" value="<?php echo $_SESSION["delivery_charge"];?>"/>
                                    </div>
                                       
                                </div>
                                   
                                    
                                <div class="summary_total">
                                    <div class="sub_total_line">
                                        <span class="line_type">Total :</span>
                                        <span  id="total_text" class="line_value">$ <?php echo number_format($_SESSION["subtotal"]+$_SESSION["delivery_charge"],2)?></span>
                                        <input id="checkout_total" type="hidden" name="checkout_total" value="50"/>
                                    </div>
                                </div>

                            </div>
                            <!--End of block body-->
                        </div>
                        <!--End of cart summary-->
                    </div>
                    <!--End of right colum-->

                </div>        
                <!--End Of body content-->
    </form>
            </div>
            <!--End of Body-->
<?php


$inpage_script= '<script type="text/javascript">
                $(function () {
                    $("body").popover({
                        selector: "input,select"
                    });
                    $("input, select").focusout(function(){
                        $(this).popover("hide");
                    });
        
                });
          
                
            </script>';

 include $_SERVER["DOCUMENT_ROOT"] . "/block/footer.php"; ?>