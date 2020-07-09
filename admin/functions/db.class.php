<?php
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
$logo = '<img src="../images/logo.png" style="width:182px;padding-top:9px;" />';
$logo2 = 'images/logo.png';
$WebsiteUrl='https://alzamanenterprises.com';
$fromemail="no-reply@alzamanenterprises.com";
$messagefooterEmail="info@techitsolution.in";
$copyright='';

$rejectOrderMsg = "";

?>