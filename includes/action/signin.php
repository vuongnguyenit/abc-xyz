<?php
if (! defined('PHUONG_NAM_SOLUTION'))  {
  header('Location: /errors/403.shtml');	
  die();
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  	echo 'Access denied - not an AJAX request...';
  	die();
}

$arraymsg['code'] = 'fail';
$data = $_POST;
if((!isset($_SESSION['member']) || count($_SESSION['member']) == 0) && is_array($data) && count($data) > 0) {
	include PNSDOTVN_ADM . DS . 'defineConst.php';
	
	if (!MEMBER_SYS)
		die();
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP; 
	$dbf->queryEncodeUTF8();
 	
  	if (empty($data['email']) || empty($data['password'])) {
		$arraymsg['code'] = 'missingData';
		echo json_encode($arraymsg);
		exit;	  
  	} else if (!$utls->chk_email($data['email'])) {
		$arraymsg['code'] = 'invalidEmail';
		echo json_encode($arraymsg);
		exit;
  	}
    
	$q = $dbf->Query('SELECT approve, status, salt, password, id, email, name FROM dynw_customer WHERE groupid = 2 AND email = "' . $data['email'] . '" LIMIT 1');
  	if ($dbf->totalRows($q) == 1) {
		$r = $dbf->nextObject($q);
		if ($r->approve == 0) {
	  		$arraymsg['code'] = 'noneActivate';
	  		echo json_encode($arraymsg);
	  		exit;
		}	
		if ($r->status == 0) {
	  		$arraymsg['code'] = 'lockedAccount';
	  		echo json_encode($arraymsg);
	  		exit;
		}
		$arraymsg['code'] = 'invalidData';
		if (sha1($r->salt . sha1($r->salt . sha1($data['password']))) == $r->password) {	        	  
	  		$cs['ip'] = $_SERVER['REMOTE_ADDR'];
	  		$cs['logined'] = (isset($point['added']) && !empty($point['added'])) ? $point['added'] : time();
	  		$dbf->updateTable(prefixTable . 'customer', $cs, 'id = ' . $r->id);	  	  
	  
	  		if (isset($data['remember']) && $data['remember'] == 1) {
				$selector = base64_encode(openssl_random_pseudo_bytes(9));
				$authenticator = openssl_random_pseudo_bytes(33);			
				setcookie(
					COOKIE_NAME,
					$selector . ':' . base64_encode($authenticator),
					time() + 864000
				);
				$dbf->insertTable(prefixTable . 'auth_tokens', 
					array(
						'selector' => $selector,
						'token' => hash('sha256', $authenticator),
						'userid' => $r->id,
						'expires' => date('Y-m-d\TH:i:s', time() + 864000),
					)
				);
	  		}	  
	  		$_SESSION['member']['id'] = $r->id;	
	  		$_SESSION['member']['name'] = $r->name;
	  		$_SESSION['member']['email'] = $r->email;
			if (isset($_SESSION['PNSDOTVN_BILLING'])) unset($_SESSION['PNSDOTVN_BILLING']);
	  		$arraymsg['code'] = 'success';
		}
		$dbf->freeResult($q);
  	}
  	unset($_POST, $data, $cs, $point);
}

echo json_encode($arraymsg);
ob_end_flush();