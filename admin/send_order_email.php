<?php

error_reporting(0);

 // ini_set("display_errors", "1");
 // error_reporting(E_ALL);

//session_start();


$zzz=0;
$disc=0;

////////////////////get the response from payu///////////////////////////

$ordate = date("d M, Y");
$user_id = $uid;

$payment_mode = 'COD';
$order_id = 'DSUPL-00-'.$id;

$dtatmasteruser = $conn->prepare("select * from registration where id = :id");
$dtatmasteruser->bindParam(':id', $user_id, PDO::PARAM_STR);
$dtatmasteruser->execute();
$dtatmasteruser_row = $dtatmasteruser->fetch(PDO::FETCH_ASSOC);

$fname = ucfirst($dtatmasteruser_row['f_name']);
$lname = ucfirst($dtatmasteruser_row['l_name']);

$fullname = $fname.' '.$lname;

	$subject="Your order # ".$order_id." has been dispatched. Below is your order details";

	$message="<table width='600' style='border:2px solid #929090;'>

		<tr>
			<td align='left'><img src='http://dsquarreuniforms.com/shop/img/logo.png' width='300' height='97'></td>
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
					<td colspan='3' align='left'>Thank you for shopping with Dsquarre Uniforms. we have received your order no. <font color=#990000 size=2 face=Arial, Helvetica, sans-serif><strong>".strtoupper($order_id)."</strong></font> placed on ".$ordate.".</td>
				</tr>

				<tr>
					<td colspan='3' align='left' >&nbsp;</td>
				</tr>
         		<tr>
         			<td colspan='3' align='left'>Your Shipping details are below.</td>
        		 </tr>

				<tr>
					<td colspan='3' align='left' >&nbsp;</td>
				</tr>

				<tr>
					<td>PayMode</td><td>:</td><td>".$payment_mode."</td>
				</tr>

				<tr>
					<td>Name</td><td>:</td><td>".$fullname."</td>
				</tr>
				<tr>
					<td>Mobile no</td><td>:</td><td>".$dtatmasteruser_row['phone']."</td>
				</tr>
				<tr>
					<td>E-mail</td><td>:</td><td>".$dtatmasteruser_row['email']."</td>
				</tr>
				<tr>
					<td>Address</td><td>:</td><td>".$dtatmasteruser_row['address']."</td>
				</tr>
				<tr>
					<td>City</td><td>:</td><td>".$dtatmasteruser_row['city']."</td>
				</tr>
				<tr>
					<td>State</td><td>:</td><td>".$dtatmasteruser_row['state']."</td>
				</tr>
				<tr>
					<td>Postal Code</td><td>:</td><td>".$dtatmasteruser_row['zip']."</td>
				</tr>
				<tr>
					<td colspan='3' align='left'>&nbsp;</td>
				</tr>
				<tr>
					<td>Courier Company Name</td><td>:</td><td>".$crname."</td>
				</tr>
				<tr>
					<td>Courier Id</td><td>:</td><td>".$crid."</td>
				</tr>
				<tr>
					<td>Courier Date</td><td>:</td><td>".date('d-m-Y', strtotime($crdate))."</td>
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

		  		<td width='100' align='center' style='color:#fff;'>Product</td>
          <td width='100' align='center' style='color:#fff;'>Name</td>
					<td width='100' align='center' style='color:#fff;'>House</td>
					<td width='100' align='center' style='color:#fff;'>Size</td>
          <td width='100' align='center' style='color:#fff;'>Unit Price</td>
          <td width='50' align='center' style='color:#fff;'>Qty</td>
          <td width='146'  align='center' style='color:#fff;'>Subtotal</td>
        </tr>";

			$total="";
			$total_voucher="";
			$subt="";
			$i=1;

					$CARTTOTAL = 0;

					$maincartquery = $conn->prepare( "select c.*, s.size as productsize, p.name, p.color, p.image, o.name as school
													from cart c LEFT JOIN product p
													ON p.id = c.pid
													LEFT JOIN size_wice_products s
													ON s.id = c.size_id
													LEFT JOIN schools o
												  ON o.id = p.sid
													where c.un_id = '".$_SESSION['UNIQUEID']."'");
					$maincartquery->execute();

					while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC))
					{
            $house = '';

						if (file_exists('dresses/'.$maincart['image'])) {

							$image = $maincart['image'];

						}else{

							$image = 'no_image.jpg';
						}

						$p_price  = $maincart['price'];
						$subt = $p_price*$maincart['qty'];

						$subtotal =  $maincart['price']*$maincart['qty'];
						$total+= $subtotal;

						if($maincart['house']!='NULL' && $maincart['house']!='')
						{
					    $house = $maincart['house'];
				 	  }

        $message=$message."<tr>

		      <td align='center'><img src='http://dsquarreuniforms.com/shop/dresses/".$image."' height='70' width='80' style='margin-bottom:5px;' /><br>".stripslashes($maincart['school'])."</td>
          <td align='center'>".stripslashes($maincart['name'])."</td>
				  <td align='center'>".$house."</td>
					<td align='center'>".$maincart['productsize']."</td>
          <td align='center'>Rs&nbsp;".$p_price."</td>
          <td align='center'>".$maincart['qty']."</td>
          <td align='center'>".$subt."</td>
        </tr>";
										 }

        $subtotal = $total;
		if($subtotal > 2000){ $ship='00'; }else{ $ship='150'; }
        $shippingcost = $ship;
				$total = $total + $ship;

			$message=$message."<tr><td height='30' colspan='7'>&nbsp;</td></tr>
			<tr><td height='30' colspan='7' align='center' bgcolor='929090' style='color:#fff;'>Your Order ID: <strong>".strtoupper($order_id)."</strong></td></tr>
      </table></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align='right'>Sub Total Rs. <strong>".$subtotal."</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td align='right'>Shpping Charges Rs. <strong>".$shippingcost."</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td align='right'>Total Rs. <strong>".$total."</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>";

		$message=$message."<tr>
			<td align='left'>You can track your order at any time by login onto our website http://dsquarreuniforms.com/shop/login.php.</td>
		</tr>
		<tr>
			<td>&nbsp;</td>

		</tr>
		<tr>
			<td align='left'>If you need any assistance or have any questions, feel free to contact us at below<br /><br>
Email: store.dsquarreuniforms@gmail.com<br /><br>
Tel: +91 7863002006 / +91 7862002003</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
		<td align='left'>We hope you enjoyed shopping at DsquarreUnforms.com</td>

		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align='left'>Team <a href='http://dsquarreuniforms.com/shop'>DsquarreUnforms.com</a></td>
		</tr>
		</table>";

	$from = 'hello@dsquarreuniforms.com';
	$to=$dtatmasteruser_row['email'];

	$too='shahnawaz24k@gmail.com,store.dsquarreuniforms@gmail.com';

	$adminsubject="order placed - Rs. ".$total;

	if(mail($to, $subject, $message, "From:  dsquarreuniforms.com <$from>\r\nContent-type: text/html\r\n"))
	{
		//mail($too, $adminsubject, $message, "From:  dsquarreuniforms.com <$from>\r\nContent-type: text/html\r\n");

		$zz=1;
	}
	else
	{
		$zz=0;
	}


?>
