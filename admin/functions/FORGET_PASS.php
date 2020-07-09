<?php

include('db.class.php');

class ForgetPass
{

  private $email;
  private $conn;

  function forget_pass($email)
  {     $connf = new db();
		$this->email=$email;
		$this->conn= $connf;

		$result=$connf->getOne("select * from admin where email='$email'");

		if($result !='')
		{
			$to=$result['email'];
			$subject='Forget Password';
			$from = 'Password@asianworldmedia.com';

			$query=$query."<table width='100%'>";
			$query=$query."<tr><td colspan='3' align='left'><strong>Recover password</strong></td></tr>";
			$query=$query."<tr><td colspan='3' align='left'><strong>Dear ".ucwords($result['name'])."</strong></td></tr>";
			$query=$query."<tr><td>Admin login email is <strong>".$result['email']."</strong></td></tr>";
			$query=$query."<tr><td>Admin login password is <strong>".$result['password']."</strong></td></tr>";
			$query=$query."</table>";
			mail($to, $subject, $query, "From: <$from>\r\nContent-type: text/html\r\n");
			return true;
		}

		else

            $connf->disconnect();
			return false;

	}


}
