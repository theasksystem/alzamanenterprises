<?php

session_start();
error_reporting(0);
include('include/db.class.php');
include('include/functions.php');
 
 // for check action is set					    
if(isset($_POST["action"])){

	 $queryData = "SELECT id,quantity,discount,discount_type,slug,image,p_size,p_color,product_name_en,price,old_price FROM products WHERE status = '1'";
	 // for keywords
	 if(isset($_POST["keywords"]) && !empty($_POST["keywords"]))
	 {
	  $queryData .= " AND product_name_en LIKE '%".$_POST["keywords"]."%'";
	 }
	 // for  type
	 if(isset($_POST["tab"]))
	 {
	  $type_filter = implode("','", $_POST["tab"]);
	  $queryData .= " AND tab_id IN('".$type_filter."')";
	 }
	 // for category
	 if(isset($_POST["category"]))
	 {
	  $category_filter = implode("','", $_POST["category"]);
	  $queryData .= " AND cat_id IN('".$category_filter."')";
	 }
	 // for subcategory
	 if(isset($_POST["subcategory"]))
	 {
	  $subcategory_filter = implode("','", $_POST["subcategory"]);
	  $queryData .= " AND subcat_id IN('".$subcategory_filter."')";
	 }
	 //Size
	 if(isset($_POST["size"]))
	 {
	  $size_filter = implode(",", $_POST["size"]);
	  $queryData .= " AND p_size IN(".$size_filter.")";
	 }
	 //Size
	 if(isset($_POST["color"]))
	 {
	  $color_filter = implode(",", $_POST["color"]);
	  $queryData .= " AND p_color IN(".$color_filter.")";
	 }
	 //for Price
	 if(isset($_POST["minimum_price"]) && isset($_POST["maximum_price"]) && !empty($_POST["minimum_price"]) && !empty($_POST["maximum_price"]))
	 {
	  $queryData .= " AND price BETWEEN ".$_POST["minimum_price"]." AND ".$_POST["maximum_price"]."";
	 }
	 
	 
	 // for sorting
	 if(isset($_POST["sortBy"]) && !empty($_POST["sortBy"]))
	 {
	 if($_POST["sortBy"]=='priceasc'){ $sort = 'round(price) ASC'; }elseif($_POST["sortBy"]=='pricedesc'){ $sort = 'round(price) DESC'; }else{ $sort = $_POST["sortBy"];}
	   $queryData .= " ORDER BY ".$sort."";
	 }
	 // for limit
	 if(isset($_POST["PageSize"]) && !empty($_POST["PageSize"]))
	 {
	  $limit = $_POST["PageSize"];
	  $queryData .= " LIMIT $limit";
	 }

		//echo  $queryData;
		//get data of rows
		$d=1;
		$productQuery = $conn->prepare($queryData);
		$productQuery->execute();
		if($productQuery->rowCount() > 0){ 
		
		while($getFeaturedRow = $productQuery->fetch(PDO::FETCH_ASSOC)){
		$TotalQuantity = TotalQuantity($conn,$getFeaturedRow['id']);
		$totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid=".$getFeaturedRow['id']." and order_id IN(select id from cart_orders where status!='rejected')");
					$totalsales->execute();
					$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
					$stockrest=$totalsaleamt['total_sale'];
					$totalstock=$TotalQuantity-$stockrest;

?>

       <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
			<div class="product-item">
					<div class="pi-pic">
					    <?php  if($totalstock > 0){ ?>
                    <?php if($getFeaturedRow['discount']!='' && $getFeaturedRow['discount_type']!='' && $getFeaturedRow['discount_type']!=0){ ?>
						<div class="tag-new">
                        <?php if($getFeaturedRow['discount_type']==1){ echo $getFeaturedRow['discount'].' % OFF'; }elseif($getFeaturedRow['discount_type']==2){ echo 'QAR '.$getFeaturedRow['discount'].' OFF'; } ?>
                        </div>
                    <?php }}else{ ?>
                    <div class="tag-new2">SOLD OUT</div><?php  } ?>
						<a href="<?=$WebsiteUrl.'/'.$getFeaturedRow['slug']; ?>">
                        	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getFeaturedRow['image']; ?>" alt="<?= substr($getFeaturedRow['product_name_en'],0,20); ?>">
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
	   </div> 
        

 
        
        <?php  $d++; }}else{ ?>
        
        <div class="col-md-12"><h2>&emsp;No product Found<?php if($_POST["keywords"]!=''){?> for “<?php echo $_POST["keywords"];?>”<?php  }?> </h2></div>

    <?php  } } ?>


