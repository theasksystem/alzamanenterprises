<?php

error_reporting(0);
session_start();

unset($_SESSION['VENDOR_ID']);
unset($_SESSION['USER_TYPE']);

if($_SESSION['VENDOR_ID']=='')
{
  echo "<script>window.location.href='../vendors'</script>";
}



?>