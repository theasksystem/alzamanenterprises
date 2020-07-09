<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

?>
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>المساعدة</span></h2>
  
  </div>
  <section class="pt-20 pb-40">
    <div class="paragr" id="payment">
     <h3><p><b style="color:#000;">Payments </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="shipping">
     <h3><p><b style="color:#000;">Shipping </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="cancellation">
     <h3><p><b style="color:#000;">Cancellation </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="faq">
     <h3><p><b style="color:#000;">FAQ </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="report">
     <h3><p><b style="color:#000;">Report Infringement </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
  </section>
</div>

<?php include('footer.php'); ?>

<script>$(document).ready(function(){var owl=$('.owl-carousel');owl.owlCarousel({items:6,loop:true,margin:10,autoplay:true,autoplayTimeout:10000,autoplayHoverPause:true,responsiveClass:true,dots:false,responsive:{360:{items:1,},480:{items:2,},600:{items:3,},1000:{items:6,}}});$('.play').on('click',function(){owl.trigger('play.owl.autoplay',[1000])})
$('.stop').on('click',function(){owl.trigger('stop.owl.autoplay')})})</script>
<script type="text/javascript">
window.onscroll = function() {myFunction()};
var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;
function myFunction() {
  if (window.pageYOffset >= sticky) {
	navbar.classList.add("sticky")
  } else {
	navbar.classList.remove("sticky");
  }
};
</script>
</body>
</html>
