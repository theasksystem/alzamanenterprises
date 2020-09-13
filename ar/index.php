<?php

session_start();
error_reporting(0);
include('../include/db.class.php');
include('../include/functions.php');

?>


<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>


	<!-- Hero section -->
<section class="slider">
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
      	<img class="slider-img" style="transform: scale(1);" src="<?= $WebsiteUrl.'/'; ?>images/banner-1.png"> 
      </div>
      <div class="carousel-item">
     	 <img class="slider-img" style="transform: scale(1);" src="<?= $WebsiteUrl.'/'; ?>images/banner-2.png"> 
	  </div>
	  <div class="carousel-item">
     	 <img class="slider-img" style="transform: scale(1);" src="<?= $WebsiteUrl.'/'; ?>images/banner-3.png"> 
      </div>
    </div>
    <div class="aerrow-slider"> <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"> <span class="aerrow-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"> <span class="aerrow-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
  </div>
</section>
	<!-- Hero section end -->
	

	

	<!-- letest product section -->
	<section class="top-letest-product-section">
		<div class="container-fluid">
			<div class="section-title">
				<h2>منتجات مميزة <a href="<?= $WebsiteUrl2.'/'; ?>products"><button class="add-to-cart2 site-btn float-right salim" type="button">عرض المزيد</button></a></h2>
			</div>
			
			<div class="product-slider owl-carousel">
				<?php
				$d=1;
					$getFeatured = featuredProduct($conn);
					foreach($getFeatured as $getFeaturedRow){
					$TotalQuantity = TotalQuantity($conn,$getFeaturedRow['id']);
					$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getFeaturedRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
					$totalsales->execute();
					$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
					$stockrest=$totalsaleamt['total_sale'];
					$totalstock=$TotalQuantity-$stockrest;
				?>
				<div class="product-item">
					<div class="pi-pic">
					    <?php  if($totalstock!='0'){ ?>
                    <?php if($getFeaturedRow['discount']!='' && $getFeaturedRow['discount_type']!='' && $getFeaturedRow['discount_type']!=0){ ?>
						<div class="tag-new">
                        <?php if($getFeaturedRow['discount_type']==1){ echo '%'.$getFeaturedRow['discount'].' خصم'; }elseif($getFeaturedRow['discount_type']==2){  echo 'خصم '.$getFeaturedRow['discount'].' ريال'; } ?>
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
							
                           	<a href="javascript:;" class="add-card quick_view2" id="<?= $getFeaturedRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>الخيارات</span></a>
                            <?php  }else{ ?>
                            
                            <a href="javascript:;" class="add-card addtocart" id="<?= $getFeaturedRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>اضافة الى السلة</span></a>
                            <?php  }}else{ ?>
                            	<a href="javascript:;" class="add-card"><i class="fas fa-shopping-cart" style="opacity:0.5"></i><span>نفدت الكمية</span></a>
							<?php  } ?>
                            
                            <a href="javascript:;"  id="<?= $getFeaturedRow['id']; ?>" class="add-card quick_view"><i class="far fa-eye"></i><span>تسوق السريع</span></a>
						
                        </div>
					</div>
					<div class="pi-text">
					    <a href="<?=$WebsiteUrl2.'/'.$getFeaturedRow['slug']; ?>"><p><?=$getFeaturedRow['product_name_ar']; ?></p></a>
						<h5><?=$getFeaturedRow['price'].' ريال'; ?><input type="hidden" id="price<?= $getFeaturedRow['id']; ?>" value="<?=$getFeaturedRow['price']; ?>" /></h5>
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
                

                <?php $d++; } ?>
				
			</div>
		</div>
        

        
	</section>
	<!-- letest product section end -->
	
	
	<!-- letest product section -->
	<section class="top-letest-product-section">
		<div class="container-fluid">
			<div class="section-title">
				<h2>اكسسوارات الازياء <a href="<?= $WebsiteUrl2.'/'; ?>products"><button class="add-to-cart2 site-btn float-right salim" type="button">عرض المزيد</button></a></h2>
                
			</div>
			
			<div class="product-slider owl-carousel">
				<?php
				$h=1;
					$getFeatured2 = featuredProduct($conn);
					foreach($getFeatured2 as $getFeaturedRow){
					$TotalQuantity = TotalQuantity($conn,$getFeaturedRow['id']);
					$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getFeaturedRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
					$totalsales->execute();
					$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
					$stockrest=$totalsaleamt['total_sale'];
					$totalstock=$TotalQuantity-$stockrest;
				?>
				<div class="product-item">
					<div class="pi-pic">
					    <?php  if($totalstock!='0'){ ?>
                    <?php if($getFeaturedRow['discount']!='' && $getFeaturedRow['discount_type']!='' && $getFeaturedRow['discount_type']!=0){ ?>
						<div class="tag-new">
                        <?php if($getFeaturedRow['discount_type']==1){ echo '%'.$getFeaturedRow['discount'].' خصم'; }elseif($getFeaturedRow['discount_type']==2){  echo 'خصم '.$getFeaturedRow['discount'].' ريال'; } ?>
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
							
                            	<a href="javascript:;" class="add-card quick_view2" id="<?= $getFeaturedRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>الخيارات</span></a>
                            <?php  }else{ ?>
                            
                            <a href="javascript:;" class="add-card addtocart" id="<?= $getFeaturedRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>اضافة الى السلة</span></a>
                            <?php  }}else{ ?>
                            	<a href="javascript:;" class="add-card"><i class="fas fa-shopping-cart" style="opacity:0.5"></i><span>نفدت الكمية</span></a>
							<?php  } ?>
                            
                            <a href="javascript:;"  id="<?= $getFeaturedRow['id']; ?>" class="add-card quick_view"><i class="far fa-eye"></i><span>تسوق السريع</span></a>
                            
						</div>
					</div>
					<div class="pi-text">
					    <a href="<?=$WebsiteUrl2.'/'.$getFeaturedRow['slug']; ?>"><p><?= $getFeaturedRow['product_name_ar']; ?></p></a>
						<h5><?=$getFeaturedRow['price'].' ريال'; ?><input type="hidden" id="price<?= $getFeaturedRow['id']; ?>" value="<?=$getFeaturedRow['price']; ?>" /></h5>
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

                <?php $h++; } ?>
				
			</div>
		</div>
        

        
	</section>
	<!-- letest product section end -->

	<!-- Banner section -->
	<section class="banner-section b100" style="padding-bottom:25px;">
		<div class="container-fluid">
		<div class="row">
		<div class="col-lg-4 col-sm-12 mybanner" style="margin-bottom:10px;">
			<img src="<?= $WebsiteUrl.'/'; ?>images/box-1.jpg" class="img-responsive">
		
		</div>
		
				<div class="col-lg-4 col-sm-12 mybanner" style="margin-bottom:10px;">
			<img src="<?= $WebsiteUrl.'/'; ?>images/box-2.jpg" class="img-responsive">
				
			
			
		</div>
		
				<div class="col-lg-4 col-sm-12 mybanner" style="margin-bottom:10px;">
			<img src="<?= $WebsiteUrl.'/'; ?>images/box-3.jpg" class="img-responsive">
				
		
		</div>
		
		</div>
		</div>
		
		
	</section>
	<!-- Banner section end  -->


	
		<!-- letest product section -->
	<section class="top-letest-product-section">
		<div class="container-fluid">
			<div class="section-title">
				<h2>احدث المنتجات</h2>
				<p>لا تفوتك منتجاتنا الجديدة والحصرية</p>
			</div>
			<div class="product-slider owl-carousel">
				<?php
				$Limit = 10;
					$f=1;
					$getNewArrival = newArrivalsProducts($conn, $Limit);
					foreach($getNewArrival as $getNewArrivalRow){
					$TotalQuantity = TotalQuantity($conn,$getNewArrivalRow['id']);
					$totalsales2 = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getNewArrivalRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
					$totalsales2->execute();
					$totalsaleamt2 = $totalsales2->fetch(PDO::FETCH_ASSOC);
					$stockrest2=$totalsaleamt2['total_sale'];
					$totalstock2=$TotalQuantity-$stockrest2;
				?>
				<div class="product-item">
						<div class="pi-pic">
						    <?php  if($totalstock2!='0'){ ?>
                        <?php if($getNewArrivalRow['discount']!='' && $getNewArrivalRow['discount_type']!='' && $getNewArrivalRow['discount_type']!=0){ ?>
							<div class="tag-sale"><?php if($getNewArrivalRow['discount_type']==1){ echo '%'.$getNewArrivalRow['discount'].' خصم'; }elseif($getNewArrivalRow['discount_type']==2){  echo 'خصم '.$getNewArrivalRow['discount'].' ريال'; } ?>
                            </div>
                         <?php }}else{ ?>
                    <div class="tag-new2">نفدت الكمية</div><?php  } ?>
							<a href="<?= $WebsiteUrl2.'/'.$getNewArrivalRow['slug']; ?>">
                            	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getNewArrivalRow['image']; ?>" alt="">
                            </a>
							<div class="pi-links">
							<?php  if($totalstock2 > 0){ 
							if($getNewArrivalRow['p_size']!='' || $getNewArrivalRow['p_color']!=''){
							?>
							
                            	<a href="javascript:;" class="add-card quick_view2" id="<?= $getNewArrivalRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>الخيارات</span></a>
                            <?php  }else{ ?>
                            
                            <a href="javascript:;" class="add-card addtocart" id="<?= $getNewArrivalRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>اضافة الى السلة</span></a>
                            <?php  }}else{ ?>
                            	<a href="javascript:;" class="add-card"><i class="fas fa-shopping-cart" style="opacity:0.5"></i><span>نفدت الكمية</span></a>
							<?php  } ?>
                            
                            <a href="javascript:;" id="<?= $getNewArrivalRow['id']; ?>" class="add-card quick_view"><i class="far fa-eye"></i><span>تسوق السريع</span></a>
                            
							</div>
						</div>
						<div class="pi-text">
						    <a href="<?= $WebsiteUrl2.'/'.$getNewArrivalRow['slug']; ?>">
                            	<p><?= $getNewArrivalRow['product_name_ar']; ?></p>
                            </a>
							<h5><?=$getNewArrivalRow['price'].' ريال'; ?><input type="hidden" id="price<?= $getNewArrivalRow['id']; ?>" value="<?=$getNewArrivalRow['price']; ?>" /></h5>
                            <h6>
						<?php if(!empty($getNewArrivalRow['old_price'])){ ?>
								<del><?=$getNewArrivalRow['old_price'].' ريال'; ?></del>
            			<?php } ?>
                         </h6><br>
						<p>	
                         <?php if(!empty($getNewArrivalRow['old_price'])){ ?>
                         		<i>وفرت  <?php echo $getNewArrivalRow['old_price']-$getNewArrivalRow['price'].' ريال'; ?></i>
                          <?php } ?>
                        </p>
						</div>
				</div>

                <?php $f++; } ?>
				
			</div>
		</div>
</section>

	<!-- Banner section -->
	<section class="banner-section " style="padding-top: 20px;">
		<div class="container-fluid">
		<div class="row">
		<div class="col-lg-6">
			<img src="<?= $WebsiteUrl.'/'; ?>images/banner3.png" class="img-responsive">
		</div>
        <div class="col-lg-6">
			<img src="<?= $WebsiteUrl.'/'; ?>images/banner4.png" class="img-responsive">
		</div>
		
		</div>
		</div>
		
		
	</section>
	<!-- Banner section end  -->
	
	
			<!-- letest product section -->
	<section class="top-letest-product-section">
		<div class="container-fluid">
			<div class="section-title">
				<h2>عرض اليوم</h2>
			</div>
			<div class="product-slider owl-carousel">
				<?php
				$Limit3 = 10;
					$j=1;
					$getNewArrival3 = newArrivalsProducts($conn, $Limit3);
					foreach($getNewArrival3 as $getNewArrivalRow){
					$totalsales2 = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getNewArrivalRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
					$totalsales2->execute();
					$totalsaleamt2 = $totalsales2->fetch(PDO::FETCH_ASSOC);
					$stockrest2=$totalsaleamt2['total_sale'];
					$totalstock2=$getNewArrivalRow['quantity']-$stockrest2;
				?>
				<div class="product-item">
						<div class="pi-pic">
						    <?php  if($totalstock2!='0'){ ?>
                        <?php if($getNewArrivalRow['discount']!='' && $getNewArrivalRow['discount_type']!='' && $getNewArrivalRow['discount_type']!=0){ ?>
							<div class="tag-sale"><?php if($getNewArrivalRow['discount_type']==1){ echo '%'.$getNewArrivalRow['discount'].' خصم'; }elseif($getNewArrivalRow['discount_type']==2){  echo 'خصم '.$getNewArrivalRow['discount'].' ريال'; } ?>
                            </div>
                         <?php }}else{ ?>
                    <div class="tag-new2">نفدت الكمية</div><?php  } ?>
							<a href="<?= $WebsiteUrl2.'/'.$getNewArrivalRow['slug']; ?>">
                            	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getNewArrivalRow['image']; ?>" alt="">
                            </a>
							<div class="pi-links">
							<?php  if($totalstock2!='0'){ 
							if($getNewArrivalRow['p_size']!='' || $getNewArrivalRow['p_color']!=''){
							?>
							
                            	<a href="javascript:;" class="add-card quick_view2" id="<?= $getNewArrivalRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>الخيارات</span></a>
                            <?php  }else{ ?>
                            
                            <a href="javascript:;" class="add-card addtocart" id="<?= $getNewArrivalRow['id']; ?>"><i class="fas fa-shopping-cart"></i><span>اضافة الى السلة</span></a>
                            <?php  }}else{ ?>
                            	<a href="javascript:;" class="add-card"><i class="fas fa-shopping-cart" style="opacity:0.5"></i><span>نفدت الكمية</span></a>
							<?php  } ?>
                            
                            <a href="javascript:;" id="<?= $getNewArrivalRow['id']; ?>" class="add-card quick_view"><i class="far fa-eye"></i><span>تسوق السريع</span></a>
							</div>
						</div>
						<div class="pi-text">
						    <a href="<?= $WebsiteUrl2.'/'.$getNewArrivalRow['slug']; ?>">
                            	<p><?= $getNewArrivalRow['product_name_ar']; ?></p>
                            </a>
							<h5><?=$getNewArrivalRow['price'].' ريال'; ?><input type="hidden" id="price<?= $getNewArrivalRow['id']; ?>" value="<?=$getNewArrivalRow['price']; ?>" /></h5>
                            <h6>
						<?php if(!empty($getNewArrivalRow['old_price'])){ ?>
								<del><?=$getNewArrivalRow['old_price'].' ريال'; ?></del>
            			<?php } ?>
                         </h6><br>
						<p>	
                         <?php if(!empty($getNewArrivalRow['old_price'])){ ?>
                         		<i>وفرت  <?php echo $getNewArrivalRow['old_price']-$getNewArrivalRow['price'].' ريال'; ?></i>
                          <?php } ?>
                        </p>
						</div>
				</div>

                <?php $j++; } ?>
				
			</div>
		</div>
    </section>
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

<a class="watsapp" href="https://api.whatsapp.com/send?phone=+97431559977&amp;text=" target="_blank"><img src="<?= $WebsiteUrl.'/'; ?>images/whatsapp.png"></a>

<?php include('footer.php'); ?>


<script>
 $(document).on('click', '.addtocart', function(){

        var product_id = $(this).attr("id");
        var pagetotal = $('#price'+product_id+'').val();
		var qty = 1;

        if(product_id=='' || pagetotal=='')
      {
        alert("يرجى ادخال الكمية المناسبة");
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
