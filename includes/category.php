<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

$def->PRODUCT_SORTING = $PRODUCT_SORTING;
$def->PRODUCT_DISPLAY = $PRODUCT_DISPLAY;
$def->add2cart = true;
$def->PageSize = CATALOG_PRODUCT_ITEM;
$html = 
$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildProduct($def, $_LNG);
$pns->showHTML($html);