$(document).ready(function(){ 

    
    
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
    
    
    
    
    
    
});