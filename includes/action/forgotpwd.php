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
	
	if (!MEMBER_SYS)
		die();
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
	$lang = $utls->getCookie('lang', $defaultLang);
	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$dbf->queryEncodeUTF8();
    	
  	if(empty($data['email']) || empty($data['captcha']) || !isset($_SESSION['captcha_id']) || $data['captcha'] <> $_SESSION['captcha_id'])
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
  
  	$arraymsg['code'] = 'invalidData';
  	$check = $dbf->getArray(prefixTable . 'customer', 'groupid = 2 AND status = 1 AND approve = 1 AND email = "' . $data['email'] . '"', '');
  	if(count($check) == 1)
  	{
		unset($data['email'], $data['send'], $data['captcha']);  
		$confirm = HOST . '/' . 'yeu-cau-gui-mat-khau?REQUEST_NEW_PWD&EMAIL_ADDRESS=' . $check[0]['email'] . '&TOKEN=' . $check[0]['token'] . '&AUTH=' . sha1($check[0]['email'] . $check[0]['added'] . $check[0]['token']);
		$store = $dbf->getStore($lang);
		$subject = 'Yêu cầu gửi mật khẩu tại ' . $dbf->upcaseFirst(SITE_NAME);
		$content = file_get_contents(PNSDOTVN_EMA . DS . 'forgotpwd_' . $lang . EXT);
		$content = str_replace(	
	  		array('{subject}', '{cs_name}', '{cs_email}', '{cs_confirm}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
	  		array($subject, $check[0]['name'], $check[0]['email'], $confirm, $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
	  		$content);	  
		$mail = $dbf->getArray(prefixTable. 'mail_template', 'status = 1 AND lang = "' . $lang . '" AND name = "forgotpwd"', '', 'stdObject');  
		if(count($mail) == 1)
		{
	  		$subject = str_replace('{site_name}', $dbf->upcaseFirst(SITE_NAME), $mail[0]->title);
	  		$content = str_replace(
				array('{subject}', '{cs_name}', '{cs_email}', '{cs_confirm}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
				array($subject, $check[0]['name'], $check[0]['email'], $confirm, $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
				$mail[0]->content);
		}
	
		#$content;
	
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
		$mail->AddAddress($check[0]['email'], $check[0]['name']);
		$mail->SetFrom('no-reply@' . SITE_NAME, $arraySMTPSERVER['fromname']);
		$mail->Subject = $subject;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($content);
		if(SEND_MAIL) $mail->Send();		  
		$arraymsg['code'] = 'success';
  	}    
  	unset($_POST, $data);
}

echo json_encode($arraymsg);
ob_end_flush();