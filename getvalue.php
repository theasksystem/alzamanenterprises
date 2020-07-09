<?php
session_start();
error_reporting(0);
include('include/db.class.php');
include('include/functions.php');
///////////////////////////////////////////////Update Address////////////////////////////////////////////////////////////

if(isset($_POST['addid']))
{


$name =   trim($_POST["name"]);
	$address = trim($_POST["address"]);
	$apartment = $_POST["apartment"];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$city = $_POST['city'];
	$zip = $_POST['zip'];
	$addid = $_POST['addid'];
	$country_code = $_POST['country_code'];
	$alt_mobile = $_POST['alt_mobile'];

	$stmt = $conn->prepare("update `tbl_address` set `user_id`=:uid, `name`=:rvname, `address`=:address, `country`=:country, `state`=:state, `city`=:city, `zip`=:zip, `alt_mobile`=:alt_mobile where id = :id");
	  
	  $stmt->bindParam(':uid', $_SESSION['LOGIN_ID'], PDO::PARAM_INT);
      $stmt->bindParam(':rvname', $name, PDO::PARAM_STR);
	  $stmt->bindParam(':address', $address, PDO::PARAM_STR);
	  $stmt->bindParam(':country', $country, PDO::PARAM_INT);
	  $stmt->bindParam(':state', $state, PDO::PARAM_INT);
	  $stmt->bindParam(':city', $city, PDO::PARAM_INT);
	  $stmt->bindParam(':zip', $zip, PDO::PARAM_INT);
	  $stmt->bindParam(':alt_mobile', $alt_mobile, PDO::PARAM_STR);
	  $stmt->bindParam(':id', $addid, PDO::PARAM_INT);
      $stmt->execute();
		

	 if($stmt == true) {
		
		$msg = 'Address Added Successfully..';
						 

    } else {
	
        $msg = 'Error !! Please try again';
		
    }



echo $msg;

 }
 

if(isset($_POST['tab']))
{


 $cat = $_POST['tab'];
 
 
     echo '<option value="">--Select City--</option>';
     
     $query = $conn->prepare("select * from  city where `country_id` = '$cat' AND visible=1 ORDER BY `name_en` ASC");
	 $query->execute();
	 
     while ($row = $query->fetch(PDO::FETCH_ASSOC))
     {
 
?>
    <option value="<?php echo $row['id'];  ?>"><?php echo $row['name_en'];  ?></option>
    
<?php } exit; } ?>



<?php
//--------------------subscribtion section----------------------------//

if(isset($_POST['subscribeEmail']))
{


 $email = $_POST['subscribeEmail'];
 
 $query=$conn->prepare("SELECT * FROM usersubscribe WHERE email='$email'");
 $query->execute();
 
 if($query->rowCount() !=0)
 {
    $msg = '<span style="color:yellow;">You allready subscribed our news-letter<span>';   
 }
 else
 {
    

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
					      <p>Hello New Email To Subscribe<br>
                        </p>
					      <p>Below is subscribe details:</p>
					      <p>Email : '.$email.'</p>
					      <p>Suscribe IP : '.$_SERVER['REMOTE_ADDR'].'</p>
					    </div></td>
            		 </tr>';
            $message2.='<tr>
                        <td style="padding:15px 30px;border-top:1px solid #fafafa;background:#f7f7f7;color:#888">Sent on '.date('d-m-Y').'. &nbsp;&nbsp;Have questions? Email to&nbsp; <a href="'.$messagefooterEmail.'" target="_blank">'.$messagefooterEmail.'</a></td>
                      </tr></tbody></table></td></tr></tbody></table>';
	       
        $headers  = "From: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= "X-Sender: ".$WebsiteTitle." < ".$fromemail." >\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: ".$fromemail."\n"; // Return path for errors
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
		
		$to=$clientmail;
		
    if(mail($to, "New Subscriber", $message2, $headers)) 
    {
	$query2=$conn->prepare("INSERT INTO `usersubscribe`(`email`, `ip`, `created_at`) VALUES ('$email', '".$_SERVER['REMOTE_ADDR']."', now())"); 
	$query2->execute();
      
        $msg = '<span style="color:green;">You are now our subscriber</span>';  
    } 
    else 
    { 
		 $msg = '<span style="color:red;">Error !! Please try again.</span>';  
    }

   

 }
 echo $msg;
}

 ?>