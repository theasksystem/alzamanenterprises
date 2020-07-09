<?php

session_start();
//error_reporting(0);
include('include/db.class.php');

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
    	$_SESSION['VENDOR_ID'] = $login_id;
		$_SESSION['USER_TYPE'] = $check['type'];
		echo "<script>window.location.href='vendor/setting.php'</script>";
  
  } else {
    	
		$msg="Sorry Email or Password is Incorrect..";
  }
  


}

if(isset($_REQUEST['user_email']))
{
    ini_set("SMTP", "mail.alzamanenterprises.com");
    ini_set("sendmail_from", $fromemail);
    
	$user_name =  trim($_POST["user_name"]);
	$user_email = trim($_POST["user_email"]);
	$user_mobile = trim($_POST["user_mobile"]);
	$store = trim($_POST["store"]);
	$type = 'Vendor';
	$vis = 0;

	$count2 = $conn->prepare("select * from tbl_admin where mobile = :phone");
    $count2->bindParam(':phone', $user_mobile, PDO::PARAM_STR);
    $count2->execute();
	
	$count1 = $conn->prepare("select * from tbl_admin where email = :email");
    $count1->bindParam(':email', $user_email, PDO::PARAM_STR);
    $count1->execute();
	
	if($count1->rowCount() > 0){
      
	 // $msg = 'Email Id Already Registered. Please Use another email id..';
		echo "<script type='text/javascript'>alert('Email Id Already Registered. Please Use another email id..');</script>";
	}elseif($count2->rowCount() > 0){
	  
	  //$msg = 'Mobile No Already Registered. Please Use another Mobile No..';
	echo "<script type='text/javascript'>alert('Mobile No Already Registered. Please Use another Mobile No..');</script>";
	}else {
		
	  $stmt = $conn->prepare("INSERT INTO `tbl_admin`(`username`, `mobile`, `email`, `company`, `type`, `vis`, `login_ip`) VALUES (:name, :phone, :email, :store, :type, :vis, :ip)");

      $stmt->bindParam(':name', $user_name, PDO::PARAM_STR);
	  $stmt->bindParam(':phone', $user_mobile, PDO::PARAM_STR);
	  $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
	  $stmt->bindParam(':store', $store, PDO::PARAM_STR);
	  $stmt->bindParam(':type', $type, PDO::PARAM_STR);
	  $stmt->bindParam(':vis', $vis, PDO::PARAM_INT);
	  $stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_INT);
      $stmt->execute();
		
	  if($stmt) {
		
		$myidd = $conn->lastInsertId();
		$activation = base64_encode(base64_encode($myidd));

	    $message2='<table width="100%" cellspacing="0" cellpadding="20" border="0">
                             <tbody>
								<tr>
								  <td><table style="border:1px solid #ddd" align="center" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
									  <tbody>
										<tr>
										  <td><table width="100%" cellspacing="0" cellpadding="0" bgcolor="#000">
											  <tbody>
												<tr>
												  <td style="padding:20px 30px" valign="center" height="88" bgcolor="#000"><img title="'.$WebsiteTitle.'" src="'.$WebsiteUrl.'/'.$logo.'" alt="'.$WebsiteTitle.'" class="CToWUd" style="width:400px"></td>
												  <td style="font-family:Georgia,serif;font-size:14px;color:#999;font-style:italic;border-bottom:1px solid #f5f5f5" valign="center" bgcolor="#F0F0F0"></td>
												</tr>
											  </tbody>
											</table>
										  </td>
            							</tr>';
         $message2.='<tr>
                      <td style="border-bottom:1px solid #eaebea">
					    <div style="line-height:21px;font-size:14px;color:#444;padding:30px;font-family:Georgia,serif;background:white;border-top:1px solid #ccc">

					      <p>Below is New Vendor Request Form details:</p>
						  <p>Name : '.$user_name.'</p>
						  <p>Store Name : '.$store.'</p>
						  <p>Email Id : '.$user_email.'</p>
					      <p>Mobile No. : '.$user_mobile.'</p>
						  <p>Unique ID : '.$myidd.'</p>
						  <p>Team – '.$WebsiteTitle.'</p><br>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#f7f7f7;color:#888">Sent on '.date('d-m-Y').'. &nbsp;&nbsp;Have questions? Email to&nbsp; <a href="'.$messagefooterEmail.'" target="_blank">'.$messagefooterEmail.'</a></td>
                      </tr></tbody></table></td></tr></tbody></table>';

//////////////////////////////////////End ////////////////////////////////////////
		 
		$headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		$to = $clientmail;
	
	    mail($to, "New Seller Request On Alzaman", $message2, $headers);

		//$msg = 1;
			echo "<script type='text/javascript'>alert('Request Processed Successfully. waiting for Approval.');</script>";
   			 echo "<script>window.location.href='request-vendors'</script>";
	
} else {
    
	echo "<script type='text/javascript'>alert('Error !! Please try again..');</script>";
        //$msg = 'Error !! Please try again..';
    }

}

echo $msg;

}



?>

<?php include('header.php'); ?>

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl.'/'; ?>">Home</a></strong>  / <span>Vendor LogIn</span></h2>
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
      
        <div class="col-md-12 col-sm-12 col-xs-12" id="sign-in-form">
          <div class="login-wrap">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Vendors </span> <strong>Login </strong> </h3>
            <span class="text-danger fsz-25 ptb-15" id="login-error"><?php if(isset($msg)){echo $msg; } ?></span></h3>
            <form name="sign_in" id="sign_in" method="post" class="login-form" action="" onSubmit="javascript: return validate();">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group ">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Id">
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                <i class="fa fa-eye" onclick="showHidePassword()"></i>
              </div></div>
              
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group">
                <input type="submit" name="loggedin" class="theme-btn btn submit-btn" id="sign-in" value="LOGIN ">
                
              </div></div>
              <div class="col-md-6 text-left"><div class="form-group">
                <label class="fsz-12"> <a href="#" class="clr-txt-2 pw" id="forget"><strong>Forgot your password? </strong> </a> </label>
              </div></div>
              <div class="col-md-6"><div class="form-group">
                <label class="forgot-pw fsz-12"><a href="request-vendors" class="clr-txt-2 pw" id="register"> Don't have an Account? <strong>Request Now </strong></a> </label>
              </div></div>
              </div>
            </form>
          </div>
        </div>
        
                
        <div class="col-md-12 col-sm-12 col-xs-12" id="forget-form" style="display:none">
          <div class="login-wrap">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Forget </span> <strong>Password </strong> </h3>
            <span class="text-danger fsz-25 ptb-15" id="login-error2"></span>
            <span class="text-success fsz-25 ptb-15" id="login-error22"></span></h3>
            <form name="forget_pass" id="forget_pass" method="post" class="login-form row mr-40">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group ">
                <input type="email" name="forget" id="forget2" class="form-control" placeholder="Email Id">
              </div></div>
              <div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group pt-15">
                <button type="button" class="theme-btn btn submit-btn" id="reset22"> <b> Reset Password </b> </button>
                <label class="forgot-pw fsz-12"><a href="#" class="clr-txt-2 pw" id="login2"> Back to <strong>Login </strong></a> </label>
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
			url: "<?= $WebsiteUrl.'/'; ?>vendorlogin.php",
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
