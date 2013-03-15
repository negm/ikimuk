<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$en = array(
    "hello" => "Hello",
    "login" => "Login",
    "signup" => "Join Us",
    "logout" => "Log Out",
    "intldelivery"=>"We deliver internationally including ",
    "cart" => "Cart",
    "about" => "About",
    "past" => "Past",
    "competitions" => "Competitions",
    "submit" => "Submit",
    "yourdesign" => "Your Design",
    "artist" => "Artist",
    "interviews" => "Interviews",
    "login" => "Login",
    "order" => "Order",
    "tshirt" => "A T-Shirt",
    "competitionno" => "Competition No",
    "endson" => "Ends ON",
    "ordersremain" => "orders until this T-shirt gets printed",
    "hooray" => "Hooray!",
    "tobeprinted" => "This design is getting printed",
    "ordernow" => "Order now",
    //END home page without the footer
    "newsletter" => "Newsletter",
    "subscribe" => "subscribe",
    "newslettermsg" => "join for updates",
    "aboutus" => "About us",
    "faq" => "faq",
    "subterms" => "SUBMISSION TERMS",
    "subscribe" => "subscribe",
    "subscribe" => "subscribe",
    //signup & login
    "email" => "E-mail",
    "password" => "Password",
    "cpassword" => "Confirm Password",
    "name" => "Name",
    "termsmsg" => 'By clicking "JOIN US", you agree to our',
    "termsanch" => 'Terms & Conditions',
    "alreadymember"=> "Already a member?",
    "notamember"=>"Not registered?",
    "or" => "OR",
    "connectingtofb" => "Connecting to Facebook",
    "forgotpassword" => "Forgot Your Password?",
    "connectwithfacebook" => "Connect With Facebook",
    //design page
    "addtocart"=>"ADD TO CART",
    "daysleft" =>"Days left",
    "showsupport" => "Show your support",
    "guy"=>"GUY",
    "girl"=>"GIRL",
    "goals_texts" => array("Once this T-shirt reaches 35 orders it will get printed. You will receive your T-shirt, stickers and a certificate of awesomeness",
				"If you order this T-shirt when it has between 35 to 75 orders, you will receive your T-shirt and stickers ",
				"If you order this T-shirt after it has reached 75 orders, you will just receive your T-shirt",
				)
);
$ar = array(
     "hello" => "اهلا",
    "login" => "الدخول",
    "signup" => "التسجيل",
    "logout" => "الخروج",
    "intldelivery"=>"خدمة التوصيل الدولي متوفرة في ",
    "cart" => "المشتريات",
    "about" => "عن",
    "past" => "تاريخ",
    "competitions" => "المسابقات",
    "submit" => "شارك",
    "yourdesign" => "بتصميم",
    "order" => "اشتري",
    "tshirt" => "تيشرت",
    "artist" => "مقابلات",
    "interviews" => "المصممين",
    "competitionno" => "المسابقة رقم ",
    "endson" => "تنتهي يوم",
    "ordersremain" => "حتى تتم طباعة التصميم",
    "hooray" => "نعم",
    "tobeprinted" => "سوف تتم طباعة هذا التصميم اطلب الان",
    "ordernow" => "اشتري الان",
    //END home page without the footer
    
    "newsletter" => "القائمة البريدية",
    "subscribe" => "اشترك",
    "newslettermsg" => "لمعرفة اخر الاخبار و التحديثات",
    //signup
    "email" => "البريد الالكتروني",
    "password" => "كلمة السر",
    "cpassword" => "تأكيد كلمة السر",
    "name" => "الاسم",
    "termsmsg" => 'بالضغط على "التسجيل" فانت توافق على ',
    "termsanch" => 'القواعد و الشروط',
    "alreadymember"=> "مسجل من قبل؟",
    "notamember"=> "غير مسجل من قبل",
    "or" => "او",
    "connectingtofb" => "يتم الاتصال ب Facebook",
    "forgotpassword" => "نسيت كلمة المرور؟",
    "connectwithfacebook" => "التسجيل بواسطة Facebook",
    //design page
    "addtocart"=>"أضف للمشتريات",
    "daysleft" =>"ايام متبقية",
    "showsupport" => "قول رأيك",
    "guy"=>"رجال",
    "girl"=>"بنات"
);
function _txt($l)
{
    global $en, $ar;
    if(isset($_SESSION["lang"]) && $_SESSION["lang"] == "ar")
    return $ar[$l];
    else
        return $en[$l];
}
?>
