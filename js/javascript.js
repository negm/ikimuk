$(document).ready(function(){ 

//Load the carousel slider
if($('.carousel').length>0)
$('.carousel').carousel({
    interval: 5000
});
    

    
    
// check wich menu item has flag equal to selected and set it as selected
$(".menu_element").each(function(){
    if( $(this).find("input[name='flag']").val() == 'selected' ){ 
        $(this).addClass('menu_element_over') 
        $(this).find(".line").css("color","#90278E");
    }
});
    
    
  //Logo Mouse Over/Out  
    $(".lower_logo").mouseenter(function(){
        $(this).find("img").attr("src","/images/ikimuk_logo_beta_hover.png");
    });
        $(".lower_logo").mouseleave(function(){
         $(this).find("img").attr("src","/images/ikimuk_logo_beta.png");
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
    
////////////////////////////Specific shop section/////////////////////////

//Cart set mouse enter
$(".selection_container_block .cart_no").mouseenter(function(){
var cell_category=$(this).parent().parent().hasClass("male_part") ? "m":"w";//Get cell category
var cell_size=$(this).find("input[name='size']").val();//Get cell size

var category=$(".add_to_cart").find("input[name='category']").val();//Get selected category
var size=$(".add_to_cart").find("input[name='size']").val();//Get selected size
    if(category==cell_category)
        if(size==cell_size)return; //check if the cell over is the same selected
    
     $(this).delay(0).animate({
        opacity:1
    },100); 
});

//Cart set mouse leave
$(".selection_container_block .cart_no").mouseleave(function(){
var cell_category=$(this).parent().parent().hasClass("male_part") ? "m":"w";//Get cell category
var cell_size=$(this).find("input[name='size']").val();//Get cell size

var category=$(".add_to_cart").find("input[name='category']").val();//Get selected category
var size=$(".add_to_cart").find("input[name='size']").val();//Get selected size
    if(category==cell_category)
        if(size==cell_size)return; //check if the cell over is the same selected
    
     $(this).delay(0).animate({
        opacity:0.3
    },100);   
});

//Cart set mouse click
$(".selection_container_block .cart_no").click(function(){

    var category=$(this).parent().parent().hasClass("male_part") ? "m":"w";//Get cell category
    var size=$(this).find("input[name='size']").val();//Get cell size
    $(".selection_container_block .cart_no").css("opacity",0.3);//reset all cells
    $(this).css("opacity",1);//set opacity to 1 for the new selected cell
    
    $(".add_to_cart").find("input[name='category']").val(category);//set selected cell category
    $(".add_to_cart").find("input[name='size']").val(size);//set selected cell size
    
});

////////////checkout section///////////////////////////

///country list value changed
$('.combo .country_list').change(function(){
    var val=$('.combo .country_list option:selected').text();
    $('.combo .select_country').text(val);
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
         flag+=check_input(tel,"Please enter a tel no.",1);
         
         //check if terms is checked
         var agree=$(".terms_conditions input[name='agree']").is(":checked");
         if(!agree){flag++;$(".agreement").find(".line_error").text("You have to agree our terms and conditions");}
         
         //determine if the user allow subscribe
         var subscribe=$(".newsletter input[name='subscribe']").is(":checked");
         
         
         if(flag==0)
             {
                 alert("everything gonna fine");
             }
  
});
//////////////////////////////////Submit Section//////////////////////////////////////////////////
$(".theme_content .theme_avatar").mouseenter(function(){
    $(this).parent().find(".theme_transparent").css("display","block");
   var type=$.browser.msie;//check browser if IE
    var version=parseInt($.browser.version);//Get Browser version
if(type&&version<9);//If version less than 9, do nothing
else{ 
         $(this).parent().find(".theme_transparent").delay(0).animate({
        opacity:0.9
    },300);  //make opacity for the floating div   
}

});
$(".theme_transparent").mouseleave(function(){
    $(this).css("display","none");
   var type=$.browser.msie;//check browser if IE
    var version=parseInt($.browser.version);//Get Browser version
if(type&&version<9);//If version less than 9, do nothing
        else
        $(this).css("opacity",0);
});
   
    
});
