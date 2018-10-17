<?php
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
	
	if(empty($data['email']) /*|| empty($data['phone'])*/)
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
	/*else if(is_int((int) $data['phone']) == false || strlen($data['phone']) < 7)
	{
	  	$arraymsg['code'] = 'invalidNumber';
	  	echo json_encode($arraymsg);
	  	exit;
	}*/  	
	
	$n['email'] = $dbf->checkValues($data['email']);
	#$n['phone'] = $dbf->checkValues($data['phone']);
	$chk = $dbf->getArray(prefixTable . 'newsletter', 'email = "' . $n['email'] . '"', '');
  	if(count($chk) == 1)
  	{
		$arraymsg['code'] = 'emailAddress';
  	} else
  	{
		$n['registered'] = time();
		$dbf->insertTable(prefixTable . 'newsletter', $n);
		$arraymsg['code'] = 'success';
	}  
	unset($data, $n);  	
}
echo json_encode($arraymsg);
ob_end_flush();