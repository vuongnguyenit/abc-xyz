<?php
require_once('class.dbf.php');

class Cart extends DBFunction {

    function transfer_money($number, $currency = 'VND') {
        $money = number_format($number);
        $money = preg_replace('/,/i', '.', $money);
        switch ($currency) {
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

    function __construct() {
        @session_start();
    }

    /**
     * @param $id
     * @param string $ccode
     * @param string $scode
     * @param int $qty
     * @param string $lang
     *
     * @return \stdClass
     */
    function add($id, $ccode = '', $scode = '', $qty = 1, $lang = 'vi-VN') {
        $rst      = new stdClass();
        $rst->flg = $this->chkProduct($id);
        if ($rst->flg == 1) {
            $_qty   = $qty;
            $_color = $this->buildColorCode($ccode, $lang);
            $_size  = $this->buildSizeCode($scode, $lang);
            $_cid   = $id;
            if (!empty($_color)) {
                $_cid .= $_color;
            }
            if (!empty($_size)) {
                $_cid .= $_size;
            }
            $cart = @$_SESSION[$this->name];

            if (!isset($cart)) {
                $cart = [];
            }
            if (isset($cart[$_cid])) {
                $cart[$_cid] = ($cart[$_cid] == $this->max_quantity) ? $this->max_quantity : ((($cart[$_cid] + $qty) > $this->max_quantity) ? $cart[$_cid] + ($qty - (($cart[$_cid] + $qty) - $this->max_quantity)) : $cart[$_cid] + $qty);
            }
            else {
                $_qty        = ($_qty > $this->max_quantity) ? $this->max_quantity : $_qty;
                $cart[$_cid] = $_qty;
            }
            $_SESSION[$this->name] = $cart;
            $rst->qty              = $_SESSION[$this->name][$_cid];
        }
        return $rst;
    }

    /**
     * @param $id
     * @param $value
     * @param $qty
     * @param string $lang
     *
     * @return \stdClass
     */
    function update($id, $value, $qty, $lang = 'vi-VN') {
        $rst      = new stdClass();
        $rst->flg = $this->chkProduct($id);

        if ($rst->flg == 1) {
            $_color = $this->buildColorCode($value, $lang);
            $_size  = $this->buildSizeCode($value, $lang);
            $_cid   = $id;
            if (!empty($_color)) {
                $_cid .= $_color;
            }
            if (!empty($_size)) {
                $_cid .= $_size;
            }
            $cart = @$_SESSION[$this->name];
            if ($qty == 0 && count($cart) == 1) {
                unset($_SESSION[$this->name]);
            }
            else if ($qty == 0) {
                unset($_SESSION[$this->name][$_cid]);
            }
            else {
                $cart[$_cid]           = $qty;
                $_SESSION[$this->name] = $cart;
            }
            $rst->id    = $id;
            $rst->color = $_color;
            $rst->size  = $_size;
        }
        else {
            unset($_SESSION[$this->name][$_cid]);
        }
        return $rst;
    }

    function removeall() {
        unset($_SESSION[$this->name]);
    }

    /**
     * @param $id
     */
    function remove($id) {
        $cart = @$_SESSION[$this->name];
        if (count($cart) == 1) {
            unset($_SESSION[$this->name]);
        }
        else {
            if (isset($_SESSION[$this->name][$id])) {
                unset($_SESSION[$this->name][$id]);
            }
        }
    }

    function pcount() {
        $count = 0;
        $cart  = @$_SESSION[$this->name];
        if (!isset($cart) || empty($cart)) {
            return $count;
        }
        foreach ($cart as $k => $v) {
            $count += $v;
        }
        return $count;
    }

    function price($id, $qty, $data = '') {
        if ($qty == 0) {
            return 0;
        }
        else {
            $price = 0;
            $rst   = $this->doSQL('SELECT price, info FROM ' . $this->prefixTable . 'product WHERE id = ' . $id . ' LIMIT 1');
            if ($this->totalRows($rst) == 1) {
                $row = $this->nextObject($rst);
                $this->freeResult($rst);
                $price = $row->price * $qty;
                if (isset($data->color) && $data->color != '|0|0') {
                    $color  = explode('|', $data->color);
                    $jdata  = unserialize($row->info);
                    $jcolor = $jdata['color'];
                    $price  = $jcolor[$color[1]]['price'] * $qty;
                }
            }
            return $price;
        }
    }

    /**
     * @return float|int
     */
    function totalprice() {
        $total = 0;
        $cart  = @$_SESSION[$this->name];
        if (!isset($cart) || empty($cart)) {
            return $total;
        }
        foreach ($cart as $id => $qty) {
            $ida   = explode('|', $id);
            $product = $this->getProduct($ida[0]);
            if(empty($ida[2])) {
                $price = @$product['price'];
                if($product['wholesale']) {
                    $wholesale = unserialize($product['wholesale']);
                    foreach ($wholesale as $wsale) {
                        if($wsale['quantity_from'] == $wsale['quantity_to'] && $qty >= $wsale['quantity_from']) {
                            $price = $wsale['wholesale_price'];
                            break;
                        }
                        if($qty >= $wsale['quantity_from'] && $qty <= $wsale['quantity_to']) {
                            $price = $wsale['wholesale_price'];
                            break;
                        }
                    }
                }
            } else {
                $price = $this->getPrice($ida[0], $ida[2]);
            }
            $total += $price * $qty;
        }
        return $total;
    }

    function chkProduct($id, $flg = 1) {
        $rst = $this->getDynamic($this->prefixTable . 'product', 'status = 1 AND id = ' . $id, '');
        if ($this->totalRows($rst) == 0) {
            $flg = 0;
        }
        else {
            $row = $this->nextObject($rst);
            if ($row->outofstock == 0) {
                $flg = 1;
            }
            else {
                $flg = 0;
            }
        }
        return $flg;
    }

    function getProduct($id, $lang = 'vi-VN', $data = '') {
        $rst = $this->getDynamicJoin($this->prefixTable . 'product', $this->prefixTable . 'product_desc', [
            'name'    => 'pname',
            'rewrite' => 'rewrite',
        ], 'INNER JOIN', 't1.status = 1 AND t1.id = ' . $id . ' AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            if ($row->outofstock == 0) {
                $picture = explode(';', $row->picture);
                $jdata   = unserialize($row->info);
                $data    = [
                    'id'        => $row->id,
                    'name'      => $row->pname,
                    'rewrite'   => $row->rewrite,
                    'picture'   => $picture[0],
                    'price'     => $row->price,
                    'color'     => (isset($jdata['color']) ? $jdata['color'] : ''),
                    'wholesale' => $row->wholesale,
                ];
            }
            else {
                $data = ['outofstock' => 1];
            }
            $this->freeResult($rst);
        }
        return $data;
    }

    function buildColorCode($code = '', $lang = 'vi-VN', $data = NULL) {
        if (!empty($code)) {
            $rst = $this->getDynamicJoin($this->prefixTable . 'color', $this->prefixTable . 'color_desc', [], 'INNER JOIN', 't1.status = 1 AND t1.code = "' . $code . '" AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
            if ($this->totalRows($rst) == 1) {
                $row  = $this->nextObject($rst);
                $data = '|' . $row->id . '|' . $row->code;
            }
        }
        return $data;
    }

    function buildSizeCode($code = '', $lang = 'vi-VN', $data = NULL) {
        if (!empty($code)) {
            $rst = $this->getDynamicJoin($this->prefixTable . 'product_size', $this->prefixTable . 'psize_desc', [], 'INNER JOIN', 't1.status = 1 AND t1.code = "' . $code . '" AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
            if ($this->totalRows($rst) == 1) {
                $row  = $this->nextObject($rst);
                $data = '|' . $row->id . '|' . $row->code;
            }
        }
        return $data;
    }

    /**
     * @param $id
     * @param null $value
     *
     * @return int
     */
    function getPrice($id, $value = NULL) {
        $price = 0;
        $rst   = $this->getDynamic(prefixTable . 'product', 'id = ' . $id, '');
        if ($this->totalRows($rst) == 1) {
            $row  = $this->nextObject($rst);
            $info = unserialize($row->info);
            if (!empty($info['size'])) {
                foreach ($info['size'] as $item) {
                    if ($item['code'] == $value) {
                        $price = $item['price'];
                        break;
                    }
                }
            }
            if (!empty($info['color'])) {
                foreach ($info['color'] as $item) {
                    if ($item['code'] == $value) {
                        $price = $item['price'];
                        break;
                    }
                }
            }
        }
        return $price;
    }

    /**
     * @param $wholesale
     * @param $quantity
     *
     * @return int
     */
    function getPriceByWholesale($wholesale, $quantity) {
        $price = 0;
        foreach ($wholesale as $wsale) {
            if($wsale['quantity_from'] == $wsale['quantity_to'] && $quantity >= $wsale['quantity_from']) {
                $price = $wsale['wholesale_price'];
                break;
            }
            if($quantity >= $wsale['quantity_from'] && $quantity <= $wsale['quantity_to']) {
                $price = $wsale['wholesale_price'];
                break;
            }
        }
        return $price;
    }
}

$cart = new Cart();