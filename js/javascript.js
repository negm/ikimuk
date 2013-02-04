$(document).ready(function(){ 

//Load the carousel slider
$('.carousel').carousel({
    interval: 2000
});
    
//Check if the email is valid    
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}
    
    
    
// check wich menu item has flag equal to selected and set it as selected
$(".menu_element").each(function(){
    if( $(this).find("input[name='flag']").val() == 'selected' ){ 
        $(this).addClass('menu_element_over') 
        $(this).find(".line").css("color","#90278E");
    }
});
    
    
  //Logo Mouse Over/Out  
    $(".lower_logo").mouseenter(function(){
        $(this).find("img").attr("src","images/ikimuk_logo_beta_hover.png");
    });
        $(".lower_logo").mouseleave(function(){
         $(this).find("img").attr("src","images/ikimuk_logo_beta.png");
    });
    
    
    
    //Menu elements(Over/Out/Click)
      $(".menu_element").mouseenter(function(){
            var flag=$(this).find("input[name='flag']").val();
            if(flag=="selected")return;
          $(this).addClass("menu_element_over");
          $(this).find(".line").css("color","#90278E");
    });
        $(".menu_element").mouseleave(function(){ 
            var flag=$(this).find("input[name='flag']").val();
            if(flag=="selected")return;
        
        $(this).removeClass("menu_element_over");
        $(this).find(".line").css("color","#FFFFFF");
    });
     $(".menu_element").click(function(){
         var link=$(this).find("input[name='link']").val();
         top.location.href=link;
     });
     
     
     
     //Mouse over the avatar section
     $(".entry .avatar").mouseenter(function(){
     
    $(this).parent().find(".avatar_transparent").css("display","block");//show the float div
        
   var type=$.browser.msie;//check browser if IE
    var version=parseInt($.browser.version);//Get Browser version
if(type&&version<9);//If version less than 9, do nothing
else{ 
         $(this).parent().find(".avatar_transparent").delay(0).animate({
        opacity:0.9
    },300);  //make opacity for the floating div
}

        
        
        
    });
    
    //Mouse leave the avatar section
         $(".avatar_transparent").mouseleave(function(){ 
        $(this).parent().find(".avatar_transparent").css("display","none");
   var type=$.browser.msie;//check browser if IE
    var version=parseInt($.browser.version);//Get Browser version
if(type&&version<9);//If version less than 9, do nothing
        else
        $(this).parent().find(".avatar_transparent").css("opacity",0);
    });
     
     //Mouse click the avatar section
     $(".avatar_transparent").click(function(){
         var link=$(this).parent().find("input[name='user_id']").val();
         alert(link);
     });
    
    
    
    
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
    
    //Login button clicked
       $(".control_section .login").click(function(){
        $(".control_section .login_menu").css("display","block");
        $(".control_section .login_menu .login_header").css("display","block");
        $(".control_section .header_button").css("display","none");
     });
     
     //Join Us clicked
     $(".control_section .joinus").click(function(){
         alert("joinus clicked");
     });
    
    //Subscribe link clicked
    $(".subscribe_container .subscribe_link").click(function(){   
        var emailaddress=$(".input_field input").val();
        if( !isValidEmailAddress( emailaddress ) ) {alert("is not a valid email");}
        else alert("is a valid email");
        
    });
    
    //Subscribe, input link (focus/focus out)
    $(".input_field input").focus(function(){
        var val=$(this).val();
        if(val=="Enter your e-mail")$(this).val("");
    });
        $(".input_field input").focusout(function(){
        var val=$(this).val();
        if(val=="")$(this).val("Enter your e-mail");
    });
    
    
    //login arrow on over
    $(".login_arrow").mouseenter(function(){
        $(".menu_drop").css("display","block");  
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
     else
         alert("profile clicked"); 
    });
    
    
////////////////////////////Specific shop section/////////////////////////

//Cart set mouse enter
$(".selection_container_block .cart_no").mouseenter(function(){
    var flag=$(this).find("input[name='flag']").val();
    if(flag=="selected"){return;}
    
     $(this).delay(0).animate({
        opacity:1
    },100);   
});
//Cart set mouse leave
$(".selection_container_block .cart_no").mouseleave(function(){
    var flag=$(this).find("input[name='flag']").val();
    if(flag=="selected"){return;}
    
     $(this).delay(0).animate({
        opacity:0.3
    },100);   
});

//Cart set mouse click
$(".selection_container_block .cart_no").click(function(){
    var flag=$(this).find("input[name='flag']").val();
    if(flag=="selected"){return;} 
$(".selection_container_block .cart_no").css("opacity",0.3);
$(".selection_container_block .cart_no").find("input[name='flag']").val("unselected");
$(this).find("input[name='flag']").val("selected");
$(this).css("opacity",1);    
});

//Add to Cart on click
$(".cart_size_selection .add_to_cart").click(function(){
  var flag=true;
  $(".selection_container_block .cart_no").each(function(){      //check which item is clicked
    if( $(this).find("input[name='flag']").val() == 'selected' ){ 
       alert($(this).find("input[name='size']").val());
       flag=false;//determine that an element is selected.
    }
}); 
  if(flag)alert("please select an item first"); 
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

//when the checkout button clicked
$(".payment_process .payment_checkout").click(function(){
    alert("checkout clicked");
     refresh_cart_prices();
});


    
    
    
});