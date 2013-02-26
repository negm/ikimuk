<?php

/*
 * This is the page to handle the pre-order process
 * State the different verification cases here
 * 
 */
//include $_SERVER["DOCUMENT_ROOT"].'/block/logged_in.php';
if (!isset($_SESSION))
{
    session_start ();
}
require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.product.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/class/class.artist.php';
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php");
$countries = new ip2nationcountries();
$countries->select_all_countries();
$countries_array = array();
while ($country = mysqli_fetch_object($countries->database->result))
{
$countries_array[]=$country;
}
$product = new product();
$settings = new settings();
$artist = new artist();
if (!isset($_GET["product_id"]))
{
 //header("Location: /index.php");
}
else
{
$design_id = $_GET["product_id"];
//if(!isset($_SESSION['size']) )
{
//header("Location: /design/$design_id");
}
if(!isset($_SESSION['sms_code']) )
{$_SESSION['sms_code'] = substr(number_format(time() * rand(),0,'',''),0,4);}

$product->select($design_id);
if(!$product->id)
   header("Location: /index.php"); 
else
{
$pagetitle = "ikimuk: ".$product->title;
$primary = $product->image;
   
    $artist->select($product->artist_id);
    if ($artist->id)
    {
        $regex = '/(?<!href=["\'])http:\/\//';
        $website_label = preg_replace($regex,'',$artist->website);
    }
    else
        $artist = null;
  }
}
include_once $_SERVER["DOCUMENT_ROOT"]."/block/header.php";
 echo '<meta property="og:title" content="'.$product->title.'" />';
    echo '<meta property="og:image" content="'.$product->image.'" />';
    echo '<meta property="fb:app_id" content="'.$settings->app_id.'" />';
    echo '<meta property="og:url" content="'.$settings->root.'design/'.$design_id.'/'.$product->title.'" />';
include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";

?>

            <div class="body">
  <form id="preoder" action="/payment.php?action=preorder" method="post">
      <input name="preorder_summary" type="hidden" value="" id="preorder_summary">
      <input type="hidden" value="<?php echo $product->image;?>" id="product_image">
      <input type="hidden" name="product_id" value="<?php echo $product->id;?>" id="product_id">
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

                        <!--Start of contact info-->      
                        <div class="std_block choose_t_shirt">

                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">1. Choose T-Shirt</span>
                                </div>
                            </div>

                            <!--Start of body block-->
                            <div class="std_block_body">
                                <div class="t_shirt_info">
                                    <img id="product_image"src="<?php echo $product->image;?>"/>
                                    <span class="t_shirt_error"></span>
                                </div>

                                <div class="t_shirt_option">
                                    <div class="order_description">
                                        <?php echo $product->title ;?>
                                    </div>
                                    <div class="order_author">
                                        by <?php echo $product->artist_name;?>
                                    </div>
                                    <!--Start Of Cart Body--> 
                                    <div class="cart_body">

                                        <!--Start Of Cart Selection--> 
                                        <div class="cart_size_selection">

                                            <!--Start Of Male Part--> 
                                            <div class="size_selection_header">GUY's - $<?php echo number_format($product->price,2);?> 
                                                <span class="order_size_info"><a href="#">Size Chart</a></span>
                                            </div>

                                            <div class="selection_container male_part">

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="S"/>
                                                    <div>S</div>
                                                </div>

                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="M"/>
                                                    <div>M</div>
                                                </div>

                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="L"/>
                                                    <div>L</div>
                                                </div>


                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="XL"/>
                                                    <div>XL</div>
                                                </div>

                                                <div class="empty_space"></div>
                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="XXL"/>
                                                    <div>XXL</div>
                                                </div>
                                            </div>
                                            <!--End Of Male Part--> 

                                            <!--Start Of Female Part--> 
                                            <div class="size_selection_header">GIRL's - $<?php echo number_format($product->price,2);?>
                                                <span class="order_size_info">
                                                    <a href="#">Size Chart</a>
                                                </span>
                                            </div>
                                            <div class="selection_container female_part">

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="S"/>
                                                    <div>S</div>
                                                </div>

                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="M"/>
                                                    <div>M</div>
                                                </div>

                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="L"/>
                                                    <div>L</div>
                                                </div>

                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="XL"/>
                                                    <div>XL</div>
                                                </div>

                                                <div class="empty_space"></div>

                                                <div class="cart_no">
                                                    <input type="hidden" name="size" value="XXL"/>
                                                    <div>XXL</div>
                                                </div>

                                            </div>
                                            <!--End Of Male Part--> 



                                        </div>
                                        <!--End Of cart size selection--> 



                                    </div>
                                    <!--End Of Cart Selection--> 


                                    <div class="order_submit">
                                        <input type="hidden" name="category" value=""/>
                                        <input type="hidden" name="size" value=""/>
                                        <div class="add_t_shirt">
                                            <span >ADD T-SHIRT</span>
                                        </div>

                                        <div class="add_plus">
                                            <span>+</span>
                                        </div>



                                    </div>


                                </div>



                            </div>
                            <!--End of Block Body-->

                        </div>
                        <!--End of Contact info-->           






























                        <!--Start Of Shipping info Section-->
                        <div class="std_block shipping_info">
                           
                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">2. Shipping info</span>
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
                                    <span class="label_title">3. Contact info</span>
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
                                                            echo '<option selected="selected" value="' . $country->phone_code . '">' . $country->country_name . " ".$country->phone_code.'</option>';
                                                        else
                                                            echo '<option value="' . $country->phone_code . '">' . $country->country_name." ".$country->phone_code. '</option>';
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
                                    <span class="label_title">4. shipping method</span>
                                </div>
                            </div>

                            <!-- Start of block body-->
                            <div class="std_block_body">

                                <div class="shipping_content">
                                    <div class="radio_holder"><input type="radio" checked="checked"/></div>
                                    <div class="ads_text">  Aramex International Priority(2-5 business days)</div>
                                    <div class="logo_holder"><img src="/img/ikimuk_aramex.png"/></div>
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
                                    <div class="payment_visa"><img src="/img/ikimuk_visa.png"></div>
                                    <div class="payment_master"><img src="/img/ikimuk_master.png"></div>
                                </div>
                                <div class="payment_checkout">
                                   
                                
                                <input type="submit" value="PLACE ORDER" name="place">
                                    
                                        <div class="gateway">
                                            <div class="gateway_icon"><img src="/img/ikimuk_lock.png"/>  </div>  
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

  <!--Start of pre-order summary-->
                        <div class="pre_order_summary">
                      
                        <div class="std_block">

                            <div class="std_block_label">
                                <div class="label_box">
                                    <span class="label_title">pre-order Summary</span>
                                </div>
                            </div>



                            <!--Start of block body-->
                            <div class="std_block_body">
                                <div class="line_link">
                                    <div class="link_holder">
                                        <!--  <a href="#">Edit</a>-->
                                    </div>
                                </div>
                                <div class="preorder_content">
                                </div>
                                <?php // for ($i = 0; $i < 2; $i++) { ?>
                                <!--     <div class="pre_order">
 
                                         <div class="pre_order_avatar">
                                             <img src="images/avatar_60.png"/>
                                         </div>    
 
                                         <div class="pre_order_description">
                                             Guys 2 XL
                                         </div>
 
                                         <div class="pre_order_option">
                                             <img src="img/ikimuk_snowstar_blue.png"/>
                                         </div>
 
                                         <div class="pre_order_price">
                                             $ 25.00
                                         </div>
 
                                         <div class="pre_order_close">
 
                                         </div>
                                     </div>  -->
                                <?php //} ?>
                                <div class="empty_pre_order">
                                    <span class="empty_message">PLEASE CHOOSE AT LEAST ONE T-SHIRT</span>
                                </div>

                                <div class="summary_sub_total">

                                    <div class="sub_total_line">
                                        <input type="hidden" name="sub_total" value=""/>
                                        <span class="line_type">Subtotal :</span>
                                        <span  class="line_value">--</span>
                                    </div>

                                    <div class="aramex_line">
                                        <input type="hidden" name="tax" value="11"/>
                                        <span class="line_type">Aramex Shipping</span>
                                        <span  class="line_value">--</span>
                                    </div>

                                </div>


                                <div class="summary_total">
                                    <div class="sub_total_line">
                                        <input type="hidden" name="total" value=""/>
                                        <span class="line_type">Total :</span>
                                        <span  class="line_value">--</span>
                                    </div>
                                </div>

                            </div>
                            <!--End of block body-->
                        </div>
                      

                        <div class="block_information">
                         Your Pre-Order is only confirmed if this T-Shirt Design gets 50 
                         Pre-Orders by Sat 02 Feb 11:59PM (GMT+2). We will reserve (pre-authorize)
                         the amount on your card. If there aren’t enough pre-orders, the pre-authorization
                         on your card gets cancelled—so make sure you share the deal with your friends!
                        </div>
                        </div>
                          <!--End of pre-order summary-->
                        

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



<?php
include $_SERVER["DOCUMENT_ROOT"].'/block/footer.php';
?>