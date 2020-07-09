<?php
error_reporting(0);
session_start();
include'functions/db.class.php';

if($_SESSION['USER_ID']=='')
{
  echo "<script>window.location='index.php';</script>";
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
        <title>Reject Orders</title>
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
                    <span class="logo-mini"><b></b></span>
                    <span class="logo-lg"><?= $logo; ?></span>
                </a>

<?php include 'header.php'; ?>

            </header>

            <aside class="main-sidebar">


<?php include 'menu.php'; ?>

                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Reject Orders</h1>
                    <ol class="breadcrumb">
                        <li><a href="home.php"><i class="fa fa-dashboard"></i>Home</a><a href="#;"><li class="active">Reject Orders</li></a></li>

                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class="box">
                        <div class="box-header">
                        <?php if (isset($msg)) echo $msg; ?>
                            <h3 class="box-title">Reject Orders</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table id="example1" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">Sno.</th>
                                        <th width="10%">Order#</th>
                                        <th width="10%">Name</th>
                                        <th width="20%">Email & Phone</th>
                                        <th width="15%">Total</th>
                                        <th width="10%">Detail</th>
                                        <th width="10%">Date</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$i = 1;
$query = $conn->prepare("select c.id,c.status,c.created_at,c.user_id,c.total, p.user_id as vendorId from cart_orders c LEFT JOIN products p ON p.id = c.pid  WHERE c.status = 'rejected' order by id desc");

$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC))
 {
$useridno=$row['user_id'];
$query2 = $conn->prepare("select name,email,phone from registration WHERE id='$useridno'");
$query2->execute();
$row2 = $query2->fetch(PDO::FETCH_ASSOC);
?>

                                        <tr>
                                            <td class="center"><?= $i++; ?></td>
                                            <td class="center"><?php echo 'ALZ-'.$row['id']; ?></td>
                                            <td class="center"><?php echo ucfirst($row2['name']); ?></td>
                                            <td class="center"><?php echo $row2['email']; ?><br>-> <?php echo $row2['phone']; ?></td>
                                            <td class="center"><?='QAR '.$row['total']; ?></td>
                                            <td class="center"><a style="cursor: pointer;" onClick="getcartdata('<?= $row['id']; ?>');">view</a></td>
                                            <td class="center"><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                            <td class="center">
                                              
                                                    <select id="<?=$row['id']; ?>" class="form-control">
                                                        <option <?php if($row['status']=='pending'){ echo 'selected'; } ?> value="pending">Pending</option>
                                                        <option <?php if($row['status']=='rejected'){ echo 'selected'; } ?> value="rejected">Rejected</option>
                                                        <option <?php if($row['status']=='approved'){ echo 'selected'; } ?> value="approved">Approved</option>
                                                        <option <?php if($row['status']=='on the way'){ echo 'selected'; } ?> value="on the way">Dispatch Order</option>
                                                        <option <?php if($row['status']=='completed'){ echo 'selected'; } ?> value="completed">Complete Order</option>
                                                        
                                                        
                                                    </select>
                                                    <input type="hidden" id="id<?=$row['id']; ?>" value="<?= base64_encode($row['id']); ?>">
                                                    <input type="hidden" id="uid<?=$row['id']; ?>" value="<?= base64_encode($row['user_id']); ?>">
                                                    <input type="hidden" id="aid<?=$row['id']; ?>" value="<?= base64_encode($row['address_id']); ?>">
                                               
                                            </td>

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
            
             <!-----------------Rejected Message------------------->
        
        <!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Reason of Reject Order</h4>
        </div>
        <form name="" action="change_order_status.php" method="post">
        <div class="modal-body">
          <textarea class="form-control" rows="5" placeholder="Reason of Reject Order" name="reason" required><?=$rejectOrderMsg; ?></textarea>
        </div>
        <div class="modal-footer">
            
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="uid" id="uid" value="">
            <input type="hidden" name="aid" id="aid" value="">
            
            <button type="submit" class="btn btn-success" name="submit">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
    </div>
  </div>


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
		<script>
			function getcartdata(orderid,vendorID) {
           

            $.ajax({
                    type: "POST",
                    url: "ajax_cart_data.php",
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

            $('select').on('change', function() {
            var status = this.value;
            var myid = $(this).attr("id");
            var id = $('#id'+myid+'').val();
            var uid = $('#uid'+myid+'').val();
            var aid = $('#aid'+myid+'').val();
            
            var dataString = 'status='+ status + '&id='+ id + '&uid='+ uid + '&aid='+ aid;
            
            if(status=='rejected'){
            $('#id').val(id);
            $('#uid').val(uid);
            $('#aid').val(aid);
               $('#myModal2').modal('show');
            }
            else{
                
            
            
                $.ajax({
                    type: "POST",
                    url: "change_order_status.php",
                    data: dataString,
                    cache: false,
                    beforeSend: function(){
                      //alert(dataString);
                    },
                    complete: function(){
			        //alert(orderid);

                    },
                    success: function(response){

                     window.location.reload(); 

                    }
                });    
            }
        });

        

        </script>


        <!-- Optionally, you can add Slimscroll and FastClick plugins.
             Both of these plugins are recommended to enhance the
             user experience. Slimscroll is required when using the
             fixed layout. -->
    </body>
</html>
