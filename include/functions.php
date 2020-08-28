<?php

function updateRate($db,$un_id,$ipAddress){
    $total_Stock = 0;
	$tabQuery = $db->prepare("SELECT * FROM cart WHERE un_id = '$un_id' OR curr_ip = '$IpAddress'");
	$tabQuery->execute();
	return $tabQuery;

}

function cartItems($db,$un_id,$ipAddress){
    $total_Stock = 0;
	$tabQuery = $db->prepare("SELECT * FROM cart WHERE un_id = '$un_id' OR curr_ip = '$IpAddress'");
	$tabQuery->execute();
	return $tabQuery;

}
function TotalQuantity($db,$prod_id){
    $total_Stock = 0;
	$tabQuery = $db->prepare("select color_id,stock from product_images where product_id = '$prod_id' group by color_id");
	$tabQuery->execute();
	while($reponse = $tabQuery->fetch(PDO::FETCH_ASSOC)){
	    $total_Stock+= $reponse['stock'];
	}
		
	return $total_Stock;

}
function SendSms($mobile,$sms){
$newno = substr($mobile, -8); 
$user="Alzamanent"; 
$password="60154114";
$mobilenumbers='974'.$newno; 

//$mobilenumbers=917895783838; 
$message = $sms;
$senderid="ALZAMAN"; //Your senderid
$messagetype="LNG"; //Type Of Your Message
$DReports="Y"; //Delivery Reports
$url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
$message = urlencode($message);
$ch = curl_init();
if (!$ch){die("Couldn't initialize a cURL handle");}
$ret = curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt ($ch, CURLOPT_POSTFIELDS,
"User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
$curlresponse = curl_exec($ch); // execute
if(curl_errno($ch))
echo 'curl error : '. curl_error($ch);
if (empty($ret)) {
// some kind of an error happened
die(curl_error($ch));
curl_close($ch); // close cURL handler
} else {
$info = curl_getinfo($ch);
curl_close($ch); // close cURL handler
 //$curlresponse; //echo "Message Sent Succesfully" ;
}
		
	return $curlresponse;

}
function TotalQuantitybyColor($db,$prod_id,$color_id){
    $total_Stock = 0;
	$tabQuery = $db->prepare("select stock from product_images where product_id = '$prod_id' and color_id = '$color_id'");
	$tabQuery->execute();
	$reponse = $tabQuery->fetch(PDO::FETCH_ASSOC);
	$total_Stock= $reponse['stock'];
	
	$totalsales = $db->prepare( "select sum(qty) as total_sale from cart_order_item where pid='$prod_id' and color_id = '$color_id' and order_id IN(select id from cart_orders where status!='rejected')");
	
	
	
    $totalsales->execute();
    $totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
    $stockrest=$totalsaleamt['total_sale'];
    $totalstock=$total_Stock-$stockrest;
		
	return $totalstock;

}

function OrderDetails($conn,$id){
    
    $WebsiteUrl = 'https://alzamanenterprises.com';
    
  $query = $conn->prepare("SELECT * FROM cart_orders WHERE id = '$id'");
  $query->execute();
  $orderdetail = $query->fetch(PDO::FETCH_ASSOC);

$shippingcharg = $orderdetail['ship_charge'];
if($shippingcharg==0){ $shippingcharg2 = 'Free Shipping'; }else{ $shippingcharg2 = 'QAR '.$shippingcharg; }
if($orderdetail['coupan_value']!=0 && $orderdetail['coupan_value']!=''){ $coupan = $orderdetail['coupan_value']; }else{ $coupan = ''; }
$total = $orderdetail['total'];

  $billingquery = $conn->prepare("select * from registration WHERE id = '".$orderdetail['user_id']."'");
  $billingquery->execute();
  $billingdata = $billingquery->fetch(PDO::FETCH_ASSOC);
  
  $billingquery2 = $conn->prepare("select * from tbl_address WHERE id = '".$orderdetail['address_id']."'");
  $billingquery2->execute();
  $billingdata2 = $billingquery2->fetch(PDO::FETCH_ASSOC);
  
    $country2 = $conn->prepare("select name_en from country where id = '".$billingdata2['country']."'");
    $country2->execute();
    $country3 = $country2->fetch(PDO::FETCH_ASSOC);
    $country = $country3['name_en'];
    
    $city2 = $conn->prepare("select name_en from city where id = '".$billingdata2['city']."'");
    $city2->execute();
    $city3 = $city2->fetch(PDO::FETCH_ASSOC);
    $city = $city3['name_en'];
     
     $fullname = ucfirst($billingdata['name']).' '.ucfirst($billingdata['lastname']); 

  
  if($orderdetail['payment_mode']==1){
  		$paymethod='Cash on delivery';
		}
		elseif($orderdetail['payment_mode']==2)
		{
		$paymethod='Store Pick Up';
		}
		
		$orderdata ='<link rel="stylesheet" href="'.$WebsiteUrl.'/css/invoice.css" type="text/css" media="all">  
    					<section class="thissection maintopwrapper col-md-12">
  						<div class="container">
   						<div class="row innerrow">
      					<div class="col-md-6 leftheaderheading">
        				<h2>Invoice</h2>
        				</div>
						<div class="col-md-6 headerlogo">
						<img src="'.$WebsiteUrl.'/images/logo.png" alt="" title="" class="img-fluid" style="width: 50%;padding: 2%;
    background: #000;margin-top: 10px;">
						</div>
						<div class="col-md-4 commontext">
						<h3>Date</h3>
						<h4>'.date('d M, Y',strtotime($orderdetail['created_at'])).'</h4>
						<p>ALZAMAN ENTERPRISES<br/>
						Abdul Rahman Street<br/>
						Doha<br/>
						+974-31559977<br/>
						info@alzamanenterprises.com
						</p>
						</div>
						<div class="col-md-4 commontext">
						<h3>Invoice To:</h3>
						<p>'.ucwords($fullname).'<br/>
						Building No- '.$billingdata2['state'].'<br/>
						Zone- '.$billingdata2['zip'].'<br/>
						Street Address- '.$billingdata2['address'].'<br/>
						City- '.$city.'<br/>
              			Phone- '.$billingdata['phone'].'<br/>
						
						</p>
          				</div>
						<div class="col-md-4 commontext">
						<h3>Ship To:</h3>
						<label>Invoice No - ALZ-'.$id.'</label>
						<p>'.ucwords($fullname).'<br/>
						Building No- '.$billingdata2['state'].'<br/>
						Zone- '.$billingdata2['zip'].'<br/>
						Street Address- '.$billingdata2['address'].'<br/>
						City- '.$city.'<br/>
              			Phone- '.$billingdata['phone'].'<br/>
						
						</p>
						</div>
						<div class="bottom"></div>
						</div> 
						</div>   
						</section>
						<section class="col-md-12 secondtablewrapper">
						<div class="container-fluid">
						<div class="row">
						<div class="container">
						<div class="card">
						<div class="card-body">
						<div class="table-responsive-sm">
						<table class="table table-striped">
						<thead>
						<tr  style="background:#fff !important;color: #000;border: 1px solid #000;">
						<th>S.NO.</th>
						<th>Product Order ID</th>
						<th>Product Description</th>
						<th>Qty</th>
						<th>Unit Price</th>
						<th>Amount</th>
						</tr>
						</thead>
						<tbody style="color: #000;border-bottom: 1px solid #000;">';
						$mm=1;
                   $maincartquery = $conn->prepare( "select c.*, p.product_name_en, p.image,p.user_id from cart_order_item c LEFT JOIN products p
													ON p.id = c.pid where c.order_id = '$id'");
                   $maincartquery->execute();

                   while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC)){
				   
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
				   
					if (file_exists($WebsiteUrl.'/adminuploads/product/'.$maincart['image'])) {

							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];

						}else{

							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];
						}

						$p_price  = $maincart['price'];
						$subt = $p_price*$maincart['qty'];

						$subtotal2 =  $maincart['price']*$maincart['qty'];
						$subtotal  =  $subtotal2; 
						
						$orderdata = $orderdata.'<tr>
						<td>'.$mm++.'</td>
						<td>'.$order_id2.'</td>
						<td>'.stripslashes($maincart["product_name_en"]).'<br>(Size: '.$size2.')<br>(Color: '.$color2.')</td>
						<td>'.$maincart['qty'].'</td>
						<td>QAR '.$p_price.'</td>
						<td>QAR '.$subtotal.'</td>
						</tr>';
						}
						$orderdata = $orderdata.'</tbody>
						</table>
						</div>
						</div>
						</div>
						</div>
						</div>
						</div>
						</section>
						<section class="col-md-12 secondtotalsection">
						<div class="container">
						<div class="row">
						<div class="col-lg-4 col-sm-5">
						</div>
						<div class="col-lg-4 col-sm-5 ml-auto">
						<table class="table table-clear">
						<tbody style="color: #000;">
						<tr>
						<td class="left">
						<strong>Total Amount</strong>
						</td>
						<td class="right">QAR '.$total.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Payment Mode</strong>
						</td>
						<td class="right">'.$paymethod.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Shipping Charge</strong>
						</td>
						<td class="right">'.$shippingcharg2.'</td>
						</tr>';
						if($coupan!=''){ $orderdata = $orderdata.'<tr>
						<td class="left">
						<strong>Coupan Value</strong>
						</td>
						<td class="right">QAR '.$coupan.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Total</strong>
						</td>
						<td class="right">
						<strong>QAR '.(($total+$shippingcharg)-($coupan)).'</strong>
						</td>
						</tr>'; }else{
						$orderdata = $orderdata.'<tr>
						<td class="left">
						<strong>Total Payable Amount</strong>
						</td>
						<td class="right">
						<strong>QAR '.($total+$shippingcharg).'</strong>
						</td>
						</tr>'; }
									
						$orderdata = $orderdata.'</tbody>
						</table>
						</div>
						</div>
						</div>
						</section>
						<div class="col-md-12 text-center thankyou">
						<div class="container">
						<div class="row">
						<h3>Thank You For Your Business</h3>
						</div>
						</div>
						</div>
						<div class="col-md-12 footer">
						<div class="container">
						<div class="row">
						<div class="footerbootom"></div>
						</div>
						</div>
						</div>';
	
  return $orderdata;

}
function OrderDetailsVendor($conn,$id,$vendorId){
    
    $WebsiteUrl = 'https://alzamanenterprises.com';
    
  $query = $conn->prepare("SELECT * FROM cart_orders WHERE id = '$id'");
  $query->execute();
  $orderdetail = $query->fetch(PDO::FETCH_ASSOC);

if($orderdetail['coupan_value']!=0 && $orderdetail['coupan_value']!=''){ $coupan = $orderdetail['coupan_value']; }else{ $coupan = ''; }

$totalAmt = $conn->prepare("select sum(price*qty) as mytotal from cart_order_item where order_id = '$id' and pid IN(select id from products where user_id = '$vendorId')");
$totalAmt->execute();
$totalAmt2 = $totalAmt->fetch(PDO::FETCH_ASSOC);

$total = $totalAmt2['mytotal'];

  $billingquery = $conn->prepare("select * from registration WHERE id = '".$orderdetail['user_id']."'");
  $billingquery->execute();
  $billingdata = $billingquery->fetch(PDO::FETCH_ASSOC);
  
  $billingquery2 = $conn->prepare("select * from tbl_address WHERE id = '".$orderdetail['address_id']."'");
  $billingquery2->execute();
  $billingdata2 = $billingquery2->fetch(PDO::FETCH_ASSOC);
  
    $country2 = $conn->prepare("select name_en from country where id = '".$billingdata2['country']."'");
    $country2->execute();
    $country3 = $country2->fetch(PDO::FETCH_ASSOC);
    $country = $country3['name_en'];
    
    $city2 = $conn->prepare("select name_en from city where id = '".$billingdata2['city']."'");
    $city2->execute();
    $city3 = $city2->fetch(PDO::FETCH_ASSOC);
    $city = $city3['name_en'];
     
     $fullname = ucfirst($billingdata['name']).' '.ucfirst($billingdata['lastname']); 

  
  if($orderdetail['payment_mode']==1){
  		$paymethod='Cash on delivery';
		}
		elseif($orderdetail['payment_mode']==2)
		{
		$paymethod='Store Pick Up';
		}
		
		$orderdata ='<link rel="stylesheet" href="'.$WebsiteUrl.'/css/invoice.css" type="text/css" media="all">  
    					<section class="thissection maintopwrapper col-md-12">
  						<div class="container">
   						<div class="row innerrow">
      					<div class="col-md-6 leftheaderheading">
        				<h2>Invoice</h2>
        				</div>
						<div class="col-md-6 headerlogo">
						<img src="'.$WebsiteUrl.'/images/logo.png" alt="" title="" class="img-fluid" style="width: 50%;padding: 2%;
    background: #000;margin-top: 10px;">
						</div>
						<div class="col-md-4 commontext">
						<h3>Date</h3>
						<h4>'.date('d M, Y',strtotime($orderdetail['created_at'])).'</h4>
						<p>ALZAMAN ENTERPRISES<br/>
						Abdul Rahman Street<br/>
						Doha<br/>
						+974-31559977<br/>
						info@alzamanenterprises.com
						</p>
						</div>
						<div class="col-md-4 commontext">
						<h3>Invoice To:</h3>
						<p>'.ucwords($fullname).'<br/>
						Building No- '.$billingdata2['state'].'<br/>
						Zone- '.$billingdata2['zip'].'<br/>
						Street Address- '.$billingdata2['address'].'<br/>
						City- '.$city.'<br/>
              			Phone- '.$billingdata['phone'].'<br/>
						
						</p>
          				</div>
						<div class="col-md-4 commontext">
						<h3>Ship To:</h3>
						<label>Invoice No - ALZ-'.$id.'</label>
						<p>'.ucwords($fullname).'<br/>
						Building No- '.$billingdata2['state'].'<br/>
						Zone- '.$billingdata2['zip'].'<br/>
						Street Address- '.$billingdata2['address'].'<br/>
						City- '.$city.'<br/>
              			Phone- '.$billingdata['phone'].'<br/>
						
						</p>
						</div>
						<div class="bottom"></div>
						</div> 
						</div>   
						</section>
						<section class="col-md-12 secondtablewrapper">
						<div class="container-fluid">
						<div class="row">
						<div class="container">
						<div class="card">
						<div class="card-body">
						<div class="table-responsive-sm">
						<table class="table table-striped">
						<thead>
						<tr  style="background:#fff !important;color: #000;border: 1px solid #000;">
						<th>S.NO.</th>
						<th>Product Order ID</th>
						<th>Product Description</th>
						<th>Qty</th>
						<th>Unit Price</th>
						<th>Amount</th>
						</tr>
						</thead>
						<tbody style="color: #000;border-bottom: 1px solid #000;">';
						$mm=1;
                   $maincartquery = $conn->prepare( "select c.*, p.product_name_en, p.image,p.user_id from cart_order_item c LEFT JOIN products p
													ON p.id = c.pid where c.order_id = '$id' and p.user_id = '$vendorId'");
                   $maincartquery->execute();

                   while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC)){
				   
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
				   
					if (file_exists($WebsiteUrl.'/adminuploads/product/'.$maincart['image'])) {

							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];

						}else{

							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];
						}

						$p_price  = $maincart['price'];
						$subt = $p_price*$maincart['qty'];

						$subtotal2 =  $maincart['price']*$maincart['qty'];
						$subtotal  =  $subtotal2; 
						
						$orderdata = $orderdata.'<tr>
						<td>'.$mm++.'</td>
						<td>'.$order_id2.'</td>
						<td>'.stripslashes($maincart["product_name_en"]).'<br>(Size: '.$size2.')<br>(Color: '.$color2.')</td>
						<td>'.$maincart['qty'].'</td>
						<td>QAR '.$p_price.'</td>
						<td>QAR '.$subtotal.'</td>
						</tr>';
						}
						$orderdata = $orderdata.'</tbody>
						</table>
						</div>
						</div>
						</div>
						</div>
						</div>
						</div>
						</section>
						<section class="col-md-12 secondtotalsection">
						<div class="container">
						<div class="row">
						<div class="col-lg-4 col-sm-5">
						</div>
						<div class="col-lg-4 col-sm-5 ml-auto">
						<table class="table table-clear">
						<tbody style="color: #000;">
						<tr>
						<td class="left">
						<strong>Total Amount</strong>
						</td>
						<td class="right">QAR '.$total.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Payment Mode</strong>
						</td>
						<td class="right">'.$paymethod.'</td>
						</tr>';
						if($coupan!=''){ $orderdata = $orderdata.'<tr>
						<td class="left">
						<strong>Coupan Value</strong>
						</td>
						<td class="right">QAR '.$coupan.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Total</strong>
						</td>
						<td class="right">
						<strong>QAR '.($total-$coupan).'</strong>
						</td>
						</tr>'; }else{
						$orderdata = $orderdata.'<tr>
						<td class="left">
						<strong>Total Payable Amount</strong>
						</td>
						<td class="right">
						<strong>QAR '.($total).'</strong>
						</td>
						</tr>'; }
									
						$orderdata = $orderdata.'</tbody>
						</table>
						</div>
						</div>
						</div>
						</section>
						<div class="col-md-12 text-center thankyou">
						<div class="container">
						<div class="row">
						<h3>Thank You For Your Business</h3>
						</div>
						</div>
						</div>
						<div class="col-md-12 footer">
						<div class="container">
						<div class="row">
						<div class="footerbootom"></div>
						</div>
						</div>
						</div>';
	
  return $orderdata;

}
function OrderDetailsArb($conn,$id){
    
    $WebsiteUrl = 'https://alzamanenterprises.com';
    
    $query = $conn->prepare("SELECT * FROM cart_orders WHERE id = '$id'");
  $query->execute();
  $orderdetail = $query->fetch(PDO::FETCH_ASSOC);

$shippingcharg = $orderdetail['ship_charge'];
if($shippingcharg==0){ $shippingcharg2 = 'Free Shipping'; }else{ $shippingcharg2 = 'QAR '.$shippingcharg; }
if($orderdetail['coupan_value']!=0 && $orderdetail['coupan_value']!=''){ $coupan = $orderdetail['coupan_value']; }else{ $coupan = ''; }
$total = $orderdetail['total'];

  $billingquery = $conn->prepare("select * from registration WHERE id = '".$orderdetail['user_id']."'");
  $billingquery->execute();
  $billingdata = $billingquery->fetch(PDO::FETCH_ASSOC);
  
  $billingquery2 = $conn->prepare("select * from tbl_address WHERE id = '".$orderdetail['address_id']."'");
  $billingquery2->execute();
  $billingdata2 = $billingquery2->fetch(PDO::FETCH_ASSOC);
  
    $country2 = $conn->prepare("select name_en from country where id = '".$billingdata2['country']."'");
    $country2->execute();
    $country3 = $country2->fetch(PDO::FETCH_ASSOC);
    $country = $country3['name_en'];
    
    $city2 = $conn->prepare("select name_en from city where id = '".$billingdata2['city']."'");
    $city2->execute();
    $city3 = $city2->fetch(PDO::FETCH_ASSOC);
    $city = $city3['name_en'];
     
     $fullname = ucfirst($billingdata['name']).' '.ucfirst($billingdata['lastname']); 

  
  if($orderdetail['payment_mode']==1){
  		$paymethod='Cash on delivery';
		}
		elseif($orderdetail['payment_mode']==2)
		{
		$paymethod='Store Pick Up';
		}
		
		$orderdata ='<link rel="stylesheet" href="'.$WebsiteUrl.'/css/invoice.css" type="text/css" media="all">  
    					<section class="thissection maintopwrapper col-md-12">
  						<div class="container">
   						<div class="row innerrow">
      					<div class="col-md-6 leftheaderheading">
        				<h2>Invoice</h2>
        				</div>
						<div class="col-md-6 headerlogo">
						<img src="'.$WebsiteUrl.'/images/logo.png" alt="" title="" class="img-fluid" style="width: 50%;padding: 2%;
    background: #000;margin-top: 10px;">
						</div>
						<div class="col-md-4 commontext">
						<h3>Date</h3>
						<h4>'.date('d M, Y',strtotime($orderdetail['created_at'])).'</h4>
						<p>ALZAMAN ENTERPRISES<br/>
						Abdul Rahman Street<br/>
						Doha<br/>
						+974-31559977<br/>
						info@alzamanenterprises.com
						</p>
						</div>
						<div class="col-md-4 commontext">
						<h3>Invoice To:</h3>
						<p>'.ucwords($fullname).'<br/>
						Building No- '.$billingdata2['state'].'<br/>
						Zone- '.$billingdata2['zip'].'<br/>
						Street Address- '.$billingdata2['address'].'<br/>
						City- '.$city.'<br/>
              			Phone- '.$billingdata['phone'].'<br/>
						
						</p>
          				</div>
						<div class="col-md-4 commontext">
						<h3>Ship To:</h3>
						<label>Invoice No - ALZ-'.$id.'</label>
						<p>'.ucwords($fullname).'<br/>
						Building No- '.$billingdata2['state'].'<br/>
						Zone- '.$billingdata2['zip'].'<br/>
						Street Address- '.$billingdata2['address'].'<br/>
						City- '.$city.'<br/>
              			Phone- '.$billingdata['phone'].'<br/>
						
						</p>
						</div>
						<div class="bottom"></div>
						</div> 
						</div>   
						</section>
						<section class="col-md-12 secondtablewrapper">
						<div class="container-fluid">
						<div class="row">
						<div class="container">
						<div class="card">
						<div class="card-body">
						<div class="table-responsive-sm">
						<table class="table table-striped">
						<thead>
						<tr  style="background:#fff !important;color: #000;border: 1px solid #000;">
						<th>S.NO.</th>
						<th>Product Order ID</th>
						<th>Product Description</th>
						<th>Qty</th>
						<th>Unit Price</th>
						<th>Amount</th>
						</tr>
						</thead>
						<tbody style="color: #000;border-bottom: 1px solid #000;">';
						$mm=1;
                   $maincartquery = $conn->prepare( "select c.*, p.product_name_en, p.image,p.user_id from cart_order_item c LEFT JOIN products p
													ON p.id = c.pid where c.order_id = '$id'");
                   $maincartquery->execute();

                   while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC)){
				   
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
				   
					if (file_exists($WebsiteUrl.'/adminuploads/product/'.$maincart['image'])) {

							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];

						}else{

							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];
						}

						$p_price  = $maincart['price'];
						$subt = $p_price*$maincart['qty'];

						$subtotal2 =  $maincart['price']*$maincart['qty'];
						$subtotal  =  $subtotal2; 
						
						$orderdata = $orderdata.'<tr>
						<td>'.$mm++.'</td>
						<td>'.$order_id2.'</td>
						<td>'.stripslashes($maincart["product_name_en"]).'<br>(Size: '.$size2.')<br>(Color: '.$color2.')</td>
						<td>'.$maincart['qty'].'</td>
						<td>QAR '.$p_price.'</td>
						<td>QAR '.$subtotal.'</td>
						</tr>';
						}
						$orderdata = $orderdata.'</tbody>
						</table>
						</div>
						</div>
						</div>
						</div>
						</div>
						</div>
						</section>
						<section class="col-md-12 secondtotalsection">
						<div class="container">
						<div class="row">
						<div class="col-lg-4 col-sm-5">
						</div>
						<div class="col-lg-4 col-sm-5 ml-auto">
						<table class="table table-clear">
						<tbody style="color: #000;">
						<tr>
						<td class="left">
						<strong>Total Amount</strong>
						</td>
						<td class="right">QAR '.$total.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Payment Mode</strong>
						</td>
						<td class="right">'.$paymethod.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Shipping Charge</strong>
						</td>
						<td class="right">'.$shippingcharg2.'</td>
						</tr>';
						if($coupan!=''){ $orderdata = $orderdata.'<tr>
						<td class="left">
						<strong>Coupan Value</strong>
						</td>
						<td class="right">QAR '.$coupan.'</td>
						</tr>
						<tr>
						<td class="left">
						<strong>Total</strong>
						</td>
						<td class="right">
						<strong>QAR '.(($total+$shippingcharg)-($coupan)).'</strong>
						</td>
						</tr>'; }else{
						$orderdata = $orderdata.'<tr>
						<td class="left">
						<strong>Total Payable Amount</strong>
						</td>
						<td class="right">
						<strong>QAR '.($total+$shippingcharg).'</strong>
						</td>
						</tr>'; }
									
						
						$orderdata = $orderdata.'</tbody>
						</table>
						</div>
						</div>
						</div>
						</section>
						<div class="col-md-12 text-center thankyou">
						<div class="container">
						<div class="row">
						<h3>Thank You For Your Business</h3>
						</div>
						</div>
						</div>
						<div class="col-md-12 footer">
						<div class="container">
						<div class="row">
						<div class="footerbootom"></div>
						</div>
						</div>
						</div>';	
  return $orderdata;

}

// function OrderDetailsArb($conn,$id){
    
//     $WebsiteUrl = 'https://alzamanenterprises.com';

//   $query = $conn->prepare("select * from cart_orders WHERE id = '$id'");
//   $query->execute();
//   $orderdetail = $query->fetch(PDO::FETCH_ASSOC);
//   //echo $orderdetail['user_id'];

//   $billingquery = $conn->prepare("select * from registration WHERE id = '".$orderdetail['user_id']."'");
//   $billingquery->execute();
//   $billingdata = $billingquery->fetch(PDO::FETCH_ASSOC);
  
//   $billingquery2 = $conn->prepare("select * from tbl_address WHERE id = '".$orderdetail['address_id']."'");
//   $billingquery2->execute();
//   $billingdata2 = $billingquery2->fetch(PDO::FETCH_ASSOC);
  
// $country2 = $conn->prepare("select name_ar from country where id = '".$billingdata2['country']."'");
// $country2->execute();
// $country3 = $country2->fetch(PDO::FETCH_ASSOC);
// $country = $country3['name_ar'];

// $city2 = $conn->prepare("select name_ar from city where id = '".$billingdata2['city']."'");
// $city2->execute();
// $city3 = $city2->fetch(PDO::FETCH_ASSOC);
// $city = $city3['name_ar'];
 
//  $fullname = ucfirst($billingdata['name']).' '.ucfirst($billingdata['lastname']); 
 
// $size2 = $conn->prepare("select size from products_size where id = '".$orderdetail['size_id']."'");
// $size2->execute();
// $size3 = $size2->fetch(PDO::FETCH_ASSOC);
// $size = $size3['size_ar'];

// $color2 = $conn->prepare("select color from products_color where id = '".$orderdetail['color_id']."'");
// $color2->execute();
// $color3 = $color2->fetch(PDO::FETCH_ASSOC);
// $color = $color3['color_ar'];
// if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
// if($color!=''){ $color2=$color; }else{ $color2='N/A'; }
  
//   if($orderdetail['payment_mode']==1){
//   		$paymethod='دفع عند الاستلام';
// 		}
// 		elseif($orderdetail['payment_mode']==2)
// 		{
// 		$paymethod='الاستلام من المتجر';
// 		}

// 		$orderdata ="<table class='table table-striped'>
// 				  <tr style='background-color: #0000004d;color: white;'><td colspan='6' align='center'>طلباتك </td></tr>
//                   <tr style='background: black;'>
//                   <td align='center' style='color:#fff;'>المنتجات </td>
// 				  <td align='center' style='color:#fff;'>الاسم</td>
// 				  <td align='center' style='color:#fff;'>السعر</td>
// 				  <td align='center' style='color:#fff;'>الكمية </td>
// 				  <td align='center' style='color:#fff;'>المجموع </td>
//                   </tr>";
				     
// 				$mm=1;
//                   $maincartquery = $conn->prepare( "select c.*, p.product_name_en, p.image from cart_orders c LEFT JOIN products p
// 													ON p.id = c.pid where c.id = '$id'");
//                   $maincartquery->execute();

//                   while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC)){
				   
// 					if (file_exists($WebsiteUrl.'/adminuploads/product/'.$maincart['image'])) {

// 							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];

// 						}else{

// 							$image = $WebsiteUrl.'/adminuploads/product/'.$maincart['image'];
// 						}

// 						$p_price  = $maincart['price'];
// 						$subt = $p_price*$maincart['qty'];

// 						$subtotal2 =  $maincart['price']*$maincart['qty'];
// 						$shippingcharg = $maincart['ship_charge'];
// 						$subtotal  =  $subtotal2+$shippingcharg; 
// 						$total+= $subtotal; 
// 						if($shippingcharg==0){ $shippingcharg2 = 'Free Shipping'; }else{ $shippingcharg2 = 'يال '.$myshippingCharge; }
// 						if($maincart['coupan_value']!=0 && $maincart['coupan_value']!=''){ $coupan = $maincart['coupan_value']; }else{ $coupan = ''; }
					
//                      $orderdata = $orderdata."<tr>
//                           <td align='center'><img src='".$image."' height='70' width='80' style='margin-bottom:5px;' /></td>
// 						  <td align='center'>".stripslashes($maincart['product_name_ar'])."<br>(الحجم: ".$size2.")<br>(اللون: ".$color2.")</td>
// 						  <td align='center'>ريال ".$p_price."</td>
// 						  <td align='center'>".$maincart['qty']."</td>
// 						  <td align='center'>ريال ".$subtotal."</td>
//                       </tr>";

//       				}

//   $orderdata = $orderdata."<table style='width:100%;' class='table table-striped'>
//                   <tr style='background-color: #0000004d;color: white;'><td colspan='3' align='center'>الفاتورة </td></tr>
//                   <tr><td colspan='3' align='left' >&nbsp;</td></tr>
//                   <tr><td>تاريخ الطلب</td><td>:</td><td>".date('d-m-Y', strtotime($orderdetail['created_at']))."</td></tr>
//                   <tr><td>الاسم</td><td>:</td><td>".$fullname."</td></tr>
// 				  <tr><td>جهة العمل </td><td>:</td><td>".$billingdata['cname']."</td></tr>
//                   <tr><td>رقم الجوال</td><td>:</td><td>".$billingdata['phone']."</td></tr>
//                   <tr><td>البريد الالكتروني</td><td>:</td><td>".$billingdata['email']."</td></tr>
// 				  <tr><td>Shipping Name</td><td>:</td><td>".$billingdata2['name']."</td></tr>
// 				  <tr><td>Shipping Mobile No</td><td>:</td><td>".$billingdata2['alt_mobile']."</td></tr>
// 				  <tr><td>Building No</td><td>:</td><td>".$billingdata2['state']."</td></tr>
// 				  <tr><td>Zone</td><td>:</td><td>".$billingdata2['zip']."</td></tr>
//                   <tr><td>اسم الشارع</td><td>:</td><td>".$billingdata2['address']."</td></tr>
         
//                   <tr><td>البلد</td><td>:</td><td>".$country."</td></tr>
			
//                   <tr><td>المدينة</td><td>:</td><td>".$city."</td></tr></table>";
				  
	
//     $orderdata = $orderdata."<table style='width:100%;' class='table table-striped'>
//                   <tr style='background-color: #0000004d;color: white;'><td colspan='3' align='center'>PAYMENT PROCESS</td></tr>
//                   <tr><td>Payment Mode</td><td>:</td><td>".$paymethod."</td></tr>
//                   <tr><td>ر الشحن والتوصيل</td><td>:</td><td>".$shippingcharg2."</td></tr>";
//       if($coupan!=''){ $orderdata = $orderdata."<tr><td>Coupan Value</td><td>:</td><td>ريال ".$coupan."</td></tr>
//                                                 <tr><td>المبلغ الاجمالي</td><td>:</td><td>ريال ".($subtotal-$coupan)."</td></tr></table>"; }else{            
//       $orderdata = $orderdata."<tr><td>المبلغ الاجمالي</td><td>:</td><td>ريال ".$subtotal."</td></tr></table>"; }
  
//   return $orderdata;

// }
function tabAlldata($db){

	$tabQuery = $db->prepare("select id,name_en,name_ar from department where visible = 1 order by id DESC");
	$result = $tabQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$tabQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $tabQuery->fetchAll();
		
	return $reponse;

}

function tabAlldataQuery($db, $tab_id){

	$tabQuery = $db->prepare("select * from department where visible = 1 and id = '$tab_id'");
	$result = $tabQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$tabQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $tabQuery->fetchAll();
		
	return $reponse;

}

function catAlldata($db){

	$catQuery = $db->prepare("select id,name_en,name_ar from sub_department where visible = 1 order by id DESC");
	$result = $catQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$catQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $catQuery->fetchAll();
		
	return $reponse;

}

function catAlldataQuery($db, $cat_id){

	$catQuery = $db->prepare("select * from sub_department where visible = 1 and id = '$cat_id'");
	$result = $catQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$catQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $catQuery->fetchAll();
		
	return $reponse;

}

function getallSubcategory($db){

	$subCatQuery = $db->prepare("select id,name_en,name_ar from sub_cat where visible = 1 order by id DESC");
	$result = $subCatQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$subCatQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $subCatQuery->fetchAll();
		
	return $reponse;

}

function getSubcategory($db, $subcat_id){

	$tabQuery = $db->prepare("select * from sub_cat where visible = 1 and id = '$subcat_id'");
	$result = $tabQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$tabQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $tabQuery->fetchAll();
		
	return $reponse;

}

function featuredProduct($db){

	$featuredQuery = $db->prepare("select id,quantity,discount,discount_type,slug,image,p_size,p_color,product_name_en,price,old_price,product_name_ar from products where status = 1 and featured = 1 order by rand() LIMIT 15");
	$result = $featuredQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$featuredQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $featuredQuery->fetchAll();
		
	return $reponse;

}

function getAllProducts($db){

	$productsQuery = $db->prepare("select * from products where status = 1 order by id DESC");
	$result = $productsQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$productsQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $productsQuery->fetchAll();
		
	return $reponse;

}

function getAllProductsbyId($db, $productId){

	$productsQuery = $db->prepare("select * from products where status = 1 WHERE id = '$productId'");
	$result = $productsQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$productsQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $productsQuery->fetchAll();
		
	return $reponse;

}

function newArrivalsProducts($db, $productLimit){

	$productsQuery = $db->prepare("select id,quantity,discount,discount_type,slug,image,p_size,p_color,product_name_en,price,old_price,product_name_ar from products where status = 1 ORDER BY rand() LIMIT $productLimit");
	$result = $productsQuery->execute();
	
	if (!$result) 
	{
		return false;  
	}
	
	$productsQuery->setFetchMode(PDO::FETCH_ASSOC);
	$reponse = $productsQuery->fetchAll();
		
	return $reponse;

}


?>