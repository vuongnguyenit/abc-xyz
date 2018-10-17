<?php 
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

if (!MEMBER_SYS)
{
	header('Location: /');
	exit;
}

if(isset($_SESSION['member']) && count($_SESSION['member']) > 0)
{
	header('Location: ' . '/');
	exit;
}

$html = 
$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildForm($def, $_LNG);
$pns->showHTML($html);