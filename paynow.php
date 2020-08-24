<?php

//error_reporting(0);

            $CARTTOTAL = 0;
			$isDelieveryFree = 0;
			$myshippingCharge = 0;
	
    $maincartquery = $conn->prepare( "select * from cart where un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
    $maincartquery->execute();

    while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC))
    {
	    $weightdata2 = $conn->prepare( "select * from products where id = '".$maincart['pid']."'");
    	$weightdata2->execute();
		$cartproduct = $weightdata2->fetch(PDO::FETCH_ASSOC);
		  
		        $sameProduct = $conn->prepare("select SUM(qty) as cartqty,SUM(price*qty) as cartTot from cart where un_id = '".$_SESSION['UNIQUEID']."' and pid = '".$maincart['pid']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."' and pid = '".$maincart['pid']."'");
				$sameProduct->execute();
				$TotalQuantity = $sameProduct->fetch(PDO::FETCH_ASSOC);
			     
				$subtotal2 =  $maincart['price']*$maincart['qty'];
				if($cartproduct['ship_charge']==''){
					$shippingcharg = 00;
					$isDelieveryFree = 1;
				}
				elseif($cartproduct['free_shipping_qty'] <= $TotalQuantity['cartqty']){
					$shippingcharg = 00;
					$isDelieveryFree = 1;
				}
				elseif($cartproduct['free_shipping_amount'] <= $TotalQuantity['cartTot']){
					$shippingcharg = 00;
					
				}
				else{
					$shippingcharg = $cartproduct['ship_charge'];
				}
				//echo $isDelieveryFree;
				$subtotal  =  $subtotal2;
				$myshippingCharge+= $cartproduct['ship_charge'];
				$CARTTOTAL+= $subtotal;
				
	  
   }
   if($isDelieveryFree==1){ $mynewShipCharge = 0; }else{ $mynewShipCharge = $myshippingCharge; }
   $totalamt =  $CARTTOTAL;

 $CoupanCode2 = $conn->prepare("SELECT a.id as myId,b.* FROM apply_coupan a right join coupan_code b ON a.coupan_id=b.id where a.un_id = '".$_SESSION['UNIQUEID']."' and b.status=1 and CURDATE() BETWEEN b.valid_from and b.valid_to");
 $CoupanCode2->execute();
 $CoupanCode = $CoupanCode2->fetch(PDO::FETCH_ASSOC);
 $applied_coupan = $CoupanCode['id'];
 
 if($CoupanCode['discount_type']==1){
     $discount = $CoupanCode['discount_value'];
 }
 elseif($CoupanCode['discount_type']==2){
    $discount = ($totalamt*$CoupanCode['discount_value'])/100;
 }
 else{
     $discount = '';
 }
//$discount;
if (isset($_REQUEST['pay_now']))
{

    $shipAddress = $_POST["shipAddress"];
    $order_total =$_SESSION['cartTotal']= $totalamt;
    $payment_mode = $_POST["payment_mode"];
    $userid = $_SESSION['LOGIN_ID'];
    $deliverydate = $_POST["deliverydate"];
    $deliverytime = $_POST["deliverytime"];
	  $_SESSION['ADDRESS'] = $shipAddress;
	
	$srstmt = $conn->prepare("INSERT INTO `cart_orders`(`un_id`, `user_id`, `ship_charge`, `total`, `curr_ip`, `created_at`, `payment_mode`, `address_id`, `coupan_id`, `coupan_uid`, `coupan_value`, `coupan_code`,`delivery_date`,`delivery_timeslot`)

        VALUES (:un_id, :uid, :shippingcharg, :total, :crrid, :createdat, :payment_mode, :addid, :cpnid, :cpnuid, :cpnamount, :code, :deliverydate, :deliverytime)");

        $srstmt->bindParam(':un_id', $_SESSION['UNIQUEID'], PDO::PARAM_STR);
        $srstmt->bindParam(':uid', $userid, PDO::PARAM_STR);
        $srstmt->bindParam(':shippingcharg', $mynewShipCharge, PDO::PARAM_STR);
        $srstmt->bindParam(':total', $totalamt, PDO::PARAM_STR);
        $srstmt->bindParam(':crrid', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $srstmt->bindParam(':createdat',$globaldate, PDO::PARAM_STR);
		    $srstmt->bindParam(':payment_mode', $payment_mode, PDO::PARAM_STR);
    	  $srstmt->bindParam(':addid', $shipAddress, PDO::PARAM_INT);
        $srstmt->bindParam(':cpnid', $CoupanCode['id'], PDO::PARAM_INT);
        $srstmt->bindParam(':cpnuid', $CoupanCode['uid'], PDO::PARAM_INT);
        $srstmt->bindParam(':cpnamount', $discount, PDO::PARAM_STR);
        $srstmt->bindParam(':code', $CoupanCode['code'], PDO::PARAM_STR);
        $srstmt->bindParam(':deliverydate', $deliverydate, PDO::PARAM_STR);
        $srstmt->bindParam(':deliverytime', $deliverytime, PDO::PARAM_STR);
        $srstmt->execute();
		
		if($srstmt == true){
		
		$lastIdd = $conn->lastInsertId();
	
    //----------add to cart order table

    $maincartquerydata = $conn->prepare( "select * from cart where un_id = '".$_SESSION['UNIQUEID']."'");
    $maincartquerydata->execute();

    while($maincartdata = $maincartquerydata->fetch(PDO::FETCH_ASSOC))
    {   
      $stmt = $conn->prepare("INSERT INTO `cart_order_item`(`order_id`, `pid`, `price`, `qty`, `size_id`, `color_id`, `curr_ip`, `created_at`)

        VALUES (:order_id, :pid, :price, :qty, :size, :cs, :crrid, :createdat)");

        $stmt->bindParam(':order_id', $lastIdd, PDO::PARAM_INT);
        $stmt->bindParam(':pid', $maincartdata['pid'], PDO::PARAM_STR);
        $stmt->bindParam(':price', $maincartdata['price'], PDO::PARAM_STR);
        $stmt->bindParam(':qty', $maincartdata['qty'], PDO::PARAM_STR);
        $stmt->bindParam(':size', $maincartdata['size_id'], PDO::PARAM_INT);
        $stmt->bindParam(':cs', $maincartdata['color_id'], PDO::PARAM_INT);
        $stmt->bindParam(':crrid', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindParam(':createdat',$globaldate, PDO::PARAM_STR);
        $stmt->execute();	
    }
	
		$orderid = $_SESSION['orderid'] = $lastIdd;
		include 'send_order_email.php';
		echo "<script>window.location='success';</script>";
	}
   	else{
		echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';
	
	}
	

  }



?>
