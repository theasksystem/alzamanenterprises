<?php

session_start();
error_reporting(0);
include('include/db.class.php');

if(isset($_REQUEST['user_email']))
{
    ini_set("SMTP", "mail.alzamanenterprises.com");
    ini_set("sendmail_from", $fromemail);
    
	$user_name =  $_SESSION['user_name'] = trim($_POST["user_name"]);
	$user_firstname =  $_SESSION['user_firstname']  = trim($_POST["user_firstname"]);
	$user_lastname =  $_SESSION['user_lastname']  = trim($_POST["user_lastname"]);
	$user_email =  $_SESSION['user_email']  = trim($_POST["user_email"]);
	$user_mobile =  $_SESSION['user_mobile']  = trim($_POST["user_mobile"]);
	$gender =  $_SESSION['gender']  = trim($_POST["gender"]);
	$user_pass =  $_SESSION['user_pass']  = trim($_POST["user_pass"]);
	$user_cpass =  $_SESSION['user_cpass']  = trim($_POST["user_cpass"]);
	$instagram_username =  $_SESSION['instagram_username']  = trim($_POST["instagram_username"]);
	$store =  $_SESSION['store_name']  = trim($_POST["store_name"]);
	$qid_number =  $_SESSION['qid_number']  = trim($_POST["qid_number"]);
	$cr_number =  $_SESSION['cr_number']  = trim($_POST["cr_number"]);
	$category =  $_SESSION['category']  = trim($_POST["category"]);
	$type = 'Vendor';
	$vis = 0;
	
	$count2 = $conn->prepare("select * from tbl_admin where mobile = :phone");
    $count2->bindParam(':phone', $user_mobile, PDO::PARAM_STR);
    $count2->execute();
	
	$count1 = $conn->prepare("select * from tbl_admin where email = :email");
    $count1->bindParam(':email', $user_email, PDO::PARAM_STR);
    $count1->execute();
	
	if($count1->rowCount() > 0){
      
	 $errmsg = 'Email Id Already Registered. Please Use another email id..';
	}elseif($count2->rowCount() > 0){
	  
	  $errmsg = 'Mobile No Already Registered. Please Use another Mobile No..';
	}else {
		
	  $stmt = $conn->prepare("INSERT INTO `tbl_admin`(`username`, `firstname`,`lastname`,`password`,`mobile`, `email`,`gender`, `instagram_username`,`company`,`qidnumber`,`crnumber`,`category`, `type`, `vis`, `login_ip`) VALUES (:username, :firstname, :lastname, :password, :phone, :email, :gender, :instagram, :store, :qidnum, :crnum, :category, :type, :vis, :ip)");

	  $stmt->bindParam(':username', $user_name, PDO::PARAM_STR);
	  $stmt->bindParam(':firstname', $user_firstname, PDO::PARAM_STR);
	  $stmt->bindParam(':lastname', $user_lastname, PDO::PARAM_STR);
	  $stmt->bindParam(':password', $user_pass, PDO::PARAM_STR);
	  $stmt->bindParam(':phone', $user_mobile, PDO::PARAM_STR);
	  $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
	  $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	  $stmt->bindParam(':instagram', $instagram_username, PDO::PARAM_STR);
	  $stmt->bindParam(':store', $store, PDO::PARAM_STR);
	  $stmt->bindParam(':qidnum', $qid_number, PDO::PARAM_INT);
	  $stmt->bindParam(':crnum', $cr_number, PDO::PARAM_INT);
	  $stmt->bindParam(':category', $category, PDO::PARAM_INT);
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
						  <p>User Name : '.$user_name.'</p>
						  <p>First Name : '.$user_firstname.'</p>
						  <p>Last Name : '.$user_lastname.'</p>
						  <p>Gender : '.$gender.'</p>
						  <p>Store Name : '.$store.'</p>
						  <p>Category : '.$category.'</p>
						  <p>QID Number : '.$qid_number.'</p>
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
		unset($_SESSION['user_name']);
		unset($_SESSION['user_firstname']);
		unset($_SESSION['user_lastname']);
		unset($_SESSION['user_email']);
		unset($_SESSION['user_mobile']);
		unset($_SESSION['gender']);
		unset($_SESSION['user_pass']);
		unset($_SESSION['user_cpass']);
		unset($_SESSION['instagram_username']);
		unset($_SESSION['store_name']);
		unset($_SESSION['qid_number']);
		unset($_SESSION['cr_number']);
		unset($_SESSION['category']);
		$msg = 'Request Processed Successfully. waiting for Approval.';
		
	
} else {
    
	
        $errmsg = 'Error !! Please try again..';
    }

}

}

	$categories = $conn->prepare("SELECT id,name_en, name_ar from department");
    $categories->execute();

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
      
        
        
        <div class="col-md-12 col-sm-12 col-xs-12" id="sign-up-form">
          <div class="register-wrap">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Start selling your items on our site</span> <strong>Register Now </strong> </h3>
            <p>By Creating An Account With Our Website, You Will Be Able To Grow Your Business Exponentially</p>
            
            <span class="text-danger fsz-25 ptb-15" id="reg-error2"><?php if(isset($errmsg)){ echo $errmsg; } ?></span>
            <span class="text-success fsz-25 ptb-15" id="reg-error22"><?php if(isset($msg)){ echo $msg; } ?></span></h3>
            <form name="sign_up" id="sign_up" method="post" class="register-form row  pt-50 second_form" >
              	<div class="row">
              		<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="text" name="user_firstname" id="user_name" class="form-control" placeholder="First Name*" required>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="text" name="user_lastname" id="user_lastname" class="form-control" placeholder="Last Name*" required>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="text" name="user_name" id="username" class="form-control" placeholder="Username">
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email Address*" required>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="number" name="user_mobile" id="user_mobile" class="form-control" placeholder="3155 9977*" required>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<select name="gender" id="gender" class="form-control">
							<option value="">Select Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="password" name="user_pass" id="user_pass" class="form-control" placeholder="Password*" required>
						<i class="fa fa-eye" onclick="showHidePassword()" class="passhide" ></i>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group ">
						<input type="password" name="user_cpass" id="user_cpass" class="form-control" placeholder="Confirm Password*" required>
						<i class="fa fa-eye" onclick="showHidePassword()" class="passhide" ></i>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="text" name="instagram_username" id="instagram_username" class="form-control" placeholder="Instagram Username">
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group ">
						<input type="text" name="store_name" id="store_name" class="form-control" placeholder="Store Name*" required>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<input type="number" name="qid_number" minlength="11" maxlength="11" id="qid_number" class="form-control" placeholder="QID Number*" required>
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group ">
						<input type="number" name="cr_number" minlength="5" maxlength="6" id="cr_number" class="form-control" placeholder="CR Number">
					</div></div>
					<div class="col-md-6 col-sm-12 col-xs-12"><div class="form-group">
						<select name="category" id="category" class="form-control" required>
							<option value="">Select Category</option>
							<?php while($row = $categories->fetch(PDO::FETCH_ASSOC)) {
									echo '<option value="'.$row["id"].'">'.$row["name_en"].' ( '.$row['name_ar'].' ) '.'</option>';
									} 
							?>
						</select>
					</div></div>
            
              		<div class="col-md-12 col-sm-12 col-xs-12"><div class="form-group pt-15">
                		<button type="submit" class="btn theme-btn-2" id="sign-up2"> <b> SUBMIT REQUEST</b> </button>
                		<label class="forgot-pw fsz-12 float-right"><a href="vendors" class="clr-txt-2 pw" id="login"> Already registered? <strong>Login </strong></a> </label>
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
$('form[id="sign_up"]').validate({
  rules: {
    user_name: 'required',
	store_name: 'required',
    user_email: {
      required: true,
      email: true,
    },
    user_mobile: {
		required: true,
		minlength: 6,
		maxlength: 15
	},
  },
  messages: {
		user_name: "Please provide your First Name",
		user_email: "Please provide a valid Email Address",
		user_mobile: "Please provide a valid Mobile Number",
		store_name: "Please provide your Store name",
		qid_number:"Please provide a valid QID Number",
		user_firstname:"Please provide a valid First Name",
		category:"Please select a valid Store Category",
		user_lastname:"Please provide your Last Name"
  },
  submitHandler: function(form) {
    form.submit();
  }
});

	function showHidePassword() {
        var x = document.getElementById("user_pass");
        var y = document.getElementById("user_cpass");
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
    }
</script>

</body>
</html>
