<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) 
{
  	echo 'Access denied - not an AJAX request...';
  	die();
}

include PNSDOTVN_ADM . DS . 'defineConst.php';
include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;

if (!MEMBER_SYS)
{
	header('Location: /');
	exit;
}

$dbf->deleteDynamic(prefixTable . 'auth_tokens', 'userid = ' . $_SESSION['member']['id']);
if(isset($_SESSION['member']))
	unset($_SESSION['member']);
if(isset($_COOKIE[COOKIE_NAME]))
	setcookie(COOKIE_NAME, '', time() - COOKIE_TIME);
if(isset($_SESSION['PNSDOTVN_BILLING']))
	unset($_SESSION['PNSDOTVN_BILLING']);	
$msg = 'success';
echo $msg;