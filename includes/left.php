<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

#$pns->printr($_SESSION);
$tmp = $def->route->name;
$def->route->tmp = $tmp;
$html = 
$pns->buildCatalogMenu($def->code, ($def->route->name == 'category' ? $def->route->id : 0), 2) .
$pns->buildProductOptionFilter($def) .
$pns->buildBarPrice($def->route) ;
$def->route->name = 'left';
$html .=
$pns->buildBrand($def, $_LNG) .
$pns->buildSupplier($def, $_LNG) .
$pns->buildAddonFilter($def, $_LNG) .
$pns->buildAdvertising('left', $utls, $_LNG);
$def->route->name = $tmp;
if($tmp == 'sale') {
    $html = $pns->buildListCategory();
}
$pns->showHTML($html);
