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
.invoice-total-table td,tr {
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


              
              <button type="button" class="btn btn-danger" id="cmd" onclick="CreatePDFfromHTML()">Download</button>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>
//Create PDf from HTML...
$(document).ready(function(){
function CreatePDFfromHTML() {
	$("#cmd").hide();
    var HTML_Width = $("body").width();
    var HTML_Height = $("body").height();
	//alert(HTML_Width);
	//alert(HTML_Height);
    var top_left_margin = 1;
    var PDF_Width = HTML_Width + (top_left_margin * 1);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 1);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($("body")[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*1),canvas_image_width,canvas_image_height);
        }
        pdf.save("Invoice-ALZ-<?=$_GET['vid'].'-'.$_GET['id']; ?>.pdf");
        
		$("#cmd").show();
    });
}

CreatePDFfromHTML();

$('#cmd').click(function(){

	CreatePDFfromHTML();

});
});
</script>



<!-- Jquery for login register -->

</body>
</html>
