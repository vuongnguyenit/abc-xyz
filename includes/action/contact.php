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
if(is_array($data) && count($data) > 0)
{
	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;  
	$dbf->queryEncodeUTF8(); 	
	unset($_POST, $data['captcha']);	
	
	if(empty($data['name']) || empty($data['email']) || empty($data['message']))
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
  
  	$title = 'Khách hàng liên hệ ' . $dbf->upcaseFirst(SITE_NAME);
  	$content = file_get_contents(PNSDOTVN_EMA . DS . 'contact' . EXT);
  	$content = str_replace(
	  	array('{title}', '{name}', '{email}', '{phone}', '{message}'),
	  	array($title, $data['name'], $data['email'], $data['phone'], $data['message']), $content); 
		
		 
  	$rst = $dbf->getDynamicJoin(prefixTable . 'setting_desc', prefixTable . 'setting', array(), 'INNER JOIN', 't1.lang = "vi-VN" AND t2.status = 1 and t2.type = "mailcontact"', '', 't2.id = t1.id');   
  	if($dbf->totalRows($rst) > 0)
  	{
		$arrEmail = array();
		$i = 0;
		while($row = $dbf->nextObject($rst))
		{
	  		$arrEmail[$i] = $row->content;	
	  		$i++;
		}		
		
		require_once PNSDOTVN_CLS . DS . 'class.phpmailer' . PHP;
		$mail = new PHPMailer(true); 
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                    
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = 'ssl';                 
		$mail->Host       = 'smtp.gmail.com';      
		$mail->Port       = 465;                   
		$mail->Username   = $arraySMTPSERVER['user'];  
		$mail->Password   = $arraySMTPSERVER['password'];          
		$mail->AddReplyTo($data['email'], $data['name']);
		$mail->AddAddress($arrEmail[0], $arrEmail[0]);
		$j = 1;	
		while($j < count($arrEmail))
		{	
		  $mail->AddCC($arrEmail[$j], $arrEmail[$j]);
		  $j++;
		}
		$mail->SetFrom('no-reply@' . SITE_NAME, 'CUSTOMER');
		$mail->Subject = $title;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($content);
		if(SEND_MAIL) $mail->Send();
		$arraymsg['code'] = 'success';				
  	}	
}
echo json_encode($arraymsg);
ob_end_flush();