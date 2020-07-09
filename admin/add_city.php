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

$getResult = $conn->prepare("SELECT * FROM `city` WHERE `id` = :id");
$getResult->bindParam(':id', $id, PDO::PARAM_INT);
$getResult->execute();
$editval = $getResult->fetch(PDO::FETCH_ASSOC);

if(isset($_REQUEST['submit'])){
	
		$name_eng = $_POST['name_eng'];
		$name_arb = $_POST['name_arb'];
		$country_id = $_POST['country_id'];
		$state_id = $_POST['state_id'];

		$sql = $conn->prepare("INSERT INTO `city`(`name_en`, `name_ar`, `country_id`, `state_id`) VALUES (:name_en, :name_ar, :c_code, :sttt)");
		
		$sql->bindParam(':name_en', $name_eng, PDO::PARAM_STR);
		$sql->bindParam(':name_ar', $name_arb, PDO::PARAM_STR);
		$sql->bindParam(':c_code', $country_id, PDO::PARAM_INT);
		$sql->bindParam(':sttt', $state_id, PDO::PARAM_INT);
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
	
		$name_eng = $_POST['name_eng'];
		$name_arb = $_POST['name_arb'];
		$country_id = $_POST['country_id'];
		$state_id = $_POST['state_id'];

		$sql = $conn->prepare("UPDATE `city` SET `name_en`='$name_eng', `name_ar`='$name_arb', `country_id`='$country_id', `state_id`='$state_id' WHERE `id` = '$id'");
		$sql->execute();
		
		if($sql == true){
		
			echo '<script>alert("Data has been Updated Successfully !!")</script>';
			echo '<script>window.location.href="city_list.php"</script>';
		
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
        <title><?=$WebsiteTitle; ?> | City</title>
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
                    <span class="logo-lg"><?=$logo; ?></span>
                </a>

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

                    <h1><?php if ($id == ''){ echo 'Add City'; } else{echo 'Update City';} ?></h1>

                    <ol class="breadcrumb">
                        <li><a href="home.php"><i class="fa fa-dashboard"></i>Home</a><a href="#;"><li class="active"> Add City</li></a></li>

                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php if ($id == '') echo 'Add City'; else echo 'Update City'; ?></h3>
                            <?php if (isset($msg)) echo $msg; ?>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">

                                <?php
                                if (!isset($_GET['id']) && !isset($_GET['edit'])) {
                                    ?>

                                    <div class="col-md-12">
                                    <!-- general form elements -->
                                    <div class="box box-primary">

                                        <form role="form" name="stform" id="stform" action="" method="post" enctype="multipart/form-data">
											<div class="form-group">
                                                <label>Country</label>
                                                <select class="form-control" name="country_id" onChange="fetch_select(this.value);" required>
												  <option value="">Select Country</option>
												  <?php 
											
											$getCat = $conn->query("SELECT * FROM `country` ORDER BY `id` asc");
											while($nBlog = $getCat->fetch(PDO::FETCH_ASSOC)){
											
											?>
											
											<option value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_en'].'('.$nBlog['name_ar'].')';  ?></option>
											
											<?php } ?>
												</select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>State(Optional)</label>
                                                <select class="form-control" name="state_id" id="category">
												  <option value="">Select State</option>
												 </select>
                                            </div>
                                            
											<div class="form-group">
                                                <label>City Name (English)</label>
                                                <input type="text" class="form-control" placeholder="City Name (English)"  name="name_eng" required>
                                            </div>
											
											<div class="form-group">
                                                <label>City Name (Arabic)</label>
                                                <input type="text" class="form-control" placeholder="City Name (Arabic)"  name="name_arb" dir="rtl" required>
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
                                                <label>Country</label>
                                                <select class="form-control" name="country_id" onChange="fetch_select(this.value);" required>
												  <option value="">Select Country</option>
												  <?php 
											
											$getCat = $conn->query("SELECT * FROM `country` ORDER BY `id` asc");
											while($nBlog = $getCat->fetch(PDO::FETCH_ASSOC)){
											
											?>
											
											<option <?php if($editval['country_id']==$nBlog['id']){ echo 'selected'; } ?> value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_en'].'('.$nBlog['name_ar'].')';  ?></option>
											
											<?php } ?>
												</select>
                                            </div>
                                            <div class="form-group">
                                                <label>State(Optional)</label>
                                                <select class="form-control" name="state_id" id="category">
												  <option value="">Select State</option>
                                                  <?php 
											
											$getCat2 = $conn->query("SELECT * FROM `state` where country_id='".$editval['country_id']."' ORDER BY `name_en` asc");
											while($nBlog2 = $getCat2->fetch(PDO::FETCH_ASSOC)){
											
											?>
											
											<option <?php if($editval['state_id']==$nBlog2['id']){ echo 'selected'; } ?> value="<?php echo $nBlog2['id'];  ?>"><?php echo $nBlog2['name_en'].'('.$nBlog2['name_ar'].')';  ?></option>
                                            <?php } ?>
												 </select>
                                            </div>
											<div class="form-group">
                                                <label>City Name (English)</label>
                                                <input class="form-control" placeholder="City Name (English)"  name="name_eng" value="<?=$editval['name_en']; ?>" required>
                                            </div>
											
											<div class="form-group">
                                                <label>City Name (Arabic)</label>
                                                <input class="form-control" placeholder="City Name (Arabic)"  name="name_arb" dir="rtl" value="<?=$editval['name_ar']; ?>" required>
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
        <div class="pull-right hidden-xs">

        </div>
        <!-- Default to the left -->
        <strong><?=$copyright; ?></strong>
    </footer>
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

											
		
function fetch_select(val)
{
    
	 $.ajax({
	 type: 'post',
	 url: 'getvalue.php',
	 data: {country:val},
	 success: function (response) {
	     //alert(response);
	   document.getElementById('category').innerHTML=response; 
	 }
	 });
}
</script>



</body>
</html>
