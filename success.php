<?php

session_start();
error_reporting(0);
include('include/db.class.php');

$maincartquerydata = $conn->prepare("DELETE FROM `cart` WHERE un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
$maincartquerydata->execute();
$maincartquerydata2 = $conn->prepare("DELETE FROM `apply_coupan` WHERE un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
$maincartquerydata2->execute();
unset($_SESSION['UNIQUEID']);
unset($_SESSION['orderid']);
unset($_SESSION['cartTotal']);
unset($_SESSION['ADDRESS']);

?>

<?php include('header.php'); ?>

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl.'/'; ?>">Home</a></strong>  / <span>Order Complete</span></h2>
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
        <div class="col-md-12 col-sm-12" id="sign-in-form">
          <div class="headline">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Thank </span> <strong>You </strong> </h3>
            <h3>Your order is accepted, Our executive will contact you shortly.</h3>
								
								<div class="view-all-button">
									<a href="index.php"> Back to home page</i></a>
								</div>
          </div>
        </div>
        
        
      </div>
    </div>
  </section>
</div>

<?php include('footer.php'); ?>

</body>
</html>
