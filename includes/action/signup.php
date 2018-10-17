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
if((!isset($_SESSION['member']) || count($_SESSION['member']) == 0) && is_array($data) && count($data) > 0)
{
  	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';
	
	if (!MEMBER_SYS)
		die();
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP; 
	$lang = $utls->getCookie('lang', $defaultLang);
	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP; 
  	$dbf->queryEncodeUTF8();
   	
  	if(!isset($data['email']) || empty($data['email']) || 
		!isset($data['password']) || empty($data['password']) || 
			!isset($data['salutation']) || empty($data['salutation']) || 
				!isset($data['name']) || empty($data['name']) || 
					!isset($data['mobile']) || empty($data['mobile']) #|| 
						#!isset($data['country']) || empty($data['country']) || 
							#!isset($data['city']) || empty($data['city']) || 
								#!isset($data['district']) || empty($data['district']) || 
									#!isset($data['address']) || empty($data['address'])
									)
  	{
		$arraymsg['code'] = 'missingData';
		echo json_encode($arraymsg);
		exit;	  
  	} else if(!$utls->chk_email($data['email']))
  	{
		$arraymsg['code'] = 'invalidEmail';
		echo json_encode($arraymsg);
		exit;
  	}
  
  	$check = $dbf->getArray(prefixTable . 'customer', 'groupid = 2 AND email = "' . $data['email'] . '"', '');
  	if(count($check) == 1)
  	{
		$arraymsg['code'] = 'emailAddress';
  	} else
  	{    
		unset($data['repassword'], $data['register']);	
		$cs['name']     	= strip_tags(trim($data['name']));
		$cs['email']    	= strip_tags(trim($data['email']));
		$cs['salt']     	= substr(md5(uniqid(rand(), true)), 0, 6);
		$cs['password'] 	= sha1($cs['salt'] . sha1($cs['salt'] . sha1($data['password'])));
		#if(!empty($data['phone'])) $cs['phone'] = strip_tags(trim($data['phone']));
		$cs['mobile']   	= strip_tags(trim($data['mobile']));
		$cs['salutation']	= (in_array($data['salutation'], array_keys($arraySalutation))) ? strip_tags(trim($data['salutation'])) : 'NA';
		$cs['groupid']  	= 2;
		$cs['ip']       	= $_SERVER['REMOTE_ADDR'];
		$cs['status']   	= 1;
		$cs['token']    	= md5($cs['password'] . $cs['salt']);
		$cs['added']    	= time();
		$cs['active']		= md5($cs['added']);
		$csid = $dbf->insertTable(prefixTable . 'customer', $cs);
		if($csid)
		{
	  		$add['csid']     		= $csid;
			#$add['address']  		= strip_tags(trim($data['address']));
			#$add['district_id']   	= (int) $data['district'];
			#$add['district_name'] 	= $dbf->getDistrict2($lang, $add['district_id']);
			#$add['city_id']       	= (int) $data['city'];
			#$add['city_name']     	= $dbf->getCity2($lang, $add['city_id']);
			$add['country_id']    	= 233; //(int) $data['country'];
			$add['country_name']  	= 'Việt Nam'; //$dbf->getCountry2($lang, $add['country_id']);
			#$add['full_address']  	= $add['address'] . ', ' . $add['district_name'] . ', ' . $add['city_name'] . ', ' . $add['country_name'];
			$add['full_address']  	= $add['country_name'];
			$caid = $dbf->insertTable(prefixTable . 'customer_address', $add);
			$dbf->updateTable(prefixTable . 'customer', array('address' => $caid), "id = " . $csid);	  		
		}
	
		$store = $dbf->getStore($lang);
		if (SIGNUP_CONFIRM) {
			$subject = 'Kích hoạt tài khoản trên ' . $dbf->upcaseFirst(SITE_NAME);
			$confirm = HOST . '/' . 'kich-hoat-tai-khoan?ACTIVE_ACCOUNT&EMAIL_ADDRESS=' . $cs['email'] . '&KEY=' . $cs['active'] . '&AUTH=' . sha1($cs['email'] . $cs['added'] . $cs['active']);
			$content = file_get_contents(PNSDOTVN_EMA . DS . 'signup_confirm_' . $lang . EXT);
			$content = str_replace(
				array('{subject}', '{cs_name}', '{cs_email}', '{cs_password}', '{cs_confirm}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
				array($subject, $cs['name'], $cs['email'], $data['password'], $confirm, $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
				$content);
			$mail = $dbf->getArray(prefixTable. 'mail_template', 'status = 1 AND lang = "' . $lang . '" AND name = "signup_confirm"', '', 'stdObject');  
			if(count($mail) == 1)
			{
				$subject = str_replace('{site_name}', $dbf->upcaseFirst(SITE_NAME), $mail[0]->title);
				$content = str_replace(
					array('{subject}', '{cs_name}', '{cs_email}', '{cs_password}', '{cs_confirm}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
					array($subject, $cs['name'], $cs['email'], $data['password'], $confirm, $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
					$mail[0]->content);
			}
		} else {
			$dbf->updateTable(prefixTable . 'customer', array('approve' => 1), "id = " . $csid);
			$subject = 'Đăng ký tài khoản thành công trên ' . $dbf->upcaseFirst(SITE_NAME);
			$content = file_get_contents(PNSDOTVN_EMA . DS . 'signup_' . $lang . EXT);
			$content = str_replace(
				array('{subject}', '{cs_name}', '{cs_email}', '{cs_password}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
				array($subject, $cs['name'], $cs['email'], $data['password'], $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
				$content);
			$mail = $dbf->getArray(prefixTable. 'mail_template', 'status = 1 AND lang = "' . $lang . '" AND name = "signup"', '', 'stdObject');  
			if(count($mail) == 1) {
				$subject = str_replace('{site_name}', $dbf->upcaseFirst(SITE_NAME), $mail[0]->title);
				$content = str_replace(
					array('{subject}', '{cs_name}', '{cs_email}', '{cs_password}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
					array($subject, $cs['name'], $cs['email'], $data['password'], $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
					$mail[0]->content);
			}
		}
	
		#echo $content;
	
		require_once PNSDOTVN_CLS . DS . 'class.phpmailer' . PHP;
		$mail = new PHPMailer(true); 
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                    
		$mail->SMTPAuth   = $arraySMTPSERVER['auth'];                
		$mail->SMTPSecure = $arraySMTPSERVER['secure'];              
		$mail->Host       = $arraySMTPSERVER['host']; 
		$mail->Port       = $arraySMTPSERVER['port'];                   
		$mail->Username   = $arraySMTPSERVER['user'];  
		$mail->Password   = $arraySMTPSERVER['password'];          
		$mail->AddReplyTo($store['mailcontact'], $arraySMTPSERVER['fromname']);
		$mail->AddAddress($cs['email'], $cs['name']);
		$mail->SetFrom('no-reply@' . SITE_NAME, $arraySMTPSERVER['fromname']);
		$mail->Subject = $subject;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($content);
		if(SEND_MAIL) $mail->Send();
		
		unset($_POST, $data, $cs);
		$arraymsg['code'] = 'success';
  	}
}

echo json_encode($arraymsg);
ob_end_flush();