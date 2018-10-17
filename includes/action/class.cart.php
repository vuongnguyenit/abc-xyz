<?php 
require_once('class.dbf.php');
class Cart extends DBFunction
{	
	function transfer_money ($number, $currency = 'VND')
	{
		$money = number_format($number);
		$money = preg_replace('/,/i', '.', $money);
		switch($currency) {
		  case 'VND':
		  	$money = $money . ' ' . $currency;
		  	break;
		  case '$':
		    $money = $currency . ' ' . $money;
		  	break;	
		}
		return $money;
	}
	
	var $name = 'PNSDOTVN_CART';
	var $prefixTable = 'dynw_';
	var $max_quantity = 99;
	
	function __construct()
	{
		@session_start();
	}
	
	function add($id, $ccode = '', $scode = '', $qty = 1, $lang = 'vi-VN')
	{
		$rst = new stdClass();
		$rst->flg = $this->chkProduct($id);
		if($rst->flg == 1)
		{
			$_qty 	= $qty;
			$_color = $this->buildColorCode($ccode, $lang);
			$_size = $this->buildSizeCode($scode, $lang);
			$_cid 	= $id . $_color . $_size;
			$cart 	= @$_SESSION[$this->name];	
			if(!isset($cart)) $cart = array();		
			if(isset($cart[$_cid])) 
			{
				$_qty = ($cart[$_cid] == $this->max_quantity) ? $this->max_quantity : ((($cart[$_cid] + $qty) > $this->max_quantity) ? $qty - (($cart[$_cid] + $qty) - $this->max_quantity) : $qty);
				$cart[$_cid] = ($cart[$_cid] == $this->max_quantity) ? $this->max_quantity : ((($cart[$_cid] + $qty) > $this->max_quantity) ? $cart[$_cid] + ($qty - (($cart[$_cid] + $qty) - $this->max_quantity)) : $cart[$_cid] + $qty);
			} else
			{
				$_qty = ($_qty > $this->max_quantity) ? $this->max_quantity : $_qty;
				$cart[$_cid] = $_qty;		
			}
			$_SESSION[$this->name] = $cart;
			$rst->qty = $_SESSION[$this->name][$_cid];
		}
		return $rst;
	}
	
	function update($id, $qty, $lang = 'vi-VN')
	{
		$rst = new stdClass();
		$rst->flg = 0;
		$_item = explode('_', $id);
		if(is_array($_item) && count($_item) > 0)
		{
			$rst->flg = $this->chkProduct((int) $_item[0]);
			if($rst->flg == 1)
			{
				$_color = isset($_item[1]) && !empty($_item[1]) ? $this->buildColorCode($_item[1], $lang) : '|0|0';
				$_size = isset($_item[2]) && !empty($_item[2]) ? $this->buildSizeCode($_item[2], $lang) : '|0|0';
				$_cid 	= $_item[0] . $_color . $_size;
				$cart = @$_SESSION[$this->name];
				if($qty == 0 && count($cart) == 1)
				{
					unset($_SESSION[$this->name]);
				} else if($qty == 0)
				{
					unset($_SESSION[$this->name][$_cid]);
				} else
				{
					$cart[$_cid] = $qty;
					$_SESSION[$this->name] = $cart;
				}
				$rst->id = $_item[0];
			} else
			{
				unset($_SESSION[$this->name][$_cid]);
			}
		}
		return $rst;
	}
	
	function removeall()
	{
		unset($_SESSION[$this->name]);
	}
	
	function remove($id, $lang = 'vi-VN')
	{
		$cart = @$_SESSION[$this->name];
		if(count($cart) == 1)
		{
			unset($_SESSION[$this->name]);
		} else
		{
			$_item = explode('_', $id);
			$_color = isset($_item[1]) && !empty($_item[1]) ? $this->buildColorCode($_item[1], $lang) : '';
			$_size = isset($_item[2]) && !empty($_item[2]) ? $this->buildSizeCode($_item[2], $lang) : '';
			$_cid 	= $_item[0] . $_color . $_size ;
			if(isset($_SESSION[$this->name][$_cid]))
				unset($_SESSION[$this->name][$_cid]);
		}
	}
	
	function pcount()
	{
		$count = 0;
		$cart = @$_SESSION[$this->name];		
		if(!isset($cart) || empty($cart)) return $count;				
		foreach($cart as $k => $v) $count += $v;
		return $count;		
	}
	
	function price($id, $qty, $data = '')
	{
		if($qty == 0)
		{
			return 0;
		} else
		{
			$price = 0;
			$rst = $this->doSQL('SELECT price, info FROM ' . $this->prefixTable . 'product WHERE id = ' . $id . ' LIMIT 1');
			if($this->totalRows($rst) == 1)
			{
				$row = $this->nextObject($rst);
				$this->freeResult($rst);
				$price = $row->price * $qty;
				if (isset($data->color) && $data->color != '|0|0' ) {
					$color = explode('|', $data->color);
					$jdata = unserialize($row->info);
					$jcolor = $jdata['color'];
					$price = $jcolor[$color[1]]['price'] * $qty;
				}
			}
			return $price;
		}
	}
	
	function totalprice()
	{
		$total = 0;
		$cart = @$_SESSION[$this->name];		
		if(!isset($cart) || empty($cart)) return $total;		
		foreach ($cart as $id => $qty) 
		{
			$ida = explode('|', $id);
			$rst = $this->doSQL('SELECT price, info FROM ' . $this->prefixTable . 'product WHERE id = ' . ((int) $ida[0]) . ' LIMIT 1');
			if ($this->totalRows($rst) == 1) {
				$row = $this->nextObject($rst);	
				$this->freeResult($rst);		
				if (isset($ida[1]) && $ida[1] != '0' && isset($ida[2]) && $ida[2] != '0') {				
					$jdata = unserialize($row->info);
					$jcolor = $jdata['color'];		
					$total += $jcolor[$ida[1]]['price'] * $qty;
				} else {
					$total += $row->price * $qty;		
				}
			}
		}
		return $total;
	}	
	
	function chkProduct($id, $flg = 1)
	{
		$rst = $this->getDynamic($this->prefixTable . 'product', 'status = 1 AND id = ' . $id, '');
		if($this->totalRows($rst) == 0)
		{
			$flg = 0;
		} else
		{
			$row = $this->nextObject($rst);
			if($row->outofstock == 1)
				$flg = 2;
		}
		return $flg;
	}
	
	function getProduct($id, $lang = 'vi-VN', $data = '')
	{
		$rst = $this->getDynamicJoin($this->prefixTable . 'product', $this->prefixTable . 'product_desc', array('name' => 'pname', 'rewrite' => 'rewrite'), 'INNER JOIN', 't1.status = 1 AND t1.id = ' . $id . ' AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
		if($this->totalRows($rst) == 1)
		{
			$row = $this->nextObject($rst);
			if($row->outofstock == 0)
			{
				$picture = explode(';', $row->picture);
				$cart = @$_SESSION[$this->name];
				$jdata = unserialize($row->info);
				$data = array(
					'id' => $row->id, 
					'name' => $row->pname, 
					'rewrite' => $row->rewrite, 
					'picture' => $picture[0], 
					'price' => $row->price,
					'color' => (isset($jdata['color']) ? $jdata['color'] : '')
				);
			} else
			{
				$data = array('outofstock' => 1);
			}
			$this->freeResult($rst);
		}
		return $data;
	}
	
	function buildColorCode($code = '', $lang = 'vi-VN', $data = '|0|0')
	{
		if(!empty($code))		
		{
			$rst = $this->getDynamicJoin($this->prefixTable . 'color', $this->prefixTable . 'color_desc', array(), 'INNER JOIN', 't1.status = 1 AND t1.code = "' . $code . '" AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
			if($this->totalRows($rst) == 1)
			{
				$row = $this->nextObject($rst);
				$data = '|' . $row->id . '|' . $row->code;
			}
		}
		return $data;
	}
	
	function buildSizeCode($code = '', $lang = 'vi-VN', $data = '|0|0')
	{
		if(!empty($code))		
		{
			$rst = $this->getDynamicJoin($this->prefixTable . 'size', $this->prefixTable . 'size_desc', array(), 'INNER JOIN', 't1.status = 1 AND t1.code = "' . $code . '" AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
			if($this->totalRows($rst) == 1)
			{
				$row = $this->nextObject($rst);
				$data = '|' . $row->id . '|' . $row->code;
			}
		}
		return $data;
	}		
	
}

$cart = new Cart();