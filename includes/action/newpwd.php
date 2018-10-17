<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$arraymsg['code'] = 'ERROR: MISSING_DATA';
if(isset($_GET['REQUEST_NEW_PWD']) && 
	isset($_GET['EMAIL_ADDRESS']) && !empty($_GET['EMAIL_ADDRESS']) && 
		isset($_GET['TOKEN']) && !empty($_GET['TOKEN']) && 
			isset($_GET['AUTH']) && !empty($_GET['AUTH']))
{
	include PNSDOTVN_ADM . DS . 'defineConst.php';
	
	if (!MEMBER_SYS)
		die();
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
	$lang = $utls->getCookie('lang', $defaultLang);
	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$dbf->queryEncodeUTF8();
	$info = $dbf->getConfig($lang);
	$def = $dbf->arrayToObject($info);
	  
  	$data['email'] = mysql_escape_string(trim($_GET['EMAIL_ADDRESS']));
  	$data['token'] = mysql_escape_string(trim($_GET['TOKEN']));
  	$data['auth'] = mysql_escape_string(trim($_GET['AUTH']));
  
  	if(!$utls->chk_email($data['email']))
  	{
		$arraymsg['code'] = 'ERROR: INVALID_EMAIL';
		echo $arraymsg['code'];
		exit;
  	}
  
  	$arraymsg['code'] = 'ERROR: INVALID_DATA';
  	$check = $dbf->getArray(prefixTable . 'customer', 'groupid = 2 AND email = "' . $data['email'] . '" AND token = "' . $data['token'] . '"', '');
  	if(count($check) == 1)
  	{
		if(sha1($data['email'] . $check[0]['added'] . $data['token']) <> $data['auth'])
		{
	  		$arraymsg['code'] = 'ERROR: NOT_AUTHORIZED';
	  		echo $arraymsg['code'];
	  		exit;			  
		}
	
		$newpwd = substr(md5(uniqid(rand(), true)), 0, 8);
		$cs['salt']     = substr(md5(uniqid(rand(), true)), 0, 6);
		$cs['password'] = sha1($cs['salt'] . sha1($cs['salt'] . sha1($newpwd)));
		$cs['token']    = md5($cs['password'] . $cs['salt']);
		$dbf->updateTable(prefixTable . 'customer', $cs, 'groupid = 2 AND email = "' . $check[0]['email'] . '" AND id = ' . $check[0]['id']);  
	
		$store = $dbf->getStore($lang);
		$subject = 'Mật khẩu mới của bạn tại ' . $dbf->upcaseFirst(SITE_NAME);
		$content = file_get_contents(PNSDOTVN_EMA . DS . 'newpwd_' . $lang . EXT);
		$content = str_replace(	
	  		array('{HOST}', '{subject}', '{cs_name}', '{cs_email}', '{cs_newpwd}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
	  		array(HOST, $subject, $check[0]['name'], $check[0]['email'], $newpwd, $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
	  		$content);  
		$mail = $dbf->getArray(prefixTable. 'mail_template', 'status = 1 AND lang = "' . $lang . '" AND name = "newpwd"', '', 'stdObject');  
		if(count($mail) == 1) 
		{
	  		$subject = str_replace('{site_name}', $dbf->upcaseFirst(SITE_NAME), $mail[0]->title);
	  		$content = str_replace(
				array('{HOST}', '{subject}', '{cs_name}', '{cs_email}', '{cs_newpwd}', '{company}', '{address}', '{hotline1}', '{email}', '{website}'),
				array(HOST, $subject, $check[0]['name'], $check[0]['email'], $newpwd, $store['company'], $store['address'], $store['hotline1'], $store['mailcontact'], $store['website']),
				$mail[0]->content);
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
		$mail->AddAddress($check[0]['email'], $check[0]['name']);
		$mail->SetFrom('no-reply@' . SITE_NAME, $arraySMTPSERVER['fromname']);
		$mail->Subject = $subject;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($content);
		if(SEND_MAIL) $mail->Send();  		  
		header('Location: ' . ((MULTI_LANG ? DS . $def->code2 : '') . DS . 'dang-nhap' . EXT) . '?PROCEED_TO_CHANGEPWD&PAGE=' . ((MULTI_LANG ? DS . $def->code2 : '') . DS . 'doi-mat-khau' . EXT));
		exit;
  	}
  	echo $arraymsg['code'];
  	exit;
}

echo $arraymsg['code'];
ob_end_flush();