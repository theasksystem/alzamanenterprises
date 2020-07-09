<?php
error_reporting(0);
session_start();

include'functions/db.class.php';
include('../include/functions.php');



if(isset($_POST['id']) && $_POST['id']!=''){

  $id = $_POST['id'];

  $data = OrderDetails($conn,$id);
    echo $data;
}
?>
