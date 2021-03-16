<?php
session_start();
unset($_SESSION['un']);
unset($_SESSION['uid']);
//session_destroy();

header("Location:../login.php");
exit;
?>