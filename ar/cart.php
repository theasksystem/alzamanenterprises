<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

?>

<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
<link rel="stylesheet" type="text/css" href="<?= $WebsiteUrl2.'/'; ?>css/checkout.css">

<div class="main-dv-sec" >
<div class="heading-main">
  <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <a href="<?= $WebsiteUrl2.'/'; ?>products">المنتجات</a> / <span> سلة المشتريات </span></h2>



</div>
<section class="cart">
<!-- top Products -->
<section class="checkout  py-md-3 py-sm-3 py-3" >
<div class="container py-md-4 py-sm-4 py-3">

<h3 class="fsz-25 ptb-15 text-right"><span class="light-font"></span> <strong>سلة المشتريات</strong> </h3>

<div class="shop_inner_inf"> 
  <div class="privacy about">
    <div class="checkout-right">
      <table class="timetable_sub">
        <thead>
          <tr>
            <th>رقم التسلسلي</th>
            <th>المنتجات</th>
            <th>اسم المنتج</th>
            <th>السعر</th>
            <th>الكمية</th>
            <!--<th>رسوم الشحن والتوصيل</th>-->
            <th>المجموع</th>
            <th>حذف</th>
          </tr>
        </thead>
        <tbody>
        
          <?php
		  
			$x=1;
			$CARTTOTAL = 0;
			$isDelieveryFree = 0;
			$myshippingCharge = 0;

			$maincartquery = $conn->prepare("select * from cart where un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."' order by id desc");
			$maincartquery->execute();

			if($maincartquery->rowCount()!=''){
			
			while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC))
			{
			    $myproduct=$maincart['pid'];
			
				$cartproductquery = $conn->prepare("select * from products where id = '$myproduct'");
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
				
				$size2 = $conn->prepare("select size_ar,size from products_size where id = '".$maincart['size_id']."'");
				$size2->execute();
				$size3 = $size2->fetch(PDO::FETCH_ASSOC);
				if($size3['size_ar']!=''){ $size = $size3['size_ar']; }else{ $size = $size3['size']; }
				
				$color2 = $conn->prepare("select color_ar,color from products_color where id = '".$maincart['color_id']."'");
				$color2->execute();
				$color3 = $color2->fetch(PDO::FETCH_ASSOC);
				if($color3['color_ar']!=''){ $color = $color3['color_ar']; }else{ $color = $color3['color']; }
				if($size!=''){ $size2=$size; }else{ $size2='N/A'; }
				if($color!=''){ $color2=$color; }else{ $color2='N/A'; }	
				
		  ?>
          <tr class="rem1">
            <td class="invert"><?= $x; ?></td>
            <td class="invert-image">
            <?php if(!empty($cartproduct['image'])){ ?>
            <img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $cartproduct['image']; ?>" style="width: 70px;" class="img-responsive">
            <?php }else{ ?>
            <img src="images/product-p1.jpeg" style="width: 70px;" class="img-responsive">
            <?php } ?>
            </td>
            <td class="invert"><?php if($cartproduct['product_name_ar']!=''){echo $cartproduct['product_name_ar']; }else{ echo $cartproduct['product_name_en']; } ?><br>(الحجم: <?=$size2; ?>)<br>(اللون: <?=$color2; ?>)</td>
            <td class="invert"><?=$maincart['price'].' ريال'; ?></td>
            <form>
            <td class="invert">
            <div class="quantity">
                <div class="quantity-select">
                  
                  <form>
                    <div class="custom-qty" style="display: inline-flex;">
<button onClick="var result = document.getElementById('qty<?= $x; ?>'); var qty22 = result.value; if( !isNaN( qty22 ) &amp;&amp; qty22 &gt; 1 ) result.value--;return false;" class="reduced items qtybutton<?= $x; ?>" type="button"> <i class="fa fa-minus"></i> </button>
<input type="number" class="input-text qty" title="Qty" value="<?= $maincart['qty']; ?>" maxlength="8" id="qty<?= $x; ?>" name="qty" style="width:40px;text-align: center;max-width:100px;" readonly>
<button onClick="var result = document.getElementById('qty<?= $x; ?>'); var qty22 = result.value; if( !isNaN( qty22 )) result.value++;return false;" class="increase items qtybutton<?= $x; ?>" type="button"> <i class="fa fa-plus"></i> </button>
                                    </div>
					<input type="hidden" value="<?= base64_encode($maincart['id']); ?>" id="itemcatid<?= $x; ?>" />
                   </form>
                  
                </div>
            </div>
            </td>
            </form>  
            <!--<td class="invert"><?php if($shippingcharg!=''){ echo $shippingcharg.' ريال'; }else { echo '00'; } ?></td>-->
            <td class="invert"><?= $subtotal.' ريال'; ?></td>
            <td class="invert">
            <div class="rem"><i class="fa fa-trash" style="font-size:30px;color:red; cursor:pointer;" id="remove_cart<?= $x; ?>"></i></div>
            <input type="hidden" value="<?= base64_encode($maincart['id']); ?>" id="delete_cart_id<?= $x; ?>" />
            </td>
          </tr>
          <?php $x++;}}else{ ?>
          <tr class="rem1">
            <td class="invert" colspan="8">Your Cart is empty</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php if($maincartquery->rowCount()!=''){ ?>
    <div class="checkout-left">
      <div class=" col-md-12 col-sm-12 col-xs-12 checkout-left-basket">
        <ul>
          <li><span class="tlPrc">الشحن والتوصيل : <?php if($isDelieveryFree==1){ echo 'الشحن مجاني'; }else{ echo $myshippingCharge.' ريال'; } ?></span></li><br>
        </ul>
      </div>
      <div class=" col-md-12 col-sm-12 col-xs-12 checkout-left-basket">
        <ul>
          <li><span class="tlPrc">المبلغ الاجمالي : <?php if($isDelieveryFree==1){ echo $CARTTOTAL.' ريال'; }else{ echo ($CARTTOTAL+$myshippingCharge).' ريال'; } ?></span></li>        
        </ul>
      </div>
      `
      <div class="clearfix"> </div>
      <div class="clearfix"></div>
    </div>
    <?php if($_SESSION['LOGIN_ID']!=''){ ?>
    <div class="new-tbn"> <a href="checkout">اتمام عملية الطلب </a>&nbsp; <a href="<?= $WebsiteUrl; ?>"> الاستمرار في التسوق</a>  </div>
    <?php }else{ ?>
    <div class="new-tbn"><a href="login-register" onClick="return confirm('لم تقم بتسجيل الدخول يرجى تسجيل الدخول اولا ثم اكمال عملية الطلب');">اتمام عملية الطلب</a> <a href="<?= $WebsiteUrl; ?>">الاستمرار في التسوق</a> </div>
    <?php } ?>
    
    <?php } ?>
    
    
  </div>
  <!-- //top products -->
  </section>
</div>

<?php include('footer.php'); ?>

<script>$(document).ready(function(){var owl=$('.owl-carousel');owl.owlCarousel({items:6,loop:true,margin:10,autoplay:true,autoplayTimeout:10000,autoplayHoverPause:true,responsiveClass:true,dots:false,responsive:{360:{items:1,},480:{items:2,},600:{items:3,},1000:{items:6,}}});$('.play').on('click',function(){owl.trigger('play.owl.autoplay',[1000])})
$('.stop').on('click',function(){owl.trigger('stop.owl.autoplay')})})</script>
<script type="text/javascript">
window.onscroll = function() {myFunction()};
var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;
function myFunction() {
  if (window.pageYOffset >= sticky) {
	navbar.classList.add("sticky")
  } else {
	navbar.classList.remove("sticky");
  }
};
</script>

<!--quantity-->
<script>
 $('.value-plus').on('click', function () {
  var divUpd = $(this).parent().find('.value'),
	newVal = parseInt(divUpd.text(), 10) + 1;
  divUpd.text(newVal);
 });
 
 $('.value-minus').on('click', function () {
  var divUpd = $(this).parent().find('.value'),
	newVal = parseInt(divUpd.text(), 10) - 1;
  if (newVal >= 1) divUpd.text(newVal);
 });
</script>
<!--quantity-->
<!--closed-->
<script>
 $(document).ready(function (c) {
  $('.close1').on('click', function (c) {
	$('.rem1').fadeOut('slow', function (c) {
	  $('.rem1').remove();
	});
  });
 });
</script>
<script>
 $(document).ready(function (c) {
  $('.close2').on('click', function (c) {
	$('.rem2').fadeOut('slow', function (c) {
	  $('.rem2').remove();
	});
  });
 });
</script>
<script>
 $(document).ready(function (c) {
  $('.close3').on('click', function (c) {
	$('.rem3').fadeOut('slow', function (c) {
	  $('.rem3').remove();
	});
  });
 });
</script>
<!--//closed-->


<script>
$(document).ready(function(){
<?php

	$y=1;
	$CARTTOTAL2 = 0;

	$maincartquery2 = $conn->prepare( "select * from cart where un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
	$maincartquery2->execute();

	while($maincart2 = $maincartquery2->fetch(PDO::FETCH_ASSOC))
	{

?>
//==========================================Quantity Manage Cart Item Ajax=====================================
 $(".qtybutton<?= $y; ?>").click(function(){
         
 var qty = document.getElementById("qty<?= $y; ?>").value;
 var itemcatid = document.getElementById("itemcatid<?= $y; ?>").value;

 if(qty=='')
 {
	alert("يرجى ادخال الكمية المناسبة");
	return false;
 }
 else
 {

   $.ajax({
		type: "POST",
		url: "<?= $WebsiteUrl2.'/'; ?>ajax_cart.php",
		data: {'cart_item_qty':qty, 'cart_item_id':itemcatid},
		cache: false,
		success: function(s){
		window.location.reload();
		}
	});
  }
    });
	
 $("#qty<?= $y; ?>").change(function(){
         
 var qty = document.getElementById("qty<?= $y; ?>").value;
 var itemcatid = document.getElementById("itemcatid<?= $y; ?>").value;

 if(qty=='')
 {
	alert("يرجى ادخال الكمية المناسبة");
	return false;
 }
 else
 {

   $.ajax({
		type: "POST",
		url: "<?= $WebsiteUrl2.'/'; ?>ajax_cart.php",
		data: {'cart_item_qty':qty, 'cart_item_id':itemcatid},
		cache: false,
		success: function(s){
		window.location.reload();
		}
	});
  }
    });
	
//==========================================Delete Cart Item Ajax=====================================	
	

 $("#remove_cart<?= $y; ?>").click(function(){
         
 var delete_cart_id = document.getElementById("delete_cart_id<?= $y; ?>").value;

//alert(delete_cart_id);
   $.ajax({
		type: "POST",
		url: "<?= $WebsiteUrl2.'/'; ?>ajax_cart.php",
		data: {'delete_cart_id':delete_cart_id},
		cache: false,
		
		success: function(s){
		window.location.reload();
		}
	});
    });
	
<?php $y++;} ?>		
	
});
</script>	

</body>
</html>
