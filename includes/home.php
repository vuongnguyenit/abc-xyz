<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}

unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']);
$def->add2cart = FALSE;
$html =
    $pns->buildAdvertising('slide', $utls, $_LNG) .
    $pns->buildAdvertising('home', $utls, $_LNG) .
    $pns->buildSaleAjax() .
    $pns->buildHomeProduct($def, $_LNG) .
    $pns->buildHomeCatalog($def, $_LNG) .
    $pns->buildMenu('m-catalog', $def) .
    $pns->buildNews($def, $_LNG);
$pns->showHTML($html);