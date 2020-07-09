<?php

error_reporting(0);
session_start();

unset($_SESSION['USER_ID']);
unset($_SESSION['USER_TYPE']);

if(!isset($_SESSION['USER_ID']) && $_SESSION['USER_ID']=='')
{
	echo "<script>window.location.href='../vendors'</script>";
}



?>