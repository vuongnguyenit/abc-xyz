<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  	header('Location: /errors/403.shtml');	
  	die();
}

if (!CART_SYS) {
	header('Location: ' . HOST);
	exit;
}
$html = $pns->buildBreadcrumb($def, $_LNG) .
$pns->buildCart($def, $_LNG);
$pns->showHTML($html);