<?php

include('db.class.php');

class AdminLogin
{

  private $email;
  private $password;
  private $conn;
  private $ddate;

  function valid_login($email,$pass)
  {     $connf = new db();
        $date = date('d-m-Y H:s:i');
		$this->email=$email;
		$this->pass=$pass;
		$this->conn= $connf;
		$this->ddate= $date;

		$result=$connf->getOne("select * from admin where email='$email' and password='$pass'");

		if($result !='')
		{
			$connf->updateExecute("UPDATE `admin` SET `pre_date`='".$result['curr_date']."',

			`pre_ip`='".$result['curr_ip']."',`curr_date`='$date',

			`current_ip`='".$_SERVER['REMOTE_ADDR']."' WHERE id='".$result['id']."'");

			$_SESSION['ADMIN_USER_ID'] = $result['id'];
			$_SESSION['LOGIN_DATE'] = $date;
			$_SESSION['ADMIN_USER_IP'] = $_SERVER['REMOTE_ADDR'];

			return true;
		}

		else

            $connf->disconnect();
			return false;

	}


}
