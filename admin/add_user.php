<?php
error_reporting(0);
session_start();
include'functions/db.class.php';

if(!isset($_SESSION['USER_ID']) && $_SESSION['USER_ID']=='')
{
  echo "<script>window.location.href='index.php'</script>";
}

$id = base64_decode($_GET['id']); // -- Get id -- //
$edit = $_GET['edit'];

// ----- Get Result ---- //
$getResult = $conn->prepare("SELECT * FROM `tbl_admin` WHERE `id` = :id");
$getResult->bindParam(':id', $id, PDO::PARAM_INT);
$getResult->execute();
$editval = $getResult->fetch(PDO::FETCH_ASSOC);

 
 	// ------- Insert Query ------- //
 
 	if(isset($_REQUEST['submit'])){
	
		$type = trim($_POST['type']);
		$username = trim($_POST['username']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$mobile = trim($_POST['mobile']);
		$company = trim($_POST['company']);
		$store_storage = $_POST['store_storage'];
		$commision = $_POST['commision'];
		$vis = 1;
		
		$img = $_FILES['image']['name'];
    	$imgtmp = $_FILES['image']['tmp_name'];
		
		$img ? $im = time().'-'.$img : $im = '';
		
		if($img!=''){
        	move_uploaded_file($imgtmp,'uploads/'.$im);
    	}

		$sql = $conn->prepare("INSERT INTO `tbl_admin`(`email`, `username`, `password`, `mobile`, `company`, `type`, `image`, `vis`, `store_storage`, `commision`) VALUES (:email, :username, :password, :mobile, :company, :type, :image, :vis, :store_storage, :commision)");
		
		$sql->bindParam(':email', $email, PDO::PARAM_STR);
		$sql->bindParam(':username', $username, PDO::PARAM_STR);
		$sql->bindParam(':password', $password, PDO::PARAM_STR);
		$sql->bindParam(':mobile', $mobile, PDO::PARAM_STR);
		$sql->bindParam(':company', $company, PDO::PARAM_STR);
		$sql->bindParam(':type', $type, PDO::PARAM_STR);
		$sql->bindParam(':image', $im, PDO::PARAM_STR);
		$sql->bindParam(':vis', $vis, PDO::PARAM_INT);
		$sql->bindParam(':store_storage', $store_storage, PDO::PARAM_STR);
		$sql->bindParam(':commision', $commision, PDO::PARAM_STR);
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
	
	// update user
 	if(isset($_REQUEST['update'])){
	
		$type = trim($_POST['type']);
		$username = trim($_POST['username']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$mobile = trim($_POST['mobile']);
		$company = trim($_POST['company']);
		$store_storage = $_POST['store_storage'];
		$commision = $_POST['commision'];
		
		$oldimg = $_POST['oldimg'];

		$img = $_FILES['image']['name'];
    	$imgtmp = $_FILES['image']['tmp_name'];
		
		$img ? $im = time().'-'.$img : $im = $oldimg;
		
		if($img!=''){
			unlink('uploads/'.$oldimg);
        	move_uploaded_file($imgtmp,'uploads/'.$im);
    	}
		
		//$img ? $im = $img : $im = $oldimg;

		$sql = $conn->prepare("update `tbl_admin` set `email` = :email, `username` = :username, `password` = :password, `mobile` = :mobile, `company` = :company, `type` = :type, `image` = :image, `store_storage` = :store_storage, `commision` = :commision where id = :id");
		
		$sql->bindParam(':email', $email, PDO::PARAM_STR);
		$sql->bindParam(':username', $username, PDO::PARAM_STR);
		$sql->bindParam(':password', $password, PDO::PARAM_STR);
		$sql->bindParam(':mobile', $mobile, PDO::PARAM_STR);
		$sql->bindParam(':company', $company, PDO::PARAM_STR);
		$sql->bindParam(':type', $type, PDO::PARAM_STR);
		$sql->bindParam(':image', $im, PDO::PARAM_STR);
		$sql->bindParam(':id', $id, PDO::PARAM_INT);
		$sql->bindParam(':store_storage', $store_storage, PDO::PARAM_STR);
		$sql->bindParam(':commision', $commision, PDO::PARAM_STR);
		$sql->execute();
		if($sql == true){
		
			echo '<script>alert("User has been Updated Successfully !!")</script>';
			echo '<script>window.location.href="user.php"</script>';
		
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
<?=$WebsiteTitle; ?> | Vendors</title>
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
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=p8k5vi6mdqhvkw9xur5608ddrxuq46gccpw17lptkmswhit9"></script>
<script>tinymce.init({ selector:'textarea' });</script>
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
      <h1><?php if ($id==''){ echo 'Add Vendor'; } else{echo 'Update Vendor';} ?></h1>
      
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title"><?php if ($id=='') echo 'Add Vendor'; else echo 'Update Vendor'; ?></h3>
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
                <label for="orangeForm-email">Roles</label>
                <select class="form-control" name="type" required>
                  <option value="">Select Role</option>
                  <option value="Vendor">Vendor</option>
                </select>
              </div>
              <div class="form-group">
                <label for="orangeForm-email">Name</label>
                <input type="text" id="orangeForm-email" name="username" class="form-control email is-valid" required />
              </div>
              <div class="form-group">
                <label for="orangeForm-email">Email</label>
                <input type="email" id="orangeForm-email" name="email" class="form-control email is-valid" required />
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Password</label>
                <input type="password" id="orangeForm-pass" name="password" class="form-control pass is-valid" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Mobile No.</label>
                <input type="number" id="orangeForm-pass" name="mobile" class="form-control pass is-valid" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Store Name</label>
                <input type="text" id="orangeForm-pass" name="company" class="form-control pass is-valid" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Store Storage Size</label>
                <input type="number" id="orangeForm-pass" name="store_storage" class="form-control pass is-valid" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Commission %(Enter Like 5)</label>
                <input type="number" step="any" id="orangeForm-pass" name="commision" class="form-control pass is-valid" required>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3 text-center"><img id="uploadPreview" width="150" height="150" ></div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <label for="exampleInputPassword1">Store Image</label>
                    <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" required />
                  </div>
                </div>
              </div>
              <input  type="submit" value="Submit" class="btn btn-primary" name="submit">
            </form>
            </div>
          </div>
        </div>
        <?php } else { ?>
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <form role="form" name="stform" id="stform" action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="orangeForm-email">Roles</label>
                <select class="form-control" name="type" required>
                  <option value="">Select Role</option>
                  <option <?php if($editval['type']=='Vendor'){ ?> selected <?php } ?> value="Vendor">Vendor</option>
                </select>
              </div>
              <div class="form-group">
                <label for="orangeForm-email">Name</label>
                <input type="text" id="orangeForm-email" name="username" class="form-control email is-valid" value="<?php echo $editval['username']; ?>" required />
              </div>
              
              <div class="form-group">
                <label for="orangeForm-email">Email</label>
                <input type="email" id="orangeForm-email" name="email" class="form-control email is-valid" value="<?php echo $editval['email']; ?>" required />
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Password</label>
                <input type="text" id="orangeForm-pass" name="password" class="form-control pass is-valid" value="<?php echo $editval['password']; ?>" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Mobile No.</label>
                <input type="number" id="orangeForm-pass" name="mobile" class="form-control pass is-valid" value="<?php echo $editval['mobile']; ?>" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Store Name</label>
                <input type="text" id="orangeForm-pass" name="company" class="form-control pass is-valid" value="<?php echo $editval['company']; ?>" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Store Storage Size</label>
                <input type="number" id="orangeForm-pass" name="store_storage" class="form-control pass is-valid" value="<?php echo $editval['store_storage']; ?>" required>
              </div>
              <div class="form-group">
                <label for="orangeForm-pass">Commission %(Enter Like 5)</label>
                <input type="number" step="any" id="orangeForm-pass" name="commision" class="form-control pass is-valid" value="<?php echo $editval['commision']; ?>" required>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3 text-center"> <img src="uploads/<?php echo $editval['image']; ?>" id="uploadPreview" width="150" height="150" ></div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <label for="exampleInputPassword1">Store Image</label>
                    <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();">
                  </div>
                </div>
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
    </section>
  </div>
  
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
<script>

$(function () {
	//Date picker
	$('#datepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true
	});
});
</script>
</body>
</html>
