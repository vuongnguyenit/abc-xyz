<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  	header('Location: /errors/403.shtml');	
  	die();
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if (!$isAjax) {
  	echo 'Access denied - not an AJAX request...';
  	die();
}

include PNSDOTVN_ADM . DS . 'defineConst.php';
include PNSDOTVN_CLS . DS . 'define.pnsdotvn' . PHP;
include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
$pns = $dbf;
#$pns->pnsdotvn_session();
$pns->queryEncodeUTF8();
	
$arraymsg['code'] = 'fail';	  
$data = $_POST;

if (is_array($data) && count($data) > 0) {
	unset($_POST);		
	include PNSDOTVN_CLS . DS . 'class.utls' . PHP; 
	include PNSDOTVN_CLS . DS . 'class.cookie' . PHP; 		
	$_ctype = array('PRODUCT' => 1, 'ARTICLE' => 2);
	$action = isset($data['action']) && !empty($data['action']) && in_array($data['action'], array('load','like','unlike')) ? $data['action'] : '';
	switch ($action) {
		case 'load':
			$id = (int) $data['id'];
			$type = $data['type'];
			$tmp = $pns->buildCommentARR($id, $type);
			if (is_array($tmp) && count($tmp) > 0) {
				$arraymsg['data'] = $tmp;
				$arraymsg['code'] = 'success';
			}
			break;		
		case 'like':
		case 'unlike':
			if (isset($data['id']) && !empty($data['id'])) {
				$ck = 'PNSDOTVN_LIKE_P_C_' . $data['id'];								
				$ctype = (isset($data['ctype']) && !empty($data['ctype']) && in_array($data['ctype'], array('PRODUCT', 'ARTICLE'))) ? $data['ctype'] : '';
				if (Cookie::Exists($ck) && !Cookie::IsEmpty($ck)) {
					$s = Cookie::Get($ck) + Cookie::OneDay - time();
					$t = $pns->secondsToTime($s);
					$arraymsg['time'] = (isset($t['h']) && $t['h'] > 0 ? $t['h'] . ' giờ ' : '') . (isset($t['m']) && $t['m'] > 0 ? $t['m'] . ' phút' : '');
					$arraymsg['code'] = 'limit';
					echo json_encode($arraymsg);
					exit;
				}					
				$id = explode('_', $data['id']);
				if (count($id) == 3) {										
					$p = (int) $id[2];
					$c = (int) $id[0];
					$pid = (int) $id[1];
					if (!$pns->pnsdotvn_comment(strtolower($ctype), array('id' => $p))) {
						$arraymsg['code'] = 'product_na';
						echo json_encode($arraymsg);
						exit;
					}					
					if (!$pns->pnsdotvn_comment('comment', array('id' => $c, 'pid' => $pid))) {
						$arraymsg['code'] = 'comment_na';
						echo json_encode($arraymsg);
						exit;
					}					
					if($action == 'unlike') Cookie::Set('PNSDOTVN_LIKE_P_C_' . $data['id'], time(), Cookie::OneDay);					
					$l = $pns->pnsdotvn_comment($action, array('id' => $c, 'pid' => $pid, 'p' => $p));
					$arraymsg['like'] = $l;
					$arraymsg['code'] = 'success';
				}
			}
			break;						
		default:
			$ctype = (isset($data['ctype']) && !empty($data['ctype']) && in_array($data['ctype'], array('PRODUCT', 'ARTICLE'))) ? $data['ctype'] : '';
			$type = (isset($data['type']) && !empty($data['type']) && in_array($data['type'], array('new', 'follow'))) ? $data['type'] : '';
			$loggedin = $pns->chkLoggedin();
			if (!$loggedin) {
				$arraymsg['code'] = 'nologgin';
				#$arraymsg['callback'] = $type;
				echo json_encode($arraymsg);
				exit;	  
			}	 									
			switch ($type) {
				case 'new':
					/*if (!isset($data['captcha']) ||
						empty($data['captcha']) ||
							!isset($_SESSION['captcha_id']) ||
								$data['captcha'] != $_SESSION['captcha_id']) {
						$arraymsg['code'] = 'captcha';
						echo json_encode($arraymsg);
						exit;
					}*/			
					if (empty($data['content']) || empty($data['item'])) {
						$arraymsg['code'] = 'missing';
						echo json_encode($arraymsg);
						exit;	  
					}					
					$item = (int) $data['item'];
					switch ($ctype) {
						case 'PRODUCT':
							#$rst = $pns->getDynamic(prefixTable . 'product', 'status = 1 and id = ' . $item, '');
							$q = $pns->Query('SELECT id FROM dynw_product WHERE status = 1 AND id = ' . $item . ' LIMIT 1');
							if ($pns->totalRows($q) == 0) {
								$pns->freeResult($q);
								$arraymsg['code'] = 'notavailable';
								echo json_encode($arraymsg);
								exit;	  
							}		
							break;
						case 'ARTICLE':
							$q = $pns->Query('SELECT id FROM dynw_cms WHERE status = 1 AND id = ' . $item . ' LIMIT 1');
							if ($pns->totalRows($q) == 0) {
								$pns->freeResult($q);
								$arraymsg['code'] = 'notavailable';
								echo json_encode($arraymsg);
								exit;	  
							}
							break;
					}
					$w = explode(' ', $data['content']);
					if (count($w) > COMMENT_WORD_NUMBER) {
						$arraymsg['code'] = 'wordnumber';
						$arraymsg['wordnumber'] = COMMENT_WORD_NUMBER;
						echo json_encode($arraymsg);
						exit;
					}										
					$comment = array(
						'item' => $item,
						'member' => $_SESSION['member']['id'],
						'type' => $_ctype[$ctype],
						'content' => $pns->checkValues($data['content']),
						'ip' => $_SERVER['REMOTE_ADDR'],
						'added' => time()
					);								
					$id = $pns->insertTable(prefixTable . 'comment', $comment);
					if ($id) {
						require_once PNSDOTVN_CAP . DS . 'rand' . PHP;
						$_SESSION['captcha_id'] = $strcaptcha;						
						$arraymsg['time'] = time();
						$arraymsg['code'] = 'success';
					}
					break;					
				case 'follow':
					if (empty($data['content']) || empty($data['id'])) {
						$arraymsg['code'] = 'missing';
						echo json_encode($arraymsg);
						exit;	  
					}					
					$id = explode('_', $data['id']);
					if (count($id) == 3) {										
						$p = (int) $id[2];
						$c = (int) $id[0];
						$pid = (int) $id[1];
						if (!$pns->pnsdotvn_comment(strtolower($ctype), array('id' => $p))) {
							$arraymsg['code'] = 'notavailable';
							echo json_encode($arraymsg);
							exit;
						}						
						if (!$pns->pnsdotvn_comment('comment', array('id' => $c, 'pid' => $pid))) {
							$arraymsg['code'] = 'invalid';
							echo json_encode($arraymsg);
							exit;
						}						
						$w = explode(' ', $data['content']);
						if (count($w) > COMMENT_WORD_NUMBER) {
							$arraymsg['code'] = 'wordnumber';
							$arraymsg['wordnumber'] = COMMENT_WORD_NUMBER;
							echo json_encode($arraymsg);
							exit;
						}						
						$d = $pns->pnsdotvn_comment('g_comment', array('id' => $c));						
						$comment = array(
							'pid' => $c,
							'item' => $p,
							'member' => $_SESSION['member']['id'],
							'follow' => $d->member,
							'type' => $_ctype[$ctype],
							'content' => $pns->checkValues($data['content']),
							'ip' => $_SERVER['REMOTE_ADDR'],
							'added' => time()
						);									
						$id = $pns->insertTable(prefixTable . 'comment', $comment);
						if($id) $arraymsg['code'] = 'success';
					}
					break;
			}			
			break;
	}
}
echo json_encode($arraymsg);
ob_end_flush();