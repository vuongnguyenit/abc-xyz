<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  	echo 'Access denied - not an AJAX request...';
  	die();
}

$arraymsg['code'] = 'fail';
$data = $_POST;
if(isset($_SESSION['member']) && count($_SESSION['member']) > 0 && is_array($data) && count($data) > 0) {
  	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';	
	if (!MEMBER_SYS) die();	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP; 
	#$lang = $utls->getCookie('lang', $defaultLang);
	$lang = $defaultLang;
	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$dbf->queryEncodeUTF8();		
   	
  	if(!isset($data['salutation']) || empty($data['salutation']) || 
		!isset($data['name']) || empty($data['name']) || 
			!isset($data['mobile']) || empty($data['mobile']) || 				
					!isset($data['country']) || empty($data['country']) || 
						!isset($data['city']) || empty($data['city']) || 
							!isset($data['district']) || empty($data['district']) || 
								!isset($data['ward']) || empty($data['ward']) || 
									!isset($data['address']) || empty($data['address'])) {
		$arraymsg['code'] = 'missingData';
		echo json_encode($arraymsg);
		exit;	  
  	} else if(!empty($data['birthdate']) && !$utls->valid_date($data['birthdate'], 'DD/MM/YYYY', 'birthdate')) {
		$arraymsg['code'] = 'birthdateNotValid';
		echo json_encode($arraymsg);
		exit;  
  	}
  
  	$arraymsg['code'] = 'notAvailable';
  	$check = $dbf->getArray(prefixTable . 'customer', 'groupid = 2 AND status = 1 AND approve = 1 AND id = ' . $_SESSION['member']['id'], '');
  	if (count($check) == 1) {	   		
		unset($data['change'], $data['district_name'], $data['city_name'], $data['country_name']);
		$cs['name']      		= strip_tags(trim($data['name']));
		$cs['birthdate'] 		= strip_tags(trim($data['birthdate']));
		#$cs['gender']    		= (in_array($data['gender'], array_keys($arrayGender))) ? strip_tags(trim($data['gender'])) : 'NA';
		$cs['salutation']    	= (in_array($data['salutation'], array_keys($lng['member']['salutation']['list']))) ? strip_tags(trim($data['salutation'])) : 'NA';
		#$cs['pin']   			= (strip_tags(trim($data['pin'])));
		$cs['phone']     		= !empty($data['phone']) ? strip_tags(trim($data['phone'])) : '';
		$cs['mobile']    		= strip_tags(trim($data['mobile']));
		$cs['updated']   		= time();
		$dbf->updateTable(prefixTable . 'customer', $cs, 'id = ' . $_SESSION['member']['id']);
		$add['address']  		= strip_tags(trim($data['address']));
		$add['ward_id']   		= (int) $data['ward'];
		$ward_name 				= $dbf->getLocation('ward', $data['district'], $add['ward_id']);
		$add['ward_name'] 		= $ward_name[0]->name;
		$add['district_id']   	= (int) $data['district'];
		$district_name 			= $dbf->getLocation('district', $data['city'], $add['district_id']);
		$add['district_name'] 	= $district_name[0]->name;
		$add['city_id']       	= (int) $data['city']; 
		$city_name 				= $dbf->getLocation('city', 0, $add['city_id']);
		$add['city_name']     	= $city_name[0]->name;
		#$add['country_id']    	= (int) $data['country'];
		$add['country_id']    	= 233;
		#$add['country_name']  	= $dbf->getCountry2($lang, $add['country_id']);
		$add['country_name']  	= 'Việt Nam';
		$add['full_address']  	= $add['address'] . ', ' . $add['ward_name'] . ', ' . $add['district_name'] . ', ' . $add['city_name'] . ', ' . $add['country_name'];
		$dbf->updateTable(prefixTable . 'customer_address', $add, 'csid = ' . $_SESSION['member']['id']);				
		$arraymsg['info'] = array(
			'salutation' => $lng['member']['salutation']['list'][$cs['salutation']], 
			'ward' => $add['ward_name'], 
			'district' => $add['district_name'], 
			'city' => $add['city_name'], 
			'country' => $add['country_name']);
		$_SESSION['member']['name'] = $data['name'];
		$arraymsg['code'] = 'success';
		unset($_POST, $data, $cs, $add);
  	}
}

echo json_encode($arraymsg);
ob_end_flush();