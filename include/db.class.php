<?php
ini_set('session.cookie_domain','.alzamanenterprises.com');
$servername = "localhost";
$username = "alzaman321_alzam";
$password = "PQFpdO4i0oXIaQ6g";
$dbname = "alzaman321_alzaman";
try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}



function dd($data){

    print_r($data); die;

}

function ADDSTR($data){

    $transform = addslashes(trim($data));

    return $transform;

}

$globaldate = date('Y-m-d');
$WebsiteTitle='Alzaman Enterprises';
$logo = 'images/logo.png';
$WebsiteUrl='https://alzamanenterprises.com';
$MainHomeUrl = 'https://alzamanenterprises.com/home.php';
$WebsiteUrl2='https://alzamanenterprises.com/ar';

$clientmail='alzamanent@gmail.com,gazinaeem6@gmail.com,info@alzamanenterprises.com';
$ordermail='alzamanent@gmail.com,order@alzamanenterprises.com';
$fromemail="mail@alzamanenterprises.com";
$messagefooterEmail="info@alzamanenterprises.com";
$copyright='';

include 'visitor_tracking.php';


?>