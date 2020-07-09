<?php

session_start();
error_reporting(0);
include'functions/db.class.php';

if ($_SESSION['USER_ID'] == '') {
    echo "<script>window.location='index.php';</script>";
}

$id = base64_decode($_GET['id']);

if (isset($_GET['visible'])) {
     $result = $conn->prepare("update tbl_admin set vis = :visible  where id = :id and id!=1");
	 $result->bindParam(':visible', $_GET['visible'], PDO::PARAM_INT);
	 $result->bindParam(':id', $id, PDO::PARAM_INT);
	 $result->execute();

    echo "<script>window.location='user.php';</script>";
}


if(isset($_REQUEST['delete']) && $_REQUEST['delete']=='y'){

	$delUser = $conn->prepare("DELETE FROM `tbl_admin` WHERE `id` = :id and id!=1");
	$delUser->bindParam(':id', $id, PDO::PARAM_INT);
	$delUser->execute();

	echo "<script>window.location='user.php';</script>";

}


if(isset($_REQUEST['send']) && $_REQUEST['send']=='y'){

  $qryyy=$conn->prepare("select * from `tbl_admin` where id = '$id' and vis=1");
  $qryyy->execute();
  $qry=$qryyy->fetch(PDO::FETCH_ASSOC);

  $uname=$qry['email'];
  $pass=substr(uniqid(),0,5);
  $myname=$qry['name'];
  $id = $qry['id'];
  $ProductUrl = $WebsiteUrl.'/new-password-vendor?uid='.base64_encode(base64_encode($qry['id'])).'&res='.base64_encode(base64_encode($pass));

////////////////////////////////////Admin Message/////////////////////////////////////////////////


$message2='<table width="100%" cellspacing="0" cellpadding="20" border="0">
                             <tbody>
								<tr>
								  <td><table style="border:1px solid #ddd" align="center" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
									  <tbody>
										<tr>
										  <td><table width="100%" cellspacing="0" cellpadding="0" bgcolor="#000">
											  <tbody>
												<tr>
												  <td style="padding:20px 30px" valign="center" height="88" bgcolor="#000"><img title="'.$WebsiteTitle.'" src="'.$WebsiteUrl.'/'.$logo2.'" alt="'.$WebsiteTitle.'" class="CToWUd" style="width:400px"></td>
												  <td style="font-family:Georgia,serif;font-size:14px;color:#999;font-style:italic;border-bottom:1px solid #f5f5f5" valign="center" bgcolor="#F0F0F0"></td>
												</tr>
											  </tbody>
											</table>
										  </td>
            							</tr>';
           $message2.='<tr>
                      <td style="border-bottom:1px solid #eaebea">
					    <div style="line-height:21px;font-size:14px;color:#444;padding:30px;font-family:Georgia,serif;background:white;border-top:1px solid #ccc">

					      <p>Congratulation dear '.$myname.' your store Have been successfully approved</p>
						  <p>Your OTP : '.$pass.'</p>
						  <p>Here is a link to reset the password. <a href='.$ProductUrl.'>Click Here</a>
						  <p>Team – '.$WebsiteTitle.'</p><br>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#f7f7f7;color:#888">Sent on '.date('d-m-Y').'. &nbsp;&nbsp;Have questions? Email to&nbsp; <a href="'.$messagefooterEmail.'" target="_blank">'.$messagefooterEmail.'</a></td>
                      </tr></tbody></table></td></tr></tbody></table>';


//////////////////////////////////////End ////////////////////////////////////////
		
		$headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		$to=$uname;

	if($uname != ''){
		
		mail($to, "Store Approved", $message2, $headers);
		
			echo '<script>alert("Send message successfully !!")</script>';
			echo '<script>window.location.href="user.php"</script>';
		
	}else{
		
			echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';
		
	}

}

$query = $conn->prepare("select * from tbl_admin where type='Vendor'  order by id desc");
$query->execute();
$productCount = $query->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>All | Vendors list</title>
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
      <h1>Vendors</h1>
      <ol class="breadcrumb">
        <li><a href="add_user.php" title="ADD NEW"><img src="img/plus.png" height="50" width="50" /></a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Vendors List <button class="btn btn-primary" style="margin-left:10px">Total Vendors - <?=$productCount; ?></button></h3>
        </div>
        <!-- /.box-header -->
        <?php if (isset($msg)) echo $msg; ?>
        <div class="box-body table-responsive">
          <table id="example1" width="100%" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>S No.</th>
                <th>Name & Code</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Store Name</th>
                <th>Storage</th>
                <th>Commission</th>
                <th>Visibility</th>
                <th>Send Email</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
				
				$i=1;
					 
					

					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					{
					
					$query2 = $conn->prepare("select * from products where user_id='".$row['id']."'  order by id desc");
					$query2->execute();
					$TotalproductCountbyVendor = $query2->rowCount();
					
			  ?>
              <tr>
                <td class="center"><?php echo $i++; ?></td>
                <td class="center"><?php echo $row['username']; ?><br><b><small>Code - </small></b><?php echo $row['id']; ?></td>
                <td class="center"><?php echo $row['email']; ?></td>
                <td class="center"><?php echo $row['mobile']; ?></td>
                <td class="center"><a href="product-vendor.php?vendor=<?=base64_encode(base64_encode($row['id']));  ?>" target="_blank"><?php echo $row['company']; ?></a>(<?=$TotalproductCountbyVendor; ?>)</td>
                <td class="center"><?php echo $row['store_storage']; ?></td>
                <td class="center"><?php echo $row['commision']; ?></td>
                <td class="center"><?php if ($row['vis'] == 1) { ?>
                  <a href="?visible=0&id=<?php echo base64_encode($row['id']); ?>"  title="ACTIVE"><img src="img/active.png" height="30" width="30" /></a>
                  <?php } else { ?>
                  <a href="?visible=1&id=<?php echo base64_encode($row['id']); ?>"  title="INACTIVE"><img src="img/inactive.png" height="30" width="30" /></a>
                  <?php } ?>
                </td>
                
                <td class="center"><a href="?id=<?php echo base64_encode($row['id']); ?>&send=y" title="SEND EMAIL TO VENDOR"><button class="btn btn-success">Send</button></td>
                <td class="center"><span style="float: left;"> <a href="add_user.php?id=<?php echo base64_encode($row['id']); ?>&edit=y" title="EDIT"><img src="img/edit.png" height="30" width="30" /></a> <a onClick="return confirm('Sure you want to delete this record.');"
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

function sendEmail(val){
    
    $.ajax({
	 type: 'post',
	 url: 'image_delete.php',
	 data: {vendor_id:val},
	 success: function (response) {
	    if(response==1){
	     alert("Email Send to the vendor Successfully");
	 }else{
	     alert("Sorry Some error. please check the store status..");
	 }
	 }
	 });
}
</script>

</body>
</html>
