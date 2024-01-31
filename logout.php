<?php

session_start();

session_destroy();
unset($_SESSION['schoolsAdmin']);
$_SESSION['schoolsAdmin'] = "";

$_SESSION['auth_msg'] = "You have been successfully logged out";
//header('location: login.php');

?>

<script language="javascript">
    document.location = "login.php";

</script>
