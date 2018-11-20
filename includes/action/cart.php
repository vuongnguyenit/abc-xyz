<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if (!$isAjax) {
    echo 'Access denied - not an AJAX request...';
    die();
}

$data = isset($_POST) ? $_POST : (isset($_GET) ? $_GET : '');
if (is_array($data) && count($data) > 0) {
    include PNSDOTVN_ADM . DS . 'defineConst.php';

    if (!CART_SYS) {
        exit;
    }

    include PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
    include PNSDOTVN_CLS . DS . 'class.cart' . PHP;
    #include PNSDOTVN_CLS . DS . 'class.utls' . PHP;
    include PNSDOTVN_CLS . DS . 'class.cookie' . PHP;
    $dbf->queryEncodeUTF8();
    $lang = $defaultLang;
    if (Cookie::Exists('lang') && !Cookie::IsEmpty('lang') && in_array(Cookie::Get('lang'), $arrayLang)) {
        $lang = Cookie::Get('lang');
    }
    require_once PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
    $_LNG = $dbf->arrayToObject($lng);
    $lg   = $dbf->getLanguage($lang);
    switch ($data['action']) {
        case 'alsobought':
            #$dbf->printr($data);
            if (isset($data['id']) && count($data['id']) > 0) {
                foreach ($data['id'] as $k => $v) {
                    $id    = $dbf->checkValues((int) $v);
                    $qty   = 1;
                    $ccode = '';
                    $scode = '';
                    $add   = $cart->add($id, $ccode, $scode, $qty, $lang);
                    if ($add->flg == 1 && isset($_SESSION['PNSDOTVN_CART'])) {
                        $bought += $qty;
                    }
                }
                echo json_encode([
                    'code'     => 'success',
                    'pnsdotvn' => [
                        'total_bought'     => $bought,
                        'total_item'       => $cart->pcount(),
                        'total_amount'     => $cart->totalprice(),
                        'total_amount_txt' => $dbf->pricevnd($cart->totalprice(), $_LNG->product->currency),
                    ],
                ]);
            }
            else {
                echo json_encode(['code' => 'fail']);
            }
            break;
        case 'addtoCart':
            $productId  = !empty($data['product_id']) ? $dbf->checkValues((int) $data['product_id']) : '';
            $qty   = !empty($data['qty']) && $data['qty'] >= 1 && $data['qty'] <= 99 ? $dbf->checkValues((int) $data['qty']) : 1;
            $attribute = !empty($data['attribute']) ? $data['attribute'] : '';
            if($attribute == 'size') {
                $scode = !empty($data['value']) ? $dbf->checkValues($data['value']) : '';
            } else {
                $ccode = !empty($data['value']) ? $dbf->checkValues($data['value']) : '';
            }
            if (!empty($productId) && !empty($qty)) {
                if (empty($ccode)) {
                    $ccode = $dbf->getDefaultColorCode($productId);
                }
                if (empty($scode)) {
                    $scode = $dbf->getDefaultSizeCode($productId);
                }
                $add = $cart->add($productId, $ccode,$scode, $qty, $lang);
                if ($add->flg == 1 && isset($_SESSION['PNSDOTVN_CART'])) {
                    $item   = $cart->getProduct($productId, $lang);
                    $icolor = $dbf->getColorbyCode($ccode, $lang);
                    $isize  = $dbf->getSizebyCode($scode, $lang);
                    if(empty($ccode) && empty($scode)) {
                        $price = @$item['price'];
                    } else {
                        $price = $cart->getPrice($productId, $data['value']);
                    }
                    echo json_encode([
                        'code'     => 'success',
                        'pnsdotvn' => [
                            'item'             => [
                                'alt'       => $item['rewrite'],
                                'color'     => (is_object($icolor) && isset($icolor->name) ? $icolor->name : 'nocolor-0'),
                                'size'      => (is_object($isize) && isset($isize->name) ? $isize->name : 'nosize-0'),
                                'href'      => (MULTI_LANG ? DS . $lg['code2'] : '') . DS . $_LNG->others->product->rewrite . DS . $item['rewrite'] . '-' . $item['id'] . EXT,
                                'id'        => $id . '_' . (!empty($ccode) ? $ccode : 'nocolor-0') . '_' . (!empty($scode) ? $scode : 'nosize-0'),
                                'name'      => stripslashes($item['name']),
                                'price'     => $item['price'],
                                'price_txt' => $dbf->pricevnd($item['price'], $_LNG->product->currency),
                                'qty'       => (int) $add->qty,
                                'src'       => '/thumb/400x400/1:1' . $item['picture'],
                                'title'     => str_replace('"', '', $item['name']),
                            ],
                            'qty'              => (int) $qty,
                            'total_item'       => $cart->pcount(),
                            'total_amount'     => $cart->totalprice(),
                            'total_amount_txt' => $dbf->pricevnd($cart->totalprice(), $_LNG->product->currency),
                        ],
                    ]);
                }
                else {
                    echo json_encode(['code' => 'fail']);
                }
            }
            break;

        case 'updateCart':
            $id  = isset($data['id']) && !empty($data['id']) ? $dbf->checkValues($data['id']) : '';
            $qty = isset($data['qty']) && $data['qty'] >= 0 && $data['qty'] <= 99 ? (int) $data['qty'] : 1;
            $value = !empty($data['value']) ? $data['value'] : NULL;
            if (!empty($id) && !empty($qty)) {
                $upd = $cart->update($id,$value, $qty, $lang);
                if ($upd->flg == 1 && isset($upd->id)) {
                    $item   = $cart->getProduct($id);

                    if(empty($value)) {
                        $price = @$item['price'];
                        if($item['wholesale']) {
                            $wholesale = unserialize($item['wholesale']);
                            $price = $cart->getPriceByWholesale($wholesale, $qty);
                        }
                    } else {
                        $price = $cart->getPrice($id, $value);
                    }
                    $amount    = $price * $qty;
                    $sub_total = $cart->totalprice();
                    echo json_encode([
                            'code'     => 'success',
                            'pnsdotvn' => [
                                'total_item'       => $cart->pcount(),
                                'total_amount'     => $sub_total,
                                'total_amount_txt' => $dbf->pricevnd($sub_total, $_LNG->product->currency),
                                'amount'           => $amount,
                                'amount_txt'       => $dbf->pricevnd($amount, $_LNG->product->currency),
                                'subtotal'         => $sub_total,
                                'subtotal_txt'     => $dbf->pricevnd($sub_total, $_LNG->product->currency),
                                'price'            => $price,
                                'price_text'       => $dbf->pricevnd($price, $_LNG->product->currency),
                                'item'             => [
                                    'id'  => $id,
                                    'qty' => $qty,
                                ],
                            ],
                        ]);
                }
                else {
                    echo json_encode(['code' => 'fail']);
                }
            }
            break;

        case 'removeCart':
            $cart->removeall();
            break;

        case 'removeItem':
            $id = isset($data['id']) && !empty($data['id']) ? $dbf->checkValues($data['id']) : '';
            if (!empty($id)) {
                $cart->remove($id, $value, $lang);
                echo json_encode([
                        'code'     => 'success',
                        'pnsdotvn' => [
                            'id'               => $id,
                            'total_item'       => $cart->pcount(),
                            'total_amount'     => $cart->totalprice(),
                            'total_amount_txt' => $dbf->pricevnd($cart->totalprice(), $_LNG->product->currency),
                        ],
                    ]);
            }
            break;
        case 'loadMore':
            // Lấy trang hiện tại
            $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;

            if ($page < 1) {
                $page = 1;
            }

            $limit = $_POST['limit'];

            $start = ($limit * $page) - $limit;
            $conditions = [];
            $conditions[] = [
                'field' => 'sale_ajax',
                'type' => '=',
                'value' => 1,
            ];
            $conditions[] = [
                'field' => 'outofstock',
                'type' => '=',
                'value' => 0,
            ];
            if(!empty($_POST['listCate'])) {
                $conditions[] = [
                    'field' => 'cid',
                    'type' => 'in',
                    'value' => $_POST['listCate'],
                ];
            }
            $orderBys = [];
            $orderBys[] = [
                'field' => 'modified',
                'type' => 'desc',
            ];

            $productSale = $dbf->selectData(prefixTable . 'product', $conditions, $orderBys, $limit + 1, $start);
            $result = [];
            foreach ($productSale as $product) {
                $conditions = [
                    [
                        'field' => 'id',
                        'type' => '=',
                        'value' => $product['id'],
                    ],
                ];
                $pDesc = $dbf->selectData(prefixTable . 'product_desc', $conditions);
                if ($pDesc) {
                    $pDesc = reset($pDesc);
                    $pDesc = $pDesc['rewrite'];
                    $href = '/san-pham/' . $pDesc . '-' . $product['id'] . '.html';
                }
                $imgUrl = explode(';', $product['picture']);
                $imgUrl = reset($imgUrl);
                $row = [
                    'id' => $product['id'],
                    'image' => $imgUrl,
                    'link'  => $href,
                    'name'  => $product['name'],
                    'price' => $product['price'],
                    'list_price' => $product['list_price']
                ];
                // Thêm vào result
                array_push($result, $row);
            }
            // Trả kết quả về cho ajax
            die (json_encode($result));
            break;
        case 'saleFilter':
            $listCate = $_POST['listCate'];
            $conditions = [];
            $conditions[] = [
                'field' => 'sale_ajax',
                'type' => '=',
                'value' => 1,
            ];
            $conditions[] = [
                'field' => 'outofstock',
                'type' => '=',
                'value' => 0,
            ];
            $conditions[] = [
                'field' => 'cid',
                'type' => 'in',
                'value' => $listCate,
            ];
            $orderBys = [];
            $orderBys[] = [
                'field' => 'modified',
                'type' => 'desc',
            ];
            $productSale = $dbf->selectData(prefixTable . 'product', $conditions, $orderBys, 4, 0);
            $totalproductSale = $dbf->selectData(prefixTable . 'product', $conditions, $orderBys);
            $result = [];
            foreach ($productSale as $product) {
                $conditions = [
                    [
                        'field' => 'id',
                        'type' => '=',
                        'value' => $product['id'],
                    ],
                ];
                $pDesc = $dbf->selectData(prefixTable . 'product_desc', $conditions);
                if ($pDesc) {
                    $pDesc = reset($pDesc);
                    $pDesc = $pDesc['rewrite'];
                    $href = '/san-pham/' . $pDesc . '-' . $product['id'] . '.html';
                }
                $imgUrl = explode(';', $product['picture']);
                $imgUrl = reset($imgUrl);
                $row = [
                    'id' => $product['id'],
                    'image' => $imgUrl,
                    'link'  => $href,
                    'name'  => $product['name'],
                    'price' => $product['price'],
                    'list_price' => $product['list_price']
                ];
                // Thêm vào result
                array_push($result, $row);
            }
            // Trả kết quả về cho ajax
            die (json_encode([
                'result' => $result,
                'total'  => count($totalproductSale)
            ]));
            break;
        case 'quotes':
            if($data['type'] == 'add') {
                $product = $cart->getProduct($data['productId']);
                if ($product) {
                    if (empty($_SESSION['quontes'])) {
                        $_SESSION['quontes'] = [
                            [
                                'quantity' => 1,
                                'product_id'    => $data['productId']
                            ]
                        ];
                        $status = 1;
                        $message = "Sản phẩm đã được thêm vào báo giá";
                    } elseif (!empty($_SESSION['quontes'])) {
                        $key = array_search($data['productId'], array_column($_SESSION['quontes'], 'product_id'));
                        if($_SESSION['quontes'][$key]['product_id'] == $data['productId']) {
                            $_SESSION['quontes'][$key]['quantity'] = intval($_SESSION['quontes'][$key]['quantity']) + 1;
                        } else {
                            $newItem = [
                                'quantity' => 1,
                                'product_id'    => $data['productId']
                            ];
                            array_push($_SESSION['quontes'], $newItem);
                        }
                        $status = 1;
                        $message = "Sản phẩm đã được thêm vào báo giá";
                    }
                } else {
                    $status = 0;
                    $message = "Sản phẩm không tồn tại";
                }
                // Trả kết quả về cho ajax
                die (json_encode([
                    'status' => $status,
                    'message' => $message
                ]));
            }
            if($data['type'] == 'delete') {
                $key = array_search($data['productId'], array_column($_SESSION['quontes'], 'product_id'));
                unset($_SESSION['quontes'][$key]);
                die (json_encode([
                    'status' => 1,
                    'message' => 'Xóa sản phẩm thành công'
                ]));
            }
            if($data['type'] == 'update') {
                foreach ($data['list'] as $item) {
                    $items = explode('-', $item);
                    $key = array_search($items[0], array_column($_SESSION['quontes'], 'product_id'));
                }
                $key = array_search($data['productId'], array_column($_SESSION['quontes'], 'product_id'));
                $_SESSION['quontes'][$key]['quantity'] = $items[1];
                die (json_encode([
                    'status' => 1,
                    'message' => 'Cập nhật báo giá thành công'
                ]));
            }
            if($data['type'] == 'confirm') {
                $list = explode('#', $data['list']);
                array_pop($list);
                $products = [];
                foreach ($list as $item) {
                    $items = explode('-', $item);
                    $products[] = [
                        'product_id'=> $items[0],
                        'quantity'=> $items[1]
                    ];
                }
                $listP = serialize($products);
                $arrayValues = [
                    'full_name' => $data['name'],
                    'email' => $data['mail'],
                    'content' => $data['content'],
                    'list' => $listP,
                    'phone' => $data['phone']
                ];
                $cart->insertTable(prefixTable . 'quontes', $arrayValues);
                unset($_SESSION['quontes']);
                die (json_encode([
                    'status' => 1,
                    'message' => 'Gửi yêu cầu báo giá thành công! chúng tôi sẽ gửi thông tin báo giá sớm nhất cho bạn'
                ]));
            }
            break;

        /*
        case 'calcTax':
            $sub_total = $cart->totalprice();
            $tax_amount = ($data['type'] == 'plus') ? $sub_total * 0.1 : 0;
            $total = $sub_total + $tax_amount;
            echo json_encode(array('subtotal' => $cart->transfer_money($sub_total), 'taxamount' => $cart->transfer_money($tax_amount), 'total' => $cart->transfer_money($total)));
            break;
        */
    }
}