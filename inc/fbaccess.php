<?php
//Application Configurations
//APP ids would be changed to the live app configs
include_once 'settings.php';
//this would be changed and we would get the url from _POST to redirect the 
//user to where he initiated the authentication request
$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
if ($_SERVER["SERVER_PORT"] != "80")
{
    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} 
else 
{
    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
$site_url= $pageURL; 

try{
	include_once "facebook.php";
}catch(Exception $e){
	error_log($e);
}
// Create our application instance
$facebook = new Facebook(array(
	'appId'		=> $app_id,
	'secret'	=> $app_secret,
	));

// Get User ID
$user = $facebook->getUser();
// We may or may not have this data based 
// on whether the user is logged in.
// If we have a $user id here, it means we know 
// the user is logged into
// Facebook, but we don�t know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if($user){
//==================== Single query method ======================================
	try{
		// Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me');
	}catch(FacebookApiException $e){
		error_log($e);
		$user = NULL;
	}
//==================== Single query method ends =================================
}

if($user){
	// Get logout URL
	$logoutUrl = $facebook->getLogoutUrl();
}else{
	// Get login URL
	$loginUrl = $facebook->getLoginUrl(array(
		'scope'			=> 'publish_stream, email',
		'redirect_uri'	=> $site_url,
                'display' => 'popup',
		));
}

if($user){
	// Proceed knowing you have a logged in user who has a valid session.
//we need to insert the user in DB here
$result = $mysqli->query("select * from user where fbid= ".$user_profile["id"]);
if ($result->num_rows == 0)
{
 print_r($user_profile);
 $mysqli->query("insert into user (fbid, name, email) values (".$user_profile["id"].",".$user_profile["name"].",". $user_profile["email"].")");
}
$result = $mysqli->query("select * from user where fbid= ".$user_profile["id"]);
//========= Batch requests over the Facebook Graph API using the PHP-SDK ========
	// Save your method calls into an array
	//$queries = array(
	//	array('method' => 'GET', 'relative_url' => '/'.$user)
	//	,array('method' => 'GET', 'relative_url' => '/'.$user.'/home?limit=50'),
	//	array('method' => 'GET', 'relative_url' => '/'.$user.'/friends'),
	//	array('method' => 'GET', 'relative_url' => '/'.$user.'/photos?limit=6'),
	//	);

	// POST your queries to the batch endpoint on the graph.
	try{
	//	$batchResponse = $facebook->api('?batch='.json_encode($queries), 'POST');
	}catch(Exception $o){
		error_log($o);
	}

	//Return values are indexed in order of the original array, content is in ['body'] as a JSON
	//string. Decode for use as a PHP array.
	//$user_info		= json_decode($batchResponse[0]['body'], TRUE);
	//$feed			= json_decode($batchResponse[1]['body'], TRUE);
	//$friends_list	= json_decode($batchResponse[2]['body'], TRUE);
	//$photos			= json_decode($batchResponse[3]['body'], TRUE);
//========= Batch requests over the Facebook Graph API using the PHP-SDK ends =====

	// Update user's status using graph api (long versio)
	if(isset($_POST['pub'])){
		try{
			$statusUpdate = $facebook->api("/$user/feed", 'post', array(
				'message'		=> 'Opening message for the post ex: I found this great design for a t-shirt...check it out',
				'link'			=> 'link to a url that the user would get to',
				'picture'		=> 'Full qualified link to a pic logo/t-shirt design',
				'name'			=> 'The name of the website',
				'caption'		=> 'domain name',
				'description'	=> '   this should be the text describing the link itself should be decided',
				));
		}catch(FacebookApiException $e){
			error_log($e);
		}
	}

	// Update user's status using graph api
	if(isset($_POST['status'])){
		try{
			$statusUpdate = $facebook->api("/$user/feed", 'post', array('message'=> $_POST['status']));
		}catch(FacebookApiException $e){
			error_log($e);
		}
	}
}
?>