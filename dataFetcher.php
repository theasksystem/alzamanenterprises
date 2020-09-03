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

  if(isset($_POST['orderId']) && $_POST['orderId']!='' && isset($_POST['itemId']) && $_POST['itemId']!='')
  {
    $orderId = $_POST['orderId'];
    $itemId  = $_POST['itemId'];
    $query   = $conn->prepare("update cart_order_item set returned = 1 where order_id = '$orderId' and pid = '$itemId'");
    $query->execute();
    echo "success";
  }
?>