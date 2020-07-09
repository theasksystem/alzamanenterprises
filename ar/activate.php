<?php
session_start();
error_reporting(0);
include('../include/db.class.php');
$actId = trim(base64_decode(base64_decode($_GET['act'])));

if(isset($_GET['act'])){

$stmt = $conn->prepare("update registration set visible=1 where id ='$actId'");
$stmt->execute();
$stmt2 = $conn->prepare("select name,lastname,email from registration where id ='$actId'");
$stmt2->execute();
$userData = $stmt2->fetch(PDO::FETCH_ASSOC);

$message1='<table width="100%" cellspacing="0" cellpadding="20" border="0">
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
          $message1.='<tr>
                      <td style="border-bottom:1px solid #eaebea">
		       <div style="line-height:21px;font-size:14px;color:#444;padding:30px;font-family:Georgia,serif;background:white;border-top:1px solid #ccc">
                          <p>Dear '.$userData['name'].' '.$userData['lastname'].',</p>
                          <p>Thank you ! Your Account is activated. It’s our honor to serve you. Now you are ready to Shop in a Click.</p>
                          <p>Please find the below link to login with e-mail & password and complete your profile. In case of forgetting your password, please click on forgot password  to reset it.</p>
                          <p><a href="'.$WebsiteUrl.'/login-register">'.$WebsiteUrl.'/login-register</a></p>
                          <p>For any queries, feel free to get in touch with us at info@alzamanenterprises.com We will be glad to help you.</p>
                          <p>You can also contact us directly on WhatsApp +974-31559977.</p>
			  

			  <p style="text-align:right">'.$userData['name'].' '.$userData['lastname'].'  السيد/ السيدة </p>
			  <p style="text-align:right">شكرا لكم ! لقد تم تفعيل حسابكم، ويشرفنا أن نكون بخدمتكم، ويمكنكم الآن التسوق مباشرة.</p>
			  <p style="text-align:right">يرجى استخدام الرابط التالي لتسجيل الدخول بالبريد الإلكترني وكلمة السر لإكمال بيانات حسابكم. في حال </p>
			  <p style="text-align:right">نسيان كلمة السر نرجو الضغط على "نسيت كلمة السر".</p>
                          <p><a href="'.$WebsiteUrl.'/login-register">'.$WebsiteUrl.'/login-register</a></p>
			  <p style="text-align:right">info@alzamanenterprises.com لأي استفسار، ليس عليكم سوى التواصل معنا عبر البريد الإلكتروني  </p>
			  <p style="text-align:right">وسنسعد بخدمتكم مباشرة.</p>
			  <p style="text-align:right">كما يمكنكم التواصل معنا رقم الواتساب +974-31559977.</p>
			  <p style="text-align:right">مع تحيات</p>
			  <p style="text-align:right">الزمان للمشاريع</p>
			  <p style="text-align:right"><a href="www.alzamanenterprises.com">www.alzamanenterprises.com</a></p>
			  </div></td>
            		 </tr>';
            $message1.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#000;color:#fff">
                        Disclaimer: This is a system generated email. Please don’t reply to it. For any information contact us through email, <a href="mailto:'.$messagefooterEmail.'">'.$messagefooterEmail.'</a> or call on 31559977<br>
                        <p style="text-align:right">تنبيه: هذه الرسالة مرسلة من قبل النظام الآلي، نرجو عدم الرد عليها. لمزيد من المعلومات يرجى التواصل معنا عبر البريد الإلكتروني <a href="mailto:'.$messagefooterEmail.'">'.$messagefooterEmail.'</a> أو الاتصال على 31559977</p></td>
                      </tr></tbody></table></td></tr></tbody></table>';

//////////////////////////////////////End ////////////////////////////////////////
		 
		$headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		$to = $userData['email'];
		$myurl = $WebsiteUrl2.'/activate';
	
	    if(mail($to, "Account Activation Confirm", $message1, $headers)){
        		
			echo "<script>window.location.href='$myurl'</script>";		
		
		}else{		
		
			echo '<script>alert("Sorry Some Error !! Please Try Again")</script>';		
		
		}

}

?>

<?php include('header.php'); ?>

<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">Home</a></strong>  / <span>Account Activate</span></h2>
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
        <div class="col-md-12 col-sm-12" id="sign-in-form">
          <div class="headline">
            <h3 class="fsz-25 ptb-15"><span class="light-font">Contratulation </span> <strong></strong> </h3>
            <h3>Your Account is successfully Activated. Now you can login</h3>
								
								<div class="view-all-button">
									<a href="<?= $WebsiteUrl2.'/'; ?>login-register"> Go to Login page</i></a>
								</div>
          </div>
        </div>
        
        
      </div>
    </div>
  </section>
</div>

<?php include('footer.php'); ?>

</body>
</html>
