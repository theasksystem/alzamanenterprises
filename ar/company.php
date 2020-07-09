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
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>الشركة</span></h2>
  
  </div>
  <section class="pt-20 pb-40">
    <div class="paragr" id="Careers">
     <h3><p><b style="color:#000;">Careers </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="Stories">
     <h3><p><b style="color:#000;">Alzaman Stories </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
    <div class="paragr" id="Press">
     <h3><p><b style="color:#000;">Press </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
    
  </section>
</div>

<?php include('footer.php'); ?>


</body>
</html>
