<?php
session_start();
error_reporting(0);
include('../include/db.class.php');
include('../include/functions.php');

if(isset($_POST['quick_view']) && $_POST['quick_view']!='' ){

$productId = $_POST['quick_view'];
$getProductdetail = $conn->prepare("SELECT * FROM products WHERE id = '$productId'");
$getProductdetail->execute();
$getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);

$getProductTab = tabAlldataQuery($conn, $getProductRow['tab_id']);
$getProductCat = catAlldataQuery($conn, $getProductRow['cat_id']);
$getProductSubCat = getSubcategory($conn, $getProductRow['subcat_id']);

$getSellerdetail = $conn->prepare("SELECT id,company FROM tbl_admin WHERE id = '".$getProductRow['user_id']."'");
$getSellerdetail->execute();
$getSellerRow = $getSellerdetail->fetch(PDO::FETCH_ASSOC);

$getColor2 = $conn->prepare("SELECT a.color_id,b.id,b.color,b.color_ar FROM product_images a left join products_color b on a.color_id=b.id where a.product_id='".$getProductRow['id']."' and a.color_id!='' GROUP by a.color_id ORDER BY b.color ASC");
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
$TotalQuantity = TotalQuantity($conn,$getProductRow['id']);

$totalstock=$TotalQuantity-$stockrest;

$getwishlistRow = $conn->prepare("SELECT COUNT(*) as tot from wishlist where un_id = '".$_SESSION['UNIQUEID2']."' and pid = '".$getProductRow['id']."'");
$getwishlistRow->execute();
$getwishlistRow2 = $getwishlistRow->fetch(PDO::FETCH_ASSOC);
$getwishlistRow = $getwishlistRow2['tot'];

?>
<script>
    
    $('.product-thumbs-track > .pt').on('click', function(){
		$('.product-thumbs-track .pt').removeClass('active');
		$(this).addClass('active');
		var imgurl = $(this).data('imgbigurl');
		var bigImg = $('.product-big-img').attr('src');
		if(imgurl != bigImg) {
			$('.product-big-img').attr({src: imgurl});
			$('.zoomImg').attr({src: imgurl});
		}
	});
</script>
<!------------------------------Models----------------------------------------------->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
          <div class="container">
			<div class="row"><button type="button" class="close float-left" data-dismiss="modal" style="float: left;
    background: goldenrod;
    padding: 6px;
    color: #fff;
    position: absolute;
    left: -16px;
    top: -16px;">&times;</button>
				
				
				<div class="col-lg-6  col-md-12 col-sm-12 col-xs-12">
					<div class="product-pic-zoom">
						<img class="product-big-img" src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_ar']; ?>">
					</div>
					<div class="product-thumbs float-left" tabindex="1" style="overflow: auto;
    outline: none;
    margin-bottom: 20px;
    width: 100%;">
						<div class="product-thumbs-track loadImage2" style="display: inline-flex;">
							<div class="pt active" data-imgbigurl="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>"><img src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_ar']; ?>"></div>
                            
                            <?php 		
								$allImages = $conn->query("SELECT image FROM `product_images` WHERE product_id = '".$getProductRow['id']."' ORDER BY `id` asc");
								$allImages->execute();
								while($getImagesRow = $allImages->fetch(PDO::FETCH_ASSOC)){
									
						    ?>
                            
							<div class="pt" data-imgbigurl="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getImagesRow['image']; ?>"><img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getImagesRow['image']; ?>" alt="<?= $getProductRow['product_name_ar']; ?>"></div>
                            
                           <?php } ?> 
						</div>
					</div>
				</div>
				<div class="col-lg-6  col-md-12 col-sm-12 col-xs-12 product-details text-right">
					<h2 class="p-title"><?php if($getProductRow['product_name_ar']!=''){ echo $getProductRow['product_name_ar']; }else{ echo $getProductRow['product_name_en']; } ?></h2>
					<h5><?=$getProductRow['price'].' ريال'; ?></h5>
                    <?php if(!empty($getProductRow['old_price'])){ ?>
                    <h6><del><?=$getProductRow['old_price'].' ريال'; ?></del></h6>
                    <?php } ?>
                    <p>
                    <b style="color:#ff0000;">
                        <?php if($getProductRow['discount']!='' && $getProductRow['discount_type']!='' && $getProductRow['discount_type']!=0){ ?>
      					<?php if($getProductRow['discount_type']==1){ echo $getProductRow['discount'].' % OFF'; }elseif($getProductRow['discount_type']==2){ echo $getProductRow['discount'].' ريال'.' OFF'; }} ?>
                        </b>
                    </p>
                    <h4 class="p-stock"> <span><?php if($totalstock > 0){ echo 'متوفر: في المخزن '. $totalstock; }else{ echo 'متوفر: نفدت الكمية'; } ?></span></h4>

                    <p><?php if($getProductRow['short_desc_ar']!=''){ echo $getProductRow['short_desc_ar']; }else{ echo $getProductRow['short_desc_en']; } ?></p>
					
					<!--<div class="p-review">
						<a href="">3 reviews</a>|<a href="">Add your review</a>
					</div>-->
                    
					<div class="fw-size-choose" style="display: flex;">
						<p>الحجم : </p>&nbsp;&nbsp;
                        <?php if($getSize2->rowCount() != 0){ 
						$adddd=1;
						while($getSize = $getSize2->fetch(PDO::FETCH_ASSOC)){
						?>
						<div class="sc-item">
							<input type="radio" name="size2" id="xs-sizee<?=$adddd; ?>" value="<?=$getSize['id']; ?>">
							<label for="xs-sizee<?=$adddd; ?>"><?php if($getSize['size_ar']!=''){ echo $getSize['size_ar']; }else{ echo $getSize['size']; } ?></label>
						</div>
                        <?php $adddd++; }}else{ ?>
                        <div class="sc-item">
							<p>N/A</p>
                            <input type="radio" name="size2" value="NA" checked>
						</div>
						<?php } ?>
					</div>
                    
                    <div class="fw-color-choose" style="display: flex;">
						<p>اللون : </p>&nbsp;&nbsp;
                         <div class="cs-item">
                     <?php if($getColor2->rowCount() != 0){
					 $adcolor=1;
					 while($getColor = $getColor2->fetch(PDO::FETCH_ASSOC)){
					 $TotalQuantitybyColor = TotalQuantitybyColor($conn,$getProductRow['id'],$getColor['id']);
					 ?>   
                       
								<input type="radio" name="cs2" id="gray-colorr<?=$adcolor; ?>" value="<?=$getColor['id']; ?>" onClick=photoChange2('<?=$getColor['id']; ?>','<?=$getProductRow["id"]; ?>'); <?php if($TotalQuantitybyColor <= 0){?> disabled<?php } ?>>
								<label <?php if($TotalQuantitybyColor <= 0){?>class="newdis" <?php } ?> for="gray-colorr<?=$adcolor; ?>">
									<?php if($getColor['color_ar']!=''){ echo $getColor['color_ar']; }else{ echo $getColor['color']; } ?>
								</label>
					   
                        
                     <?php $adcolor++; }}else{ ?>
                     <p>N/A</p><input type="radio" name="cs2" value="NA" checked> 
                     <?php } ?>
                        </div> 
					</div>
                    
                    
					<div class="quantity">
                     <p style="width:auto;">الكمية : </p>
                        <p><table  style="width:auto;">
          <td class="invert"  style="width:auto;">
          <div class="custom-qty" style="display: inline-flex;">
                                      <button onClick="var result = document.getElementById('qtyM'); var qty22 = result.value; if( !isNaN( qty22 ) &amp;&amp; qty22 &gt; 1 ) result.value--;return false;" class="reduced items qtybutton" type="button"> <i class="fa fa-minus"></i> </button>
                                      <input type="number" class="input-text qty" title="Qty" value="1" maxlength="8" id="qtyM" name="qty" style="max-width:100px; width:40px;text-align: center;" readonly>
                                      <button onClick="var result = document.getElementById('qtyM'); var qty22 = result.value; if( !isNaN( qty22 )) result.value++;return false;" class="increase items qtybutton" type="button"> <i class="fa fa-plus"></i> </button>
                                    </div></td>
        </table> </p>
                        
                    </div>
                    <input type="hidden" id="pagetotal2" value="<?= $getProductRow['price']; ?>" />
                    <div id="wishref">
                            <?php  if($totalstock > 0){ ?>
                                <button class="add-to-cart3 site-btn" type="button"><i class="fas fa-shopping-cart"></i>اضف الى السلة</button>
                            <?php }else{ ?>
                            	<button class="site-btn" type="button"><i class="fas fa-shopping-cart"></i> Out of Stock</button>
                            <?php } ?>
                              <img id="cartloader" height="40" width="40" style="display: none;" src="<?= $WebsiteUrl.'/'; ?>images/loader.gif">
                              
                    	
                            
                            
                              
                    </div>
					<div id="accordion" class="accordion-area col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link active text-right" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">معلومات اخرى</button>
							</div>
							<div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="panel-body  text-right">
									<p><b>الشحن والتوصيل :</b> <span><?php if($getProductRow['ship_charge'] != ''){ echo $getProductRow['ship_charge'].' ريال'; }else{ echo 'الشحن مجاني'; } ?></span></p>
                                    <p><b>متجر :</b> <span><a href="store.php?own=<?=base64_encode(base64_encode(base64_encode($getSellerRow['id']))); ?>" style="color:goldenrod;"><?=$getSellerRow['company']; ?></a></span></p>
								</div>
							</div>
						</div>
						
						<div class="panel">
							<div class="panel-header" id="headingThree">
								<button class="panel-link  text-right" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">الشحن والارجاع</button>
							</div>
							<div id="collapse3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
								<div class="panel-body text-right">
									<p><strong>No Returns & Returns option should avalible.</strong></p>
								</div>
							</div>
						</div>
                        
					</div>
					
				</div>
				
			</div>
		</div>
        </div>
       
      </div>
      
    </div>
  </div>
 <script>
    function photoChange2(val,val2){
    //alert(val2);
    var dataString = 'val='+ val + '&val2='+ val2;
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>models.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                   // $(".loads").show();
                },
                complete: function(){
				//	$(".loads").hide();
                },
                success: function(response){
				//alert(data);
			    $('.loadImage2').html(response);
                }
            });
}  
     $('.add-to-cart3').on("click", function(){

	var product_id = '<?= $getProductRow['id']; ?>';
	var pagetotal = $('#pagetotal2').val();
	var qty =  $('#qtyM').val();
	var size = $("input[name='size2']:checked").val();
	var cs =   $("input[name='cs2']:checked").val();
	
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
        alert("يرجى ادخال الكمية المناسبة");
        return false;
      }
	  if(size==undefined)
      {
	  	//alert(size);
        alert("يرجى اختيار الحجم");
        return false;
      }
	  if(cs==undefined)
      {
	  	
		//alert(cs);
        alert("يرجى إختيار اللون");
        return false;
      }
      else
      {
	  
        var dataString = 'product_id='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty + '&size='+ size2 + '&cs='+ cs2;
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
                success: function(data){
				//alert(data);
				window.location.href='cart';
                }
            });
      }

});

     
 </script> 
  
<?php } 

if(isset($_POST['quick_view2']) && $_POST['quick_view2']!='' ){

$productId = $_POST['quick_view2'];
$getProductdetail = $conn->prepare("SELECT * FROM products WHERE id = '$productId'");
$getProductdetail->execute();
$getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);

$getProductTab = tabAlldataQuery($conn, $getProductRow['tab_id']);
$getProductCat = catAlldataQuery($conn, $getProductRow['cat_id']);
$getProductSubCat = getSubcategory($conn, $getProductRow['subcat_id']);

$getSellerdetail = $conn->prepare("SELECT id,company FROM tbl_admin WHERE id = '".$getProductRow['user_id']."'");
$getSellerdetail->execute();
$getSellerRow = $getSellerdetail->fetch(PDO::FETCH_ASSOC);

$getColor2 = $conn->prepare("SELECT a.color_id,b.id,b.color,b.color_ar FROM product_images a left join products_color b on a.color_id=b.id where a.product_id='".$getProductRow['id']."' and a.color_id!='' GROUP by a.color_id ORDER BY b.color ASC");
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
$TotalQuantity = TotalQuantity($conn,$getProductRow['id']);

$totalstock=$TotalQuantity-$stockrest;

$getwishlistRow = $conn->prepare("SELECT COUNT(*) as tot from wishlist where un_id = '".$_SESSION['UNIQUEID2']."' and pid = '".$getProductRow['id']."'");
$getwishlistRow->execute();
$getwishlistRow2 = $getwishlistRow->fetch(PDO::FETCH_ASSOC);
$getwishlistRow = $getwishlistRow2['tot'];

?>
<script>
    
    $('.product-thumbs-track > .pt').on('click', function(){
		$('.product-thumbs-track .pt').removeClass('active');
		$(this).addClass('active');
		var imgurl = $(this).data('imgbigurl');
		var bigImg = $('.product-big-img').attr('src');
		if(imgurl != bigImg) {
			$('.product-big-img').attr({src: imgurl});
			$('.zoomImg').attr({src: imgurl});
		}
	});
</script>
<!------------------------------Models----------------------------------------------->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
          <div class="container">
			<div class="row"><button type="button" class="close float-left" data-dismiss="modal" style="float: left;
    background: goldenrod;
    padding: 6px;
    color: #fff;
    position: absolute;
    left: -16px;
    top: -16px;">&times;</button>
				
				
				<div class="col-lg-6  col-md-12 col-sm-12 col-xs-12">
					<div class="product-pic-zoom">
						<img class="product-big-img" src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_ar']; ?>">
					</div>
					<div class="product-thumbs float-left" tabindex="1" style="overflow: auto;
    outline: none;
    margin-bottom: 20px;
    width: 100%;">
						<div class="product-thumbs-track loadImage2" style="display: inline-flex;">
							<div class="pt active" data-imgbigurl="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>"><img src="<?=$WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" alt="<?= $getProductRow['product_name_ar']; ?>"></div>
                            
                            <?php 		
								$allImages = $conn->query("SELECT image FROM `product_images` WHERE product_id = '".$getProductRow['id']."' ORDER BY `id` asc");
								$allImages->execute();
								while($getImagesRow = $allImages->fetch(PDO::FETCH_ASSOC)){
									
						    ?>
                            
							<div class="pt" data-imgbigurl="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getImagesRow['image']; ?>"><img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getImagesRow['image']; ?>" alt="<?= $getProductRow['product_name_ar']; ?>"></div>
                            
                           <?php } ?> 
						</div>
					</div>
				</div>
				<div class="col-lg-6  col-md-12 col-sm-12 col-xs-12 product-details text-right">
					<h2 class="p-title"><?php if($getProductRow['product_name_ar']!=''){ echo $getProductRow['product_name_ar']; }else{ echo $getProductRow['product_name_en']; } ?></h2>
					<h5><?=$getProductRow['price'].' ريال'; ?></h5>
                    <?php if(!empty($getProductRow['old_price'])){ ?>
                    <h6><del><?=$getProductRow['old_price'].' ريال'; ?></del></h6>
                    <?php } ?>
                    <p>
                    <b style="color:#ff0000;">
                        <?php if($getProductRow['discount']!='' && $getProductRow['discount_type']!='' && $getProductRow['discount_type']!=0){ ?>
      					<?php if($getProductRow['discount_type']==1){ echo $getProductRow['discount'].' % OFF'; }elseif($getProductRow['discount_type']==2){ echo $getProductRow['discount'].' ريال'.' OFF'; }} ?>
                        </b>
                    </p>
					<!--<h4 class="p-stock">Available: <span><?php if($totalstock > 0){ echo $totalstock.' Left In Stock'; }else{ echo 'Out of Stock'; } ?></span></h4>-->
                    <p><?php if($getProductRow['short_desc_ar']!=''){ echo $getProductRow['short_desc_ar']; }else{ echo $getProductRow['short_desc_en']; } ?></p>
					
					<!--<div class="p-review">
						<a href="">3 reviews</a>|<a href="">Add your review</a>
					</div>-->
                    
					<div class="fw-size-choose" style="display: flex;">
						<p>الحجم : </p>&nbsp;&nbsp;
                        <?php if($getSize2->rowCount() != 0){ 
						$adddd=1;
						while($getSize = $getSize2->fetch(PDO::FETCH_ASSOC)){
						?>
						<div class="sc-item">
							<input type="radio" name="size2" id="xs-sizee<?=$adddd; ?>" value="<?=$getSize['id']; ?>">
							<label for="xs-sizee<?=$adddd; ?>"><?php if($getSize['size_ar']!=''){ echo $getSize['size_ar']; }else{ echo $getSize['size']; } ?></label>
						</div>
                        <?php $adddd++; }}else{ ?>
                        <div class="sc-item">
							<p>N/A</p>
                            <input type="radio" name="size2" value="NA" checked>
						</div>
						<?php } ?>
					</div>
                    
                    <div class="fw-color-choose" style="display: flex;">
						<p>اللون : </p>&nbsp;&nbsp;
                         <div class="cs-item">
                     <?php if($getColor2->rowCount() != 0){
					 $adcolor=1;
					 while($getColor = $getColor2->fetch(PDO::FETCH_ASSOC)){
					 $TotalQuantitybyColor = TotalQuantitybyColor($conn,$getProductRow['id'],$getColor['id']);
					 ?>   
                       
								<input type="radio" name="cs2" id="gray-colorr<?=$adcolor; ?>" value="<?=$getColor['id']; ?>" onClick=photoChange2('<?=$getColor['id']; ?>','<?=$getProductRow["id"]; ?>'); <?php if($TotalQuantitybyColor <= 0){?> disabled<?php } ?>>
								<label <?php if($TotalQuantitybyColor <= 0){?>class="newdis" <?php } ?> for="gray-colorr<?=$adcolor; ?>">
									<?php if($getColor['color_ar']!=''){ echo $getColor['color_ar']; }else{ echo $getColor['color']; } ?>
								</label>
					   
                        
                     <?php $adcolor++; }}else{ ?>
                     <p>N/A</p><input type="radio" name="cs2" value="NA" checked> 
                     <?php } ?>
                        </div> 
					</div>
                    
                    
					<div class="quantity">
                     <p style="width:auto;">الكمية : </p>
                        <p><table  style="width:auto;">
          <td class="invert"  style="width:auto;">
          <div class="custom-qty" style="display: inline-flex;">
                                      <button onClick="var result = document.getElementById('qtyM'); var qty22 = result.value; if( !isNaN( qty22 ) &amp;&amp; qty22 &gt; 1 ) result.value--;return false;" class="reduced items qtybutton" type="button"> <i class="fa fa-minus"></i> </button>
                                      <input type="number" class="input-text qty" title="Qty" value="1" maxlength="8" id="qtyM" name="qty" style="max-width:100px; width:40px;text-align: center;" readonly>
                                      <button onClick="var result = document.getElementById('qtyM'); var qty22 = result.value; if( !isNaN( qty22 )) result.value++;return false;" class="increase items qtybutton" type="button"> <i class="fa fa-plus"></i> </button>
                                    </div></td>
        </table> </p>
                        
                    </div>
                    <input type="hidden" id="pagetotal2" value="<?= $getProductRow['price']; ?>" />
                    <div id="wishref">
                            <?php  if($totalstock > 0){ ?>
                                <button class="add-to-cart3 site-btn" type="button"><i class="fas fa-shopping-cart"></i>اضف الى السلة</button>
                            <?php }else{ ?>
                            	<button class="site-btn" type="button"><i class="fas fa-shopping-cart"></i> Out of Stock</button>
                            <?php } ?>
                              <img id="cartloader" height="40" width="40" style="display: none;" src="<?= $WebsiteUrl.'/'; ?>images/loader.gif">
                              
                    	
                            
                            
                              
                    </div>
					
				</div>
				
			</div>
		</div>
        </div>
       
      </div>
      
    </div>
  </div>
 <script>
    function photoChange2(val,val2){
    //alert(val2);
    var dataString = 'val='+ val + '&val2='+ val2;
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>models.php",
                data: dataString,
                cache: false,
                beforeSend: function(){
					
                   // $(".loads").show();
                },
                complete: function(){
				//	$(".loads").hide();
                },
                success: function(response){
				//alert(data);
			    $('.loadImage2').html(response);
                }
            });
}  
     $('.add-to-cart3').on("click", function(){

	var product_id = '<?= $getProductRow['id']; ?>';
	var pagetotal = $('#pagetotal2').val();
	var qty =  $('#qtyM').val();
	var size = $("input[name='size2']:checked").val();
	var cs =   $("input[name='cs2']:checked").val();
	
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
        alert("يرجى ادخال الكمية المناسبة");
        return false;
      }
	  if(size==undefined)
      {
	  	//alert(size);
        alert("يرجى اختيار الحجم");
        return false;
      }
	  if(cs==undefined)
      {
	  	
		//alert(cs);
        alert("يرجى إختيار اللون");
        return false;
      }
      else
      {
	  
        var dataString = 'product_id='+ product_id + '&pagetotal='+ pagetotal + '&qty='+ qty + '&size='+ size2 + '&cs='+ cs2;
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
                success: function(data){
				//alert(data);
				window.location.href='cart';
                }
            });
      }

});

     
 </script> 
 
<?php } ?>
