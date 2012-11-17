/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var target="";
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
    
function AjaxResponse()
{
var myData = 'connect= 1';
jQuery.ajax({
type: "POST",
url: "process_facebook.php",
dataType:"html",
data:myData,
cache: false,
success:function(response){
$("#results").html(response); //Result
if(target.length > 1)
    window.location = target;
else
    location.reload(true);
 },
error:function (xhr, ajaxOptions, thrownError){
//$("#results").html('<fieldset style="padding:20px;color:red;">'+thrownError+'</fieldset>'); //Error
    }
 });
 }
 
function CallAfterLogin(){
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
    $("#results").html('<img src="img/ajax-loader.gif" /> Please Wait Connecting...'); //show loading image while we process user
}
 
function ResetAnimate() //Reset User button
{
    $("#LoginButton").show(); //Show login button
    $("#results").html(''); //reset element html
}
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
  _gaq.push(['_setAccount', 'UA-35526185-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  

///////////////////////////////////////upload & submit
$(document).ready(function() {
 var uploaded = false;
 var options = {
//  target:     '#divToUpdate', 
    url:        'process-submit.php', 
    success:    function(response) {
        
        if (response === 'done')
            {
                $("#submitDesign").fadeOut(1000);
                $(".userInfo").fadeOut(1000);
                $("#orderComplete").removeClass("hidden");
                return false;
            }
        else
            {alert("something went wrong"+ response);}
            
       
        },
    beforeSubmit: function(arr, $form, options) { 
    // The array of form data takes the following form: 
    // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ] 
    var valid = true;
    $("*").removeClass("alert");
    if($('#design_title').val().length <1){           
     $("#title_g").removeClass("hidden").addClass("alert").focus();  valid = false;}
    if (!uploaded)
        {$("#img_g").removeClass("hidden").addClass("alert").focus();  valid = false;}
     return valid;
    // return false to cancel submit                  
    }
     
}; 

// pass options to ajaxForm 
$('#submitDesign').ajaxForm(options);


	$(function(){
		var btnUpload=$('#upload');
		var status=$('#status');
		new AjaxUpload(btnUpload, {
			action: 'process-upload.php',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                    // extension is not allowed 
					status.text('Only JPG, PNG or GIF files are allowed');
					return false;
				}
				status.text('Uploading...');
			},
			onComplete: function(file, response){
				//On completion clear the status
				status.text('');
				//Add uploaded file to list
				if(response != "error"){
					$('<li></li>').appendTo('#files').html('<img src="'+response+'" alt="" /><br />'+file).addClass('success');
                                        $('#img_url').val(response);
                                        uploaded = true;
                                        $('#upload').hide();
				} else{
					$('<li></li>').appendTo('#files').text(file).addClass('error');
				}
			}
		});
		
	});
 });
 
 
 ////////////////preorder
     var size="";
    $(document).ready(function() {
   // put all your jQuery goodness in here.
    $('#name').attr("readonly",true);
    $('#email').attr("readonly",true);
    $('#ccode').attr("readonly",true);
    $('#address').popover({'trigger':'focus', 'title': 'Please write down your full address so we can deliver to your doorstep!'});
    $('#monum').popover({'trigger':'focus', 'placement':'bottom', 'title': 'Please fill in your 8-digit  Lebanese number!'});
    $('#vcode').popover({'trigger':'focus', 'title': 'The code you received via SMS!'});
    
    var preorder_options = {
//  target:     '#divToUpdate', 
    url:        'process_preorder.php', 
    success:    function(response) { 
        //alert('thanks for completing the preorder' + response); 
        if (response === "agreement error")
            {
                $("#agreement_g").removeClass("hidden").addClass("alert").focus();return false;
            }
          if (response === "mobile error")
            {
               $("#monum_g").removeClass("hidden").addClass("alert").focus(); return false;
            }
           if (response === "user error")
               {
                   alert("Please login using your facebook!  Scroll up :)");
                   return false;
               }
           if (response === "verification error")
               {
                    $("#vcode_g").removeClass("hidden").addClass("alert").focus();
                    return false;
               }
           if (response === "address error")
               {
                   $("#address_g").addClass("alert");
                     $("#address").focus();  return false;
               }
            else
                {
                $("#preorderForm").fadeOut(1000);
                $(".userInfo").fadeOut(1000);
                $("#orderComplete").removeClass("hidden");
                return false;
                }
        },
    beforeSubmit: function(arr, $form, options) { 
    // The array of form data takes the following form: 
    // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ] 
    var valid = true;
    $("*").removeClass("alert");
    if ($("#address").val().length < 9){$("#address_g").addClass("alert");
     $("#address").focus();  valid = false;}
    if ($("#size").val()  ==="")
        {$("#size_g").removeClass("hidden").addClass("alert").focus();valid = false;}
     if (!$("#agreement").is(':checked'))
        {$("#agreement_g").removeClass("hidden").addClass("alert").focus(); valid = false;}
   return valid;
    // return false to cancel submit                  
    }  
}; 
// pass options to ajaxForm 
$('#preorderForm').ajaxForm(preorder_options);
 });
 $(function(){
    $(".sizeIcon").click(function() {
    $("#size").val(this.name);
    $(".sizeIcon").removeClass("selected");
    $(this).addClass("selected");
        
        return false;
    }  );})

$(function(){
    $("#verify").click(function() 
    {
        //if ($("#ccode").val()==="" ||  $("#ccode").val().trim().match(/[^\d]/))
          //  {alert("The country code you entered isn't correct");$("#ccode").focus();return false;}
        if ($("#monum").val()==="" || $("#monum").val().trim().length <6 || $("#monum").val().trim().match(/[^\d]/))
            {$("#monum_g").removeClass("hidden").addClass("alert").focus(); return false;}
        else{var monum = $("#monum").val();       
               if (monum[0] === '0'){monum = monum.replace(/^0+/, '');}
               var dataString = 'number=+'+$("#ccode").val().trim()+monum;
                $.ajax({
                type: "POST",
                url: "sms.php",
                data: dataString,
                success: function(response)
                 { 
                    if ((response === 'done'))
                    {
                    //show the preoder form
                     $("#vcode_g2").removeClass("hidden").addClass("alert").focus();
                     return false;
                     }
                     else 
                     if(response === 'shit' )
                     {
                       //Either received more than 5 messages or requested a new code in less than 5 minutes
                     $("#vcode_g3").removeClass("hidden").addClass("alert").focus();
                     return false;
                      }
                      else
                      {
                     $("#vcode_g4").removeClass("hidden").addClass("alert").focus();
                     return false;
                      }
                  } 
                });
                
                
            }
    return false;
    }
    
);})
