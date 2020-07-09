<?php

error_reporting(0);
session_start();
include'functions/db.class.php';

$msg='';

if(isset($_REQUEST['frogetpass']) && !empty($_REQUEST['email']))
{

	$email = strip_tags(trim($_POST['email']));
	$result = $conn->prepare("select * from tbl_admin where email = '$email'");
    $result->execute();
    $check = $result->fetch(PDO::FETCH_ASSOC);
	
	if($check!=''){
		$to = $check['email'];
		$subject = 'Forget Password';
		$from = 'Password@asianworldmedia.com';
	
		$query=$query."<table width='100%'>";
		$query=$query."<tr><td colspan='3' align='left'><strong>Recover password</strong></td></tr>";
		$query=$query."<tr><td colspan='3' align='left'><strong>Dear ".ucwords($check['username'])."</strong></td></tr>";
		$query=$query."<tr><td>Admin login email is <strong>".$check['email']."</strong></td></tr>";
		$query=$query."<tr><td>Admin login password is <strong>".$check['password']."</strong></td></tr>";
		$query=$query."</table>";
				
	
		if($result === true)
		{
		  
		  mail($to, $subject, $query, "From: <$from>\r\nContent-type: text/html\r\n");
		  $msg = 'Sent Login detail to your email.';
		  $color = '#0C0';
		}
		else
		{
		  $msg = 'Invalid login email id';
		  $color = '#a42427';
		}
	}

}



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Forget Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body class="hold-transition login-page" style="background: #333;">
<div class="login-box">
  <div class="login-logo">
    <!--<a href="#;"><b style="color:#a42427;">Hindu College</b></a>-->
    <img src="images/logo.png" />
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><b>Recover Password</b></p>
<div class="submit" id="msgdiv">
        	<p style="color:<?= $color; ?>;text-align: center;"><?php if(isset($msg)){ echo $msg; } ?></p>
        </div>
    <form action="" method="post" name="form1" id="form1">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" title="Please enter registered email id" placeholder="Email" required />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-8">

        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="button" class="btn btn-primary btn-block btn-flat" name="frogetpass" style="background-color:#49bbcf; border-color:#49bbcf;">Send</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="index.php" style="color:#a42427;"><i class="fa fa-fw fa-arrow-left"></i>Login</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
