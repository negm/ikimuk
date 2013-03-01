<?php
/*
 * View the cart content and remove/update items
 */
include $_SERVER["DOCUMENT_ROOT"] . "/class/class.product.php";
include $_SERVER["DOCUMENT_ROOT"] . "/inc/KLogger.php";
session_start();
$selected = Array ("unselected","unselected","unselected","unselected","selected" );
if (!isset($_SESSION["cart"]) || $_SESSION["cart"] == null) {
    $cart = null;
    $item_count = 0;
} else {
    //print_r($_SESSION["cart"]);
    validate_cart_items();
    $cart = $_SESSION["cart"];
    $item_count = $_SESSION["item_count"];
    $subtotal = $_SESSION["subtotal"];
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

$pagetitle = "ikimuk | Cart";
include $_SERVER["DOCUMENT_ROOT"] . "/block/header.php";
include $_SERVER["DOCUMENT_ROOT"] . "/block/top_area.php";
?>
<div class="body">

    <!--start of body content-->
    <div class="body_content">
<?php $NUM_OF_ITEM = $item_count; ?>
        
          <div class="links_section">
                        <div class="links_content">
                            <div class="link_deactive"><a class="link_deactive" href="/index.php">ikimuk</a></div>
                            <div class="link_deactive">/</div>
                            <div class="link_active">
                                <a href="#">Cart</a>
                            </div>
                        </div>
                    </div>
      
        <!--start of Cart section content-->
        <div class="cart_section_content">
            <div class="cart_section_header">items in your cart</div>

        <?php if ($NUM_OF_ITEM != 0) { ?>
                <div class="cart_table">
                        <!--Start of cart table header-->
                        
                            <div class="cart_header">
                                <div class="cart_preview">Preview</div>
                                <div class="cart_title">Title</div>
                                <div class="cart_description">Description</div>
                                <div class="cart_price">Price</div>
                                <div class="cart_quantity">Quantity</div>
                                <div class="cart_total">Total</div>
                            </div>
                        
                        <!--End of cart table header-->

                        <!--Start of cart table body-->
                            <div class="cart_body_content">

    <?php foreach ($cart as $key => $cart_item) { ?>

                                <!--Start of cart section section-->
                                <div class="cart_entry">

                                    <!--Start of cart entry content-->
                                    <div class="cart_entry_content">

                                        <!--Avatar section-->
                                        <div class="cart_entry_avatar">
                                            <img src="<?php echo $cart_item["url"]; ?>"/>
                                        </div>

                                        <!--Title section-->
                                        <div class="cart_entry_title">
                                            <div class="cart_title_content">
					    <div class="cart_entry_name"><?php echo $cart_item["product_title"]; ?></div>
					    <div class="cart_entry_author">by <?php echo $cart_item["artist_name"]; ?></div></div>
                                            <div class="cart_remove">
                                                <input type="hidden" name="product_id" id="product_id" value="<?php echo $cart_item["product_id"]; ?>">
                                                <input type="hidden" name="size" id="size" value="<?php echo $cart_item["size"]; ?>">
                                                <input type="hidden" name="cut" id="cut" value="<?php echo $cart_item["cut"]; ?>">
                                                <a href="#">Remove</a></div>
                                        </div>


                                        <!--Description section-->
                                        <div class="cart_entry_description">
                                          <div class="entry_description_type">  <?php echo $cart_item["cut"]."";
                                              echo "&nbsp;(".$cart_item["size"].")"; ?> </div>
                                          <div class="entry_description_details"> 
						<div class="flags_container"><div class="flag_container flag_<?php echo $settings->goals_colors[$cart_item['goal']-1]; ?>" style="background-image:none"><div class="flag_medal"></div></div></div>
						<div class="entry_goal_text">Goal <?php echo $cart_item["goal"]; ?> Perks</div>
                                          </div>  
                                        </div>


                                        <!--Price section-->
                                        <div class="cart_entry_price">
                                            <input type="hidden" name="price" value="<?php echo $cart_item["price"]; ?>"/>
                                            $<span><?php echo number_format($cart_item["price"], 2); ?></span>
                                        </div>


                                        <!--Quantity section-->
                                        <div class="cart_entry_quantity">
                                            <div class="item_quantity">
                                                <input type="text" name="item_quantity" value="<?php echo $cart_item["quantity"]; ?>"/>
                                                <input type="hidden" name="product_id" id="product_id" value="<?php echo $cart_item["product_id"]; ?>">
                                                <input type="hidden" name="size" id="size" value="<?php echo $cart_item["size"]; ?>">
                                                <input type="hidden" name="cut" id="cut" value="<?php echo $cart_item["cut"]; ?>">
                                            </div>
                                            <div class="item_update">
                                                Update 
                                            </div>
                                        </div>


                                        <!--Total section-->
                                        <div class="cart_entry_total">
                                            <input type="hidden" name="cart_total" value="<?php echo $cart_item["subtotal"]; ?>"/>
                                            $<span class="cart_total"><?php echo number_format($cart_item["subtotal"], 2); ?></span>
                                        </div>


                                    </div>
                                    <!--End of cart entry content-->


                                </div>
                                <!--End of cart section section-->

    <?php } ?>
                        </div>
                        <!--End of cart table header-->
                    </div>
                



                <!--start of cart bottom-->
                <div class="cart_bottom">


                   <div class="cart_panel">


                                <div class="cart_panel_label">
                                    <div class="panel_label_container">
                                        <span> FAQ (Frequently Asked Question)</span>
                                    </div>
                                </div>

                                <!--Start of Cart Panel Content-->
                                <div class="cart_panel_content">

                                    <div class="faq_panel_link">
                                        <div class="faq_icon">&nbsp;</div> 
                                        <div class="faq_link">
                                            <a href="/faq.php?#sec1" target="_blank">How does it work?</a>
                                        </div>
                                    </div>

                                    <div class="faq_panel_link">
                                        <div class="faq_icon">&nbsp;</div>
                                        <div class="faq_link">
                                            <a href="/faq.php?#sec2" target="_blank">How do I pay?</a>
                                        </div>
                                    </div>

                                    <div class="faq_panel_link">
                                        <div class="faq_icon">&nbsp;</div>
                                        <div class="faq_link">
                                            <a href="/faq.php?#sec3" target="_blank">Did you ever imagine that this project would be so successful ?</a>
                                        </div>
                                    </div>
                                </div>
                                <!--End of Cart Panel Content-->
                   </div>

                    <!--start of cart payment-->
                    <div class="cart_payment">

                        <!--Subtotal section-->
                        <div class="line_payment subtotal">
                            <span class="type">Subtotal:</span>
                            <span id="subtotal_text" class="payment_subtotal">$<?php echo number_format($_SESSION["subtotal"], 2); ?></span>
                            <input id="checkout_subtotal" type="hidden" name="payment_subtotal" value="<?php echo number_format($_SESSION["subtotal"], 2); ?>"/>
                        </div>

                        <!--shipment section-->
                        <div class="line_payment shipment">
                            <span class="type">Shipping Estimate:</span>
                            <span id="shipping_text" class="payment_shipment"><?php echo number_format($_SESSION["delivery_charge"], 2); ?></span>
                            <input id="checkout_shipping" type="hidden" name="payment_shipment" value="<?php echo number_format($_SESSION["delivery_charge"], 2); ?>"/>
                        </div>
                        <!--total section-->
                        <div class="line_total">
                            <span class="type">Estimate Total:</span>
                            <span id="total_text" class="payment_total">$<?php echo number_format($_SESSION["subtotal"]+$_SESSION["delivery_charge"], 2); ?></span>
                            <input id="checkout_total" type="hidden" name="payment_total" value="0"/>
                        </div>
                        <!--Start payment section-->
                        <div class="payment_process">
                            <div class="payment_type">
                                <div class="payment_visa"><img src="/images/ikimuk_visa.png"/></div>
                                <div class="payment_master"><img src="/images/ikimuk_master.png"/></div>
                            </div>
                            <div class="payment_checkout">
    <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) { ?>
                                    <input type="button" name="checkout" value="CHECKOUT">
    <?php } else { ?>
                                    <a href="#login" data-toggle="modal" style="text-decoration: none"><div class="fake_button">Checkout</div></a>
    <?php } ?>
                            </div>
                        </div>
                        <!--End of payment section section-->
                    </div>
                    <!--End of cart payment-->
                </div>
                <!--End of cart bottom-->
<?php
} //end if num diffrent of 0
else {//show the empty cart
    ?>
                <div class="no_item_cart">
                    <span>No Items found in your shopping cart!</span>
                </div>
            <?php } ?>
        </div>       
        <!--end of Cart section content-->

    </div>
    <!--end of body content-->

</div>
<!--End of body class-->
<?php include $_SERVER["DOCUMENT_ROOT"] . "/block/footer.php"; ?>                                