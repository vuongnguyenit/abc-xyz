<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  	header('Location: /errors/403.shtml');	
	die();
}

if (!CART_SYS) {
	header('Location: ' . HOST);
	exit;
}

if (!isset($_SESSION['PNSDOTVN_CART']) || empty($_SESSION['PNSDOTVN_CART']) || count($_SESSION['PNSDOTVN_CART']) == 0) {
	@header('Location: ' . (MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->cart->rewrite . EXT);
	exit;
}

if (!isset($_GET['STEP']) || empty($_GET['STEP'])) {
	@header('Location: /thanh-toan.html?STEP=SIGNIN');
	exit;
}

$html = 
#$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildCheckout($def, $_LNG);
$pns->showHTML($html);