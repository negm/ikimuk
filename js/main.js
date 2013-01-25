/* 
 * main JS file
 */
var target="";
var user_name="";
//Login
$(function() {
$( "#form_login" ).validate(
            {rules: {
                email: {              //input name: email
                    required: true,   //required boolean: true/false
                    email: true       //required boolean: true/false
                },
                password: {            //input name: message
                    required: true
                }
            },
            messages: {               //messages to appear on error
                email: "Enter a valid email.",
                password: "Password can't be empty"
                  },
            submitHandler: function(form) {
                   var myData = 'action=login&'+$('#form_login').serialize();
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
    if(target.length > 1)
        window.location.href = target;
    else
        location.reload();
    
 },
error:function (xhr, ajaxOptions, thrownError){
//$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
    }
 });
  return false;
}
            });
            
//Signup
$( "#form_register" ).validate(
            {rules: {
			firstname: "required",
			lastname: "required",
			password: {
				required: true,
				minlength: 8
			},
			password_confirmation: {
				required: true,
				minlength: 8,
				equalTo: "#register_password"
			},
			email: {
				required: true,
				email: true
				},
			policy_agreement: "required"
		},
		messages: {
			firstname: "Enter your firstname",
			lastname: "Enter your lastname",
			password: {
				required: "Provide a password",
				rangelength: jQuery.format("Enter at least {0} characters")
			},
			password_confirmation: {
				required: "Repeat your password",
				minlength: jQuery.format("Enter at least {0} characters"),
				equalTo: "Enter the same password as above"
			},
			email: {
				required: "Please enter a valid email address",
				minlength: "Please enter a valid email address"
				},
			policy_agreement: "Please read the Privacy Policy and Terms"
		},
            submitHandler: function(form) {
                   var myData = 'action=signup&'+$('#form_register').serialize();
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
    if(target.length > 1)
        window.location.href = target;
    else
        location.reload();
    
 },
error:function (xhr, ajaxOptions, thrownError){
//$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
    }
 });
  return false;
}
            });

});
 
  $(document).ready(function() {
//Signup


});
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
if (window.focus) {newwindow.focus()}
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
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
///////////////////////////////////////upload & submit
 var uploaded = false;
 var img_list = new Array();
$(function() {  
  $("#submit_design").click(function(e){
    if($("#newsletter").is(':checked'))
        newsletetr_val = 1;
    else
        newsletetr_val = 0;
    e.preventDefault();
    $("#submit_design").html('<img src="/img/ajax-loader-ikimuk.gif" />');
    var valid = true;
    $("*").removeClass("alertr");
    if($('#design_title').val().length <1){           
     $("#title_g").removeClass("hidden").addClass("alertr").focus();  valid = false;}
    if (!uploaded)
        {$("#img_g").removeClass("hidden").addClass("alertr").focus();  valid = false;}
     if(!valid)
         {$("#submit_design").html("Submit your design");return valid; }
    // return false to cancel submit    
    var params = 'design_title='+ $('#design_title').val()+'&img_url='+
        $("#img_url").val()+'&comment='+$("#comment").val()+'&newsletter='+newsletetr_val;
    $.ajax({  
    type: "POST",  
    url: "/process_submit.php",  
    data: params,  
    success: function(response) {  
     if (response === 'done')
            {
                $(".preSummary").fadeOut(1000);
                $('#title_msg').html( $('#design_title').val());
                $("#orderComplete").removeClass("hidden");
                return false;
            }
        else
            { $(".preSummary").fadeOut(1000);
                $('#title_msg').html( $('#design_title').val());
                $("#submitFailed").removeClass("hidden");
                return false;}
            
       }
       
      });   
      return false;
  });}) 
 ////////////////preorder
     var size="";
    $(document).ready(function() {
   // put all your jQuery goodness in here.
    $('#name').attr("readonly",true);
    //$('#email').attr("readonly",true);
    $('#ccode').attr("readonly",true);
    $('#address').popover({'trigger':'focus', 'title': 'Please write down your full address so we can deliver to your doorstep!'});
    $('#monum').popover({'trigger':'focus', 'placement':'bottom', 'html':'true','title': 'Please fill in your 8-digit<br>  Lebanese number!'});
    $('#vcode').popover({'trigger':'focus', 'title': 'The code you received via SMS!'});
    
    
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
    {$("#region_g").removeClass("hidden").addClass("alertr").focus();valid = false;}
    if ($("#address").val().length < 9){$("#address_g").removeClass("hidden").addClass("alertr");
     $("#address").focus();  valid = false;}
    if ($("#size").val()  ==="")
        {$("#size_g").removeClass("hidden").addClass("alertr").focus();valid = false;}
    if (!$("#agreement").is(':checked'))
        {$("#agreement_g").removeClass("hidden").addClass("alertr").focus(); valid = false;}
    if ($("#monum").length > 0)
        {
            if ($("#monum").val().length < 7)
                {$("#monum_g").removeClass("hidden").addClass("alertr").focus(); valid = false;}
            if ($("#vcode").val().length < 4)
                {$("#vcode_g").removeClass("hidden").addClass("alertr").focus(); valid = false;}
        }
    if (!valid){$("#preorderSubmit").html('Preorder');return false;}
    
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
                
                $("#agreement_g").removeClass("hidden").addClass("alertr").focus();return false;
            }
          if (response === "mobile error")
            {$("#monum_g").removeClass("hidden").addClass("alertr").focus(); return false;}
           if (response === "user error")
               {alert("Please login using your facebook!  Scroll up :)");return false;}
           if (response === "verification error")
               {$("#vcode_g").removeClass("hidden").addClass("alertr").focus();return false;}
           if (response === "address error")
               {$("#address_g").removeClass("hidden").addClass("alertr");$("#address").focus();  return false;}
           if (response === "already voted")
               {
                   $("#preorderForm").fadeOut(1000);$(".userInfo").parent().fadeOut(1000);$("#orderDuplicate").removeClass("hidden");return false; 
               }
           else
                {
                 $("#preorderForm").fadeOut(1000);$(".userInfo").parent().fadeOut(1000);$("#orderComplete").removeClass("hidden");return false; 
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
	dataType:"html",
	data:size,
	cache: false,
	success:function(response){
        if (response === 'done'){         
        
	$(".sizeIcon").removeClass("selected");
        $(sid).addClass("selected");
        return false;}
        else{return false;}

 },
error:function (xhr, ajaxOptions, thrownError){
//$("#results").html('<fieldset style="color:red;">'+thrownError+'</fieldset>'); //Error
 return false;
    }
 });
   }  );})
   
 });

$(function(){
    $(".preorderButton").click(function() {
        if ($(".selected").length === 0)
            {$("#size_g").removeClass("hidden").addClass("alertr").focus();return false;}
    });})

$(function(){
    $("#verify").click(function(e) 
    {   e.preventDefault();
        $('#verify').attr("disabled","disabled");
        $("#verify").html('<img src="/img/ajax-loader-ikimuk.gif" />');
        var valid = true;
    $(".alertr").addClass("hidden");
    $("*").removeClass("alertr");
    if ($("#region").val() === "")
    {$("#region_g").removeClass("hidden").addClass("alertr").focus();valid = false;}
    if ($("#address").val().length < 9){$("#address_g").removeClass("hidden").addClass("alertr");
     $("#address").focus();  valid = false;}
    if ($("#size").val()  ==="")
        {$("#size_g").removeClass("hidden").addClass("alertr").focus();valid = false;}
    if ($("#monum").length > 0)
        {
            if ($("#monum").val().length < 7)
                {$("#monum_g").removeClass("hidden").addClass("alertr").focus(); valid = false;}
        }
    if (!valid){$('#verify').removeAttr("disabled").html("get SMS code"); return false;}
        if ($("#monum").val()==="" || $("#monum").val().trim().length <6 || $("#monum").val().trim().match(/[^\d]/))
            {$("#monum_g").removeClass("hidden").addClass("alertr").focus();
            $('#verify').removeAttr("disabled").html("get SMS code"); 
            return false;}
        else{var monum = $("#monum").val();       
               if (monum[0] === '0'){monum = monum.replace(/^0+/, '');}
               var dataString = 'number='+$("#ccode").val().trim()+monum;
                $.ajax({
                type: "POST",
                url: "/sms.php",
                data: dataString,
                success: function(response)
                 { 
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
    
);})

// add product
$(document).ready(function() {
 var options = {
    url:        'process-addproduct.php', 
    success:    function(response) {
        if (response === 'done')
            { $("#addproduct").fadeOut(1000);
                $(".userInfo").fadeOut(1000);
                $("#orderComplete").removeClass("hidden");
                return false;}
        else {alert("something went wrong"+ response);}
          },
    beforeSubmit: function(arr, $form, options) { 
    // The array of form data takes the following form: 
    // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ] 
    var valid = true;
    $("*").removeClass("alertr");
    if($('#title').val().length <1){           
     $("#title_g").removeClass("hidden").addClass("alertr").focus();  valid = false;}
    if (!uploaded)
        {$("#img_g").removeClass("hidden").addClass("alertr").focus();  valid = false;}
     return valid;
    // return false to cancel submit                  
    }
}; 

// pass options to ajaxForm 
$('#addproduct').ajaxForm(options);
});

/*
*/