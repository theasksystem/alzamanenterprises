<?php

$userDetails = $conn->prepare("select * from tbl_admin where id = :adm_id");
$userDetails->bindValue(':adm_id', $_SESSION['VENDOR_ID'], PDO::PARAM_STR);
$userDetails->execute();
$userDetailsFetch = $userDetails->fetch(PDO::FETCH_ASSOC);

?>
<section class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image"><img src="<?php if($userDetailsFetch['image']!=''){ echo $WebsiteUrl.'/admin/uploads/'.$userDetailsFetch['image']; }else{ echo 'images/logo2.png'; }; ?>" class="img-circle" alt="User Image"></div>
    <div class="pull-left info" style="padding-top:16px;">
      <p>
        <a href="home-user.php"><?= $userDetailsFetch['username']; ?></a>
      </p>
      <!-- Status -->
      <span>&nbsp;</span> </div>
  </div>
  <!-- Sidebar Menu -->
  <ul class="sidebar-menu">
    <!--<li class="header">MENU</li>-->
    <!-- Optionally, you can add icons to the links -->
    <li class="treeview"><a href="product-user.php"><i class="fa fa-fw fas fa-home"></i><span>Products</span></a></li> 
    <li class="treeview"><a href="new_orderss.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>New Orders</span></a></li>
    <li class="treeview"><a href="approved_orderss.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Approved Orders</span></a></li>
	<li class="treeview"><a href="orders_lists.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Orders History</span></a></li>
	<li class="treeview"><a href="reject_lists.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Reject Orders History</span></a></li> 
    <li class="treeview"><a href="commission.php"><i class="fa fa-percent" aria-hidden="true"></i> <span>Products Commission</span></a></li> 
    <li class="treeview"><a href="view-coupan.php"><i class="fa fa-barcode"></i> <span>Coupan Code</span></a></li>
   
    
    </ul>
  <!-- /.sidebar-menu -->
</section>
