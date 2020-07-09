<?php

session_start();
error_reporting(0);
include 'functions/db.class.php';
include('../include/functions.php');

if ($_SESSION['VENDOR_ID'] == '') {
    echo "<script>window.location.href='../vendors'</script>";
}

$editUser = $conn->prepare("SELECT * FROM `tbl_admin` where id = '".$_SESSION['VENDOR_ID']."'");
$editUser->execute();
$editRow = $editUser->fetch(PDO::FETCH_ASSOC);


$id = base64_decode($_GET['id']);

if (isset($_GET['visible'])) {
     $result = $conn->prepare("update products set status = :visible where id = :id");
	 $result->bindParam(':visible', $_GET['visible'], PDO::PARAM_INT);
	 $result->bindParam(':id', $id, PDO::PARAM_INT);
	 $result->execute();
	 echo "<script>window.location='product-user.php';</script>";
}

if (isset($_GET['delete']) == 'y')
{
    
    $delimage = $conn->prepare("SELECT * FROM `products` WHERE `id` = :id");
	$delimage->bindParam(':id', $id, PDO::PARAM_INT);
	$delimage->execute();
	$imgval = $delimage->fetch(PDO::FETCH_ASSOC);

	$delCenter = $conn->prepare("DELETE FROM `products` WHERE `id` = :id");
	$delCenter->bindParam(':id', $id, PDO::PARAM_INT);
	$delCenter->execute();
	unlink('../adminuploads/product/'.$imgval['image']);
	
	$delimage2 = $conn->prepare("SELECT id,image FROM `product_images` WHERE `product_id` = '$id'");
	$delimage2->execute();
	while($imgval2 = $delimage2->fetch(PDO::FETCH_ASSOC)){
		
		$delCenter = $conn->prepare("DELETE FROM `product_images` WHERE `id` = :id");
		$delCenter->bindParam(':id', $imgval2['id'], PDO::PARAM_INT);
		$delCenter->execute();
		unlink('../adminuploads/product/'.$imgval2['image']);
		
	}
	
    echo "<script>window.location='product-user.php';</script>";
  
}

$i=0;
$query = $conn->prepare("select * from products where user_id='".$_SESSION['VENDOR_ID']."' and user_id!=1  order by id desc");
$query->execute();
$productCount = $query->rowCount();

$productActiveCount2 = $conn->prepare("select * from products where user_id='".$_SESSION['VENDOR_ID']."' and user_id!=1  and status = 1  $vendor2");
$productActiveCount2->execute();
$productActiveCount = $productActiveCount2->rowCount();

$productInactiveCount2 = $conn->prepare("select * from products where user_id='".$_SESSION['VENDOR_ID']."' and user_id!=1  and status = 0  $vendor2");
$productInactiveCount2->execute();
$productInactiveCount = $productInactiveCount2->rowCount();

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
<title>All | Product list</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
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
      <h1>Product</h1>
      <ol class="breadcrumb">
         <li><a href="add_product.php" title="ADD NEW"><img src="img/plus.png" height="50" width="50" /></a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Product List <button class="btn btn-primary" style="margin-left:10px">Total Products - <?=$productCount; ?></button>
          <button class="btn btn-primary" style="margin-left:10px">Total Active Products - <?=$productActiveCount; ?></button>
          <button class="btn btn-primary" style="margin-left:10px">Total Inactive Products - <?=$productInactiveCount; ?></button></h3>
        </div>
        <!-- /.box-header -->
        <?php if (isset($msg)) echo $msg; ?>
        <div class="box-body table-responsive">
          <table id="example1" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>S No.</th>
                <th>Product</th>
                <th>Tab</th>

                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Published</th>
              </tr>
            </thead>
            <!-- Table head -->
            <!-- Table body -->
            <tbody>
              <?php
				
				

					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					 {
						$i++;
						
						$query2 = $conn->prepare("select * from department where id = '".$row['tab_id']."'");
						$query2->execute();
						$row2 = $query2->fetch(PDO::FETCH_ASSOC);
						
						$query3 = $conn->prepare("select * from sub_department where id = '".$row['cat_id']."'");
						$query3->execute();
						$row3 = $query3->fetch(PDO::FETCH_ASSOC);
						
						$TotalQuantity = TotalQuantity($conn,$row['id']);
						
						$neworders = $conn->prepare( "select sum(qty) as new_orders from cart_order_item where pid=".$row['id']." and order_id IN(select id from cart_orders where status!='rejected')");
                        $neworders->execute();
						$neworderss = $neworders->fetch(PDO::FETCH_ASSOC);
						$remain = $neworderss['new_orders'];
              ?>
              <tr>
                <td class="center"><?php echo $i; ?></td>
                <td class="center"><?php echo $row['product_name_en']; ?><br>
                                   <?php echo $row['product_name_ar']; ?></td>
                <td class="center"><?php echo $row2['name_en']; ?><br>
                  <?php echo $row2['name_ar']; ?></td>
                  <td class="center"><?php echo $row3['name_en']; ?><br>
                  <?php echo $row3['name_ar']; ?></td>
                <td class="center"><?php echo $row['price']; ?></td>
                <td class="center"><?php echo 'Total - '.$TotalQuantity; ?><br>Remaining-<?php echo $TotalQuantity-$remain; ?></td>
                <td class="center"><?php if ($row['status'] == 1) { ?>
                  <a href="?visible=0&id=<?php echo base64_encode($row['id']); ?>"  title="ACTIVE"><img src="img/active.png" height="30" width="30" /></a>
                  <?php } else { ?>
                  <a href="?visible=1&id=<?php echo base64_encode($row['id']); ?>"  title="INACTIVE"><img src="img/inactive.png" height="30" width="30" /></a>
                  <?php } ?>
                </td>
                <td class="center"><span style="float: left;"> <a href="add_product.php?id=<?php echo base64_encode($row['id']); ?>&edit=y" title="EDIT"><img src="img/edit.png" height="30" width="30" /></a> <a onClick="return confirm('Sure you want to delete this record.');"
                                                                                href="?id=<?php echo base64_encode($row['id']); ?>&delete=y"  title="DELETE"><img src="img/delete.png" height="30" width="30" /></a> </span> </td>
                <td class="center"><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>                                                                
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
