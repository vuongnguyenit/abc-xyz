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

if (!isset($_SESSION['member']) || empty($_SESSION['member']) || count($_SESSION['member']) == 0) {
	$jdata['code'] = 'fail';
	echo json_encode($jdata);
	exit();	
}

$jdata['code'] = 'fail';
$data = $_POST;
if (is_array($data) && count($data) > 0) {
	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';
	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	#include PNSDOTVN_CLS . DS . 'class.utls' . PHP;	
  	include PNSDOTVN_CLS . DS . 'class.cookie' . PHP;
  	$dbf->queryEncodeUTF8();
	$lang = $defaultLang;
	$info = $dbf->getConfig($lang);
	$def = $dbf->arrayToObject($info);
	if (Cookie::Exists('lang') && !Cookie::IsEmpty('lang') && in_array(Cookie::Get('lang'), $arrayLang)) 
		$lang = Cookie::Get('lang');					
  	require_once PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$_LNG = $dbf->arrayToObject($lng);  
  	if (isset($data['action']) && in_array($data['action'], array('add', 'remove'))) {
		$jdata['code'] = 'product';
		$id = (int) $dbf->checkValues($data['id']);
		$status = $dbf->checkProductStatus($id);		
		if ($status) {
			$csid = $_SESSION['member']['id'];
			$def->route->position = 'member';
			$wishlist = $dbf->getWishlist($def, $_LNG);			
			switch($data['action']) {	  	  
				case 'add':
					$jdata['code'] = 'add';
					if (empty($wishlist) || !in_array($id, array_keys($wishlist))) {
						$dbf->buildWishlistControl($id, $wishlist, $data['action'], $csid);	
						$jdata['code'] = 'success';					
					}
					break;			  
				case 'remove':
					$jdata['code'] = 'remove';
					if (is_array($wishlist) && count($wishlist) > 0 && in_array($id, array_keys($wishlist))) {
						$dbf->buildWishlistControl($id, $wishlist, $data['action'], $csid);
						$jdata['code'] = 'success';					
					}
					break;		
			}
		}
	}
  	unset($data, $_POST);
}
echo json_encode($jdata);