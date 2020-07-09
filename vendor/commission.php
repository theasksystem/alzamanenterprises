<?php
session_start();
error_reporting(0);
include 'functions/db.class.php';

if($_SESSION['VENDOR_ID']=='') {
    echo "<script>window.location.href='../vendors'</script>";
}

$editUser = $conn->prepare("SELECT * FROM `tbl_admin` where id = '".$_SESSION['VENDOR_ID']."'");
$editUser->execute();
$editRow = $editUser->fetch(PDO::FETCH_ASSOC);
$mycomm = $editRow['commision'];

$queryyy = $conn->prepare("select GROUP_CONCAT(id) as id from cart_orders WHERE status = 'completed' and id IN(select order_id from cart_order_item where pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."'))");
$queryyy->execute();
$productCount2 = $queryyy->fetch(PDO::FETCH_ASSOC);
$totalAmtt = $conn->prepare("select sum(price*qty) as mytotal from cart_order_item where order_id IN (".$productCount2['id'].") and pid IN(select id from products where user_id = '".$_SESSION['VENDOR_ID']."')");
$totalAmtt->execute();
$totalAmtt2 = $totalAmtt->fetch(PDO::FETCH_ASSOC);

$productCount3 = ($totalAmtt2['mytotal']*$mycomm)/100;
$productCount = number_format($productCount3, 2, '.', '');

$productActiveCount2 = $conn->prepare("select GROUP_CONCAT(id) as id from cart_orders WHERE status = 'completed' and id IN(select order_id from cart_order_item where pid IN(select id from products where user_id=".$_SESSION['VENDOR_ID']."))  and `commision_status` = 1");
$productActiveCount2->execute();
$productActiveCount3 = $productActiveCount2->fetch(PDO::FETCH_ASSOC);
if($productActiveCount3['id']==''){ $acCount = 0; }else{ $acCount = $productActiveCount3['id']; }
$totalAmtt3 = $conn->prepare("select sum(price*qty) as mytotal from cart_order_item where order_id IN ($acCount) and pid IN(select id from products where user_id = '".$_SESSION['VENDOR_ID']."')");
$totalAmtt3->execute();
$totalAmtt4 = $totalAmtt3->fetch(PDO::FETCH_ASSOC);

$productActiveCount2 = ($totalAmtt4['mytotal']*$mycomm)/100;
$productActiveCount = number_format($productActiveCount2, 2, '.', '');


$productInactiveCount2 = $conn->prepare("select GROUP_CONCAT(id) as id from cart_orders WHERE status = 'completed' and id IN(select order_id from cart_order_item where pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')) and `commision_status` = 0");
$productInactiveCount2->execute();
$productInactiveCount3 = $productInactiveCount2->fetch(PDO::FETCH_ASSOC);
$totalAmtttt = $conn->prepare("select sum(price*qty) as mytotal from cart_order_item where order_id IN (".$productInactiveCount3['id'].") and pid IN(select id from products where user_id = '".$_SESSION['VENDOR_ID']."')");
$totalAmtttt->execute();
$totalAmtttt2 = $totalAmtttt->fetch(PDO::FETCH_ASSOC);

$productInactiveCount4 = ($totalAmtttt2['mytotal']*$mycomm)/100;
$productInactiveCount = number_format($productInactiveCount4, 2, '.', '');
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
      <h1>Products Commission</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Products Commission List <button class="btn btn-primary" style="margin-left:10px">Total Products Commission - QAR <?=$productCount; ?></button>
          <button class="btn btn-primary" style="margin-left:10px">Paid Products Commission - QAR <?=$productActiveCount; ?></button>
          <button class="btn btn-primary" style="margin-left:10px">Pending Products Commission - QAR <?=$productInactiveCount; ?></button></h3>
        </div>
        <!-- /.box-header -->
        <?php if (isset($msg)) echo $msg; ?>
        <div class="box-body table-responsive">
          <table id="example1" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">Sno.</th>
                                        <th width="10%">Order#</th>
                                        <th width="10%">Name</th>
                                        <th width="20%">Email & Phone</th>
                                        <th width="15%">Total</th>
                                        <th width="15%">Commission</th>
                                        <th width="15%">Commission Status</th>
                                        <th width="10%">Detail</th>
                                        <th width="10%">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$i = 1;

$query = $conn->prepare("select user_id,id,created_at,status,commision_status from cart_orders WHERE status = 'completed' and id IN(select order_id from cart_order_item where pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')) order by id desc");

$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC))
 {
$useridno=$row['user_id'];
$query2 = $conn->prepare("select name,phone,email from registration WHERE id='$useridno'");
$query2->execute();
$row2 = $query2->fetch(PDO::FETCH_ASSOC);

$totalAmt = $conn->prepare("select sum(price*qty) as mytotal from cart_order_item where order_id = '".$row['id']."' and pid IN(select id from products where user_id = '".$_SESSION['VENDOR_ID']."')");
$totalAmt->execute();
$totalAmt2 = $totalAmt->fetch(PDO::FETCH_ASSOC);
$commission2 = ($totalAmt2['mytotal']*$mycomm)/100;
$commission = number_format($commission2, 2, '.', '');
?>

                                        <tr>
                                            <td class="center"><?= $i++; ?></td>
                                            <td class="center"><?php echo 'ALZ-00-'.$row['id']; ?></td>
                                            <td class="center"><?php echo ucfirst($row2['name']); ?></td>
                                            <td class="center"><?php echo $row2['email']; ?><br>-> <?php echo $row2['phone']; ?></td>
                                            <td class="center"><?='QAR '.$totalAmt2['mytotal']; ?></td>
                                            <td class="center"><?='QAR '.$commission; ?></td>
                                            <td class="center"><?php if($row['commision_status']==1){ echo 'Paid';}if($row['commision_status']==0){ echo 'Pending';} ?></td>
                                            <td class="center"><a style="cursor: pointer;" onClick="getcartdata('<?= $row['id']; ?>');">view</a></td>
                                            <td class="center"><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                            

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
<!-- ./wrapper -->
<!-- REQUIRED JS SCRIPTS -->
<script>
			function getcartdata(orderid) {
           

            $.ajax({
                    type: "POST",
                    url: "ajax_cart_data2.php",
                    data: {'id':orderid},
                    cache: true,
                    beforeSend: function(){
                      var order = 'Order Details For Order No.<span style="color:red;"> ALZ-'+orderid+'</span>';
                      $('.orderid').html(order);
                    },
                    complete: function(){
			//alert(orderid);

                    },
                    success: function(response){

                      //var json = $.parseJSON(html);
                     // $( "#shipping_details" ).html();
					  document.getElementById("shipping_details").innerHTML=response; 
                      //$( "#cart_details" ).html(json.cart);
                      //$( "#totaldata" ).html(json.totaldata);
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
