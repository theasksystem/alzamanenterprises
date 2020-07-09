<?php

error_reporting(0);
session_start();
include'functions/db.class.php';

if($_SESSION['VENDOR_ID']=='')
{
  echo "<script>window.location.href='../vendors'</script>";
}

if(isset($_REQUEST['email']))
{

$name = addslashes(strip_tags(trim($_POST['name'])));
$email = addslashes(strip_tags(trim($_POST['email'])));
$mobile = addslashes(strip_tags(trim($_POST['mobile'])));
$company = addslashes(strip_tags(trim($_POST['company'])));
$npass = addslashes(strip_tags(trim($_POST['npass'])));
$cpass = addslashes(strip_tags(trim($_POST['cpass'])));

$oldimg = $_POST['oldimg'];

$img = $_FILES['image']['name'];
$imgtmp = $_FILES['image']['tmp_name'];

$img ? $im = time().'-'.$img : $im = $oldimg;

if($img!=''){
	unlink('../admin/uploads/'.$oldimg);
	move_uploaded_file($imgtmp,'../admin/uploads/'.$im);
}


if($npass=='' && $cpass==''){

  $stmt = $conn->prepare("UPDATE `tbl_admin` SET `username` = :name, `email` = :email, `mobile` = :mobile, `company` = :company, `image` = :image WHERE `id` = :id");
  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':mobile', $mobile, PDO::PARAM_STR);
  $stmt->bindParam(':company', $company, PDO::PARAM_STR);
  $stmt->bindParam(':image', $im, PDO::PARAM_STR);
  $stmt->bindParam(':id', $_SESSION['VENDOR_ID'], PDO::PARAM_INT);
  $stmt->execute();

  $msg = '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success</h4>
            Your setting updated successfully.
          </div>';

}elseif($npass == $cpass){

  $stmt = $conn->prepare("UPDATE `tbl_admin` SET `password` = :pass WHERE `id` = :id");

  $stmt->bindParam(':pass', $npass, PDO::PARAM_STR);
  $stmt->bindParam(':id', $_SESSION['VENDOR_ID'], PDO::PARAM_INT);
  $stmt->execute();


  $msg = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Success</h4>
                Your setting updated successfully.
          </div>';
}else{

   $msg = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error</h4>
                Password did not match.
           </div>';
}


}


$st = $conn->prepare("select * from tbl_admin where id = :adm_id");
$st->bindValue(':adm_id', $_SESSION['VENDOR_ID'], PDO::PARAM_STR);
$st->execute();
$detail = $st->fetch(PDO::FETCH_ASSOC);

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
| Vendor Setting</title>
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
      <h1>Vendor Setting</h1>
      <ol class="breadcrumb">
        <li><a href="home-user.php"><i class="fa fa-dashboard"></i>Home</a>
        <a href="#;">
        <li class="active">Vendor Setting</li>
        </a>
        </li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Vendor Details</h3>
          <?php if(isset($msg)){ echo $msg; } ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-primary">
                <form role="form" method="post" action="" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Name</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" name="name" placeholder="Enter Your First Name" value="<?php echo $detail['username']; ?>" required />
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email" value="<?= $detail['email']; ?>" required />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Mobile No</label>
                      <input type="number" class="form-control" id="exampleInputEmail1" name="mobile" placeholder="Enter Mobile" value="<?= $detail['mobile']; ?>" required />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Company</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" name="company" placeholder="Enter Company" value="<?= $detail['company']; ?>" required />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Current Password</label>
                      <input type="text" class="form-control" id="exampleInputPassword1" name="curr" value="<?= $detail['password']; ?>" disabled />
                      <input type="hidden" name="curpass" value="<?= $detail['pass']; ?>" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">New Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" name="npass" placeholder="New Password" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Confirm Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" name="cpass" placeholder="Confirm Password" />
                    </div>
                    <div class="form-group">
                        <div class="row">
                          <div class="col-md-3 text-center"> <img src="<?php echo $WebsiteUrl; ?>/admin/uploads/<?php echo $detail['image']; ?>" id="uploadPreview" width="150" height="150" ></div>
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
              		<input type="hidden" name="oldimg" value="<?php echo $detail['image']; ?>" />
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
            </div>
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
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>