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
        $(this).find("img").attr("src","images/ikimuk_close.png"); 
    });
    $(".member_header .member_close a").mouseleave(function(){
        $(this).find("img").attr("src","images/ikimuk_disabled_close.png"); 
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
                confirm_password.parent().parent().find(".line_error").text("No combination");
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
                        $('#error').html("Incorrect email/password").show();
                        $('#error').html(response.error).show();
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
    
    ////////////////////////////Cart shop section/////////////////////////
 
    //Function to refresh total price
    function refresh_cart_prices(){
  
        var sub_total=0;
        $(".std_block_body .cart_entry").each(function(){      //Loop over all items and get its line price.
            sub_total+=parseFloat($(this).find(".cart_entry_content").find(".cart_entry_total").find("input[name='cart_total']").val());
        }); 

        $(".cart_payment .line_payment .payment_subtotal").text("$"+sub_total.toFixed(2));//update the subtotal text
        $(".cart_payment .subtotal").find("input[name='payment_subtotal']").val(sub_total);//update the subtotal hidden field
 
        var shipment=parseFloat($(".cart_payment .shipment").find("input[name='payment_shipment']").val());//read the shipment value
        var total=parseFloat(sub_total+shipment);//add the values of subtotal and shipment.
 
        $(".cart_payment .line_total").find("input[name='payment_total']").val(total);//Update the total payment
        $(".cart_payment .line_total .payment_total").text("$"+total.toFixed(2));//Update the text of total payment
 
    }

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
                    $('#item_count').html(response.item_count);
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
        alert(myData);
        jQuery.ajax({
            type: "POST",
            url: "/process_cart.php",
            //dataType:"json",
            data:myData,
            cache: false,
            success:function(response){
                if (response.quantity == 0)
                {
                    location.reload();
                }
                else
                {
                    $('#item_count').html(response.item_count);
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
    
    
    
    $(".cart_size_selection .add_to_cart").click(function(){
        var cut=$(".add_to_cart").find("input[name='category']").val();//Get selected category
        var size=$(".add_to_cart").find("input[name='size']").val();//Get selected size
        if(cut=="")alert("please choose an elementasdsad");//check if the user didn't click on any cell
        else{  
            var myData = 'action=add&cut='+cut+"&size="+size+"&product_id="+$("#product_id").val();
            alert(myData)
            jQuery.ajax({
                type: "POST",
                url: "/process_cart.php",
                dataType:"json",
                data:myData,
                cache: false,
                success:function(response){
                    //var json = $.parseJSON(response);
        
                    if ((response.error).length >3)
                    {
                        //alert(response.error)
                        return false;
                    }
                    else 
                    {
                        //update number of items next to cart
                        $('#item_count').html(response.item_count);
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
        //Get need variable and values
        var competition_type=$(".type_body").find(".type_select").find("input:radio[name='competition_type']:checked").val(); 
        var title=$(".info_body").find(".line_input").find("input[name='design_title']");
        var details=$(".info_body").find(".line_input").find("textarea[name='design_details']").val();
        var city=$(".self_info_body").find(".line_input").find("input[name='city']");
        var website_blog_1=$(".self_info_body").find(".line_input").find("input[name='website_blog_1']").val();
        var website_blog_2=$(".self_info_body").find(".line_input").find("input[name='website_blog_2']").val();
        var agree=$(".agreement_submit_section").find(".agreement").find(".terms_conditions").find("input[name='agree']").is(":checked");
        var newsletter=$(".agreement_submit_section").find(".newsletter").find("input[name='subscribe']").is(":checked");
        var img_list = $("#img_url");
        //check if there are any error and diplay it
        var flag=0;
        flag+=check_input(title,"please enter a title",1);
        flag+=check_input(city,"please enter your city",1);
        flag+=check_input(img_list,"Please Upload at least one image",1);
        if(!agree)
        {
            flag++;
            $(this).parent().parent().find(".line_error").text("Please Accept Our terms and conditions");
        }
  
  
        if(flag==0){
            var params = 'action=add_submission&design_title='+ encodeURIComponent(title.val())+'&img_url='+
            encodeURIComponent(img_list.val())+'&comment='+ encodeURIComponent(details)
            +'&newsletter='+newsletter+"&city="+ encodeURIComponent(city.val())
            +"&website_blog_1="+ encodeURIComponent(website_blog_1)+"&website_blog_2="+ 
            encodeURIComponent(website_blog_2)+"&competition="+competition_type;
            params = encodeURI(params);
            alert(params);
            $.ajax({  
                type: "POST",  
                url: "/process_submit.php",  
                data: params,  
                success: function(response) {  
                    alert(response)
                    if (response != 'shit')
                    {
                        alert("we made it bro :)")
                        return false;
                    }
                    else
                    { 
                        alert("we got shit all over the place bro")
                        return false;
                    }
            
                }
       
            });  
        }
  
    });
    //Reset all input fields
    function reset_fields()
    {
        $(".line_input input").css("border-color","#CCCCCC");   
        $(".line_input select").parent().css("border-color","#CCCCCC");
        $(".line_error").text(""); 
    }

    //validate input, variable,message displayed in case of error, 0=input combo box,1=input text
    function check_input(variable,message,flag)
    {
    
        if(variable.val().length==0)
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


//end of document ready
});



/* 
 * main JS file
 */
var target="";
var user_name="";
//Facebook login
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
            $("#results").html(response); //Result
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
    $("#LoginButton").hide(); //hide login button once user authorize the application
    $("#results").html('<img src="/img/ajax-loader-ikimuk.gif" /> Please Wait Connecting...'); //show loading image while we process user
}
 
function ResetAnimate() //Reset User button
{
    $("#LoginButton").show(); //Show login button
    $("#results").html(''); //reset element html
}

/*-------------------------------------------------------------------------------------*/
//--------------cart -------------------------------------

$(function() {
    $("#add_to_cart_submit").click(function(e) {
        if ($('#size').val()=="")
        {
            $("#size_g").removeClass("hidden").addClass("alertr").focus();
            return false;
        }
    
        return false;
    });

});
// Caption Overlay
function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            if (oldonload) {
                oldonload();
            }
            func();
        }
    }
}
addLoadEvent(function() {
    $('.home_list').mouseenter(function(){
        var image= $(this).find('img'),
        caption = $(this).find('div');
    
        caption.width(image.width());
        caption.height(image.height());
        caption.stop(true, true).fadeIn(350);
    }).mouseleave(function(){
        var image= $(this).find('img'),
        caption = $(this).find('div');
    
        caption.width(image.width());
        caption.height(image.height());
        caption.stop(true, true).fadeOut(350);
    });
});
//image Hover
$(function() {
    $('img[data-hover]').hover(function() {
        $(this)
        .attr('tmp', $(this).attr('src'))
        .attr('src', $(this).attr('data-hover'))
        .attr('data-hover', $(this).attr('tmp'))
        .removeAttr('tmp');
    }).each(function() {
        $('<img />').attr('src', $(this).attr('data-hover'));
    });
});
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
///////////////////////////////////////upload & submit
var uploaded = false;
var img_list = new Array();
$
////////////////preorder
var size="";
$(document).ready(function() {
    // put all your jQuery goodness in here.
    $('#name').attr("readonly",true);
    $('#ccode').attr("readonly",true);
    
    
});
 
$(function() {  
    $("#preorderSubmit").click(function(e) {  
        // validate and process form here
        if($("#newsletter").is(':checked'))
            newsletetr_val = 1;
        else
            newsletetr_val = 0;
        e.preventDefault();
        $("#preorderSubmit").html('<img src="/img/ajax-loader-ikimuk.gif" />');
        var valid = true;
        $(".alertr").addClass("hidden");
        if ($("#region").val() === "")
        {
            $("#region_g").removeClass("hidden").addClass("alertr").focus();
            valid = false;
        }
        if ($("#address").val().length < 9){
            $("#address_g").removeClass("hidden").addClass("alertr");
            $("#address").focus();
            valid = false;
        }
        if ($("#size").val()  ==="")
        {
            $("#size_g").removeClass("hidden").addClass("alertr").focus();
            valid = false;
        }
        if (!$("#agreement").is(':checked'))
        {
            $("#agreement_g").removeClass("hidden").addClass("alertr").focus();
            valid = false;
        }
        if ($("#monum").length > 0)
        {
            if ($("#monum").val().length < 7)
            {
                $("#monum_g").removeClass("hidden").addClass("alertr").focus();
                valid = false;
            }
            if ($("#vcode").val().length < 4)
            {
                $("#vcode_g").removeClass("hidden").addClass("alertr").focus();
                valid = false;
            }
        }
        if (!valid){
            $("#preorderSubmit").html('Preorder');
            return false;
        }
    
        var dataString = 'action=add&address='+$("#address").val()+'&size='+$("#size").val()+
        '&name='+$("#name").val()+'&email='+$("#email").val()+'&ccode='+$("#ccode").val()
        +'&monum='+$("#monum").val()+'&vcode='+$("#vcode").val()+'&design_id='+$("#design_id").val()
        +'&agreement='+$("#agreement").val()+'&newsletter='+newsletetr_val+'&region='+$('#region').val();
        $.ajax({  
            type: "POST",  
            url: "/process_preorder.php",  
            data: dataString,  
            success: function(response) {
        
                if (response === "agreement error")
                {
                
                    $("#agreement_g").removeClass("hidden").addClass("alertr").focus();
                    return false;
                }
                if (response === "mobile error")
                {
                    $("#monum_g").removeClass("hidden").addClass("alertr").focus();
                    return false;
                }
                if (response === "user error")
                {
                    alert("Please login using your facebook!  Scroll up :)");
                    return false;
                }
                if (response === "verification error")
                {
                    $("#vcode_g").removeClass("hidden").addClass("alertr").focus();
                    return false;
                }
                if (response === "address error")
                {
                    $("#address_g").removeClass("hidden").addClass("alertr");
                    $("#address").focus();
                    return false;
                }
                if (response === "already voted")
                {
                    $("#preorderForm").fadeOut(1000);
                    $(".userInfo").parent().fadeOut(1000);
                    $("#orderDuplicate").removeClass("hidden");
                    return false; 
                }
                else
                {
                    $("#preorderForm").fadeOut(1000);
                    $(".userInfo").parent().fadeOut(1000);
                    $("#orderComplete").removeClass("hidden");
                    return false; 
                }
            }  
        });  
        return false;  
     
    });  
});  

$(document).ready(function() {
    $(function(){
        $(".sizeIcon").click(function(e) {
            e.preventDefault();
            var size= 'size='+this.name;
            sid = "#"+this.id;
            //$("#size").val(this.name);
            jQuery.ajax({
                type: "POST",
                url: "/process_size.php",
                //dataType:"json",
                data:size,
                cache: false,
                success:function(response){
                    var json = $.parseJSON(response);
                    if (json.error == ""){
                        $(".sizeIcon").removeClass("selected");
                        $(sid).addClass("selected");
                        $('#size').val(json.size);
                        $('#cut').val(json.cut);
                        return false;
                    }
                    else{
                        return false;
                    }

                },
                error:function (xhr, ajaxOptions, thrownError){
                    //$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
                    return false;
                }
            });
        } );
    })
   
});

$(function(){
    $(".preorderButton").click(function() {
        if ($(".selected").length === 0)
        {
            $("#size_g").removeClass("hidden").addClass("alertr").focus();
            return false;
        }
    });
})

$(function(){
    $("#verify").click(function(e) 
    {
        e.preventDefault();
        $('#verify').attr("disabled","disabled");
        $("#verify").html('<img src="/img/ajax-loader-ikimuk.gif" />');
        var valid = true;
        $(".alertr").addClass("hidden");
        $("*").removeClass("alertr");
        if ($("#region").val() === "")
        {
            $("#region_g").removeClass("hidden").addClass("alertr").focus();
            valid = false;
        }
        if ($("#address").val().length < 9){
            $("#address_g").removeClass("hidden").addClass("alertr");
            $("#address").focus();
            valid = false;
        }
        if ($("#size").val()  ==="")
        {
            $("#size_g").removeClass("hidden").addClass("alertr").focus();
            valid = false;
        }
        if ($("#monum").length > 0)
        {
            if ($("#monum").val().length < 7)
            {
                $("#monum_g").removeClass("hidden").addClass("alertr").focus();
                valid = false;
            }
        }
        if (!valid){
            $('#verify').removeAttr("disabled").html("get SMS code");
            return false;
        }
        if ($("#monum").val()==="" || $("#monum").val().trim().length <6 || $("#monum").val().trim().match(/[^\d]/))
        {
            $("#monum_g").removeClass("hidden").addClass("alertr").focus();
            $('#verify').removeAttr("disabled").html("get SMS code"); 
            return false;
        }
        else{
            var monum = $("#monum").val();       
            if (monum[0] === '0'){
                monum = monum.replace(/^0+/, '');
            }
            var dataString = 'number='+$("#ccode").val().trim()+monum;
            $.ajax({
                type: "POST",
                url: "/sms.php",
                data: dataString,
                success: function(response)
                { 
                    alert(response);
                    if ((response === 'done'))
                    {
                        //show the preoder form
                        $("#vcode_g2").removeClass("hidden").addClass("alertr").focus();
                        $('#verify').removeAttr("disabled").html("Resend"); 
                        $("#vcode").focus();
                        return false;
                    }
                    else 
                    if(response === 'shit' )
                    {
                        //Either received more than 5 messages or requested a new code in less than 5 minutes
                        $("#vcode_g3").removeClass("hidden").addClass("alertr").focus();
                        $('#verify').removeAttr("disabled").html("get SMS code"); 
                        return false;
                    }
                    else
                    {
                        $("#vcode_g4").removeClass("hidden").addClass("alertr").focus();
                        $('#verify').removeAttr("disabled").html("get SMS code"); 
                        return false;
                    }
                } 
            });
                
                
        }
        $('#verify').removeAttr("disabled"); 
        return false;
    }
    
    );
})

// add product
$(document).ready(function() {
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
            if (!uploaded)
            {
                $("#img_g").removeClass("hidden").addClass("alertr").focus();
                valid = false;
            }
            return valid;
        // return false to cancel submit                  
        }
    }; 

    // pass options to ajaxForm 
    $('#addproduct').ajaxForm(options);
});

/*
*/