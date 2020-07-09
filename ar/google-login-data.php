<?

require_once ('../Google/Google/autoload.php');

//Insert your cient ID and secret 
//You can get it from : https://console.developers.google.com/
$client_id = '592129363368-l7pcla4c8k2kjv9iit9njbpq4g7639ed.apps.googleusercontent.com'; 
$client_secret = 'LyBIpmkb-v4an2Lin94a0XuR';
$redirect_uri = 'https://alzamanenterprises.com/ar/login-register';


//incase of logout request, just unset the session var
if (isset($_GET['logout'])) {
  unset($_SESSION['access_token']);
}

/************************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 ************************************************/
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

/************************************************
  When we create the service here, we pass the
  client to it. The client then queries the service
  for the required scopes, and uses that when
  generating the authentication URL later.
 ************************************************/
$service = new Google_Service_Oauth2($client);

/************************************************
  If we have a code back from the OAuth 2.0 flow,
  we need to exchange that with the authenticate()
  function. We store the resultant access token
  bundle in the session, and redirect to ourself.
*/
  
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
  exit;
}

/************************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 ************************************************/
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}


if (isset($authUrl)){ 
	
} else {
	
	$user = $service->userinfo->get(); //get user info
	$_SESSION['LOGIN_ID2'] = $user->id;
		$_SESSION['LOGIN_USER_EMAIL'] = $user->email;
	
	$googleouthloginsql = $conn->prepare("select id from registration where email = '".$_SESSION['LOGIN_USER_EMAIL']."' and visible=1");
	$googleouthloginsql->execute();
	$totalsaleamt = $googleouthloginsql->fetch(PDO::FETCH_ASSOC);
	if($googleouthloginsql->rowCount() > 0)
  	{
		$_SESSION['LOGIN_ID2'] = $user->id;
		$_SESSION['LOGIN_NAME'] = $user->name;
		$_SESSION['LOGIN_USER_EMAIL'] = $user->email;
		$_SESSION['LOGIN_GENDER'] = $user->gender;
		
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
		$_SESSION['LOGIN_ID2'] = $user->id;
		$_SESSION['LOGIN_NAME'] = $user->name;
		$_SESSION['LOGIN_USER_EMAIL'] = $user->email;
		$_SESSION['LOGIN_GENDER'] = $user->gender;
		
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

?>