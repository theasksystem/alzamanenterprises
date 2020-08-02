<?php
    session_start();
    error_reporting(0);
    include('include/db.class.php');
    include('include/functions.php');
    
    $store_id= base64_decode(base64_decode(base64_decode($_GET['store'])));
    echo $store_id;
    $user_id = $_SESSION['LOGIN_ID'];
    $followdetail = $conn->prepare("INSERT into `tbl_follow`(`store_id`,`user_id`) VALUES (:store_id,:user_id)");
    $followdetail->bindParam(':store_id', $store_id, PDO::PARAM_INT);
    $followdetail->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $followdetail->execute();
    header("Location: {$_SERVER['HTTP_REFERER']}");
?>