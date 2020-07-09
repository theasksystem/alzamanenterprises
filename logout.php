<?php



error_reporting(0);

session_start();

unset($_SESSION['LOGIN_ID']);
unset($_SESSION['LOGIN_NAME']);
unset($_SESSION['LOGIN_ID2']);
unset($_SESSION['LOGIN_USER_EMAIL']);
unset($_SESSION['LOGIN_GENDER']);
unset($_SESSION['access_token']);
unset($_SESSION['facebook_access_token']);



echo "<script>window.location.href='login-register'</script>";



?>

