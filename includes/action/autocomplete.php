<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
	header('Location: /errors/403.shtml');	
	die();
}

include PNSDOTVN_ADM . DS . 'defineConst.php';
include PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';
include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
include PNSDOTVN_CLS . DS . 'class.cart' . PHP;
include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
include PNSDOTVN_CLS . DS . 'class.cookie' . PHP;
$dbf->queryEncodeUTF8();

$html = '';
if (isset($_GET['q']) && !empty($_GET['q']) && isset($_GET['limit']) && $_GET['limit'] > 0) {
	$k = $dbf->checkValues(urldecode($_GET['q']));
	$l = (int) $_GET['limit'];
	$q = $dbf->Query('SELECT t1.id, t2.rewrite, t2.name FROM dynw_product t1 INNER JOIN dynw_product_desc t2 ON t2.id = t1.id INNER JOIN dynw_category t3 ON t3.id = t1.cid WHERE t1.status = 1 AND t3.status = 1 AND t2.lang = "vi-VN" AND t2.name LIKE "%' . $k . '%" ORDER BY t1.hits DESC, t1.ordering, t1.created DESC LIMIT 0,' . $l);
	if (($t = $dbf->totalRows($q)) > 0) {
		$html = "<div class=\"ac_ads\">&nbsp;</div>|\n";
		while ($r = $dbf->nextObject($q)) {
			$path = 'san-pham' . DS . $r->rewrite . '-' . $r->id . EXT;
			$html .= "<div style=\"float:left\">" . $r->name . "</div><div class=\"ac_rc\">&nbsp;</div>|" . $r->name . "|" . $path . "\n";
		}
		$dbf->freeResult($q);
		if ($t <= 10) {
			$q = $dbf->Query('SELECT t1.id, t2.rewrite, t2.name FROM dynw_category t1 INNER JOIN dynw_category_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t2.lang = "vi-VN" AND t2.name LIKE "%' . $k . '%" ORDER BY t1.created DESC LIMIT 0,10');
			if ($dbf->totalRows($q) > 0) {
				while ($r = $dbf->nextObject($q)) {
					$path = $r->rewrite . '-' . $r->id . EXT;
					$html .= "<div style=\"float:left\"><span style=\"font-style:italic\">Danh mục</span> " . $r->name . "</div><div class=\"ac_rc\">&nbsp;</div>|" . $r->name . "|" . $path . "\n";
				}
				$dbf->freeResult($q);
			}
		}
	} else {
		$q = $dbf->Query('SELECT t1.id, t2.rewrite, t2.name FROM dynw_category t1 INNER JOIN dynw_category_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t2.lang = "vi-VN" AND t2.name LIKE "%' . $k . '%" ORDER BY t1.created DESC LIMIT 0,' . $l);
		if ($dbf->totalRows($q) > 0) {
			$html = "<div class=\"ac_ads\">&nbsp;</div>|\n";
			while ($r = $dbf->nextObject($q)) {
				$path = $r->rewrite . '-' . $r->id . EXT;
				$html .= "<div style=\"float:left\"><span style=\"font-style:italic\">Danh mục</span> " . $r->name . "</div><div class=\"ac_rc\">&nbsp;</div>|" . $r->name . "|" . $path . "\n";
			}
			$dbf->freeResult($q);
		}
	}
}
echo $html;