<?php
/*
 * Final step befor submitting the order to Audi
 * The user would enter Shipping details and that would determine
 * the shupping cost
 */
include ($_SERVER["DOCUMENT_ROOT"] . "/block/logged_in");
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php");
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php");
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
            $cart[$key]["subtotal"] = $product->price * $cart_item["quantity"];
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
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
?>
<div class="body">
    <form action="/payment.php?action=order" method="post">
                <div class="body_content">

                    <div class="links_section">
                        <div class="links_content">
                            <div class="link_deactive">ikimuk</div>
                            <div class="link_deactive">/</div>
                            <div class="link_deactive">Cart</div>
                            <div class="link_deactive">/</div>
                            <div class="link_active">
                                <a href="#">Checkout</a>
                            </div>
                        </div>
                    </div>



                    <div class="checkout_column_left">

                        <!--Start Of Shipping info Section-->
                        <div class="std_block shipping_info">

                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">1. Shipping info</span>
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
                                            <select name="country" class="country_list hidden_input" data-content="Please Select a country" data-animation="true" data-trigger="focus">
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
                                            <input id="first_name" type="text" name="first_name" data-content="Please enter your name" data-animation="true" data-trigger="focus"/>
                                        </div>
                                        <div class="line_error"></div>
                                    </div>

                                    <div class="half_line marginl20">
                                        <div class="line_header">Last Name</div>
                                        <div class="line_input">
                                            <input id="last_name" type="text" name="last_name" data-content="Please Enter your last name" data-animation="true" data-trigger="focus"/>
                                        </div>
                                        <div class="line_error"></div> 
                                    </div>

                                </div>



                                <div class="line_element">

                                    <div class="full_line">
                                        <div class="line_header">Address</div>
                                        <div class="line_input">
                                            <input type="text" name="address" data-content="Please Enter your addres" data-animation="true" data-trigger="focus" />
                                        </div>
                                        <div class="line_error"></div>
                                    </div>

                                </div>


                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">City</div>
                                        <div class="line_input">
                                            <input type="text" name="city" data-content="Please Enter your city" data-animation="true" data-trigger="focus" />
                                        </div>
                                        <div class="line_error"></div>
                                    </div>


                                    <div class="half_line marginl20">
                                        <div class="line_header">State, Region or Province</div>
                                        <div class="line_input">
                                            <input type="text" name="region" data-content="Please Enter your region" data-animation="true" data-trigger="focus" />
                                        </div>
                                        <div class="line_error"></div> 
                                    </div>

                                </div>

                                <div class="line_element">
                                    <div class="half_line">
                                        <div class="line_header">Zip Code (if Applicable)</div>
                                        <div class="line_input">
                                            <input type="text" name="zip" data-content="Please Enter your ZIP Code" data-animation="true" data-trigger="focus"/>
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

                                            <select name="code" class="code_list hidden_input" data-content="Please Enter your country code" data-animation="true" data-trigger="focus">
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
                                            <input type="text" name="tel" data-content="Please Enter your telephone number" data-animation="true" data-trigger="focus"/>
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
                                    <span class="label_title">3. shipping method</span>
                                </div>
                            </div>

                            <!-- Start of block body-->
                            <div class="std_block_body">

                                <div class="shipping_content">
                                    <div class="radio_holder"><input type="radio" checked="checked"/></div>
                                    <div class="ads_text">  Aramex International Priority(2-5 business days)</div>
                                    <div class="logo_holder"><img src="img/ikimuk_aramex.png"/></div>
                                    <div class="fee_content">
                                        Custom fees and additional fees may apply for international shipments.
                                        Please contact your local customs office for more information.
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
                                    <input type="checkbox" name="agree"/>I agree on ikimuk's 
                                    <a href="#">Terms &amp; Conditions</a>
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

                                <div class="line_link">
                                    <div class="link_holder">
                                        <a href="#">Edit</a>
                                    </div>
                                </div>
                                <?php for ($i = 0; $i < 2; $i++) { ?>
                                    <div class="cart_element">

                                        <div class="cart_element_avatar">
                                            <img src="images/avatar_60.png"/>
                                        </div>    

                                        <div class="cart_element_description">
                                            Guys 2 XL
                                        </div>

                                        <div class="cart_element_option">
                                            <img src="img/ikimuk_snowstar_blue.png"/>
                                        </div>

                                        <div class="cart_element_price">
                                            $ 25.00
                                        </div>

                                    </div>
                                <?php } ?>


                                <div class="summary_sub_total">
                                   
                                    <div class="sub_total_line">
                                        <span class="line_type">Subtotal :</span>
                                        <span  id="subtotal" class="line_value">$ <?php echo number_format($_SESSION["subtotal"],2);?></span>
                                        <input type="hidden" name="checkout_subtotal" value="<?php echo $_SESSION["subtotal"];?>"/>
                                    </div>
                                       
                                    <div class="sub_total_line">
                                        <span class="line_type">Aramex Shipping</span>
                                        <span  class="line_value">$ <?php echo number_format($_SESSION["delivery_charge"],2);?></span>
                                        <input type="hidden" name="checkout_shipping" value="<?php echo $_SESSION["delivery_charge"];?>"/>
                                    </div>
                                       
                                </div>
                                   
                                    
                                <div class="summary_total">
                                    <div class="sub_total_line">
                                        <span class="line_type">Total :</span>
                                        <span  class="line_value">$ <?php echo number_format($_SESSION["subtotal"]+$_SESSION["delivery_charge"],2)?></span>
                                        <input type="hidden" name="checkout_total" value="50"/>
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



            <script type="text/javascript">
                $(function () {
                    $('body').popover({
                        selector: 'input,select'
                    });
                    $("input, select").focusout(function(){
                        $(this).popover("hide");
                    });
        
                });
          
                
            </script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/block/footer.php"; ?>