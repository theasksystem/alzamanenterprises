<?

//initialize facebook sdk
require '../Facebook/autoload.php';

$app_id = '509513349698703';
$app_secret = '960035bb3c1881b6c053d21b9b136c7b';
$permissions = ['email']; // Optional permissions
$callbackurl = 'https://alzamanenterprises.com/ar/login-register';
$logoutUrl = 'logout.php';

$fb = new Facebook\Facebook([
 'app_id' => $app_id,
 'app_secret' => $app_secret,
 'default_graph_version' => 'v2.5',
]);


$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {
if (isset($_SESSION['facebook_access_token'])) {
$accessToken = $_SESSION['facebook_access_token'];
} else {
  $accessToken = $helper->getAccessToken();
}
} catch(Facebook\Exceptions\facebookResponseException $e) {
// When Graph returns an error
echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
// When validation fails or other local issues
echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
if (isset($accessToken)) {
if (isset($_SESSION['facebook_access_token'])) {
$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
} else {
// getting short-lived access token
$_SESSION['facebook_access_token'] = (string) $accessToken;
  // OAuth 2.0 client handler
$oAuth2Client = $fb->getOAuth2Client();
// Exchanges a short-lived access token for a long-lived one
$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
// setting default access token to be used in script
$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
}
// redirect the user to the profile page if it has "code" GET variable
if (isset($_GET['code'])) {
echo "<script>window.location.href='".$_SESSION['previous_page']."'</script>";
}
// getting basic info about user
try {
$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
$requestPicture = $fb->get('/me/picture?redirect=false&height=200'); //getting user picture
$picture = $requestPicture->getGraphUser();
$profile = $profile_request->getGraphUser();
$fbid = $profile->getProperty('id');           // To Get Facebook ID
$fbfullname = $profile->getProperty('name');   // To Get Facebook full name
$fbemail = $profile->getProperty('email');    //  To Get Facebook email
$fbgender = $profile->getProperty('gender');    //  To Get Facebook gender
$fbpic = "<img src='".$picture['url']."' class='img-rounded' width='60' height='60'/>";
# save the user nformation in session variable
$_SESSION['LOGIN_ID2'] = $fbid;
$_SESSION['LOGIN_NAME'] = $fbfullname;
$_SESSION['LOGIN_USER_EMAIL'] = $fbemail;
$_SESSION['LOGIN_USER_PROFILE'] = $fbpic;


	$_SESSION['LOGIN_ID2'] = $fbid;
	$_SESSION['LOGIN_USER_EMAIL'] = $fbemail;
	
	$googleouthloginsql = $conn->prepare("select id from registration where email = '".$_SESSION['LOGIN_USER_EMAIL']."' and visible=1");
	$googleouthloginsql->execute();
	$totalsaleamt = $googleouthloginsql->fetch(PDO::FETCH_ASSOC);
	if($googleouthloginsql->rowCount() > 0)
  	{
		$_SESSION['LOGIN_ID2'] = $fbid;
		$_SESSION['LOGIN_NAME'] = $fbfullname;
		$_SESSION['LOGIN_USER_EMAIL'] = $fbemail;
		$_SESSION['LOGIN_GENDER'] = $fbgender;
		
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
		$_SESSION['LOGIN_ID2'] = $fbid;
		$_SESSION['LOGIN_NAME'] = $fbfullname;
		$_SESSION['LOGIN_USER_EMAIL'] = $fbemail;
		$_SESSION['LOGIN_GENDER'] = $fbgender;
		
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


} catch(Facebook\Exceptions\FacebookResponseException $e) {
// When Graph returns an error
echo 'Graph returned an error: ' . $e->getMessage();
session_destroy();
// redirecting user back to app login page
header("Location: ./");
exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
// When validation fails or other local issues
echo 'Facebook SDK returned an error: ' . $e->getMessage();
exit;
}
} else {
// replace your website URL same as added in the developers.Facebook.com/apps e.g. if you used http instead of https and you used            
$loginUrl = $helper->getLoginUrl($callbackurl, $permissions);
//echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}

?>