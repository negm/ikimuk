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
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div class="header">
        <!--Start of Upper Section--> 
        <div class="upper">

            <!--Start of Upper Content-->
            <div class="upper_content">


                <div class="header_title">
                    We deliver internationally including <?php if (!isset($_SESSION["country_name"]) || strlen($_SESSION["country_name"]) < 2) echo "your country"; else echo $_SESSION["country_name"]; ?> 
                </div>


                <div class="control_section">                          
                    <?php
                    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                        ?>
                        <a href="#login" data-toggle="modal"><div class="header_button login">Login</div></a>
                        <a href="#join" data-toggle="modal"><div class="header_button joinus">Join Us</div></a>
    <?php
} else {
    ?>

                        <div class="login_menu">
                            <div class="login_header">
                                <div class="login_arrow"></div>
                                <div class="login_name"><?php echo $_SESSION["user_name"]; ?></div>
                                <div class="login_avatar"><img src="/images/avatar_30.png"/></div>
                            </div> 
                            <div class="menu_drop">
                                <div class="empty_space"></div>
                                <div class="menu_entry"><span class="profile">My Profile</span></div>
                                <div class="menu_h_line"></div>
                                <a href="/index.php?logout=1"><div class="menu_entry"><span class="logout">Log Out</span></div></a>
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
                        <a href="/">  <img src="img/ikimuk_logo_beta.png" alt="logo"/> </a>
                    </div>
                </div>


                <div class="lower_menu">

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="selected"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/"/>

                        <div class="menu_content">

                            <div class="preorder">
                                <div class="line"> PRE-ORDER </div>
                                <div class="line"> A T-SHIRT</div>
                            </div>

                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="unselected"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/submit-intro.php"/>

                        <div class="menu_content">
                            <div class="submit">
                                <div class="line"> SUBMIT </div>
                                <div class="line"> YOUR DESIGN </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="unselected"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/competitions.php"/>

                        <div class="menu_content">
                            <div class="previous">
                                <div class="line"> PREVIOUS </div>
                                <div class="line"> COMPETITION </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>

                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="unselected"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="about/index.php"/>

                        <div class="menu_content">
                            <div class="about">
                                <div class="line"> ABOUT </div>
                                <div class="line"> ikimuk </div>
                            </div>
                        </div>
                    </div>
                    <!--End of menu element-->

                    <div class="pipe"></div>
                    <!--Start of Menu element-->
                    <div class="menu_element">
                        <!--This to Determine if the user is in the current menu link,selected=yes - unselected=no-->
                        <input type="hidden" name="flag" value="unselected"/>
                        <!--This to set a link to go for when the user click-->
                        <input type="hidden" name="link" value="/cart.php"/>

                        <div class="menu_content">
                            <div class="cart">
                                <div class="line"><img src="img/ikimuk_cart.png"/></div>
                                <div class="line"> CART(<span class="cart_sum">0</span>)</div>

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

