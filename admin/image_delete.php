<?php
session_start();
//error_reporting(0);
include 'functions/db.class.php';
include('../include/functions.php');

if ($_SESSION['USER_ID'] == '') {
    echo "<script>window.location='index.php';</script>";
}

// get image id
$id2 = $_POST['id2'];

if($_POST['id2']!='')
{
   //echo 'aaaaaa'; 

	 
	$delimage = $conn->prepare("SELECT * FROM `product_images` WHERE `id` = '$id2'");
	$delimage->execute();
	$imgval = $delimage->fetch(PDO::FETCH_ASSOC);
	//echo $imgval['image'];

	$delCenter = $conn->prepare("DELETE FROM `product_images` WHERE `id` = '$id2'");
	$delCenter->execute();
	unlink('../adminuploads/product/'.$imgval['image']);
	
     
}

if(isset($_POST['editval']) && $_POST['column']!=''){

    $color_id = $_POST["column"];
    $val = $_POST["editval"];
    $prod_id = $_POST['prod_id'];
  
  $getCenter = $conn->prepare("update product_images set stock = '$val' where product_id='$prod_id' and color_id = '$color_id'");
  $getCenter->execute();
  
   $TotalQuantity = TotalQuantitybyColor($conn,$prod_id,$color_id);
   $totalsales = $conn->prepare( "select sum(qty) as total_sale from cart_order_item where pid= '$prod_id' and order_id IN(select id from cart_orders where status!='rejected')");
   $totalsales->execute();
   $totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC);
   $stockrest=$totalsaleamt['total_sale'];
   $totalstock=$TotalQuantity-$stockrest;
  
  if($getCenter== true)
  			{
  			 if($totalstock > 0){
  			    $totalsales = $conn->prepare( "select a.user_id,a.id,b.product_name_en,b.product_name_ar,b.slug from send_notify a left join products b on a.product_id=b.id where a.product_id = '$prod_id' and a.send_mail = 0");
                $totalsales->execute();
                while($totalsaleamt = $totalsales->fetch(PDO::FETCH_ASSOC)){
                    
                   $userDetail2 = $conn->prepare( "select email,phone,name,lastname from registration where id = '".$totalsaleamt['user_id']."'");
                   $userDetail2->execute(); 
                   $userDetail = $userDetail2->fetch(PDO::FETCH_ASSOC); 
                   $fullname = $userDetail['name'].' '.$userDetail['lastname'];
                   
                   $message2='<table width="100%" cellspacing="0" cellpadding="20" border="0">
                             <tbody>
								<tr>
								  <td><table style="border:1px solid #ddd" align="center" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
									  <tbody>
										<tr>
										  <td><table width="100%" cellspacing="0" cellpadding="0" bgcolor="#000">
											  <tbody>
												<tr>
												  <td style="padding:20px 30px" valign="center" height="88" bgcolor="#000"><img title="'.$WebsiteTitle.'" src="'.$WebsiteUrl.'/'.$logo2.'" alt="'.$WebsiteTitle.'" class="CToWUd" style="width:400px"></td>
												  <td style="font-family:Georgia,serif;font-size:14px;color:#999;font-style:italic;border-bottom:1px solid #f5f5f5" valign="center" bgcolor="#F0F0F0"></td>
												</tr>
											  </tbody>
											</table>
										  </td>
            							</tr>';
            							$message2.='<tr>
                      <td style="border-bottom:1px solid #eaebea">
					    <div style="line-height:21px;font-size:14px;color:#444;padding:30px;font-family:Georgia,serif;background:white;border-top:1px solid #ccc">
					    <p style="text-align:right">نبشرك ! لقد وصلنا المنتج الذي بحثت عنه <b>'.$totalsaleamt['product_name_ar'].'</b> ، يمكنك التوجه فورا إلى

</p>
						<p style="text-align:right"> موقعنا  او الضغط على رابط المنتج <a href="'.$WebsiteUrl.'/'.$totalsaleamt['slug'].'"><b> هنا </b></a> للحصول عليه والاستمتاع به</p>
                            <p style="text-align:right"> ،تحياتنا </p>
                            <p style="text-align:right"> الزمان للمشاريع</p>
                            <p>Dear  '.$fullname.' <br>
                            You’re the first to know. The <b>'.$totalsaleamt['product_name_en'].'</b> is back in stock and ready to be shopped in a click.<br> 
                            Please login and place your order or just click  on the product link <a href="'.$WebsiteUrl.'/'.$totalsaleamt['slug'].'"><b>here</b></a> Waiting to see you on our website.<br> 
                            Thank you <br>
                            Regards <br>
                            Team Alzamanenterprises</p>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#000;color:#fff">
                        Disclaimer: This is a system generated email. Please don’t reply to it. For any information contact us through email, <a href="mailto:'.$messagefooterEmail.'">'.$messagefooterEmail.'</a> or call on 31559977<br>
                        <p style="text-align:right">تنبيه: هذه الرسالة مرسلة من قبل النظام الآلي، نرجو عدم الرد عليها. لمزيد من المعلومات يرجى التواصل معنا عبر البريد الإلكتروني <a href="mailto:'.$messagefooterEmail.'">'.$messagefooterEmail.'</a> أو الاتصال على 31559977</p></td>
                      </tr></tbody></table></td></tr></tbody></table>';
                      
                        $headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
                		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
                		$headers .= 'X-Mailer: PHP/' . phpversion();
                		$headers .= "X-Priority: 1\n"; // Urgent message!
                		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
                		$headers .= "MIME-Version: 1.0\r\n";
                		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
                		
                		mail($userDetail['email'], "Good news your ".$totalsaleamt['product_name_en'], $message2, $headers);
                		$sms = 'نبشرك ! لقد وصلنا المنتج الذي بحثت عنه '.$totalsaleamt['product_name_ar'].' ، يمكنك التوجه فورا إلى
موقعنا  او الضغط على رابط المنتج '.$WebsiteUrl.'/'.$totalsaleamt['slug'].' للحصول عليه والاستمتاع به
،تحياتنا 
الزمان للمشاريع,
Dear  '.$fullname.'
You are the first to know. The '.$totalsaleamt['product_name_en'].' is back in stock and ready to be shopped in a Click.
Please login and place your order Or just click  on the product link '.$WebsiteUrl.'/'.$totalsaleamt['slug'].'
Look forward to seeing you on our website.
Thank you
Regards
Team Alzamanenterprises';
                		
                		SendSms($userDetail['phone'],$sms);
                		
                		
                		$changeStatus = $conn->prepare("update send_notify set send_mail = 1 where id='".$totalsaleamt['id']."'");
                        $changeStatus->execute();
                } 
  			     
  			     
  			 }
  			echo 'Stock Updated Successfully';
			}
		else
			{
			echo 'Sorry Some Error';
			}
  
}

if(isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id']!='')
{
    ini_set("SMTP", "mail.alzamanenterprises.com");
    ini_set("sendmail_from", $fromemail);
  $id=$_POST['vendor_id'];
  
  $qryyy=$conn->prepare("select * from `tbl_admin` where id = '$id' and vis=1");
  $qryyy->execute();
  $qry=$qryyy->fetch(PDO::FETCH_ASSOC);

  $uname=$qry['email'];
  $pass=substr(uniqid(),0,5);
  $myname=$qry['name'];
  $id = $qry['id'];
  $ProductUrl = $WebsiteUrl.'/new-password-vendor?uid='.base64_encode(base64_encode($qry['id'])).'&res='.base64_encode(base64_encode($pass));

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
												  <td style="padding:20px 30px" valign="center" height="88" bgcolor="#000"><img title="'.$WebsiteTitle.'" src="'.$WebsiteUrl.'/'.$logo2.'" alt="'.$WebsiteTitle.'" class="CToWUd" style="width:400px"></td>
												  <td style="font-family:Georgia,serif;font-size:14px;color:#999;font-style:italic;border-bottom:1px solid #f5f5f5" valign="center" bgcolor="#F0F0F0"></td>
												</tr>
											  </tbody>
											</table>
										  </td>
            							</tr>';
           $message2.='<tr>
                      <td style="border-bottom:1px solid #eaebea">
					    <div style="line-height:21px;font-size:14px;color:#444;padding:30px;font-family:Georgia,serif;background:white;border-top:1px solid #ccc">

					      <p>Congratulation dear '.$myname.' your store Have been successfully approved</p>
						  <p>Your OTP : '.$pass.'</p>
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

	if(mail($to, "Store Approved", $message2, $headers)){
		
		
		
		$msg = 1;
	
	}else{
		$msg=2;
	}
echo $msg;
}

if(isset($_POST['msgId']) && $_POST['msgId']!=''){

    $msgId = $_POST["msgId"];
      $qryyy=$conn->prepare("select * from `tbl_query` where id = '$msgId'");
      $qryyy->execute();
      $qry=$qryyy->fetch(PDO::FETCH_ASSOC);
      ?>

    <table width="100%" class="table table-bordered table-striped">
      <tr>
      <th>Name</th>
      <td><?=$qry['name']; ?></td>
      </tr>
      <tr>
      <th>Email</th>
      <td><?=$qry['email']; ?></td>
      </tr>
      <tr>
      <th>Subject</th>
      <td><?=$qry['subject']; ?></td>
      </tr>
      <tr>
      <th>Message</th>
      <td><?=$qry['msg']; ?></td>
      </tr>
    </table>
<?php  
}

?>