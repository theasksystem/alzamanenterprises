<?php

session_start();
error_reporting(0);
include('../include/db.class.php');
include('../include/functions.php');

$storeId= base64_decode(base64_decode(base64_decode($_GET['own'])));
$getProductdetail = $conn->prepare("SELECT id,quantity,discount,discount_type,slug,image,p_size,p_color,product_name_ar,price,old_price FROM products WHERE user_id = '$storeId' and status = 1 order by id desc");
$getProductdetail->execute();

$followdetail = $conn->prepare("SELECT user_id from tbl_follow WHERE store_id = '$storeId' and user_id = '".$_SESSION['LOGIN_ID']."'");
$followdetail->execute();

$followCount = $conn->prepare("SELECT count(*) as total from tbl_follow WHERE store_id = '$storeId'");
$followCount->execute();
$followCountData = $followCount->fetch(PDO::FETCH_ASSOC);

$getSellerdetail = $conn->prepare("SELECT id,company FROM tbl_admin WHERE id = '$storeId'");
$getSellerdetail->execute();
$getSellerRow = $getSellerdetail->fetch(PDO::FETCH_ASSOC);

?>
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>

<section class="product-filter-section" style="padding-top:20px">
		<div class="container-fluid">
			<div class="section-title">
				<h2><?=$getSellerRow['company']; ?> المنتجات
        <?php  if($_SESSION['LOGIN_ID']!=''){
          if( $followdetail->rowCount() > 0 )  { ?>
            <span class="follow"><a class="fa fa-check-circle" aria-hidden="true" href="#" ><span style="font-family: sans-serif">&nbsp;Following</span></a><br><span class="follow-count" style="color: grey;text-align:right;font-size:large;"><?=$followCountData['total']?> Followers</span></span> 
          <?php } else { ?>
            <span class="follow"><a href="<?= $WebsiteUrl.'/'; ?>follow.php?store='<?=base64_encode(base64_encode(base64_encode($storeId)));?>'">Follow&nbsp;Us&nbsp;&nbsp;<i class="fas fa-bell"></i></a><br><span class="follow-count" style="color: grey;text-align:left;font-size:large;"><?=$followCountData['total']?> Followers</span></span>
          <?php } }?>
        </h2>
      </div>
		
			<div class="row">
            
            <?php
			$d=1;
			if($getProductdetail->rowCount() > 0){ 
		
				while($getFeaturedRow = $getProductdetail->fetch(PDO::FETCH_ASSOC)){
                $TotalQuantity = TotalQuantity($conn,$getFeaturedRow['id']);
				$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getFeaturedRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
					$totalsales->execute();
					$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
					$stockrest=$totalsaleamt['total_sale'];
					$totalstock=$TotalQuantity-$stockrest;

?>

       <div class="col-lg-2 col-md-3 col-sm-6 col-xs-6">
			<div class="product-item">
					<div class="pi-pic">
					    <?php  if($totalstock > 0){ ?>
                    <?php if($getFeaturedRow['discount']!='' && $getFeaturedRow['discount_type']!='' && $getFeaturedRow['discount_type']!=0){ ?>
						<div class="tag-new">
                        <?php if($getFeaturedRow['discount_type']==1){ echo '%'.$getFeaturedRow['discount'].' خصم'; }elseif($getFeaturedRow['discount_type']==2){ echo 'خصم '.$getFeaturedRow['discount'].' ريال'; } ?>
                        </div>
                    <?php }}else{ ?>
                    <div class="tag-new2">نفدت الكمية</div><?php  } ?>
						<a href="<?=$WebsiteUrl2.'/'.$getFeaturedRow['slug']; ?>">
                        	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getFeaturedRow['image']; ?>" alt="<?=$getFeaturedRow['product_name_ar']; ?>">
                        </a>
						<div class="pi-links">
							
                          <?php  if($totalstock > 0){ 
							if($getFeaturedRow['p_size']!='' || $getFeaturedRow['p_color']!=''){
							?>
							
                            <a href="javascript:;" class="add-card quick_view2" id="<?= $getFeaturedRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>Select Option</span></a>
                            <?php  }else{ ?>
                            
                            <a href="javascript:;" class="add-card addtocart" id="<?= $getFeaturedRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>ADD TO CART</span></a>
                            <?php  }}else{ ?>
                            	<a href="javascript:;" class="add-card"><i class="fas fa-shopping-cart" style="opacity:0.5"></i><span>Out of Stock</span></a>
							<?php  } ?>
                            
                            <a href="javascript:;"  id="<?= $getFeaturedRow['id']; ?>" class="add-card quick_view"><i class="far fa-eye"></i><span>Quick Shop</span></a>
                            
						</div>
					</div>
					<div class="pi-text">
					    <a href="<?=$WebsiteUrl2.'/'.$getFeaturedRow['slug']; ?>"><p><?=$getFeaturedRow['product_name_ar']; ?></p></a>
						<h5><?= $getFeaturedRow['price'].' ريال'; ?><input type="hidden" id="price<?= $getFeaturedRow['id']; ?>" value="<?=$getFeaturedRow['price']; ?>" /></h5>
                        <h6>
						<?php if(!empty($getFeaturedRow['old_price'])){ ?>
								<del><?=$getFeaturedRow['old_price'].' ريال'; ?></del>
            			<?php } ?>
                         </h6>
						<p>
                        <?php if(!empty($getFeaturedRow['old_price'])){ ?>
                          <i style="color:red;font-size:14px;">وفرت  <?php echo $getFeaturedRow['old_price']-$getFeaturedRow['price'].' ريال'; ?></i>
                        <?php } ?>
                        </p>
					</div>
				</div>				
	   </div> 
        

 
        
        <?php  $d++; }}else{ ?>
        
        <div class="col-md-12"><h2>Sorry No Product Found..</h2></div>

    <?php  } ?>
		
			</div>
			
		</div>
	</section>
    
            
<!-- Modal Cart -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 0px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle" style="font-size: 1em;">Product Added to the Cart Successfully</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color: #fff;">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="shipping_details">
      
        
        
      </div>
      <div class="modal-footer">
        <a href="<?= $WebsiteUrl2.'/'; ?>cart"><button type="button" class="site-btn">CART</button></a>
        <button type="button" class="site-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Wishlist -->
<div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 0px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle" style="font-size: 1em;">Product Added to the Wishlist Successfully</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color: #fff;">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="shipping_details2">
      
        
        
      </div>
      <div class="modal-footer">
        <a href="<?= $WebsiteUrl2.'/'; ?>wishlist"><button type="button" class="site-btn">Wishlist</button></a>
        <button type="button" class="site-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>


	<script>
	 $(document).on('click', '.addtocart', function(){

        var product_id = $(this).attr("id");
        var pagetotal = $('#price'+product_id+'').val();
		var qty = 1;

        if(product_id=='' || pagetotal=='')
      {
        //alert("يرجى ادخال الكمية المناسبة");
      }
	  
      else
      {
	  
        var dataString = 'product_id='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty;
	//	alert(dataString);
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl2.'/'; ?>ajax_cart.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $('#cartloader'+product_id+'').show();
                },
                complete: function(){
					$('#cartloader'+product_id+'').hide();
                },
                success: function(response){
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

$(document).on('click', '.mywishlist', function(){

        var product_id = $(this).attr("id");
        var pagetotal = $('#price'+product_id+'').val();
		var qty = 1;

        if(product_id=='' || pagetotal=='')
      {
        //alert("Please Select Quantity..");
      }
	  
      else
      {
	  
        var dataString = 'product_id2='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty;
		//alert(dataString);
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl2.'/'; ?>ajax_cart.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $('#cartloader'+product_id+'').show();
                },
                complete: function(){
					$('#cartloader'+product_id+'').hide();
                },
                success: function(response){
				$("#header2").load(location.href + " #header2");
				document.getElementById("shipping_details2").innerHTML=response;
				$('#exampleModalCenter2').modal('show');
				setTimeout(function(){
					$("#exampleModalCenter2").modal("hide");
				}, 3000);
				
                }
            });
      }
    });


</script>
</body>
</html>
