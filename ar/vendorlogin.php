<?php
session_start();
error_reporting(0);
include('../include/db.class.php');

// user login

//if(isset($_POST['email']) && isset($_POST['password'])){
//
//  $email = trim($_POST['email']);
//  $pass = trim($_POST['password']);
//
//  $stmt = $conn->prepare("select * from tbl_admin where email = :email and password = :pass and vis=1");
//  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
//  $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
//  $stmt->execute();
//  $check = $stmt->fetch(PDO::FETCH_ASSOC);
//  $login_id =  $check['id'];
//
//   
//  if($stmt->rowCount() > 0)
//  {
//    	$_SESSION['USER_ID'] = $login_id;
//		$_SESSION['USER_TYPE'] = $check['type'];
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

// Vendor registration

if(isset($_REQUEST['user_email']) && isset($_REQUEST['user_pass']))
{

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
      
	  $msg = 'Email Id Already Registered. Please Use another email id..';
	
	}elseif($count2->rowCount() > 0){
	  
	  $msg = 'Mobile No Already Registered. Please Use another Mobile No..';
	
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
  $qryyy=$conn->prepare("select * from `tbl_admin` where email = '$email' and vis=1");
  $qryyy->execute();
  $qry=$qryyy->fetch(PDO::FETCH_ASSOC);

  $uname=$qry['email'];
  $pass=substr(uniqid(),0,5);
  $myname=$qry['name'];
  $id = $qry['id'];
  $ProductUrl = $WebsiteUrl2.'/new-password-vendor?uid='.base64_encode(base64_encode($qry['id'])).'&res='.base64_encode(base64_encode($pass));

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
					      <p>You are receiving this e-mail because you requested a password reset for Vendor <b>'.$myname.'</b> your temporary password is <b><u>'.$pass.'</u></b> valid for 30 minutes only , request generated from <b>ALZAMAN ENTERPRISES</b>.</p>
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
		
		mail($to, "Vendor Forget Password", $message2, $headers);
		
		$msg = $ProductUrl;
	
	}else{
		$msg=2;
	}
echo $msg;
}

?>
