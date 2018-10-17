<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
	header('Location: /errors/403.shtml');	
	die();
}

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) 
{
  	echo 'Access denied - not an AJAX request...';
  	die();
}

$arraymsg['code'] = 'fail';

$data = $_POST;
if (is_array($data) && count($data) > 0 && isset($_SESSION['PNSDOTVN_CART']) && count($_SESSION['PNSDOTVN_CART']) > 0) {		
  	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';
	
	if (MEMBER_SYS && (!isset($_SESSION['member']) || empty($_SESSION['member']) || count($_SESSION['member']) == 0) && !CHECKOUT_NOT_REG) {
		$arraymsg['code'] = 'login';
		echo json_encode($arraymsg);
		exit;
	}	
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
  	include PNSDOTVN_CLS . DS . 'class.cart' . PHP;
  	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
	include PNSDOTVN_CLS . DS . 'class.cookie' . PHP;
  	$dbf->queryEncodeUTF8();
	$lang = $defaultLang;
  	if(Cookie::Exists('lang') && !Cookie::IsEmpty('lang') && in_array(Cookie::Get('lang'), $arrayLang)) 
		$lang = Cookie::Get('lang');
  	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$_LNG = $dbf->arrayToObject($lng);	
  
  	if(empty($data['billing_name']) || empty($data['billing_address']) || empty($data['billing_ward']) || empty($data['billing_district']) || empty($data['billing_city']) || empty($data['billing_mobile']) || 
     empty($data['shipping_name']) || empty($data['shipping_address']) || empty($data['shipping_ward']) || empty($data['shipping_district']) || empty($data['shipping_city']) || empty($data['shipping_mobile']))
  	{
		$arraymsg['code'] = 'missingData';
		echo json_encode($arraymsg);
		exit;	  
  	} else if((!isset($_SESSION['member']['id']) && empty($data['billing_email'])) || (isset($data['billing_email']) && !$utls->chk_email($data['billing_email'])))
  	{
		$arraymsg['code'] = 'invalidEmail';
		echo json_encode($arraymsg);
		exit;
  	}
                
  	$subtotal = $cart->totalprice();  	
  	$cs['tax_rate'] = 0;
  	$cs['tax_amount'] = 0;
    
  	$cs['csid'] = MEMBER_SYS && isset($_SESSION['member']['id']) ? $_SESSION['member']['id'] : 0;  
  	$cs['sub_total'] = $subtotal;
  	$tax = 0;
  	if(isset($data['tax']) && $data['tax'] == 1)
  	{
		$cs['tax_rate'] = TAX_RATE;
		$cs['tax_amount'] = $cs['sub_total'] * ($cs['tax_rate'] / 100);
		$tax = 1;
  	}
  	$cs['cost'] = $cs['sub_total'] + $cs['tax_amount'];
	
	
	/*** BEGIN: POINT_SYSTEM */
	if (MEMBER_SYS && isset($_SESSION['member']) && count($_SESSION['member']) > 0 && POINT_REWARD && isset($data['pns_point']) && $data['pns_point'] > 0) {
		$a = $_SESSION['member']['id'];
		$b = (int) $data['pns_point'];		
		$c = $dbf->getPoint();		
		if ($c >= $b) { /* So diem hien co phai lon hon so diem muon quy doi */			
			$d = $dbf->getPoint2Money();
			if ($d > 0) { /* Cau hinh so tien tuong duong 1 diem phai lon hon 0 */
				$e = $c - $b;
				$f = $b * $d;
				if ($cs['cost'] >= $f) { /* Tong gia tri don hang phai lon hon hoac bang so tien duoc giam tru */
					$dbf->updateTable(prefixTable. 'customer', array('point' => $e), 'id = ' . $a); 
					$g = array(
						'member' => $a,
						'action' => 2, /* TRANG THAI: Tru diem tang */
						'point' => $b,
						'related' => 1,
						'status' => 1,
						'added' => time()
					);
					$phid = $dbf->insertTable(prefixTable. 'point_history', $g);
					$cs['cost'] = $cs['cost'] - $f;
					$cs['point'] = $b;
					$cs['point_amount'] = $f;
					$h = $dbf->getMoney2Point();
					$cs['point_award'] = floor ($cs['cost'] / $h);
				}
			}
		}
	}
	/* END: POINT_SYSTEM ***/
	
  	$cs['groupid'] = isset($m['groupid']) && !empty($m['groupid']) ? $m['groupid'] : 1;
  
  	$cs['billing_name'] = $dbf->checkValues($data['billing_name']);
  	if(isset($data['billing_phone']) && !empty($data['billing_phone'])) $cs['billing_phone'] = $dbf->checkValues($data['billing_phone']);
  	$cs['billing_mobile'] = $dbf->checkValues($data['billing_mobile']);    
  	$cs['billing_address'] = $dbf->checkValues($data['billing_address']);
	$cs['billing_ward_id'] = (int) $dbf->checkValues($data['billing_ward']);
  	#$cs['billing_ward_name'] = $dbf->getWard($lang, $cs['billing_ward_id']);
  	$cs['billing_district_id'] = (int) $dbf->checkValues($data['billing_district']);
  	#$cs['billing_district_name'] = $dbf->getDistrict($lang, $cs['billing_district_id']);
  	$cs['billing_city_id'] = (int) $dbf->checkValues($data['billing_city']);
  	#$cs['billing_city_name'] = $dbf->getCity($lang, $cs['billing_city_id']);
		
	$ward_name = $dbf->getLocation('ward', $cs['billing_district_id'], $cs['billing_ward_id']);
	$cs['billing_ward_name'] = $ward_name[0]->name;
	$district_name = $dbf->getLocation('district', $cs['billing_city_id'], $cs['billing_district_id']);
	$cs['billing_district_name'] = $district_name[0]->name;
	$city_name = $dbf->getLocation('city', 0, $cs['billing_city_id']);
	$cs['billing_city_name'] = $city_name[0]->name;
	
  	#$cs['billing_country_id'] = (int) $dbf->checkValues($data['billing_country']);
  	#$cs['billing_country_name'] = $dbf->getCountry($lang, $cs['billing_country_id']);
	$cs['billing_country_id'] = 233;
	$cs['billing_country_name'] = 'Việt Nam';
  	$cs['billing_full_address'] = $cs['billing_address'] . ', ' . $cs['billing_ward_name'] . ', ' . $cs['billing_district_name'] . ', ' . $cs['billing_city_name'] . ', ' . $cs['billing_country_name'];
  
  	if ($cs['csid'] == 0)
	{
		$c = array(
			'name' => $cs['billing_name'],
			'email' => $data['billing_email'],
			'phone' => isset($cs['billing_phone']) ? $cs['billing_phone'] : '',
			'mobile' => $cs['billing_mobile'],
			'groupid' => 1,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'status' => 1,
			'approve' => 1,
			'added' => time()
		);
		$cs['csid'] = $dbf->insertTable(prefixTable. 'customer', $c);
		if ($cs['csid'])
		{
			$ca = array(
				'csid'     		=> $cs['csid'],
				'address'  		=> $cs['billing_address'],
				'ward_id' 		=> $cs['billing_ward_id'],
				'ward_name'		=> $cs['billing_ward_name'],
				'district_id' 	=> $cs['billing_district_id'],
				'district_name'	=> $cs['billing_district_name'],
				'city_id'      	=> $cs['billing_city_id'],
				'city_name'    	=> $cs['billing_city_name'],
				'country_id'   	=> $cs['billing_country_id'],
				'country_name'	=> $cs['billing_country_name'],
				'full_address'	=> ($cs['billing_address'] . ', ' . $cs['billing_ward_name'] . ', ' . $cs['billing_district_name'] . ', ' . $cs['billing_city_name'] . ', ' . $cs['billing_country_name'])
			);
			$caid = $dbf->insertTable(prefixTable. 'customer_address', $ca);
			$dbf->updateTable(prefixTable . 'customer', array('address' => $caid), "id = " . $cs['csid']);
		}
	}
  
  	$cs['shipping_name'] = $dbf->checkValues($data['shipping_name']);
  	if(isset($data['shipping_phone']) && !empty($data['shipping_phone'])) $cs['shipping_phone'] = $dbf->checkValues($data['shipping_phone']);
  	$cs['shipping_mobile'] = $dbf->checkValues($data['shipping_mobile']); 
  	$cs['shipping_address'] = $dbf->checkValues($data['shipping_address']);
	$cs['shipping_ward_id'] = (int) $dbf->checkValues($data['shipping_ward']);
  	#$cs['shipping_ward_name'] = $dbf->getWard($lang, $cs['shipping_ward_id']);
  	$cs['shipping_district_id'] = (int) $dbf->checkValues($data['shipping_district']);
  	#$cs['shipping_district_name'] = $dbf->getDistrict($lang, $cs['shipping_district_id']);
  	$cs['shipping_city_id'] = (int) $dbf->checkValues($data['shipping_city']);
  	#$cs['shipping_city_name'] = $dbf->getCity($lang, $cs['shipping_city_id']);
	
	$ward_name = $dbf->getLocation('ward', $cs['shipping_district_id'], $cs['shipping_ward_id']);
	$cs['shipping_ward_name'] = $ward_name[0]->name;
	$district_name = $dbf->getLocation('district', $cs['shipping_city_id'], $cs['shipping_district_id']);
	$cs['shipping_district_name'] = $district_name[0]->name;
	$city_name = $dbf->getLocation('city', 0, $cs['shipping_city_id']);
	$cs['shipping_city_name'] = $city_name[0]->name;
	
  	#$cs['shipping_country_id'] = (int) $dbf->checkValues($data['shipping_country']);
  	#$cs['shipping_country_name'] = $dbf->getCountry($lang, $cs['shipping_country_id']);
	$cs['shipping_country_id'] = 233;
	$cs['shipping_country_name'] = 'Việt Nam';
  	$cs['shipping_full_address'] = $cs['shipping_address'] . ', ' . $cs['shipping_ward_name'] . ', ' . $cs['shipping_district_name'] . ', ' . $cs['shipping_city_name'] . ', ' . $cs['shipping_country_name'];
  
  	$cs['payment_id'] = isset($data['payment_method']) && in_array($data['payment_method'], array_keys($arrayPaymentID)) ? (int) $dbf->checkValues($data['payment_method']) : 1;
  	$cs['payment_name'] = $arrayPaymentName[$arrayPaymentID[$cs['payment_id']]];
  
  	if(isset($data['company']) && !empty($data['company']) && $tax == 1) $cs['company'] = $dbf->checkValues($data['company']);
  	if(isset($data['company_address']) && !empty($data['company_address']) && $tax == 1) $cs['company_address'] = $dbf->checkValues($data['company_address']);
  	if(isset($data['tax_code']) && !empty($data['tax_code']) && $tax == 1) $cs['tax_code'] = (int) $dbf->checkValues($data['tax_code']);
  	if($data['order_note'] && !empty($data['order_note'])) $cs['order_note'] = $dbf->checkValues($data['order_note']);
  
  	$cs['ordered'] = time();
  	$cs['ordered_by'] = $cs['csid'];  
  	$oid = $dbf->insertTable(prefixTable . 'order', $cs);
  	if($oid)
  	{
		if (isset($phid) && $phid > 0) {
			$dbf->updateTable(prefixTable. 'point_history', array('rid' => $oid), 'id = ' . $phid); 
		}
		$dbf->updateTable(prefixTable. 'order', array('order_code' => 'ORD-' . date('ymd') . $oid . '#'), "id = '" . $oid . "'"); 
		$pns_cart = $_SESSION['PNSDOTVN_CART'];	
		foreach($pns_cart as $k => $v)
		{  
	  		$id = explode('|', $k);
			$rst = $dbf->getDynamicJoin(prefixTable . 'product', prefixTable . 'product_desc', array('rewrite' => 'rewrite'), 'INNER JOIN', 't1.status = 1 AND t1.id = ' . $id[0] . ' AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
			$row = $dbf->nextObject($rst);
			$link = HOST . DS . substr($lang, 0, -3) . DS . $_LNG->product->rewrite . DS . $row->rewrite . '-' . $row->id . EXT;
			$c = $dbf->getColorbyCode($id[2], $lang);
			$s = $dbf->getSizebyCode($id[4], $lang);
			$jdata = unserialize($row->info);
			$jcolor = isset($jdata['color']) ? $jdata['color'] : '';
			$info = array(
			  	'code' => $row->code,
			  	'color' => (isset($c->name) ? $c->name : ''),
				'size' => (isset($s->name) ? $s->name : '')
			);
			$dbf->insertTable(prefixTable . 'order_detail',
				array(
					'order_id' => $oid,
					'product_id' => $k,
					'customer_id' => $cs['csid'],
					'name' => $row->name,
					'link' => $link,
					'list_price' => $row->list_price,
					'sale_off' => $row->sale_off,
					'price' => (isset($jcolor[$id[1]]['price']) ? $jcolor[$id[1]]['price'] : $row->price),
					'quantity' => $v,
					'info' => serialize($info)
				)
			);
		}	  
		
		$arraymsg['code'] = 'success';
		$_SESSION['PNSDOTVN_CHECKOUT'] = $oid;
		$_SESSION['PNSDOTVN_CHECKOUT_CSID'] = $cs['csid'];
		unset($_POST, $data, $cs);
  	}
}

echo json_encode($arraymsg);
ob_end_flush();