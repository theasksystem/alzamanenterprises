<?php

error_reporting(0);
session_start();

unset($_SESSION['USER_ID']);


if(!isset($_SESSION['USER_ID']) && $_SESSION['USER_ID']=='')
{
	echo "<script>window.location.href='http://alzamanenterprises.com/admin/'</script>";
}



?>