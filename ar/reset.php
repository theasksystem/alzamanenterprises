<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

$uid = base64_decode(base64_decode($_GET['uid']));
$otp = base64_decode(base64_decode($_GET['res']));

if(isset($_POST['update']))
{

	$pass = trim($_POST["pass"]);
	$cpass = trim($_POST["cpass"]);
	$notp = trim($_POST["notp"]);
		
	if($otp!=$notp){
      
	 $errmsg = 'You Entered Wrong Otp';
	}
	elseif($pass!=$cpass){
      
	 $errmsg = 'Password and Confirm Password Didnot Match..';
	}
	else {
		
	  $stmt = $conn->prepare("update `registration` set `password` = '$pass' where `id` ='$uid'");
      $stmt->execute();
		
	  if($stmt) {
		
		 echo '<script>alert("Password Changed Successfully.")</script>';
			echo '<script>window.location.href="login-register"</script>';		
		
		}else{		
		
			echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';
    }

}

}



?>

<?php include('header.php'); ?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?= $WebsiteUrl2.'/'; ?>style.css">
<script type="text/javascript" src="<?= $WebsiteUrl2.'/'; ?>password_strength/password_strength_lightweight.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $WebsiteUrl2.'/'; ?>password_strength/password_strength.css">

<script>
    $(document).ready(function($) {
        $('#myPassword').strength_meter();

    });
</script>
<div class="main-dv-sec">
  <div class="heading-main">
     <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>تغيير الرقم السري</span></h2>
 
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
      
        
        
        <div class="col-md-12 col-sm-12 col-xs-12" id="sign-up-form">
          <div class="register-wrap">
            <h3 class="fsz-25 ptb-15"><span class="light-font"></span> <strong>أدخل الرقم السري الجديد</strong> </h3>            
            <span class="text-danger fsz-25 ptb-15" id="reg-error2"><?php if(isset($errmsg)){ echo $errmsg; } ?></span>
            <span class="text-success fsz-25 ptb-15" id="reg-error22"><?php if(isset($msg)){ echo $msg; } ?></span></h3>
            <form name="sign_up" id="sign_up" method="post" class="register-form row  pt-50 second_form" >
              <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="text" name="notp" id="notp" class="form-control" placeholder="إدخال الرقم السري المؤقت" required>
              </div></div>
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                  <div id="myPassword"></div>
                <!--<input type="text" name="pass" id="user_name" class="form-control" placeholder="كلمة المرور" required>-->
              </div></div>
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                <input type="text" name="cpass" id="store" class="form-control" placeholder="اعادة كلمة المرور" required>
              </div></div>
            
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group pt-15">
                <input type="submit" name="update" class="btn theme-btn-2" id="sign-up2" value="تحديث رقم السر" style="background-color: goldenrod !important;
    color: #ffffff !important;">
              </div></div>
              
              </div>
            </form>
          </div>
        </div>
        
        
      </div>
    </div>
  </section>
</div>

<?php include('footer.php'); ?>

<script>
$.validator.addMethod("pwcheck",
function() {
    var data = $(".strength_meter > div").hasClass('strong');
	  return data;
});	
$('form[id="sign_up"]').validate({
  rules: {
    user_name: 'required',
	store: 'required',
    notp: {
      required: true
    },
    pass: {
      required: true,
	  pwcheck: true
    },
	cpass: {
      required: true,
      equalTo: "#password",
	  minlength: 3
    },
  },
  messages: {
        notp: "Please Enter OTP",
		pass:
		{
                                required: "Please provide a password",
                                pwcheck: "Please provide a strong password"
                            },
		cpass:{
                                required: "Please provide confirm password",
                                equalTo: "Please provide same as Password "
                            }
  },
  submitHandler: function(form) {
    form.submit();
  }
});
</script>

</body>
</html>
