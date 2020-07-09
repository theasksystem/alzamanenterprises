<?php
error_reporting(0);
session_start();
include'functions/db.class.php';

if($_SESSION['USER_ID']=='')
{
  echo "<script>window.location='index.php';</script>";
}
$id = base64_decode($_POST['id']);
$uid = base64_decode($_POST['uid']);
$addId = base64_decode($_POST['aid']);
$reason = $_POST['reason'];


if (isset($_POST['status']) && $_POST['status']=='pending')
{
  $result = $conn->prepare("update cart_orders set status = :status  where id = :id");
	$result->bindValue(':status', 'pending', PDO::PARAM_STR);
	$result->bindValue(':id', $id, PDO::PARAM_STR);
	$result->execute();
    if($result== true){
        echo 1;
    }
    else{
        echo 0;
    }
}


if (isset($_POST['status']) && $_POST['status']=='rejected')
{
  $result = $conn->prepare("update cart_orders set status = :status, reason= :reason  where id = :id");
	$result->bindValue(':status', 'rejected', PDO::PARAM_STR);
	$result->bindValue(':reason', $reason, PDO::PARAM_STR);
	$result->bindValue(':id', $id, PDO::PARAM_STR);
	$result->execute();
	
	////////////////////Complition Email MSG///////////////////////////

$ordate = date("d M, Y");
$user_id = $uid;

$payment_mode = $payment;

$imdd=$id;

$dtatmasteruser = $conn->prepare("select * from registration where id = :id");
$dtatmasteruser->bindParam(':id', $user_id, PDO::PARAM_STR);
$dtatmasteruser->execute();
$dtatmasteruser_row = $dtatmasteruser->fetch(PDO::FETCH_ASSOC);

$dtatmasteruser2 = $conn->prepare("select * from tbl_address where id = :id");
$dtatmasteruser2->bindParam(':id', $addId, PDO::PARAM_STR);
$dtatmasteruser2->execute();
$dtatmasteruser_row2 = $dtatmasteruser2->fetch(PDO::FETCH_ASSOC);

$country2 = $conn->prepare("select name_en from country where id = '".$dtatmasteruser_row2['country']."'");
$country2->execute();
$country3 = $country2->fetch(PDO::FETCH_ASSOC);
$country = $country3['name_en'];

$state2 = $conn->prepare("select name_en from state where id = '".$dtatmasteruser_row2['state']."'");
$state2->execute();
$state3 = $state2->fetch(PDO::FETCH_ASSOC);
$state = $state3['name_en'];

$city2 = $conn->prepare("select name_en from city where id = '".$dtatmasteruser_row2['city']."'");
$city2->execute();
$city3 = $city2->fetch(PDO::FETCH_ASSOC);
$city = $city3['name_en'];


$shipdata2 = $conn->prepare("select c.payment_mode,c.size_id,c.status,c.color_id, p.user_id from cart_orders c LEFT JOIN products p ON p.id = c.pid where c.id = '$imdd'");
$shipdata2->execute();
$shipdata = $shipdata2->fetch(PDO::FETCH_ASSOC);
$payment_method2 = $shipdata['payment_mode'];
if($payment_method2==1){
	$payment_method = 'Cash on Delievery';
}
if($payment_method2==2){
	$payment_method = 'Store Pick Up';
}
$order_id = 'ALZ-'.$shipdata['user_id'].'-'.$imdd;
$fullname = ucfirst($dtatmasteruser_row['name']).' '.ucfirst($dtatmasteruser_row['lastname']);


$size2 = $conn->prepare("select size from products_size where id = '".$shipdata['size_id']."'");
$size2->execute();
$size3 = $size2->fetch(PDO::FETCH_ASSOC);
$size = $size3['size'];

$color2 = $conn->prepare("select color from products_color where id = '".$shipdata['color_id']."'");
$color2->execute();
$color3 = $color2->fetch(PDO::FETCH_ASSOC);
$color = $color3['color'];
if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
if($color!=''){ $color2=$color; }else{ $color2='N/A'; } 

	$message="<table width='600' style='border:2px solid #929090;'>

		<tr>
			<td align='left'><img src='".$WebsiteUrl."/".$logo2."' width='300' height='97'></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>";

		$message=$message."<tr>
			<td align='left'>
				<table style='width:100%;'>
				<tr>
					<td colspan='3' align='left'>Dear ".$fullname."</td>
				</tr>
                <tr>
					<td colspan='3' align='left'>Reason : ".$reason."</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>";
		
		$headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "Reply-To: no-reply@alzamanenterprises.com" . "\r\n" ;
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";

		
		$usermail=$dtatmasteruser_row['email'];
		$subject="Your order # ".$order_id." with ".$WebsiteTitle." is Rejected.";
        mail($usermail, $subject, $message, $headers);
    
    header('location:reject_list.php');
}

if (isset($_POST['status']) && $_POST['status']=='approved')
{
    $result = $conn->prepare("update cart_orders set status = :status  where id = :id");
	$result->bindValue(':status', 'approved', PDO::PARAM_STR);
	$result->bindValue(':id', $id, PDO::PARAM_STR);
	$result->execute();
	
////////////////////Complition Email MSG///////////////////////////

$ordate = date("d M, Y");
$user_id = $uid;

$payment_mode = $payment;

$imdd=$id;

$dtatmasteruser = $conn->prepare("select * from registration where id = :id");
$dtatmasteruser->bindParam(':id', $user_id, PDO::PARAM_STR);
$dtatmasteruser->execute();
$dtatmasteruser_row = $dtatmasteruser->fetch(PDO::FETCH_ASSOC);

$dtatmasteruser2 = $conn->prepare("select * from tbl_address where id = :id");
$dtatmasteruser2->bindParam(':id', $addId, PDO::PARAM_STR);
$dtatmasteruser2->execute();
$dtatmasteruser_row2 = $dtatmasteruser2->fetch(PDO::FETCH_ASSOC);

$country2 = $conn->prepare("select name_en from country where id = '".$dtatmasteruser_row2['country']."'");
$country2->execute();
$country3 = $country2->fetch(PDO::FETCH_ASSOC);
$country = $country3['name_en'];

$state2 = $conn->prepare("select name_en from state where id = '".$dtatmasteruser_row2['state']."'");
$state2->execute();
$state3 = $state2->fetch(PDO::FETCH_ASSOC);
$state = $state3['name_en'];

$city2 = $conn->prepare("select name_en from city where id = '".$dtatmasteruser_row2['city']."'");
$city2->execute();
$city3 = $city2->fetch(PDO::FETCH_ASSOC);
$city = $city3['name_en'];


$shipdata2 = $conn->prepare("select c.payment_mode,c.size_id,c.color_id, p.user_id from cart_orders c LEFT JOIN products p ON p.id = c.pid where c.id = '$imdd'");
$shipdata2->execute();
$shipdata = $shipdata2->fetch(PDO::FETCH_ASSOC);
$payment_method2 = $shipdata['payment_mode'];
if($payment_method2==1){
	$payment_method = 'Cash on Delievery';
}
if($payment_method2==2){
	$payment_method = 'Store Pick Up';
}
$order_id = 'ALZ-'.$shipdata['user_id'].'-'.$imdd;
$fullname = ucfirst($dtatmasteruser_row['name']).' '.ucfirst($dtatmasteruser_row['lastname']);


$size2 = $conn->prepare("select size from products_size where id = '".$shipdata['size_id']."'");
$size2->execute();
$size3 = $size2->fetch(PDO::FETCH_ASSOC);
$size = $size3['size'];

$color2 = $conn->prepare("select color from products_color where id = '".$shipdata['color_id']."'");
$color2->execute();
$color3 = $color2->fetch(PDO::FETCH_ASSOC);
$color = $color3['color'];
if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
if($color!=''){ $color2=$color; }else{ $color2='N/A'; } 

	$message="<table width='600' style='border:2px solid #929090;'>

		<tr>
			<td align='left'><img src='".$WebsiteUrl."/".$logo2."' width='300' height='97'></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>";

		
		$message=$message."<tr>
			<td align='left'>
				<table style='width:100%;'>
				<tr>
					<td colspan='3' align='left'>Dear ".$fullname."</td>
				</tr>
                <tr>
					<td colspan='3' align='left'>Your Order is Confirmed. We will let you know when your order is on the way.</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>";


	    $headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "Reply-To: no-reply@alzamanenterprises.com" . "\r\n" ;
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";

		
		$usermail=$dtatmasteruser_row['email'];
		$subject="Your order # ".$order_id." with ".$WebsiteTitle."";
        mail($usermail, $subject, $message, $headers);

	
  if($result== true){
        echo 1;
    }
    else{
        echo 0;
    }
}


if (isset($_POST['status']) && $_POST['status']=='on the way')
{
  $result = $conn->prepare("update cart_orders set status = :status  where id = :id");
	$result->bindValue(':status', 'on the way', PDO::PARAM_STR);
	$result->bindValue(':id', $id, PDO::PARAM_STR);
	$result->execute();
	

////////////////////Complition Email MSG///////////////////////////

$ordate = date("d M, Y");
$user_id = $uid;

$payment_mode = $payment;

$imdd=$id;

$dtatmasteruser = $conn->prepare("select * from registration where id = :id");
$dtatmasteruser->bindParam(':id', $user_id, PDO::PARAM_STR);
$dtatmasteruser->execute();
$dtatmasteruser_row = $dtatmasteruser->fetch(PDO::FETCH_ASSOC);

$dtatmasteruser2 = $conn->prepare("select * from tbl_address where id = :id");
$dtatmasteruser2->bindParam(':id', $addId, PDO::PARAM_STR);
$dtatmasteruser2->execute();
$dtatmasteruser_row2 = $dtatmasteruser2->fetch(PDO::FETCH_ASSOC);

$country2 = $conn->prepare("select name_en from country where id = '".$dtatmasteruser_row2['country']."'");
$country2->execute();
$country3 = $country2->fetch(PDO::FETCH_ASSOC);
$country = $country3['name_en'];

$state2 = $conn->prepare("select name_en from state where id = '".$dtatmasteruser_row2['state']."'");
$state2->execute();
$state3 = $state2->fetch(PDO::FETCH_ASSOC);
$state = $state3['name_en'];

$city2 = $conn->prepare("select name_en from city where id = '".$dtatmasteruser_row2['city']."'");
$city2->execute();
$city3 = $city2->fetch(PDO::FETCH_ASSOC);
$city = $city3['name_en'];


$shipdata2 = $conn->prepare("select c.payment_mode,c.size_id,c.status,c.color_id, p.user_id from cart_orders c LEFT JOIN products p ON p.id = c.pid where c.id = '$imdd'");
$shipdata2->execute();
$shipdata = $shipdata2->fetch(PDO::FETCH_ASSOC);
$payment_method2 = $shipdata['payment_mode'];
if($payment_method2==1){
	$payment_method = 'Cash on Delievery';
}
if($payment_method2==2){
	$payment_method = 'Store Pick Up';
}
$order_id = 'ALZ-'.$shipdata['user_id'].'-'.$imdd;
$fullname = ucfirst($dtatmasteruser_row['name']).' '.ucfirst($dtatmasteruser_row['lastname']);


$size2 = $conn->prepare("select size from products_size where id = '".$shipdata['size_id']."'");
$size2->execute();
$size3 = $size2->fetch(PDO::FETCH_ASSOC);
$size = $size3['size'];

$color2 = $conn->prepare("select color from products_color where id = '".$shipdata['color_id']."'");
$color2->execute();
$color3 = $color2->fetch(PDO::FETCH_ASSOC);
$color = $color3['color'];
if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
if($color!=''){ $color2=$color; }else{ $color2='N/A'; } 

	$message="<table width='600' style='border:2px solid #929090;'>

		<tr>
			<td align='left'><img src='".$WebsiteUrl."/".$logo2."' width='300' height='97'></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>";

		$message=$message."<tr>
			<td align='left'>
				<table style='width:100%;'>
				<tr>
					<td colspan='3' align='left'>Dear ".$fullname."</td>
				</tr>
                <tr>
					<td colspan='3' align='left'>Your order is on the way.</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>";
		
		$headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "Reply-To: no-reply@alzamanenterprises.com" . "\r\n" ;
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";

		
		$usermail=$dtatmasteruser_row['email'];
		$subject="Your order # ".$order_id." with ".$WebsiteTitle."";
        mail($usermail, $subject, $message, $headers);
		if($result== true){
        echo 1;
    }
    else{
        echo 0;
    }
}

if (isset($_POST['status']) && $_POST['status']=='completed')
{
  $result = $conn->prepare("update cart_orders set status = :status  where id = :id");
	$result->bindValue(':status', 'completed', PDO::PARAM_STR);
	$result->bindValue(':id', $id, PDO::PARAM_STR);
	$result->execute();
	

////////////////////Complition Email MSG///////////////////////////

$ordate = date("d M, Y");
$user_id = $uid;

$payment_mode = $payment;

$imdd=$id;

$dtatmasteruser = $conn->prepare("select * from registration where id = :id");
$dtatmasteruser->bindParam(':id', $user_id, PDO::PARAM_STR);
$dtatmasteruser->execute();
$dtatmasteruser_row = $dtatmasteruser->fetch(PDO::FETCH_ASSOC);

$dtatmasteruser2 = $conn->prepare("select * from tbl_address where id = :id");
$dtatmasteruser2->bindParam(':id', $addId, PDO::PARAM_STR);
$dtatmasteruser2->execute();
$dtatmasteruser_row2 = $dtatmasteruser2->fetch(PDO::FETCH_ASSOC);

$country2 = $conn->prepare("select name_en from country where id = '".$dtatmasteruser_row2['country']."'");
$country2->execute();
$country3 = $country2->fetch(PDO::FETCH_ASSOC);
$country = $country3['name_en'];

$state2 = $conn->prepare("select name_en from state where id = '".$dtatmasteruser_row2['state']."'");
$state2->execute();
$state3 = $state2->fetch(PDO::FETCH_ASSOC);
$state = $state3['name_en'];

$city2 = $conn->prepare("select name_en from city where id = '".$dtatmasteruser_row2['city']."'");
$city2->execute();
$city3 = $city2->fetch(PDO::FETCH_ASSOC);
$city = $city3['name_en'];


$shipdata2 = $conn->prepare("select c.payment_mode,c.size_id,c.color_id, p.user_id, p.id from cart_orders c LEFT JOIN products p ON p.id = c.pid where c.id = '$imdd'");
$shipdata2->execute();
$shipdata = $shipdata2->fetch(PDO::FETCH_ASSOC);
$payment_method2 = $shipdata['payment_mode'];
if($payment_method2==1){
	$payment_method = 'Cash on Delievery';
}
if($payment_method2==2){
	$payment_method = 'Store Pick Up';
}
$order_id = 'ALZ-'.$shipdata['user_id'].'-'.$imdd;
$fullname = ucfirst($dtatmasteruser_row['name']).' '.ucfirst($dtatmasteruser_row['lastname']);

$ProductUrl = $WebsiteUrl.'/product-review.php?reviewId='.base64_encode(base64_encode($shipdata['id'])).'&uid='.base64_encode(base64_encode($user_id));


$size2 = $conn->prepare("select size from products_size where id = '".$shipdata['size_id']."'");
$size2->execute();
$size3 = $size2->fetch(PDO::FETCH_ASSOC);
$size = $size3['size'];

$color2 = $conn->prepare("select color from products_color where id = '".$shipdata['color_id']."'");
$color2->execute();
$color3 = $color2->fetch(PDO::FETCH_ASSOC);
$color = $color3['color'];
if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
if($color!=''){ $color2=$color; }else{ $color2='N/A'; } 

	$message="<table width='600' style='border:2px solid #929090;'>

		<tr>
			<td align='left'><img src='".$WebsiteUrl."/".$logo2."' width='300' height='97'></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>";
		
		$message=$message."<tr>
			<td align='left'>
				<table style='width:100%;'>
				<tr>
					<td colspan='3' align='left'>Dear ".$fullname."</td>
				</tr>
                <tr>
					<td colspan='3' align='left'>your order has been delivered successfully, We hope you are happy with your purchase. 
			If you like our service and products please give us positive <a href='".$ProductUrl."'>Review and Rating</a>. In case of any inconvenience you can 
			contact us directly before giving negative review Thank you and we are honor to serve you.</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>";
		
		
		$headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "Reply-To: no-reply@alzamanenterprises.com" . "\r\n" ;
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";

		
		$usermail=$dtatmasteruser_row['email'];
		$subject="Your order # ".$order_id." with ".$WebsiteTitle."";
        mail($usermail, $subject, $message, $headers);
		if($result== true){
        echo 1;
        }
        else{
            echo 0;
        }
}




