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
if(isset($_SESSION['member']) && count($_SESSION['member']) > 0 && is_array($data) && count($data) > 0)
{
  	include PNSDOTVN_ADM . DS . 'defineConst.php';
	
	if (!MEMBER_SYS)
		die();
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;  
  	$dbf->queryEncodeUTF8();
 	
  	if(empty($data['old_pwd']) || empty($data['password']))
  	{
		$arraymsg['code'] = 'missingData';
		echo json_encode($arraymsg);
		exit;	  
  	} elseif($data['old_pwd'] == $data['password'])
  	{
		$arraymsg['code'] = 'pwdMatch';
		echo json_encode($arraymsg);
		exit;	  
  	}
  
  	$arraymsg['code'] = 'notAvailable';
  	$rst = $dbf->getDynamic(prefixTable . 'customer', 'groupid = 2 AND id = ' . $_SESSION['member']['id'] . ' AND status = 1', '');
  	if($dbf->totalRows($rst) == 1)
  	{
		$row = $dbf->nextObject($rst);
		if(sha1($row->salt . sha1($row->salt . sha1($data['old_pwd']))) <> $row->password)
		{
	  		$arraymsg['code'] = 'oldPwdNotValid';
	  		echo json_encode($arraymsg);
	  		exit;
		}
		unset($data['old_pwd'], $data['repassword'], $data['change']);
		$cs['salt']     = substr(md5(uniqid(rand(), true)), 0, 6);
		$cs['password'] = sha1($cs['salt'] . sha1($cs['salt'] . sha1($data['password'])));
		$cs['token']    = md5($cs['password'] . $cs['salt']);
		$cs['updated'] 	= time();
		$dbf->updateTable(prefixTable . 'customer', $cs, 'id = ' . $_SESSION['member']['id']);
		$arraymsg['code'] = 'success';
		$dbf->freeResult($rst);
  	}
  	unset($_POST, $data);
}

echo json_encode($arraymsg);
ob_end_flush();