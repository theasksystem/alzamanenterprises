<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<footer>
  <div class="footer-gray">
    <div class="footer-custom">
      <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-6 text-center">
          <h6 class="ftr-hdr">COMPANY</h6>
          <ul class="ftr-links-sub">
            <li><a href="<?= $WebsiteUrl.'/'; ?>about-us">About Us</a></li>
            <li><a href="<?= $WebsiteUrl.'/'; ?>contact-us" >Contact Us</a></li>
            <li><a href="company.php#Careers" >Careers</a></li>
          </ul>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6 text-center">
          <h6 class="ftr-hdr">HELP</h6>
          <ul class="ftr-links-sub">
            <li><a href="help.php#payment" >Payments</a></li>
            <li><a href="help.php#shipping" >Shipping</a></li>
            <li><a href="help.php#cancellation" >Cancellation</a></li>
            <!--<li><a href="help.php#faq" >FAQ</a></li>-->
            <!--<li><a href="help.php#report" >Report Infringement</a></li>-->
          </ul>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6 text-center">
          <h6 class="ftr-hdr">POLICY</h6>
          <ul class="ftr-links-sub">
            <li><a href="policy.php#Return" >Return Policy</a></li>
            <li><a href="policy.php#Term">Term of Use</a></li>
            <li><a href="policy.php#Security">Security</a></li>
            <li><a href="policy.php#Privacy">Privacy</a></li>
            <li><a href="policy.php#Site">Site Map</a></li>
            <!--<li><a href="policy.php#Compliance">EPR Compliance</a></li>-->
          </ul>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6 text-center">
          <h6 class="ftr-hdr">ALZAMAN BUSINESS</h6>
          <ul class="ftr-links-sub">
            <li><a href="javascript:;" rel="nofollow">Online Shopping</a></li>
            <li><a href="vendors" rel="nofollow">Sell With Us</a></li>
          </ul>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 text-center">
          <h6 class="ftr-hdr">Sign up for exclusive offers and inspiration</h6>
          <div id="ftr-email" class="ftr-email-form">
            <form id="ftrEmailForm" name="reg">
              <div class="error">Please enter a valid email address</div>
              <input type="text" id="ftrEmailInput" name="email" class="input subscribeEmailButton" placeholder="Enter email address" />
              <input type="button" class="button ntt-btn subscribebutton" id="subscribebutton" value="SUBMIT" />
              <img id="subsloader" style="display: none; height:50px;" src="<?=$WebsiteUrl.'/'; ?>images/loader.gif">
			  <div class="subcr text-right"></div>
            </form>
          </div>
          <div class="footer-me">
            <h6 class="ftr-hdr">Follow Us</h6>
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
    <div class="design">Copyright © 2019 alzamanenterprise. All Rights Reserved </div>
  </div>
</footer>


</div>



<div class="myquickView"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="<?= $WebsiteUrl.'/'; ?>js/jquery-ui.js"></script>
<script src="<?= $WebsiteUrl.'/'; ?>js/bootstrap.js" type="text/javascript"></script>
<script src="<?= $WebsiteUrl.'/'; ?>js/new/owl.carousel.min.js"></script>
<script src="<?= $WebsiteUrl.'/'; ?>js/menu.js"></script>
<script src="<?= $WebsiteUrl.'/'; ?>js/my-custom.js"></script> 

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

<script type="text/javascript" src="<?= $WebsiteUrl.'/'; ?>js/typeahead.js"></script>

	
<script>

$(document).ready(function () {
    
        $('#myInput').typeahead({
            source: function (query, result) {
               // alert(query);
                $.ajax({
                    url: "<?=$WebsiteUrl2.'/'; ?>server.php",
					data: 'query=' + query,   
                    type: "POST",
                    success: function (data) {
                        
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
                    url: "<?=$WebsiteUrl.'/'; ?>getvalue.php",
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
<script type="text/javascript">
// function myFunction() {
//   document.getElementById("myDropdown").classList.toggle("show");
// }

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}</script>

    <script src="<?= $WebsiteUrl.'/'; ?>js/new/jquery.slicknav.min.js"></script>
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/jquery.nicescroll.min.js"></script>
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/jquery.zoom.min.js"></script>
	<script src="<?= $WebsiteUrl.'/'; ?>js/new/main.js"></script>
