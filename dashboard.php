<?php

session_start();
error_reporting(0);
include('include/db.class.php');
if($_SESSION['LOGIN_ID']=='')
{
   echo "<script>window.location.href='".$WebsiteUrl."/login-register'</script>";
}
?>

<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
<style>
.timetable_sub th:nth-child(1){
	width: 10%;
}
.timetable_sub th:nth-child(2){
	width: 15%;
}
.timetable_sub th:nth-child(3){
	width: 15%;
}
.timetable_sub th:nth-child(4){
	width: 15%;
}
.timetable_sub th:nth-child(5){
	width: 15%;
}
.timetable_sub th:nth-child(6){
	width: 15%;
}
.timetable_sub th:nth-child(7){
	width: 5%;
}
 .timetable_sub td {
    text-align: center !important;
}
 .span2 {
    float: left;
    text-align: left;
    width: 80px;
    height: 80px;
    background: #000;
    border-radius: 90px;
    position: absolute;
    border: 2px solid #333;
    text-align: center;
    font-weight: 600;
    padding-top: 28px;
    color: #fff;
}
.progress-bar{
                        background-color:#000;    
            
        }

</style>
<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl.'/'; ?>">Home</a></strong>  / <span>User DashBoard</span></h2>
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12" id="sign-in-form">
          <div class="">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Order's </span> <strong>History </strong> </h3>
            
      <table class="timetable_sub table-responsive" style="display: inline-block;">
        <thead>
          <tr style="text-align:center;">
            <th>Sno.</th>
            <th>Order#</th>
            <th>Total</th>
            <th>Detail</th>
            <th>Invoice</th>
            <th>Order Status</th>
            <th>Order Date</th>
          </tr>
        </thead>
        <tbody style="text-align:center;">
        
        <?php
$i = 1;
$query = $conn->prepare("select c.id,c.status,c.created_at, p.user_id as vendorId from cart_orders c LEFT JOIN products p ON p.id = c.pid WHERE c.user_id='".$_SESSION['LOGIN_ID']."' order by id desc");
$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC))
 {
$query3 = $conn->prepare("select sum((price*qty)+(ship_charge)) as totalPrice from cart_orders WHERE id='".$row['id']."'");
$query3->execute();
$row3 = $query3->fetch(PDO::FETCH_ASSOC);
?>

                                        <tr>
                                            <td class="center"><?= $i++; ?></td>
                                            <td class="center"><?php echo 'ALZ-'.$row['vendorId'].'-'.$row['id']; ?></td>
                                            <td class="center"><?='QAR '.$row3['totalPrice']; ?></td>
                                            <td class="center"><a style="color: goldenrod;" target="_blank" href="order-details?id=<?= $row['id']; ?>&vid=<?=$row['vendorId']; ?>">View Details</a></td>
                                            <td class="center"><a style="color: goldenrod;" target="_blank" href="invoice.php?id=<?= $row['id']; ?>&vid=<?=$row['vendorId']; ?>">Download</a></td>
                                            <td class="center"><a style="cursor: pointer;color: goldenrod;" onClick="getcartdata2('<?= $row['id']; ?>','<?=$row['vendorId']; ?>','<?=$row['status']; ?>');">View Status</a></td>
                                            <td class="center"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                            

                                        </tr>


<?php } ?>
        </tbody>
      </table>
          </div>
        </div>
        
        
      </div>
    </div>
    
     
</div>
    
  </section>
  <div id="myModal" class="modal fade"  role="dialog" style="z-index: 9999999;">
             <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                <div id="pdfcontent">
                  <div class="modal-header">
                    
                    <h4 class="modal-title orderid"></h4><button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left"  id="printTable">
                   <table class="table table-striped table-responsive">
                       <tbody class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left">
                      <tr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left">
                        <!--- this is for shipping details --->
                         <td align="left" id="shipping_details" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left"></td>
                        <!--- this is for shipping details --->
                      </tr>
                      </tbody>
                  </table>
                  <div id="editor"></div>
                  </div>
                  
                </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="pntbtn">Print <i class="fa fa-print"></i></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
</div>


<?php include('footer.php'); ?>

<script>
			function getcartdata(orderid,vendorID) {
            $.ajax({
                    type: "POST",
                    url: "<?= $WebsiteUrl.'/'; ?>ajax_cart.php",
                    data: {'id':orderid},
                    cache: true,
                    beforeSend: function(){
                      var order = 'Order Details For Order No.<span style="color:red;"> ALZ-'+vendorID+'-'+orderid+'</span>';
                      $('.orderid').html(order);
                    },
                    complete: function(){

                    },
                    success: function(response){
                   // alert(response);
					  document.getElementById("shipping_details").innerHTML=response; 
                      $('#myModal').modal('show');

                    }
                });

        }
        
        function getcartdata2(orderid,vendorID,status) {
            
            var order = 'Order Status For Order No.<span style="color:red;"> ALZ-'+vendorID+'-'+orderid+'</span>';
            $('.orderid2').html(order);
                      
            if(status=='pending'){
                
                response = '<div style="width:90%; "><div class="progress"><div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2" style="background:goldenrod">PENDING</span></div><div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2">APPROVED</span></div><div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2">ON THE<br> WAY</span></div><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2">ORDER<br> DELIVERED</span></div></div></div>'; 
                document.getElementById("statusdata").innerHTML=response; 
                $('#myModal2').modal('show');
            }
            else if(status=='approved'){
                
                response = '<div style="width:90%; "><div class="progress"><div class="progress-bar" role="progressbar" style="width: 33%;background-color:goldenrod" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2" style="background:goldenrod">PENDING</span></div><div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2"  style="background:goldenrod">APPROVED</span></div><div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2">ON THE<br> WAY</span></div><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2">ORDER<br> DELIVERED</span></div></div></div>'; 
                document.getElementById("statusdata").innerHTML=response; 
                $('#myModal2').modal('show');
            }
            else if(status=='on the way'){
                
                response = '<div style="width:90%; "><div class="progress"><div class="progress-bar" role="progressbar" style="width: 33%;background-color:goldenrod" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2" style="background:goldenrod">PENDING</span></div><div class="progress-bar" role="progressbar" style="width: 33%;background-color:goldenrod" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2"  style="background:goldenrod">APPROVED</span></div><div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2"  style="background:goldenrod">ON THE<br> WAY</span></div><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2">ORDER<br> DELIVERED</span></div></div></div>'; 
                document.getElementById("statusdata").innerHTML=response; 
                $('#myModal2').modal('show');
            }
            else if(status=='completed'){
                
                response = '<div style="width:90%; "><div class="progress"><div class="progress-bar" role="progressbar" style="width: 33%;background-color:goldenrod" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2" style="background:goldenrod">PENDING</span></div><div class="progress-bar" role="progressbar" style="width: 33%;background-color:goldenrod" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2"  style="background:goldenrod">APPROVED</span></div><div class="progress-bar" role="progressbar" style="width: 33%;background-color:goldenrod" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2"  style="background:goldenrod">ON THE<br> WAY</span></div><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="span2" style="background:goldenrod">ORDER<br> DELIVERED</span></div></div></div>'; 
                document.getElementById("statusdata").innerHTML=response; 
                $('#myModal2').modal('show');
            }
           

        }
        
        
function printData()
{
   var divToPrint=document.getElementById("printTable");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}

$('#pntbtn').on('click',function(){
printData();
})
		</script>



  <!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog" style="z-index: 9999999;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title orderid2"></h4><button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="statusdata">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
