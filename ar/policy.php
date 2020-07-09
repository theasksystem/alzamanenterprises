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
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>سياسات</span></h2>
 
  </div>
  <section class="pt-20 pb-40">
    <div class="paragr" id="Return">
     <h3><p><b style="color:#000;">Return Policy </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="Term">
     <h3><p><b style="color:#000;">Term of Use </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="Security">
     <h3><p><b style="color:#000;">Security </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="Privacy">
     <h3><p><b style="color:#000;">Privacy </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="Site">
     <h3><p><b style="color:#000;">Site Map </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    <div class="paragr" id="Compliance">
     <h3><p><b style="color:#000;">EPR Compliance </b></p></h3>
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
