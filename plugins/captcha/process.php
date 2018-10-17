<?php
@session_start();
$captcha=strtoupper(str_replace(" ","",$_GET['captcha']));
$session=strtoupper(str_replace(" ","",$_SESSION['captcha_id']));
if($captcha==$session) echo 'true';
else echo 'false';
?>