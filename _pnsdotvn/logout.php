<?php
error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
ob_start();
@session_start();
if(isset($_SESSION['user']) AND !empty($_SESSION['user'])) {
	unset($_SESSION['adminid'], $_SESSION['user'], $_SESSION['admission']);
	session_unset();
	session_destroy();
	header( "Location: login.jsp" );
} else {
    header( "Location: login.jsp" );
}
ob_end_flush()
?>