<?php

//error_reporting(0);

$zzz=0;
$disc=0;

$fromemail2 = 'no-reply@alzamanenterprises.com';

if(isset($_SESSION['orderid']) && $_SESSION['orderid']!='')
{

////////////////////get the response from payu///////////////////////////

$ordate = date("d M, Y");
$user_id = $_SESSION['LOGIN_ID'];

$imdd=$_SESSION['orderid'];

$dtatmasteruser = $conn->prepare("select * from registration where id = :id");
$dtatmasteruser->bindParam(':id', $user_id, PDO::PARAM_STR);
$dtatmasteruser->execute();
$dtatmasteruser_row = $dtatmasteruser->fetch(PDO::FETCH_ASSOC);

$dtatmasteruser2 = $conn->prepare("select * from tbl_address where id = :id");
$dtatmasteruser2->bindParam(':id', $_SESSION['ADDRESS'], PDO::PARAM_STR);
$dtatmasteruser2->execute();
$dtatmasteruser_row2 = $dtatmasteruser2->fetch(PDO::FETCH_ASSOC);

$country2 = $conn->prepare("select name_en from country where id = '".$dtatmasteruser_row2['country']."'");
$country2->execute();
$country3 = $country2->fetch(PDO::FETCH_ASSOC);
$country = $country3['name_en'];


$city2 = $conn->prepare("select name_en from city where id = '".$dtatmasteruser_row2['city']."'");
$city2->execute();
$city3 = $city2->fetch(PDO::FETCH_ASSOC);
$city = $city3['name_en'];


$shipdata2 = $conn->prepare("select payment_mode,ship_charge,coupan_value,total from cart_orders where id = '$imdd'");
$shipdata2->execute();
$shipdata = $shipdata2->fetch(PDO::FETCH_ASSOC);
$payment_method2 = $shipdata['payment_mode'];
if($payment_method2==1){
	$payment_method = 'Cash on Delievery';
}
if($payment_method2==2){
	$payment_method = 'Store Pick Up';
}
$shippingcharg = $shipdata['ship_charge'];
if($shippingcharg==0){ $shippingcharg2 = 'Free Shipping'; }else{ $shippingcharg2 = 'QAR '.$shippingcharg; }
if($shipdata['coupan_value']!=0 && $shipdata['coupan_value']!=''){ $coupan = $shipdata['coupan_value']; }else{ $coupan = ''; }


$order_id = 'ALZ-'.$_SESSION['orderid'];
$fullname = ucfirst($dtatmasteruser_row['name']).' '.ucfirst($dtatmasteruser_row['lastname']);


	$message="<table width='600' style='border:2px solid #929090;'>

		<tr>
			<td align='left'><img src='".$WebsiteUrl."/".$logo."' width='300' height='97'></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align='left'>
				<table style='width:100%;'>
				<tr>
					<td colspan='3' align='left'>Dear ".$fullname."</td>
				</tr>

				<tr>
					<td colspan='3' align='left' >&nbsp;</td>
				</tr>

				<tr>
					<td colspan='3' align='left'>we have received your booking no. <font color=#990000 size=2 face=Arial, Helvetica, sans-serif><strong>".strtoupper($order_id)."</strong></font> booked on ".$ordate.".</td>
				</tr>

				<tr>
					<td colspan='3' align='left' >&nbsp;</td>
				</tr>
         		<tr>
         			<td colspan='3' align='left'>Your Booking details are below.</td>
        		 </tr>

				<tr>
					<td colspan='3' align='left' >&nbsp;</td>
				</tr>


				<tr>
					<td>Billing Name</td><td>:</td><td>".$fullname."</td>
				</tr>
				<tr>
					<td>Billing Mobile no</td><td>:</td><td>".$dtatmasteruser_row['phone']."</td>
				</tr>
				<tr>
					<td>E-mail</td><td>:</td><td>".$dtatmasteruser_row['email']."</td>
				</tr>
				<tr>
					<td>Shipping Name</td><td>:</td><td>".$dtatmasteruser_row2['name']."</td>
				</tr>
				<tr>
					<td>Shipping Mobile No</td><td>:</td><td>".$dtatmasteruser_row2['alt_mobile']."</td>
				</tr>
				<tr>
					<td>Building No</td><td>:</td><td>".$dtatmasteruser_row2['state']."</td>
				</tr>
				<tr>
					<td>Zone</td><td>:</td><td>".$dtatmasteruser_row2['zip']."</td>
				</tr>
				<tr>
					<td>Street</td><td>:</td><td>".$dtatmasteruser_row2['address']."</td>
				</tr>
				<tr>
					<td>City</td><td>:</td><td>".$city."</td>
				</tr>
				<tr>
					<td>Country</td><td>:</td><td>".$country."</td>
				</tr>
				<tr>
					<td colspan='3' align='left'>&nbsp;</td>
				</tr>

				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='3'><table width='646' border='0' cellpadding='0' cellspacing='0' bordercolor='#FFFFFF' style='border:1px solid #CCCCCC'>
        <tr bgcolor='929090'>
		  <td width='100' align='center' style='color:#fff;'>Product Order ID</td>
		  <td width='100' align='center' style='color:#fff;'>Product</td>
          <td width='100' align='center' style='color:#fff;'>Name</td>
          <td width='100' align='center' style='color:#fff;'>Unit Price</td>
          <td width='50' align='center' style='color:#fff;'>Qty</td>
          <td width='146'  align='center' style='color:#fff;'>Subtotal</td>
        </tr>";

			
			$total_voucher="";
			$subt="";
			$i=1;
            $total = $shipdata['total'];
					$CARTTOTAL = 0;

					$maincartquery = $conn->prepare( "select c.*, p.product_name_en,p.user_id, p.image from cart_order_item c LEFT JOIN products p ON p.id = c.pid where c.order_id = '$imdd'");
					$maincartquery->execute();

					while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC))
					{
					$size2 = $conn->prepare("select size from products_size where id = '".$maincart['size_id']."'");
					$size2->execute();
					$size3 = $size2->fetch(PDO::FETCH_ASSOC);
					$size = $size3['size'];
					
					$color2 = $conn->prepare("select color from products_color where id = '".$maincart['color_id']."'");
					$color2->execute();
					$color3 = $color2->fetch(PDO::FETCH_ASSOC);
					$color = $color3['color'];
					if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
					if($color!=''){ $color2=$color; }else{ $color2='N/A'; } 
					
					$order_id2 = 'ALZ-'.$maincart['user_id'].'-'.$maincart['id'];
						
					  $image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];
					
						$subtotal2 =  $maincart['price']*$maincart['qty'];
						$subtotal  =  $subtotal2; 
						 
						
						
						

        $message=$message."<tr>
		  <td align='center'>".$order_id2."</td>
		  <td align='center'><img src='".$image."' height='70' width='80' style='margin-bottom:5px;' /></td>
          <td align='center'>".stripslashes($maincart['product_name_en'])."<br>(Size: ".$size2.")<br>(Color: ".$color2.")</td>
          <td align='center'>QAR ".$p_price."</td>
          <td align='center'>".$maincart['qty']."</td>
          <td align='center'>QAR ".$subtotal."</td>
        </tr>";
	}
		$message=$message."<tr><td height='30' colspan='7'>&nbsp;</td></tr>
			<tr><td height='30' colspan='7' align='center' bgcolor='929090' style='color:#fff;'>Your Order ID: <strong>".strtoupper($order_id)."</strong></td></tr>
      		</table></td>
		</tr>
		<tr>
			<td align='right'>Order Total : <strong>".$total."</strong></td>
		</tr>
		<tr>
			<td align='right'>Payment Mode : <strong>".$payment_method."</strong></td>
		</tr>
		<tr>
			<td align='right'>Shipping Charge : <strong>".$shippingcharg2."</strong></td>
		</tr>";
		
      if($coupan!=''){ $message = $message."<tr><td align='right'>Coupan Value : QAR <strong>".$coupan."</strong></td></tr>
      
                                                <tr><td align='right'>Total : QAR <strong>".($total-$coupan)."</strong></td></tr>"; }else{            
      $message = $message."<tr><td>Total Payable Amount : QAR <strong>".$total."</strong></td></tr>"; }
	
		$message=$message."<tr>
		<td align='left'>We hope you enjoyed shopping at ".$WebsiteUrl."</td>

		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align='left'>Team <a href='".$WebsiteUrl."'>".$WebsiteTitle."</a></td>
		</tr>
		</table>";

	    $headers  = "From: ".$WebsiteTitle." < ".$fromemail2." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail2." >\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail2."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		$usermail=$dtatmasteruser_row['email'];
		$to=$ordermail;
	
		$adminsubject="New order placed - QAR ".$total;
		$subject="Your order # ".$order_id." with ".$WebsiteTitle."";
		
		mail($to, $adminsubject, $message, $headers);
		
        mail($usermail, $subject, $message, $headers);

		
		
		}

?>
