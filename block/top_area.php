<?php
if (isset($_GET["logout"]) && $_GET["logout"] == 1) {
//User clicked logout button, distroy all session variables.
    $_GET["logout"] = 0;
    unset($_GET["logout"]);
    $_SESSION['logged_in'] = false;
    unset($_COOKIE);
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
}
?>
</head>
<body>
    <div id="fb-root"></div>
    <div class="header">
        <!--Start of Upper Section--> 
        <div class="upper">

            <!--Start of Upper Content-->
            <div class="upper_content">


                <div class="header_title">
                    <?php echo _txt("intldelivery");
                        
                    if (!isset($_SESSION["country_name"]) || strlen($_SESSION["country_name"]) < 2)
                       if(isset($ip2c)) {echo $ip2c->get_country_name();}
                       else {echo "your country"; }
                    else echo $_SESSION["country_name"]; 
                    ?> 
                </div>


                <div class="control_section">                          
                    <?php
                    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                        ?>
                        <a href="#join" data-toggle="modal"><div class="header_button joinus"><?php echo _txt("signup");?></div></a>
		        <a href="#login" data-toggle="modal"><div class="header_button login"><?php echo _txt("login");?></div></a>

    <?php
} else {
    ?>

                        <div class="login_menu">
                            <div class="login_header">
                                <div class="login_arrow"></div>
                                <div class="login_name"><?php echo _txt("hello").", <b> ".$_SESSION["user_name"]; ?></b></div>
                                

                            </div> 
                            <div class="menu_drop">
                                <!--<div class="menu_entry"><span class="profile">My Profile</span></div>-->
                                <div class="menu_h_line"></div>
                                <a href="/index.php?logout=1"><div class="menu_entry"><span class="logout"><?php echo _txt("logout");?></span></div></a>
                            </div>
                        </div>

<?php } ?>

                </div>


            </div>
            <!--End of Upper content-->
        </div>
        <!--End of Upper section-->           

        <!--Start of lower section-->
        <div class="lower">

            <!--Start of upper content-->
            <div class="lower_content">

                <div class="logo_content">
                    <div class="lower_logo">
                        <a href="/">  <img src="/img/ikimuk_logo_beta.png" alt="logo"/> </a>
                    </div>
                </div>


                <div class="lower_menu">

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="<?php echo $selected[0];?>"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/"/>

                        <div class="menu_content">

                            <div class="preorder">
                                <div class="line"> <?php echo _txt("order");?> </div>
                                <div class="line"> <?php echo _txt("tshirt");?></div>
                            </div>

                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="<?php echo $selected[1];?>"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/submit-intro.php"/>

                        <div class="menu_content">
                            <div class="submit">
                                <div class="line"> <?php echo _txt("submit");?> </div>
                                <div class="line"> <?php echo _txt("yourdesign");?> </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="<?php echo $selected[2];?>"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/competitions.php"/>

                        <div class="menu_content">
                            <div class="previous">
                                <div class="line"> <?php echo _txt("past");?> </div>
                                <div class="line"> <?php echo _txt("competitions");?> </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->
                     <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="<?php echo $selected[3];?>"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/interviews.php"/>

                        <div class="menu_content">
                            <div class="about">
                                <div class="line"> <?php echo _txt("artist");?> </div>
                                <div class="line"> <?php echo _txt("interviews");?> </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->
                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="<?php echo $selected[4];?>"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/about-us.php"/>

                        <div class="menu_content">
                            <div class="about">
                                <div class="line"> <?php echo _txt("about");?> </div>
                                <div class="line"> ikimuk </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>
                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="<?php echo $selected[5];?>"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/cart.php"/>

                        <div class="menu_content">
                            <div class="cart">
                                <div class="line"><img src="/img/ikimuk_cart.png"/></div>
                                <div class="line"> <?php echo _txt("cart");?>(<span id="cart_sum" class="cart_sum">
                                    <?php if (!isset($_SESSION["item_count"])) echo '0'; else echo $_SESSION["item_count"]; ?>
                                    </span>)</div>

                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->
                    <div class="pipe"></div>

                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
<?php include $_SERVER["DOCUMENT_ROOT"] . "/block/authentication.php"; ?>

