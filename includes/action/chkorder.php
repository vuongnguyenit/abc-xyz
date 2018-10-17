<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
	header('Location: /errors/403.shtml');	
  	die();
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if (!$isAjax) {
  	echo 'Access denied - not an AJAX request...';
  	die();
}

$arraymsg['code'] = 'fail';
$data = $_POST;
if (is_array($data) && count($data) > 0) {
  	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';		
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
	$lang = $utls->getCookie('lang', $defaultLang);
	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$dbf->queryEncodeUTF8();
    	
  	if (empty($data['email']) || empty($data['code'])) {
		$arraymsg['code'] = 'missingData';
		echo json_encode($arraymsg);
		exit;	  
  	} else if (!$utls->chk_email($data['email'])) {
		$arraymsg['code'] = 'invalidEmail';
		echo json_encode($arraymsg);
		exit;
  	}
  
  	$arraymsg['code'] = 'invalidData';
  	$q = $dbf->Query('SELECT t2.* FROM ' . prefixTable . 'customer t1 INNER JOIN ' . prefixTable . 'order t2 ON t2.csid = t1.id WHERE t1.email = "' . $data['email'] . '" AND t2.order_code = "' . $dbf->checkValues($data['code']) . '" LIMIT 1');
  	if($dbf->totalRows($q) == 1) {
		$r = $dbf->nextObject($q);
		$arraymsg['order']['code'] = $r->order_code;
		$arraymsg['order']['date'] = date('H:i d-m-Y', $r->ordered);
		$arraymsg['order']['payment'] = $r->payment_name;
		$arraymsg['order']['shipping'] = '-';
		$arraymsg['order']['cost'] = $dbf->pricevnd($r->cost);
		$arraymsg['order']['status'] = $dbf->buildOrderStatusMemo(unserialize($r->status_memo));
		$dbf->freeResult($q);
		
		$q2 = $dbf->Query('SELECT * FROM ' . prefixTable . 'order_detail WHERE order_id = ' . $r->id);
		if($dbf->totalRows($q2) > 0) {
			while ($r2 = $dbf->nextObject($q2)) {
				$arraymsg['order']['detail']['name'][] = $r2->name;
				$arraymsg['order']['detail']['price'][] = $dbf->pricevnd($r2->price);
				$arraymsg['order']['detail']['quantity'][] = $r2->quantity;
				$arraymsg['order']['detail']['amount'][] = $dbf->pricevnd($r2->price * $r2->quantity);
			}
			$dbf->freeResult($q2);
		}
		
		unset($data);  			  
		$arraymsg['code'] = 'success';
  	}    
  	unset($_POST, $data);
}

echo json_encode($arraymsg);
ob_end_flush();