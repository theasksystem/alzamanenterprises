<?php
//error_reporting(0);
include('include/db.class.php');
include('include/functions.php');

  if(isset($_POST['val']) && $_POST['val']!='' && isset($_POST['val2']) && $_POST['val2']!='')
  {
      $colorId = $_POST['val'];
      $productId = $_POST['val2'];
      $query = $conn->prepare("SELECT price FROM `product_images` WHERE product_id = '$productId' and color_id = '$colorId'");
      $query->execute();
      $price = $query->fetch(PDO::FETCH_ASSOC);
      $price = $price['price'];
      echo $price;
  }
?>