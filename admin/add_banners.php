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

$getResult = $conn->prepare("SELECT * FROM `banners` WHERE `id` = :id");
$getResult->bindParam(':id', $id, PDO::PARAM_INT);
$getResult->execute();
$editval = $getResult->fetch(PDO::FETCH_ASSOC);

 
 	// ------- Insert Query ------- //
 
 	if(isset($_REQUEST['submit'])){
	
		$map_on = $_POST['map_on'];
		$did = $_POST['did'];
		$sid = $_POST['sid'];
		$rid = $_POST['rid'];
		$type = $_POST['type'];
		$seo_title = $_POST['seo_title'];
		$seo_alt = $_POST['seo_alt'];
		$seo_title_ar = $_POST['seo_title_ar'];
		$seo_alt_ar = $_POST['seo_alt_ar'];
		$img = $_FILES['image']['name'];
    	$imgtmp = $_FILES['image']['tmp_name'];
		
		$img ? $im = time().'-'.$img : $im = '';
		
		if($img!=''){
        	move_uploaded_file($imgtmp,'../astra/banners/'.$im);
    	}

		$sql = $conn->prepare("INSERT INTO `banners`(`map_on`, `department`, `sub_department`, `receipe`, `type`, `seo_title_eng`, `seo_alt_eng`, `seo_title_ar`, `seo_alt_ar`, `banner`) VALUES (:map_on, :did, :sid, :rid, :type, :seo_title, :seo_alt, :seo_title_ar, :seo_alt_ar, :banner)");
		$sql->bindParam(':map_on', $map_on, PDO::PARAM_INT);
		$sql->bindParam(':did', $did, PDO::PARAM_INT);
		$sql->bindParam(':sid', $sid, PDO::PARAM_INT);
		$sql->bindParam(':rid', $rid, PDO::PARAM_INT);
		$sql->bindParam(':type', $type, PDO::PARAM_INT);
		$sql->bindParam(':seo_title', $seo_title, PDO::PARAM_STR);
		$sql->bindParam(':seo_alt', $seo_alt, PDO::PARAM_STR);
		$sql->bindParam(':seo_title_ar', $seo_title_ar, PDO::PARAM_STR);
		$sql->bindParam(':seo_alt_ar', $seo_alt_ar, PDO::PARAM_STR);
		$sql->bindParam(':banner', $im, PDO::PARAM_STR);
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
		
		$map_on = $_POST['map_on'];
		$did = $_POST['did'];
		$sid = $_POST['sid'];
		$rid = $_POST['rid'];
		$type = $_POST['type'];
		$seo_title = $_POST['seo_title'];
		$seo_alt = $_POST['seo_alt'];
		$seo_title_ar = $_POST['seo_title_ar'];
		$seo_alt_ar = $_POST['seo_alt_ar'];
		$img = $_FILES['image']['name'];
    	$imgtmp = $_FILES['image']['tmp_name'];
		
		$oldimg = $_POST['oldimg'];

		$img = $_FILES['image']['name'];
    	$imgtmp = $_FILES['image']['tmp_name'];
		
		$img ? $im = time().'-'.$img : $im = $oldimg;
		
		if($img!=''){
			unlink('../astra/banners/'.$oldimg);
        	move_uploaded_file($imgtmp,'../astra/banners/'.$im);
    	}

		$sql = $conn->prepare("UPDATE `banners` SET `map_on`=:map_on, `department`=:did, `sub_department`=:sid, `receipe`=:rid, `type`=:type, `seo_title_eng`=:seo_title, `seo_alt_eng`=:seo_alt, `seo_title_ar`=:seo_title_ar, `seo_alt_ar`=:seo_alt_ar, `banner`=:banner WHERE `id` = :id");
		
		$sql->bindParam(':id', $id, PDO::PARAM_INT);
		$sql->bindParam(':map_on', $map_on, PDO::PARAM_INT);
		$sql->bindParam(':did', $did, PDO::PARAM_INT);
		$sql->bindParam(':sid', $sid, PDO::PARAM_INT);
		$sql->bindParam(':rid', $rid, PDO::PARAM_INT);
		$sql->bindParam(':type', $type, PDO::PARAM_INT);
		$sql->bindParam(':seo_title', $seo_title, PDO::PARAM_STR);
		$sql->bindParam(':seo_alt', $seo_alt, PDO::PARAM_STR);
		$sql->bindParam(':seo_title_ar', $seo_title_ar, PDO::PARAM_STR);
		$sql->bindParam(':seo_alt_ar', $seo_alt_ar, PDO::PARAM_STR);
		$sql->bindParam(':banner', $im, PDO::PARAM_STR);
		$sql->execute();
		
		if($sql == true){
		
			echo '<script>alert("Banner has been Updated Successfully !!")</script>';
			echo '<script>window.location.href="banners.php"</script>';
		
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
        <title><?=$WebsiteTitle; ?> | Banner</title>
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

                    <h1><?php if ($id == ''){ echo 'Add Banner'; } else{echo 'Update Banner';} ?></h1>

                    <ol class="breadcrumb">
                        <li><a href="home.php"><i class="fa fa-dashboard"></i>Home</a><a href="#;"><li class="active"> Add Banner</li></a></li>

                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php if ($id == '') echo 'Add Banner'; else echo 'Update Banner'; ?></h3>
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
                                                <label>Map Banner On</label><br>
                                                <input type="radio" name="map_on" value="1" onChange="showdata(this.value)" checked="checked"> Department &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="map_on" value="2" onChange="showdata(this.value)"> Receipe
                                            </div>
											
											<div id="divdata"></div>
											
											<div class="form-group">
											<label>Banner type</label>
												<select class="form-control" name="type" required>
								  					<option value="">Select Banner Type</option>
													<option value="1">Web Banner</option>
													<option value="2">Mobile Banner</option>											
												</select>
                               				 </div>
                                            
											<div class="form-group">
                                                <label>Seo Title (English)</label>
                                                <input type="text" class="form-control" placeholder="Seo Title (English)"  name="seo_title">
												
                                            </div>
											
											<div class="form-group">
                                                <label>Seo Alt (English)</label>
                                                <input type="text" class="form-control" placeholder="Seo Alt (English)"  name="seo_alt">
                                            </div>
											
											<div class="form-group">
                                                <label>Seo Title (Arabic)</label>
                                                <input type="text" class="form-control" placeholder="Seo Title (Arabic)"  name="seo_title_ar" dir="rtl">
												
                                            </div>
											
											<div class="form-group">
                                                <label>Seo Alt (Arabic)</label>
                                                <input type="text" class="form-control" placeholder="Seo Alt (Arabic)"  name="seo_alt_ar" dir="rtl">
                                            </div>
											
											<div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 text-center"> <img id="uploadPreview" width="150" height="150" ></div>
                                                        </div>
                                            </div>
											
											<div class="form-group">
													<div class="row">
                                                       <div class="col-md-12">
                                                        <label for="exampleInputPassword1">Banner Image(Banner size should be 1920 x 345 pixels)</label>
                                                        <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" required />
													   </div>
														</div>
                                                    </div>

                                            <input  type="submit" value="Submit" class="btn btn-primary" name="submit">

                                        </form>


                                    </div>



                                </div>
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>
$(document).ready(function(){
	showdata(1);
});
function showdata(str) {
//alert(str);
$.ajax({
                    type: "POST",
                    url: "state.php",
                    data: {'q':str,},
                    cache: false,
                    
                    success: function(data){
                        
                        setTimeout(function() {                                          
                          $( "#subsloader" ).hide();
                          $("#divdata").html(data);
                    }, 0);

                    }
                });
				}
</script>
<?php } else { ?>

                                <div class="col-md-12">
                                    <!-- general form elements -->
                                    <div class="box box-primary">

                                        <form role="form" name="stform" id="stform" action="" method="post" enctype="multipart/form-data">
											
											<div class="form-group">
                                                <label>Map Banner On</label><br>
                                                <input type="radio" name="map_on" value="1" onChange="showdata(this.value)" <?php if($editval['map_on']==1){ echo 'checked'; } ?> > Department &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="map_on" value="2" onChange="showdata(this.value)" <?php if($editval['map_on']==2){ echo 'checked'; } ?>> Receipe
                                            </div>
											
											<div id="divdata"></div>
											
											<div class="form-group">
											<label>Banner type</label>
												<select class="form-control" name="type" required>
								  					<option value="">Select Banner Type</option>
													<option <?php if($editval['type']==1){ echo 'selected'; } ?> value="1">Web Banner</option>
													<option <?php if($editval['type']==2){ echo 'selected'; } ?> value="2">Mobile Banner</option>											
												</select>
                               				 </div>
                                            
											<div class="form-group">
                                                <label>Seo Title (English)</label>
                                                <input type="text" class="form-control" placeholder="Seo Title (English)"  name="seo_title" value="<?php echo $editval['seo_title_eng']; ?>">
												
                                            </div>
											
											<div class="form-group">
                                                <label>Seo Alt (English)</label>
                                                <input type="text" class="form-control" placeholder="Seo Alt (English)"  name="seo_alt" value="<?php echo $editval['seo_alt_eng']; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Seo Title (Arabic)</label>
                                                <input type="text" class="form-control" placeholder="Seo Title (Arabic)"  name="seo_title_ar" dir="rtl" value="<?php echo $editval['seo_title_ar']; ?>">
												
                                            </div>
											
											<div class="form-group">
                                                <label>Seo Alt (Arabic)</label>
                                                <input type="text" class="form-control" placeholder="Seo Alt (Arabic)"  name="seo_alt_ar" dir="rtl" value="<?php echo $editval['seo_alt_ar']; ?>">
                                            </div>
											
											<div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3 text-center"> <img src="../astra/banners/<?php echo $editval['banner']; ?>" id="uploadPreview" width="150" height="150" ></div>
                                                        </div>
                                                    </div>
											
											<div class="form-group">
													<div class="row">
                                                       <div class="col-md-12">
                                                        <label for="exampleInputPassword1">Banner Image(Banner size should be 1920 x 345 pixels)</label>
                                                        <input type="file" class="form-control" id="image" name="image" onChange="PreviewImage();" />
													   </div>
														</div>
                                                    </div>

                                            <input type="hidden" name="oldimg" value="<?php echo $editval['banner']; ?>" />
                                            <input  type="submit" value="Update" class="btn btn-primary" name="update">

                                        </form>


                                    </div>


                                </div>
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script>
$(document).ready(function(){
	showdata(<?php echo $editval['map_on']; ?>);
});
function showdata(str) {
//alert(str);
$.ajax({
                    type: "POST",
                    url: "state.php",
                    data: {'qq':str,'depart':'<?php echo $editval['department']; ?>','subdepart':'<?php echo $editval['sub_department']; ?>','recep':'<?php echo $editval['receipe']; ?>',},
                    cache: false,
                    
                    success: function(data){
                        
                        setTimeout(function() {                                          
                          $( "#subsloader" ).hide();
                          $("#divdata").html(data);
                    }, 0);

                    }
                });
				}
								</script>

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
</script>
<script>

            function PreviewImage()
            {
                var oFReader = new FileReader();
                oFReader.readAsDataURL(document.getElementById("image").files[0]);
                oFReader.onload = function (oFREvent) {
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                };
            };

		function fetch_select2(val)
    {
	//alert(val);
		 $.ajax({
		
		 type: 'post',
		 url: 'state.php',
		 data: {
		  product_cat:val
		 },
		
		 success: function (response) {
		  document.getElementById("new_select2").innerHTML=response; 
		 }
		 });
    }
        </script>


</body>
</html>
