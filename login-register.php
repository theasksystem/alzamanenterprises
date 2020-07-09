<?php

session_start();
error_reporting(0);
include('include/db.class.php');


if($_SESSION['LOGIN_ID']!='')
  {

		echo "<script>window.location.href='".$_SESSION['previous_page']."'</script>";
  
  }

//include 'fb-login-data.php';
include 'google-login-data.php';

//echo $_SESSION['previous_page'];

if(isset($_POST['loggedin'])){

  $email = trim($_POST['email']);
  $pass = trim($_POST['password']);

  $stmt = $conn->prepare("select * from registration where email = :email and password = :pass and visible=1");
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
  $stmt->execute();
  $check = $stmt->fetch(PDO::FETCH_ASSOC);
  $login_id =  $check['id'];

   
  if($stmt->rowCount() > 0)
  {
    	$_SESSION['LOGIN_ID'] = $login_id;
		$_SESSION['LOGIN_NAME'] = ucwords($check['name']);

		echo "<script>window.location.href='".$_SESSION['previous_page']."'</script>";
  
  } else {
    	
		$msg = "Sorry Email or Password Is Incorrect.";
  }
  


}

?>

<?php include('header.php'); ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl.'/'; ?>">Home</a></strong>  / <span>Log In &  Register</span></h2>
  </div>
  <section class="pt-20 pb-40">
    
      <!-- Login Starts -->
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12" id="sign-in-form">
          <div class="login-wrap">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Log in </span> <strong>Your Account </strong> </h3>
            <span class="text-danger fsz-25 ptb-15" id="login-error"><?php if(isset($msg)){echo $msg; } ?></span></h3>
            <form name="sign_in" id="sign_in" method="post" action="" class="login-form" onSubmit="javascript: return validate();">
             <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Id">
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                <i class="fa fa-eye" onclick="showHidePassword()" class="passhide" ></i>
              </div></div>
             
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="submit" name="loggedin" class="theme-btn btn submit-btn" id="sign-in" value="LOGIN ">
              </div></div>
              <div class="col-md-6 col-sm-6 col-xs-6 text-left"><div class="form-group">
                <label class="fsz-12"> 
                
                <?='<a href="'.$google_client->createAuthUrl().'"><img src="images/google-login.png" alt="Google Login" title="Google Login"/></a>'; ?> </label>
              </div></div>
               <div class="col-md-6 col-sm-6 col-xs-6"><div class="form-group">
                <!--<label class="forgot-pw fsz-12">-->
                <!--<? echo '<a href="'.htmlspecialchars($loginUrl).'"><img src="images/facebook.png" alt="Facebook Login" title="Facebook Login" /></a>'; ?> </label>-->
                <label class="forgot-pw fsz-12">
                <? echo '<a href="#"><img src="images/facebook.png" alt="Facebook Login" title="Facebook Login" /></a>'; ?> </label>
              </div></div>
               <div class="col-md-6 text-left"><div class="form-group">
                <label class="fsz-12"> <a href="#" class="clr-txt-2 pw" id="forget"><strong>Forgot your password ?</strong> </a> </label>
              </div></div>
               <div class="col-md-6"><div class="form-group">
                <label class="forgot-pw fsz-12"><a href="#" class="clr-txt-2 pw" id="register"> Don't have an Account? <strong>Register Now </strong></a> </label>
              </div></div>
              
              </div>
            </form>
          </div>
        </div>
        
        <div class="col-md-12 col-sm-12 col-xs-12" style="display:none" id="sign-up-form">
          <div class="register-wrap ">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Don't have an Account? </span> <strong>Register Now </strong> </h3>
            <p>By creating an account with our store, you will be able to move through the checkout process faster.</p>
            
            <span class="text-danger fsz-25 ptb-15" id="reg-error2"></span>
            <span class="text-success fsz-25 ptb-15" id="reg-error22"></span></h3>
            <form name="sign_up" id="sign_up" method="post" class="register-form" >
              <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                <input type="text" name="user_name" id="user_name" class="form-control" placeholder="First Name">
              </div></div>
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                <input type="text" name="user_lastname" id="user_lastname" class="form-control" placeholder="Last Name">
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email Address">
              </div></div>
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                <input type="number" name="user_mobile" id="user_mobile" class="form-control" placeholder="3155 9977" >
              </div></div>
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                <select name="gender" id="gender" class="form-control">
                	<option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
              </div></div>

              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
                <input type="password" name="user_pass" id="user_pass" class="form-control" placeholder="Password">
                <i class="fa fa-eye" onclick="showHidePassword()" class="passhide" ></i>
              </div></div>
              <div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group ">
                <input type="password" name="user_cpass" id="user_cpass" class="form-control" placeholder="Re - Password">
                <i class="fa fa-eye" onclick="showHidePassword()" class="passhide" ></i>
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <button type="button" class="btn theme-btn-2" id="sign-up"> <b> REGISTER NOW </b> </button>
                <label class="forgot-pw fsz-12"><a href="#" class="clr-txt-2 pw" id="login"> Already registered? <strong>Login </strong></a> </label>
              </div></div>
              </div>
            </form>
          </div>
        </div>
        
        <div class="col-md-12 col-sm-12 col-xs-12" id="forget-form" style="display:none">
          <div class="login-wrap">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Forget </span> <strong>Password </strong> </h3>
            <span class="text-danger fsz-25 ptb-15" id="reg-error3"></span>
            <span class="text-success fsz-25 ptb-15" id="reg-error33"></span></h3>
            <form name="forget_pass" id="forget_pass" method="post" class="login-form">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="email" name="forget" id="forget2" class="form-control" placeholder="Email Id">
              </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <button type="button" class="theme-btn btn submit-btn" id="reset22"> <b> Reset Password </b> </button>
                <label class="forgot-pw fsz-12"><a href="#" class="clr-txt-2 pw" id="login2"> Back to <strong>Login </strong></a> </label>
              </div></div>
              </div>
            </form>
          </div>
        </div>
      </div>

  </section>
</div>

<?php include('footer.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
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


<script>

$(document).ready(function(){

		
	$("#register").click(function(){
		$("#sign-in-form").hide();
		$("#sign-up-form").show();
		$("#forget-form").hide();
	});
	
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


$('#sign-up').on("click", function(){
	
	var regEmail = /^([-a-zA-Z0-9._]+@[-a-zA-Z0-9.]+(\.[-a-zA-Z0-9]+)+)$/;
	var user_name = $("#user_name").val();
	var user_lastname = $("#user_lastname").val();
	var user_email = $("#user_email").val();
	var user_mobile = $("#user_mobile").val();
	var gender = $("#gender").val();
//	var dob = $("#dob").val();
//	var pdd = $("#pdd").val();
//	var pyy = $("#pyy").val();
	var user_pass = $("#user_pass").val();
	var user_cpass = $("#user_cpass").val();		

    if(user_name==''){
	  
        $("#user_name").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.user_name.focus();
		return false;
    }
	if(user_lastname==''){
	  
        $("#user_lastname").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.user_lastname.focus();
		return false;
    }
	if(user_email=='')
    {
	  
        $("#cremail").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.user_email.focus();
		return false;
    }
	if(!document.sign_up.user_email.value.match(regEmail))
	{
		alert("Email Not in Valid Format..");
		document.sign_up.user_email.focus();
		return false;
	}
	
	if(user_mobile=='')
    {
	   $("#user_mobile").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.user_mobile.focus();
	   return false;
    }
	if(!(document.sign_up.user_mobile.value.length >= 6 && document.sign_up.user_mobile.value.length <= 15))
	{
		alert("Please Enter Between 6 and 15 Digit in Your Contact No.\n\n You have enter "+document.sign_up.user_mobile.value.length+" Digit.");
		document.sign_up.user_mobile.focus();
		return false;
	}
	if(gender=='')
    {
	   $("#gender").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.gender.focus();
	   return false;
    }
/*	if(dob=='')
    {
	   $("#dob").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.dob.focus();
	   return false;
    }
    if(pdd=='')
    {
	   $("#pdd").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.pdd.focus();
	   return false;
    }
    if(pyy=='')
    {
	   $("#pyy").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.pyy.focus();
	   return false;
    }*/
	if(user_pass=='')
    {
	   $("#user_pass").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.user_pass.focus();
	   return false;
    }
	if(user_pass!=user_cpass)
    {
	   alert("Password and Confirm password not match..");
	   document.sign_up.user_cpass.focus();
	   return false;
    
	}else{
       
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl.'/'; ?>login.php",
                data: {'user_name':user_name,'user_lastname':user_lastname,'user_email':user_email,'user_mobile':user_mobile,'gender':gender,'user_pass':user_cpass},
                cache: false,
                success: function(response){
				$("#sign-in-form").hide();
				$("#sign-up-form").show();
				if(response == 1){
				$("#reg-error22").html("Congratulation. An Activation Link is send to your Email Address.");
				$("#reg-error2").html("");
				$("#user_name").val('');
				$("#user_lastname").val('');
				$("#user_email").val('');
				$("#user_mobile").val('');
				//$("#pdd").val('');
				//$("#pyy").val('');
				$("#user_pass").val('');
				$("#user_cpass").val('');	
				}
				else{
				$("#reg-error2").html(response);
				$("#reg-error22").html("");
				}
                }
            });
     }

});
$('#reset22').on("click", function(){

    var forget = $("#forget2").val();
		
    if(forget==''){
	  
     $("#forget2").css({'border':'red 1px solid','background-color':'#eee'});
		return false;
    
	} else {
       
        $.ajax({
			type: "POST",
			url: "<?= $WebsiteUrl.'/'; ?>login.php",
			data: {'forget':forget},
			cache: false,
			success: function(response){
			//alert(response);
			 if(response==2){
			 $("#reg-error3").html("Sorry Not a Registered User.");
			 $("#reg-error33").html("");
			}
			else{
			 //alert(response);
			 $("#reg-error33").html("We have sent you an Email with Login Details on your Registered Email");
			 $("#reg-error3").html("");
			 $("#forget2").val('');
			 window.location.href = response;
			 
			}
			}
        });
    }

});

});

function showHidePassword() {
  var x = document.getElementById("password");
  var y = document.getElementById("user_pass");
  var z = document.getElementById("user_cpass");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
  if (y.type === "password") {
    y.type = "text";
  } else {
    y.type = "password";
  }
  if (z.type === "password") {
    z.type = "text";
  } else {
    z.type = "password";
  }
  
}

</script>

</body>
</html>
