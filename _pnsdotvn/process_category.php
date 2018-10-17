<?php 
error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);

date_default_timezone_set('Asia/Saigon');
ob_start('ob_gzhandler');
session_start();

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) 
{
  	$user_error = 'Access denied - not an AJAX request...';
  	#trigger_error($user_error, E_USER_ERROR);
	die($user_error);
}

if(!isset($_SESSION['user']) || empty($_SESSION['user'])) 
{
	$user_error = 'Access denied - Please login to the Administrator System!';
	#trigger_error($user_error, E_USER_ERROR);
	die($user_error);
}

$arraymsg['code'] = 'fail';
$data = $_POST;
if(is_array($data) && count($data) > 0)
{
	require_once str_replace('\\', '/', dirname(__FILE__)) . '/class/class.admin.php';
	require_once str_replace('\\', '/', dirname(dirname(__FILE__))) . '/class/class.utls.php';
	require_once str_replace('\\', '/', dirname(__FILE__)) . '/defineConst.php';
	$dbf->queryEncodeUTF8();
	
	if(isset($data['action']) && !empty($data['action']))
	{
		switch($data['action'])
		{
			case 'getList':
				if(isset($data['id']) && !empty($data['id'])
					&& isset($data['rid']) && !empty($data['rid'])
				)					
				{					
					$id = (int) $data['id'];
					$rid = (int) $data['rid'];
					$rst = $dbf->getDynamic(prefixTable . 'category', 'status = 1 and id = ' . $id, '');
					if($dbf->totalRows($rst) == 1)
					{
						$ida = $dbf->getChildCategory($id);
  						$ids = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) . ',' . $id : $id;
						$rst = $dbf->getDynamicJoin(prefixTable . 'product', prefixTable . 'category', array(), 'INNER JOIN', 't1.status = 1 and t2.status = 1 and t1.cid in (' . $ids . ') and t1.id <> ' . $rid, 't1.created', 't2.id = t1.cid');
						if($dbf->totalRows($rst) > 0)
						{
							while($row = $dbf->nextObject($rst))
							{
								$jdata[] = array(
									'id' => $row->id,
									'name' => stripslashes($row->name),
								);
							}
							$arraymsg['code'] = 'success';
							$arraymsg['data'] = $jdata;
						} else
						{
							$arraymsg['msg'] = 'LỖI :: Không tồn tại sản phẩm.';
						}
					}
					
				} else
				{
					$arraymsg['msg'] = 'LỖI :: Sản phẩm chưa được tạo.';
				}
				break;
			
			case 'add-item':
				if(isset($data['article']) && !empty($data['article'])
					&& isset($data['id']) && !empty($data['id']))					
				{					
					$article = (int) $data['article'];
					$selected = (int) $data['id'];
					$rst = $dbf->getDynamic(prefixTable . 'product', 'status = 1 and id = ' . $selected, '');
					if($dbf->totalRows($rst) == 1)
					{												
						$rst2 = $dbf->getDynamic(prefixTable . 'product', 'id = ' . $article, '');
						if($dbf->totalRows($rst2) == 1)
						{
							$row2 = $dbf->nextObject($rst2);
							$jdata = unserialize($row2->jrelated);
							if(is_array($jdata) && !in_array($selected, $jdata)) $jdata[] = $selected;
							else $jdata[] = $selected;	
							$_data = serialize($jdata);						
							$dbf->updateTable(prefixTable . 'product', array('jrelated' => $_data), 'id = ' . $article);
						
							$arraymsg['code'] = 'success';
						}
					} else
					{
						$arraymsg['msg'] = 'LỖI :: Sản phẩm không tồn tại.';
					}
					
				} else
				{
					$arraymsg['msg'] = 'LỖI :: Mã sản phẩm không tồn tại.';
				}
				break;
				
			case 'remove-selected':
				if(isset($data['article']) && !empty($data['article'])
					&& isset($data['selected']) && !empty($data['selected']))					
				{					
					$article = (int) $data['article'];
					$selected = (int) $data['selected'];
					$rst = $dbf->getDynamic(prefixTable . 'product', 'id = ' . $article, '');
					if($dbf->totalRows($rst) == 1)
					{												
						$row = $dbf->nextObject($rst);						
						$jdata = unserialize($row->jrelated);
						if(!empty($jdata) && is_array($jdata) && count($jdata) > 0 && in_array($selected, $jdata)) 
						{			
							$key = array_search($selected, $jdata);
							unset($jdata[$key]);
							$_data = serialize($jdata);						
							$dbf->updateTable(prefixTable . 'product', array('jrelated' => $_data), 'id = ' . $article);						
							$arraymsg['code'] = 'success';
						}						
					}					
				}
				break;	
		
			case 'add-item-alsobuy':
				if(isset($data['article']) && !empty($data['article'])
					&& isset($data['id']) && !empty($data['id']))					
				{					
					$article = (int) $data['article'];
					$selected = (int) $data['id'];
					$rst = $dbf->getDynamic(prefixTable . 'product', 'status = 1 and id = ' . $selected, '');
					if($dbf->totalRows($rst) == 1)
					{												
						$rst2 = $dbf->getDynamic(prefixTable . 'product', 'id = ' . $article, '');
						if($dbf->totalRows($rst2) == 1)
						{
							$row2 = $dbf->nextObject($rst2);
							$jdata = unserialize($row2->jalsobuy);
							if(is_array($jdata) && !in_array($selected, $jdata)) $jdata[] = $selected;
							else $jdata[] = $selected;	
							$_data = serialize($jdata);						
							$dbf->updateTable(prefixTable . 'product', array('jalsobuy' => $_data), 'id = ' . $article);
						
							$arraymsg['code'] = 'success';
						}
					} else
					{
						$arraymsg['msg'] = 'LỖI :: Sản phẩm không tồn tại.';
					}
					
				} else
				{
					$arraymsg['msg'] = 'LỖI :: Mã sản phẩm không tồn tại.';
				}
				break;	
				
			case 'remove-selected-alsobuy':
				if(isset($data['article']) && !empty($data['article'])
					&& isset($data['selected']) && !empty($data['selected']))					
				{					
					$article = (int) $data['article'];
					$selected = (int) $data['selected'];
					$rst = $dbf->getDynamic(prefixTable . 'product', 'id = ' . $article, '');
					if($dbf->totalRows($rst) == 1)
					{												
						$row = $dbf->nextObject($rst);						
						$jdata = unserialize($row->jalsobuy);
						if(!empty($jdata) && is_array($jdata) && count($jdata) > 0 && in_array($selected, $jdata)) 
						{			
							$key = array_search($selected, $jdata);
							unset($jdata[$key]);
							$_data = serialize($jdata);						
							$dbf->updateTable(prefixTable . 'product', array('jalsobuy' => $_data), 'id = ' . $article);						
							$arraymsg['code'] = 'success';
						}						
					}					
				}
				break;								
		}
	}
	
}
echo json_encode($arraymsg);
