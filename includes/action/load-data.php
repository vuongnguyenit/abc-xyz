<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if (!$isAjax) {
  	echo 'Access denied - not an AJAX request...';
  	die();
}

/*error_reporting(-1);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

$arraymsg['code'] = 'fail';
$data = $_POST;
if (is_array($data) && count($data) > 0) {
	include PNSDOTVN_ADM . DS . 'defineConst.php';
	include PNSDOTVN_CLS . DS . 'define.pnsdotvn' . PHP;
	include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
	#include PNSDOTVN_CLS . DS . 'class.utls' . PHP;	
  	include PNSDOTVN_CLS . DS . 'class.cookie' . PHP;
  	$dbf->queryEncodeUTF8();
	$lang = $defaultLang;
	if(Cookie::Exists('lang') && !Cookie::IsEmpty('lang') && in_array(Cookie::Get('lang'), $arrayLang)) 
		$lang = Cookie::Get('lang');					
  	require_once PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
  	$_LNG = $dbf->arrayToObject($lng);
  
  	switch ($data['action']) {	 
		case 'cmsrating' :
			if (isset($data['cms']) && $data['cms'] > 0 && isset($data['point']) && $data['point'] > 0) {
				$q = $dbf->Query('SELECT rating_vote, rating_point FROM dynw_cms WHERE status = 1 AND id = ' . $data['cms'] . ' LIMIT 1');
				if ($dbf->totalRows($q) == 1) {
					$r = $dbf->nextObject($q);
					$dbf->freeResult($q);
					$update = array(
						'rating_vote' => ($r->rating_vote + 1),
						'rating_point' => ($r->rating_point + $data['point'])
					);
					$dbf->updateTable(prefixTable . 'cms', $update, 'id = ' . $data['cms']);
					$jdata['code'] = 'success';
				}
			}
			break; 	  
		case 'rating' :
			if (isset($data['product']) && $data['product'] > 0 && isset($data['point']) && $data['point'] > 0) {
				$q = $dbf->Query('SELECT rating_vote, rating_point FROM dynw_product WHERE status = 1 AND id = ' . $data['product'] . ' LIMIT 1');
				if ($dbf->totalRows($q) == 1) {
					$r = $dbf->nextObject($q);
					$dbf->freeResult($q);
					$update = array(
						'rating_vote' => ($r->rating_vote + 1),
						'rating_point' => ($r->rating_point + $data['point'])
					);
					$dbf->updateTable(prefixTable . 'product', $update, 'id = ' . $data['product']);
					$jdata['code'] = 'success';
				}
			}
			break;
		case 'scrolling' :
			if (isset($_POST['page']) && $_POST['page'] > 1) {
				if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    				sleep(1);
				}
				$page = (int) $_POST['page'];
				$max = ALLPRODUCT_PRODUCT_ITEM;
				#$from = ($page - 1) * $max;				
				
				$jdata['display'] = $display = DEFAULT_PRODUCT_DISPLAY;
				if (isset($data['display']) && !empty($data['display']) && array_key_exists($data['display'], $PRODUCT_DISPLAY)) {
					Cookie::Set('PRODUCT_DISPLAY', $data['display'], Cookie::OneYear);	  
					$jdata['display'] = $display = $PRODUCT_DISPLAY[$data['display']];
				} elseif (Cookie::Exists('PRODUCT_DISPLAY') && !Cookie::IsEmpty('PRODUCT_DISPLAY') && array_key_exists(Cookie::Get('PRODUCT_DISPLAY'), $PRODUCT_DISPLAY)) {
					$jdata['display'] = $display = $PRODUCT_DISPLAY[Cookie::Get('PRODUCT_DISPLAY')];
				}
				
				$orderby = DEFAULT_PRODUCT_SORTING;
				if (isset($data['orderby']) && !empty($data['orderby']) && array_key_exists($data['orderby'], $PRODUCT_SORTING)) {
					Cookie::Set('PRODUCT_SORTING', $data['orderby'], Cookie::OneYear);
					$orderby = $PRODUCT_SORTING[$data['orderby']]['query'];		
				} elseif (Cookie::Exists('PRODUCT_SORTING') && !Cookie::IsEmpty('PRODUCT_SORTING') && array_key_exists(Cookie::Get('PRODUCT_SORTING'), $PRODUCT_SORTING)) {
					$orderby = $PRODUCT_SORTING[Cookie::Get('PRODUCT_SORTING')]['query'];
				}
				
				
				$add = '';
				if (isset($data['catalog']) && !empty($data['catalog'])) {
					$r = explode('_', $data['catalog']);
					if (!empty($r) && is_array($r) && count($r) == 5) {
						switch ($r[2]) {
							case 'HOME':
								switch ($r[1]) {
									case 'NEW':
										$add = ' AND t1.new = 1';	
										$max = NEW_PRODUCT_ITEM;	
										break;										
								}
								break;
							case 'CATEGORY':
								$rst = $dbf->getDynamicJoin(prefixTable. 'category', prefixTable. 'category_desc', array(), 'INNER JOIN', 't1.status = 1 AND t2.lang = "' . $lang . '" AND t2.rewrite = "' . $dbf->safeParam(strtolower($r[1])) . '" AND t1.id = '  . $dbf->safeParam((int) $r[0]), '', 't2.id = t1.id');
								if ($dbf->totalRows($rst) == 1) {
									$row = $dbf->nextObject($rst);
									$dbf->freeResult($rst);
									$ida = $dbf->getChildCategory($row->id);
									$ids = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) . ',' . $row->id : $row->id; 
									$add = ' AND t1.cid in (' . $ids . ')';									
								}
								$max = CATALOG_PRODUCT_ITEM;
								break;							
							case 'BRAND':
								$rst = $dbf->getDynamicJoin(prefixTable. 'brand', prefixTable. 'brand_desc', array(), 'INNER JOIN', 't1.status = 1 AND t2.lang = "' . $lang . '"' . ($r[1] <> 'ALLBRAND' ? ' AND t2.rewrite = "' . $dbf->safeParam(strtolower($r[1])) . '" AND t1.id = '  . $dbf->safeParam((int) $r[0]) : ''), '', 't2.id = t1.id');
								$t = $dbf->totalRows($rst);								
								if ($t == 1) {
									$row = $dbf->nextObject($rst);
									$dbf->freeResult($rst);
									$add = ' AND t1.brand = ' . $row->id;
								} elseif ($t > 1) {
									while ($row = $dbf->nextObject($rst)) 
										$ida[] = $row->id;
									if (is_array($ida) && count($ida) > 0) {
										$ids = implode(',', $ida); 
										$add = ' AND t1.brand in (' . $ids . ')';
									}
								}
								$max = BRAND_PRODUCT_ITEM;
								break;							
							default: 
								switch ($r[1]) {
									case 'NEWPRODUCT': $add = 't1.new = 1'; break;
									case 'HOTPRODUCT': $add = 't1.hot = 1'; break;
									case 'TOPPRODUCT': $add = 't1.top = 1'; break;							
									case 'PROMOPRODUCT': $add = 't1.promo = 1'; break;
									case 'FAVORITEPRODUCT': $add = 't1.favorite = 1'; break;
									case 'FEATUREDPRODUCT': $add = 't1.featured = 1'; break;
								}
								$max = ALLPRODUCT_PRODUCT_ITEM;
								break;
						}
					}										
				}
				
				$from = ($page - 1) * $max;
				$rst = $dbf->queryJoin(prefixTable . 'product t1', 't1.status = 1 AND t2.lang = "' . $lang . '" AND t3.status = 1 AND t4.lang = "' . $lang . '"' . $add, $orderby, 't1.id, t1.code, t1.picture, t1.list_price, t1.sale_off, t1.price, t1.outofstock, t2.name, t2.rewrite, t2.introtext, t2.metatitle', $from . ',' . $max, array(prefixTable . 'product_desc t2' => 't2.id = t1.id', prefixTable . 'category t3' => 't3.id = t1.cid', prefixTable . 'category_desc t4' => 't4.id = t3.id'), 'INNER JOIN');
				if ($dbf->totalRows($rst)) {
					$jdata['code'] = 'success';
					while ($row = $dbf->nextObject($rst)) {
						$pic = explode(';', $row->picture);		  									
						$jdata['data'][] = array(
							'id' 				=> (int) $row->id,
							'name' 				=> stripslashes($row->name),
							'title' 			=> str_replace('"', '', stripslashes($row->name)),
							'alt' 				=> $row->rewrite,
							'href' 				=> (MULTI_LANG ? DS . substr($lang, 0, -3) : '') . DS . $_LNG->others->product->rewrite . '/' . $row->rewrite . '-' . $row->id . EXT, 
							'src' 				=> $pic[0],
							'list_price' 		=> (int) $row->list_price, 
							'list_price_txt' 	=> $dbf->pricevnd($row->list_price, $_LNG->product->currency), 
							'discount' 			=> (int) $row->sale_off, 
							'price' 			=> (int) $row->price,
							'price_txt' 		=> $dbf->pricevnd($row->price, $_LNG->product->currency),
							'button' 			=> $_LNG->product->button->add2cart,
							'desc' 				=> $dbf->compressHtml($row->introtext),
							'state' 			=> ($row->outofstock == 1 ? 0 : 1),
							'contact_txt' 		=> ($row->outofstock == 1 ? $_LNG->product->callme : '')						
						);														
					}
					$dbf->freeResult($rst);
				}
			}
			break;			
		case '_district':
	  		$rst = $dbf->getDynamic(prefixTable . 'district', 'status = 1 and city = ' . $data['id'], 'name');
	  		if ($dbf->totalRows($rst)) {
				while($row = $dbf->nextObject($rst))
		  			$jdata[] = array('id' => $row->id, 'name' => stripslashes($lang == 'en-US' ? $utls->stripUnicode($row->name) : $row->name));
				$dbf->freeResult($rst);
			}
	  		break;
		case 'district':
	  		$rst = $dbf->getDynamic(prefixTable . 'location', 'status = 1 AND lgroup = 2 AND pid = ' . $data['id'], 'name');
	  		if ($dbf->totalRows($rst)) {
				while($row = $dbf->nextObject($rst))
		  			$jdata[] = array('id' => $row->id, 'name' => stripslashes($lang == 'en-US' ? $utls->stripUnicode($row->name) : $row->name));
				$dbf->freeResult($rst);
			}
	  		break;
		case 'ward':
	  		$rst = $dbf->getDynamic(prefixTable . 'location', 'status = 1 AND lgroup = 3 AND pid = ' . $data['id'], 'name');
	  		if ($dbf->totalRows($rst)) {
				while($row = $dbf->nextObject($rst))
		  			$jdata[] = array('id' => $row->id, 'name' => stripslashes($lang == 'en-US' ? $utls->stripUnicode($row->name) : $row->name));
				$dbf->freeResult($rst);
			}
	  		break;	  
		case 'sorting':	  					
		case 'display':	 
			if (((isset($data['display']) && !empty($data['display'])) || (isset($data['orderby']) && !empty($data['orderby']))) &&
				isset($data['catalog']) && !empty($data['catalog'])) {	
				$display = DEFAULT_PRODUCT_DISPLAY;
				if (isset($data['display']) && !empty($data['display']) && array_key_exists($data['display'], $PRODUCT_DISPLAY)) {
					Cookie::Set('PRODUCT_DISPLAY', $data['display'], Cookie::OneYear);	  
					$display = $PRODUCT_DISPLAY[$data['display']];
				} elseif (Cookie::Exists('PRODUCT_DISPLAY') && !Cookie::IsEmpty('PRODUCT_DISPLAY') && array_key_exists(Cookie::Get('PRODUCT_DISPLAY'), $PRODUCT_DISPLAY)) {
					$display = $PRODUCT_DISPLAY[Cookie::Get('PRODUCT_DISPLAY')];
				}				
				$orderby = DEFAULT_PRODUCT_SORTING;
				if (isset($data['orderby']) && !empty($data['orderby']) && array_key_exists($data['orderby'], $PRODUCT_SORTING)) {
					Cookie::Set('PRODUCT_SORTING', $data['orderby'], Cookie::OneYear);
					$orderby = $PRODUCT_SORTING[$data['orderby']]['query'];		
				} elseif (Cookie::Exists('PRODUCT_SORTING') && !Cookie::IsEmpty('PRODUCT_SORTING') && array_key_exists(Cookie::Get('PRODUCT_SORTING'), $PRODUCT_SORTING)) {
					$orderby = $PRODUCT_SORTING[Cookie::Get('PRODUCT_SORTING')]['query'];
				}				
				$add = '';
				if (isset($data['catalog']) && !empty($data['catalog'])) {
					$r = explode('_', $data['catalog']);
					if (!empty($r) && is_array($r) && count($r) == 5) {
						switch ($r[2]) {
							case 'CATEGORY':
								$rst = $dbf->getDynamicJoin(prefixTable. 'category', prefixTable. 'category_desc', array(), 'INNER JOIN', 't1.status = 1 AND t2.lang = "' . $lang . '" AND t2.rewrite = "' . $dbf->safeParam(strtolower($r[1])) . '" AND t1.id = '  . $dbf->safeParam((int) $r[0]), '', 't2.id = t1.id');
								if ($dbf->totalRows($rst) == 1) {
									$row = $dbf->nextObject($rst);
									$dbf->freeResult($rst);
									$ida = $dbf->getChildCategory($row->id);
									$ids = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) . ',' . $row->id : $row->id; 
									$add = 't1.cid in (' . $ids . ')';									
								}
								$_max = CATALOG_PRODUCT_ITEM;
								break;							
							case 'BRAND':
								$rst = $dbf->getDynamicJoin(prefixTable. 'brand', prefixTable. 'brand_desc', array(), 'INNER JOIN', 't1.status = 1 AND t2.lang = "' . $lang . '"' . ($r[1] <> 'ALLBRAND' ? ' AND t2.rewrite = "' . $dbf->safeParam(strtolower($r[1])) . '" AND t1.id = '  . $dbf->safeParam((int) $r[0]) : ''), '', 't2.id = t1.id');
								$t = $dbf->totalRows($rst);								
								if ($t == 1) {
									$row = $dbf->nextObject($rst);
									$dbf->freeResult($rst);
									$add = 't1.brand = ' . $row->id;
								} elseif ($t > 1) {
									while ($row = $dbf->nextObject($rst)) 
										$ida[] = $row->id;
									if (is_array($ida) && count($ida) > 0) {
										$ids = implode(',', $ida); 
										$add = 't1.brand in (' . $ids . ')';
									}
								}
								$_max = BRAND_PRODUCT_ITEM;
								break;							
							default: 
								switch ($r[1]) {
									case 'NEWPRODUCT': $add = 't1.new = 1'; break;
									case 'HOTPRODUCT': $add = 't1.hot = 1'; break;
									case 'TOPPRODUCT': $add = 't1.top = 1'; break;							
									case 'PROMOPRODUCT': $add = 't1.promo = 1'; break;
									case 'FAVORITEPRODUCT': $add = 't1.favorite = 1'; break;
									case 'FEATUREDPRODUCT': $add = 't1.featured = 1'; break;
								}
								$_max = ALLPRODUCT_PRODUCT_ITEM;
								break;
						}
					}										
				}				
				$f = '';
				if (isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && 
					is_array($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && 
						!empty($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && 
							count($_SESSION['PNSDOTVN_PRODUCT_FILTER']) > 0) {
					@$d->code = $lang;
					$filter = $dbf->getFilter($d);
					foreach($_SESSION['PNSDOTVN_PRODUCT_FILTER'] as $k => $v) {
						if ($k == 'price') {
							$tmp = explode(';', $v);
							$f .= ' AND t1.price BETWEEN ' . $tmp[0] . ' AND ' . $tmp[1];
						} else if ($k == 'brand') {
							#$b = $dbf->getBrandId($v);
							#$f .= $b != 0 ? ' AND t1.' . $k . ' = "' .  $b . '"' : '';
							$q = $dbf->Query('SELECT id FROM ' . prefixTable . 'brand WHERE status = 1 AND name = "' . $dbf->checkValues(str_replace('+', ' ', $v)) . '" LIMIT 1');
							if ($dbf->totalRows($q)) {
								$v = $dbf->nextObject($q);
								$dbf->freeResult($q);
								$f .= ' AND t1.' . $k . ' = "' .  $v->id . '"';
							}
						} else if ($k == 'keyword') {
							$f .= '';
						} 
						/*
						else {
							$f .= ' AND t1.' . $k . ' = "' .  $v . '"';
						}
						*/
						if (is_array($filter) && count($filter) > 0 && in_array($k, $filter)) {
							$q = $dbf->Query('SELECT t2.id, t3.id AS oid FROM ' . prefixTable . 'product_option_data_desc t1 INNER JOIN ' . prefixTable . 'product_option_data t2 ON t2.id = t1.id INNER JOIN ' . prefixTable . 'product_option t3 ON t3.id = t2.oid WHERE t2.status = 1 AND t1.lang = "' . $lang . '" AND t1.name = "' . $v . '" AND t3.status = 1 AND t3.code = "' . $k . '" LIMIT 1');
							if ($dbf->totalRows($q) == 1) {
								$r2 = $dbf->nextObject($q);
								$dbf->freeResult($q);
								$f .= ' AND t1.joption LIKE "%opt' . $r2->oid . '_' .  $r2->id . '%"';
							}
						}
					}
				}		
				$page = (isset($r[4]) && $r[4] > 0 ? $r[4] : 1);
				$rst = $dbf->queryJoin(prefixTable . 'product t1', 't1.status = 1 ' . (!empty($add) ? 'AND ' . $add . ' ' : '') . 'AND t2.lang = "' . $lang . '" AND t3.status = 1 AND t4.lang = "' . $lang . '"' . $f, $orderby, 't1.id, t1.code, t1.picture, t1.list_price, t1.sale_off, t1.price, t1.new, t1.brand, t1.outofstock, t2.name, t2.rewrite, t2.introtext, t2.metatitle', ((($page - 1) * $_max) . ',' . $_max), array(prefixTable . 'product_desc t2' => 't2.id = t1.id', prefixTable . 'category t3' => 't3.id = t1.cid', prefixTable . 'category_desc t4' => 't4.id = t3.id'), 'INNER JOIN');
				if ($dbf->totalRows($rst) > 0) {
					while ($row = $dbf->nextObject($rst)) {
						$pic = explode(';', $row->picture);	
						$d = new stdClass;
						$d->code = 'vi-VN';
						$d->route = new stdClass;
						$d->route->name = 'product';
						$d->route->id = $row->brand;
						$tmp = $dbf->getBrand($d);
						$brand = new stdClass;
						$brand->status = 0;
						if (!empty($tmp) && is_array($tmp) && count($tmp) == 1) {
							$brand = $tmp[0];	  								
							$brand->status = 1;
						}
						$jdata[] = array(
							'id' 				=> (int) $row->id,
							'name' 				=> stripslashes($row->name),
							'title' 			=> str_replace('"', '', stripslashes($row->name)),
							'alt' 				=> $row->rewrite,
							'href' 				=> (MULTI_LANG ? DS . substr($lang, 0, -3) : '') . DS . $_LNG->others->product->rewrite . '/' . $row->rewrite . '-' . $row->id . EXT, 
							'src' 				=> $pic[0],
							'list_price' 		=> (int) $row->list_price, 
							'list_price_txt' 	=> $dbf->pricevnd($row->list_price, $_LNG->product->currency), 
							'discount' 			=> (int) $row->sale_off, 
							'price' 			=> (int) $row->price,
							'price_txt' 		=> ($row->price > 0 ? $dbf->pricevnd($row->price, $_LNG->product->currency) : 'Liên hệ'),
							'new' 				=> (int) $row->new,
							'brand'				=> $brand,
							#'button' 			=> $_LNG->product->button->add2cart,
							'desc' 				=> $dbf->compressHtml($row->introtext),
							'state' 			=> ($row->outofstock == 1 ? 0 : 1),
							#'contact_txt' 		=> ($row->outofstock == 1 ? $_LNG->product->callme : '')						
						);														
					}
					$dbf->freeResult($rst);
				}
			}
	 		break;			
  	}
  	unset($data, $_POST);
}

echo json_encode($jdata);