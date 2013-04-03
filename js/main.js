//woopera
function woopraReady(tracker){tracker.setDomain('ikimuk.com');tracker.setIdleTimeout(1800000);tracker.track();return false;}(function() {var wsc = document.createElement('script');wsc.src = document.location.protocol+'//static.woopra.com/js/woopra.js';wsc.type = 'text/javascript';wsc.async = true;var ssc = document.getElementsByTagName('script')[0];ssc.parentNode.insertBefore(wsc, ssc);})();
//////////////////GA
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-36448861-1']);
_gaq.push(['_setDomainName', 'ikimuk.com']);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_trackPageview']);

(function() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
})();
var target="";
var user_name="";
function CallAfterLogin(){
    $('#loginModal').modal('hide');
    FB.login(function(response) {		
        if (response.status === "connected") 
        {
            LoadingAnimate(); //Animate login
            FB.api('/me', function(data) {
                if(data.email == null)
                {
                    //Facbeook user email is empty, you can check something like this.
                    ResetAnimate();

                }else{
                    AjaxResponse();
                }
            });
        }
    });
}
function LoadingAnimate() //Show loading Image
{
    $("#join-button").attr("disabled", "disabled");
    $("#login-button").attr("disabled", "disabled");
    $(".loader_box").show();
}
 
function ResetAnimate() //Reset User button
{
    $("#LoginButton").show(); //Show login button
    $("#results").html(''); //reset element html
}
function AjaxResponse()
{
    var myData = 'connect=1&action=fb_login';
    jQuery.ajax({
        type: "POST",
        url: "/process_user.php",
        dataType:"html",
        data:myData,
        cache: false,
        success:function(response){
            if(target.length > 1)
                window.location.href = target;
            else
                location.reload();
        },
        error:function (xhr, ajaxOptions, thrownError){
        //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
        }
    });
}

//from ali
$(document).ready(function(){
    // Login + Join
    // 
    //////////////////////////Join US part///////////////////////////////////////////////
     //Check if the email is valid    
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    }
    
    //reset inputs     
    function reset_member_login(){
        $(".login").find(".line_error").text("");
        $(".login").find("input[type='text']").css("border-color","#CCCCCC");
        $(".login").find("input[type='password']").css("border-color","#CCCCCC"); 
    }
 
    //close link, mouse enter,out and click
    $(".member_header .member_close a").mouseenter(function(){
        $(this).find("img").attr("src","/images/ikimuk_close.png"); 
    });
    $(".member_header .member_close a").mouseleave(function(){
        $(this).find("img").attr("src","/images/ikimuk_disabled_close.png"); 
    });
    $(".member_header .member_close a").click(function(){
        reset_member_login();
        $(".login").find("input[type='text']").val("");
    });
    //Check if input is empty and display an error message    
    function check_input_member(variable,message)
    {
        if(variable.val().length==0)
        { 
            variable.parent().parent().find(".line_error").text(message);         
            variable.css("border-color","#EF2C21");
            return 1; //is empty  
        }
        return 0;
    }
    function reset_member_join(){
        $(".join").find(".line_error").text("");
        $(".join").find("input[type='text']").css("border-color","#CCCCCC");
        $(".join").find("input[type='password']").css("border-color","#CCCCCC");  
    }
    //reset password
    
    $("#change_password_reset").click(function(){ 
    $("#change_password_reset").attr("disabled", "disabled");
    var flag=0;
    var password=$("#password_reset");
    var confirm_password=$("#password_reset_confirm");
            if(check_input_member(password,"Password is empty"))flag++;
        else
        {   
            if(password.val().length<6)  {//check the password strength
                flag++;
                password.parent().parent().find(".line_error").text("Password should be at least 6 character");
            }
            else
            if(!(password.val()===confirm_password.val()))//check password combination
            {
                flag++;
                confirm_password.parent().parent().find(".line_error").text("Password & confirmation don't match");
            }
                   
        }
        if(flag==0){
         
            var myData = "action=change_password_reset&password="+password.val()+"&password_confirmation="+confirm_password.val();
            jQuery.ajax({
                type: "POST",
                url: "/process_user.php",
                dataType:"json",
                data:myData,
                cache: false,
                success:function(response){
                    $("#change_password_reset").removeAttr("disabled");
                    if ((response.error).length > 5)
                    {
                        $('#reset_error').text(response.error).parent().show();
                        //$('#error').html(response.error).show();
                        return false;
                    }
                    else {
                           window.location.href = "/index.php?reset=success";
                    }
                    return false;
    
    
                },
                error:function (xhr, ajaxOptions, thrownError){
                    //alert(thrownError)
                //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
                }
            });
        }

});
    //forgot password 
    $("#reset_password_submit").click(function(){ 
    $("#reset_password_submit").attr("disabled", "disabled");
    var flag=0;
    var email=$("#email_reset");
    if(check_input_member(email,"Email field is empty"))flag++;
        else
        if(!isValidEmailAddress(email.val())){//check if email is valid
            flag++;
            email.parent().parent().find(".line_error").text("Invalid Email");
        }
        if(flag==0){
            var myData = "action=reset_password&email="+email.val();
            jQuery.ajax({
                type: "POST",
                url: "/process_user.php",
                dataType:"json",
                data:myData,
                cache: false,
                success:function(response){
                    $("#reset_password_submit").removeAttr("disabled");
                    if ((response.error).length > 5)
                    {
                        $('#reset_error').text(response.error).parent().show();
                        //$('#error').html(response.error).show();
                        return false;
                    }
                    else {
                           window.location.href = "/index.php?reset=email";
                    }
                    return false;
    
    
                },
                error:function (xhr, ajaxOptions, thrownError){
                    //alert(thrownError)
                //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
                }
            });
        }

});
    //Join Button clicked
    $(".join .member_join input[name='join']").click(function(){ 
        reset_member_join();//reset fields
        //Get objects
        var full_name=$(".join").find("input[name='full_name']");
        var email=$(".join").find("input[name='email']");
        var password=$(".join").find("input[name='password']");
        var confirm_password=$(".join").find("input[name='confirm_password']");
        var flag=0;
        flag+=check_input_member(full_name,"Name field is empty");  
        if(check_input_member(email,"Email field is empty"))flag++;
        else
        if(!isValidEmailAddress(email.val())){//check if email is valid
            flag++;
            email.parent().parent().find(".line_error").text("Invalid Email");
        }
        if(check_input_member(password,"Password is empty"))flag++;
        else
        {   
            if(password.val().length<6)  {//check the password strength
                flag++;
                password.parent().parent().find(".line_error").text("Password should be at least 6 character");
            }
            else
            if(!(password.val()===confirm_password.val()))//check password combination
            {
                flag++;
                confirm_password.parent().parent().find(".line_error").text("Passwords don't match");
            }
                   
        }
        if(flag==0){
         
            var myData = 'action=signup&full_name='+full_name.val()+"&email="+email.val()+"&password="
            +password.val()+"&confirm_password="+confirm_password.val();
            jQuery.ajax({
                type: "POST",
                url: "/process_user.php",
                dataType:"json",
                data:myData,
                cache: false,
                success:function(response){
                    if ((response.error).length >5)
                    {
                        $('#join_error').text(response.error).parent().show();
                        return false;
                    }
                    else {
                        if(target.length > 1)
                            window.location.href = target;
                        else
                            location.reload();
                    }
                    return false;
    
    
                },
                error:function (xhr, ajaxOptions, thrownError){
                //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
                }
            });
        }
            
    });
    
    
    ////////////Login
    //
    //
    $(".login .member_join input[name='join']").click(function(){ 
        reset_member_login();//reset all fields
        var email=$(".login").find("input[name='email']");//get email object
        var password=$(".login").find("input[name='password']");//get password object
        
        var flag=0;
        if(check_input_member(email,"Email field is empty"))flag++;//email field is empty
        else
        if(!isValidEmailAddress(email.val())){//check if valid email
            flag++;
            email.parent().parent().find(".line_error").text("Email not valid");
        }


        if(check_input_member(password,"Password field is empty"))flag++;//check if password field is empty
        else
        if(password.val().length<6){//password count less than six
            flag++;
            password.parent().parent().find(".line_error").text("Password must be at least 6 character");
        }
          
          
          
        if(flag==0){
            var myData = 'action=login&email='+email.val()+"&password="+password.val();
            jQuery.ajax({
                type: "POST",
                url: "/process_user.php",
                dataType:"json",
                data:myData,
                cache: false,
                success:function(response){
                    if ((response.error).length >5)
                    {
                        email.parent().parent().find(".line_error").text("Incorrect Email/password");
                        return false;
                    }
                    if(target.length > 1)
                        window.location.href = target;
                    else
                        location.reload();
    
                },
                error:function (xhr, ajaxOptions, thrownError){
                //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
                }
            });
        }
            
    });
    
    ////////////////////////////Cart shop section////////////////////////


    $(".std_block_body .cart_entry:last").css("border",0);//remove the border from the last cart entry
    refresh_cart_prices();//refresh all prices at startup







    //when the checkout button clicked
    $(".payment_process .payment_checkout input[name='checkout']").click(function(){
        //alert("checkout clicked");
        window.location.href = '/checkout.php';
        refresh_cart_prices();
    });



    //When the remove link clicked
    $(".cart_remove a").click(function(){
        var myData = "action=remove&product_id="+$(this).parent().children('#product_id').val()+"&size="+$(this).parent().children('#size').val()+"&cut="+$(this).parent().children('#cut').val();
        jQuery.ajax({
            type: "POST",
            url: "/process_cart.php",
            dataType:"json",
            data:myData,
            cache: false,
            success:function(response){
                if (response.item_count == 0)
                {
                    location.reload();
                }
                else
                {
                    $('#cart_sum').html(response.item_count);
                }
    
            },
            error:function (xhr, ajaxOptions, thrownError){
            //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
            }
        });
        $(this).parent().parent().parent().parent().remove();//remove the element
        $(".std_block_body .cart_entry:last").css("border",0);//remove border from last element
        refresh_cart_prices();//refresh the prices
        return false;
    });


    //Update button clicked
    $(".cart_entry_quantity .item_update").click(function(){
  
        var unit_price=parseFloat($(this).parent().parent().find(".cart_entry_price").find("input[name='price']").val());//Get Item Price
        var qty=parseInt($(this).parent().find(".item_quantity").find("input[name='item_quantity']").val());//Get item quantity inserted
 
        if(isNaN(qty)||qty<0){
            qty=0;//assign 0 for non-number or less than zero quantity.
        }
        var id = $(this).parent().children('.item_quantity').children('#product_id').val();
        var size = $(this).parent().children('.item_quantity').children('#size').val();
        var cut = $(this).parent().children('.item_quantity').children('#cut').val();
        var myData = "action=update&product_id="+id+"&size="+size+"&cut="+cut+"&quantity="+qty
        //alert(myData);
        jQuery.ajax({
            type: "POST",
            url: "/process_cart.php",
            dataType:"json",
            data:myData,
            cache: false,
            success:function(response){
                 if (response.item_count == 0 || qty == 0)
                {
                    location.reload();
                }
                else
                {
                    $('#cart_sum').html(response.item_count);
                }
            },
            error:function (xhr, ajaxOptions, thrownError){
            //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
            }
        });
        $(this).parent().find(".item_quantity").find("input[name='item_quantity']").val(qty);//Update the item quantity.
        var line_total=unit_price*qty;
        $(this).parent().parent().find(".cart_entry_total").find(".cart_total").text(""+line_total.toFixed(2));//Update the text of line payment
        $(this).parent().parent().find(".cart_entry_total").find("input[name='cart_total']").val(line_total);//Update the line payment
        refresh_cart_prices();

    });
    
    
    
    $("#add_to_cart").click(function(){
        _gaq.push(['_trackEvent', 'Product', 'add_to_cart', $("#product_id").val()]);
        $("#add_to_cart").attr("disabled", "disabled");
        $('#size_error').hide();
        var cut=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(cut=="") $('#size_error').show();//check if the user didn't click on any cell
        else{  
            var myData = 'action=add&cut='+cut+"&size="+size+"&product_id="+$("#product_id").val();
            //alert(myData)
            jQuery.ajax({
                type: "POST",
                url: "/process_cart.php",
                dataType:"json",
                data:myData,
                cache: false,
                success:function(response){
                    //var json = $.parseJSON(response);
                    $("#add_to_cart").removeAttr("disabled");
                    if ((response.error).length >3)
                    {
                        //alert(response.error)
                        return false;
                    }
                    else 
                    {
                        //update number of items next to cart
                        $('#cart_sum').html(response.item_count);
                        $("#item_added").modal('show');
                        setTimeout(function(){$("#item_added").modal('hide')},3000);
                        return false;
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
                }
            });
    
        }
  
    });

    /////////////////////////////////////Submit Design Section///////////////////////////////////////////////
    $(".submit_personal_design input[name=submit_design]").click(function(){
 
        reset_fields();//reset fields from error
	$("#error").hide();
        //Get need variable and values
        var competition_type=$(".type_body").find(".type_select").find("input:radio[name='competition_type']:checked").val(); 
        var title=$(".info_body").find(".line_input").find("input[name='design_title']");
        var details=$(".info_body").find(".line_input").find("textarea[name='design_details']").val();
        var city=$(".self_info_body").find(".line_input").find("input[name='city']");
        var website_blog_1=$(".self_info_body").find(".line_input").find("input[name='website_blog_1']").val();
        var website_blog_2=$(".self_info_body").find(".line_input").find("input[name='website_blog_2']").val();
        var agree=$(".agreement_submit_section").find(".agreement").find(".terms_conditions").find("input[name='agree']").is(":checked");
        var newsletter=$(".agreement_submit_section").find(".newsletter").find("input[name='subscribe']").is(":checked");
        var images = $("#img_url");
        //check if there are any error and diplay it
        var flag=0;
        flag+=check_input(title,"please enter a title",1);
        flag+=check_input(city,"please enter your city",1);
        flag+=check_input(images,"Please Upload at least one image",1);
        if(!agree)
        {
            flag++;
            $(this).parent().parent().find(".line_error").text("Please Accept Our terms and conditions");
        }
         if(flag==0){
            var params = 'action=add_submission&design_title='+ encodeURIComponent(title.val())+'&img_url='+
            encodeURIComponent(images.val())+'&comment='+ encodeURIComponent(details)
            +'&newsletter='+newsletter+"&city="+ encodeURIComponent(city.val())
            +"&website_blog_1="+ encodeURIComponent(website_blog_1)+"&website_blog_2="+ 
            encodeURIComponent(website_blog_2)+"&competition="+competition_type;
            params = encodeURI(params);
            //alert(params);
            $.ajax({  
                type: "POST",  
                url: "/process_submit.php",  
                data: params,  
                success: function(response) {  
                    //alert(response)
                    if (response != 'shit')
                    {
                        location.href = "/?submit=success";
                        return false;
                    }
                    else
                    { 
                        $("#error").show();
                        return false;
                    }
                 }
             });  
         }else{
	     location.href = "#error_link";
	 }
    });

    //validate input, variable,message displayed in case of error, 0=input combo box,1=input text
    function check_input(variable,message,flag)
    {
    
        if($.trim(variable.val()).length==0)
        { 
            variable.parent().parent().find(".line_error").text(message);
            if(flag){   
                variable.css("border-color","#EF2C21");
            }
            else{ 
                variable.parent().css("border-color","#EF2C21");
            }
            return 1;
        }
        return 0;
    }


/* 
 * main JS file
 */
var target="";
var user_name="";
//Facebook login

/*-------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------*/    
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=700');
    if (window.focus) {
        newwindow.focus()
        }
    return false;
}

function moveOnMax(field,nextFieldID){
    if(field.value.length >= field.maxLength){
        document.getElementById(nextFieldID).focus();
    }
}

///////////////////////////////////////upload & submit
var preorder_list = new Array();
////////////////preorder
var size="";
// add product
    var options = {
        url:        'process-addproduct.php', 
        success:    function(response) {
            if (response === 'done')
            {
                $("#addproduct").fadeOut(1000);
                $(".userInfo").fadeOut(1000);
                $("#orderComplete").removeClass("hidden");
                return false;
            }
            else {
                alert("something went wrong"+ response);
            }
        },
        beforeSubmit: function(arr, $form, options) { 
            // The array of form data takes the following form: 
            // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ] 
            var valid = true;
            $("*").removeClass("alertr");
            if($('#title').val().length <1){           
                $("#title_g").removeClass("hidden").addClass("alertr").focus();
                valid = false;
            }
/*            if (!uploaded)
            {
                $("#img_g").removeClass("hidden").addClass("alertr").focus();
                valid = false;
            }*/
            return valid;
        // return false to cancel submit                  
        }
    }; 

    // pass options to ajaxForm 
    $('#addproduct').ajaxForm(options);
    //
    //
    //all.js
   
    //////////////////////////////////Header+Footer///////////////////////////////////
    //Round links Mouse Over/Leave
    $(".round_links .round_link").mouseenter(function(){
        var src=$(this).find("img").attr("src");
        src=src.replace('.png', '_hover.png');
        $(this).find("img").attr("src",src);
        
    });
    $(".round_links .round_link").mouseleave(function(){
        var src=$(this).find("img").attr("src");
        src=src.replace('_hover.png','.png');
        $(this).find("img").attr("src",src);
        
    });
    
        //login menu mouse out
    $(".login_menu").mouseleave(function(){
        $(".menu_drop").css("display","none");  
    });
    //menu entry clicked
    $(".menu_entry").click(function(){ 
        var flag=$(this).find("span").hasClass("logout");//check if the logout is clicked
        if(flag){
            $(".control_section .login_menu").css("display","none");//hide the login menu
            $(".control_section .login_menu .login_header").css("display","none");//hide the login header
            $(".control_section .header_button").css("display","block");//show the header  buttons
        }
        //else
            //alert("profile clicked"); 
    });
    //Subscribe link clicked
    /*$(".subscribe_link .input_submit").click(function(){   
        var emailaddress=$(".subscribe_container .input_field input[name='email']").val();
        if( !isValidEmailAddress( emailaddress ) ) {
            alert("is not a valid email");
        }
        else alert("is a valid email");
        
    });*/
    /////////////////////////////////////////////////////////////////////////////////////////    
      
    
    ////////////////////////////Start of Home Section/////////////////////////
    
     
    //Mouse over the avatar section
    $(".entry").mouseenter(function(){
     
        $(this).find(".entry_transparent").css("display","block");//show the float div
        
        $(this).find(".entry_transparent").delay(0).stop(true,false).animate({
            opacity:0.9
        },400);  //make opacity for the floating div
    
        $(this).css("border-color","#EF4050");
        $(this).find(".entry_option").find(".option_price").css("background-image","url('img/ikimuk_entry_price_red.png')");
        $(this).find(".entry_option").find(".option_male").css("background-image","url('img/ikimuk_entry_male_red.png')");
        $(this).find(".entry_option").find(".option_female").css("background-image","url('img/ikimuk_entry_female_red.png')");

        
        
        
    });
    
    //Mouse leave the avatar section
    $(".entry").mouseleave(function(){ 
             
        $(this).stop();
        $(this).find(".entry_transparent").css("display","none");

        $(this).css("border-color","#CCCCCC");
        $(this).css("border-color","#CCCCCC");
        $(this).find(".entry_option").find(".option_price").css("background-image","url('img/ikimuk_entry_price.png')");
        $(this).find(".entry_option").find(".option_male").css("background-image","url('img/ikimuk_entry_male.png')");
        $(this).find(".entry_option").find(".option_female").css("background-image","url('img/ikimuk_entry_female.png')");
        $(this).find(".entry_transparent").css("opacity",0);
       
    });
     
     
     
    //Mouse click the avatar section
    $(".entry_transparent").click(function(){
        var link=$(this).parent().find("input[name='user_id']").val();
        window.location.href = link;
    });
    
    ///////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////Order section/////////////////////////

    //Cart set mouse enter
    $(".block_order .cart_body .selection_container .cart_no").mouseenter(function(){
        var cell_category=$(this).parent().hasClass("male_part") ? "GUY":"GIRL";//Get cell category
        var cell_size=$(this).find("input[name='size']").val();//Get cell size

        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category==cell_category)
            if(size==cell_size)return; //check if the cell over is the same selected
    
        $(this).delay(0).stop(true,false).animate({
            opacity:1
        },100); 
    });

    //Cart set mouse leave
    $(".block_order .cart_body .selection_container .cart_no").mouseleave(function(){
        var cell_category=$(this).parent().hasClass("male_part") ? "GUY":"GIRL";//Get cell category
        var cell_size=$(this).find("input[name='size']").val();//Get cell size

        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category==cell_category)
            if(size==cell_size)return; //check if the cell over is the same selected
    
        $(this).delay(0).stop(true,false).animate({
            opacity:0.3
        },100);   
    });

    //Cart set mouse click
    $(".block_order .cart_body .selection_container .cart_no").click(function(){

        var category=$(this).parent().hasClass("male_part") ? "GUY":"GIRL";//Get cell category
        var size=$(this).find("input[name='size']").val();//Get cell size
        $(".selection_container .cart_no").css("opacity",0.3);//reset all cells
        $(this).css("opacity",1);
        $(".order_submit").find("input[name='category']").val(category);//set selected cell category
        $(".order_submit").find("input[name='size']").val(size);//set selected cell size
    });

    /////////////////////////////////////////////////////////////////////////////////
   
   
    //////////////////////////Start Of cart Section//////////////////////////

    //Function to refresh total price
    function refresh_cart_prices(){
  
        var sub_total=0;
        $(".cart_entry").each(function(){      //Loop over all items and get its line price.
            sub_total+=parseFloat($(this).find(".cart_entry_content").find(".cart_entry_total").find("input[name='cart_total']").val());
        }); 

        $(".cart_payment .line_payment .payment_subtotal").text("$"+sub_total.toFixed(2));//update the subtotal text
        $(".cart_payment .subtotal").find("input[name='payment_subtotal']").val(sub_total);//update the subtotal hidden field
 
        var shipment=parseFloat($(".cart_payment .shipment").find("input[name='payment_shipment']").val());//read the shipment value
        var total=parseFloat(sub_total+shipment);//add the values of subtotal and shipment.
 
        $(".cart_payment .line_total").find("input[name='payment_total']").val(total);//Update the total payment
        $(".cart_payment .line_total .payment_total").text("$"+total.toFixed(2));//Update the text of total payment
    }

    if($(".cart_table").length>0)
        refresh_cart_prices();//refresh prices at startup


    //When the remove link clicked
    $(".cart_remove a").click(function(){
    
        $(this).parent().parent().parent().parent().remove();//remove the element
        refresh_cart_prices();//refresh the prices
    });
   
    /////////////////////////////////////////////////////////////////////////////////
   
   
    //////////////////////////Start Of checkout Section//////////////////////////
     function refresh_checkout_prices(){
                        var sub_total=parseFloat($("#checkout_subtotal").val());//Get the sub total
                        var shipping=parseFloat($("#checkout_shipping").val());//Get the shipping value
                        var total=sub_total+shipping;
                        $("#checkout_total").val(total);//set the total value in its hidden input
                        $("#total_text").text("$ "+total.toFixed(2));//set the text
                        $("#subtotal_text").text("$ "+sub_total.toFixed(2));//set the text
                    }
   
    ///country list value changed
    $('.combo .country_list').change(function(){
        var val=$('.combo .country_list option:selected').text();
        $('.combo .select_country').text(val);
        var shipping = $('.combo .country_list option:selected').data("delivery");
       $("#checkout_shipping").val(shipping);//change delivery charge
       $("#shipping_text").text("$ "+shipping.toFixed(2));
       refresh_checkout_prices();
      
       
    });
    ///code list value changed
    $('.combo .code_list').change(function(){
        var val=$('.combo .code_list option:selected').text();
        $('.combo .country_code').text(val);
    });

    //combo box focus on
    $('.combo select').focus(function(){
        $(this).parent().addClass("combo_highlight");
    });
    //combo boxx foxus out
    $('.combo select').focusout(function(){
        $(this).parent().removeClass("combo_highlight");
    }); 

    //validate input, variable,message displayed in case of error, 0=input combo box,1=input text
    function check_input(variable,message,flag)
    {
    
        if($.trim(variable.val()).length==0)
        { 
            variable.parent().parent().find(".line_error").text(message);
            if(flag){   
                variable.css("border-color","#EF2C21");
            }
            else{ 
                variable.parent().css("border-color","#EF2C21");
            }
            return 1;
            
        }
        return 0;
    }
     //Reset all input fields
    function reset_fields()
    {
        $(".line_input input").css("border-color","#CCCCCC");   
        $(".line_input select").parent().css("border-color","#CCCCCC");
        $(".line_error").text(""); 
    }
    //Reset all input fields
    /*function reset_fields()
    {
        $(".line_input input").css("border-color","#CCCCCC");   
        $(".line_input select").parent().css("border-color","#CCCCCC");
        $(".line_error").text(""); 
    }*/

    //payment checkout clicked
    $(".payment_checkout input[name='place']").click(function(){
    
        reset_fields();//reset all fields
    
        //get needed variables
        var country=$(".line_input select[name='country']");  
        var first_name=$(".line_input input[name='first_name']");   
        var last_name=$(".line_input input[name='last_name']");
        var address=$(".line_input input[name='address']");
        var city=$(".line_input input[name='city']");
        var region=$(".line_input input[name='region']");
        var zip=$(".line_input input[name='zip']");
        var code=$(".line_input select[name='code']");
        var tel=$(".line_input input[name='tel']");
     
        var flag=0;
        //check all required fields
        flag+=check_input(country,"Please Select a Country",0);
        flag+=check_input(first_name,"Please enter first name",1);
        flag+=check_input(last_name,"Please enter last name",1);
        flag+=check_input(address,"Please enter an address",1);
        flag+=check_input(city,"Please enter a city",1);
        flag+=check_input(region,"Please enter a region",1);
        //   flag+=check_input(zip,"Please enter zip code",1);
        flag+=check_input(code,"Please enter country code",0);
        flag+=check_input(tel,"Please enter your number",1);
         
        //check if terms is checked
        var agree=$(".terms_conditions input[name='agree']").is(":checked");
        if(!agree){
            flag++;
            $(".agreement").find(".line_error").text("You have to agree our terms and conditions");
        }
         //determine if the user allow subscribe
        var subscribe=$(".newsletter input[name='subscribe']").is(":checked");
        if(flag==0)
        {
            /*window.location.href = "/payment.php";
            if (1==1)
                {
            $.ajax({  
            type: "POST",  
            url: "/payment.php",  
            data: dataString,  
            success: function(response) {
        
                
            }  
        });}
        else
            {
                
            $.ajax({  
            type: "POST",  
            url: "/payment.php",  
            data: dataString,  
            success: function(response) {
        
                
            }  
            });
            
            }*/
        }
        else{
	    location.href = "#error_link";
            return false;
        }
  
    });
   
   
    ///////////////////////////////Start of preorder section//////////////////////////////////////////////
    //pre-order set mouse enter
    $(".t_shirt_option .cart_body .selection_container .cart_no").mouseenter(function(){
        var cell_category=$(this).parent().hasClass("male_part") ? "GUY":"GIRL";//Get cell category
        var cell_size=$(this).find("input[name='size']").val();//Get cell size

        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category==cell_category)
            if(size==cell_size)return; //check if the cell over is the same selected
    
        $(this).delay(0).stop(true,false).animate({
            opacity:1
        },100); 
    });

    //pre-order set mouse leave
    $(".t_shirt_option .cart_body .selection_container .cart_no").mouseleave(function(){
        var cell_category=$(this).parent().hasClass("male_part") ? "GUY":"GIRL";//Get cell category
        var cell_size=$(this).find("input[name='size']").val();//Get cell size

        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category==cell_category)
            if(size==cell_size)return; //check if the cell over is the same selected
    
        $(this).delay(0).stop(true,false).animate({
            opacity:0.3
        },100);   
    });

    //pre-order set mouse click
    $(".t_shirt_option .cart_body .selection_container .cart_no").click(function(){

        var category=$(this).parent().hasClass("male_part") ? "GUY":"GIRL";//Get cell category
        var size=$(this).find("input[name='size']").val();//Get cell size
        $(".selection_container .cart_no").css("opacity",0.3);//reset all cells
        $(this).css("opacity",1);
        $(".order_submit").find("input[name='category']").val(category);//set selected cell category
        $(".order_submit").find("input[name='size']").val(size);//set selected cell size
    });

    function RefreshPreorderPrices()
    {
        var sum=0;
        $(".pre_order").each(function(){ 
            var order_price= parseFloat($(this).find("input[name='price']").val()); //Het the entry price
            var order_count= parseInt($(this).find("input[name='count']").val()); //Get the entry count
            sum+=order_price*order_count;
        });
      
      
        var tax=parseInt($(".aramex_line").find("input[name='tax']").val());//Get the tax vcalue
        $(".aramex_line").find(".line_value").text("$ "+tax.toFixed(2));//Update the line value
        if(sum>0) //If there are entries
        {
            var content="$ "+sum.toFixed(2);
            $(".summary_sub_total").find(".sub_total_line").find(".line_value").text(content);//Update Subtotal text
            $(".summary_sub_total").find(".sub_total_line").find("input[name='sub_total']").val(sum);//Update the subtotal value
        

         
            var total=sum+tax;
            content="$ "+total.toFixed(2);
            $(".summary_total").find(".sub_total_line").find(".line_value").text(content);//Update Total Text
            $(".summary_total").find(".sub_total_line").find("input[name='total']").val(total);//Update The total value  
              
        }
        else{//If there are no entries
     
            $(".std_block_body .empty_pre_order").css("display","block");//Display the empty block
        
            $(".aramex_line").find(".line_value").text("--");//remove text value from tax line

            $(".summary_sub_total").find(".sub_total_line").find(".line_value").text("--");//remove text value from sub-total line
            $(".summary_sub_total").find(".sub_total_line").find("input[name='sub_total']").val(0);//reset subtotal value

            $(".summary_total").find(".sub_total_line").find(".line_value").text("--");//remove text value from total line
            $(".summary_total").find(".sub_total_line").find("input[name='total']").val(0);//reset total value
        }

         
    }

    var size_translator_object ={"XS" : "X Small", "S" : "Small", "M" : "Medium", "L" : "Large", "XL" : "X Large", "XXL" : "XX Large"};
    $(".t_shirt_option .order_submit").click(function(){
        
        $(".t_shirt_info .t_shirt_error").empty();//Clear the error field.
        
        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
	var perks = $("#preorder-perks").val();
        var size= size_translator_object[$(".order_submit").find("input[name='size']").val()];//Get selected size
        var product_id = $("#product_id").val();
        var flag_exist=false;
        var arr = new Array();
        
        $(".pre_order").each(function(){
            var order_category=  $(this).find("input[name='category']").val();   
            var order_size=  $(this).find("input[name='size']").val(); 
            if(category==order_category)
                if(size==order_size){//this item already exist
                    var new_count=parseInt($(this).find("input[name='count']").val());//Get its count
                    new_count++;//increase by 1
                    $(this).find("input[name='count']").val(new_count);
                    
                    var content=new_count + " " +size+" " +category+' T-shirt'+perks;
                    $(this).find(".pre_order_description").text(content);//Upate the description
                    $(".order_submit").find("input[name='category']").val("");//Reset category
                    $(".order_submit").find("input[name='size']").val("");//Reset size       
                    $(".selection_container .cart_no").css("opacity",0.3);//Reset appearance
                    RefreshPreorderPrices();//refrsh the prices
                    flag_exist=true;//mention that this entry already exist
                    return;
                }

        }); 
        if(!flag_exist)//entry already exist
        if(category==""){//no size selected
            $(".t_shirt_info .t_shirt_error").text("please choose an element");
        }//check if the user didn't click on any cell
        else{
            $(".std_block_body .empty_pre_order").css("display","none");//Hide the empty block
            var str='<div class="pre_order">';
            str+='<input type="hidden" name="category" value="'+category+'"/><input type="hidden" name="size" value="'+size+'"/>';
            str+='<input type="hidden" name="count" value="1"/><input type="hidden" name="price" value="25"/>';
            str+='<table><tbody><tr><td class="pre_order_avatar"><img width=60 src="'+$("#product_image").val()+'"/></td><td class="pre_order_description">';
            str+=size+' ' +category+' T-shirt'+perks+'</td>';
            str+='<td class="pre_order_close"></td></tr></tbody></table></div>';
            $(".pre_order_summary .std_block_body .preorder_content").append(str);//add new entry to cart
            $(".order_submit").find("input[name='category']").val("");//Reset category
            $(".order_submit").find("input[name='size']").val("");//Reset size       
            $(".selection_container .cart_no").css("opacity",0.3);//Reset appearance  
            RefreshPreorderPrices();//refresh prices
          }
         
          
        $(".pre_order").each(function(){
            var item = new Array();
            var order_category=  $(this).find("input[name='category']").val();   
            var order_size=  $(this).find("input[name='size']").val(); 
            var count = $(this).find("input[name='count']").val(); 
            item.push(product_id);
            item.push(order_size);
            item.push(order_category);
            item.push(count);
            arr.push(item);
        });
        $("#preorder_summary").val(arr);
  
    });
    
    
    $("body").delegate(".pre_order_close", "click", function(e) {
        $(this).parent().parent().parent().parent().remove();//remove block

        RefreshPreorderPrices();//Update Prices
    });
    /////////////////////////////////////////////////////////////////////////////////
   

   
    $(function () {
        var msie6 = $.browser == 'msie' && $.browser.version < 7;
        if (!msie6) { 
      
            var top = $('.cart_summary, .pre_order_summary').offset().top;//detectthe height from window to the container
    
            $(window).scroll(function (event) {
                var y = $(this).scrollTop();  //Get Scroll height 
                if (y >= top) { //The container is upper the window
          
                    var cart_height= $('.cart_summary, .pre_order_summary').offset().top+$('.cart_summary, .pre_order_summary').height();//Get height from top window to bottom container
       
                    var left_height=$('.checkout_column_left').height();//Get Left container height.
       
                    var diff=left_height-cart_height;
                    if(diff>0)diff=0;
         
                    $('.cart_summary, .pre_order_summary').css("position","fixed");//set cart div to fixed position
         
                    var option = {
                        top: diff
                    };//set top position
                    $('.cart_summary, .pre_order_summary').stop(true,false).animate(
                        option, 
                        {
                            queue:false, 
                            duration: "slow"
                        }); 
                }
                else { 
                    $('.cart_summary, .pre_order_summary').css("position","relative");//reset it to relative
                }
      
      
            });
        }
    }); 
   
   
/////////////////////////////////////////////////////////////////////////////////
    
});
 //Mouse over the avatar section
                $(document).ready(function(){
                        
                    $(".designer .designer_avatar").mouseenter(function(){
                        $(this).parent().find(".designer_transparent").css("display","block");//show the float div
        
                        $(this).parent().find(".designer_transparent").stop(true,false).delay(0).animate({
                            opacity:0.9
                        },400);  //make opacity for the floating div
    
                        $(this).parent().css("border-color","#333333");
         
                    });
    
                    //Mouse leave the avatar section
                    $(".designer .designer_transparent").mouseleave(function(){ 
             
                        $(this).stop();
                        $(this).parent().find(".designer_transparent").css("display","none");

                        $(this).parent().css("border-color","#CCCCCC");
                        $(this).parent().find(".designer_transparent").css("opacity",0);
       
                    });
                     
                     
                         $(".designer .designer_transparent").click(function(){
        var link=$(this).parent().find("input[name='designer_id']").val();
        location.href = link;
    });
                     
                     
                     
                });
    
/*
*/