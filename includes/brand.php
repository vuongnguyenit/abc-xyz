<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']);
$def->PRODUCT_SORTING = $PRODUCT_SORTING;
$def->PRODUCT_DISPLAY = $PRODUCT_DISPLAY;
$def->add2cart = false;
$def->PageSize = ($def->route->name == 'brand' && $def->route->type == 'detail' ? BRAND_ITEM : BRAND_PRODUCT_ITEM);
$html = 
$pns->buildBreadcrumb($def, $_LNG) .
$pns->buildProduct($def, $_LNG);
$pns->showHTML($html);