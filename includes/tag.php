<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

$def->PageSize = TAG_ARTICLE_ITEM;
$html = 
#$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildNews($def, $_LNG);
$pns->showHTML($html);