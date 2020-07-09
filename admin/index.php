<?php

session_start();
error_reporting(0);
include'functions/db.class.php';

if($_REQUEST['submit'])
{
  $email = trim($_POST['email']);
  $pass = trim($_POST['pass']);

  $st = $conn->prepare("select * from tbl_admin where email = :em and password = :pas and vis=1 and type='Master Admin'");
  $st->bindValue(':em', $email, PDO::PARAM_STR);
  $st->bindValue(':pas', $pass, PDO::PARAM_STR);
  $st->execute();
  $check = $st->fetch(PDO::FETCH_ASSOC);


  if($check['id']!='')
  {
		$_SESSION['USER_ID'] = $check['id'];
		

		echo "<script>window.location='home.php';</script>";
  }
  else
	{
    $msg = 'Incorrect username or password';
  }


}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$WebsiteTitle; ?></title>
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
    <?=$logo; ?>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><b><?=$WebsiteTitle; ?> ADMIN PANEL</b></p>
<div class="submit" id="msgdiv">
        	<p style="color:#a42427;text-align: center;"><?php if(isset($msg)){ echo $msg; } ?></p>
        </div>
    <form action="" method="post" name="form1" id="form1">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" title="Please enter registered email id" placeholder="Email" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>" required />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="pass" placeholder="Password" title="Please enter registered password" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>" required />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember" value="1" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="submit" class="btn btn-primary btn-block btn-flat" name="submit" style="background-color:#49bbcf; border-color:#49bbcf;" value="Sign In">
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!--<a href="forget_pass.php" style="color:#a42427;">I forgot my password <i class="fa fa-fw fa-arrow-right"></i></a>-->

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
