<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

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
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>اكتمل  الطلب بنجاح</span></h2>
 
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
        <div class="col-md-12 col-sm-12" id="sign-in-form">
          <div class="headline text-right">
            <h3 class="fsz-25 ptb-15"><span class="light-font"> </span> <strong>شكرا لك  </strong> </h3>
            <h3>تم قبول طلبك ، وسيتواصل معك الموظف المسؤول قريبًا</h3>
								
								<div class="view-all-button float-left">
									<a href="<?= $WebsiteUrl2; ?>"> الرئيسية</i></a>
								</div>
          </div>
        </div>
        
        
      </div>
    </div>
  </section>
</div>

<?php include('footer.php'); ?>

<!-- Jquery for login register -->

</body>
</html>
