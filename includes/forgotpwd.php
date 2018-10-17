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
	header('Location: ' . DS . $_LNG->changepwd->rewrite . EXT);
	exit;
}

require_once PNSDOTVN_CAP . DS . 'rand' . PHP;
$_SESSION['captcha_id'] = $strcaptcha;

$def->redirect = (MULTI_LANG ? DS . $def->code2 : '') . DS . 'dang-nhap' . EXT;
$html = 
$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildForm($def, $_LNG);
$pns->showHTML($html);