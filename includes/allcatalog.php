<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

$html = 
$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildAllcatalog($def, $_LNG);
$pns->showHTML($html);