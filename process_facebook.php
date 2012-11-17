<?php 
session_start(); 

/*
check our post variable from index.php, just to insure user isn't accessing this page directly.
You can replace this with strong function, something like HTTP_REFERER
*/
if(isset($_POST["connect"]) && $_POST["connect"]==1)
{       unset($_GET["logout"]);	
	include_once("settings.php"); //Include configuration file.
	
	//Call Facebook API
	if (!class_exists('FacebookApiException')) {
	require_once('inc/facebook.php' );
	}
		$facebook = new Facebook(array(
		'appId' => $app_id,
		'secret' => $app_secret,
	));
	
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
		}
	}
	
	// redirect user to facebook login page if empty data or fresh login requires
	if (!$fbuser){
		$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$site_url, false));
		header('Location: '.$loginUrl);
	}
	
	//user details
	$fullname = $me['name'];
	$email = $me['email'];
          
	//Check user id in our database
	$result = $mysqli->query("SELECT * FROM user WHERE fbid=$uid");
	if($result->num_rows > 0)
	{	
		//User exist, Show welcome back message
		echo 'Welcome back '. $me['first_name'] . ' '. $me['last_name'].'!';
		$row = $result->fetch_assoc();
		//print user facebook data
		//echo '<pre>';
		//print_r($me);
		//echo '</pre>';
		//User is now connected, log him in
                $_SESSION['user_id']=$row['id'];
		$_SESSION['logged_in']=true;
                $_SESSION['validated_mobile'] = $row["validated_mobile"];
                $_SESSION['role']= $row['role_id'];
	}
	else
	{
		//User is new, Show connected message and store info in our Database
		echo 'Hi '. $me['first_name'] . ' '. $me['last_name'].'!.';
		//print user facebook data
		echo '<pre>';
		print_r($me);
		echo '</pre>';
		// Insert user into Database.
		$mysqli->query("INSERT INTO user (fbid, name, email) VALUES ($uid, '$fullname','$email')");
		
		//User is now connected, log him in
                $_SESSION['user_id']= $mysqli->insert_id;
                $_SESSION['logged_in']=true;
                
	}
	$_SESSION['user_name']=$me['first_name'].' '.$me['last_name'];
        $_SESSION['user_email'] =$me['email'];
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