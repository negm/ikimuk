<?php 
session_start(); 

/*
check our post variable from index.php, just to insure user isn't accessing this page directly.
You can replace this with strong function, something like HTTP_REFERER
*/
if(isset($_POST["connect"]) && $_POST["connect"]==1)
{       unset($_GET["logout"]);	
	require_once("class/settings.php"); //Include configuration file.
        require_once 'class/class.user.php';
	$settings = new settings();
        $user =  new user();
	//Call Facebook API
	if (!class_exists('FacebookApiException')) {
	require_once('inc/facebook.php' );
	}
		$facebook = new Facebook(array(
		'appId' => $settings->app_id,
		'secret' => $settings->app_secret,
	));
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
		//header('Location: '.$loginUrl);
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
		
		//print user facebook data
		//echo '<pre>';
		//print_r($me);
		//echo '</pre>';
		//User is now connected, log him in
                $_SESSION['user_id']=$user->id;
		$_SESSION['logged_in']=true;
                $_SESSION['validated_mobile'] = $user->validated_mobile;
                $_SESSION['role']= $user->role_id;
	}
	else
	{
		//User is new, Show connected message and store info in our Database
		echo "Hi $user->name!.";
		//print user facebook data
                //echo "<pre>';
                //print_r($me);
                //echo '</pre>';
		// Insert user into Database.
		$user->insert();
		//User is now connected, log him in
                $_SESSION['user_id']= $user->id;
                $_SESSION['logged_in']=true;
                
	}
	$_SESSION['user_name']=$user->name;
        $_SESSION['user_email'] =$user->email;
        $_SESSION["access_token"] = $facebook->getAccessToken();
}

function login_user($loggedin,$user_name)
{
	/*
	function stores some session variables to imitate user login. 
	We will use these session variables to keep user logged in, until s/he clicks log-out link.
	If you are using some authentication library, login user with it instead.
	*/
	$_SESSION['logged_in']=$loggedin;
	$_SESSION['user_name']=$user_name;
}
?>