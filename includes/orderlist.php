<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  	header('Location: /errors/403.shtml');	
  	die();
}

if (!MEMBER_SYS) {
	header('Location: /');
	exit;
}

if(!isset($_SESSION['member']) || empty($_SESSION['member']) || count($_SESSION['member']) == 0) {
	header('Location: ' . (DS . $def->code2 . DS . $_LNG->signin->rewrite . EXT));
	exit;
}

$def->pmid = $arrayPaymentID;
$html = $pns->buildBreadcrumb($def, $_LNG) .
$pns->buildOrder($def, $_LNG);
$pns->showHTML($html);