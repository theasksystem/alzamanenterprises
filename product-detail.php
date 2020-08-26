<?php

session_start();
error_reporting(0);
include('include/db.class.php');
include('include/functions.php');

$productId = $_REQUEST['productId'];
$reviewId = $_REQUEST['reviewId'];

$URL=$WebsiteUrl.'/'.$_REQUEST['productId'];

$getProductdetail = $conn->prepare("SELECT * FROM products WHERE slug = '$productId'");
$getProductdetail->execute();
$getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);

$getProductTab = tabAlldataQuery($conn, $getProductRow['tab_id']);
$getProductCat = catAlldataQuery($conn, $getProductRow['cat_id']);
$getProductSubCat = getSubcategory($conn, $getProductRow['subcat_id']);

$getSellerdetail = $conn->prepare("SELECT id,company FROM tbl_admin WHERE id = '".$getProductRow['user_id']."'");
$getSellerdetail->execute();
$getSellerRow = $getSellerdetail->fetch(PDO::FETCH_ASSOC);

$getColor2 = $conn->prepare("SELECT a.color_id,a.stock,b.id,b.color FROM product_images a left join products_color b on a.color_id=b.id where a.product_id='".$getProductRow['id']."' and a.color_id!='' GROUP by a.color_id ORDER BY b.color ASC");
$getColor2->execute();

$mypSize = $getProductRow['p_size'];
if($mypSize != '')
{
	$mypSize2 = $mypSize;
}
else{
	$mypSize2 = 0;
}
$getSize2 = $conn->prepare("SELECT * FROM products_size WHERE id IN (".$mypSize2.") order by id desc");
$getSize2->execute();

$TotalQuantity = TotalQuantity($conn,$getProductRow['id']);

$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getProductRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
$totalsales->execute();
$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
$stockrest=$totalsaleamt['total_sale'];

$totalstock=$TotalQuantity-$stockrest;

$getwishlistRow = $conn->prepare("SELECT COUNT(*) as tot from wishlist where un_id = '".$_SESSION['UNIQUEID2']."' and pid = '".$getProductRow['id']."'");
$getwishlistRow->execute();
$getwishlistRow2 = $getwishlistRow->fetch(PDO::FETCH_ASSOC);
$getwishlistRow = $getwishlistRow2['tot'];


$getnotifyRow = $conn->prepare("SELECT COUNT(*) as tot from send_notify where user_id = '".$_SESSION['LOGIN_ID']."' and product_id = '".$getProductRow['id']."' and send_mail = 0");
$getnotifyRow->execute();
$getnotifyRow2 = $getnotifyRow->fetch(PDO::FETCH_ASSOC);
$getnotify = $getnotifyRow2['tot'];

?>
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
<style>
    img.img-circle {
    width: 50px;
}

/* The popup form - hidden by default */
.form-popup {
    display: none;
    top:50%;
    left:50%;
    margin-left: -210px;
    margin-top: -255px;
    padding: 1%;
    opacity: 1;
    z-index: 1;
    background-color: rgba(0,0,0,0.5);
    z-index: 2;
    cursor: pointer;
    transform: translate(30%,-30%);
    -ms-transform: translate(20%,-30%);
    width: 60%;
    -moz-box-shadow: 0 0 3px #ccc;
    -webkit-box-shadow: 0 0 3px #ccc;
    box-shadow: 0 0 3px #ccc;
}

/* Add styles to the form container */
.form-container {
  width: 100%;
  padding: 7px;
  background-color: white;
  opacity: 1;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #DAA520;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 50%;
  height: 30%;
  margin-bottom:5px;
  opacity: 1;
}

.close {
  cursor: pointer;
  position: absolute;
  top: 10%;
  right: 0%;
  padding: 12px 16px;
  transform: translate(0%, -50%);
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}
</style>
	<!-- product section -->
	<section class="product-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<div class="product-pic-zoom">
						<img class="product-big-img" src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_en']; ?>">
					</div>
					<div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none; margin-bottom:20px;">
						<div class="product-thumbs-track loadImage" style="display: inline-flex;"  id="loadImage">
                            
							<div class="pt active" data-imgbigurl="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>"><img src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_en']; ?>"></div>
                            <?php 		
								$allImages = $conn->query("SELECT image FROM `product_images` WHERE product_id = '".$getProductRow['id']."' ORDER BY `id` asc");
								$allImages->execute();
								while($getImagesRow = $allImages->fetch(PDO::FETCH_ASSOC)){
									
						    ?>
                            
							<div class="pt" data-imgbigurl="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getImagesRow['image']; ?>"><img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getImagesRow['image']; ?>" alt="<?= $getProductRow['product_name_en']; ?>"></div>
                            
                           <?php } ?> 
                          
						</div>
					</div>
				</div>
				<div class="col-lg-6  col-md-12 col-sm-12 col-xs-12 product-details">
					<h2 class="p-title"><?= $getProductRow['product_name_en']; ?>
					<div class="text-right" style="width: max-content;float: right;">
					    <a><i class="fa fa-share"></i></a>
				   
				   <a href="https://www.facebook.com/sharer/sharer.php?u=<?=$URL; ?>&t=<?=$getProductRow['product_name_en']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook" class="text-center py-2 mr-1"><span><img src="<?=$WebsiteUrl.'/'; ?>images/fb.png" class="img-circle" /></span></a>
				   <a href="https://twitter.com/share?url=<?=$URL; ?>&text=<?=$getProductRow['product_name_en']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Twitter" class="buy-now text-center py-2"><span><img src="<?=$WebsiteUrl.'/'; ?>images/twitter.png" class="img-circle" /></span></a>
				   <a href="whatsapp://send?text=<?=$URL; ?>" data-action="share/whatsapp/share" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on whatsapp" class="text-center py-2 mr-1"><span><img src="<?=$WebsiteUrl.'/'; ?>images/watsapp.png" class="img-circle" /></span></a>
			
				   <!--<a href="http://pinterest.com/pin/create/button/?url=<?=$URL; ?>&media=<?=$WebsiteUrl; ?>/adminuploads/product/<?= $getProductRow['image']; ?>" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Pinterest" class="buy-now text-center py-2"><span><img src="<?=$WebsiteUrl.'/'; ?>images/pinterest.png" class="img-circle" /></span></a>-->
				   <!--<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?=$URL; ?>&t=<?=$getProductRow['product_name_en']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Linkedin" class="text-center py-2 mr-1"><span><img src="<?=$WebsiteUrl.'/'; ?>images/linkdin.png" class="img-circle" /></span></a>-->
				   
					</div>
				</h2>	
					
					
					<h5 class="p-price"><?= 'QAR '.$getProductRow['price']; ?></h5>
                    <?php if(!empty($getProductRow['old_price'])){ ?>
                    <h6><del><?= 'QAR '.$getProductRow['old_price']; ?></del></h6>
                    <?php } ?>
                    <p>
                    <b style="color:#ff0000;">
                        <?php if($getProductRow['discount']!='' && $getProductRow['discount_type']!='' && $getProductRow['discount_type']!=0){ ?>
      					<?php if($getProductRow['discount_type']==1){ echo $getProductRow['discount'].' % OFF'; }elseif($getProductRow['discount_type']==2){ echo 'QAR '.$getProductRow['discount'].' OFF'; }} ?>
                        </b>
                    </p>
					<h4 class="p-stock">Available: <span><?php if($totalstock > 0){ echo $totalstock.' Left In Stock'; }else{ echo 'Out of Stock'; } ?></span></h4>
                    <p><?= strip_tags($getProductRow['short_desc_en']); ?></p>
					
					<!--<div class="p-review">
						<a href="">3 reviews</a>|<a href="">Add your review</a>
					</div>-->
                    
					<div class="fw-size-choose col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<p>Size : </p>
                        <?php if($getSize2->rowCount() != 0){ 
						$adddd=1;
						while($getSize = $getSize2->fetch(PDO::FETCH_ASSOC)){
						?>
						<div class="sc-item">
							<input type="radio" name="size" id="xs-size<?=$adddd; ?>" value="<?=$getSize['id']; ?>">
							<label for="xs-size<?=$adddd; ?>"><?=$getSize['size']; ?></label>
						</div>
                        <?php $adddd++; }}else{ ?>
                        <div class="sc-item">
							<p>N/A</p>
                            <input type="radio" name="size" value="NA" checked>
						</div>
						<?php } ?>
					</div>
                    
                    <div class="fw-color-choose col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<p>Color : </p>
                         <div class="cs-item">
                     <?php if($getColor2->rowCount() != 0){
					 $adcolor=1;
					 while($getColor = $getColor2->fetch(PDO::FETCH_ASSOC)){
					 $TotalQuantitybyColor = TotalQuantitybyColor($conn,$getProductRow['id'],$getColor['id']);
					 ?>   
                       
								<input type="radio" name="cs" id="gray-color<?=$adcolor; ?>"  value="<?=$getColor['id']; ?>" onClick=photoChange('<?=$getColor['id']; ?>','<?=$getProductRow["id"]; ?>'); <?php if($TotalQuantitybyColor <= 0){?>disabled<?php } ?>>
								<label <?php if($TotalQuantitybyColor <= 0){?>class="newdis" <?php } ?>  for="gray-color<?=$adcolor; ?>" >
									<?=$getColor['color']; ?>
								</label>
					   
                        
                     <?php $adcolor++; }}else{ ?>
                     <p>N/A</p><input type="radio" name="cs" value="NA" checked> 
                     <?php } ?>
                        </div> 
					</div>
                    
                    
					<div class="quantity col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p style="width:auto;">Quantity : </p>
                        <p><table  style="width:auto;">
          <td class="invert"  style="width:auto;">
          <div class="custom-qty" style="display: inline-flex;">
                                      <button onClick="var result = document.getElementById('qty'); var qty22 = result.value; if( !isNaN( qty22 ) &amp;&amp; qty22 &gt; 1 ) result.value--;return false;" class="reduced items qtybutton" type="button"> <i class="fa fa-minus"></i> </button>
                                      <input type="number" class="input-text qty" title="Qty" value="1" maxlength="8" step="1" min="1" oninput="validity.valid||(value='');" max="99" id="qty" name="qty" style="max-width:100px; width:40px;text-align: center;">
                                      <button onClick="var result = document.getElementById('qty'); var qty22 = result.value; if( !isNaN( qty22 )) result.value++;return false;" class="increase items qtybutton" type="button"> <i class="fa fa-plus"></i> </button>
                                    </div></td>
        </table> </p>
                        
                    </div>
                    <input type="hidden" id="pagetotal" value="<?= $getProductRow['price']; ?>" />
                    <div id="wishref" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php  if($totalstock > 0){ ?>
                                <button class="add-to-cart2 site-btn" type="button"><i class="fas fa-shopping-cart"></i> add to cart</button>
                            <?php }else{ ?>
                            	<button class="site-btn" type="button"><i class="fas fa-shopping-cart"></i> Out of Stock</button>
                            <?php } ?>
                              <img id="cartloader" height="40" width="40" style="display: none;" src="images/loader.gif">
                              
                    		 <?php if($_SESSION['LOGIN_ID']!=''){ 
                    		   if($getwishlistRow != 0){ ?>
                    		   
                    		    <button class="site-btn" type="button"><i class="fa fa-heart" aria-hidden="true" style="color: gold;"></i> Favourite</button>
                    		<?php }else{ ?>
							<button class="site-btn mywishlist" type="button"  id="<?= $getProductRow['id']; ?>"><i class="far fa-heart"></i> Add to Favourite</button>	
                            <?php }}else{ ?> 
                            <button class="site-btn" type="button"  id="<?= $getProductRow['id']; ?>" onClick="return alert('Please Login First to Add Products in Wishlist');"><i class="far fa-heart"></i> Add to Favourite</button>
                            <?php } ?>
                           <?php  if($totalstock <= 0){ ?> 
                            
                            <hr>
                            <?php if($_SESSION['LOGIN_ID']!=''){ 
                    		   if($getnotify > 0){ ?>
                    		   
                    		    <button class="site-btn" type="button"> Request Sent</button>
                    		<?php }else{ ?>
							<button class="site-btn" onclick="openForm()" type="button"  id="<?= $getProductRow['id']; ?>"></i> Notify me</button>
							<img id="cartloader2" height="40" width="40" style="display: none;" src="images/loader.gif">
                            <?php }}else{ ?> 
                            <button class="site-btn" type="button"  id="<?= $getProductRow['id']; ?>" onClick="return alert('Please Login First for restock notification');"> Notify me</button>
                            <?php }} ?>   
                            <div class="form-popup" id="myForm">
                              <form action="" class="form-container">
                                <h3><b>Please select your Notification type</h3><span onclick="closeForm()" class="close">&times;</span><br>
                                <input type="radio" id="notifytype" name="notifytype" value="EMAIL" checked>
                                <label for="email"><b>Email</b></label><br>
                                <input type="radio" id="notifytype" name="notifytype" value="SMS">
                                <label for="sms"><b>SMS</b></label><br>
                                <input type="radio" id="notifytype" name="notifytype" value="BOTH">
                                <label for="both"><b>Both</b></label>
                                <center><button class="btn fetchType">Submit</button></center></br></br>
                              </form>
                            </div>      
                    </div>

					<div id="accordion" class="accordion-area col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link active" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">Other Information</button>
							</div>
							<div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="panel-body">
									<p><b>Shipping & Delivery :</b> <span><?php if($getProductRow['ship_charge'] != ''){ echo 'QAR '.$getProductRow['ship_charge']; }else{ echo 'Free Delivery'; } ?></span></p>
                                    <p><b>Store :</b> <span><a href="store.php?own=<?=base64_encode(base64_encode(base64_encode($getSellerRow['id']))); ?>" style="color:goldenrod;"><?=$getSellerRow['company']; ?></a></span></p>
								</div>
							</div>
						</div>
						
						<div class="panel">
							<div class="panel-header" id="headingThree">
								<button class="panel-link" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">shipping & Returns</button>
							</div>
							<div id="collapse3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
								<div class="panel-body">
									<p><strong>No Returns & Returns option should avalible.</strong></p>
								</div>
							</div>
						</div>
                        
					</div>
					
				</div>
			</div>
		</div>
        
        <div class="container">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Product Details</a>
                      <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Reviews and Ratings</a>
                      <a class="nav-item nav-link" id="nav-spec-tab" data-toggle="tab" href="#nav-spec" role="tab" aria-controls="nav-spec" aria-selected="false">Specifications</a>
                       </div>
                  </nav>
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                      <?= $getProductRow['long_desc_en']; ?>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                      <div class="reviews">
       <?php 
	  $st = $conn->prepare("select * from tbl_testimonial where visible = 1 and pid = '".$getProductRow['id']."'");
	  $st->execute();
	  if($st->rowCount()!= 0){
	 while($check = $st->fetch(PDO::FETCH_ASSOC))
	   {
	   
	   ?>
       			<div class="people-text">
           <ul>
             
             <li><u>By <?=trim(ucfirst($check['name'])); ?></u></li>
             <li><i class="far fa-clock"></i> <?=date('M d - Y h:m:a',strtotime($check['created_at'])); ?></li>
             <h5><i class="fas fa-comments"></i> <?=$check['comment']; ?></h5>
            </ul>
        
       </div>
       
		<?php }}else{ ?>
		
		<div class="people-text">
       
             
             <h5>No Reviews Available on this Product..</h5>
           
        
       </div>
       
		<?php } if($reviewId != ''){ ?>
              
       <form name="reg5">
  <div class="form-group pl-1 pr-1 row">
  
                  <div class="col-md-2"></div>
                <div class="col-md-8 servicesheading">
<h2 class="text-center wow fadeIn">Give Your Review</h2>
<p class="text-center wow fadeIn" style="line-height: 32px;">Give Us Your Feedback To Make Things Better</p><hr />
</div>
                <div class="col-md-2"></div>
                
    <div class="col-md-4 commonrv">
                    <div class="form-group">
                        <input type="text" name="rvname" class="form-control" id="rvname" placeholder="Your Name" required>
                    </div>
                </div>
    <div class="col-md-4 commonrv">
        <div class="form-group">
            <input type="email" class="form-control" id="rvemail" name="rvemail" placeholder="Your Mail" required>
        </div>
    </div>
    <div class="col-md-4 commonrv">
        <div class="form-group">
            <input type="number" placeholder="Mobile Number" class="form-control" id="rvnumber" name="rvnumber" required>
        </div>
    </div>
    <div class="col-md-12 commonrvmessage">
        <textarea class="form-control" id="rvmessage" name="rvmessage" placeholder="Leave Your Feedback / Review / Message..."></textarea>
    </div>
    <div class="col-md-8 ratenow">
                   <div class="row">
                        <div class="col-md-2">
                        <label>Rate Us</label>
                       </div>
                       
                       <div class="col-md-4" id="ratee">
                        	<div class="star-border"><div class="star"></div></div>
                            <div class="star-border"><div class="star"></div></div>
                            <div class="star-border"><div class="star"></div></div>
                            <div class="star-border"><div class="star"></div></div>
                            <div class="star-border"><div class="star"></div></div>
                       </div>
                       <div class="col-md-1" style="padding-left:0px; text-align:left;">
                        <div class="debug"></div>
                       </div>
                       <input type="hidden" id="debug" />
                       
                       <div class="col-md-3">
                       <button type="button" id="submitreview" class="btn-4 commonsubmitbutton" value="Ride Now"><span></span>Submit Review</button>
                       </div>
                       
                       
                      
                    </div>
                </div>
    
    <div class="col-md-4 ratenow" id="regerrormsg" style="color:#FF0000;"></div>
  </div>


</form>
		
        <?php } ?>
     
   </div>
                    </div>
                    
                    <div class="tab-pane fade" id="nav-spec" role="tabpanel" aria-labelledby="nav-home-spec">
                      <?php if($getProductRow['product_spec_en']!=''){ echo $getProductRow['product_spec_en']; }else{ echo '<h5>No Specification Available for this Product..</h5>'; } ?>
                    </div>
                  </div>
                
                </div>
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
        <a href="<?= $WebsiteUrl.'/'; ?>wishlist"><button type="button" class="site-btn">Wishlist</button></a>
        <button type="button" class="site-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
        
        <!---------Model End Wishlist Here----------->

	</section>
	<!-- product section end -->
	
		<!-- letest product section -->
	<section class="top-letest-product-section">
		<div class="container-fluid">
			<div class="section-title">
				<h2 class="mobileleft">Related Products </h2>
                
			</div>
			
			<div class="product-slider owl-carousel">
				<?php
				$d=1;
					$getFeatured = featuredProduct($conn);
					foreach($getFeatured as $getFeaturedRow){
					$TotalQuantity = TotalQuantity($conn,$getFeaturedRow['id']);
					$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_orders where pid=".$getFeaturedRow['id']." and status!='rejected'");
					$totalsales->execute();
					$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
					$stockrest=$totalsaleamt['total_sale'];
					$totalstock=$TotalQuantity-$stockrest;
				?>
				<div class="product-item">
					<div class="pi-pic">
                    <?php if($getFeaturedRow['discount']!='' && $getFeaturedRow['discount_type']!='' && $getFeaturedRow['discount_type']!=0){ ?>
						<div class="tag-new">
                        <?php if($getFeaturedRow['discount_type']==1){ echo $getFeaturedRow['discount'].' % OFF'; }elseif($getFeaturedRow['discount_type']==2){ echo 'QAR '.$getFeaturedRow['discount'].' OFF'; } ?>
                        </div>
                    <?php } ?>
						<a href="<?=$WebsiteUrl.'/'.$getFeaturedRow['slug']; ?>">
                        	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getFeaturedRow['image']; ?>" alt="<?= substr($getFeaturedRow['product_name_en'],0,20); ?>">
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
					    <a href="<?=$WebsiteUrl.'/'.$getFeaturedRow['slug']; ?>"><p><?= substr(ucfirst($getFeaturedRow['product_name_en']),0,18).'..'; ?></p></a>
						<h5><?= 'QAR '.$getFeaturedRow['price']; ?><input type="hidden" id="price<?= $getFeaturedRow['id']; ?>" value="<?=$getFeaturedRow['price']; ?>" /></h5>
                        <h6>
						<?php if(!empty($getFeaturedRow['old_price'])){ ?>
								<del><?= 'QAR '.$getFeaturedRow['old_price']; ?></del>
            			<?php } ?>
                         </h6>
						<p>
                        <?php if(!empty($getFeaturedRow['old_price'])){ ?>
                          <i style="color:red;font-size:14px;">You Save QAR <?php echo $getFeaturedRow['old_price']-$getFeaturedRow['price']; ?></i>
                        <?php } ?>
                        </p>
					</div>
				</div>
                

                <?php $d++; } ?>
				
			</div>
		</div>
        

        
	</section>
  <!-- letest product section end -->

<?php include('footer.php'); ?>

<script>
function photoChange(val,val2){
    //alert(val2);
    var dataString = 'val='+ val + '&val2='+ val2;
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>models.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $(".loads").show();
                },
                complete: function(){
					$(".loads").hide();
                },
                success: function(response){
                  $('.loadImage').html(response);
                }
            });

        $.ajax({
              type: "POST",
              url: "<?= $WebsiteUrl.'/'; ?>dataFetcher.php",
              data: dataString,
              cache: false,
              success: function(response){
                $('.p-price').html("QAR " + response);
              }
        });

}
$( document ).ready(function() {
    

  var set;
 $(".star-border").click(function(){
  $(this).find(".star").css("background","gold");
  $(this).prevAll().find(".star").css("background","gold");
  $(this).nextAll().find(".star").css("background","white");
  set = $(this).prevAll().length + 1;
});

setInterval(function(){
  $(".debug").text(set);
  $("#debug").val(set);
}, 50);  
  
});
$('.add-to-cart2').on("click", function(){

	var product_id = '<?= $getProductRow['id']; ?>';
	var pagetotal = $('#pagetotal').val();
	var qty =  $('#qty').val();
	var size = $("input[name='size']:checked").val();
	var cs =   $("input[name='cs']:checked").val();
	
	if(size=='NA'){
		size2 = '';
	}
	else{
		size2 = size;
	}
	if(cs=='NA'){
		cs2 = '';
	}
	else{
		cs2 = cs;
	}
		
		

      if(qty=='')
      {
        alert("Please Enter a Valid Quantity..");
        return false;
      }
	  if(size==undefined)
      {
	  	//alert(size);
        alert("Please Select a Size..");
        return false;
      }
	  if(cs==undefined)
      {
	  	
		//alert(cs);
        alert("Please Select a Color..");
        return false;
      }
      else
      {
	  
        var dataString = 'product_id='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty + '&size='+ size2 + '&cs='+ cs2;
	//alert(dataString);
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>ajax_cart.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $("#cartloader").show();
                },
                complete: function(){
					$("#cartloader").hide();
                },
                success: function(data){
				//alert(data);
				window.location.href='cart';
                }
            });
      }

});
$('#submitreview').on("click", function(){
		var regEmail4 = /^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)$/;
        var rvname = $("#rvname").val();
		var rvemail = $("#rvemail").val();
		var rvnumber = $("#rvnumber").val();
		var rvmessage = $("#rvmessage").val();	
		var star = 	$("#debug").val();

       if(rvname=='')
      {
	  
        $("#rvname").css({'border':'red 1px solid','background-color':'#eee'});
		document.reg5.rvname.focus();
		return false;
      }
	  if(rvemail=='')
      {
	  
        $("#rvemail").css({'border':'red 1px solid','background-color':'#eee'});
		document.reg5.rvemail.focus();
		return false;
      }
	  if(!document.reg5.rvemail.value.match(regEmail4))
		{
			alert("Email Not in Valid Format..");
			document.reg5.rvemail.focus();
			return false;
		}
	  if(rvnumber=='')
      {
	   $("#crmobile").css({'border':'red 1px solid','background-color':'#eee'});
	   document.reg5.rvnumber.focus();
		return false;
      }
	  if(!(document.reg5.rvnumber.value.length >= 10 && document.reg5.rvnumber.value.length <= 12))
		{
			alert("Please Enter Between 10 and 12 Digit in Your Contact No.\n\n You have enter "+document.reg5.rvnumber.value.length+" Digit.");
			document.reg5.rvnumber.focus();
			return false;
		}
	  if(rvmessage=='')
      {
	   $("#rvmessage").css({'border':'red 1px solid','background-color':'#eee'});
	   document.reg5.rvmessage.focus();
		return false;
      }
	  if(star=='')
      {
	   $("#ratee").css({'border':'red 1px solid','background-color':'#eee'});
		return false;
      }
      else
      {
       
        $.ajax({
                type: "POST",
                url: "login.php",
                data: {'rvname':rvname,'rvemail':rvemail,'rvnumber':rvnumber,'rvmessage':rvmessage,'star':star, 'ppid':<?= $getProductRow['id']; ?>},
                cache: false,
                beforeSend: function(){
				                    
                },
                complete: function(){

                },
                success: function(response){
				
				$("#regerrormsg").html(response);
				$("#rvname").val('');
				$("#rvemail").val('');
				$("#rvnumber").val('');
				$("#rvmessage").val('');
				
                }
            });
      }

});

$(document).on('click', '.mywishlist', function(){

        var product_id = '<?= $getProductRow['id']; ?>';
	    var pagetotal = $('#pagetotal').val();
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
                url: "<?= $WebsiteUrl.'/'; ?>ajax_cart.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $("#cartloader").show();
                },
                complete: function(){
					$("#cartloader").hide();
                },
                success: function(response){
				$("#wishref").load(location.href + " #wishref");
				document.getElementById("shipping_details2").innerHTML=response;
				$('#exampleModalCenter2').modal('show');
				setTimeout(function(){
					$("#exampleModalCenter2").modal("hide");
				}, 3000);
				
                }
            });
      }
    });
    
$(document).on('click', '.fetchType', function(){

        var type = document.querySelector('input[name = "notifytype"]:checked').value;
        var notify_product = '<?= $getProductRow['id']; ?>';
        var dataString = 'notify_product='+ notify_product + '&type=' + type;
	//	alert(dataString);
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>ajax_cart.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                    $("#cartloader2").show();
                },
                complete: function(){
					$("#cartloader2").hide();
                },
                success: function(response){
				location.reload(true);
				
                }
            });

    });

    function openForm() {
      document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
      document.getElementById("myForm").style.display = "none";
    }
</script>
</body>
</html>