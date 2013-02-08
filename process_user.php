<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/class/settings.php"); //Include configuration file.
require_once ($_SERVER["DOCUMENT_ROOT"]."/class/class.user.php");
session_start(); 

/*
check our post variable from index.php, just to insure user isn"t accessing this page directly.
You can replace this with strong function, something like HTTP_REFERER
*/
if (!isset($_POST["action"]))
{
    header("Location: /index.php");
}
if ($_POST["action"] == "fb_login")
{
    facebook_login();
}
if ($_POST["action"]== "login")
{
    login();
}
if ($_POST["action"]== "signup")
{
    signup();
}
if ($_POST["action"]== "change_password")
{
    change_password();
}
else
{
    //header("Location: /index.php");
}


function facebook_login()
{
$settings = new settings();
if(isset($_POST["connect"]) && $_POST["connect"]==1)
{       unset($_GET["logout"]);	
	$user =  new user();
	//Call Facebook API
	if (!class_exists('FacebookApiException')) {
	require_once('/inc/facebook.php' );
	}
		$facebook = new Facebook(array('appId' => $settings->app_id,'secret' => $settings->app_secret,));
	Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
        Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
	$fbuser = $facebook->getUser();
        if ($fbuser) {
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$me = $facebook->api('/me'); //user
			$uid = $facebook->getUser();
		}
		catch (FacebookApiException $e) 
		{
                       	echo error_log($e);
                       	$fbuser = null;
                        return;
		}
	}
	
	// redirect user to facebook login page if empty data or fresh login requires
	if (!$fbuser){
		$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$settings->site_url, false));
	}
	//user details
	$user->name = $me['name'];
	$user->email= $me['email'];
        $user->fbid = $uid;
          
	//Check user id in our database
	$user->selectbyfb();
	if($user->database->rows > 0)
	{	
	//User exist, Show welcome back message
	echo "Welcome back $user->name!";
	//User is now connected, log him in
        }
	else
	{
	//User is new, Show connected message and store info in our Database
	echo "Hi $user->name!.";
	// Insert user into Database.
	$user->insert_fb();
         }
        $_SESSION["access_token"] = $facebook->getAccessToken();
        login_user($user);
}
else
    return;
}

function login()
{
    if (!isset($_POST["email"]) || !isset($_POST["password"])
            ||strlen($_POST["email"])<1 || strlen($_POST["password"])<1)
    {   $error = "parameters missing";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    $password=$_POST["password"];
    $email=$_POST["email"];
    $settings = new settings();
    $user = new user();
    $password = crypt($password, $settings->salt);
    $user->email = $email;
    $user->password = $password;
    if ($user->login() )
    {   
        login_user($user);
        $error = "";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    else
       { $error = "Incorrect email/password";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
       }
     
}

function signup()
{
    if (!isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["full_name"])
          || !isset($_POST["confirm_password"])||strlen($_POST["email"])<1
            || strlen($_POST["password"])<1  || strlen($_POST["full_name"])<1 )
      {
         $error = "Please complete all the required fields";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    if ($_POST["password"] != $_POST["confirm_password"])
    {
         $error = "Password and password confirmation don't match";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
     /*if ($_POST["policy_agreement"]!= 1)
    {
        $error = "Please read and agree to the privacy policy";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }*/
    try{
    $email = $_POST["email"];
    if(filter_var($email,FILTER_VALIDATE_EMAIL) === false)
    {
        $error = "Invalid Email".$email;
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    $password= $_POST["password"];
    $settings = new settings();
    $user = new user();
    $password = crypt($password, $settings->salt);
    $name = $_POST["full_name"];
    $user->name = $name;
    $user->email = $email;
    $user->password = $password;
    if ($user->is_email_used())
    {
        $error = "Email already used";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return; 
    }
    $user->insert();
    {
        login_user($user);
        $error = "";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return; 
     }
    
}catch(Exception $ex)
{
    $error = "exception caught";
    $result = json_encode(array("error"=>$error));
    print_r($result);
    return; 
}

}

function change_password()
{
    if (!isset($_POST["password"]) || !isset($_POST["password_confirmation"]) )
    {
         $error = "Please complete all the required fields";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    if ($_POST["password"] != $_POST["password_confirmation"])
    {
         $error = "Password and confirmation don't match";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    if (!isset($_SESSION["logged_in"])|| !$_SESSION["logged_in"])
    {
         $error = "user is not logged in";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    $password= $_POST["password"];
    $settings = new settings();
    $user = new user();
    $user->id = $_SESSION['user_id'];
    $password = crypt($password, $settings->salt);
    $user->password = $password;
    if ($user->change_password())
    {
         $error = "";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    else
    {
         $error = "process failed";
        $result = json_encode(array("error"=>$error));
        print_r($result);
        return;
    }
    
    
}
function login_user($user)
{	/*
	function stores some session variables to imitate user login. 
	We will use these session variables to keep user logged in, until s/he clicks log-out link.
	If you are using some authentication library, login user with it instead.
	*/
	$_SESSION['user_name']=$user->name;
        $_SESSION['user_email'] =$user->email;
        $_SESSION['user_id']= $user->id;
        $_SESSION['validated_mobile']=$user->validated_mobile;
        $_SESSION['logged_in']=true;
}
?>