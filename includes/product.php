<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}

$html = $pns->buildBreadcrumb($def, $_LNG);
$a = $pns->getProduct($def);
if (isset($a['list'][0]) && is_object($a['list'][0])) {
    require_once PNSDOTVN_CAP . DS . 'rand' . PHP;
    $_SESSION['captcha_id'] = $strcaptcha;
    $v = $a['list'][0];
    $wholesale = unserialize($v->wholesale);
    $def->product = $v->id;
    $def->catalog = $v->cid;
    $def->brand = $v->brand;
    $def->supplier = $v->supplier;
    $def->jalsobuy = $v->jalsobuy;
    $brand = $pns->buildBrand($def, $_LNG);
    $supplier = $pns->buildSupplier($def, $_LNG);
    $option = unserialize($v->joption);
    $v->href = HOST . $v->href;
    $v->img = HOST . $v->src;
    $def->option = $option;
    $jdata = unserialize($v->info);
    $v->color = isset($jdata['color']) ? $jdata['color'] : '';
    $v->product_size = isset($jdata['size']) ? $jdata['size'] : '';
    $m = (isset($_SESSION['member']) && count($_SESSION['member']) > 0 ? TRUE : FALSE);
    if ($m) {
        $w = $pns->getWishlist($def, $_LNG);
    }
    $f = ($m && isset($w) && is_array($w) && in_array($v->id, array_keys($w)) ? TRUE : FALSE);
    $v->loggedin = $pns->chkLoggedin();
    $html .= '<input type="hidden" name"product_id" id="product_id" value="' . $v->id . '">';
    $html .= '<div id="pro_detail" itemscope itemtype="http://schema.org/Product">
                <link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'glasscase.min.css" media="screen" />
                <script type="text/javascript" src="/themes/default/js/functions-products.js"></script>
                <script type="text/javascript" src="' . JS_PATH . 'JS.loadcomment.js"></script>
                <script type="text/javascript" src="' . JS_PATH . 'jquery.glasscase.min.js" ></script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#glasscase").glassCase({ \'widthDisplay\': 480, \'heightDisplay\': 550, \'thumbsPosition\': \'bottom\', \'colorIcons\': \'#fff\', \'colorActiveThumb\': \'#ddd\' });
                      });
                    </script>
                <script type="text/javascript">var ITEM_ID = ' . $v->id . '; var ITEM_TYPE = "PRODUCT"</script>
            <div id="pro_detail_info">';
    $html .= '<div class="picture col-md-5">';
    $html .= $pns->buildPicture($def, $v) != '' ? $pns->buildPicture($def, $v) : '';
    $html .= '</div><div class="description col-md-5">';
    $html .= '<div class="alert alert-success add-cart-message hidden"></div>';
    $html .= '<h1 class="name" itemprop="name">' . $v->name . '</h1>';
	$html .= '<div class="price mb10" style="display: inline-flex"><div><span id="pns_price">';
    $html .= ($v->price > 0 ? $pns->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></div>';
    $html .= '<div style="padding: 0 0 0 100px;">';
    $html .= ($v->price < $v->list_price ?
            '<span class="list-price" style="float: right">' . $pns->pricevnd($v->list_price, $_LNG->product->currency) . '</span>' .
            ($v->sale_off > 0 ? '<span class="save">(-<span>' . $v->sale_off . '%</span>)</span>' : '') : '') . '</div>' .
        '</div>';
    $html .= $pns->buildProductRating($v);
    $html .= '<div class="q-view">
                <a href="#pro_detail_detail">Xem chi tiết sản phẩm</a> | 
                <a href="#comments">Xem bình luận sản phẩm</a>' . $pns->buildPrevNextProduct($def) .
        '</div>';
    $html .= '<div class="short-des" style="clear:both" itemprop="description">' . $v->introtext . '</div>';
    $html .= '<div class="beefup"><ul>';
    if (!empty($wholesale)) {
        $html .= '<div><i class="fa fa-bell blink" aria-hidden="true"></i> <span>Bán buôn/sỉ: &nbsp;</span>';
        $totalWholesale = count($wholesale);
        foreach ($wholesale as $key => $value) {
            if ($key > 1) {
                break;
            }
            if ($value['quantity_from'] == $value['quantity_to']) {
                $html .= '<span>Mua ( > ' . $value['quantity_to'] . ' )' . $pns->pricevnd($value['wholesale_price'], $_LNG->product->currency) . '</span>,&nbsp;';
            } else {
                $html .= '<span>Mua ( ' . $value['quantity_from'] . '-' . $value['quantity_to'] . ' ) ' . $pns->pricevnd($value['wholesale_price'], $_LNG->product->currency) . '</span>';
                if ($key < 1) {
                    $html .= ',&nbsp;';
                }
            }

        }
        if ($totalWholesale >= 2) {
            $html .= '&nbsp;<a href="javascript:void(0);" id="view-more-wholesale">
                     <span>Chi tiết</span></a>';
        }
        $html .= '</div><br>';
    }

    $productColorHtml = $pns->buildProductDetailColorList($v->color, $_LNG, $def->code);
    if ($productColorHtml != '') {
        $html .= $productColorHtml;
    }
    $productSizeHtml = $pns->buildProductDetailZize($v->product_size);
    if ($productSizeHtml != '') {
        $html .= $productSizeHtml;
    }
    $html .= ($v->code ? '<li class="label">Mã sản phẩm: <span itemprop="mpn">' . $v->code . '</span></li>' : '');

    $productOptionHtml = $pns->buildProductOption($def);
    if ($productOptionHtml != '') {
        $html .= $productOptionHtml;
    }

    $html .= '<li class="label">Tình trạng: <span>' . (!$v->outofstock ? 'Còn hàng' : 'Hết hàng') . '</span></li>';
    $html .= (!empty($brand) ? '<li class="label">Thương hiệu: <span itemprop="brand">' . $brand . '</span></li>' : '');
    $html .= (!empty($supplier) ? '<li class="label">Nhà cung cấp: <span>' . $supplier . '</span></li>' : '');
    $html .= '</ul></div>';
    $html .= (!empty($v->promodesc) ?
        '<div class="clear box-promo">
                <div class="box-promo-title">Quà tặng khuyến mãi</div>
                <div class="box-promo-conent">' . $v->promodesc . '</div>
            </div>' : '');
    
    $html .= (!$v->outofstock ?
        '<div class="p-d-order">
          <div class="label">
            Số lượng:<input id="quantity" name="quantity" type="number" value="1" style="width: 65px;">
          </div>
          <div class="btnCon"><span>
            <input type="hidden" name="product_' . $v->id . '" id="product_' . $v->id . '" value="' . $v->id . '" />
            <button type="submit" id="' . $v->id . '" name="btnAddtoCart" class="order add-to-cart mr10" title="' . $v->title . '"><i class="fa fa-shopping-cart"></i> Đặt hàng</button>
            </span>
          </div>
          <div class="btnCon"><span class="order-action order-action-mobile">
            <button type="submit" id="' . $v->id . '" name="btnAddtoCart" class="order order-now" title="' . $v->title . '"><i class="fa fa-cart-plus"></i> Mua ngay</button>
            </span>
          </div>
        </div>
        <div class="p-d-hotline"><div class="pulsating-circle"></div> Khách hàng công trình, lấy sỉ vui lòng liên hệ để được giá tốt.</div>' : '');
    $html .= $pns->buildAlsoBoughtProduct($def, $v, $_LNG) .
        '</div>
        <div class="col-md-2 visible-lg visible-md">
            <div class="policy ">
                <ul>
                    <li class="visible-lg visible-md"><i class="fa fa-shield" aria-hidden="true"></i> Hàng chính hãng.</li>
                    <!--<li class="visible-lg visible-md"><i class="fa fa-list-ol" aria-hidden="true"></i> Giá tốt hơn khi <a href="mailto:info@giaphatstore.com?subject=[BG] - ID: #' . $v->id . '" rel="nofollow">mua số lượng</a>.</li>
                    <li><i class="fa fa-facebook" aria-hidden="true"></i><a href="https://m.me/chauruachengiaphat" target="_blank" rel="nofollow"> Chat qua Facebook Messenger</a></li>-->
                    <li class="visible-lg visible-md"><i class="fa fa-map-marker" aria-hidden="true"></i> Giao hàng toàn quốc.</li>				
                    <li class="visible-lg visible-md"><i class="fa fa-credit-card" aria-hidden="true"></i> Thanh toán linh hoạt</li>
                    <li class="visible-lg visible-md"><i class="fa fa-phone-square" aria-hidden="true"></i> Hotline: <a href="tel:+84988753595" rel="nofollow" target="_blank">0988.75.35.95</a></li>
                    <li class="visible-lg visible-md"><i class="fa fa-phone-square" aria-hidden="true"></i> Zalo: <a href="https://zalo.me/nguyentuanvuong" rel="nofollow noopener noreferrer" target="_blank">0988.75.35.95</a></li>
                    <!--<li class="visible-lg visible-md"><i class="fa fa-comments" aria-hidden="true"></i> SMS: <a href="sms:+84988753595" rel="nofollow" target="_blank">0988.75.35.95</a></li>-->
                    <li class="visible-lg visible-md"><button type="submit" id="' . $v->id . '" name="btnAddtoFavorite" class="add-to-favorite' . (!$m ? ' signin' : '') . ($f ? ' added' : '') . '" title="' . $v->title . '"><i class="fa fa-heart' . ($f ? '-o' : '') . '"></i> <span id="favorite"> ' . ($f ? 'Sản phẩm yêu thích' : '') . 'Tôi thích sản phẩm này</span></button></li>
                </ul>
            </div>
            <div class="quote mt20" style="">
                <p class="title">Yêu cầu báo giá SP này:</p>
                <p>
                  <input class="quote-text" type="text" name="c_quantity" id="c_quantity" value="" placeholder="Số lượng" />
                </p>
                <p>
                  <input class="quote-text" type="text" name="c_phone" id="c_phone" value="" placeholder="Điện thoại" />
                </p>
                <p>
                  <input class="quote-text" type="text" name="c_email" id="c_email" value="" placeholder="Địa chỉ email" />
                </p>
                <input type="hidden" name="c_name" id="c_name" value="' . $v->name . '" />
                <input type="hidden" name="c_href" id="c_href" value="' . $v->href . '">
                <p class="center mt10">
                  <input class="quote-button" type="button" name="btnConsultant" id="btnConsultant" value="Gửi yêu cầu" />
                </p>
                <script type="text/javascript" src="/themes/default/js/JS.consultant.js"></script></div>
            </div>
        </div>';
    $html .= '
        <!-- Modal -->
        <div class="modal fade" id="wholesale" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="vertical-alignment-helper">
                <div class="modal-dialog vertical-align-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Bán buôn/sỉ</h4>
                        </div>
                        <div class="modal-body">
                            <table id="classTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Tiết kiệm được</th>
                                    </tr>
                                </thead>
                                <tbody>';

    foreach ($wholesale as $value) {
        $html .= '<tr>';
        if ($value['quantity_from'] == $value['quantity_to']) {
            $html .= '<td>  ' . $value['quantity_to'] . '</td>';
        } else {
            $html .= '<td>' . $value['quantity_from'] . ' - ' . $value['quantity_to'] . '</td>';
        }
        $html .= '<td>' . $pns->pricevnd($value['wholesale_price'], $_LNG->product->currency) . '</td>
                                                <td>' . $pns->pricevnd($v->price - $value['wholesale_price'], $_LNG->product->currency) . '</td>
                                            </tr>
                                        ';
    }

    $html .= '</tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';
    $html .=
        $pns->buildProductInBrand($def, $_LNG) .
        '<div id="pro_detail_detail">
        <div class="help visible-lg visible-md mb10">' .
        (isset($def->pd_desc) && !empty($def->pd_desc) ? str_replace([
            '{icon1}',
            '{icon2}',
            '{icon3}',
        ], [
            '<i class="fa fa-shopping-cart color-1"></i>',
            '<i class="fa fa-truck color-2"></i>',
            '<i class="fa fa-credit-card color-3"></i>',
        ], $def->pd_desc) : '') .
        '</div>	
        <div id="exTab2" class="container">
            <ul class="nav nav-tabs">
                <li class="active ' . (!empty($v->description) ? '' : 'hidden') . '">
                    <a  href="#1" data-toggle="tab"><strong>Chi tiết sản phẩm ' . $v->name . '</strong></a>
                </li>
                <li class="' . (!empty($v->specification) ? '' : 'hidden') . '"><a href="#2" data-toggle="tab"><strong>Thông số kỹ thuật</a></strong></li>
                <li class="' . (!empty($v->guide) ? '' : 'hidden') . '"><a href="#3" data-toggle="tab"><strong>Hướng dẫn sử dụng</a></strong></li>
                <li class="' . (!empty($v->pdf) ? '' : 'hidden') . '"><a href="#4" data-toggle="tab"><strong>Tải về file PDF </a></strong></li>
            </ul>
            <div class="tab-content ">
                <div class="tab-pane active" id="1">
                    ' . $v->description . '
                </div>
                <div class="tab-pane" id="2">
                    ' . $v->specification . '
                </div>
                <div class="tab-pane" id="3">
                    ' . $v->guide . '
                </div>
                <div class="tab-pane" id="3">
                    Nội dung đang được cập nhật.
                </div>
            </div>
        </div>';
    $j = unserialize($v->jrelated);
    if (!empty($j) && is_array($j) && count($j) > 0) {
        $def->route->name = 'related';
        $def->jrelated = $j;
        $html .= $pns->buildProduct($def, $_LNG);
    }
    $v->ctype = 'PRODUCT';
    $html .=
        '<div class="beefup" id="comments"><div class="clear title mt20 beefup__head">Bình luận:</div>' .
        '<div id="comment" class="comment beefup__body">' . $pns->buildProductComment($v) . '</div></div>' .
        '<div class="beefup_ visible-lg visible-md"><div class="clear title mt20 beefup__head_">Tags:</div>' .
        '<div class="tags beefup__body_">Từ khóa liên quan: ' . $pns->buildKeyword($def->keywords) . '</div></div>' .

        $pns->buildRelatedNewsInProduct($def, $v, $_LNG) .

        '</div>	  
        </div>' .
        $pns->buildMenu('m-catalog', $def);
    $pns->buildRecentlyViewedProducts($v);
}
$pns->showHTML($html);
?>

