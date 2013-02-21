$(document).ready(function(){ 

    //Check if the email is valid    
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    }
    
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
    
    //Login button clicked
    $(".control_section .login").click(function(){
        alert("login clicked");
    });
     
    //Join Us clicked
    $(".control_section .joinus").click(function(){
        alert("joinus clicked");
    });
    
    //Subscribe link clicked
    $(".subscribe_link .input_submit").click(function(){   
        var emailaddress=$(".subscribe_container .input_field input[name='email']").val();
        if( !isValidEmailAddress( emailaddress ) ) {
            alert("is not a valid email");
        }
        else alert("is a valid email");
        
    });
    /////////////////////////////////////////////////////////////////////////////////////////    
      
    
    ////////////////////////////Start of Home Section/////////////////////////
    
     
    //Mouse over the avatar section
    $(".entry .entry_avatar").mouseenter(function(){
     
        $(this).parent().find(".entry_transparent").css("display","block");//show the float div
        
        $(this).parent().find(".entry_transparent").delay(0).animate({
            opacity:0.9
        },400);  //make opacity for the floating div
    
        $(this).parent().css("border-color","#EF4050");
        $(this).parent().find(".entry_option").find(".option_price").css("background-image","url('img/ikimuk_entry_price_red.png')");
        $(this).parent().find(".entry_option").find(".option_male").css("background-image","url('img/ikimuk_entry_male_red.png')");
        $(this).parent().find(".entry_option").find(".option_female").css("background-image","url('img/ikimuk_entry_female_red.png')");

        
        
        
    });
    
    //Mouse leave the avatar section
    $(".entry .entry_transparent").mouseleave(function(){ 
             
        $(this).stop();
        $(this).parent().find(".entry_transparent").css("display","none");

        $(this).parent().css("border-color","#CCCCCC");
        $(this).parent().css("border-color","#CCCCCC");
        $(this).parent().find(".entry_option").find(".option_price").css("background-image","url('img/ikimuk_entry_price.png')");
        $(this).parent().find(".entry_option").find(".option_male").css("background-image","url('img/ikimuk_entry_male.png')");
        $(this).parent().find(".entry_option").find(".option_female").css("background-image","url('img/ikimuk_entry_female.png')");
        $(this).parent().find(".entry_transparent").css("opacity",0);
       
    });
     
     
     
    //Mouse click the avatar section
    $(".entry_transparent").click(function(){
        var link=$(this).parent().find("input[name='user_id']").val();
        alert(link);
    });
    
    ///////////////////////////////////////////////////////////////////////////////////
    
    
   

    ////////////////////////////Order section/////////////////////////

    //Cart set mouse enter
    $(".selection_container .cart_no").mouseenter(function(){
        var cell_category=$(this).parent().hasClass("male_part") ? "male":"female";//Get cell category
        var cell_size=$(this).find("input[name='size']").val();//Get cell size

        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category==cell_category)
            if(size==cell_size)return; //check if the cell over is the same selected
    
        $(this).delay(0).animate({
            opacity:1
        },100); 
    });

    //Cart set mouse leave
    $(".selection_container .cart_no").mouseleave(function(){
        var cell_category=$(this).parent().hasClass("male_part") ? "male":"female";//Get cell category
        var cell_size=$(this).find("input[name='size']").val();//Get cell size

        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category==cell_category)
            if(size==cell_size)return; //check if the cell over is the same selected
    
        $(this).delay(0).animate({
            opacity:0.3
        },100);   
    });

    //Cart set mouse click
    $(".selection_container .cart_no").click(function(){

        var category=$(this).parent().hasClass("male_part") ? "male":"female";//Get cell category
        var size=$(this).find("input[name='size']").val();//Get cell size
        $(".selection_container .cart_no").css("opacity",0.3);//reset all cells
        $(this).css("opacity",1);
        $(".order_submit").find("input[name='category']").val(category);//set selected cell category
        $(".order_submit").find("input[name='size']").val(size);//set selected cell size
    });

    $(".order_submit").click(function(){
        var category=$(".order_submit").find("input[name='category']").val();//Get selected category
        var size=$(".order_submit").find("input[name='size']").val();//Get selected size
        if(category=="")alert("please choose an element");//check if the user didn't click on any cell
        else{
            alert(category);
            alert(size);
        }
  
    });
/////////////////////////////////////////////////////////////////////////////////
    
});