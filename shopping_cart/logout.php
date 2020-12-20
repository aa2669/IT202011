
<?php require_once("nav.php"); ?>
<?php
//since this function call is included we can omit it here. Having multiple calls to session_start() will cause errors/warnings
//session_start();
// remove all session variables
$_SESSION['username'] = null;
$_SESSION['firstname'] = null;
$_SESSION['lastname'] = null;
$_SESSION['user_role'] = null;
$_SESSION["user_id"] = null;
$_SESSION["password"] = null;

header("Location: login.php");
