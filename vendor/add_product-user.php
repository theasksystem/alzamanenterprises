<?php
session_start();
error_reporting(0);
include 'functions/db.class.php';

if($_SESSION['VENDOR_ID']=='')
{
  echo "<script>window.location.href='../vendors'</script>";
}

$id = base64_decode($_GET['id']); // -- Get id -- //
$edit = $_GET['edit'];

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
$old_price = $_POST['old_price'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$insert_ip = $_SERVER['REMOTE_ADDR'];

$img = $_FILES['image']['name'];
$imgtmp = $_FILES['image']['tmp_name'];

$img ? $im = time().'-'.$img : $im = '';

if($img!=''){
	move_uploaded_file($imgtmp,'../adminuploads/product/'.$im);
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

		$sql = $conn->prepare("INSERT INTO `products`(`user_id`, `tab_id`, `cat_id`, `subcat_id`, `product_name_en`, `product_name_ar`, `short_desc_en`, `short_desc_ar`, `long_desc_en`, `long_desc_ar`, `image`, `old_price`, `price`, `quantity`, `slug`, `insert_ip`, `created_at`) VALUES (:user_id, :tab_id, :cat_id, :subcat_id, :product_name_en, :product_name_ar, :short_desc_en, :short_desc_ar, :long_desc_en, :long_desc_ar, :image, :old_price, :price, :quantity, :slug, :insert_ip, :created_at)");
		
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
		$sql->bindParam(':image', $im, PDO::PARAM_STR);
		$sql->bindParam(':old_price', $old_price, PDO::PARAM_STR);
		$sql->bindParam(':price', $price, PDO::PARAM_STR);
		$sql->bindParam(':quantity', $quantity, PDO::PARAM_INT);
		$sql->bindParam(':slug', $slug, PDO::PARAM_STR);
		$sql->bindParam(':insert_ip', $insert_ip, PDO::PARAM_STR);
		$sql->bindParam(':created_at', $globaldate, PDO::PARAM_STR);
		$sql->execute();
		
		$last_id = $conn->lastInsertId(); // get last query insert id 
		
		// Count total files
  		$countfiles = count($_FILES['images']['name']);
	
		for($i = 0; $i<$countfiles; $i++){
		
		$filename = $last_id.time()."-".$_FILES['images']['name'][$i];
  		$dest = '../adminuploads/product/'.$filename;
  		$filetmp = $_FILES['images']['tmp_name'][$i];
		move_uploaded_file($filetmp,$dest); // Upload file
		
		$insQuery = $conn->exec("INSERT INTO `product_images`(`product_id`, `image`, `created_at`) VALUES ('$last_id', '$filename', now())");
		
		}
		
		if($sql == true){
		
			echo '<script>alert("Product has been Added Successfully !!")</script>';
			echo '<script>window.location.href="add_product-user.php"</script>';
		
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
$old_price = $_POST['old_price'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$featured = $_POST['featured'];
$update_ip = $_SERVER['REMOTE_ADDR'];
		
$oldimg = $_POST['oldimg'];

$img = $_FILES['image']['name'];
$imgtmp = $_FILES['image']['tmp_name'];

$img ? $im = time().'-'.$img : $im = $oldimg;

if($img!=''){
	unlink('../adminuploads/product/'.$oldimg);
	move_uploaded_file($imgtmp,'../adminuploads/product/'.$im);
}

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
		
		$sql = $conn->prepare("UPDATE `products` SET `user_id` = :user_id, `tab_id` = :tab_id, `cat_id` = :cat_id, `subcat_id` = :subcat_id, `product_name_en` = :product_name_en, `product_name_ar` = :product_name_ar, `short_desc_en` = :short_desc_en, `short_desc_ar` = :short_desc_ar, `long_desc_en` = :long_desc_en, `long_desc_ar` = :long_desc_ar, `image` = :image, `old_price` = :old_price, `price` = :price, `quantity` = :quantity, `featured` = :featured, `slug` = :slug, `update_ip` = :update_ip, `updated_at` = :updated_at WHERE `id` = :id");
		
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
		$sql->bindParam(':image', $im, PDO::PARAM_STR);
		$sql->bindParam(':old_price', $old_price, PDO::PARAM_STR);
		$sql->bindParam(':price', $price, PDO::PARAM_STR);
		$sql->bindParam(':quantity', $quantity, PDO::PARAM_INT);
		$sql->bindParam(':featured', $featured, PDO::PARAM_INT);
		$sql->bindParam(':slug', $slug, PDO::PARAM_STR);
		$sql->bindParam(':update_ip', $update_ip, PDO::PARAM_STR);
		$sql->bindParam(':updated_at', $globaldate, PDO::PARAM_STR);
		$sql->execute();
		
		// Count total files
  		$countfiles = count($_FILES['images']['name']);
	
		for($i = 0; $i<$countfiles; $i++){
		
		$filename = $id.time()."-".$_FILES['images']['name'][$i];
  		$dest = '../adminuploads/product/'.$filename;
  		$filetmp = $_FILES['images']['tmp_name'][$i];
		move_uploaded_file($filetmp,$dest); // Upload file
		
		$insQuery = $conn->exec("INSERT INTO `product_images`(`product_id`, `image`, `created_at`) VALUES ('$id', '$filename', now())");
		
		}
		
		if($sql == true){
		
			echo '<script>alert("Product has been Updated Successfully !!")</script>';
		if($_SESSION['USER_TYPE']== 'Vendor'){	
			echo '<script>window.location.href="product-user.php"</script>';
		}else{
			echo '<script>window.location.href="product.php"</script>';
		}
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
    <a href="home.php" class="logo">
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
													
						$department = $conn->query("SELECT * FROM `department` WHERE visible=1 ORDER BY `id` asc");
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
                    <textarea class="form-control" placeholder="Product Short Description (English)"  name="short_desc_en"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Product Short Description (Arabic)</label>
                    <textarea class="form-control" placeholder="Product Short Description (Arabic)"  name="short_desc_ar" dir="rtl" ></textarea>
                  </div>
                  <div class="form-group">
                    <label>Product Description (English)</label><br>
                    <textarea class="form-control" placeholder="Product Description (English)"  name="long_desc_en"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Product Description (Arabic)</label><br>
                    <textarea class="form-control" placeholder="Product Description (Arabic)"  name="long_desc_ar" dir="rtl" row="6" ></textarea>
                   
                  </div>
                  
                  <div class="form-group">
                    <label>Price</label>
                    <input type="text" class="form-control" placeholder="Price"  name="price" required>
                  </div>
                  <div class="form-group">
                    <label>Discount Percent(%)</label>
                    <input type="number" step="any" class="form-control" placeholder="Enter Percent Like 15"  name="old_price">
                  </div>
                  <div class="form-group">
                    <label>Stock</label>
                    <input type="number" class="form-control" placeholder="Stock"  name="quantity">
                  </div>
                  
                  <?php if($_SESSION['VENDOR_ID']==1){ ?>
                  <div class="form-group">
                    <label>Featured</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="featured" value="1">
                  </div>
                  <?php } ?>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3 text-center"> <img id="uploadPreview" width="150" height="150" ></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="exampleInputPassword1">Product Cover Image</label>
                        <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" required />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="exampleInputPassword1">Product Images (Select Multiple)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple/>
                      </div>
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
                    <textarea class="form-control" placeholder="Product Short Description (English)"  name="short_desc_en"><?php echo $editval['short_desc_en'];  ?></textarea>
                  </div>
                  <div class="form-group">
                    <label>Product Short Description (Arabic)</label>
                    <textarea class="form-control" placeholder="Product Short Description (Arabic)"  name="short_desc_ar" dir="rtl" ><?php echo $editval['short_desc_ar'];  ?></textarea>
                  </div>
                  <div class="form-group">
                    <label>Product Long Description (English)</label><br>
                    <textarea class="form-control" placeholder="Product Description (English)"  name="long_desc_en"><?php echo $editval['long_desc_en'];  ?></textarea>
                   
                  </div>
                  <div class="form-group">
                    <label>Product Long Description (Arabic)</label><br>
                    <textarea class="form-control" placeholder="Product Description (Arabic)"  name="long_desc_ar" dir="rtl" ><?php echo $editval['long_desc_ar'];  ?></textarea>
                    </div>
                    
                  <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="any" class="form-control" placeholder="Price"  name="price"  value="<?php echo $editval['price'];  ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Discount Percent(%)</label>
                    <input type="number" step="any" class="form-control" placeholder="Enter Percent Like 15"  name="old_price"  value="<?php echo $editval['old_price'];  ?>">
                  </div>
                  <div class="form-group">
                    <label>Stock</label>
                    <input type="number" step="any" class="form-control" placeholder="Stock"  name="quantity"  value="<?php echo $editval['quantity'];  ?>">
                  </div>
                  
                  
                  <div class="form-group" <?php if($_SESSION['VENDOR_ID']!=1){ ?> style="display:none;"  <?php } ?>>
                    <label>Featured</label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="featured" <?php if($editval['featured']==1){ echo 'checked'; } ?> value="1">
                  </div>
                  
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3 text-center"> <img src="../adminuploads/product/<?php echo $editval['image']; ?>" id="uploadPreview" width="150" height="150" ></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="exampleInputPassword1">Product Cover Image</label>
                        <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <label for="exampleInputPassword1">Add More Images (Select Multiple)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple/>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                  <?php 
													
					$allImages = $conn->query("SELECT image FROM `product_images` WHERE product_id = '".$editval['id']."' ORDER BY `id` asc");
					$allImages->execute();
					while($getImagesRow = $allImages->fetch(PDO::FETCH_ASSOC)){
						
				  ?>
                  
                      <div class="col-md-3 text-center"> 
                      <img src="../adminuploads/product/<?php echo $getImagesRow['image']; ?>" width="150" height="150" style="border: 2px solid #000;"></div>
                    
                  <?php } ?>
                  </div>
                  </div>
                  <!--<div class="form-group">
                    <div class="row">
                      <div class="col-md-3">
                        <a href="update-images.php?pid=<?php echo base64_encode($editval['id']);  ?>" target="_blank">Update Images</a>
                      </div>
                    </div>
                  </div>-->
                  
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

<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>

<script>

$(function () {

	//Date picker
	$('#datepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true
	});
});


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

</body>
</html>
