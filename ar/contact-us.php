<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

if(isset($_POST['submit'])){

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);


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

					      <p>Below is Contact Form details:</p>
						  <p>Name : '.$name.'</p>
						  <p>Email Id : '.$email.'</p>
						  <p>Subject : '.$subject.'</p>
						  <p>Message : '.$message.'</p>
						  <p>Team – '.$WebsiteTitle.'</p><br>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#f7f7f7;color:#888">Sent on '.date('d-m-Y').'. &nbsp;&nbsp;Have questions? Email to&nbsp; <a href="'.$messagefooterEmail.'" target="_blank">'.$messagefooterEmail.'</a></td>
                      </tr></tbody></table></td></tr></tbody></table>';


//////////////////////////////////////End ////////////////////////////////////////


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

					      <p>Thanks for contacting with us. We will contact you soon.</p>
						  <p>Team – '.$WebsiteTitle.'</p><br>
					    </div></td>
            		 </tr>';
            $message1.='<tr>
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
		
		$to=$clientmail;
	
	$Query = $conn->prepare("INSERT INTO `tbl_query`(`name`, `email`, `subject`, `msg`) VALUES (:cname, :cemail, :csubject, :cmessage)");
	$Query->bindParam(':cname', $name, PDO::PARAM_STR);
	$Query->bindParam(':cemail', $email, PDO::PARAM_STR);
	$Query->bindParam(':csubject', $subject, PDO::PARAM_STR);
	$Query->bindParam(':cmessage', $message, PDO::PARAM_STR);
	$Query->execute();
	
	if($Query == true){
	
		mail($to, "Enquiry from Alzaman Enterprises", $message2, $headers);
		
        mail($email, "Enquiry from Alzaman Enterprises", $message1, $headers);
		
	    echo "<script type='text/javascript'>alert('Your Enquiry have been successfully submitted..');</script>";
        echo "<script>window.location.href='contact-us'</script>";
    } else {
        echo "<script type='text/javascript'>alert('It seems some error accured. please try again.');</script>";
	
	}

}

?>

<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
<div class="main-dv-sec">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>التواصل معنا</span></h2>
 
  </div>
  <div class="cont">
    <section class="htc__contact__area ptb--100 bg__white">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
            <div class="map-contacts--2">
              <div style="width: 100%; height:100%;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3606.293796983859!2d51.48946081448714!3d25.32792053260111!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e45db34304cd8b9%3A0xe83392c056639e64!2sAlzaman%20Enterprises!5e0!3m2!1sen!2sin!4v1573812577458!5m2!1sen!2sin" width="100%" height="446px" frameborder="0" style="border:0"></iframe>
              </div>
            </div>
          </div>
          <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
            <div class="address">
              <div class="address__icon"> <i class="fa fa-map-marker" aria-hidden="true"></i> </div>
              <div class="address__details">
                <h2 class="ct__title text-right">عنواننا </h2>
                <p>Alzaman Enterprises, Abdul Rahman St, Doha</p>
              </div>
            </div>
            <div class="address">
              <div class="address__icon"> <i class="fa fa-envelope" aria-hidden="true"></i> </div>
              <div class="address__details">
                <h2 class="ct__title text-right">البريد الالكتروني</h2>
                <p>info@alzamanenterprises.com</p>
              </div>
            </div>
            <div class="address">
              <div class="address__icon"> <i class="fa fa-phone" aria-hidden="true"></i> </div>
              <div class="address__details">
                <h2 class="ct__title text-right">رقم الجوال</h2>
                <p>3155 9977</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="contact-form-wrap mt--60">
            <div class="col-md-12">
              <div class="contact-title">
                <h2 class="title__line--6">تواصل معنا</h2>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <form id="contact-form" name="reg" action="" method="post">

              <div class="single-contact-form">
                <div class="contact-box subject">
                  <input type="text" name="name" placeholder="الاسم*" required>
                  
                </div>
              </div>
              <div class="single-contact-form">
                <div class="contact-box subject">
                  <input type="email" name="email" placeholder="البريد الالكتروني*" required>
                </div>
              </div>
              <div class="single-contact-form">
                <div class="contact-box subject">
                  <input type="text" name="subject" placeholder="العنوان*" required>
                </div>
              </div>
              <div class="single-contact-form">
                <div class="contact-box message">
                  <textarea name="message" placeholder="الرسالة*" required></textarea>
                </div>
              </div>
              <div class="contact-btn text-right">
                <input type="submit" name="submit" id="usubmit" class="fv-btn" value=" ارسل الرسالة">
              </div>
            </form>
            <div class="form-output">
              <p class="form-messege"></p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include('footer.php'); ?>


</body>
</html>
