<?php

session_start();
error_reporting(0);
include('include/db.class.php');
include('include/functions.php');

$getTab= base64_decode(base64_decode($_GET['tid']));
$getCat= base64_decode(base64_decode($_GET['did']));
$getSubcat= base64_decode(base64_decode($_GET['sid']));

$maxPriceData = $conn->prepare("select MAX(round(price)) as maxdata from products where status=1");
$maxPriceData->execute();
$maxPrice2 = $maxPriceData->fetch(PDO::FETCH_ASSOC);
$maxPrice=$maxPrice2['maxdata'];

$minPriceData = $conn->prepare("select MIN(round(price)) as mindata from products where status=1");
$minPriceData->execute();
$minPrice2 = $minPriceData->fetch(PDO::FETCH_ASSOC);
$minPrice=$minPrice2['mindata'];
?>
    
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
    <style>
		#loading
		{
		 text-align:center; 
		 background: url('<?= $WebsiteUrl.'/'; ?>images/loader.gif') no-repeat center; 
		 height: 200px;
		}
		.col-md-3 {
    float: left;
}
.view-product img {
    width: 100%;
    height: 200px;
}

@media (min-width: 250px) and (max-width: 768px){
.filter-menu {
    display: none;
}
.headline{padding:0px; margin:0px;}
.product-in{ margin:0px;}
}

@media (min-width: 769px) {

i.fas.fa-filter.filter-but {
    display: none;
}
}

</style>

<section class="category-section spad">
	<div class="container-fluid">
		<div class="row">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".filter-but").click(function(){
    $(".filter-menu").toggle();
  });
});
</script>
    
    <div class="col-lg-3 col-md-11 col-sm-11 col-xs-11 order-lg-1">
      <div class="product-in">
        <div class="headline">
          <h2 class="">Filters  <i class="fas fa-filter filter-but"> <i class="fas fa-bars"></i></i> </h2> 
        </div>
        <div class="wrapper filter-menu" style="padding:20px;">
          <div class="extra-controls form-inline">
            <div class="rangetext">
            <p>Price Range <b id="price_show">1 - <?php echo $maxPrice; ?></b></p>
                    
                    <div id="price_range"></div>
            </div>
          </div>
          
          <div class="extra-controls form-inline" style="margin-top:20px;">
            <div class="rangetext">
              <input type="number" id="hidden_minimum_price" value="1" class="common_selector2" placeholder="Min" style="width: 80px !important;padding: 5px;border: 1px solid goldenrod;" />
              <input type="number" id="hidden_maximum_price" value="<?php echo $maxPrice; ?>" class="common_selector2" placeholder="Max"  style="width: 80px !important;padding: 5px;border: 1px solid goldenrod;"/>
            </div>
          </div>
        </div>
        
        <!-- left acordion -->
        <ul id="accordion" class="accordion filter-menu">
          <li>
            <div class="link">Tab<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
              <?php
					  
				  $tab = 1;
				  $getTabQuery = tabAlldata($conn);
				  
				  foreach($getTabQuery as $getTabRow){
					$tab++;
			  ?>
              <li>
                <input type="checkbox" id="fruit<?= $tab; ?>" value="<?= $getTabRow['id']; ?>" <?php if($getTabRow['id']==$getTab){ echo 'checked'; } ?> class="common_selector tab">
                <label for="fruit<?= $tab; ?>"><?= $getTabRow['name_en']; ?></label>
              </li>
              <?php } ?>
            </ul>
          </li>
          <li>
            <div class="link">Category<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
              <?php
					  
				  $cat = 1;
				  $getCatQuery = catAlldata($conn);
				  
				  foreach($getCatQuery as $getCatRow){
					$cat++;
			  ?>
              <li>
                <input type="checkbox" id="fruitt<?= $cat; ?>" value="<?= $getCatRow['id']; ?>" <?php if($getCatRow['id']==$getCat){ echo 'checked'; } ?> class="common_selector category">
                <label for="fruitt<?= $cat; ?>"><?= $getCatRow['name_en']; ?></label>
              </li>
              <?php } ?>
            </ul>
          </li>
          
          <li>
            <div class="link">Sub Category<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
              <?php
					  
				  $subcat = 1;
				  $getsubCatQuery = getallSubcategory($conn);
				  
				  foreach($getsubCatQuery as $getsubCatRow){
					$subcat++;
			  ?>
              <li>
                <input type="checkbox" id="fruittt<?= $subcat; ?>" value="<?= $getsubCatRow['id']; ?>" <?php if($getsubCatRow['id']==$getSubcat){ echo 'checked'; } ?> class="common_selector subcategory">
                <label for="fruittt<?= $subcat; ?>"><?= $getsubCatRow['name_en']; ?></label>
              </li>
              <?php } ?>
            </ul>
          </li>
          <li>
            <div class="link">Discount<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
              <li>
                <input type="checkbox" id="fruitaa1" value="" class="common_selector discountt">
                <label for="fruitaa1">10% or more</label>
              </li>
              <li>
                <input type="checkbox" id="fruitaa2" value="" class="common_selector discountt">
                <label for="fruitaa2">20% or more</label>
              </li>
              <li>
                <input type="checkbox" id="fruitaa3" value="" class="common_selector discountt">
                <label for="fruitaa3">30% or more</label>
              </li>
              <li>
                <input type="checkbox" id="fruitaa4" value="" class="common_selector discountt">
                <label for="fruitaa4">40% or more</label>
              </li>
              <li>
                <input type="checkbox" id="fruitaa5" value="" class="common_selector discountt">
                <label for="fruitaa5">50% or more</label>
              </li>
              <li>
                <input type="checkbox" id="fruitaa6" value="" class="common_selector discountt">
                <label for="fruitaa6">60% or more</label>
              </li>
              
            </ul>
          </li>
          <li>
            <div class="link">Colors<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
              <?php
					  
				$colordata = $conn->prepare("select * from products_color where status=1");
				$colordata->execute();
				while($colorrow = $colordata->fetch(PDO::FETCH_ASSOC)){
			  ?>
              <li>
                <input type="checkbox" id="fruity<?= $colorrow['color']; ?>" value="<?= $colorrow['id']; ?>" class="common_selector colorss">
                <label for="fruity<?= $colorrow['color']; ?>"><?= $colorrow['color']; ?></label>
              </li>
              <?php } ?>
            </ul>
          </li>
          <li>
            <div class="link">Sizes<i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
              <?php	  
				$sizedata = $conn->prepare("select * from products_size where status=1");
				$sizedata->execute();
				while($sizerow = $sizedata->fetch(PDO::FETCH_ASSOC)){
			  
			  ?>
              <li>
                <input type="checkbox" id="fruitv<?= $sizerow['size']; ?>" value="<?= $sizerow['id']; ?>" class="common_selector sizess">
                <label for="fruitv<?= $sizerow['size']; ?>"><?= $sizerow['size']; ?></label>
              </li>
              <?php } ?>
            </ul>
          </li>
          
        </ul>
      </div>
    </div>
    <div class="col-lg-9 order-lg-2 mb-4 mt-sm-4 px-sm-4 mb-lg-0">
      <div class="main-sec">
        <div class="heading filter-menu">
        <div class="row">
        <div class="col-lg-4 col-md-6 col-xs-12 py-2">
          <h2><span>Sort : </span><select id="sortBy" class="common_selector3">
          	<option value="product_name_en ASC">Name (A-Z)</option>
            <option value="product_name_en DESC">Name (Z-A)</option>
            <option value="priceasc">Price (Min to Max)</option>
            <option value="pricedesc">Price (Max to Min)</option>
          </select>
          </h2>
          </div>
           <div class="col-lg-4  col-md-6 col-xs-12 py-2"> 
          <h2><span>Limit : </span><select id="PageSize" class="common_selector3">
          	<option value="10">10</option>
            <option value="20">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select><span> Per Page</span>
          </h2>
          </div>
          </div>
        </div>
        
        <div class="row">
          <!-- display data in filter data -->
          <div class="filter_data  col-md 12"></div>
        </div>
        
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
        <a href="<?= $WebsiteUrl.'/'; ?>cart"><button type="button" class="site-btn">CART</button></a>
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
        <a href="<?= $WebsiteUrl.'/'; ?>wishlist"><button type="button" class="site-btn">Wishlist</button></a>
        <button type="button" class="site-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
        

  </div>
</div>
</section>
<?php include('footer.php'); ?>

   


<script>
$(document).ready(function(){

    filter_data();

    function filter_data()
    {
        $('.filter_data').html('<div id="loading" style=""></div>');
        var action = 'fetch_data';
		var keyword = $('#myInput').val();
		var sortBy = $('#sortBy').val();
		var PageSize = $('#PageSize').val();
		var tab = get_filter('tab');
		var category = get_filter('category');
		var subcategory = get_filter('subcategory');
		var color = get_filter('colorss');
		var size = get_filter('sizess');
		var discount = get_filter('discountt');
		var minimum_price = $('#hidden_minimum_price').val();
        var maximum_price = $('#hidden_maximum_price').val();
        $.ajax({
            url:"<?= $WebsiteUrl.'/'; ?>product-data.php",
            method:"POST",
            data:{action:action, sortBy:sortBy, PageSize:PageSize, keywords:keyword, color:color, size:size, tab:tab, category:category, subcategory:subcategory, minimum_price:minimum_price, maximum_price:maximum_price, discount:discount},
            success:function(data){
			
                $('.filter_data').html(data);
            }
        });
    }

    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    $('.common_selector').click(function(){
        filter_data();
    });
	$('.common_selector2').keyup(function(){
        filter_data();
    });
	$('.common_selector3').change(function(){
        filter_data();
    });
	$('#hidden_minimum_price').change(function(){
		$('#price_show').html(minimum_price+ ' - ' +maximum_price);
        filter_data();
    });
	$('#hidden_maximum_price').change(function(){
	$('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
        filter_data();
    });

	$('#price_range').slider({ 
	range:true,
	min:1,
	max:<?php echo $maxPrice; ?>,
	values:[1, <?php echo $maxPrice; ?>],
	step:5,
	stop:function(event, ui)
	{
		$('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
		$('#hidden_minimum_price').val(ui.values[0]);
		$('#hidden_maximum_price').val(ui.values[1]);
		filter_data();
	}
    });
    
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
	//	alert(dataString);
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>ajax_cart.php",
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
                url: "<?= $WebsiteUrl.'/'; ?>ajax_cart.php",
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


	

});
</script>
</body>
</html>
