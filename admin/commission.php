<?php
session_start();
error_reporting(0);
include 'functions/db.class.php';

if ($_SESSION['USER_ID'] == '') {
    echo "<script>window.location='../index.php';</script>";
}

$editUser = $conn->prepare("SELECT * FROM `tbl_admin` where id = '".$_SESSION['USER_ID']."'");
$editUser->execute();
$editRow = $editUser->fetch(PDO::FETCH_ASSOC);

$id = base64_decode($_GET['id']);
$vendor = base64_decode($_GET['vendor']);

if (isset($_GET['accept']))
{
  $result = $conn->prepare("update cart_orders set commision_status = 1  where id = '$id'");
  $result->execute();
  header('location:commission.php');
}

if($vendor!=''){

$vendor2 = " and pid IN(SELECT id FROM `products` where user_id = '$vendor')";

} else {

$vendor2 = '';

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
<title>All | Products Commission list</title>
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
      <h1>Products Commission</h1>
     <ol class="breadcrumb"> 
      <li><div class="form-group" style="margin-top: -10px;">
      <form name="">
                    <select class="form-control" name="vendor" onChange="this.form.submit()">
                      <option value="">Select Store Wise Commission</option>
                      <?php 
													
						$department = $conn->query("SELECT id,username,email FROM `tbl_admin` WHERE vis=1 and id IN(select user_id from products where id IN(select pid from cart_orders where status='completed')) ORDER BY `id` asc");
						$department->execute();
						while($departmentRow = $department->fetch(PDO::FETCH_ASSOC)){
					  ?>
                      <option <?php if($departmentRow['id']==$vendor){ echo 'selected'; } ?> value="<?=base64_encode($departmentRow['id']);  ?>"><?php echo $departmentRow['username'].'('.$departmentRow['email'].')';  ?></option>
                      <?php } ?>
                    </select>
       </form>
                  </div></li>
    </ol>              
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Products Commission List <!--<button class="btn btn-primary" style="margin-left:10px">Total Products Commission - QAR <?=$productCount; ?></button>
          <button class="btn btn-primary" style="margin-left:10px">Paid Products Commission - QAR <?=$productActiveCount; ?></button>
          <button class="btn btn-primary" style="margin-left:10px">Pending Products Commission - QAR <?=$productInactiveCount; ?></button></h3>-->
        </div>
        <!-- /.box-header -->
        <?php if (isset($msg)) echo $msg; ?>
        <div class="box-body table-responsive">
          <table id="example1" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">Sno.</th>
                                        <th width="10%">Order No.</th>
                                        <th width="10%">Order Date</th>
                                        <th width="20%">Store Name</th>
                                        <th width="15%">Order Total</th>
                                        <th width="15%">Commission</th>
                                        <th width="15%">Commission Status</th>
                                        <th width="10%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
                                $i = 1;
                                $query = $conn->prepare("SELECT * FROM `cart_orders` WHERE STATUS='completed' $vendor2 order by id desc");
                                $query->execute();
                                while ($row = $query->fetch(PDO::FETCH_ASSOC))
                                 {
                                     $OrderTotal2 = $conn->prepare("select sum((price*qty)+(ship_charge)) as totalPrice from cart_orders WHERE id='".$row['id']."'");
                                        $OrderTotal2->execute();
                                        $OrderTotal3 = $OrderTotal2->fetch(PDO::FETCH_ASSOC);
								$OrderTotal = $OrderTotal3['totalPrice'];
								$query2= $conn->prepare("SELECT * FROM `products` WHERE id = '".$row['pid']."'");
                                $query2->execute();
                                $row2 = $query2->fetch(PDO::FETCH_ASSOC);
								
								$query3= $conn->prepare("SELECT * FROM `tbl_admin` WHERE id = '".$row2['user_id']."'");
                                $query3->execute();
                                $row3 = $query3->fetch(PDO::FETCH_ASSOC);
								$mycomm = $row3['commision'];
								$productCount3 = ($OrderTotal*$mycomm)/100;
								$productCount = number_format($productCount3, 2, '.', '');
								
                                ?>

                                <tr>
                                    <td class="center"><?= $i++; ?></td>
                                    <td class="center"><?php echo 'ALZ-00-'.$row['id']; ?></td>
                                    <td class="center"><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                    <td class="center"><?php echo ucfirst($row3['company']); ?></td>
                                    <td class="center"><?='QAR '.$OrderTotal; ?></td>
                                    <td class="center"><?='QAR '.$productCount; ?></td>
                                    <td class="center">
                                    <span><?php if($row['commision_status']==1){ ?><a><small class="label pull-right bg-green" style="margin-right:10px; height: 25px; padding: 7px 10px 10px 10px;">Paid</small></a></span>
                                    	  <?php }if($row['commision_status']==0){ ?>
                                          <a href="?id=<?= base64_encode($row['id']); ?>&accept=y"><small class="label pull-right bg-yellow" style="margin-right:10px; height: 25px; padding: 7px 10px 10px 10px;">Confirm Pay</small></a></span>
													<?php } ?>
                                                    
                                                    </td>
                                    <td class="center"><a style="cursor: pointer;" onClick="getcartdata('<?= $row['id']; ?>');">view</a></td>
                                </tr>


<!-- Modal -->


<!---- modal ------>


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
   <div id="myModal" class="modal fade"  role="dialog">
              <div class="modal-dialog modal-lg" style="width: 98% !important;">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title orderid"></h4>
                  </div>
                  <div class="modal-body" id="shipping_details">
                  
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
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

<script>
function getcartdata(orderid) {
//alert(orderid);
   $.ajax({
		type: "POST",
		url: "ajax_cart_data3.php",
		data: {'id':orderid},
		cache: true,
		beforeSend: function(){
		  var order = 'Order Details';
		  $('.orderid').html(order);
		},
		complete: function(){
		},
		success: function(response){
			
			document.getElementById("shipping_details").innerHTML=response; 
			$('#myModal').modal('show');

		}
	});

 }

</script>
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
