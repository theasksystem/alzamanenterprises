<?php

session_start();
//error_reporting(0);
include('../include/db.class.php');
include('paynow.php');

if($_SESSION['LOGIN_ID']=='')
{
   echo "<script>window.location.href='".$WebsiteUrl2."/login-register'</script>";
}


$userData2 = $conn->prepare("select * from registration where id = '".$_SESSION['LOGIN_ID']."'");
$userData2->execute();
$userData = $userData2->fetch(PDO::FETCH_ASSOC);

$redeemableAmount = intdiv($userData['rewardpoints'],100);
$redeemablePoints = $redeemableAmount * 100;

if(isset($_GET['ud']) && $_GET['ud']!=''){

$ud = base64_decode(base64_decode($_GET['ud']));
$DelAdd = $conn->prepare("delete from tbl_address where id = '$ud'");
$DelAdd->execute();

echo "<script>window.location.href='checkout'</script>";

}
if(isset($_GET['myid']) && $_GET['myid']!=''){

$ud = base64_decode(base64_decode($_GET['myid']));
$DelAdd = $conn->prepare("delete from apply_coupan where id = '$ud'");
$DelAdd->execute();

echo "<script>window.location.href='checkout'</script>";

}
?>

<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
<link rel="stylesheet" type="text/css" href="<?= $WebsiteUrl2.'/'; ?>css/checkout.css">
<style>
.funkyradio div {
  clear: both;
  overflow: hidden;
}

.funkyradio label {
  width: 100%;
  border-radius: 3px;
  border: 1px solid goldenrod;
  font-weight: normal;
}

.funkyradio input[type="radio"]:empty,
.funkyradio input[type="checkbox"]:empty {
  display: none;
}

.funkyradio input[type="radio"]:empty ~ label,
.funkyradio input[type="checkbox"]:empty ~ label {
  position: relative;
  line-height: 2.5em;
  text-indent: 3.25em;
  margin-top: 2em;
  cursor: pointer;
  text-align: center;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.funkyradio input[type="radio"]:empty ~ label:before,
.funkyradio input[type="checkbox"]:empty ~ label:before {
  position: absolute;
  display: block;
  top: 0;
  bottom: 0;
  left: 0;
  content: '';
  width: 2.5em;
  /*background: goldenrod;*/
  border-radius: 3px 0 0 3px;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
  color: #888;
}

.funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
.funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #fff;
}

.funkyradio input[type="radio"]:checked ~ label,
.funkyradio input[type="checkbox"]:checked ~ label {
  color: #000;
}

.funkyradio input[type="radio"]:checked ~ label:before,
.funkyradio input[type="checkbox"]:checked ~ label:before {
  content: '\2714';
  text-indent: .9em;
  color: #fff;
  background-color: #fff;
}

.funkyradio input[type="radio"]:focus ~ label:before,
.funkyradio input[type="checkbox"]:focus ~ label:before {
  box-shadow: 0 0 0 3px #fff;
}

.funkyradio-success input[type="radio"]:checked ~ label:before,
.funkyradio-success input[type="checkbox"]:checked ~ label:before {
  color: #fff;
  background-color: black;
}
.woocommerce-info {
    font-size: 12px;
    line-height: 21px;
    border: 1px solid goldenrod !important;
    background-color: #fcfcfc !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    margin: 10px 0 20px 0 !important;
    padding: 10px !important;
}
.checkout_coupon {
    border-color: #ededed;
    border-radius: 0;
}
.form-row {
    padding: 3px;
    margin: 0 0 6px;
}
.input-text {
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    outline: 0;
    line-height: normal;
}
a.showcoupon {
    color: goldenrod;
}

</style>
<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / المنتجات  / <span>اكمال عملية الشراء</span></h2>
  
  
  
  </div>
  <section class="cart">
  <div class="container">
    <div class="row">
       
        
      <div class="col-md-8  col-sm-12 col-xs-12">
          
          <div class="text-right">
            
           <h2 class="mb-0">بيانات التوصيل</h2>    
            <div class="bilinfo">
                    <form action="" name="billingform" method="post" onsubmit="return validmy()">
                      <div class="row">
                                       
                        <div class="col-md-12  col-sm-12 col-xs-12">
                        
            <?php
		   
		    $userData2 = $conn->prepare("select * from tbl_address where user_id = '".$_SESSION['LOGIN_ID']."' order by setByDefault desc");
			$userData2->execute();
			while($userData = $userData2->fetch(PDO::FETCH_ASSOC)){
			
			$addressData =$conn->prepare("SELECT `name_ar` as country FROM `country` where id='".$userData['country']."'");
			$addressData->execute();
			$addressData2 = $addressData->fetch(PDO::FETCH_ASSOC);
			$addressData4 =$conn->prepare("SELECT `name_ar` as city FROM `city` where id='".$userData['city']."'");
			$addressData4->execute();
			$addressData24 = $addressData4->fetch(PDO::FETCH_ASSOC);
			
			?>
           <div class="_2HW10N">
           <div class="_1MIUfH">
          
           <p class="ZBYhh4 funkyradio" style="margin-top: -35px"><span class="_2Fw4MM funkyradio-success">
            
            <input type="radio" name="shipAddress" value="<?=$userData['id']; ?>" id="<?=$userData['id']; ?>" <?php if($userData['setByDefault']==1){ echo 'checked'; } ?>>
            <label for="<?=$userData['id']; ?>" style="width:36px;margin-right:10px;">&nbsp;</label>
               
               
               <?=ucfirst($userData['name']); ?> &nbsp;&nbsp;<i style="font-size: 13px;"><?=$userData['alt_mobile']; ?></i></span>
               
             <div class="iqngYe">
           <div class="_1suckO">
           <a id="UpdShow<?=$userData['id']; ?>"><i class="fa fa-edit" aria-hidden="true"></i></a> 
           <a href="checkout?ud=<?=base64_encode(base64_encode($userData['id'])); ?>" onClick="return confirm('Are you sure to remove this Address');"><i class="fa fa-trash" aria-hidden="true"></i></a>
			</div></div>
			
           <span class="_3MbGVP _2Fw4MM"></span></p>
           <span class="ZBYhh4 _1Zn3iq"><?php if($userData['state']!=''){ echo '<b>رقم المبنى - </b>'.$userData['state'];} ?><br><?php if($userData['zip']!=''){ echo '<b>منطقة - </b>'.$userData['zip'];} ?><br><?php if($userData['address']!=''){ echo '<b>اسم الشارع - </b>'.$userData['address'];} ?><br><?php if($userData['city']!=''){ echo $addressData24['city'].' - ';} ?><?php if($userData['country']!=''){ echo $addressData2['country'];} ?></span>
           </div>
           </div>
           
          <div class="_1yf-9T" id="UpdAdd<?=$userData['id']; ?>" style="display:none">
           <form action="" name="sign_up<?=$userData['id']; ?>" method="post">
                      <div class="row">
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="name<?=$userData['id']; ?>" id="name<?=$userData['id']; ?>" placeholder="الاسم" value="<?=ucfirst($userData['name']); ?>">
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="state<?=$userData['id']; ?>" id="state<?=$userData['id']; ?>" placeholder="رقم المبنى" value="<?=$userData['state']; ?>">
                            	
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="zip<?=$userData['id']; ?>" id="zip<?=$userData['id']; ?>" placeholder="منطقة" value="<?=$userData['zip']; ?>" >
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="address<?=$userData['id']; ?>" id="address<?=$userData['id']; ?>" placeholder="اسم الشارع" value="<?=$userData['address']; ?>">
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="country<?=$userData['id']; ?>" id="country<?=$userData['id']; ?>" onChange="fetch_select<?=$userData['id']; ?>(this.value);">
                            	<option value="">البلد</option>
                                <?php
                                     $query = $conn->prepare("SELECT id,name_ar FROM country WHERE visible=1 order by name_en ASC");
										$query->execute();

									while ($row = $query->fetch(PDO::FETCH_ASSOC))
                                     {
                               ?>
                               <option <?php if($row['id']==$userData['country']){ echo 'selected'; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['name_ar']; ?></option>
                               <?php } ?>
                            </select>
                            
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="city<?=$userData['id']; ?>" id="city<?=$userData['id']; ?>" >
                            	<option value="">المدينة</option>
                                <?php
                                    $query3 = $conn->prepare("SELECT id,name_ar FROM city WHERE visible=1 and country_id='".$userData['country']."'");
									$query3->execute();
									while ($row3 = $query3->fetch(PDO::FETCH_ASSOC))
                                     {
                               ?>
                               <option <?php if($row3['id']==$userData['city']){ echo 'selected'; } ?> value="<?php echo $row3['id']; ?>"><?php echo $row3['name_ar']; ?></option>
                               <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="alt_mobile<?=$userData['id']; ?>" id="alt_mobile<?=$userData['id']; ?>" value="<?=$userData['alt_mobile']; ?>" placeholder="+974-31559977" >
                          </div>
                        </div>
                                               
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" name="update" id="addAddress<?=$userData['id']; ?>" value="تحديث العنوان" class="checkout-btn">
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" id="cancel<?=$userData['id']; ?>" value="الالغاء" class="checkout-btn">
                          </div>
                        </div>
                        <div><img id="subsloader<?=$userData['id']; ?>" height="100" style="display: none;" src="<?=$WebsiteUrl.'/'; ?>images/loader.gif"></div>
                      </div>
                    </form>
           </div>
           
           <?php } ?> 
           <div class="_1yf-9T">
             	<div>
                	<div class="_2kr2AM  text-right" id="youradd">
                    	<i class="fa fa-plus" aria-hidden="true"></i>اضافة عنوان جديد
                    </div>
               </div>
           </div>
           <div class="_1yf-9T" id="myadd" style="display:none;">
                      <div class="row">
                          <div class="col-md-12  col-sm-12 col-xs-12">
                              <div class="single-input">
                          <div class="heading-main">
                            <h2 style="padding-left: 0px;"><span><center><b>يرجى منكم تعبئة البيانات التالية، لتتمتعو بخدمة تسليم سلسة و سريعة🚚</b></center></span></h2>
                          </div>
                          </div>
                        </div>
                        <div class="col-md-8  col-sm-12 col-xs-12">
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="name" id="name" placeholder="الاسم">
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="state" id="state" placeholder="رقم المبنى">
                            	
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="zip" id="zip" placeholder="منطقة">
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="address" id="address" placeholder="اسم الشارع">
                          </div>
                        </div>
                        </div>
                        
                        <div class="col-md-4  col-sm-12 col-xs-12">
                           <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input"> 
                            <img src="<?= $WebsiteUrl.'/'; ?>images/address.jpg" class="img-responsive">
                          </div>
                          </div>
                          
                        </div>
                        
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="country" id="country" onChange="fetch_select(this.value);">
                            	<option value="">البلد</option>
                                <?php
                                     $query = $conn->prepare("SELECT id,name_ar FROM country WHERE visible=1");
										$query->execute();

									while ($row = $query->fetch(PDO::FETCH_ASSOC))
                                     {
                               ?>
                               <option value="<?php echo $row['id']; ?>"><?php echo $row['name_ar']; ?></option>
                               <?php } ?>
                            </select>
                            
                          </div>
                        </div>
                        
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="city" id="city" >
                            	<option value="">المدينة</option>
                                
                            </select>
                          </div>
                        </div>

                        <!--<div class="col-md-3">
                          <div class="single-input">
                            <input type="text" name="country_code" id="country_code" placeholder="رمز الدولة" readonly>
                          </div>
                        </div>-->
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="alt_mobile" id="alt_mobile" placeholder="+974-31559977" >
                          </div>
                        </div>
                                               
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" name="update" id="addAddress" value="اضافة العنوان" class="checkout-btn">
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" id="cancel" value="الالغاء" class="checkout-btn">
                          </div>
                        </div>
                        <div><img id="subsloader" height="100" style="display: none;" src="<?=$WebsiteUrl.'/'; ?>images/loader.gif"></div>
                      </div>
           </div>
                        
           </div>
           <!-- Delivery Date & Time Selection -->
           <div>
                    <h2>&nbsp;تاريخ ووقت التوصيل</h2></br>
                    <div>
                      <div class="row">
                        <div class="deliverydate">
                          <label for="deliverydate">&emsp; اختر التاريخ&nbsp;&nbsp;&emsp;</label>
                            <select name="deliverydate" id="deliverydate" required>
                              <?php 
                                $date = new DateTime(null, new DateTimeZone('Asia/Qatar'));
                                $hour = $date->format('H');
                                $excludeTimeSlots = true;
                                for( $i = 1; $i<=3 ; $i++)  {
                                  if($i != 1)
                                    $date->add(new DateInterval('P1D'));
                                  if($hour >= 20 && $i == 1)  {
                                    $excludeTimeSlots = false;
                                    $date->add(new DateInterval('P1D'));
                                  }
                                ?>
                                <option value="<?php echo $date->format('Y-m-d');?>"><?php echo $date->format('Y-m-d')?></option>
                              <?php  }  ?>
                            </select>
                        </div>
                      </div></br>
                      <div class="row">
                        <div class="deliverytime">
                          <label for="deliverytime">&emsp;اختر التوقيت &emsp;</label>
                            <select name="deliverytime" id="deliverytime" required>
                                <?php if ($hour < 12 || $excludeTimeSlots == false) { ?>
                                  <option value="12PM-4PM" >12PM-4PM</option>
                                <?php } ?>
                                <?php if ($hour < 16 || $excludeTimeSlots == false) { ?>
                                  <option value="4PM-8PM"  >4PM-8PM</option>
                                <?php } ?>
                                <?php if ($hour < 20 || $excludeTimeSlots == false) { ?>
                                  <option value="8PM-12AM" >8PM-12AM</option>
                                <?php } ?>
                            </select>
                        </div>        
                      </div>
                    </div>
                </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                       
                        <div class="float-right col-md-12  col-sm-12 col-xs-12">
                      <!--  <input type="radio" name="payment_mode" value="1" checked>
                              &nbsp;&nbsp;&nbsp;<span>Cash On Delievery</span>
                        &nbsp;&nbsp;&nbsp; <input type="radio" name="payment_mode" value="2">     
                              &nbsp;&nbsp;&nbsp;<span>Store Pickup</span>-->
                              
    <div class="funkyradio col-md-12  col-sm-12 col-xs-12 areyou-error">
    <div class="row">
        <div class="funkyradio-success col-md-6  col-sm-12 col-xs-12">
            <input type="radio" name="payment_mode" value="1" id="checkbox1">
            <label for="checkbox1">دفع عند الاستلام</label>
        </div>
        <div class="funkyradio-success col-md-6  col-sm-12 col-xs-12">
            <input type="radio" name="payment_mode" value="2" id="checkbox2">
            <label for="checkbox2">الاستلام من المتجر</label>
        </div>
     </div>
    </div>        
                            </div>
                         </div>
                         <input type="checkbox" id="redeem-amt" name="redeem-amt" value="0" style="opacity:0;" checked>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="submit" name="pay_now" value="اتمام عملية الطلب" class="checkout-btn">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  
           
            
          </div>
      </div>
      
      <div class="col-md-4  col-sm-12 col-xs-12">
        <div class="order-details">
          <h5 class="order-details__title">طلباتك </h5>
          <?php
		  
			$x=1;
			$CARTTOTAL = 0;
			$isDelieveryFree = 0;
			$myshippingCharge = 0;
			$TOTQTY = 0;

			$maincartquery = $conn->prepare("select * from cart where un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."' order by id desc");
			$maincartquery->execute();

			if($maincartquery->rowCount()!=''){
			
			while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC))
			{
			    $myproduct=$maincart['pid'];
			
				$cartproductquery = $conn->prepare("select a.*,b.company from products a left join tbl_admin b ON a.user_id=b.id where a.id = '$myproduct'");
				$cartproductquery->execute();
				$cartproduct = $cartproductquery->fetch(PDO::FETCH_ASSOC);
			
				$sameProduct = $conn->prepare("select SUM(qty) as cartqty,SUM(price*qty) as cartTot from cart where un_id = '".$_SESSION['UNIQUEID']."' and pid = '$myproduct' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."' and pid = '$myproduct'");
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
				$TOTQTY+= $maincart['qty'];
				
		  ?>
          <div class="order-details__item">
            <div class="single-item">
              <div class="single-item__thumb"> 
              <?php if(!empty($cartproduct['image'])){ ?>
              <img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $cartproduct['image']; ?>" alt="ordered item"> 
              <?php } ?>
              </div>
              <div class="single-item__content" align="right"> 
              <a href="#"><?=$cartproduct['product_name_ar']; ?></a> 
              <span class="price"><?=$maincart['price'].' ريال'; ?> <?= $maincart['qty']; ?>x </span>
              <span class="price"><?= 'المجموع = '.$subtotal.' ريال'; ?></span> 
              </div>
              <div class="single-item__remove"> <a href="#"><i class="zmdi zmdi-delete"></i></a> </div>
            </div>
          </div>
          <?php $x++;}}?>
          <div class="order-details__count">
            <div class="order-details__count__single">
              <h5>Total</h5>
              <span class="price"><?=$CARTTOTAL.' ريا'; ?></span></div>
          </div>
          <?php if($redeemablePoints >= 1000) { ?>
                <div class="order-details__count">
                  <div class="order-details__count__single">
                    <h5>استخدم النقاط المكتسبة</h5>
                    <span class="redeem-price" style="width:30%;text-align: left;font-weight: 600;"><?php echo 'ريال '.$redeemableAmount ?>&emsp;&nbsp;<input class="redeem-check" type="checkbox" id="redeem" name="redeem" value="<?=$redeemableAmount?>" style="display:inline;width:20px;height:20px;vertical-align: middle;"></span>
                  </div>  
                </div>
              <?php } ?>
          <div class="order-details__count">
            <div class="order-details__count__single">
              <h5>الشحن والتوصيل</h5>
              <span class="price"><?php if($isDelieveryFree==1){ echo 'الشحن مجاني'; }else{ echo $myshippingCharge.' ريال'; } ?></span></div>
          </div>
          <?php if($discount!=''){ ?> 
         <div class="order-details__count">
            <div class="order-details__count__single">
              <h5>Coupan Value</h5>
              <span class="price">- <?=$discount.' ريا'; ?>
              <a href="checkout?myid=<?=base64_encode(base64_encode($CoupanCode['myId'])); ?>" data-toggle="tooltip" title="Click Here to Change the Coupan" style="float:right;" onclick="return confirm('Are you sure to remove this Coupan Code.');">
              <i class="fa fa-trash" aria-hidden="true" style="color:red"></i>
              </a>
              </span></div>
          </div>
          <?php }else{ ?>
        	<p class="form-row form-row-last  col-md-4 col-sm-4 col-xs-4">
        		<input type="button" class="checkout-btn" id="apply_coupon" name="apply_coupon" value="Apply coupon" style="height: 42px !important;padding: 0px !important;font-size: 16px;">
        	</p>
        	<p class="form-row form-row-first col-md-8 col-sm-8 col-xs-8">
        		<input type="text" name="coupon_code" class="input-text form-control" placeholder="كود الخصم" id="coupon_code" value="">
        	</p>
        
        	
            <div><img id="subsloaderrr" height="60" style="display: none;" src="<?= $WebsiteUrl.'/'; ?>images/loader.gif"></div>
            <span id="cpnmsg" style="color:red;"></span>
        	<div class="clear"></div>
		  <?php } ?>
		  <div class="order-details__count">
            <div class="order-details__count__single">
              <h5>المجموع </h5>
              <span class="price" id="totalPrice"><?php if($isDelieveryFree==1){ echo ($CARTTOTAL-$discount).' ريال'; }else{ echo (($CARTTOTAL+$myshippingCharge)-$discount).' ريال'; } ?></span></div>
          </div>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>
<script >
$(document).ready(function(){
	
$(".showcoupon").click(function(){
    $(".woocommerce-form-coupon").toggle(500);
  });
  
	$("#cancel").click(function(){
		$("#myadd").hide();
	});
		
	$("#youradd").click(function(){
		$("#myadd").toggle();
	});
	
	
});	
</script>

<script type="text/javascript">
		
function fetch_select(val)
{
   //alert(val); 
	 $.ajax({
	 type: 'post',
	 url: '<?= $WebsiteUrl2.'/'; ?>getvalue.php',
	 data: {tab:val},
	 success: function (response) {
	   //alert(response);
	   document.getElementById('city').innerHTML=response; 
	 }
	 });

}

$('#apply_coupon').on("click", function(){
	
	var coupon_code = $("#coupon_code").val();		
    if(coupon_code==''){
	  
        $("#coupon_code").css({'border':'red 1px solid','background-color':'#eee'});
		return false;
    }
  else{
       
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>login.php",
                data: {'coupon_code':coupon_code,'totQty':<?=$TOTQTY; ?>,'totAmount':<?=$CARTTOTAL; ?>},
                cache: false,
				beforeSend: function(){
				
                        $( "#subsloaderrr" ).show();
                    },
                    complete: function(){
                        $( "#subsloaderrr" ).hide();
                    },
                    success: function(data){
                          if(data==1){
                            location.reload();  
                          }else{                                       
                          
						  $('#cpnmsg').html('This Coupan Is Not Valid.'); 
                          }

                    }
                
            });
     }

});

$(".deliverydate").change(function(){
  var selectedDate =  $('.deliverydate option:selected').text();
  selectedDate = selectedDate.substring(selectedDate.lastIndexOf("-")+1);
  var currentDate = new Date().getDate().toLocaleString("en-US", {timeZone: "Asia/Qatar"});
  if (selectedDate != currentDate)  {
    $('.deliverytime').html("<label for=\"deliverytime\">&emsp;اختر التوقيت &emsp;</label>\
                      <select name=\"deliverytime\" id=\"deliverytime\" required>\
                        <option value=\"12PM-4PM\" >12PM-4PM</option>\
                        <option value=\"4PM-8PM\"  >4PM-8PM</option>\
                        <option value=\"8PM-12AM\" >8PM-12AM</option>\
                      </select>")
  } else {
    $('.deliverytime').html("<label for=\"deliverytime\">&emsp;اختر التوقيت &emsp;</label>\
                      <select name=\"deliverytime\" id=\"deliverytime\" required>\
                          <?php if ($hour < 12 || $excludeTimeSlots == false) { ?>\
                            <option value=\"12PM-4PM\" >12PM-4PM</option>\
                          <?php } ?>\
                          <?php if ($hour < 16 || $excludeTimeSlots == false) { ?>\
                            <option value=\"4PM-8PM\"  >4PM-8PM</option>\
                          <?php } ?>\
                          <?php if ($hour < 20 || $excludeTimeSlots == false) { ?>\
                            <option value=\"8PM-12AM\" >8PM-12AM</option>\
                          <?php } ?>\
                      </select>")
  }

  
});

$('#addAddress').on("click", function(){
	
	var name = $("#name").val();
	var address = $("#address").val();
	var country = $("#country").val();
    var state = $("#state").val();
	var city = $("#city").val();
    var zip = $("#zip").val();
	var alt_mobile = $("#alt_mobile").val();		

     if(name==''){
	  
        $("#name").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.name.focus();
		return false;
    }
    if(state=='')
    {
	   $("#state").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.state.focus();
	   return false;
    
	}
	if(zip=='')
    {
	   $("#zip").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.zip.focus();
	   return false;
    
	}
	if(address=='')
    {
	  
        $("#address").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.address.focus();
		return false;
    }
	

	if(country=='')
    {
	   $("#country").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.country.focus();
	   return false;
    }
	if(city=='')
    {
	   $("#user_cpass").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.city.focus();
	   return false;
    }
	if(alt_mobile=='')
    {
	   $("#alt_mobile").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.alt_mobile.focus();
	   return false;
    
	}else{
       
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl2.'/'; ?>login.php",
                data: {'name':name,'address':address,'country':country,'state':state,'city':city,'zip':zip,'alt_mobile':alt_mobile},
                cache: false,
				beforeSend: function(){
				
                        $( "#subsloader" ).show();
                    },
                    complete: function(){

                    },
                    success: function(data){
                        
                        setTimeout(function() {                                          
                          $( "#subsloader" ).hide();
						  
						  $("#myadd").hide();
						  location.reload();
                    }, 2000);

                    }
                
            });
     }

});
</script>
<script>
function validmy(){
	var address = $("input[name='shipAddress']:checked").val();
	var mode =   $("input[name='payment_mode']:checked").val();
	if(address==undefined)
      {
	  	//alert(size);
        alert("الرجاء اختيار   عنوان التوصيل");
		return false;
      }
	  if(mode==undefined)
      {
	  	
		//alert(cs);
        alert("الرجاء اختيار الدفع عند الاستلام او الاستلام  من المتجر");
		return false;
      }
	return true;
}

</script>

 <script>
 
         <?php
            $userData4 = $conn->prepare("select id from tbl_address where user_id = '".$_SESSION['LOGIN_ID']."' order by setByDefault desc");
			$userData4->execute();
			while($userData3 = $userData4->fetch(PDO::FETCH_ASSOC)){
        ?>

				$("#cancel<?=$userData3['id']; ?>").click(function(){
					$("#UpdAdd<?=$userData3['id']; ?>").hide();
				});
					
				$("#UpdShow<?=$userData3['id']; ?>").click(function(){
					$("#UpdAdd<?=$userData3['id']; ?>").toggle();
				});
				
				function fetch_select<?=$userData3['id']; ?>(val)
				{
				   //alert(val); 
					 $.ajax({
					 type: 'post',
					 url: '<?= $WebsiteUrl.'/'; ?>getvalue.php',
					 data: {tab:val},
					 success: function (response) {
					   // alert(response);
					   document.getElementById('city<?=$userData3['id']; ?>').innerHTML=response; 
					 }
					 });
					 
				}
			
				
				
				$('#addAddress<?=$userData3['id']; ?>').on("click", function(){
					
					var name = $("#name<?=$userData3['id']; ?>").val();
					var address = $("#address<?=$userData3['id']; ?>").val();
					var country = $("#country<?=$userData3['id']; ?>").val();
                    var state = $("#state<?=$userData3['id']; ?>").val();
					var city = $("#city<?=$userData3['id']; ?>").val();
					var zip = $("#zip<?=$userData3['id']; ?>").val();	
					var alt_mobile = $("#alt_mobile<?=$userData3['id']; ?>").val();	
				
					if(name==''){
					  
						$("#name<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
						document.sign_up<?=$userData3['id']; ?>.name<?=$userData3['id']; ?>.focus();
						return false;
					}
					if(state=='')
                    {
                	   $("#state<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
                	   document.sign_up<?=$userData3['id']; ?>.state<?=$userData3['id']; ?>.focus();
                	   return false;
                    
                	}
                	if(zip=='')
                    {
                	   $("#zip<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
                	   document.sign_up<?=$userData3['id']; ?>.zip<?=$userData3['id']; ?>.focus();
                	   return false;
                    
                	}
					if(address=='')
					{
					  
						$("#address<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
						document.sign_up<?=$userData3['id']; ?>.address<?=$userData3['id']; ?>.focus();
						return false;
					}
					
					
					if(country=='')
					{
					   $("#country<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
					   document.sign_up<?=$userData3['id']; ?>.country<?=$userData3['id']; ?>.focus();
					   return false;
					}
					if(city=='')
					{
					   $("#city<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
					   document.sign_up<?=$userData3['id']; ?>.city<?=$userData3['id']; ?>.focus();
					   return false;
					}
					if(alt_mobile=='')
					{
					   $("#alt_mobile<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
					   document.sign_up<?=$userData3['id']; ?>.alt_mobile<?=$userData3['id']; ?>.focus();
					   return false;
					
					}else{
					   
						$.ajax({
								type: "POST",
								url: "<?= $WebsiteUrl2.'/'; ?>getvalue.php",
								data: {'name':name,'address':address,'country':country,'state':state,'city':city,'zip':zip,'addid':<?=$userData3['id']; ?>,'alt_mobile':alt_mobile},
								cache: false,
								beforeSend: function(){
								
										$( "#subsloader<?=$userData3['id']; ?>" ).show();
									},
									complete: function(){
				
									},
									success: function(data){
										
										setTimeout(function() {                                          
										  $( "#subsloader<?=$userData3['id']; ?>" ).hide();
										  $("#UpdAdd<?=$userData3['id']; ?>").hide();
										  location.reload(); 
									}, 2000);
				
									}
								
							});
					 }
				
				});
				
				
<?php } ?>				

$('.redeem-check').change(function() {
    if(this.checked) {
      <?php $CARTTOTAL=$CARTTOTAL-$redeemableAmount; 
      if($isDelieveryFree!=1)
        $CARTTOTAL+=$myshippingCharge; ?>
      $('#totalPrice').html('<?=$CARTTOTAL?> ريال');
      $('#redeem-amt').val("<?=$redeemableAmount?>");    
    } else {
      <?php $CARTTOTAL+=$redeemableAmount;  ?>
      $('#totalPrice').html('<?=$CARTTOTAL?> ريال ');
      $('#redeem-amt').val("0"); 
    }
                    
});


			</script>


</body>
</html>
