<?php
//ini_set("display_errors", "1");
error_reporting(0);
session_start();

if ($_SESSION['USER_ID'] == '') {
    echo "<script>window.location='index.php';</script>";
}
include'functions/db.class.php';

$id = base64_decode($_GET['id']);

if (isset($_GET['visible'])) {
     $result = $conn->prepare("update  department set visible = :visible  where id = :id");
	 $result->bindParam(':visible', $_GET['visible'], PDO::PARAM_INT);
	 $result->bindParam(':id', $id, PDO::PARAM_INT);
	 $result->execute();
	 echo "<script>window.location='department.php';</script>";
}

if (isset($_GET['delete']) == 'y')
{
   	$delCenter = $conn->prepare("DELETE FROM `department` WHERE `id` = :id");

	$delCenter->bindParam(':id', $id, PDO::PARAM_INT);

	$delCenter->execute();

     echo "<script>window.location='department.php';</script>";
  
}

?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>All | Tab List</title>
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



        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
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
                    <h1>Tab List</h1>
                    <ol class="breadcrumb">
                        <li><a href="add_department.php" title="ADD NEW"><img src="img/plus.png" height="50" width="50" /></a></li>

                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Tab List</h3>
                        </div>
                        <!-- /.box-header -->
                        <?php if (isset($msg)) echo $msg; ?>
                        <div class="box-body table-responsive">
                            <table id="example1" width="100%" class="table table-bordered table-striped">
                                <thead>
                                   <tr>
                                        <th>S No.</th>
                                        <th>Tab Name</th>
                                        <th>Visibility</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                               <?php
								$i=0;
                                     $query = $conn->prepare("select * from  department order by id desc");
										$query->execute();

									while ($row = $query->fetch(PDO::FETCH_ASSOC))
                                     {
										$i++;
										//echo $row['name_ar']
                               ?>

										<tr>
                                           <td class="center"><?php echo $i; ?></td>
                                           <td class="center"><?php echo $row['name_en']; ?><br><?php echo $row['name_ar']; ?></td>
                                           <td class="center">
                                                <?php if ($row['visible'] == 1) { ?>
                                                   <a href="?visible=0&id=<?php echo base64_encode($row['id']); ?>"  title="ACTIVE"><img src="img/active.png" height="30" width="30" /></a>

                                                <?php } else { ?>

                                                   <a href="?visible=1&id=<?php echo base64_encode($row['id']); ?>"  title="INACTIVE"><img src="img/inactive.png" height="30" width="30" /></a>

                                                <?php } ?>
                                            </td>


                                            <td class="center">
                                                <span style="float: left;">
                                                    <a href="add_department.php?id=<?php echo base64_encode($row['id']); ?>&edit=y" title="EDIT"><img src="img/edit.png" height="30" width="30" /></a> <a onClick="return confirm('Sure you want to delete this record.');"
                                                                                href="?id=<?php echo base64_encode($row['id']); ?>&delete=y"  title="DELETE"><img src="img/delete.png" height="30" width="30" /></a>
                                                </span>
                                            </td>

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
 <script>
		function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		} 
		
		function saveToDatabase(editableObj,column,id) {
			$(editableObj).css("background","#FFF url(../images/Preloader_2.gif) no-repeat right");
			$.ajax({
				url: "saveedit.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
				success: function(data){
				window.location.reload(); 
					$(editableObj).css("background","#FDFDFD");
					
				}        
		   });
		}
</script>

    </body>
</html>
