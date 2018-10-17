<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$arraymsg['code'] = 'ERROR: MISSING_DATA';
if((!isset($_SESSION['member']) || count($_SESSION['member']) == 0) && 
	isset($_GET['ACTIVE_ACCOUNT']) && 
		isset($_GET['EMAIL_ADDRESS']) && !empty($_GET['EMAIL_ADDRESS']) && 
			isset($_GET['KEY']) && !empty($_GET['KEY']) && 
				isset($_GET['AUTH']) && !empty($_GET['AUTH']))
{	
	include PNSDOTVN_ADM . DS . 'defineConst.php';
	
	if (!MEMBER_SYS)
		die();
	
  	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
	$dbf->queryEncodeUTF8();
	#$lang = $utls->getCookie('lang', $defaultLang);
	$lang = $defaultLang;
	include PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP; 	
	$_LNG = $dbf->arrayToObject($lng);	
	#$info = $dbf->getConfig($lang);
	$info = $dbf->getConfig();	
	$def = $dbf->arrayToObject($info);
  
  	$data['email'] = mysql_escape_string(trim($_GET['EMAIL_ADDRESS']));
  	$data['key'] = mysql_escape_string(trim($_GET['KEY']));
  	$data['auth'] = mysql_escape_string(trim($_GET['AUTH']));
  
  	if(!$utls->chk_email($data['email']))
  	{
		$arraymsg['code'] = 'ERROR: INVALID_EMAIL';
		echo $arraymsg['code'];
		exit;
  	}
  
  	$arraymsg['code'] = 'ERROR: INVALID_DATA';
  	$check = $dbf->getArray(prefixTable . 'customer', 'groupid = 2 AND email = "' . $data['email'] . '" and active = "' . $data['key'] . '"', '');
  	if(count($check) == 1)
  	{		
		if($check[0]['approve'] == 1)
		{
	  		header('Location: ' . (MULTI_LANG ? DS . $def->code2 : '') . DS . 'dang-nhap' . EXT);
	  		exit;		
		} else
		{
	  		if(sha1($data['email'] . $check[0]['added'] . $data['key']) <> $data['auth'])
	  		{
				$arraymsg['code'] = 'ERROR: NOT_AUTHORIZED';
				echo $arraymsg['code'];
				exit;			  
	  		}	  
	  		$cs['approve']    = 1;
	  		$dbf->updateTable(prefixTable . 'customer', $cs, 'email = "' . $check[0]['email'] . '"');  							
	  		header('Location: ' . (MULTI_LANG ? DS . $def->code2 : '') . DS . 'dang-nhap' . EXT);
	  		exit;
		}
  	}
  	echo $arraymsg['code'];
  	exit;
}

echo $arraymsg['code'];
ob_end_flush();