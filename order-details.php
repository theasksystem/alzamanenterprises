<?php

session_start();
error_reporting(0);
include('include/db.class.php');
include('include/functions.php');

if($_SESSION['LOGIN_ID']=='')
{
   echo "<script>window.location.href='".$WebsiteUrl."/login-register'</script>";
}

if(isset($_GET['id']) && $_GET['id']!=''){

  $id = $_GET['id'];

    $orderdata = OrderDetails($conn,$id);
}
?>

    <link href="<?= $WebsiteUrl.'/'; ?>css/bootstrap.css" rel="stylesheet" />
<style>
    .maintopwrapper.thissection .bottom {
    border-width: 0 0 180px 81.5vw !important;
}

.footerbootom {
    border-width: 0 0 235px 88.7vw !important;
    left: -9% !important;
}
.footer {
    height: 217px !important;
    overflow: hidden !important;
}
.col-md-4.commontext h3, .col-md-4.commontext h4, .col-md-4.commontext label{
    text-align: left;
    margin-left: 6%;
    font-size: large;
    font-weight: 700;
    width: 100%;
}
.col-md-6.leftheaderheading h2 {
    font-size: 62px;
    text-align: left;
}
.invoice-table th  {
    border: 2px solid #daa51e;
    text-align:center;
    padding:5px;
}
.invoice-table td  {
    border-right: 2px solid #daa51e;
    text-align:center;
    font-size: 18px;
    font-weight:450;
    padding:5px;
}
.invoice-total-table table {
    border-collapse: collapse;
}
.invoice-total-table td{
    border: 1px solid #daa51e;
    padding:10px;
    padding-top:7px;
    padding-bottom: 7px;
}
</style>

                <div class="modal-content col-md-12">
                
                  <div class="container" id="content" style="overflow:hidden;">
                   <?=$orderdata; ?>
                  
                </div>
                
                  
                </div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</body>
</html>
