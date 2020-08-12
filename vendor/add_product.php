<?php
session_start();
//error_reporting(0);
include 'functions/db.class.php';
include 'functions/image-resize.php';

if($_SESSION['VENDOR_ID']=='')
{
  echo "<script>window.location.href='../vendors'</script>";
}

$id = base64_decode($_GET['id']); // -- Get id -- //
$edit = $_GET['edit'];

$w = 500; $h = 500;
$time = time();

$userDetails22 = $conn->prepare("select store_storage,category from tbl_admin where id = :adm_id");
$userDetails22->bindValue(':adm_id', $_SESSION['VENDOR_ID'], PDO::PARAM_STR);
$userDetails22->execute();
$userDetailsFetch22 = $userDetails22->fetch(PDO::FETCH_ASSOC);
$totalUploadLimit=$userDetailsFetch22['store_storage'];
$vendor_category=$userDetailsFetch22['category'];

$countpp = $conn->prepare("select * from products where user_id = '".$_SESSION['VENDOR_ID']."'");
$countpp->execute();

if($countpp->rowCount()>=$totalUploadLimit)
{
  echo '<script>alert("Sorry You have reached your Maximum Product Upload Limit. Please Contact with Alzaman Enterprises to Increase Your Limit or Delete Some Products.")</script>';
  echo "<script>window.location.href='product-user.php'</script>";
}
// ----- Get Result ---- //

$getResult = $conn->prepare("SELECT * FROM `products` WHERE `id` = :id");
$getResult->bindParam(':id', $id, PDO::PARAM_INT);
$getResult->execute();
$editval = $getResult->fetch(PDO::FETCH_ASSOC);

if(isset($_REQUEST['submit'])){

$tab = $_POST['tab'];
$cat = $_POST['cat'];
$sub_cat = $_POST['sub_cat'];
$product_name_en = $_POST['product_name_en'];
$product_name_ar = $_POST['product_name_ar'];
$short_desc_en = $_POST['short_desc_en'];
$short_desc_ar = $_POST['short_desc_ar'];
$long_desc_en = $_POST['long_desc_en'];
$long_desc_ar = $_POST['long_desc_ar'];
$product_spec_en = $_POST['product_spec_en'];
$product_spec_ar = $_POST['product_spec_ar'];
$free_shipping_amount = $_POST['free_shipping_amount'];
$free_shipping_qty = $_POST['free_shipping_qty'];
$discount_type = $_POST['discount_type'];  // 1 for percent 2 for Manual
$discount = $_POST['discount'];
$price2 = $_POST['price'];
if($discount_type==1){

$price3 = $price2-(($price2*$discount)/100);
$price = number_format($price3, 2, '.', '');
$old_price = $price2;
}
elseif($discount_type==2){

$price3 = $price2-$discount;
$price = number_format($price3, 2, '.', '');
$old_price = $price2;
}
else
{
$price = $price2;
$old_price = '';
}

$ship_charge = $_POST['ship_charge'];
$insert_ip = $_SERVER['REMOTE_ADDR'];

/////////////////////////Multiple Size Pick///////////////////////////
$sizeids = array();
foreach($_POST['sizeof'] as $sizeval)
{
$sizeids[] = (int) $sizeval;
}
$sizeids = implode(',', $sizeids);

$img = $_FILES['image']['name'];
$imgtmp = $_FILES['image']['tmp_name'];

$img ? $im = time().'-'.$img : $im = '';

if($img!=''){
	$files = mediumresize($w, $h, $time);
}

$count = $conn->prepare("select id from products where product_name_en = :product_name_en");
$count->bindParam(':product_name_en', $product_name_en, PDO::PARAM_STR);
$count->execute();

if($count->rowCount()==0)
{
  $slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace( "/[^a-zA-Z0-9\-]/", '-', addslashes($_POST['product_name_en']))),"-")); }
else
{
  $slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace( "/[^a-zA-Z0-9\-]/", '-', addslashes($_POST['product_name_en']."-".time()))),"-"));
}

		$sql = $conn->prepare("INSERT INTO `products`(`user_id`, `tab_id`, `cat_id`, `subcat_id`, `product_name_en`, `product_name_ar`, `short_desc_en`, `short_desc_ar`, `long_desc_en`, `long_desc_ar`, `product_spec_en`, `product_spec_ar`, `image`, `discount_type`, `discount`, `price`, `old_price`, `slug`, `insert_ip`, `created_at`,`p_size`, `ship_charge`, `free_shipping_amount`, `free_shipping_qty`) VALUES (:user_id, :tab_id, :cat_id, :subcat_id, :product_name_en, :product_name_ar, :short_desc_en, :short_desc_ar, :long_desc_en, :long_desc_ar, :product_spec_en, :product_spec_ar, :image, :discount_type, :discount, :price, :old_price, :slug, :insert_ip, :created_at, :size, :ship_charge, :free_shipping_amount, :free_shipping_qty)");
		
		$sql->bindParam(':user_id', $_SESSION['VENDOR_ID'], PDO::PARAM_INT);
		$sql->bindParam(':tab_id', $tab, PDO::PARAM_INT);
		$sql->bindParam(':cat_id', $cat, PDO::PARAM_INT);
		$sql->bindParam(':subcat_id', $sub_cat, PDO::PARAM_INT);
		$sql->bindParam(':product_name_en', $product_name_en, PDO::PARAM_STR);
		$sql->bindParam(':product_name_ar', $product_name_ar, PDO::PARAM_STR);
		$sql->bindParam(':short_desc_en', $short_desc_en, PDO::PARAM_STR);
		$sql->bindParam(':short_desc_ar', $short_desc_ar, PDO::PARAM_STR);
		$sql->bindParam(':long_desc_en', $long_desc_en, PDO::PARAM_STR);
		$sql->bindParam(':long_desc_ar', $long_desc_ar, PDO::PARAM_STR);
		$sql->bindParam(':product_spec_en', $product_spec_en, PDO::PARAM_STR);
		$sql->bindParam(':product_spec_ar', $product_spec_ar, PDO::PARAM_STR);
		$sql->bindParam(':free_shipping_amount', $free_shipping_amount, PDO::PARAM_STR);
		$sql->bindParam(':free_shipping_qty', $free_shipping_qty, PDO::PARAM_STR);
		$sql->bindParam(':image', $im, PDO::PARAM_STR);
		$sql->bindParam(':discount_type', $discount_type, PDO::PARAM_STR);
		$sql->bindParam(':discount', $discount, PDO::PARAM_STR);
		$sql->bindParam(':price', $price, PDO::PARAM_STR);
		$sql->bindParam(':old_price', $old_price, PDO::PARAM_STR);
		$sql->bindParam(':ship_charge', $ship_charge, PDO::PARAM_INT);
		$sql->bindParam(':slug', $slug, PDO::PARAM_STR);
		$sql->bindParam(':insert_ip', $insert_ip, PDO::PARAM_STR);
		$sql->bindParam(':created_at', $globaldate, PDO::PARAM_STR);
		$sql->bindParam(':size', $sizeids, PDO::PARAM_STR);
		$sql->execute();
		
		$last_id = $conn->lastInsertId(); // get last query insert id 
		
		// Count total files with color
		for($n = 1; $n<=10; $n++){
		    $color_id = $_POST['colorof'.$n.''];
		    $stockk = $_POST['stock'.$n.''];
      		$countfiles2 = count($_FILES['photo'.$n.'']['name']);
    		for($j = 0; $j<$countfiles2; $j++){
    		
    			
    			if($_FILES['photo'.$n.'']['name'][$j]!=''){
    			
    			$filename2 = $last_id.time()."-".$_FILES['photo'.$n.'']['name'][$j];
    			$dest2 = '../adminuploads/product/'.$filename2;
    			$filetmp2 = $_FILES['photo'.$n.'']['tmp_name'][$j];
    			move_uploaded_file($filetmp2,$dest2); // Upload file
    			
    			$insQuery = $conn->exec("INSERT INTO `product_images`(`product_id`, `color_id`, `stock`, `image`, `created_at`) VALUES ('$last_id', '$color_id', '$stockk', '$filename2', now())");
    			
    			}
    		}
    		
		}
		
		if($sql == true){
		
			echo '<script>alert("Product has been Added Successfully !!")</script>';
			echo '<script>window.location.href="add_product.php"</script>';
		
		}else{
		
			echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';
		
		}
 	
}	

// update query
if(isset($_REQUEST['update'])){
		
$tab = $_POST['tab'];
$cat = $_POST['cat'];
$sub_cat = $_POST['sub_cat'];
$product_name_en = $_POST['product_name_en'];
$product_name_ar = $_POST['product_name_ar'];
$short_desc_en = $_POST['short_desc_en'];
$short_desc_ar = $_POST['short_desc_ar'];
$long_desc_en = $_POST['long_desc_en'];
$long_desc_ar = $_POST['long_desc_ar'];
$product_spec_en = $_POST['product_spec_en'];
$product_spec_ar = $_POST['product_spec_ar'];
$free_shipping_amount = $_POST['free_shipping_amount'];
$free_shipping_qty = $_POST['free_shipping_qty'];

$discount_type = $_POST['discount_type'];  // 1 for percent 2 for Manual
$discount = $_POST['discount'];
$price2 = $_POST['price'];
if($discount_type==1){

$price3 = $price2-(($price2*$discount)/100);
$price = number_format($price3, 2, '.', '');
$old_price = $price2;
}
elseif($discount_type==2){

$price3 = $price2-$discount;
$price = number_format($price3, 2, '.', '');
$old_price = $price2;
}
else
{
$price = $price2;
$old_price = '';
}
$ship_charge = $_POST['ship_charge'];
$update_ip = $_SERVER['REMOTE_ADDR'];
		
$oldimg = $_POST['oldimg'];

$img = $_FILES['image']['name'];
$imgtmp = $_FILES['image']['tmp_name'];

$img ? $im = time().'-'.$img : $im = $oldimg;

if($img!=''){
	unlink('../adminuploads/product/'.$oldimg);
	$files = mediumresize($w, $h, $time);
}

/////////////////////////Multiple Size Pick///////////////////////////
$sizeids = array();
foreach($_POST['sizeof'] as $sizeval)
{
$sizeids[] = (int) $sizeval;
}
$sizeids = implode(',', $sizeids);

$count = $conn->prepare("select id from products where id!=:id  and product_name_en = :product_name_en");
$count->bindParam(':product_name_en', $product_name_en, PDO::PARAM_STR);
$count->bindParam(':id', $id, PDO::PARAM_INT);
$count->execute();

if($count->rowCount()==0)
{
  $slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace( "/[^a-zA-Z0-9\-]/", '-', addslashes($_POST['product_name_en']))),"-")); }
else
{
  $slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace( "/[^a-zA-Z0-9\-]/", '-', addslashes($_POST['product_name_en']."-".time()))),"-"));
}
		
		$sql = $conn->prepare("UPDATE `products` SET `user_id` = :user_id, `tab_id` = :tab_id, `cat_id` = :cat_id, `subcat_id` = :subcat_id, `product_name_en` = :product_name_en, `product_name_ar` = :product_name_ar, `short_desc_en` = :short_desc_en, `short_desc_ar` = :short_desc_ar, `long_desc_en` = :long_desc_en, `long_desc_ar` = :long_desc_ar, `product_spec_en` = :product_spec_en, `product_spec_en` = :product_spec_en, `image` = :image, `discount_type` = :discount_type, `discount` = :discount, `price` = :price, `old_price` = :old_price, `p_size` = :size, `slug` = :slug, `update_ip` = :update_ip, `updated_at` = :updated_at, `ship_charge` = :ship_charge, `free_shipping_amount` = :free_shipping_amount, `free_shipping_qty` = :free_shipping_qty WHERE `id` = :id");
		
		$sql->bindParam(':id', $id, PDO::PARAM_INT);
		$sql->bindParam(':user_id', $_SESSION['VENDOR_ID'], PDO::PARAM_INT);
		$sql->bindParam(':tab_id', $tab, PDO::PARAM_INT);
		$sql->bindParam(':cat_id', $cat, PDO::PARAM_INT);
		$sql->bindParam(':subcat_id', $sub_cat, PDO::PARAM_INT);
		$sql->bindParam(':product_name_en', $product_name_en, PDO::PARAM_STR);
		$sql->bindParam(':product_name_ar', $product_name_ar, PDO::PARAM_STR);
		$sql->bindParam(':short_desc_en', $short_desc_en, PDO::PARAM_STR);
		$sql->bindParam(':short_desc_ar', $short_desc_ar, PDO::PARAM_STR);
		$sql->bindParam(':long_desc_en', $long_desc_en, PDO::PARAM_STR);
		$sql->bindParam(':long_desc_ar', $long_desc_ar, PDO::PARAM_STR);
		$sql->bindParam(':product_spec_en', $product_spec_en, PDO::PARAM_STR);
		$sql->bindParam(':product_spec_ar', $product_spec_ar, PDO::PARAM_STR);
		$sql->bindParam(':free_shipping_amount', $free_shipping_amount, PDO::PARAM_STR);
		$sql->bindParam(':free_shipping_qty', $free_shipping_qty, PDO::PARAM_STR);
		$sql->bindParam(':image', $im, PDO::PARAM_STR);
		$sql->bindParam(':discount_type', $discount_type, PDO::PARAM_STR);
		$sql->bindParam(':discount', $discount, PDO::PARAM_STR);
		$sql->bindParam(':price', $price, PDO::PARAM_STR);
		$sql->bindParam(':old_price', $old_price, PDO::PARAM_STR);
		$sql->bindParam(':ship_charge', $ship_charge, PDO::PARAM_INT);
		$sql->bindParam(':slug', $slug, PDO::PARAM_STR);
		$sql->bindParam(':update_ip', $update_ip, PDO::PARAM_STR);
		$sql->bindParam(':updated_at', $globaldate, PDO::PARAM_STR);
		$sql->bindParam(':size', $sizeids, PDO::PARAM_STR);
		$sql->execute();
		
		// Count total files with color
		for($n = 1; $n<=10; $n++){
		    $color_id = $_POST['colorof'.$n.''];
		    $stockk = $_POST['stock'.$n.''];
      		$countfiles2 = count($_FILES['photo'.$n.'']['name']);
    		for($j = 0; $j<$countfiles2; $j++){
    		
    			
    			if($_FILES['photo'.$n.'']['name'][$j]!=''){
    			
    			$filename2 = $id.time()."-".$_FILES['photo'.$n.'']['name'][$j];
    			$dest2 = '../adminuploads/product/'.$filename2;
    			$filetmp2 = $_FILES['photo'.$n.'']['tmp_name'][$j];
    			move_uploaded_file($filetmp2,$dest2); // Upload file
    			
    			$insQuery = $conn->exec("INSERT INTO `product_images`(`product_id`, `color_id`, `stock`, `image`, `created_at`) VALUES ('$id', '$color_id', '$stockk', '$filename2', now())");
    			
    			}
    		}
    		
		}
		
		if($sql == true){
		
		echo '<script>alert("Product has been Updated Successfully !!")</script>';
		echo '<script>window.location.href="product-user.php"</script>';
		
		}else{
		
			echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';
		
		}
	
	}


?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
<?=$WebsiteTitle; ?>
| Add Product</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect.
        -->
<link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="plugins/datepicker/datepicker3.css">

<script>

	function PreviewImage()
	{
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("image").files[0]);
		oFReader.onload = function (oFREvent) {
			document.getElementById("uploadPreview").src = oFREvent.target.result;
		};
	};

</script>



</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <!-- Main Header -->
  <header class="main-header">
    <!-- Logo -->
    <a href="home-user.php" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b></b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">
    <?=$logo; ?>
    </span> </a>
    <!-- Header Navbar -->
    <?php include 'header.php'; ?>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <?php include 'menu.php'; ?>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php if ($id == ''){ echo 'Add Product'; } else{echo 'Update Product';} ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Home</a>
        <a href="#;">
        <li class="active"> Add Product</li>
        </a>
        </li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if ($id == '') echo 'Add Product'; else echo 'Update Product'; ?>
          </h3>
          <?php if (isset($msg)) echo $msg; ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <?php if (!isset($_GET['id']) && !isset($_GET['edit'])) { ?>
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                <form role="form" name="stform" id="stform" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>Select Tab</label>
                    <select class="form-control" name="tab" onChange="fetch_select(this.value);" required>
                    <option value="">Select Tab</option>
                      <?php 
                      
            $department = $conn->prepare("SELECT * FROM `department` WHERE visible=1 and id = :catid");
            $department->bindValue(':catid', $vendor_category, PDO::PARAM_INT);
            $department->execute();
            while($departmentRow = $department->fetch(PDO::FETCH_ASSOC)){
						
					  ?>
                      <option value="<?php echo $departmentRow['id'];  ?>"><?php echo $departmentRow['name_en'].'('.$departmentRow['name_ar'].')';  ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Select Category</label>
                    <select class="form-control" name="cat" id="category" onChange="fetch_selectSub(this.value);">
                        <option value="">Select Category</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Select SubCategory</label>
                    <select class="form-control" name="sub_cat" id="sub_cat">
                      
                     <option value="">Select Sub Category</option> 
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Product Name (English)</label>
                    <input class="form-control" placeholder="Product Name (English)"  name="product_name_en" required>
                  </div>
                  <div class="form-group">
                    <label>Product Name (Arabic)</label>
                    <input class="form-control" placeholder="Product Name (Arabic)"  name="product_name_ar" dir="rtl" required>
                  </div>
                  <div class="form-group">
                    <label>Product Short Description (English)</label>
                    <textarea class="form-control edit1" placeholder="Product Short Description (English)"  name="short_desc_en"></textarea>
                      <input name="imageEditor" type="file" id="upload1" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Short Description (Arabic)</label>
                    <textarea class="form-control edit2" placeholder="Product Short Description (Arabic)"  name="short_desc_ar" dir="rtl" ></textarea>
                      <input name="imageEditor" type="file" id="upload2" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Description (English)</label><br>
                    <textarea class="form-control edit3" placeholder="Product Description (English)"  name="long_desc_en"></textarea>
                      <input name="imageEditor" type="file" id="upload3" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Description (Arabic)</label><br>
                    <textarea class="form-control edit4" placeholder="Product Description (Arabic)"  name="long_desc_ar" dir="rtl" row="6" ></textarea>
                      <input name="imageEditor" type="file" id="upload4" class="hidden" onChange="" style="display:none;">
                   
                  </div>
                   
                  <div class="form-group">
                    <label>Product Specification (English)</label><br>
                    <textarea class="form-control edit5" placeholder="Product Specification (English)"  name="product_spec_en"></textarea>
                      <input name="imageEditor" type="file" id="upload5" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Specification (Arabic)</label><br>
                    <textarea class="form-control edit6" placeholder="Product Specification (Arabic)"  name="product_spec_ar" dir="rtl" row="6" ></textarea>
                      <input name="imageEditor" type="file" id="upload6" class="hidden" onChange="" style="display:none;">
                  </div>
                  
                  <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="any" class="form-control" placeholder="Price"  name="price" required>
                  </div>
                  <div class="form-group">
                    <label>Discount Type(Optional)</label>
                    <select class="form-control" name="discount_type"  onchange="discountType(this.value);" required>
                        <option value="">Select Discount Type</option>
                        <option value="0">No Discount</option>
                        <option value="1">IN PERCENT(Discount in %)</option>
                        <option value="2">IN PRICE(For Manual Discount)</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Discount</label>
                    <input type="number" step="any" class="form-control" placeholder="Enter Discount"  id="discount"  name="discount">
                  </div>
                  <div class="form-group">
                    <label>Free Shipping By Quantity (Optional)</label>
                    <input type="number" class="form-control" placeholder="Enter Quantity"  name="free_shipping_qty">
                  </div>
                  <div class="form-group">
                    <label>Free Shipping By Amount (Optional)</label>
                    <input type="number" step="any" class="form-control" placeholder="Enter Amount"  name="free_shipping_amount">
                  </div>
                  <div class="form-group">
                    <label>Shipping Charge(optional)</label>
                    <input type="number" class="form-control" placeholder="Shipping Charge(If Any)"  name="ship_charge">
                  </div>
                                    
                  <div class="form-group">
                    <label>Size(If any)</label><br>
                   <?php 
													
						$sizeee = $conn->query("SELECT * FROM `products_size` WHERE status=1 ORDER BY `id` asc");
						$sizeee->execute();
						while($sizeee2 = $sizeee->fetch(PDO::FETCH_ASSOC)){
						
					  ?> 
                   <input type="checkbox" name="sizeof[]" value="<?=$sizeee2['id']; ?>"> <?=strtoupper($sizeee2['size']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                   
                   <?php } ?>
                  </div><hr>
                  
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3 text-center"> <img id="uploadPreview" width="150" height="150" ></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="exampleInputPassword1">Product Cover Image (Please Upload 5.20inch x 5.20inch images)</label>
                        <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" required />
                      </div>
                    </div>
                  </div>
                  
                  
                  <div class="form-group field_wrapper" id="p_scents"><hr>
                    <label>Upload Images According to Color</label> &nbsp;<input type="button" value="Add More" onClick="addrow('row');" class="btn btn-success add_button"><hr><br>
                  <div class="row">
                     
                  
                  
                  </div> 
                  </div>
                  
                  <input type="submit" value="Submit" class="btn btn-primary" name="submit">
                </form>
              </div>
            </div>
            <?php } else { ?>
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                <form role="form" name="stform" id="stform" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>Select Tab</label>
                    <select class="form-control" name="tab" onChange="fetch_select(this.value);"  required>
                      <option value="">Select Tab</option>
                      <?php 
													
						$getCat2 = $conn->query("SELECT * FROM `department` WHERE visible=1 ORDER BY `id` asc");
						while($nBlog2 = $getCat2->fetch(PDO::FETCH_ASSOC)){
						
					  ?>
                      <option <?php if($editval['tab_id']==$nBlog2['id']){ echo 'selected'; } ?> value="<?php echo $nBlog2['id'];  ?>"><?php echo $nBlog2['name_en'].'('.$nBlog2['name_ar'].')';  ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Select Category</label>
                    <select class="form-control" name="cat" id="category" onChange="fetch_selectSub(this.value);">
                      <option value="">Select Sub Department</option>
                      <?php 
													
						$sub_department = $conn->query("SELECT * FROM `sub_department` WHERE `did` = '".$editval['cat_id']."' and visible=1  ORDER BY `id` asc");
						while($sub_depar = $sub_department->fetch(PDO::FETCH_ASSOC)){
						
						?>
                      <option <?php if($editval['cat_id']==$sub_depar['id']){ echo 'selected'; } ?> value="<?php echo $sub_depar['id'];  ?>"><?php echo $sub_depar['name_en'].'('.$sub_depar['name_ar'].')';  ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Select Sub Category</label>
                    <select class="form-control" name="sub_cat" id="sub_cat">
                      <option value="">Select Sub Category</option>
                      <?php 
													
						$sub_cat = $conn->query("SELECT * FROM `sub_cat` WHERE  `did` = '".$editval['cat_id']."' AND  `sid` = '".$editval['subcat_id']."' AND visible=1  ORDER BY `id` asc");
						while($sub_catRow = $sub_cat->fetch(PDO::FETCH_ASSOC)){
						
						?>
                      <option <?php if($editval['subcat_id']==$sub_catRow['id']){ echo 'selected'; } ?> value="<?php echo $sub_catRow['id'];  ?>"><?php echo $sub_catRow['name_en'].'('.$sub_catRow['name_ar'].')';  ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Product Name (English)</label>
                    <input type="text" class="form-control" placeholder="Product Name (English)"  value="<?php echo $editval['product_name_en'];  ?>"  name="product_name_en" required>
                  </div>
                  <div class="form-group">
                    <label>Product Name (Arabic)</label>
                    <input type="text" class="form-control" placeholder="Product Name (Arabic)"  value="<?php echo $editval['product_name_ar'];  ?>"  name="product_name_ar" dir="rtl" required>
                  </div>
                  <div class="form-group">
                    <label>Product Short Description (English)</label>
                    <textarea class="form-control edit1" placeholder="Product Short Description (English)"  name="short_desc_en"><?php echo $editval['short_desc_en'];  ?></textarea>
                      <input name="imageEditor" type="file" id="upload1" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Short Description (Arabic)</label>
                    <textarea class="form-control edit2" placeholder="Product Short Description (Arabic)"  name="short_desc_ar" dir="rtl" ><?php echo $editval['short_desc_ar'];  ?></textarea>
                      <input name="imageEditor" type="file" id="upload2" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Long Description (English)</label><br>
                    <textarea class="form-control edit3" placeholder="Product Description (English)"  name="long_desc_en"><?php echo $editval['long_desc_en'];  ?></textarea>
                      <input name="imageEditor" type="file" id="upload3" class="hidden" onChange="" style="display:none;">
                   
                  </div>
                  <div class="form-group">
                    <label>Product Long Description (Arabic)</label><br>
                    <textarea class="form-control edit4" placeholder="Product Description (Arabic)"  name="long_desc_ar" dir="rtl" ><?php echo $editval['long_desc_ar'];  ?></textarea>
                      <input name="imageEditor" type="file" id="upload4" class="hidden" onChange="" style="display:none;">
                    </div>
                   <div class="form-group">
                    <label>Product Specification (English)</label><br>
                    <textarea class="form-control edit5" placeholder="Product Specification (English)"  name="product_spec_en"><?php echo $editval['product_spec_en'];  ?></textarea>
                      <input name="imageEditor" type="file" id="upload5" class="hidden" onChange="" style="display:none;">
                  </div>
                  <div class="form-group">
                    <label>Product Specification (Arabic)</label><br>
                    <textarea class="form-control edit6" placeholder="Product Specification (Arabic)"  name="product_spec_ar" dir="rtl" row="6" ><?php echo $editval['product_spec_ar'];  ?></textarea>
                      <input name="imageEditor" type="file" id="upload6" class="hidden" onChange="" style="display:none;">
                  </div>
                    
                  <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="any" class="form-control" placeholder="Price"  name="price"  value="<?php if($editval['discount']!=''){ echo $editval['old_price']; }else{ echo $editval['price']; }  ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label>Discount Type</label>
                    <select class="form-control" name="discount_type" onChange="discountType(this.value);" required>
                        <option value="">Select Discount Type</option>
                        <option <?php if($editval['discount_type']==0){ echo 'selected'; } ?> value="0">No Discount</option>
                        <option <?php if($editval['discount_type']==1){ echo 'selected'; } ?> value="1">IN PERCENT(Discount in %)</option>
                        <option <?php if($editval['discount_type']==2){ echo 'selected'; } ?> value="2">IN PRICE(For Manual Discount)</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Discount</label>
                    <input type="number" step="any" class="form-control" placeholder="Enter Discount"  id="discount"  name="discount" value="<?php echo $editval['discount'];  ?>">
                  </div>
                  <div class="form-group">
                    <label>Free Shipping By Quantity (Optional)</label>
                    <input type="number" class="form-control" placeholder="Enter Quantity"  name="free_shipping_qty" value="<?php echo $editval['free_shipping_qty'];  ?>">
                  </div>
                  <div class="form-group">
                    <label>Free Shipping By Amount (Optional)</label>
                    <input type="number" step="any" class="form-control" placeholder="Enter Amount"  name="free_shipping_amount" value="<?php echo $editval['free_shipping_amount'];  ?>">
                  </div>
                  <div class="form-group">
                    <label>Shipping Charge(optional)</label>
                    <input type="number" class="form-control" placeholder="Shipping Charge(If Any)"  name="ship_charge" value="<?php echo $editval['ship_charge'];  ?>">
                  </div>
                  
                  <div class="form-group">
                    <label>Size(If any)</label><br>
                   <?php 
				   $mypSize = $editval['p_size'];
					if($mypSize != '')
					{
						$mypSize2 = $mypSize;
					}
					else{
						$mypSize2 = 0;
					}
													
						$sizeee = $conn->prepare("SELECT * FROM `products_size` WHERE status=1 and id IN (".$mypSize2.") ORDER BY `id` asc");
						$sizeee->execute();
						while($sizeee2 = $sizeee->fetch(PDO::FETCH_ASSOC)){
					?> 
                   <input type="checkbox" name="sizeof[]" value="<?=$sizeee2['id']; ?>" checked> <?=strtoupper($sizeee2['size']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                   
                   <?php } ?>
                   <?php 
													
						$sizeee22 = $conn->prepare("SELECT * FROM `products_size` WHERE status=1 and id NOT IN (".$mypSize2.") ORDER BY `id` asc");
						$sizeee22->execute();
						while($sizeee3 = $sizeee22->fetch(PDO::FETCH_ASSOC)){
					?> 
                   <input type="checkbox" name="sizeof[]" value="<?=$sizeee3['id']; ?>"> <?=strtoupper($sizeee3['size']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                   
                   <?php } ?>
                  </div><hr>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3 text-center"> <img src="../adminuploads/product/<?php echo $editval['image']; ?>" id="uploadPreview" width="150" height="150" ></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="exampleInputPassword1">Product Cover Image  (Please Upload 5.20inch x 5.20inch images)</label>
                        <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" />
                      </div>
                    </div>
                  </div>
                  
                   <div class="form-group field_wrapper" id="p_scents"><hr>
                    <label>Upload Images According to Color</label> &nbsp;<input type="button" value="Add More" onClick="addrow('row');" class="btn btn-success add_button"><hr><br>
                  <div class="row">
                      
                  
                  
                  </div> 
                  </div>
                  <hr>
                  
                   <div class="table table-responsive">
                                              <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                  <tr>
                                                    <th>S.No</th>
                                                    <th>Color Name</th>
                                                    <th>Stock</th>
                                                    <th>Images</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php 
                                                    $i=1;
													$allImages2 = $conn->query("SELECT id,image,stock,color_id FROM product_images WHERE product_id = '".$editval['id']."' group by color_id ORDER BY `id` asc");
                                					$allImages2->execute();
                                					while($imageRow = $allImages2->fetch(PDO::FETCH_ASSOC)){
                                					    
                                					$mycolor2 = $conn->query("SELECT color FROM products_color WHERE id = '".$imageRow['color_id']."'");
                                					$mycolor2->execute();
                                					$mycolor = $mycolor2->fetch(PDO::FETCH_ASSOC);
                                                  ?>
                                                  <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php if($mycolor['color']!=''){ echo $mycolor['color']; }else{ echo 'Default Color'; } ?></td>
                                                    <td contenteditable="true" onBlur="saveToDatabase(this,'<?= $imageRow['color_id']; ?>','<?=$editval["id"]; ?>')" onClick="showEdit(this);"><?= $imageRow['stock']; ?></td>
                                                    <td>
                                                    <?php 
													$onlyImg = $conn->query("SELECT image,id FROM product_images WHERE product_id = '".$editval['id']."' and color_id = '".$imageRow['color_id']."'");
                                					$onlyImg->execute();
                                					while($onlyImg2 = $onlyImg->fetch(PDO::FETCH_ASSOC)){
                                                  ?> 
                                                 <b id="<?php echo $onlyImg2['id']; ?>"> <img src="../adminuploads/product/<?php echo $onlyImg2['image']; ?>" width="100" height="100" style="border: 2px solid #000;"> - <a onClick=myRemove(<?php echo $onlyImg2['id']; ?>);><i class="fa fa-trash" aria-hidden="true"></i> </a></b>
                                                  <?php } ?>
                                                        
                                                    </td>
                                                    
                                                  </tr>
                                                  <?php } ?>
                                                </tbody>
                                              </table>
                                          </div>    
                                            
                  
                  
                  <input type="hidden" name="oldimg" value="<?php echo $editval['image']; ?>" />
                  <input  type="submit" value="Update" class="btn btn-primary" name="update">
                </form>
              </div>
            </div>
            <?php } ?>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs"> </div>
    <!-- Default to the left -->
    <strong>
    <?=$copyright; ?>
    </strong> </footer>
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<script src="dist/js/demo.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="../tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>
$(document).ready(function() {
  tinymce.init({
    selector: ".edit1",
    paste_data_images: true,
    height: 250,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload1').trigger('click');
        $('#upload1').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
  tinymce.init({
    selector: ".edit2",
    paste_data_images: true,
    height: 250,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload2').trigger('click');
        $('#upload2').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
  tinymce.init({
    selector: ".edit3",
    paste_data_images: true,
    height: 250,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload3').trigger('click');
        $('#upload3').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
  tinymce.init({
    selector: ".edit4",
    paste_data_images: true,
    height: 250,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload4').trigger('click');
        $('#upload4').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
  tinymce.init({
    selector: ".edit5",
    paste_data_images: true,
    height: 250,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload5').trigger('click');
        $('#upload5').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
  tinymce.init({
    selector: ".edit6",
    paste_data_images: true,
    height: 250,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload6').trigger('click');
        $('#upload6').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
});    
</script>

<script>
discountType(<?=$editval['discount_type']; ?>);
 function discountType(val){
	var dis_type = val;
	//alert(dis_type);
	if(dis_type==0){
		$('#discount').val('');
		$('#discount').attr('readonly', true);
		
	}else{
		$('#discount').attr('readonly', false);
	
	}
}
$(function () {

	//Date picker
	$('#datepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true
	});
});
function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		} 
		
		function saveToDatabase(editableObj,column,id) {
			$(editableObj).css("background","#FFF url(../images/loader.gif) no-repeat right");
			var mydata = 'column='+column+'&editval='+editableObj.innerHTML+'&prod_id='+id;
			//alert(mydata);
			$.ajax({
				url: "../admin/image_delete.php",
				type: "POST",
				data:mydata,
				success: function(data){
				    alert(data);
				window.location.reload(); 
				
					
				}        
		   });
		}

</script>
<script type="text/javascript">
		
function fetch_select(val)
{
    
	 $.ajax({
	 type: 'post',
	 url: 'getvalue.php',
	 data: {tab:val},
	 success: function (response) {
	     //alert(response);
	   document.getElementById('category').innerHTML=response; 
	 }
	 });
}


function fetch_selectSub(val)
{
	 $.ajax({
	 type: 'post',
	 url: 'getvalue.php',
	 data: {cat:val},
	 success: function (response) {
	   document.getElementById('sub_cat').innerHTML=response; 
	 }
	 });
}

</script>

<script type="text/javascript">
function myRemove(val){
       // alert(val);
        var id2 = val;

        if(confirm('Are you sure to remove this record ?'))
        {
            $.ajax({
               type: "POST",
               url: "../admin/image_delete.php",
               data: {'id2':id2},
               cache: true,
               error: function() {
                  alert('Something is wrong');
               },
               success: function(data) {
                  // alert(data);
                    $("#"+id2).remove();
                    alert("Record removed successfully");  
               }
            });
        }
    }
</script>
<script type="text/javascript">
var i =  1;
function addrow()
 {
 
    var scntDiv = document.getElementById('p_scents');

	 if( i<=9 )
	 {
	    var j = i+1;
		
	
	$('<div class="row" id='+ j +' style="margin-top: 14px;"><div class="col-md-3"><label for="exampleInputPassword1">Product Color</label><select  class="form-control" name="colorof'+ j +'" required><option value="0">Default Color</option><?php $colorrr1 = $conn->query("SELECT * FROM `products_color` WHERE status=1 ORDER BY `id` asc");$colorrr1->execute();while($colorrr3 = $colorrr1->fetch(PDO::FETCH_ASSOC)){ ?><option value="<?=$colorrr3['id']; ?>"><?=strtoupper($colorrr3['color']); ?></option><?php } ?></select></div><div class="col-md-3"><label for="exampleInputPassword1">Stock</label><input type="number" class="form-control" name="stock'+ j +'" required></div><div class="col-md-3"><label for="exampleInputPassword1">Product Images (Select Multiple)</label><input type="file" class="form-control" name="photo'+ j +'[]" multiple required></div><div class="col-md-3"><label for="exampleInputPassword1">&nbsp;</label><br><button class="btn btn-danger" id="remScnt" onclick=\'removerow('+j+')\'>Delete</button></div></div>').appendTo(scntDiv);
                      
		
		i++;
	}
       return false;

 }


function removerow(trName) {

  var d = document.getElementById('p_scents');

  var oldtr = document.getElementById(trName);

  d.removeChild(oldtr);

  i--;
}

</script>

</body>
</html>
