<?php

session_start();
error_reporting(0);
include 'functions/db.class.php';

if(!isset($_SESSION['USER_ID']) && $_SESSION['USER_ID']=='')
{
  echo "<script>window.location.href='index.php'</script>";
}

$id = base64_decode($_GET['id']); // -- Get id -- //
$edit = $_GET['edit'];

// ----- Get Result ---- //

$getResult = $conn->prepare("SELECT * FROM `coupan_code` WHERE `id` = :id");
$getResult->bindParam(':id', $id, PDO::PARAM_INT);
$getResult->execute();
$editval = $getResult->fetch(PDO::FETCH_ASSOC);

if(isset($_REQUEST['submit'])){
	
	$code = $_POST['code'];
	$valid_from = trim($_POST['valid_from']);
	$valid_to = $_POST['valid_to'];
	$coupan_type = $_POST['coupan_type'];
	$discount_type = trim($_POST['discount_type']);
	$discount_value = $_POST['discount_value'];
	$cart_type = trim($_POST['cart_type']);
	$cart_value = $_POST['cart_value'];

	$sql = $conn->prepare("INSERT INTO `coupan_code`(`uid`, `code`, `valid_from`, `valid_to`, `coupan_type`, `discount_type`, `discount_value`, `cart_type`, `cart_value`, `created_at`) VALUES (:uid, :code, :valid_from, :valid_to, :coupan_type, :discount_type, :discount_value, :cart_type, :cart_value, :created_at)");
	
	$sql->bindParam(':uid', $_SESSION['USER_ID'], PDO::PARAM_INT);
	$sql->bindParam(':code', $code, PDO::PARAM_STR);
	$sql->bindParam(':valid_from', $valid_from, PDO::PARAM_STR);
	$sql->bindParam(':valid_to', $valid_to, PDO::PARAM_STR);
	$sql->bindParam(':coupan_type', $coupan_type, PDO::PARAM_INT);
	$sql->bindParam(':discount_type', $discount_type, PDO::PARAM_STR);
	$sql->bindParam(':discount_value', $discount_value, PDO::PARAM_STR);
	$sql->bindParam(':cart_type', $cart_type, PDO::PARAM_STR);
	$sql->bindParam(':cart_value', $cart_value, PDO::PARAM_STR);
	$sql->bindParam(':created_at', $globaldate, PDO::PARAM_STR);
	$sql->execute();
		
	if($sql == true){

        $msg = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success</h4>
                Data added successfully
                </div>';
    } else {
        $msg = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error</h4>
                Error !! Please try again
                </div>';
    }
}

if(isset($_REQUEST['update'])){
	
	$code = $_POST['code'];
	$valid_from = trim($_POST['valid_from']);
	$valid_to = $_POST['valid_to'];
	$coupan_type = $_POST['coupan_type'];
	$discount_type = trim($_POST['discount_type']);
	$discount_value = $_POST['discount_value'];
	$cart_type = trim($_POST['cart_type']);
	$cart_value = $_POST['cart_value'];
	
	$sql = $conn->prepare("UPDATE `coupan_code` SET `code` = '$code', `valid_from` = '$valid_from', `valid_to` = '$valid_to', `coupan_type` = '$coupan_type', `discount_type` = '$discount_type', `discount_value` = '$discount_value', `cart_type` = '$cart_type', `cart_value` = '$cart_value' WHERE `id` = '$id'");
	$sql->execute();
		
	if($sql == true){
		
			echo '<script>alert("Data has been Updated Successfully !!")</script>';
			echo '<script>window.location.href="view-coupan.php"</script>';
		
	}else{
		
			echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';
		
	}
	
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>
<?=$WebsiteTitle; ?>
| Coupan Code</title>
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
<link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
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
        <?php if ($id == ''){ echo 'Add Coupan Code'; } else{echo 'Update Coupan Code';} ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Home</a>
        <a href="#;">
        <li class="active"> Add Coupan Code</li>
        </a>
        </li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if ($id == '') echo 'Add Coupan Code'; else echo 'Update Coupan Code'; ?>
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
                    <label>Coupan Code </label>
                    <input type="text" class="form-control" placeholder="Coupan Code"  name="code" required>
                  </div>
                  <div class="form-group">
                    <label>Coupan Validity From </label>
                    <input type="date" class="form-control"  name="valid_from" required>
                  </div>
                  <div class="form-group">
                    <label>Coupan valid till </label>
                    <input type="date" class="form-control"  name="valid_to" required>
                  </div>
                  <div class="form-group">
                    <label>Coupan Type</label>
                    <select class="form-control"  name="coupan_type" id="coupan_type" required>
                        <option value="">Select</option>
                        <option value="1">One Time Use</option>
                        <option value="2">One Time Per User</option>
                        <option value="3">For Unlimited Use</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Discount Type</label>
                    <select class="form-control"  name="discount_type" id="discount_type" required>
                        <option value="">Select</option>
                        <option value="1">By Price</option>
                        <option value="2">By Percent</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Discount value</label>
                    <input type="number" step="any" class="form-control" id="discount_value"  placeholder="Discount value" name="discount_value" required>
                  </div>
                  <div class="form-group">
                    <label>Cart Type</label>
                    <select class="form-control"  name="cart_type" id="cart_type" required>
                        <option value="">Select</option>
                        <option value="1">By Total Quantity</option>
                        <option value="2">By Total Amount</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Cart value</label>
                    <input type="number" step="any" class="form-control" id="cart_value"  placeholder="Cart value" name="cart_value" required>
                  </div>
                  <input  type="submit" value="Submit" class="btn btn-primary" name="submit">
                </form>
              </div>
            </div>
	
            <?php } else { ?>
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                <form role="form" name="stform" id="stform" action="" method="post" enctype="multipart/form-data">
                  
                  <div class="form-group">
                    <label>Coupan Code </label>
                    <input type="text" class="form-control" placeholder="Coupan Code"  name="code" value="<?=$editval['code']; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Coupan Validity From </label>
                    <input type="date" class="form-control"  name="valid_from" value="<?=$editval['valid_from']; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Coupan valid till </label>
                    <input type="date" class="form-control"  name="valid_to" value="<?=$editval['valid_to']; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Coupan Type</label>
                    <select class="form-control"  name="coupan_type" id="coupan_type" required>
                        <option value="">Select</option>
                        <option <?php if($editval['coupan_type']==1){ echo 'selected'; } ?> value="1">One Time Use</option>
                        <option <?php if($editval['coupan_type']==2){ echo 'selected'; } ?> value="2">One Time Per User</option>
                        <option <?php if($editval['coupan_type']==3){ echo 'selected'; } ?> value="3">For Unlimited Use</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Discount Type</label>
                    <select class="form-control"  name="discount_type" id="discount_type" required>
                        <option value="">Select</option>
                        <option <?php if($editval['discount_type']==1){ echo 'selected'; } ?> value="1">By Price</option>
                        <option <?php if($editval['discount_type']==2){ echo 'selected'; } ?> value="2">By Percent</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Discount value</label>
                    <input type="number" step="any" class="form-control" id="discount_value"  placeholder="Discount value" name="discount_value" value="<?=$editval['discount_value']; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Cart Type</label>
                    <select class="form-control"  name="cart_type" id="cart_type" required>
                        <option value="">Select</option>
                        <option <?php if($editval['cart_type']==1){ echo 'selected'; } ?> value="1">By Total Quantity</option>
                        <option <?php if($editval['cart_type']==2){ echo 'selected'; } ?> value="2">By Total Amount</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Cart value</label>
                    <input type="number" step="any" class="form-control" id="cart_value"  placeholder="Cart value" name="cart_value" value="<?=$editval['cart_value']; ?>" required>
                  </div>
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
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<script src="dist/js/demo.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
<script>

$(function () {

	//Date picker
	$('#datepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true
	});
});
$(document).ready(function(){ 
  $('#discount_type').change(function () {
        var k = $(this).val();
        if (k == 1) {
            $("#discount_value").attr("placeholder", "Discount value in QAR").placeholder();
        }
        else if (k == 2) {
            $("#discount_value").attr("placeholder", "Discount value in %").placeholder();
        }
        else{
            $("#discount_value").attr("placeholder", "Discount value").placeholder();
        }
    });
     $('#cart_type').change(function () {
        var k = $(this).val();
        if (k == 1) {
            $("#cart_value").attr("placeholder", "Cart Total Item Quantity").placeholder();
        }
        else if (k == 2) {
            $("#cart_value").attr("placeholder", "Cart Total Amount").placeholder();
        }
        else{
            $("#cart_value").attr("placeholder", "Cart value").placeholder();
        }
    });
});
</script>
</body>
</html>
