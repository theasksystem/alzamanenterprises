<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

if(isset($_POST['loggedin'])){

  $email = trim($_POST['email']);
  $pass = trim($_POST['password']);

  $stmt = $conn->prepare("select * from tbl_admin where email = :email and password = :pass and vis=1 and type='Vendor'");
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
  $stmt->execute();
  $check = $stmt->fetch(PDO::FETCH_ASSOC);
  $login_id =  $check['id'];

   
  if($stmt->rowCount() != 0)
  {
    	$_SESSION['USER_ID'] = $login_id;
		$_SESSION['USER_TYPE'] = $check['type'];
		echo "<script>window.location.href='../vendor/setting.php'</script>";
  
  } else {
    	
		$msg="Sorry Email or Password is Incorrect..";
  }
  


}

?>
<?php include('header.php'); ?>

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>تسجيل الدخول البائع</span></h2>
 
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
      
        <div class="col-md-12 col-sm-12 col-xs-12" id="sign-in-form">
          <div class="login-wrap">
            <h3 class="fsz-25 ptb-15 text-right"><span class="light-font"></span> <strong>تسجيل الدخول البائع </strong> </h3>
            <span class="text-danger fsz-25 ptb-15" id="login-error"><?php if(isset($msg)){echo $msg; } ?></span></h3>
            <form name="sign_in" id="sign_in" method="post" class="login-form" action="" onSubmit="javascript: return validate();">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group ">
                <input type="email" name="email" id="email" class="form-control" placeholder="البريد الالكتروني">
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="كلمة المرور">
              <i class="fa fa-eye" onclick="showHidePassword()"></i>
              </div></div>
              
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="submit" name="loggedin" class="theme-btn btn submit-btn" id="sign-in" value="التسجيل الدخول ">
                
              </div></div>
			  <div class="col-md-6 float-left"><div class="form-group">
                <label class="forgot-pw fsz-12"> <a href="#" class="clr-txt-2 pw" id="forget"><strong>هل نسيت كلمة المرور؟</strong></a> </label>
              </div></div>
			  <div class="col-md-6 text-left"><div class="form-group">
               <label class="fsz-12"><a href="request-vendors" class="clr-txt-2 pw" id="register"><strong>اطلب الان </strong> مشترك جديد؟</a> </label>
              </div></div>
              </div>
            </form>
          </div>
        </div>
        
        
        
        <div class="col-md-12 col-sm-12 col-xs-12" id="forget-form" style="display:none">
          <div class="login-wrap">
            <h3 class="fsz-25 ptb-15 text-right"><span class="light-font"></span> <strong>هل نسيت كلمة المرور </strong> </h3>
            <span class="text-danger fsz-25 ptb-15" id="login-error2"></span>
            <span class="text-success fsz-25 ptb-15" id="login-error22"></span></h3>
            <form name="forget_pass" id="forget_pass" method="post" class="login-form">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group ">
                <input type="email" name="forget" id="forget2" class="form-control" placeholder="البريد الالكتروني">
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group pt-15">
                <button type="button" class="theme-btn btn submit-btn" id="reset22"> <b>إعادة تعيين كلمة المرور</b> </button>
                <label class="forgot-pw fsz-12"><a href="#" class="clr-txt-2 pw" id="login2"><strong>التسجيل الدخول</strong></a> </label>
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
function validate()
	{
	
	var regEmail = /^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)$/;
	
	if(document.sign_in.email.value=="")
		{
		$("#email").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_in.email.focus();
		return false;
		}
	if(!document.sign_in.email.value.match(regEmail))
		{
		$("#email").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_in.email.focus();
		return false;
		}
		if(document.sign_in.password.value=="")
		{
		$("#password").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_in.password.focus();
		return false;
		}
		return true;
		}
</script>


<!-- Jquery for login register -->

<script>

$(document).ready(function(){

	$("#login").click(function(){
		$("#sign-in-form").show();
		$("#sign-up-form").hide();
		$("#forget-form").hide();
	});
	
	$("#login2").click(function(){
		$("#sign-in-form").show();
		$("#sign-up-form").hide();
		$("#forget-form").hide();
	});
	
	$("#forget").click(function(){
		$("#forget-form").show();
		$("#sign-in-form").hide();
		$("#sign-up-form").hide();
	});


$('#reset22').on("click", function(){

    var forget = $("#forget2").val();
	//alert(forget);	
    if(forget==''){
	  
     $("#forget2").css({'border':'red 1px solid','background-color':'#eee'});
		
    
	} else {
       
        $.ajax({
			type: "POST",
			url: "<?= $WebsiteUrl2.'/'; ?>vendorlogin.php",
			data: {'forget':forget},
			cache: false,
			success: function(response){
			
		if(response==2){
			 //alert("Sorry Not a Registered User!");
			 $("#login-error2").html("Sorry Not a Registered User.");
			 $("#login-error22").html('');
			}
			else{
			
			 $("#login-error22").html("We have sent you an Email with Login Details on your Registered Email");
			 $("#forget2").val('');
			 $("#login-error2").html('');
			 window.location.href = response;
			 
			}
			}
        });
    }

});

});
function showHidePassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
 
  
}
</script>

</body>
</html>
