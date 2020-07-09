<?php

$userDetails = $conn->prepare("select * from tbl_admin where id = :adm_id");
$userDetails->bindValue(':adm_id', $_SESSION['USER_ID'], PDO::PARAM_STR);
$userDetails->execute();
$userDetailsFetch = $userDetails->fetch(PDO::FETCH_ASSOC);

?>
<section class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel">
    <div class="pull-left image"><img src="<?php if($userDetailsFetch['image']!=''){ echo 'uploads/'.$userDetailsFetch['image']; }else{ echo 'images/logo2.png'; }; ?>" class="img-circle" alt="User Image"></div>
    <div class="pull-left info" style="padding-top:16px;">
      <p>
        <a href="home.php"><?= $userDetailsFetch['username']; ?></a>
      </p>
      <!-- Status -->
      <span>&nbsp;</span> </div>
  </div>
  <!-- Sidebar Menu -->
  <ul class="sidebar-menu">
  
    <li class="treeview"><a href="department.php"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Tabs</span></a></li>
    <li class="treeview"><a href="subdepartment.php"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Category</span></a></li>
    <li class="treeview"><a href="sub_cat.php"><i class="fa fa-list-alt" aria-hidden="true"></i> <span>Sub Category</span></a></li>  <!-- 
    <li class="treeview"><a href="view-brand.php"><i class="fa fa-fw fas fa-home"></i><span>Brand</span></a></li>-->
    <li class="treeview"><a href="view-size.php"><i class="fa fa-list-alt"></i> <span>Size</span></a></li>
    <li class="treeview"><a href="view-color.php"><i class="fa fa-creative-commons"></i> <span>Color</span></a></li>
    <li class="treeview"><a href="product.php"><i class="fa fa-product-hunt" aria-hidden="true"></i> <span>Admin Products</span></a></li>    
    <li class="treeview"><a href="product-vendor.php"><i class="fa fa-product-hunt" aria-hidden="true"></i> <span>Vendor Products</span></a></li>
    <li class="treeview"><a href="user.php"><i class="fa fa-fw fas fa-user"></i><span>Vendors</span></a></li>
    <li class="treeview"><a href="view-coupan.php"><i class="fa fa-barcode"></i> <span>Coupan Code</span></a></li>
    <li class="treeview">
          <a href="#"><i class="fa fa-fw fa-map"></i> <span>Shipping Locations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="country_list.php">Country</a></li>
            <li><a href="state_list.php">State</a></li>
            <li><a href="city_list.php">City</a></li>
			          </ul>
    </li>
    <li class="treeview"><a href="registered-user.php"><i class="fa fa-fw fas fa-user"></i><span>Registered User</span></a></li>
    <li class="treeview"><a href="new_orders.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>New Orders</span></a></li>
    <li class="treeview"><a href="approved_orders.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Approved Orders</span></a></li>
    <li class="treeview"><a href="dispatch-order.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Dispatch Orders</span></a></li>
	<li class="treeview"><a href="orders_list.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Orders History</span></a></li>
	<li class="treeview"><a href="reject_list.php"><i class="fa fa-fw fas fa-shopping-cart"></i> <span>Reject Orders History</span></a></li>
    <li class="treeview"><a href="review.php"><i class="fa fa-comments" aria-hidden="true"></i> <span>Product Reviews</span></a></li>
    <li class="treeview"><a href="commission.php"><i class="fa fa-percent" aria-hidden="true"></i> <span>Products Commission</span></a></li>
    <li class="treeview"><a href="notification.php"><i class="fa fa-bell" aria-hidden="true"></i> <span>Notification</span></a></li>
    <li class="treeview"><a href="enquiry.php"><i class="fa fa-comment" aria-hidden="true"></i> <span>Contact Enquiries</span></span></a></li>
    <li class="treeview"><a href="subscriber.php"><i class="fa fa-user-plus" aria-hidden="true"></i> <span>Subscribers</span></span></a></li>
    
    </ul>
  <!-- /.sidebar-menu -->
</section>
