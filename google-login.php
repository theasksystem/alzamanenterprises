<?php

//Include Configuration File
include('google-config.php');

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  
  //header('Location: login-register');
  
  $_SESSION['LOGIN_ID2'] = $data['id'];
  $_SESSION['LOGIN_USER_EMAIL'] = $data['email'];
	
	$googleouthloginsql = $conn->prepare("select id from registration where email = '".$_SESSION['LOGIN_USER_EMAIL']."' and visible=1");
	$googleouthloginsql->execute();
	$totalsaleamt = $googleouthloginsql->fetch(PDO::FETCH_ASSOC);
	if($googleouthloginsql->rowCount() > 0)
  	{
		$_SESSION['LOGIN_ID2'] = $data['id'];
		$_SESSION['LOGIN_NAME'] = $data['given_name'];
		$_SESSION['LOGIN_USER_EMAIL'] = $data['email'];
		$_SESSION['LOGIN_GENDER'] = $data['gender'];
		
		$updateOuthUid = $conn->prepare("UPDATE `registration` SET `name` = '".$_SESSION['LOGIN_NAME']."', `lastname` = '', `email` = '".$_SESSION['LOGIN_USER_EMAIL']."', `oauth_uid` = '".$_SESSION['LOGIN_ID2']."' WHERE `email` = '".$_SESSION['LOGIN_USER_EMAIL']."'");
		$updateOuthUid->execute();
		
		if($updateOuthUid== true){
		    $_SESSION['LOGIN_ID'] = $totalsaleamt['id'];
			echo "<script>window.location.href='".$_SESSION['previous_page']."'</script>";
		}else{
			session_destroy();
			header('Location: login-register');
		}
	
	}else{
		$_SESSION['LOGIN_ID2'] = $data['id'];
		$_SESSION['LOGIN_NAME'] = $data['given_name'];
		$_SESSION['LOGIN_USER_EMAIL'] = $data['email'];
		$_SESSION['LOGIN_GENDER'] = $data['gender'];
		
		$insertOuthUid = $conn->prepare("INSERT INTO `registration`(`name`, `gender`, `email`, `oauth_uid`, `created_at`, `visible`) VALUES ('".$_SESSION['LOGIN_NAME']."', '".$_SESSION['LOGIN_GENDER']."', '".$_SESSION['LOGIN_USER_EMAIL']."', '".$_SESSION['LOGIN_ID2']."', NOW(), '1')");
		$insertOuthUid->execute();
		$myidd = $conn->lastInsertId();
		
		if($insertOuthUid== true){
		    $_SESSION['LOGIN_ID'] = $myidd;
			echo "<script>window.location.href='".$_SESSION['previous_page']."'</script>";
		}else{
			session_destroy();
			header('Location: login-register');
		}
		
	}
	

  
  
 }
}

//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if(!isset($_SESSION['access_token']))
{
 //Create a URL to obtain user authorization
 $login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="sign-in-with-google.png" /></a>';
}

?>