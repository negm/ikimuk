/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function CallAfterLogin(){
		FB.login(function(response) {		
		if (response.status === "connected") 
		{
			LodingAnimate(); //Animate login
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
function LodingAnimate() //Show loading Image
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
 function moveOnMax(field,nextFieldID){
  if(field.value.length >= field.maxLength){
    document.getElementById(nextFieldID).focus();
  }
}
 var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35526185-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();