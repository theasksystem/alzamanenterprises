<?php

session_start();
error_reporting(0);
include'functions/db.class.php';

if ($_SESSION['USER_ID'] == '') {
    echo "<script>window.location='index.php';</script>";
}

$id = base64_decode($_GET['id']);

if (isset($_GET['visible'])) {
     $result = $conn->prepare("update tbl_testimonial set visible = :visible where id = :id");
	 $result->bindParam(':visible', $_GET['visible'], PDO::PARAM_INT);
	 $result->bindParam(':id', $id, PDO::PARAM_INT);
	 $result->execute();

    echo "<script>window.location='review.php';</script>";
}


if(isset($_REQUEST['delete']) && $_REQUEST['delete']=='y'){

	$delUser = $conn->prepare("DELETE FROM `tbl_testimonial` WHERE `id` = :id");
	$delUser->bindParam(':id', $id, PDO::PARAM_INT);
	$delUser->execute();

	echo "<script>window.location='review.php';</script>";

}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>All | Product Reviews list</title>
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
    <span class="logo-lg"><b>
    <?=$logo; ?>
    </b></span> </a>
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
      <h1>Product Reviews list</h1>
     
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Product Reviews list</h3>
        </div>
        <!-- /.box-header -->
        <?php if (isset($msg)) echo $msg; ?>
        <div class="box-body table-responsive">
                            <table id="example1" width="100%" class="table table-bordered table-striped">
                                 <thead>
              <tr>
                <th>S No.</th>
                <th>Product Name</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Visibility</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
				
				$i=1;
					 
					$query = $conn->prepare("select * from tbl_testimonial order by id desc");
					$query->execute();

					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					{
				    $getProductdetail = $conn->prepare("SELECT product_name_en FROM products WHERE id = '".$row['pid']."'");
                    $getProductdetail->execute();
                    $getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);
					
			  ?>
              <tr>
                <td class="center"><?php echo $i++; ?></td>
                <td class="center"><?php echo $getProductRow['product_name_en']; ?></td>
                <td class="center"><?php echo $row['name']; ?></td>
                <td class="center"><?php echo $row['email']; ?></td>
                <td class="center"><?php echo $row['mobile']; ?></td>
                <td class="center"><?php echo $row['comment']; ?></td>
                <td class="center"><?php echo $row['created_at']; ?></td>
                <td class="center"><?php if ($row['visible'] == 1) { ?>
                  <a href="?visible=0&id=<?php echo base64_encode($row['id']); ?>"  title="ACTIVE"><img src="img/active.png" height="30" width="30" /></a>
                  <?php } else { ?>
                  <a href="?visible=1&id=<?php echo base64_encode($row['id']); ?>"  title="INACTIVE"><img src="img/inactive.png" height="30" width="30" /></a>
                  <?php } ?>
                </td>
                <td class="center"><span style="float: left;"><a onClick="return confirm('Sure you want to delete this record.');"
                                                                                href="?id=<?php echo base64_encode($row['id']); ?>&delete=y"  title="DELETE"><img src="img/delete.png" height="30" width="30" /></a> </span> </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
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
        <!-- DataTables -->
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="dist/js/demo.js"></script>
        <!-- page script -->
        <script>
                                        $(function () {
                                            $("#example1").DataTable();
                                            $('#example2').DataTable({
                                                "paging": true,
                                                "lengthChange": false,
                                                "searching": false,
                                                "ordering": true,
                                                "info": true,
                                                "autoWidth": false
                                            });
                                        });


        

        </script>
</body>
</html>
