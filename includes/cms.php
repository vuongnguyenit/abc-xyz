<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

require_once PNSDOTVN_CAP . DS . 'rand' . PHP;
$_SESSION['captcha_id'] = $strcaptcha;
$def->PageSize = MENU_ARTICLE_ITEM;
$html = $pns->buildBreadcrumb($def, $_LNG) .
$pns->buildNews($def, $_LNG);
$pns->showHTML($html);