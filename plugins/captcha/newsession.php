<?php
// Include the random string file
include str_replace("\\","/",dirname(__FILE__)).'/rand.php';
// Begin a new session
@session_start();
// Set the session contents
$_SESSION['captcha_id'] = $strcaptcha;
echo ' ';
?>