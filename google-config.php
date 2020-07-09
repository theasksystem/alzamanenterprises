<?php

//Include Google Client Library for PHP autoload file
//require_once 'vendor/autoload.php';

require_once('Google/Google/autoload.php');

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('592129363368-l7pcla4c8k2kjv9iit9njbpq4g7639ed.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('LyBIpmkb-v4an2Lin94a0XuR');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('https://alzamanenterprises.com/login-register');

//
$google_client->addScope('email');

$google_client->addScope('profile');

?>