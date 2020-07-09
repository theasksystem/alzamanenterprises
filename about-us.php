<?php

session_start();
error_reporting(0);
include('include/db.class.php');

?>
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl.'/'; ?>">Home</a></strong>  / <span>About Us</span></h2>
  </div>
  <section class="pt-20 pb-40">
    <div class="paragr">
      <p>Al-Zaman Enterprises is the leading e-commerce marketplace in Qatar, launched in 2019. Al-Zaman Enterprises offers an ultra-modern shopping quality by integrating all conceivable needs of the customers under one roof & can be delivered at your destination upon the click. We deal in various products includes high-class in- home application, fashionable cloths, sports collections , Electronics, Mobiles & latest  technological innovative products, perfumes and many international brands which are 100% genuine. Al-Zaman Enterprises has grown into a brand that champion’s digital innovation, has a fiercely independent spirit and inspires its fashion loving customer to experiment with their style.</p>
      <p>Sell today at Al-Zaman Enterprises & Achieve Your Entrepreneur Dreams. We  also provides platform to the dealers a very easy-to-understand, flexible policies to sell their products & starting your business journey with Al-Zaman Enterprises as a seller just by enrolling a simple registration process and enjoy selling your products to the entire country. Hurry up! Don’t miss the opportunity to enroll with Al-Zaman Enterprises and see your business reach staggering heights.</p>
      
      <h3><p><b style="color:#000;">Alzaman Enterprises Vision </b></p></h3>
      <p>To offer the Best E-Shopping experience for our valuable costumers by providing various quality Goods, as well as taking the company to heights of success on GCC level especially in the State of Qatar🇶🇦</p>
      
    </div>
  </section>
</div>

<?php include('footer.php'); ?>
	
</body>
</html>
