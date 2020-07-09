<?php

session_start();
error_reporting(0);
include('include/db.class.php');

//$productId = $_REQUEST['productId'];
$productId = base64_decode(base64_decode($_REQUEST['reviewId']));
$uid = base64_decode(base64_decode($_REQUEST['uid']));
$reviewId = $_REQUEST['reviewId'];

if($_REQUEST['reviewId']=='' || $_REQUEST['uid']=='')
{
  echo "<script>window.location='products';</script>";
}

$getProductdetail = $conn->prepare("SELECT * FROM products WHERE id = '$productId'");
$getProductdetail->execute();
$getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);

$getUserData2 = $conn->prepare("SELECT * FROM registration WHERE id = '$uid'");
$getUserData2->execute();
$getUserData = $getUserData2->fetch(PDO::FETCH_ASSOC);

$getSellerdetail = $conn->prepare("SELECT id,company FROM tbl_admin WHERE id = '".$getProductRow['user_id']."'");
$getSellerdetail->execute();
$getSellerRow = $getSellerdetail->fetch(PDO::FETCH_ASSOC);

$mypColor = $getProductRow['p_color'];
if($mypColor != '')
{
	$mypColor2 = $mypColor;
}
else{
	$mypColor2 = 0;
}
$getColor2 = $conn->prepare("SELECT * FROM products_color WHERE id IN (".$mypColor2.") order by id desc");
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

$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getProductRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
$totalsales->execute();
$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
$stockrest=$totalsaleamt['total_sale'];

$totalstock=$getProductRow['quantity']-$stockrest;

$getwishlistRow = $conn->prepare("SELECT COUNT(*) as tot from wishlist where un_id = '".$_SESSION['UNIQUEID2']."' and pid = '".$getProductRow['id']."'");
$getwishlistRow->execute();
$getwishlistRow2 = $getwishlistRow->fetch(PDO::FETCH_ASSOC);
$getwishlistRow = $getwishlistRow2['tot'];
//echo $getwishlistRow;
?>

<?php include('header.php'); ?>

	<!-- product section -->
	<section class="product-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="product-pic-zoom">
						<img class="product-big-img" src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_en']; ?>">
					</div>
					<div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none; margin-bottom:20px;">
						<div class="product-thumbs-track">
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
				<div class="col-lg-6 product-details">
					<h2 class="p-title"><?= $getProductRow['product_name_en']; ?></h2>
					<h5><?= 'QAR '.$getProductRow['price']; ?></h5>
                    <?php if(!empty($getProductRow['old_price'])){ ?>
                    <h6><del><?= 'QAR '.$getProductRow['old_price']; ?></del></h6>
                    <?php } ?>
                    <p>
                    <b style="color:#ff0000;">
                        <?php if($getProductRow['discount']!='' && $getProductRow['discount_type']!='' && $getProductRow['discount_type']!=0){ ?>
      					<?php if($getProductRow['discount_type']==1){ echo $getProductRow['discount'].' % OFF'; }elseif($getProductRow['discount_type']==2){ echo 'QAR '.$getProductRow['discount'].' OFF'; }} ?>
                        </b>
                    </p>
					<h4 class="p-stock">Available: <span>In Stock</span></h4>
                    <p><?= $getProductRow['short_desc_en']; ?></p>
					
					<!--<div class="p-review">
						<a href="">3 reviews</a>|<a href="">Add your review</a>
					</div>-->
                    
					<div class="fw-size-choose">
						<p>Size : </p>
                        <?php if($getSize2->rowCount() != 0){ 
						$adddd=1;
						while($getSize = $getSize2->fetch(PDO::FETCH_ASSOC)){
						?>
						<div class="sc-item">
							<input type="radio" name="size" id="xs-size<?=$adddd; ?>" <?php if($adddd==1){ echo 'checked'; } ?> value="<?=$getSize['id']; ?>">
							<label for="xs-size<?=$adddd; ?>"><?=$getSize['size']; ?></label>
						</div>
                        <?php $adddd++; }}else{ ?>
                        <div class="sc-item">
							<p>N/A</p>
                            <input type="hidden" name="size" value="">
						</div>
						<?php } ?>
					</div>
                    
                    <div class="fw-color-choose">
						<p>Color : </p>
                         <div class="cs-item">
                     <?php if($getColor2->rowCount() != 0){
					 $adcolor=1;
					 while($getColor = $getColor2->fetch(PDO::FETCH_ASSOC)){
					 ?>   
                       
								<input type="radio" name="cs" id="gray-color<?=$adcolor; ?>" <?php if($adcolor==1){ echo 'checked'; } ?> value="<?=$getColor['id']; ?>">
								<label  for="gray-color<?=$adcolor; ?>" >
									<?=$getColor['color']; ?>
								</label>
					   
                        
                     <?php $adcolor++; }}else{ echo '<p>N/A</p><input type="hidden" name="cs" value="">'; } ?>
                        </div> 
					</div>
                    
                    
					<div class="quantity">
                        <p><table>
          <td class="invert">
          <div class="custom-qty" style="display: inline-flex;">
                                      <button onClick="var result = document.getElementById('qty'); var qty22 = result.value; if( !isNaN( qty22 ) &amp;&amp; qty22 &gt; 1 ) result.value--;return false;" class="reduced items qtybutton" type="button"> <i class="fa fa-minus"></i> </button>
                                      <input type="text" class="input-text qty" title="Qty" value="1" maxlength="8" id="qty" name="qty" style="max-width:40px;text-align: center;" readonly>
                                      <button onClick="var result = document.getElementById('qty'); var qty22 = result.value; if( !isNaN( qty22 )) result.value++;return false;" class="increase items qtybutton" type="button"> <i class="fa fa-plus"></i> </button>
                                    </div></td>
        </table> </p>
                        
                    </div>
                    <input type="hidden" id="pagetotal" value="<?= $getProductRow['price']; ?>" />
                    <div id="wishref">
                            <?php  if($totalstock!='0'){ ?>
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
                            
                             <!-------------------------------------------Share Buttons----------------------------------------------->
                
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 sharebutton" style="margin-top: 10px;direction: ltr;">
				   
				   <a>Share <i class="fa fa-share"></i></a>
				   
				   <a href="https://www.facebook.com/sharer/sharer.php?u=<?=$URL; ?>&t=<?=$getProductRow['product_name_en']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Facebook" class="text-center py-2 mr-1"><span><img src="<?=$WebsiteUrl.'/'; ?>images/fb.png" class="img-circle" /></span></a>
				   <a href="https://twitter.com/share?url=<?=$URL; ?>&text=<?=$getProductRow['product_name_en']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Twitter" class="buy-now text-center py-2"><span><img src="<?=$WebsiteUrl.'/'; ?>images/twitter.png" class="img-circle" /></span></a>
				   <!--<a href="whatsapp://send?text=<?=$URL; ?>" data-action="share/whatsapp/share" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on whatsapp" class="text-center py-2 mr-1"><span><img src="<?=$WebsiteUrl.'/'; ?>images/watsapp.png" class="img-circle" /></span></a>
				-->
				<a href="http://pinterest.com/pin/create/button/?url=<?=$URL; ?>&media=<?=$WebsiteUrl; ?>/adminuploads/product/<?= $getProductRow['image']; ?>" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Pinterest" class="buy-now text-center py-2"><span><img src="<?=$WebsiteUrl.'/'; ?>images/pinterest.png" class="img-circle" /></span></a>
				   <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?=$URL; ?>&t=<?=$getProductRow['product_name_en']; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on Linkedin" class="text-center py-2 mr-1"><span><img src="<?=$WebsiteUrl.'/'; ?>images/linkdin.png" class="img-circle" /></span></a>
				   
				   
				</div>
				
			<!-------------------------------------------End Share Buttons----------------------------------------------->	
                            
                    </div>
					<div id="accordion" class="accordion-area col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link active" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">Other Information</button>
							</div>
							<div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="panel-body">
									<p><b>Shipping Charge :</b> <span><?php if($getProductRow['ship_charge'] != ''){ echo 'QAR '.$getProductRow['ship_charge']; }else{ echo 'Free'; } ?></span></p>
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
                
    <div class="col-md-4 col-sm-12 col-xs-12 commonrv">
                    <div class="form-group">
                        <input type="hidden" name="rvname" class="form-control" id="rvname" placeholder="Your Name" value="<?=$getUserData['name'].' '.$getUserData['lastname']; ?>">
                    </div>
                </div>
    <div class="col-md-4 col-sm-12 col-xs-12 commonrv">
        <div class="form-group">
            <input type="hidden" class="form-control" id="rvemail" name="rvemail" placeholder="Your Mail" value="<?=$getUserData['email']; ?>" >
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12 commonrv">
        <div class="form-group">
            <input type="hidden" placeholder="Mobile Number" class="form-control" id="rvnumber" name="rvnumber" value="<?=$getUserData['phone']; ?>" >
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 commonrvmessage">
        <textarea class="form-control" id="rvmessage" name="rvmessage" placeholder="Leave Your Feedback / Review / Message..."></textarea>
    </div>
    <div class="col-md-8 col-sm-12 col-xs-12 ratenow">
                   <div class="row">
                        <div class="col-md-2">
                        <label>Rate Us</label>
                       </div>
                       
                       <div class="col-md-4 col-sm-10 col-xs-10" id="ratee">
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
                       
                       <div class="col-md-3 col-sm-12 col-xs-12">
                       <button type="button" id="submitreview" class="btn-4 commonsubmitbutton" value="Ride Now"><span></span>Submit Review</button>
                       </div>
                       
                       
                      
                    </div>
                </div>
    
    <div class="col-md-4  col-sm-12 col-xs-12 ratenow" id="regerrormsg" style="color:#FF0000;"></div>
  </div>


</form>
		
        <?php } ?>
     
   </div>
                    </div>
                   <div class="tab-pane fade" id="nav-spec" role="tabpanel" aria-labelledby="nav-home-spec">
                      <?php if($getProductRow['product_spec_ar']!=''){ echo $getProductRow['product_spec_ar']; }else{ echo '<h5>No Specification Available for this Product..</h5>'; } ?>
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

<?php include('footer.php'); ?>


	
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/jquery-3.2.1.min.js"></script>
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/bootstrap.min.js"></script>
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/owl.carousel.min.js"></script>
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/jquery-ui.min.js"></script>
<script>
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
		
		

      if(product_id=='' || pagetotal=='')
      {
        alert("Please Select Quantity..");
      }
	  
      else
      {
	  
        var dataString = 'product_id='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty + '&size='+ size + '&cs='+ cs;
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
</script>
</body>
</html>