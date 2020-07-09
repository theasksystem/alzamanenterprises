<?php

session_start();
error_reporting(0);
include'functions/db.class.php';

if ($_SESSION['VENDOR_ID'] == '') {
    echo "<script>window.location.href='../vendors'</script>";
}

// get image id
$id = $_GET['id'];

// delete query
if (isset($id))
{
   	// $delQuery = $conn->prepare("DELETE FROM `product_images` WHERE `id` = :id");
//	 $delQuery->bindParam(':id', $id, PDO::PARAM_INT);
//	 $delQuery->execute();
	 
	$delimage = $conn->prepare("SELECT * FROM `product_images` WHERE `id` = :id");
	$delimage->bindParam(':id', $id, PDO::PARAM_INT);
	$delimage->execute();
	$imgval = $delimage->fetch(PDO::FETCH_ASSOC);

	$delCenter = $conn->prepare("DELETE FROM `product_images` WHERE `id` = :id");
	$delCenter->bindParam(':id', $id, PDO::PARAM_INT);
	$delCenter->execute();
	unlink('../adminuploads/product/'.$imgval['image']);
     
}

?>