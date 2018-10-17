<?php
error_reporting(-1);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

/*
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}
*/

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
	unset($_POST);	
	
	if(empty($data['email']) || empty($data['phone']) || empty($data['quantity']) || empty($data['name']) || empty($data['href']))
	{
	  	$arraymsg['code'] = 'missingData';
	  	echo json_encode($arraymsg);
	  	exit;
	} else if(!$utls->chk_email($data['email']))
	{
	  	$arraymsg['code'] = 'invalidEmail';
	  	echo json_encode($arraymsg);
	  	exit;
	} else if(is_int((int) $data['phone']) == false || strlen($data['phone']) < 7)
	{
	  	$arraymsg['code'] = 'invalidNumber';
	  	echo json_encode($arraymsg);
	  	exit;
	}  	
	
	$n['email'] = $dbf->checkValues($data['email']);
	$n['phone'] = $dbf->checkValues($data['phone']);
	$n['quantity'] = $dbf->checkValues($data['quantity']);
	$n['name'] = $dbf->checkValues($data['name']);
	$n['href'] = $dbf->checkValues($data['href']);
	$n['registered'] = time();
	//echo ''. $n['quantity'] .'';
	$dbf->insertTable(prefixTable . 'consultant', $n);
	$arraymsg['code'] = 'success';	  	
	
	$message = '<p>Kính chào Quý khách,<br>
Cảm ơn Quý khách đã gửi yêu cầu báo giá đến chúng tôi. Dưới đây là nội dung Quý khách đã gửi yêu cầu:</p><hr><p><strong>Số điện thoại:</strong> ' . $n['phone'] . '<br /></p><p><strong>Email:</strong> ' . $n['email'] . '<br /></p><p><strong>Sản phẩm:</strong> ' . $n['name'] . '<br /></p><p><strong>Số lượng:</strong> ' . $n['quantity'] . '<br /></p><p><strong>Liên kết:</strong> ' . $n['href'] . '<br /></p><hr><p>Chúng tôi sẽ phản hồi lại cho Quý khách trong thời gian sớm sau khi nhận được yêu cầu.</p><p>Chào trân trọng và hợp tác!</p>
<p><strong>CÔNG TY TNHH MỘT THÀNH VIÊN NỘI THẤT GIA PHÁT</strong></p>
<p>Hotline/Zalo: 0988.75.35.95</p>';
	require_once("class/class.phpmailer.php");
	$mail = new PHPMailer(true);
	$mail->IsSMTP(); 
	$mail->SMTPDebug  = 0;                     
	$mail->SMTPAuth   = $arraySMTPSERVER['auth'];           
	$mail->SMTPSecure = $arraySMTPSERVER['secure'];            
	$mail->Host       = $arraySMTPSERVER['host'];
	$mail->Port       = $arraySMTPSERVER['port'];            
	$mail->Username   = $arraySMTPSERVER['user'];
	$mail->Password   = $arraySMTPSERVER['password'];
	$mail->AddReplyTo('info@giaphatstore.com', 'chauruachen.pns.vn');
    $mail->AddAddress('vattu@pns.vn', 'chauruachen.pns.vn');
	$mail->AddCC('' . $n['email'] . '', '[chauruachen.pns.vn] - Đăng ký nhận báo giá');
    //$mail->AddBCC('vuong@pns.vn', 'chauruachen.pns.vn');
	$mail->SetFrom($arraySMTPSERVER['from'], '[chauruachen.pns.vn] - Đăng ký nhận báo giá');
	$mail->Subject = 'Đăng ký nhận báo giá sản phẩm: ' . $n['name'] . '';
	$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
	$mail->MsgHTML($message);
	$mail->Send();
	
	unset($data, $n);
}
echo json_encode($arraymsg);
ob_end_flush();