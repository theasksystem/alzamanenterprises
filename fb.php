<?php

session_start();
error_reporting(0);
include('include/db.class.php');

include 'fb-login-data.php';

$mmurl = htmlspecialchars($loginUrl);

header('location:"'.$mmurl.'"');