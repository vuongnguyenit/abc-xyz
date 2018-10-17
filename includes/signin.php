<?php 
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

if (!MEMBER_SYS) {
	header('Location: /');
	exit;
}

if (isset($_SESSION['member']) && count($_SESSION['member']) > 0) {
	header('Location: /');
	exit;
}

$def->redirect = ((isset($_GET['PROCEED_TO_CHECKOUT']) && !empty($_GET['PAGE'])) ? (DS . $def->code2 . DS . 'thanh-toan' . EXT) : ((isset($_GET['PROCEED_TO_CHANGEPWD']) && !empty($_GET['PAGE'])) ? (DS . $def->code2 . DS . 'doi-mat-khau' . EXT) : ((isset($_GET['WRITE_A_COMMENT']) && !empty($_GET['PAGE'])) ? $_GET['PAGE'] : ((isset($_GET['REDIRECT']) && !empty($_GET['PAGE'])) ? $_GET['PAGE'] : '/'))));
$html = 
#$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildForm($def, $_LNG);
$pns->showHTML($html);