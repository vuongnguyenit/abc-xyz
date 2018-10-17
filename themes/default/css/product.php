<?php
if (! defined('PHUONG_NAM_SOLUTION')) {
  header('Location: /errors/403.shtml');	
  die();
}

#$pns->printr($_SESSION);
$html = $pns->buildBreadcrumb($def, $_LNG);
$a = $pns->getProduct($def);
if (isset($a['list'][0]) && is_object($a['list'][0])) {
  require_once PNSDOTVN_CAP . DS . 'rand' . PHP;
  $_SESSION['captcha_id'] = $strcaptcha;	
  $v = $a['list'][0];	
  $def->product = $v->id;
  $def->catalog = $v->cid;
  $def->brand = $v->brand;
  $def->supplier = $v->supplier;
  $def->jalsobuy = $v->jalsobuy;
  $b = $pns->buildBrand($def, $_LNG);
  $s = $pns->buildSupplier($def, $_LNG);	
  $o = unserialize($v->joption);
  $v->href = HOST . $v->href;
  $v->img = HOST . $v->src;
  $def->option = $o;
  $jdata = unserialize($v->info);
  $v->color = isset($jdata['color']) ? $jdata['color'] : '';
  $m = (isset($_SESSION['member']) && count($_SESSION['member']) > 0 ? true : false);  
  if ($m) $w = $pns->getWishlist($def, $_LNG);
  $f = ($m && isset($w) && is_array($w) && in_array($v->id, array_keys($w)) ? true : false);
  $v->loggedin = $pns->chkLoggedin();  
  $html .= 
  '<div id="pro_detail" itemscope itemtype="http://schema.org/Product">
	<link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'glasscase.min.css" media="screen" />
	<script type="text/javascript" src="' . JS_PATH . 'jquery.glasscase.min.js" ></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#glasscase").glassCase({ \'widthDisplay\': 370, \'heightDisplay\': 550, \'thumbsPosition\': \'left\', \'colorIcons\': \'#fff\', \'colorActiveThumb\': \'#ddd\' });		
	  });
	</script>
	<script type="text/javascript">var ITEM_ID = ' . $v->id . '; var ITEM_TYPE = "PRODUCT"</script>
	<!--<script type="text/javascript" src="' . JS_PATH . 'JS.loadcomment.js"></script> -->
	<div id="pro_detail_info">
	  <div class="picture col-md-5">' .
		$pns->buildPicture($def, $v) .
	  '</div>
	  <div class="description col-md-5">
		<h1 class="name" itemprop="name">' . $v->name . '</h1>'.				
		$pns->buildProductRating($v) .				
		'<div class="q-view"><a href="#pro_detail_detail">Xem chi tiết sản phẩm</a> | <a href="#comments">Xem bình luận sản phẩm</a>' . $pns->buildPrevNextProduct($def) . '</div>' .	
							
		$pns->buildProductDetailColorList($v->color, $_LNG, $def->code) .
		'<div class="short-des" style="clear:both" itemprop="description">' . $v->introtext . '</div>
		<div class="beefup m-b-20"><!--<div class="beefup__head__ beefup-title mt10 clear">Thông tin sản phẩm</div>--><ul class="beefup__body__">' .
		  ($v->code ? '<li class="label">Mã sản phẩm: <span itemprop="mpn">' . $v->code . '</span></li>' : '') .
		  $pns->buildProductOption($def) .
		  '<li class="label">Tình trạng: <span>' . ($v->outofstock ? 'Hết hàng' : 'Còn hàng') . '</span></li>' .
		  (!empty($b) ? '<li class="label">Thương hiệu: <span itemprop="brand">' . $b . '</span></li>' : '') .		
		  (!empty($s) ? '<li class="label">Nhà cung cấp: <span>' . $s . '</span></li>' : '') .	  
		'</ul></div>' .
		(!empty($v->promodesc) ?
		'<div class="clear box-promo">
		  <div class="box-promo-title">Quà tặng khuyến mãi</div>
		  <div class="box-promo-conent">' . $v->promodesc . '</div>
		</div>' : '') .
		'<div class="price mb10" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer"><span id="pns_price">' . ($v->price > 0 ? $pns->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span><meta itemprop="price" content="' . $v->price . '">
			<meta itemprop="priceCurrency" content="VND">
			<link itemprop="availability" href="http://schema.org/InStock"></div>'.
		($v->price < $v->list_price ? 
		'<div class="mb10"><span class="list-price">' . $pns->pricevnd($v->list_price, $_LNG->product->currency) . '</span>' . ($v->sale_off > 0 ? '<span class="save">(-<span>' . $v->sale_off . '%</span>)</span>' : '') . '</div>' : '') .
		(!$v->outofstock ?
		'<div class="p-d-order">
		  <div class="label">Số lượng:
			<select id="quantity" name="quantity" style="width:67px;">
			  <option value="1">1</option>
			  <option value="2">2</option>
			  <option value="3">3</option>
			  <option value="4">4</option>
			  <option value="5">5</option>
			  <option value="6">6</option>
			  <option value="7">7</option>
			  <option value="8">8</option>
			  <option value="9">9</option>
			  <option value="10">10</option>
			</select>
		  </div>		  			
		  <div class="btnCon"><span>
			<input type="hidden" name="product_' . $v->id . '" id="product_' . $v->id . '" value="' . $v->id . '" />
			<button type="submit" id="' . $v->id . '" name="btnAddtoCart" class="add-to-cart mr10" title="' . $v->title . '"><i class="fa fa-shopping-cart"></i> Đặt hàng</button>
			</span>
		  </div>
		  <div class="btnCon"><span class="order-action order-action-mobile">
			<button type="submit" id="' . $v->id . '" name="btnAddtoCart" class="order-now buynow" title="' . $v->title . '"><i class="fa fa-cart-plus"></i> Mua ngay</button>
			</span>
		  </div>
		  <!--<div class="favorite">
			<div class="btnCon">
			  
			</div>
		  </div>-->		  						
		</div>
		<div class="p-d-hotline"><strong>*</strong> Khách hàng công trình, lấy sỉ vui lòng liên hệ để được giá tốt.</div>' : '').	
		$pns->buildAlsoBoughtProduct($def, $v, $_LNG) .
	  '</div>	  
		<div class="col-md-2 visible-lg visible-md">
			<div class="policy ">
				<ul>
					<li class="visible-lg visible-md"><i class="fa fa-shield" aria-hidden="true"></i> Hàng chính hãng.</li>
					<li class="visible-lg visible-md"><i class="fa fa-list-ol" aria-hidden="true"></i> Giá tốt hơn khi <a href="mailto:info@giaphatstore.com?subject=[BG] - ID: #'. $v->id .'" rel="nofollow">mua số lượng</a>.</li>
					<!--<li><i class="fa fa-facebook" aria-hidden="true"></i><a href="https://m.me/chauruachengiaphat" target="_blank" rel="nofollow"> Chat qua Facebook Messenger</a></li>-->
					<li class="visible-lg visible-md"><i class="fa fa-map-marker" aria-hidden="true"></i> Giao hàng toàn quốc.</li>				
					<li class="visible-lg visible-md"><i class="fa fa-credit-card" aria-hidden="true"></i> Thanh toán linh hoạt</li>
					<li class="visible-lg visible-md"><i class="fa fa-phone-square" aria-hidden="true"></i> Hotline: <a href="tel:+84988753595" rel="nofollow" target="_blank">0988.75.35.95</a></li>
					<li class="visible-lg visible-md"><i class="fa fa-phone-square" aria-hidden="true"></i> Zalo: <a href="https://zalo.me/nguyentuanvuong" rel="nofollow noopener noreferrer" target="_blank">0988.75.35.95</a></li>
					<li class="visible-lg visible-md"><i class="fa fa-comments" aria-hidden="true"></i> SMS: <a href="sms:+84988753595" rel="nofollow" target="_blank">0988.75.35.95</a></li>
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
	</div>' ;	
	$html .=
	$pns->buildProductInBrand($def, $_LNG) .
	'<div id="pro_detail_detail">
	  <div class="help visible-lg visible-md mb10">' .
		(isset($def->pd_desc) && !empty($def->pd_desc) ? str_replace(array('{icon1}', '{icon2}', '{icon3}'), array('<i class="fa fa-shopping-cart color-1"></i>', '<i class="fa fa-truck color-2"></i>', '<i class="fa fa-credit-card color-3"></i>'), $def->pd_desc) : '') .
	  '</div>	
	  <h2 class="title">Chi tiết sản phẩm ' . $v->name . '</h2>
	  <div class="clear pro-detail-content view__">' . (!empty($v->description) ? $v->description : 'Nội dung đang được cập nhật.') . '</div>' ;
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
	  '<div class="beefup"><div class="clear title mt20 beefup__head">Tags:</div>' .
	  '<div class="tags beefup__body">Từ khóa liên quan: ' . $pns->buildKeyword($def->keywords) . '</div></div>' .
	  
	  $pns->buildRelatedNewsInProduct($def, $v, $_LNG) .
	  
	'</div>	  
  </div>' .
  $pns->buildMenu('m-catalog', $def); 
  $pns->buildRecentlyViewedProducts($v); 
}
$pns->showHTML($html);