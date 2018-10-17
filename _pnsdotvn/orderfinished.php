<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  	header('Location: /errors/403.shtml');	
  	die();
}

if (!CART_SYS)
{
	header('Location: ' . HOST);
	exit;
}

if(!isset($_SESSION['PNSDOTVN_CART']) || empty($_SESSION['PNSDOTVN_CART']) || count($_SESSION['PNSDOTVN_CART']) == 0)
{
	header('Location: ' . (MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->cart->rewrite . EXT);
	exit;
}

if(!isset($_SESSION['PNSDOTVN_CHECKOUT']) || empty($_SESSION['PNSDOTVN_CHECKOUT']) || $_SESSION['PNSDOTVN_CHECKOUT'] == 0)
{
	header('Location: ' . (MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->checkout->rewrite . EXT);
	exit;
}

if(isset($_SESSION['PNSDOTVN_CART']) && !empty($_SESSION['PNSDOTVN_CART']) && count($_SESSION['PNSDOTVN_CART']) > 0)
{
	$oid = $_SESSION['PNSDOTVN_CHECKOUT'];	
	$msg = $dbf->buildOrderMsg($oid, $def, $_LNG, $lang);
	if(!empty($msg) && is_object($msg))
	{	
		$m = $dbf->getMemberInfo(MEMBER_SYS && isset($_SESSION['member']['id']) ? $_SESSION['member']['id'] : $_SESSION['PNSDOTVN_CHECKOUT_CSID']);
		require_once PNSDOTVN_CLS . DS . 'class.phpmailer' . PHP;
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = $arraySMTPSERVER['host'];
		$mail->Port       = 465;
		$mail->Username   = $arraySMTPSERVER['user']; 
		$mail->Password   = $arraySMTPSERVER['password']; 
		$mail->AddReplyTo($def->mailcontact, SHOP_NAME);
		$mail->AddAddress($m['email'], stripslashes($m['name']));			
		$mail->AddCC($def->mailcontact, SHOP_NAME);
		$mail->SetFrom('no-reply@' . SITE_NAME, SHOP_NAME);
		$mail->Subject = $msg->title;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($msg->content);
		if(SEND_MAIL) $mail->Send();		
	}
	echo '<div class="content"><div class="box" style="background-color: #FFF; margin: 5px 15px; padding: 10px; color:#000; text-align: center; position: relative;">' . $_LNG->others->finished->success . '</div></div>';	
}

// unset($_SESSION['PNSDOTVN_CART'], $_SESSION['PNSDOTVN_CHECKOUT'], $_SESSION['PNSDOTVN_CHECKOUT_CSID']);