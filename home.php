<!DOCTYPE html>

<?php

session_start();

error_reporting(0);

include('include/db.class.php');

$sql = $conn->prepare("INSERT INTO `check_for_lang`(`ip_address`) VALUES ('".$_SERVER['REMOTE_ADDR']."')");
$sql->execute();

?>

<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Alzaman Enterprises is Qatar's Best Fashion and Lifestyle Online Shopping site for men, women & kids. Buy clothing, shoes, Watches, footwear and more from your...">

    <meta name="keywords" content="Alzaman Enterprises,Online shopping in Qatar,Shopping in Qatar,Shopping Qatar,Online shopping,Buy online,Shop online,Buy and sale in Qatar,Online website in Qatar">

    <meta name="author" content="Alzaman Enterprises">

    <title>Alzaman Enterprises</title>

     <link href="<?= $WebsiteUrl.'/'; ?>css/language.css" rel="stylesheet">

     <link href="<?= $WebsiteUrl.'/'; ?>css/bootstrap.css" rel="stylesheet">





   

</head>

     <section class="page-lang">

            <div class="container">

                <div class="row">

                    <div class="col-md-12 ">


                        <div class="lang-box2">
                    	<div class="logo">

                            <img src="images/logo.png">
                    	</div>

                        <div class="tag-newss">
                            <a href="<?=$WebsiteUrl; ?>" class="tag-newsss"> English</a>
                            <a href="<?=$WebsiteUrl2; ?>" class="tag-newsss"> Arabic</a>           
                        </div>

                        </div>


                    </div>

                </div>

            </div>

     </section>





    