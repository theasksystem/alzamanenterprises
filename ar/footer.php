<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<footer>
  <div class="footer-gray text-right">
    <div class="footer-custom">
      <div class="row">
      
        <div class="col-md-2 col-sm-6 col-xs-6">
          <h6 class="ftr-hdr">الشركة</h6>
          <ul class="ftr-links-sub">
            <li><a href="<?= $WebsiteUrl2.'/'; ?>about-us">عنّا</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>contact-us" >التواصل معنا</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>company.php#Careers" >الوضائف</a></li>
          </ul>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
          <h6 class="ftr-hdr">المساعدة</h6>
          <ul class="ftr-links-sub">
            <li><a href="<?= $WebsiteUrl2.'/'; ?>help.php#payment" >الدفع</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>help.php#shipping" >رسوم الشحن والتوصيل </a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>help.php#cancellation" >الالغاء</a></li>
           <!-- <li><a href="<?= $WebsiteUrl2.'/'; ?>help.php#faq" >FAQ</a></li>-->
            <li><a href="<?= $WebsiteUrl2.'/'; ?>help.php#report" >ابلاغ المخالفة</a></li>
          </ul>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
          <h6 class="ftr-hdr">سياسات</h6>
          <ul class="ftr-links-sub">
            <li><a href="<?= $WebsiteUrl2.'/'; ?>policy.php#Return" >سياسة الارجاع</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>policy.php#Term">شروط الاستخدام</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>policy.php#Security">سياسة الامان</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>policy.php#Privacy">سياسة الخصوصية</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>policy.php#Site">Site Map</a></li>
            <!--<li><a href="<?= $WebsiteUrl2.'/'; ?>policy.php#Compliance">EPR Compliance</a></li>-->
          </ul>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
          <h6 class="ftr-hdr">معلومات عن الزمان</h6>
          <ul class="ftr-links-sub">
            <li><a href="javascript:;" rel="nofollow">تسوق الالكتروني</a></li>
            <li><a href="<?= $WebsiteUrl2.'/'; ?>vendors" rel="nofollow">بع مع الزمان المشاريع</a></li>
          </ul>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
          <h6 class="ftr-hdr"> الاشتراك للحصول على العروض الحصرية</h6>
          <div id="ftr-email" class="ftr-email-form">
            <form id="ftrEmailForm" name="reg">
              <div class="error">Please enter a valid email address</div>
              <input type="text" id="ftrEmailInput" name="email" class="input subscribeEmailButton" placeholder="البريد الالكتروني" />
              <input type="button" class="button ntt-btn subscribebutton" id="subscribebutton" value="تقديم الطلب" />
              <img id="subsloader" style="display: none; height:50px;" src="<?=$WebsiteUrl.'/'; ?>images/loader.gif">
			  <div class="subcr text-right float-right"></div>
            </form>
          </div>
          <div class="footer-me">
            <h6 class="ftr-hdr">تابعونا</h6>
            <ul>
              <li> <a href="https://www.facebook.com/alzamanenterprises/" title="Facebook" target="_blank"> <i class="fab fa-facebook"></i> </a> </li>
              <li> <a href="https://www.snapchat.com/add/alzamanent" title="Snapchat" target="_blank"> <i class="fab fa-snapchat"></i> </a> </li>
              <li> <a href="https://bit.ly/2KOyLXS" title="WhatsApp" target="_blank"> <i class="fab fa-whatsapp"></i> </a> </li>
              <li> <a href="https://www.youtube.com/channel/UCUxNO17lH22_mWF0nCVGpAA?view_as=subscriber" target="_blank" title="Youtube"> <i class="fab fa-youtube"></i> </a> </li>
              <li> <a target="_blank" href="https://instagram.com/alzamanenterprises?igshid=13zqn9scvq0nh" title="Instagram"> <i class="fab fa-instagram"></i> </a> </li>
            </ul>
          </div>
          <div class="ftr-email-privacy-policy"></div>
        </div>
        
      </div>
    </div>
    <div class="design">جميع الحقوق محفوظة © 2019 alzamanenterprise</div>
  </div>
</footer>


<div class="myquickView"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="<?= $WebsiteUrl2.'/'; ?>js/jquery-ui.js"></script>
<script src="<?= $WebsiteUrl2.'/'; ?>js/bootstrap.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="<?= $WebsiteUrl2.'/'; ?>js/new/owl.carousel.min.js"></script>
<script src="<?= $WebsiteUrl2.'/'; ?>js/menu.js"></script>
<!--<script src="<?= $WebsiteUrl2.'/'; ?>js/smooth-scroll.js"></script>-->
<script src="<?= $WebsiteUrl2.'/'; ?>js/my-custom.js"></script>
<script type="text/javascript" src="<?= $WebsiteUrl.'/'; ?>js/typeahead.js"></script>

<script>
const $menu = $('#mySidenav');

$(document).mouseup(e => {
   if (!$menu.is(e.target) // if the target of the click isn't the container...
   && $menu.has(e.target).length === 0) // ... nor a descendant of the container
   {
     $menu.removeClass('mySidenav');
  }
 });

$('.toggle').on('click', () => {
  $menu.toggleClass('mySidenav');
});
$('.closebtn').on('click', () => {
  $menu.removeClass('mySidenav');
});

</script>	
<script>

$(document).ready(function () {
        $('#myInput').typeahead({
            source: function (query) {
               // alert(query);
                $.ajax({
                    url: "<?=$WebsiteUrl2.'/'; ?>server.php",
					data: 'query2=' + query, 
                    type: "POST",
                    success: function (data) {
                        //alert(data);
                      $('#myInput2').html(data);  
					
                    }
                });
            }
        });
    });




$('#subscribebutton').click(function(){ 

     var regEmail = /^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)$/;
	 var subscribeEmail = $(".subscribeEmailButton").val();
	$(".subcr").html('');
	 if(subscribeEmail=='')
      {
		$(".subscribeEmailButton").css({'border':'red 1px solid','background-color':'white'});
      }
      else
      {
            $.ajax({
                    type: "POST",
                    url: "<?=$WebsiteUrl2.'/'; ?>getvalue.php",
                    data: {'subscribeEmail':subscribeEmail},
                    cache: false,
                    beforeSend: function(){
                        
                        $( "#subsloader" ).show();
                    },
                    complete: function(){

                    },
                    success: function(data){
                        
                        setTimeout(function() {                                          
                          $( "#subsloader" ).hide();
                          $(".subcr").html(data);
                    }, 2000);

                    }
                });
	  }           
});
$(function () {
  $("#datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true
  });
});




</script>

    <script src="<?= $WebsiteUrl2.'/'; ?>js/new/jquery.slicknav.min.js"></script>
	<script src="<?= $WebsiteUrl2.'/'; ?>js/new/jquery.nicescroll.min.js"></script>
	<script src="<?= $WebsiteUrl2.'/'; ?>js/new/jquery.zoom.min.js"></script>
	<script src="<?= $WebsiteUrl2.'/'; ?>js/new/main.js"></script>
