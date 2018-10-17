<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

if(MEMBER_SYS && isset($_COOKIE[COOKIE_NAME]) && !empty($_COOKIE[COOKIE_NAME]))
{
  	$dbf->queryEncodeUTF8();	
	
	/*
  	parse_str($_COOKIE[COOKIE_NAME]);
  	$rst = $dbf->getDynamic(prefixTable . 'customer', 'groupid = 2 AND email = "' . $usr . '"', '');
  	if($dbf->totalRows($rst) == 1)
  	{
		$row = $dbf->nextObject($rst);
		if($hash == sha1($row->password . md5($row->id) . $row->logined))
		{
	  		$_SESSION['member']['id'] = $row->id;	
	  		$_SESSION['member']['name'] = $row->name;
	  		$_SESSION['member']['email'] = $row->email;
		}
		$dbf->freeResult($rst);
  	}
	*/
	
	if(!function_exists('hash_equals')) 
	{
		function hash_equals($str1, $str2) 
		{
			if(strlen($str1) != strlen($str2)) 
			{
			  	return false;
			} else 
			{
			  	$res = $str1 ^ $str2;
			  	$ret = 0;
			  	for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
			  	return !$ret;
			}
		}
	}
	
	list($selector, $authenticator) = explode(':', $_COOKIE[COOKIE_NAME]);
	if (!empty($selector) && !empty($authenticator))
	{
		$rst = $dbf->Query ('SELECT token, userid FROM dynw_auth_tokens WHERE selector = "' . $selector . '" LIMIT 1');
		if($dbf->totalRows($rst))
		{
			$row = $dbf->nextObject($rst);
			$dbf->freeResult($rst);
			if (hash_equals($row->token, hash('sha256', base64_decode($authenticator)))) 
			{
				$c = $dbf->getMemberInfo($row->userid);
				if (!empty($c) && is_array($c) && count($c) > 0)
				{
					$_SESSION['member']['id'] = $row->userid;	
					$_SESSION['member']['name'] = $c['name'];
					$_SESSION['member']['email'] = $c['email'];
				}
			}
		}
	}
}