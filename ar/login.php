<?php

//ini_set("display_errors", "1");
//error_reporting(E_ALL);

session_start();
error_reporting(0);
include('../include/db.class.php');

// user login

//if(isset($_POST['email']) && isset($_POST['password'])){
//
//  $email = trim($_POST['email']);
//  $pass = trim($_POST['password']);
//
//  $stmt = $conn->prepare("select * from registration where email = :email and password = :pass and visible=1");
//  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
//  $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
//  $stmt->execute();
//  $check = $stmt->fetch(PDO::FETCH_ASSOC);
//  $login_id =  $check['id'];
//
//   
//  if($stmt->rowCount() > 0)
//  {
//    	$_SESSION['LOGIN_ID'] = $login_id;
//		$_SESSION['LOGIN_NAME'] = ucwords($check['name']);
//
//		$msg=1;
//  
//  } else {
//    	
//		$msg=2;
//  }
//  
//  echo $msg;
//
//}

// user registration

if(isset($_REQUEST['user_email']) && isset($_REQUEST['user_pass']))
{

	$user_name =  trim($_POST["user_name"]);
	$user_lastname =  trim($_POST["user_lastname"]);
	$user_email = trim($_POST["user_email"]);
	$user_mobile = trim($_POST["user_mobile"]);
	$gender = trim($_POST["gender"]);
	$dob = trim($_POST["dob"]);
	$pdd = $_POST["pdd"];
	$pyy = $_POST["pyy"];
	$user_pass = trim($_POST["user_pass"]);
	$visible = 0;

	$count2 = $conn->prepare("select * from registration where phone = :phone");
    $count2->bindParam(':phone', $user_mobile, PDO::PARAM_STR);
    $count2->execute();
	
	$count1 = $conn->prepare("select * from registration where email = :email");
    $count1->bindParam(':email', $user_email, PDO::PARAM_STR);
    $count1->execute();
	
	if($count1->rowCount() > 0){
      
	  $msg = 'Email Id Already Registered. Please Use another Email id..';
	
	}elseif($count2->rowCount() > 0){
	  
	  $msg = 'Mobile No Already Registered. Please Use another Mobile No..';
	
	}else {
		
	   $stmt = $conn->prepare("INSERT INTO `registration`(`name`,`lastname`, `phone`, `email`,`gender`,`dob`,`pdd`,`pyy`, `password`) VALUES (:name,:lastname, :phone, :email, :gender, :dob, :pdd, :pyy, :password)");

      $stmt->bindParam(':name', $user_name, PDO::PARAM_STR);
	  $stmt->bindParam(':lastname', $user_lastname, PDO::PARAM_STR);
	  $stmt->bindParam(':phone', $user_mobile, PDO::PARAM_STR);
	  $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);
	  $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
	  $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
	  $stmt->bindParam(':pdd', $pdd, PDO::PARAM_STR);
	  $stmt->bindParam(':pyy', $pyy, PDO::PARAM_STR);
	  $stmt->bindParam(':password', $user_pass, PDO::PARAM_STR);
      $stmt->execute();
		
	  if($stmt) {
		
		$myidd = $conn->lastInsertId();
		$activation = $WebsiteUrl2.'/activate/'.base64_encode(base64_encode($myidd));

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

					      <p>Below is Registration details:</p>
						  <p>Name : '.$user_name.'</p>
						  <p>Email Id : '.$user_email.'</p>
						  <p>Team – '.$WebsiteTitle.'</p><br>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#000;color:#fff">
                        Disclaimer: This is a system generated email. Please don’t reply to it. For any information contact us through email, <a href="mailto:'.$messagefooterEmail.'">'.$messagefooterEmail.'</a> or call on 31559977<br>
                        <p style="text-align:right">تنبيه: هذه الرسالة مرسلة من قبل النظام الآلي، نرجو عدم الرد عليها. لمزيد من المعلومات يرجى التواصل معنا عبر البريد الإلكتروني <a href="mailto:'.$messagefooterEmail.'">'.$messagefooterEmail.'</a> أو الاتصال على 31559977</p></td>
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
                          <p>Dear '.$user_name.' '.$user_lastname.',</p>
			  <p style="text-align:right">'.$user_name.' '.$user_lastname.'  السيد/ السيدة </p>
                          <p>Greetings from Alzaman Enterprises!</p>
                          <p>Thank you for registering with us.</p>
			  <p style="text-align:right">تحياتنا وشكرنا لكم على تسجيلكم معنا!</p>
                          <p>Your Registration is almost done</p>
			  <p style="text-align:right">لقد شارفتم الانتهاء من عملية التسجيل</p>
                          <p><a href="'.$activation.'">Click here to confirm your email and Get ready to shop in a Click</a></p>
			  <p style="text-align:right"><a href="'.$activation.'">يرجي الضغط هنا لتأكيد البريد الالكتروني ومن ثم الاستمتاع بالتسوق معنا.</a></p>
                          <p>For any queries, feel free to get in touch with us at info@alzamanenterprises.com We will be glad to help you.</p>
                          <p>You can also contact us directly on WhatsApp +974-31559977.</p>
                          <p>Regards,<br> Alzaman Enterprises </p>
			  <p style="text-align:right">info@alzamanenterprises.com لأي استفسار، ليس عليكم سوى التواصل معنا عبر البريد الإلكتروني </p>
			  <p style="text-align:right">وسنسعد بخدمتكم مباشرة.</p>
			  <p style="text-align:right">كما يمكنكم التواصل معنا رقم الواتساب +974-31559977.</p>
			  <p style="text-align:right">مع تحيات</p>
			  <p style="text-align:right">الزمان للمشاريع</p>
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
		
		$to = $clientmail;
	
	    mail($to, "New Registration On Alzaman", $message2, $headers);
	    mail($user_email, "Thanks from Alzaman", $message1, $headers);

		$msg = 1;
						 

    } else {
        $msg = 'Error !! Please try again..';
    }

}

echo $msg;

}


// forget Password 

if(isset($_REQUEST['forget']))
{
    
    ini_set("SMTP", "mail.alzamanenterprises.com");
    ini_set("sendmail_from", $fromemail);

  $email=$_POST['forget'];

  $qryyy=$conn->prepare("select * from registration where email = '$email' and visible=1");
  $qryyy->execute();
  $qry=$qryyy->fetch(PDO::FETCH_ASSOC);

  $uname=$qry['email'];
  $pass=substr(uniqid(),0,5);
  $myname=$qry['name'];
  $id=$qry['id'];
  $ProductUrl = $WebsiteUrl2.'/new-password?uid='.base64_encode(base64_encode($qry['id'])).'&res='.base64_encode(base64_encode($pass));

////////////////////////////////////Admin Message/////////////////////////////////////////////////


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

					      <p>Dear Requester, </p>
					      <p>You are receiving this e-mail because you requested a password reset for User <b>'.$myname.'</b> your temporary password is <b><u>'.$pass.'</u></b> valid for 30 minutes only , request generated from <b>ALZAMAN ENTERPRISES</b>.</p>
					      <p style="text-align:right">     عزيزنا العميل،         </p>
					      <p style="text-align:right">  لقد طلبت تجديد كلمة السر لحسابك   <b>'.$myname.'</b>  ورمز التفعيل السري  الموقت  الصالح لمدة 30 دقيقة فقط هو   <b><u>'.$pass.'</u></b>   تم إنشاء الطلب من قبل الزمان للمشاريع. </p>
                                              <p>Here is a link to reset the password. <a href='.$ProductUrl.'>Click Here</a>
					      <p>Team – '.$WebsiteTitle.'</p><br>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
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
		
		$to=$uname;

	if($uname==$email){
		
		mail($to, "Forget Password", $message2, $headers);
		
		$msg = $ProductUrl;
	
	}else{
		//$errmsg="Sorry Not a Registered User.";
		$msg=2;
	}
echo $msg;
}



///////////////////////////////////////////////Review////////////////////////////////////////////////////////////

if (isset($_REQUEST['rvname']) && isset($_REQUEST['rvemail']))
{

	$rvname =   trim($_POST["rvname"]);
	$rvemail = trim($_POST["rvemail"]);
	$rvnumber = $_POST["rvnumber"];
	$rvmessage  = $_POST["rvmessage"];
	$star = $_POST['star'];
	$pid = $_POST['ppid'];
	$visible = 0;

	$stmt = $conn->prepare("INSERT INTO `tbl_testimonial`(`pid`, `name`, `email`, `mobile`, `comment`, `star`, `visible`)

    VALUES (:ppid, :rvname, :rvemail, :rvnumber, :rvmessage, :star, :visible)");

      $stmt->bindParam(':rvname', $rvname, PDO::PARAM_STR);
	  $stmt->bindParam(':rvemail', $rvemail, PDO::PARAM_STR);
	  $stmt->bindParam(':rvnumber', $rvnumber, PDO::PARAM_STR);
	  $stmt->bindParam(':rvmessage', $rvmessage, PDO::PARAM_STR);
	  $stmt->bindParam(':star', $star, PDO::PARAM_INT);
	  $stmt->bindParam(':ppid', $pid, PDO::PARAM_INT);
	  $stmt->bindParam(':visible', $visible, PDO::PARAM_INT);
      $stmt->execute();
		

	 if($stmt) {
		


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

					      <p>Below is Registration details:</p>
						  <p>Name : '.$rvname.'</p>
						  <p>Email Id : '.$rvemail.'</p>
					      <p>Mobile No. : '.$rvnumber.'</p>
						  <p>Message : '.$rvmessage.'</p>
						  <p>Star : '.$star.'</p>
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

					      <p>Thanks from Alzaman Enterprises for your valuable Review</p>
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
	
	 mail($to, "Review On Alzaman Enterprises", $message2, $headers);
	 mail($cremail, "Thanks from Alzaman Enterprises for your valuable Review", $message1, $headers);

			$msg = 'Review is visible Once approved by Admin.';
						 

    } else {
        $msg = 'Error !! Please try again';
    }



echo $msg;

}

///////////////////////////////////////////////Add Address////////////////////////////////////////////////////////////

if (isset($_REQUEST['address']) && isset($_REQUEST['country']))
{

	$name =   trim($_POST["name"]);
	$address = trim($_POST["address"]);
	$apartment = $_POST["apartment"];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$city = $_POST['city'];
	$zip = $_POST['zip'];
	$country_code = $_POST['country_code'];
	$alt_mobile = $_POST['alt_mobile'];

	$stmt = $conn->prepare("INSERT INTO `tbl_address`(`user_id`, `name`, `address`, `apartment`, `country`, `state`, `city`, `zip`, `country_code`, `alt_mobile`)

    VALUES (:uid, :rvname, :address, :apartment, :country, :state, :city, :zip, :country_code, :alt_mobile)");
	  
	  $stmt->bindParam(':uid', $_SESSION['LOGIN_ID'], PDO::PARAM_INT);
      $stmt->bindParam(':rvname', $name, PDO::PARAM_STR);
	  $stmt->bindParam(':address', $address, PDO::PARAM_STR);
	  $stmt->bindParam(':apartment', $apartment, PDO::PARAM_STR);
	  $stmt->bindParam(':country', $country, PDO::PARAM_INT);
	  $stmt->bindParam(':state', $state, PDO::PARAM_INT);
	  $stmt->bindParam(':city', $city, PDO::PARAM_INT);
	  $stmt->bindParam(':zip', $zip, PDO::PARAM_INT);
	  $stmt->bindParam(':country_code', $country_code, PDO::PARAM_STR);
	  $stmt->bindParam(':alt_mobile', $alt_mobile, PDO::PARAM_STR);
      $stmt->execute();
		

	 if($stmt) {
	 
	 $last_id = $conn->lastInsertId();
	 
	 $updSetDefault = $conn->prepare("update tbl_address set setByDefault = 1 where id = '$last_id' and user_id = '".$_SESSION['LOGIN_ID']."'");
	 $updSetDefault->execute();
	 $updSetDefault2 = $conn->prepare("update tbl_address set setByDefault = 0 where id != '$last_id' and user_id = '".$_SESSION['LOGIN_ID']."'");
	 $updSetDefault2->execute();
		
		$msg = 'Address Added Successfully..';
						 

    } else {
	
        $msg = 'Error !! Please try again';
		
    }



echo $msg;

}
?>
