<?php
//error_reporting(0);
session_start();
include'functions/db.class.php';

if($_SESSION['VENDOR_ID']=='')
{
  echo "<script>window.location.href='../vendors'</script>";
}

$startmonth = date('Y-m-').'01';
$currentmonth = date('Y-m-d');

$totalsales = $conn->prepare("select sum((price*qty)+(ship_charge*qty)) as total_sale from cart_orders where status='completed' and pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')");
$totalsales->execute();
$totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
$totalsaleamt['total_sale'] ? $totalsaleamtt = $totalsaleamt['total_sale'] : $totalsaleamtt = '0.00';

$totalsalesmonth = $conn->prepare( "select sum((price*qty)+(ship_charge*qty)) as total_sale from cart_orders where status='completed' and created_at >='$startmonth' and created_at <= '$currentmonth' and pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')");
$totalsalesmonth->execute();
$totalsalemonthamt = $totalsalesmonth->fetch(PDO::FETCH_ASSOC);
$totalsalemonthamt['total_sale'] ? $totalsalemont = $totalsalemonthamt['total_sale'] : $totalsalemont = '0.00';

$totalProducts2 = $conn->prepare( "select count(*) as all_product from products where user_id='".$_SESSION['VENDOR_ID']."'");
$totalProducts2->execute();
$totalProducts = $totalProducts2->fetch(PDO::FETCH_ASSOC);

$neworders = $conn->prepare( "select count(*) as new_orders from cart_orders where status='pending' and pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')");
$neworders->execute();
$neworderss = $neworders->fetch(PDO::FETCH_ASSOC);

$approvedorders = $conn->prepare( "select count(*) as approved_orders from cart_orders where status='approved' and pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')");
$approvedorders->execute();
$approvedorders = $approvedorders->fetch(PDO::FETCH_ASSOC);

$completeorders = $conn->prepare( "select count(*) as compete_orders from cart_orders where status='completed' and pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')");
$completeorders->execute();
$completeorderss = $completeorders->fetch(PDO::FETCH_ASSOC);

$rejectorders = $conn->prepare( "select count(*) as rejected_orders from cart_orders where status='rejected' and pid IN(select id from products where user_id='".$_SESSION['VENDOR_ID']."')");
$rejectorders->execute();
$rejectorderss = $rejectorders->fetch(PDO::FETCH_ASSOC);

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
  <title><?=$WebsiteTitle; ?> | DASHBOARD</title>
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

  <!-- Morris charts -->
  <link rel="stylesheet" href="plugins/morris/morris.css">

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
  
  <style>
.small-box>.inner {
    padding: 10px;
    height: 115px !important;
}
      
  </style>

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
    <a href="home-user.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?= $logo; ?></span>
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
      <h1><i class="glyphicon glyphicon-dashboard"></i>&nbsp;&nbsp; Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="home-user.php"><i class="fa fa-dashboard"></i>Home</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">


        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-basket"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Sales</span>
              <span class="info-box-number">QAR <?= $totalsaleamtt; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-shopping-basket"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Sale This Month</span>
              <span class="info-box-number">QAR<?= $totalsalemont; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-product-hunt" aria-hidden="true"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Products</span>
              <span class="info-box-number"><?= $totalProducts['all_product']; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
       
</div>
<div class="row">

          
        <div class="col-lg-3 col-xs-6">
		
          <!-- small box -->
          <div class="small-box bg-aqua">
		  
            <div class="inner">
              <h3><?= $neworderss['new_orders']; ?></h3>

              <p><a href="new_orderss.php" style="color:#fff;">New Orders</a></p>
            </div>
			
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="new_orderss.php" class="small-box-footer">
            </a>
          </div>
		
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
		
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $approvedorders['approved_orders']; ?></h3>

              <p><a href="approved_orderss.php" style="color:#fff;">Approved Orders</a></p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="approved_orderss.php" class="small-box-footer">

            </a>
          </div>
		
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
		  <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $completeorderss['compete_orders']; ?></h3>

              <p><a href="orders_lists.php" style="color:#fff;">Total Complete Orders</a></p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="orders_lists.php" class="small-box-footer">
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $rejectorderss['rejected_orders']; ?></h3>

              <p><a href="reject_lists.php" style="color:#fff;">Total Reject Orders</a></p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="reject_lists.php" class="small-box-footer">
            </a>
          </div>
        </div>
        <!-- ./col -->

      </div>
      <!-- /.row -->


      <div class="row">


        <!-- /.col (LEFT) -->
        <div class="col-md-12">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Sales</h3>

              <div class="box-tools pull-right">
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="line-chart" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->




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

<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script src="plugins/morris/morris.min.js"></script>

<script>
  $(function () {
    "use strict";

    // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
        {y: '2011 Q1', item1: 2666},
        {y: '2011 Q2', item1: 2778},
        {y: '2011 Q3', item1: 4912},
        {y: '2011 Q4', item1: 3767},
        {y: '2012 Q1', item1: 6810},
        {y: '2012 Q2', item1: 5670},
        {y: '2012 Q3', item1: 4820},
        {y: '2012 Q4', item1: 15073},
        {y: '2013 Q1', item1: 10687},
        {y: '2013 Q2', item1: 8432}
      ],
      xkey: 'y',
      ykeys: ['item1'],
      labels: ['Item 1'],
      lineColors: ['#3c8dbc'],
      hideHover: 'auto'
    });
  });
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
	 
</body>
</html>
