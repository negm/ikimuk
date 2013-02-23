<?php
/*
 * Final step befor submitting the order to Audi
 * The user would enter Shipping details and that would determine
 * the shupping cost
 */
session_start();
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php");
include ($_SERVER["DOCUMENT_ROOT"] . "/class/class.ip2nationcountries.php");
$countries = new ip2nationcountries();
$countries->select_all_countries();
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
    <div class="body_content checkout_section">

        <!--Start of Cart section-->
<?php include $_SERVER["DOCUMENT_ROOT"] . "/block/cart_count.php"; ?>
        <!--end of Cart section-->
        <div class="checkout_section_content">
            <div class="checkout_section_header">checkout</div>
            <form method="post" action="/payment.php">
                <div class="checkout_column_left">
                    <div class="shipping_info">
                        <div class="std_block">
                            <div class="std_block_header">
                                <div class="header_content">1. Shipping Info</div>
                            </div>
                            <div class="std_block_body">
                                <div class="line_element">
                                    <div class="full_line">
                                        <div class="line_header">Country</div>
                                        <div class="line_input">
                                            <select name="country">
<?php
while ($country = mysqli_fetch_object($countries->database->result))
                                                        if($country->country_name == $_SESSION["country_name"])
                                                    echo '<option  value="' . $country->country_code . '" selected="selected">' . $country->country_name . '</option>';
                                                        else
                                                            echo '<option  value="' . $country->country_code . '">' . $country->country_name . '</option>';
?>
                                           <!--<input class='round_corners" type="text"/>-->
                                            </select>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                </div>
                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">First Name</div>
                                        <div class="line_input">
                                            <input class="round_corners" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                    <div class="half_line" style="margin-left:20px;">
                                        <div class="line_header">Last Name</div>
                                        <div class="line_input">
                                            <input class="round_corners" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                </div>
                                <div class="line_element">
                                    <div class="full_line">
                                        <div class="line_header">Address</div>
                                        <div class="line_input">
                                            <input class="round_corners" name="address" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                </div>
                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">City</div>
                                        <div class="line_input">
                                            <input class="round_corners" name="city" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>


                                    <div class="half_line" style="margin-left:20px;">
                                        <div class="line_header">State, Region or Province</div>
                                        <div class="line_input">
                                            <input class="round_corners" name="region" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                </div>

                                <div class="line_element">

                                    <div class="half_line">
                                        <div class="line_header">Zip Code (if Applicable)</div>
                                        <div class="line_input">
                                            <input class="round_corners" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contact_info">
                        <div class="std_block">
                            <div class="std_block_header">
                                <div class="header_content">2. Contact Info</div>
                            </div>
                            <div class="std_block_body">
                                <div class="line_element">
                                    <div class="half_line">
                                        <div class="line_header">Country Code</div>
                                        <div class="line_input">
                                            <input class="round_corners" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                    <div class="half_line" style="margin-left:20px;">
                                        <div class="line_header">Telephone Number</div>
                                        <div class="line_input">
                                            <input class="round_corners" name="phone" type="text"/>
                                        </div>
                                        <div class="line_error">Error Message</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shipping_method">
                        <div class="std_block">
                            <div class="std_block_header">
                                <div class="header_content">3. Shipping Method</div>
                            </div>
                            <div class="std_block_body">
                                <div style="margin-left: 20px;margin-top:20px;width:460px;">
                                    <div style="float:left;padding-top:10px;"><input type="radio" checked="checked"/></div>
                                    <div style="float:left;padding-top:10px;">Aramex International Priority(2-5 business days)</div>
                                    <div style="float:right;"><img src="images/ikimuk_aramex.png"/></div>
                                    <div style="clear:both;font-size: 13px;color:#CCCCCC;padding-top:10px;">
                                        Custom fees and additional fees may apply for international shipments.
                                        Please contact your local customs office for more information.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="place_order">
                        <div class="agreement">
                            <div><input type="checkbox"/>I agree on ikimuk's <a href="#">Terms & Conditions</a></div>
                            <div style="font-size: 12px;color:red;padding-left:20px;">Error message</div>
                        </div>
                        <div class="newsletter">
                            <input type="checkbox" name="newsletter"/>Keep me in the loop, sign me up for your newsletter
                        </div>
                        <div class="proceed">
                            <div class="payment_type">
                                <div class="payment_visa"><img src="images/ikimuk_visa.png"></div>
                                <div class="payment_master"><img src="images/ikimuk_master.png"></div>
                            </div>
                            <div class="payment_checkout">
                                <input type="submit" name="place_order" value="PLACE ORDER"/>

                                <div class="gateway">
                                    You will be redirected to Bank Audi's payment gateway
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="checkout_column_right">
                <div class="cart_summary">
                    <div class="std_block block_expandable">
                        <div class="std_block_header">
                            <div class="header_content">3. Shipping Method <div style="float:right;margin-right:20px;font-size:14px;"><a href="/cart.php">Edit</a></div></div>
                        </div>
                        <div class="std_block_body summary_content">
<?php foreach ($cart as $key => $cart_item) { ?>
                                <div class="summary_line">
                                    <div class="summary_avatar"><img src="<?php echo $cart_item["url"]; ?>"/></div>
                                    <div class="summary_description">
    <?php echo $cart_item["product_title"]; ?>
                                        <br/>(<?php echo $cart_item["quantity"]; ?>) * (<?php echo $cart_item["size"]; ?>)
                                    </div>
                                    <div class="summary_price">
                                        $ <?php echo number_format($cart_item["subtotal"], 2); ?>
                                    </div>
                                    <div></div>
                                </div>
<?php } ?>
                            <div class="summary_sub_total">
                                <div class="sub_total_line">
                                    <span class="sub_type">Subtotal:</span>
                                    <span class="sub_value">$ <?php echo number_format($_SESSION["subtotal"], 2); ?></span>
                                </div>
                                <div class="sub_total_line">
                                    <span class="sub_type">Aramex Shipping:</span>
                                    <span class="sub_value">$ <?php echo number_format(doubleval($_SESSION["delivery_charge"]), 2); ?></span>
                                </div>
                            </div>
                            <div class="summary_total">
                                <div class="total_line">
                                    <span class="sub_type">Aramex Shipping:</span>
                                    <span class="sub_value">$ <?php echo number_format(doubleval($_SESSION["delivery_charge"]), 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>


<?php include $_SERVER["DOCUMENT_ROOT"] . "/block/footer.php"; ?>