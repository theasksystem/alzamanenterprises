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
  <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>المفضلات</span></h2>


</div>
<section class="cart">
<!-- top Products -->
<section class="checkout  py-md-3 py-sm-3 py-3" >
<div class="container py-md-4 py-sm-4 py-3">
    

<h3 class="fsz-25 ptb-15 text-right"><span class="light-font"></span> <strong>المفضلات</strong> </h3>
<div class="shop_inner_inf">
  <div class="privacy about" id="wishdata">
    <div class="checkout-right col-md-12 col-sm-12 col-xs-12">
      <table class="timetable_sub table-responsive">
        <thead>
          <tr>
            <th>رقم التسلسلي</th>
            <th>المنتجات </th>
            <th>اسم المنتج</th>
            <th>السعر</th>
            <th>رسوم الشحن والتوصيل</th>
            <th>المجموع</th>
            <th>اضف الى السلة</th>
            <th>حذف</th>
          </tr>
        </thead>
        <tbody>
        
          <?php
		  
			$x=1;
			$CARTTOTAL = 0;
			$deposit = 0;

			$maincartquery = $conn->prepare("select * from wishlist where un_id = '".$_SESSION['LOGIN_ID']."' order by id desc");
			$maincartquery->execute();

			if($maincartquery->rowCount()!=''){
			
			while($maincart = $maincartquery->fetch(PDO::FETCH_ASSOC))
			{
			    $myproduct=$maincart['pid'];
			
				$cartproductquery = $conn->prepare("select * from products where id = '$myproduct'");
				$cartproductquery->execute();
				$cartproduct = $cartproductquery->fetch(PDO::FETCH_ASSOC);
			
				$subtotal2 =  $maincart['price']*$maincart['qty'];
				$shippingcharg = $cartproduct['ship_charge']*$maincart['qty'];
				$subtotal  =  $subtotal2+$shippingcharg; 
				$CARTTOTAL+= $subtotal;
				$mysize = $cartproduct['p_size'];
				$mycolor = $cartproduct['p_color'];
				
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
            <td class="invert"><?php if($cartproduct['product_name_ar']!=''){echo $cartproduct['product_name_ar']; }else{ echo $cartproduct['product_name_en']; } ?></td>
            <td class="invert"><?=$maincart['price'].' ريال'; ?><input type="hidden" id="price<?= $cartproduct['id']; ?>" value="<?=$maincart['price']; ?>" /></td>
            
            <td class="invert"><?php if($shippingcharg!=''){ echo $shippingcharg.' ريال'; }else { echo '00'; } ?></td>
            <td class="invert"><?= $subtotal.' ريال'; ?></td>
            
            <?php if($mysize!='' || $mycolor!=''){ ?>
            <td class="invert"><a href="<?=$WebsiteUrl2.'/'.$cartproduct['slug']; ?>"><button class="site-btn" type="button">Select Options</button></a></td>
            <?php }else{ ?>
            <td class="invert"><button class="add-to-cart2 site-btn addtocart" id="<?= $cartproduct['id']; ?>" type="button">add to cart</button></td>
            <?php } ?>
            
            <td class="invert">
            <div class="rem"><a href="ajax_cart.php?delete_cart_id2=<?= base64_encode($maincart['id']); ?>"><i class="fa fa-trash" style="font-size:30px;color:red; cursor:pointer;" onclick="return confirm('Are you sure to remove this Product from Wishlist')"></i></a></div>
            
            </td>
          </tr>
          <?php $x++;}}else{ ?>
          <tr class="rem1">
            <td class="invert" colspan="8">Your Wishlist is empty</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php if($maincartquery->rowCount()!=''){ ?>
    <div class="checkout-left">
      <div class="col-md-12 checkout-left-basket">
        <ul>
          <li><span class="tlPrc">المبلغ الاجمالي : <?= $CARTTOTAL.' ريال'; ?></span></li><br />
        </ul>
      </div>
      `
      <div class="clearfix"> </div>
      <div class="clearfix"></div>
    </div>
    <?php } ?>
    
    
  </div>
  <!-- //top products -->
  </section>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 0px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle" style="font-size: 1em;">تم اضافة المنتج الى سلة المشتريات</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color: #fff;">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="shipping_details">
      
        
        
      </div>
      <div class="modal-footer">
        <a href="<?= $WebsiteUrl2.'/'; ?>cart"><button type="button" class="site-btn">سلة المشتريات</button></a>
        <button type="button" class="site-btn" data-dismiss="modal">اغلاق </button>
      </div>
    </div>
  </div>
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
 
 $(document).on('click', '.addtocart', function(){

        var product_id = $(this).attr("id");
        var pagetotal = $('#price'+product_id+'').val();
		var qty = 1;

        if(product_id=='' || pagetotal=='')
      {
        //alert("Please Select Quantity..");
      }
	  
      else
      {
	  
        var dataString = 'product_id='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty;
		//alert(dataString);
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl2.'/'; ?>ajax_cart.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $("#cartloader").show();
                },
                complete: function(){
					$("#cartloader").hide();
                },
                success: function(response){
				$("#wishdata").load(location.href + " #wishdata");
				$("#header2").load(location.href + " #header2");
				
				document.getElementById("shipping_details").innerHTML=response;
				$('#exampleModalCenter').modal('show');
				setTimeout(function(){
					$("#exampleModalCenter").modal("hide");
				}, 3000);
				
                }
            });
      }
    });

</script>

</body>
</html>
