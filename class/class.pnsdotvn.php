<?php
require_once('class.BUL.php');

class PNS_DOT_VN extends BusinessLogic {

    function showHTML($html = '') {
        echo $this->compressHtml($html);
    }

    /**
     * @param $p
     * @param string $html
     *
     * @return string
     */
    function buildProductRating($p, $html = '') {
        $html = '<link href="' . CSS_PATH . 'bootstrap-rating.css" rel="stylesheet">
            <script type="text/javascript" src="' . JS_PATH . 'bootstrap-rating.js"></script>
            <script type="text/javascript" src="' . JS_PATH . 'JS.rating.js"></script>
            <input type="hidden" class="rating" value="' . ($rating = round($p->rating_point / $p->rating_vote, 1)) . '" data-fractions="2" />' . ($p->rating_vote > 0 ? '
            <span class="label label-default">(' . $p->rating_vote . ' lượt bình chọn)</span>' : '') .
            '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="hidden">
                <span itemprop="ratingValue">' . $rating . '</span>
                <span itemprop="bestRating">5</span>
                <span itemprop="ratingCount">' . $p->rating_vote . '</span>
            </div>';
        return $html;
    }

    function buildCmsRating($p, $html) {
        $html = '<link href="' . CSS_PATH . 'bootstrap-rating.css" rel="stylesheet">
		<script type="text/javascript" src="' . JS_PATH . 'bootstrap-rating.js"></script>
		<script type="text/javascript" src="' . JS_PATH . 'JS.cmsrating.js"></script>
		<input type="hidden" class="rating" value="' . ($rating = round($p->rating_point / $p->rating_vote, 1)) . '" data-fractions="2" />' . ($p->rating_vote > 0 ? '<span class="label label-default">(' . $p->rating_vote . ' lượt bình chọn)</span>' : '') . '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="hidden">
			<span itemprop="ratingValue">' . $rating . '</span>
			<span itemprop="bestRating">5</span>
			<span itemprop="ratingCount">' . $p->rating_vote . '</span>
		</div>';
        return $html;
    }

    function buildScrollingScript($d, $html = '') {
        if (isset($_GET['PNSDOTVN_TEST_MODE'])) {
            $this->printr($d);
        }
        if (!isset($d->filter)) {
            $html = '<script type="text/javascript">
				var is_busy=!1,page=1,stopped=1,IS_SCROLLING = true;
				$(document).ready(function(){
					var catalog = ' . ($d->route->name == 'home' && $d->route->position == 'new' ? '$(\'.san_pham_moi .products .content\')' : '$(\'#head_toolbar .sorter\')') . '.attr(\'id\');' . 'IS_SCROLLING && $(window).scroll(function(){
						return $element=' . ($d->route->name == 'home' && $d->route->position == 'new' ? '$(\'.products #\' + catalog)' : '$(\'.products .content\')') . ',
						$loadding=$(\'#scroll_loading\'),
						$(window).scrollTop()+$(window).height()>=$element.height()?1==is_busy?!1:1==stopped?!1:(is_busy=!0,page++,$loadding.removeClass(\'hidden\'),
						$.ajax({
							type:\'post\',
							dataType:\'json\',
							url:\'/xu-ly-du-lieu\',
							data:{action:\'scrolling\', page:page, catalog:catalog},
							success:function(a,t){
								var d=\'\';
								\'success\' == a.code && \'success\' == t && $.each(a.data,function(t){										
									d += (a.display == \'PRODUCT_DISPLAY_GRID\' ?
									\'<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">\' +
									  \'<div class="border box-img"><span class="over-layer-0"></span>\' +
										\'<div class="picture"><a href="\' + a.data[t].href + \'" title="\' + a.data[t].title + \'"><img alt="\' + a.data[t].alt + \'" src="/thumb/250x250\' + a.data[t].src + \'" title="\' + a.data[t].title + \'" /></a></div>\' +
										\'<div class="proname"><a href="\' + a.data[t].href + \'" title="\' + a.data[t].title + \'">\' + a.data[t].name + \'</a></div>\' +
										\'<div class="gr-price">\' + (a.data[t].list_price > a.data[t].price ? (\'<span class="list-price"><span>\' + a.data[t].list_price_txt + \'</span></span>\') : \'\') + \'<span class="price"><span>\' + a.data[t].price_txt + \'</span></span></div>\' +
									  \'</div>\' +
									\'</div>\' :
									\'<div class="block detail">\' +
									\'<div class="col-left col-md-9">\' +
										\'<div class="picture"><a href="\' + a.data[t].href + \'" title="\' + a.data[t].title + \'"><img alt="\' + a.data[t].alt + \'" src="/thumb/500x500\' + a.data[t].src + \'" title="\' + a.data[t].title + \'" /></a></div>\' +
										\'<div class="name"><a href="\' + a.data[t].href + \'" title="\' + a.data[t].title + \'">\' + a.data[t].name + \'</a></div>\' +
										\'<div class="description">\' + a.data[t].desc + \'</div>\' +
									  \'</div>\' +
									  \'<div class="col-right col-md-3">\' +
										\'<div class="price"><span class="price-new">\' + a.data[t].price_txt + \'</span>\' + 
										  (a.data[t].list_price > a.data[t].price ? \'<br /><span class="price-old">\' + a.data[t].price_txt + \'</span>\' : \'\') +
										\'</div>\' +
										\'<div class="order">\' +
										  \'<input type="hidden" name="product_\' + a.data[t].id + \'" id="product_\' + a.data[t].id + \'" value="\' + a.data[t].id + \'" />\' +
										  \'<input type="button" id="\' + a.data[t].id + \'" name="btnAddtoCart" value="Thêm vào giỏ hàng" title="\' + a.data[t].title + \'" />\' +
										\'</div>\' +
									  \'</div>\' +
									  
									\'</div>\')
								}),
								$element.append(d)
							}
						}).always(function(){
							$loadding.addClass(\'hidden\'), is_busy=!1
						}), !1):void 0
					})
				});
				</script>';
        }
        return $html;
    }

    function buildPointHistory($d, $_LNG, $html = '') {
        $html = '<h1>Quản lý điểm tặng</h1>' . '<div class="clear">Bạn hiện có <strong>' . $this->getPoint() . '</strong> điểm.</div>';
        $a = $this->getPointHistory($d);
        #$this->printr($a);
        if (isset($a['list']) && is_array($a['list']) && isset($a['total']) && $a['total'] > 0) {
            $html .= '<div class="clear">
			<table class="table table-striped table-bordered">
			  <thead>
				<tr>
				  <th>Điểm tặng</th>
				  <th>Thời gian </th>
				  <th>Nội dung</th>
				</tr>
			  </thead>
			  <tbody>';
            foreach ($a['list'] as $k => $v) {
                $html .= '<tr>
				  <td class="' . $v->code . '">' . $v->symbol . $v->point . '</td>
				  <td>' . $v->added . '</td>
				  <td>' . $v->content . '</td>				  
				</tr>';
            }
            $html .= '</tbody></table>' . (!empty($a['page']) ? '<div class="clear paging">' . $a['page'] . '</div>' : '') . '</div>';
        }
        return $html;
    }

    function buildRelatedNewsInProduct($d, $p, $_LNG, $html = '') {
        $a = $this->getRelatedNewsInProduct($d, $p);
        if (isset($a['list']) && is_array($a['list']) && isset($a['total']) && $a['total'] > 0) {
            $html = '<div class="other-news mt20" style="clear:both">
			  <div class="title title-grey">Tin liên quan đến sản phẩm "' . $p->name . '"</div>
			  <div id="other-news" class="util-carousel top-nav-box">';
            foreach ($a['list'] as $k => $v) {
                $html .= '<div class="item"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->alt . '" src="/thumb/210x150/1.4:1' . $v->src . '" title="' . $v->title . '" /><span>' . $v->name . '</span></a></div>';
            }
            $html .= '</div>
			  <script type="text/javascript" src="' . JS_PATH . 'viewer.min.js" ></script>
			  <script type="text/javascript" src="' . JS_PATH . 'script.viewer.js" ></script>
			  <script> $(function() { $("#other-news").utilCarousel({ navigation : true, navigationText : [\'<i class="icon-left-open-big"></i>\', \'<i class="icon-right-open-big"></i>\'], breakPoints : [[1900, 5], [1200, 4], [992, 3], [768, 2], [480, 1]] }); }); </script>
			</div>';
        }
        return $html;
    }

    function buildProductInBrand($def, $_LNG, $html = '') {
        $a = $this->getProductInBrand($def);
        if (isset($a['list']) && is_array($a['list']) && isset($a['total']) && $a['total'] > 0) {
            $html = '<section class="products mt20">
  			  <h2 class="title box-line"><a rel="nofollow" href="javascript:;" title="Sản phẩm cùng thương hiệu">Sản phẩm cùng thương hiệu</a></h2>  
  			  <div id="normal-imglist-inbrand" class="util-carousel normal-imglist wow fadeIn animated">';
            foreach ($a['list'] as $a => $b) {
                $v = (object) $b;
                $o = $v->outofstock ? TRUE : FALSE;
                $html .= '<div class="item">
				  <div class="block">
					<div class="border">' . (!empty($v->lname) && !empty($v->lcolor) ? '<span class="label ' . $v->lcolor . '">' . $v->lname . '</span>' : '') . '<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/250x250' . $v->src . '" title="' . $v->title . '" /></a></div>
					  <div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
					  <div class="gr-price">' . ($v->list_price > $v->price ? ' <span class="list-price"><span>' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span></div>
					</div>
				  </div>
				</div>';
            }
            $html .= '</div>
			  <script type="text/javascript"> $(function(){$("#normal-imglist-inbrand").utilCarousel({pagination:!1,navigationText:[\'<i class="icon-left-open-big"></i>\',\'<i class=" icon-right-open-big"></i>\'],navigation:!0,rewind:!1,breakPoints : [[1900, 5], [1200, 5], [992, 3], [768, 2], [480, 2]]})}); </script>
			</section>';
        }
        return $html;
    }

    function buildViewedProduct($_LNG, $html = '') {
        if (isset($_SESSION['PNSDOTVN_VIEWED_PRODUCTS']) && count($_SESSION['PNSDOTVN_VIEWED_PRODUCTS']) > 0) {
            $a['list'] = array_reverse($_SESSION['PNSDOTVN_VIEWED_PRODUCTS']);
            $html = '<section class="products mt20">
  			  <h2 class="title box-line"><a rel="nofollow" href="javascript:;" title="Sản phẩm đã xem">Sản phẩm đã xem</a></h2>  
  			  <div id="normal-imglist-viewed-product" class="util-carousel normal-imglist wow fadeIn animated">';
            foreach ($a['list'] as $a => $b) {
                $v = (object) $b;
                $o = $v->outofstock ? TRUE : FALSE;
                $html .= '<div class="item">
				  <div class="block">
					<div class="border">' . #(!empty($v->lname) && !empty($v->lcolor) ? '<span class="label ' . $v->lcolor . '">' . $v->lname . '</span>' : '') .
                    '<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/250x250' . $v->src . '" title="' . $v->title . '" /></a></div>
					  <div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
					  <div class="gr-price">' . ($v->list_price > $v->price ? ' <span class="list-price"><span>' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span></div>
					</div>
				  </div>
				</div>';
            }
            $html .= '</div>
			  <script type="text/javascript"> $(function(){$("#normal-imglist-viewed-product").utilCarousel({pagination:!1,navigationText:[\'<i class="icon-left-open-big"></i>\',\'<i class=" icon-right-open-big"></i>\'],navigation:!0,rewind:!1,breakPoints : [[1900, 5], [1200, 5], [992, 3], [768, 2], [480, 2]]})}); </script>
			</section>';
        }
        return $html;
    }

    function buildForm($d, $_LNG, $html = '') {
        switch ($d->route->name) {
            case 'signup' :
                $html = '<div id="signup">
				  <div class="register-box">
					<div class="top">
					  <form id="frmSignup" name="frmSignup" action="/xu-ly-dang-ky" method="post" enctype="application/x-www-form-urlencoded">
						<h3>Đăng ký</h3>
						<select name="salutation" id="salutation" class="form-control {validate:{required:true}}" style="float: left; height:32px; width: 60px !important;">
						  <option value="">--</option>
						  <option value="mr."' . (PNSDOTVN_TEST_MODE ? ' selected="selected"' : '') . '>Ông</option>
						  <option value="mrs.">Bà</option>
						  <option value="ms.">Cô</option>
						</select>
						<input type="text" id="name" name="name" class="input form-control {validate: {required:true}}" maxlength="50" autocomplete="off" placeholder="Họ và tên" style="float:left; width:228px !important;"' . (PNSDOTVN_TEST_MODE ? ' value="Phuong Nam Solution"' : '') . ' />
						<input type="text" id="mobile" name="mobile" class="input form-control {validate: {required:true, number:true, rangelength:[6,12]}}" autocomplete="off" placeholder="Di động"' . (PNSDOTVN_TEST_MODE ? ' value="0935777527"' : '') . ' />
						<input type="text" id="email" name="email" class="input form-control {validate: {required:true, email:true}}" autocomplete="off" placeholder="Email"' . (PNSDOTVN_TEST_MODE ? ' value="info@sieuthinhanh.vn"' : '') . ' />
						<input type="password" id="password" name="password" class="input form-control {validate: {required:true, minlength:6, maxlength:32}}" autocomplete="off" placeholder="Mật khẩu"' . (PNSDOTVN_TEST_MODE ? ' value=""' : '') . ' />
						
						<input type="checkbox" id="agree" name="agree" value="1" onclick="document.getElementById(\'register\').disabled=!this.checked" />
						<span class="agree">Tôi đã ĐỌC và ĐỒNG Ý với <a href="/dieu-khoan-su-dung.html" title="Điều khoản sử dụng">Điều khoản sử dụng</a>.</span><br />
						<br />
						<input type="submit" id="register" name="register" class="submit button btn btn-lg" value="Đăng ký" disabled="disabled" />
						&nbsp;
					  </form>
					</div>
					<div class="bottom">Đã có tài khoản ? <a href="/dang-nhap.html" class="already" title="Đăng nhập">Đăng nhập tại đây</a></div>
				  </div>
				  <div class="showdata corner_5" id="showdata" onclick="jQuery(this).css(\'display\', \'none\')">
					<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
					<ol>
					  <li><label for="email" class="error">Địa chỉ email không hợp lệ.</label></li>
					  <li><label for="password" class="error">Mật khẩu bắt buộc nhập và tối thiểu 06 ký tự.</label></li>
					  <li><label for="repassword" class="error">Mật khẩu và Nhập lại mật khẩu không khớp.</label></li>
					  <li><label for="salutation" class="error">Danh xưng bắt buộc chọn.</label></li>
					  <li><label for="name" class="error">Họ và tên bắt buộc nhập.</label></li>
					  <li><label for="mobile" class="error">Di động phải là số.</label></li>
					</ol>
					<a class="close" href="javascript:void(0);" onclick="jQuery(\'div.showdata\').fadeOut(300).hide(1)"></a>
				  </div>
				  <script type="text/javascript"> var SITE_NAME = "' . DOMAINNAME . '"; var SIGNIN_URL = "/dang-nhap.html"; </script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'JS.signup.js"></script>
				</div>';
                break;
            case 'signin' :
                $html = '<div id="content" class="col-md-12">
				  <link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_style.css" />
				  <link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_layout.css" />
				  <link rel="stylesheet" href="' . CSS_PATH . 'fontello.css" />
				  <script src="' . JS_PATH . 'tmm_form_wizard_custom.js"></script> 
				  <!--[if lt IE 9]>
				  <script src="' . JS_PATH . 'respond.min.js"></script>
				  <![endif]-->
				  <form id="frmSignin" name="frmSignin" method="post" action="/xu-ly-dang-nhap" enctype="application/x-www-form-urlencoded" role="form">
					<div id="tmm-form-wizard" class="form-login substrate-transparent">
					  <h2 class="form-login-heading">Đăng nhập tài khoản</h2>
					  <fieldset class="input-block">
						<label for="login">Tên tài khoản:</label>
						<input type="text" id="email" name="email" class="form-icon form-icon-user {validate: {required:true, email:true}}" placeholder="Login" required>
					  </fieldset>
					  <fieldset class="input-block">
						<label for="password">Mật khẩu:</label>
						<input type="password" id="password" name="password" class="form-icon form-icon-lock {validate: {required:true, maxlength:32}}" placeholder="Password" required>
					  </fieldset>
					  <div class="tip"> <a href="/quen-mat-khau.html" title="Quên mật khẩu ? Click vào đây để reset mật khẩu">Quên mật khẩu?</a> </div>
					  <div class="button-group">
						<button class="button" type="submit" id="login" name="login"><span>Đăng nhập</span></button>&nbsp;
						<button class="button" type="reset"><span>Nhập lại</span></button>
					  </div>' . (isset($_GET['PROCEED_TO_CHECKOUT']) && CART_SYS && isset($_SESSION['PNSDOTVN_CART']) && count($_SESSION['PNSDOTVN_CART']) > 0 && CHECKOUT_NOT_REG ? '<p>Hoặc</p>
					  <div class="button-group">
						<button class="button" type="button" id="buy_not_reg" name="buy_not_reg"><span>Mua hàng không cần đăng ký</span></button>
						<script type="text/javascript">$("#buy_not_reg").live("click", function() {document.location.href = "/thanh-toan.html?CHECKOUT_NOT_REG";return false;});</script>
					  </div>' : '') . '</div>
				  </form>
				  <div class="showdata corner_5" id="showdata" onclick="$(this).css(\'display\', \'none\')">
					<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
					<ol>
					  <li><label for="email" class="error">Email bắt buộc và phải đúng định dạng.</label></li>
					  <li><label for="password" class="error">Mật khẩu bắt buộc nhập.</label></li>
					</ol>
					<a class="close" href="javascript:void(0);" onclick="$(\'div.showdata\').fadeOut(300).hide(1)"></a>
				  </div>
				  <script type="text/javascript"> var REDIRECT_URL = \'' . (isset($_GET['PROCEED_TO_CHECKOUT']) ? '/thanh-toan.html' : $d->redirect) . '\'; </script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'JS.signin.js"></script>
				</div>';
                break;
            case 'signin2' :
                $html = '<div id="signin">
				  <div class="login-box">
					<div class="top">
					  <form id="frmSignin" name="frmSignin" method="post" action="/xu-ly-dang-nhap" enctype="application/x-www-form-urlencoded">
						<h1>Đăng nhập tài khoản</h1>
						<input type="text" id="email" name="email" class="form-control input {validate: {required:true, email:true}}" autocomplete="off" placeholder="Email" />
						<input type="password" id="password" name="password" class="form-control input {validate: {required:true, maxlength:32}}" autocomplete="off" placeholder="Mật khẩu" />
						<p>
						  <input type="checkbox" id="remember" name="remember" value="1" />
						  <span class="remember">Ghi nhớ đăng nhập ?</span> | <a href="/quen-mat-khau.html" title="Quên mật khẩu ? Click vào đây để reset mật khẩu">Quên mật khẩu?</a></p>
						<input type="submit" id="login" name="login" class="submit button btn btn-lg" value="Đăng nhập" />
						&nbsp;' . (isset($_GET['PROCEED_TO_CHECKOUT']) && CART_SYS && isset($_SESSION['PNSDOTVN_CART']) && count($_SESSION['PNSDOTVN_CART']) > 0 && CHECKOUT_NOT_REG ? '<p>hoặc</p>
						<input type="button" id="buy_not_reg" name="buy_not_reg" class="button btn btn-lg" value="Mua hàng không cần đăng ký" />
						<script type="text/javascript">$("#buy_not_reg").live("click", function() {document.location.href = "/thanh-toan.html?CHECKOUT_NOT_REG";return false;});</script>' : '') . '</form>
					</div>
					<div class="bottom">Chưa có tài khoản ?<a href="/dang-ky.html" class="already" title="Đăng ký"> Đăng ký tại đây</a></div>
				  </div>
				  <div class="showdata corner_5" id="showdata" onclick="$(this).css(\'display\', \'none\')">
					<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
					<ol>
					  <li><label for="email" class="error">Email bắt buộc và phải đúng định dạng.</label></li>
					  <li><label for="password" class="error">Mật khẩu bắt buộc nhập.</label></li>
					</ol>
					<a class="close" href="javascript:void(0);" onclick="$(\'div.showdata\').fadeOut(300).hide(1)"></a>
				  </div>
				  <script type="text/javascript"> var REDIRECT_URL = \'' . (isset($_GET['PROCEED_TO_CHECKOUT']) ? '/thanh-toan.html' : $d->redirect) . '\'; </script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'JS.signin.js"></script>
				</div>';
                break;
            case 'forgotpwd' :
                $html = '<div id="forgotpwd">
				  <div class="forgotpwd-box">
					<div class="top">
					  <form id="frmForgotpwd" name="frmForgotpwd" action="/xu-ly-quen-mat-khau" method="post" enctype="application/x-www-form-urlencoded">
						<h1>Quên mật khẩu</h1>
						<p>Hãy nhập địa chỉ email của Bạn vào đây để khôi phục mật khẩu.</p>
						<input id="email" name="email" type="text" class="form-control input {validate: {required:true, email:true}}" maxlength="64" autocomplete="off" placeholder="Email" />
						<input id="captcha" name="captcha" type="text" class="form-control captcha {validate: {required:true, remote:\'/captcha-process\'}}" maxlength="6" autocomplete="off" placeholder="Mã bảo vệ" />
						<span id="captchaimage"><a rel="nofollow" tabindex="-1" href="javascript:void(0);" id="refreshimg" title="Click lên đây để lấy hình khác"> <img  alt="captcha-code" align="absmiddle" src="/captcha-image?' . time() . '" width="100" border="0" /></a></span>
						<input id="send" name="send" type="submit" class="submit button btn btn-lg" value="Gửi yêu cầu" />
					  </form>
					</div>
				  </div>
				  <div class="showdata corner_5" id="showdata" onclick="$(this).css(\'display\',\'none\')">
					<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
					<ol>
					  <li><label for="email" class="error">Email bắt buộc và phải đúng định dạng.</label></li>
					  <li><label for="captcha" class="error">Mã bảo vệ không đúng.</label></li>
					</ol>
					<a class="close" href="javascript:void(0);" onclick="$(\'div.showdata\').fadeOut(300).hide(1)"></a></div>
				  <script type="text/javascript"> var SIGNIN_URL = "' . $d->redirect . '"; </script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'JS.forgotpwd.js"></script>
				</div>';
                break;
            case 'changepwd' :
                $html = '<div id="changepwd">
				  <h1>Đổi mật khẩu</h1>
				  <form id="frmChangePwd" name="frmChangePwd" method="post" action="/xu-ly-doi-mat-khau" enctype="application/x-www-form-urlencoded" >
					<label class="label required" for="old_pwd">Mật khẩu cũ:</label>
					<input id="old_pwd" name="old_pwd" type="password" class="input {validate: {required:true, maxlength:32}}" maxlength="32" autocomplete="off" />
					<br />
					<label class="label required" for="password">Mật khẩu mới:</label>
					<input id="password" name="password" type="password" class="input {validate: {required:true, maxlength:32}}" maxlength="32" autocomplete="off" />
					<br />
					<label class="label required" for="repassword">Nhập lại M.khẩu mới:</label>
					<input id="repassword" name="repassword" type="password" class="input {validate: {required:true, equalTo:\'#password\'}}" maxlength="32" autocomplete="off" />
					<br />
					<input id="change" name="change" type="submit" class="submit button" value="Đồng ý" />
				  </form>
				  <p class="clear"><span class="required">* Thông tin bắt buộc</span></p>
				  <div class="showdata corner_5" id="showdata" onclick="$(this).css(\'display\', \'none\')">
					<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
					<ol>
					  <li><label for="old_pwd" class="error">Mật khẩu cũ bắt buộc nhập.</label></li>
					  <li><label for="password" class="error">Mật khẩu mới bắt buộc nhập.</label></li>
					  <li><label for="repassword" class="error">Mật khẩu mới và Nhập lại M.khẩu mới không khớp.</label></li>
					</ol>
					<a class="close" href="javascript:void(0);" onclick="$(\'div.showdata\').fadeOut(300).hide(1)"></a></div>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'JS.changepwd.js"></script>
				</div>';
                break;
            case 'member' :
                $m = $this->getMember($d, $_LNG);
                $m->city_id = $m->city_id > 0 ? $m->city_id : 51; // 51 ~ TP.HCM
                $m->district_id = $m->district_id > 0 ? $m->district_id : 621; // 621 ~ Q.Go Vap
                $m->ward_id = $m->ward_id > 0 ? $m->ward_id : 10072; // 621 ~ P.14
                $m->salutation = strtolower($m->salutation);
                $s = (array) $_LNG->member->salutation->list;
                $html = '<div id="member">
				  <h1>Thông tin thành viên <span id="modify">[Thay đổi thông tin]</span></h1>
				  <form id="frm" name="frm" method="post" action="/xu-ly-thong-tin-thanh-vien" enctype="application/x-www-form-urlencoded" >
					<label class="label required" for="salutation">Danh xưng:</label>
					<input id="salutation_name" name="salutation_name" type="text" class="input none" value="' . $s[$m->salutation] . '" maxlength="6" autocomplete="off" readonly="readonly" />
					<select name="salutation" id="salutation" class="select hide {validate: {required:true}}">';
                foreach ($s as $a => $b) {
                    $html .= '<option value="' . $a . '"' . ($a == $m->salutation ? ' selected' : '') . '>' . $b . '</option>';
                }
                $html .= '</select>
					<br />
					<label class="label required" for="name">Họ và tên:</label>
					<input id="name" name="name" type="text" class="input none {validate: {required:true}}" value="' . $m->name . '" maxlength="64" autocomplete="off" readonly="readonly" />
					<br />
					<label class="label" for="birthdate">Ngày sinh:</label>
					<input id="birthdate" name="birthdate" type="text" class="input none" value="' . $m->birthdate . '" maxlength="10" autocomplete="off" readonly="readonly" />
					<span class="required hide"> (Ngày/Tháng/Năm)</span><br />
					<label class="label" for="phone">Điện thoại:</label>
					<input id="phone" name="phone" type="text" class="input none {validate: {required:false, number:true, rangelength:[6,12]}}" value="' . $m->phone . '" autocomplete="off" readonly="readonly" />
					<br />
					<label class="label required" for="mobile">Di động:</label>
					<input id="mobile" name="mobile" type="text" class="input none {validate: {required:true, number:true, rangelength:[6,12]}}" value="' . $m->mobile . '" autocomplete="off" readonly="readonly" />
					<br />
					<label class="label required" for="country">Quốc gia:</label>
					<input id="country_name" name="country_name" type="text" class="input none" value="' . $m->country_name . '" autocomplete="off" readonly="readonly" />
					<select id="country" name="country" class="select hide {validate: {required:true}}">
					  <option value="233" selected="selected">Việt Nam</option>
					</select>
					<br />
					<label class="label required" for="city">Tỉnh/Thành phố:</label>
					<input id="city_name" name="city_name" type="text" class="input none" value="' . $m->city_name . '" autocomplete="off" readonly="readonly" />' . $this->buildLocation('city', 0, $m->city_id) . '
					<br />
					<label class="label required" for="district">Quận/Huyện:</label>
					<input id="district_name" name="district_name" type="text" class="input none" value="' . $m->district_name . '" autocomplete="off" readonly="readonly" />
					<span id="district">' . $this->buildLocation('district', $m->city_id, $m->district_id) . '</span><br />
					<label class="label required" for="ward">Phường/Xã:</label>
					<input id="ward_name" name="ward_name" type="text" class="input none" value="' . $m->ward_name . '" autocomplete="off" readonly="readonly" />
					<span id="ward">' . $this->buildLocation('ward', $m->district_id, $m->ward_id) . '</span><br />
					<label class="label required" for="address">Địa chỉ:</label>
					<input id="address" name="address" type="text" class="input none expand {validate: {required:true}}" value="' . $m->address . '" autocomplete="off" readonly="readonly" />
					<span class="required hide"> (Số nhà, Đường, Phường / Xã)</span><br />
					<span class="hide">
					<input id="change" name="change" type="submit" class="submit hide button" value="Đồng ý" />
					hoặc <span class="cancel hide">[Hủy bỏ]</span></span>
				  </form>
				  <p class="clear"><span class="required hide">* Thông tin bắt buộc</span></p>
				  <div class="showdata corner_5" id="showdata" onclick="$(this).css(\'display\', \'none\')">
					<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
					<ol>
					  <li><label for="salutation" class="error">Danh xưng bắt buộc chọn.</label></li>
					  <li><label for="name" class="error">Họ và tên bắt buộc nhập.</label></li>
					  <li><label for="phone" class="error">Điện thoại phải là số.</label></li>
					  <li><label for="mobile" class="error">Di động phải là số.</label></li>
					  <li><label for="country" class="error">Chọn Quốc gia.</label></li>
					  <li><label for="city" class="error">Chọn Tỉnh/Thành phố.</label></li>
					  <li><label for="district" class="error">Chọn Quận/Huyện.</label></li>
					  <li><label for="district" class="error">Chọn Phường/Xã.</label></li>
					  <li><label for="address" class="error">Địa chỉ bắt buộc nhập.</label></li>
					</ol>
					<a class="close" href="javascript:void(0);" onclick="$(\'div.showdata\').fadeOut(300).hide(1)"></a></div>
				  <script type="text/javascript"> var FROM_YEAR = \'' . (date('Y') - 100) . '\'; var TO_YEAR = \'' . (date('Y')) . '\'; </script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
				  <script type="text/javascript" src="' . JS_PATH . 'JS.member.js"></script>
				</div>';
                break;
        }
        return $html;
    }

    function buildUserCP($d, $cart, $_LNG, $html = '') {
        $html = '<ul id="usercp">';
        if (MEMBER_SYS) {
            if (isset($_SESSION['member']) && count($_SESSION['member']) > 0) {
                $html .= '<li class="member iconCustomer"><a class="customer" href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->member->rewrite . EXT) . '" title="' . $_SESSION['member']['name'] . '">' . $_SESSION['member']['name'] . '</a>
				  <ul class="menu">
				    <li class="iconCustomer"><a class="customer" href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->member->rewrite . EXT) . '" title="' . $_SESSION['member']['name'] . '">' . $_SESSION['member']['name'] . '</a></li>
					<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->member->rewrite . EXT) . '" title="' . $_LNG->panel->member->title . '">' . $_LNG->panel->member->title . '</a></li>
					<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->order->rewrite . EXT) . '" title="' . $_LNG->panel->order->title . '">' . $_LNG->panel->order->title . '</a></li>
					<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->changepwd->rewrite . EXT) . '" title="' . $_LNG->panel->changepwd->title . '">' . $_LNG->panel->changepwd->title . '</a></li>
					<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'danh-sach-yeu-thich' . EXT) . '" title="Danh sách yêu thích">Danh sách yêu thích</a></li>' . (POINT_REWARD ? '<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'quan-ly-diem-tang' . EXT) . '" title="Quản lý điểm tặng">Quản lý điểm tặng</a></li>' : '') . '<li><a href="javascript:void(0)" onclick="Signout(\'/dang-xuat\');" title="' . $_LNG->panel->signout->title . '">' . $_LNG->panel->signout->title . '</a></li>
				  </ul>
				  <script type="text/javascript"> function Signout(processPage) { if(confirm("' . $_LNG->panel->signout->confirm . '")) { jQuery.get(processPage, "", function(data, status) { if(data == "success") { window.location = "/"; return; } }); } return false; } </script>
				</li>';
            }
            else {
                $html .= '<li><a href="/dang-ky.html" title="Đăng ký">Đăng ký</a></li>
				<li><a href="/dang-nhap.html" title="Đăng nhập">Đăng nhập</a></li>';
            }
        }
        $html .= #'<li id="top-cart"><a href="/vi/gio-hang.html" title="Giỏ hàng"><i class="fa fa-shopping-cart"></i><p style="display: none;">Giỏ hàng</p></a></li>' .
            (CART_SYS ? $this->buildMiniCart($d->route, $cart, $_LNG, $d->code2, $d->code) : '') . '</ul>';
        return $html;
    }

    function buildUserCP2($d, $_LNG, $html = '') {
        if (isset($_SESSION['member']) && count($_SESSION['member']) > 0) {
            $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#" data-title="Login"><i class="icon-user icn-left visible-sm-inline"></i><span class="hidden-sm"> Tài khoản </span><i class="icon-caret-down m-marker "></i></a>
			<div class="dropdown-menu-container">
			  <ul class="dropdown-menu no-border-radius col-lg-3 col-md-4 col-sm-4">
			    <li class="iconCustomer"><a class="customer" href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->member->rewrite . EXT) . '" title="' . $_SESSION['member']['name'] . '">' . $_SESSION['member']['name'] . '</a></li>
				<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->member->rewrite . EXT) . '" title="' . $_LNG->panel->member->title . '">' . $_LNG->panel->member->title . '</a></li>
				<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->order->rewrite . EXT) . '" title="' . $_LNG->panel->order->title . '">' . $_LNG->panel->order->title . '</a></li>
				<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->panel->changepwd->rewrite . EXT) . '" title="' . $_LNG->panel->changepwd->title . '">' . $_LNG->panel->changepwd->title . '</a></li>
				<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'danh-sach-yeu-thich' . EXT) . '" title="Danh sách yêu thích">Danh sách yêu thích</a></li>' . (POINT_REWARD ? '<li><a href="' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'quan-ly-diem-tang' . EXT) . '" title="Quản lý điểm tặng">Quản lý điểm tặng</a></li>' : '') . '<li><a href="javascript:void(0)" onclick="Signout(\'/dang-xuat\');" title="' . $_LNG->panel->signout->title . '">' . $_LNG->panel->signout->title . '</a></li>
			  </ul>
			</div>';
        }
        else {
            $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#" data-title="Login"><i class="icon-user icn-left visible-sm-inline"></i><span class="hidden-sm"> Đăng nhập </span><i class="icon-caret-down m-marker "></i></a>
			<div class="dropdown-menu-container">
			  <ul class="dropdown-menu no-border-radius col-lg-3 col-md-4 col-sm-4">
				<li>
				  <div>
					<form role="form">
					  <div class="form-group pad-top-1">
						<label for="log-email">Email</label>
						<input type="text" class="form-control" id="email" name="email" placeholder="Email">
					  </div>
					  <div class="form-group">
						<label for="inputPassword3" class="control-label"> Mật khẩu </label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
					  </div>
					  <div class="form-group">
						<button class="btn btn-primary btn-mina btn-mina-rip-m" name="login" type="button"> Đăng nhập </button>
					  </div>							  
					</form>
					<p class="text-info"><a href="/quen-mat-khau.html"> Quên mật khẩu. </a></p>
					<hr>
					<h4><strong> Chưa có tài khoản? </strong></h4>
					<button onclick="window.location.href=\'/dang-ky.html\'" class="btn btn-amber btn-mina btn-mina-rip-m"> Đăng ký</button>
				  </div>
				</li>
			  </ul>
			</div>';
        }
        return $html;
    }

    function buildHomeProduct($d, $_LNG, $html = '') {
        $d->route->position = 'new';
        $html = '<section class="pro-home box-line">
		  <div class="tab" role="tabpanel">
			<ul class="nav nav-tabs row" role="tablist">
			  <li role="presentation" class="active"><a rel="nofollow" href="#san-pham-moi" aria-controls="home" role="tab" data-toggle="tab">Sản phẩm mới</a></li>
			  <li role="presentation"><a rel="nofollow" href="#san-pham-ban-chay" aria-controls="profile" role="tab" data-toggle="tab">Bán chạy</a></li>
			</ul>
			<div class="tab-content">
			  <div role="tabpanel" class="tab-pane fade in active san_pham_moi" id="san-pham-moi">
				<section class="products">' . //$this->getTextlink('topnewhome') .
            '<div class="text-link text-link-home">' . $this->getTextlink('topnewhome') . '</div>' . '<div id="0_NEW_HOME_PAGE_0" class="content">' . $this->buildNewProduct($d, $_LNG) . '</div>' . //$this->getTextlink('botnewhome') .
            '<div class="text-link text-link-home">' . $this->getTextlink('botnewhome') . '</div>' . '</section>' . $this->buildScrollingScript($d) . '</div>
			  <div role="tabpanel" class="tab-pane fade" id="san-pham-ban-chay">
				<section class="products">
				  <div id="0_HOT_HOME_PAGE_0" class="content">' . $this->buildHotProduct($d, $_LNG) . '</div>
				</section>
			  </div>
			</div>
		  </div>
		</section>';
        return $html;
    }

    function buildProductOption($d, $html = '') {
        if (isset($d->option) && !empty($d->option) && is_array($d->option) && count($d->option) > 0) {
            foreach ($d->option as $k => $v) {
                if (isset($v['oid']) && !empty($v['oid']) && isset($v['id']) && !empty($v['id'])) {
                    $d->joid = $v['oid'];
                    $d->jid = $v['id'];
                    $a = $this->getProductOption($d);
                    if (isset($a[0]['catalog']) && isset($a[0]['child'][0])) {
                        $b = $a[0]['catalog'];
                        $c = $a[0]['child'][0];
                        //$html .= '<div class="label">' . $b->name . ': <span>' . $c->name . '</span></div>';
                        $html .= '<li class="label">' . $b->name . ': <span>' . $c->name . '</span></li>';
                    }
                }
            }
        }
        return $html;
    }

    function buildProductOptionFilter($d, $html = '') {
        #echo $d->route->name;
        if (in_array($d->route->name, ['allproduct', 'category', 'search'])) {
            $a = $this->getProductOption($d);
            if (isset($a) && is_array($a) && count($a)) {
                foreach ($a as $k => $v) {
                    if (isset($v['catalog'])) {
                        $c = $v['catalog'];
                        $html .= '<div id="' . $c->code . '-' . $c->id . '" class="categories mt10">
						  <div class="title beefup__head">' . $c->name . '</div>
						  <ul class="beefup__body">';
                        if (isset($v['child']) && is_array($v['child']) && count($v['child']) > 0) {
                            $p = !in_array($d->route->tmp, [
                                'category',
                                'allproduct',
                                'search',
                            ]) ? TRUE : FALSE;
                            foreach ($v['child'] as $k2 => $v2) {
                                $f = isset($_SESSION['PNSDOTVN_PRODUCT_FILTER'][$c->code]) && str_replace('+', ' ', $_SESSION['PNSDOTVN_PRODUCT_FILTER'][$c->code]) == $v2->name ? TRUE : FALSE;
                                $d->joption = 'opt' . $c->id . '_' . $v2->id;
                                $t = $this->countProduct($d, 'product_option');
                                $html .= '<li class="item" ' . ($f && !$p ? ' class="filter"' : '') . '><a href="' . ($p ? '/san-pham' . EXT : '') . '?' . $c->code . '=' . str_replace(' ', '+', $v2->name) . '" title="' . $v2->name . '">' . $v2->name . '</a>' . ($t > 0 ? ' (<span class="pro-count">' . $t . '</span>)' : '') . ($f && !$p ? '<a title="Xóa bộ lọc" rel="nofollow" class="remove" href="?REMOVE_PRODUCT_' . strtoupper($c->code) . '_FILTER">x</a>' : '') . '</li>';
                            }
                        }
                        $html .= '</ul></div>';
                    }
                }
            }
        }
        return $html;
    }

    function buildFilter($d, $data, $array = '') {
        if (PRODUCT_FILTER && in_array($d->route->name, [
                'category',
                'allproduct',
                'search',
            ])) {
            if ($d->route->name == 'search') {
                if (isset($_POST['keyword']) && !empty($_POST['keyword'])) {
                    $_SESSION['PNSDOTVN_PRODUCT_FILTER']['keyword'] = $this->checkValues($_POST['keyword']);
                }
                if (isset($_POST['standard-dropdown']) && !empty($_POST['standard-dropdown']) && $_POST['standard-dropdown'] != 0) {
                    $_SESSION['PNSDOTVN_PRODUCT_FILTER']['catalog'] = $this->checkValues($_POST['standard-dropdown']);
                }
            }

            $filter = $this->getFilter($d);
            if (is_array($filter) && count($filter) > 0) {
                foreach ($filter as $fil) {
                    $_fil = strtoupper($fil);
                    if (isset($data[$fil]) && $this->checkFilter($d, $data[$fil], $_fil)) {
                        $_SESSION['PNSDOTVN_PRODUCT_FILTER'][$fil] = $data[$fil];
                    }
                    if (isset($data['REMOVE_PRODUCT_' . $_fil . '_FILTER'])) {
                        unset($_SESSION['PNSDOTVN_PRODUCT_FILTER'][$fil]);
                    }
                }
            }

            if (isset($data['updated'])) {
                $_SESSION['PNSDOTVN_PRODUCT_FILTER']['updated'] = $data['updated'];
            }
            if (isset($data['REMOVE_PRODUCT_UPDATED_FILTER'])) {
                unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['updated']);
            }

            if (isset($data['saleoff'])) {
                $_SESSION['PNSDOTVN_PRODUCT_FILTER']['saleoff'] = $data['saleoff'];
            }
            if (isset($data['REMOVE_PRODUCT_SALEOFF_FILTER'])) {
                unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['saleoff']);
            }

            if (isset($data['brand']) && $this->checkFilter($d, $data['brand'], 'BRAND')) {
                $_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand'] = $data['brand'];
            }
            if (isset($data['REMOVE_PRODUCT_BRAND_FILTER'])) {
                unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand']);
            }

            if (isset($data['supplier']) && $this->checkFilter($d, $data['supplier'], 'SUPPLIER')) {
                $_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier'] = $data['supplier'];
            }
            if (isset($data['REMOVE_PRODUCT_SUPPLIER_FILTER'])) {
                unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier']);
            }

            if (isset($data['price']) && !empty($data['price'])) {
                $tmp = explode(';', $data['price']);
                if (!empty($tmp) && count($tmp) == 2 && is_int((int) $tmp[0]) && is_int((int) $tmp[1]) && $tmp[0] <= $tmp[1]) {
                    $_SESSION['PNSDOTVN_PRODUCT_FILTER']['price'] = $data['price'];
                }
            }
            /*
            if (isset($data['price']) && isset($array['price']) && in_array($data['price'], $array['price']))
                $_SESSION['PNSDOTVN_PRODUCT_FILTER']['price'] = $data['price'];
            */
            if (isset($data['REMOVE_PRODUCT_PRICE_FILTER'])) {
                unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['price']);
            }

            if (isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && !isset($_GET['PRODUCT_FILTER'])) {
                if ($d->route->name == 'category') {
                    if (!isset($_SESSION['PNSDOTVN_CATALOG'])) {
                        $_SESSION['PNSDOTVN_CATALOG'] = $d->route->id;
                    }
                    #if ($_SESSION['PNSDOTVN_CATALOG'] != $d->route->id && isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand']))
                    if ($_SESSION['PNSDOTVN_CATALOG'] != $d->route->id) {
                        #unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand']);
                        unset($_SESSION['PNSDOTVN_PRODUCT_FILTER']);
                    }
                    $_SESSION['PNSDOTVN_CATALOG'] = $d->route->id;
                }
                foreach ($_SESSION['PNSDOTVN_PRODUCT_FILTER'] as $k => $v) {
                    if ($k == 'keyword' && $d->route->name != 'search') {
                        $f .= '';
                    }
                    else {
                        $f .= '&' . $k . '=' . str_replace(' ', '+', $v);
                    }
                }
                header('Location: ?' . substr($f, 1) . (isset($_GET['page']) ? '&page=' . (int) $_GET['page'] : '') . '&PRODUCT_FILTER');
                exit();
            }
        }
    }

    function buildBarPrice($route, $html = '') {
        $f = PRODUCT_MIN_PRICE_DEFAULT;
        $t = PRODUCT_MAX_PRICE_DEFAULT;
        $p = in_array($route->name, [
            'category',
            'allproduct',
            'search',
        ]) ? TRUE : FALSE;
        if (isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['price']) && !$p) {
            $tmp = explode(';', $_SESSION['PNSDOTVN_PRODUCT_FILTER']['price']);
            $f = $tmp[0];
            $t = $tmp[1];
        }
        $html = '<div class="search-price mt10">' . '<div class="title">Tìm theo giá</div>' . '<link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'ion.rangeSlider.css" media="all" />' . '<script src="' . JS_PATH . 'ion.rangeSlider.js"></script>' . '<script>
		  $(function () { 
		  	$("#range").ionRangeSlider({ 
			  hide_min_max: true, 
			  keyboard: true, 
			  min: ' . PRODUCT_MIN_PRICE . ', 
			  max: ' . PRODUCT_MAX_PRICE . ', 
			  from: ' . $f . ', 
			  to: ' . $t . ', 
			  type: "double", 
			  step: ' . PRODUCT_STEP_PRICE . ', 
			  postfix: " đ", 
			  grid: false }); 
		  }); 
		  $(document).ready(function() { 
		  	$("input.price_search_button").live("click", function() { 
			  var price = $("input#range").val(); 
			  document.location.href = "?price=" + price; }); 
			  $("input.cancel_button").live("click", function() { 
			    document.location.href = "?REMOVE_PRODUCT_PRICE_FILTER"; 
			  }); 
		  });
		  </script>' . '<form method="POST" action="">' . '<p style="margin-bottom:5px;">' . '<input type="text" id="range" value="" name="range" />' . '<input type="button" class="price_search_button" value="Tìm" />' . '<input type="button" class="cancel_button" value="Bỏ tìm" />' . '</p>' . '</form>' . '</div>';
        return $html;
    }

    function buildCheckout($d, $_LNG, $html = '') {
        if (isset($_GET['STEP']) && in_array($_GET['STEP'], [
                'SIGNIN',
                'SIGNUP',
                'CUSTOMER_INFO',
                'CONFIRM_ORDER',
            ])) {
            $step = $_GET['STEP'];
            switch ($step) {
                case 'CONFIRM_ORDER' :
                    #$this->printr($_POST, true);
                    if (isset($_POST) && !empty($_POST) && isset($_POST['PNSDOTVN_STEP']) && $_POST['PNSDOTVN_STEP'] == 'CUSTOMER_INFO') {
                        $p = $_POST;
                        if (!isset($p['billing_name']) || empty($p['billing_name']) || !isset($p['billing_mobile']) || empty($p['billing_mobile']) || !isset($p['billing_city']) || empty($p['billing_city']) || !isset($p['billing_district']) || empty($p['billing_district']) || !isset($p['billing_ward']) || empty($p['billing_ward']) || !isset($p['billing_address']) || empty($p['billing_address']) || (isset($_GET['CHECKOUT_NOT_REG']) && (!isset($p['billing_email']) || empty($p['billing_email'])))) {
                            header('Location: /thanh-toan.html?STEP=CUSTOMER_INFO');
                            exit;
                        }
                        unset($_POST['order'], $_POST['CHECKOUT_NOT_REG'], $_POST['PNSDOTVN_STEP']);
                        $_SESSION['PNSDOTVN_BILLING'] = serialize($_POST);
                        unset($_POST);
                        header('Location: /thanh-toan.html?STEP=CONFIRM_ORDER');
                        exit;
                    }
                    #$this->printr($_SESSION);
                    if (!isset($_SESSION['PNSDOTVN_BILLING'])) {
                        header('Location: /thanh-toan.html?STEP=SIGNIN');
                        exit;
                    }
                    $billing = unserialize($_SESSION['PNSDOTVN_BILLING']);
                    #$this->printr($billing);
                    $ward = (isset($billing['billing_ward']) ? $this->getLocation('ward', $billing['billing_district'], $billing['billing_ward']) : '');
                    $district = (isset($billing['billing_district']) ? $this->getLocation('district', $billing['billing_city'], $billing['billing_district']) : '');
                    $city = (isset($billing['billing_city']) ? $this->getLocation('city', 0, $billing['billing_city']) : '');
                    $a = $this->getCart($d->code, $_LNG);
                    $html = '<div id="content" class="col-md-12">
					<link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_style.css" />
					<link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_layout.css" />
					<link rel="stylesheet" href="' . CSS_PATH . 'fontello.css" />  
					<!--[if lt IE 9]>
					<script src="' . JS_PATH . 'respond.min.js"></script>
					<![endif]-->
					<div class="form-container">
					  <div id="tmm-form-wizard" class="container substrate">
						<div class="row">
						  <div class="col-xs-12">
							<h2 class="form-login-heading"><a href="https://chauruachen.pns.vn" rel="nofollow">Trang chủ</a> > Các bước <span>đặt hàng</span></h2>
						  </div>
						</div>
						<div class="row stage-container">
						  <div class="stage tmm-success col-md-4 col-sm-4">
							<div class="stage-header head-number">1</div>
							<div class="stage-content">
							  <h3 class="stage-title">Đăng ký/Đăng nhập</h3>
							</div>
						  </div>
						  <div class="stage tmm-success col-md-4 col-sm-4">
							<div class="stage-header head-number">2</div>
							<div class="stage-content">
							  <h3 class="stage-title">Thông tin tài khoản</h3>
							</div>
						  </div>
						  <div class="stage tmm-current col-md-4 col-sm-4">
							<div class="stage-header head-number">3</div>
							<div class="stage-content">
							  <h3 class="stage-title">Xác nhận đặt hàng</h3>
							</div>
						  </div>
						</div>
						<div class="row">
						  <div class="col-xs-12">
							<div class="form-header">
							  <div class="form-title form-icon title-icon-payment"> <b>Xác nhận</b> đặt hàng </div>
							  <div class="steps"> Bước 3 - 3 </div>
							</div>
						  </div>
						</div>
						<form id="frmCheckout" name="frmCheckout" method="post" action="/xu-ly-dat-hang" enctype="application/x-www-form-urlencoded" role="form">
						  <div class="form-wizard">
							<div class="row">
							  <div class="col-md-8 col-sm-7">
								<div class="data-container">
								  <div class="title">Địa chỉ nhận hàng:</div>
								  <dl>
									<dt>Họ & tên:</dt>
									<dd class="b">' . (isset($billing['billing_name']) ? $billing['billing_name'] : '') . '</dd>
								  </dl>
								  <dl>
									<dt>Địa chỉ:</dt>
									<dd>' . (isset($billing['billing_address']) ? $billing['billing_address'] . ', ' : '') . (isset($ward[0]->name) ? $ward[0]->name . ', ' : '') . (isset($district[0]->name) ? $district[0]->name . ', ' : '') . (isset($city[0]->name) ? $city[0]->name : '') . '</dd>
								  </dl>
								  <dl>
									<dt>Điện thoại:</dt>
									<dd>' . (isset($billing['billing_phone']) ? $billing['billing_phone'] : '') . '</dd>
								  </dl>
								  <dl>
									<dt>Di động:</dt>
									<dd>' . (isset($billing['billing_mobile']) ? $billing['billing_mobile'] : '') . '</dd>
								  </dl>' . (!isset($_SESSION['member']['id']) && isset($billing['billing_email']) && isset($billing['CHECKOUT_NOT_REG']) ? '<dl>
									<dt>Email:</dt>
									<dd>' . $billing['billing_email'] . '</dd>
								  </dl>' : '') . '<dl>
									<dt>Ghi chú:</dt>
									<dd>
									  <textarea id="order_note" name="order_note" class="tex {validate:{required:false, maxlength:500}}" tabindex="24"></textarea>
									</dd>
								  </dl>
								  <div class="title clear">Địa chỉ nhận hàng khác:
									<input type="checkbox" id="chkNotSame" name="chkNotSame" value="chkNotSame" />
								  </div>
								  <div class="group-box other-delivery-add">
									<div class="row">
									  <div class="col-md-4 col-sm-4">
										<fieldset class="input-block">
										  <label>Chức danh</label>
										  <div class="dropdown">
											<select name="shipping_salution" class="dropdown-select">
											  <option value="1">Ông</option>
											  <option value="2">Bà</option>
											  <option value="3">Cô</option>
											</select>
										  </div>
										</fieldset>
									  </div>
									  <div class="col-md-6 col-sm-6">
										<fieldset class="input-block">
										  <label for="shipping_name">Họ và tên</label>
										  <input id="shipping_name" name="shipping_name" type="text" class="{validate:{required:true}}" maxlength="64" autocomplete="off" value="' . (isset($billing['billing_name']) ? $billing['billing_name'] : (isset($m->name) ? $m->name : (isset($_GET['TEST_MODE'])) ? 'Phuong Nam Solution' : '')) . '" placeholder="Họ và tên" />
										  <div class="tooltip">
											<p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để giao hàng, lưu đơn hàng</p>
											<span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
										</fieldset>
									  </div>
									</div>									
									<div class="row">
									  <div class="col-md-6 col-sm-6">
										<fieldset class="input-block">
										  <label for="shipping_phone">Số điện thoại</label>
										  <input id="shipping_phone" name="shipping_phone" type="text" class="form-icon form-icon-phone {validate:{required:false, number:true, rangelength:[6,12]}}" maxlength="12" autocomplete="off" value="' . (isset($billing['billing_phone']) ? $billing['billing_phone'] : (isset($m->phone) ? $m->phone : (isset($_GET['TEST_MODE']) ? '0826919777' : ''))) . '" placeholder="Số điện thoại" />
										  <div class="tooltip">
											<p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để chúng tôi xác nhận đơn hàng và giao hàng.</p>
											<span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
										</fieldset>
									  </div>
									  <div class="col-md-6 col-sm-6">
										<fieldset class="input-block">
										  <label for="shipping_mobile">Số điện thoại di động</label>
										  <input id="shipping_mobile" name="shipping_mobile" type="text" class="form-icon form-icon-phone {validate:{required:true, number:true, rangelength:[6,12]}}" maxlength="12" autocomplete="off" value="' . (isset($billing['billing_mobile']) ? $billing['billing_mobile'] : (isset($m->mobile) ? $m->mobile : (isset($_GET['TEST_MODE']) ? '0935777527' : ''))) . '" placeholder="Số điện thoại di động" />
										  <div class="tooltip">
											<p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để chúng tôi xác nhận đơn hàng và giao hàng.</p>
											<span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
										</fieldset>
									  </div>
									</div>
									<div class="row">
									  <div class="col-md-4 col-sm-4">
										<fieldset class="input-block">
										  <label for="shipping_city">Tỉnh/Thành</label>
										  <div class="dropdown">' . '<span id="shipping_city">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 1 and pid = 0', 'id', 'shipping_city', 'name', 'id', (isset($billing['billing_city']) ? $billing['billing_city'] : (isset($m->city_id) ? $m->city_id : 51)), ['class' => 'dropdown-select sel short city {validate:{required:true}} cus']) . '</span>' . '</div>
										</fieldset>
									  </div>
									  <div class="col-md-4 col-sm-4">
										<fieldset class="input-block">
										  <label for="shipping_district">Quận/Huyện</label>
										  <div class="dropdown">
											<span id="shipping_district">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 2 and pid = ' . (isset($billing['billing_city']) ? $billing['billing_city'] : (isset($m->city_id) ? $m->city_id : 51)), 'name', 'shipping_district', 'name', 'id', (isset($billing['billing_district']) ? $billing['billing_district'] : (isset($m->district_id) ? $m->district_id : 621)), [
                            'firstText' => $_LNG->member->errors->district,
                            'class' => 'dropdown-select sel short district {validate:{required:true}} cus',
                        ]) . '</span>										  
										  </div>
										</fieldset>
									  </div>
									  <div class="col-md-4 col-sm-4">
										<fieldset class="input-block">
										  <label for="shipping_ward">Phường/Xã</label>
										  <div class="dropdown">
											<span id="shipping_ward">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 3 and pid = ' . (isset($billing['billing_district']) ? $billing['billing_district'] : (isset($m->district_id) ? $m->district_id : 621)), 'name', 'shipping_ward', 'name', 'id', (isset($billing['billing_ward']) ? $billing['billing_ward'] : (isset($m->ward_id) ? $m->ward_id : 10072)), [
                            'firstText' => $_LNG->member->errors->ward,
                            'class' => 'dropdown-select sel short {validate:{required:true}} cus',
                        ]) . '</span>
										  </div>	
										</fieldset>
									  </div>
									</div>
									<div class="row">
									  <div class="col-md-12 col-sm-12">
										<fieldset class="input-block">
										  <label for="shipping_address">Địa chỉ</label>
										  <input id="shipping_address" name="shipping_address" type="text" class="{validate:{required:true}}" maxlength="200" autocomplete="off" value="' . (isset($billing['billing_address']) ? $billing['billing_address'] : (isset($m->address) ? $m->address : (isset($_GET['TEST_MODE']) ? '205/11/13 Phạm Văn Chiêu' : ''))) . '" placeholder="Địa chỉ" />
										  <div class="tooltip">
											<p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để chúng tôi giao hàng.</p>
											<span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
										</fieldset>
									  </div>
									</div>
								  </div>
								  <div class="title clear">Hình thức thanh toán:</div>
								  <div class="group-box">
									<ul>
									  <li>
										<input type="radio" name="payment_method" id="payment_method1" value="1" tabindex="19" checked="checked">
										&nbsp;
										<label for="payment_method1" class="payment">Thanh toán sau khi nhận hàng.</label>
									  </li>
									  <li>
										<input type="radio" name="payment_method" id="payment_method2" value="2" tabindex="20">
										&nbsp;
										<label for="payment_method2" class="payment">Chuyển khoản qua tài khoản ngân hàng hoặc ATM.</label>
										<div id="bankinfo">
										  <p>Nội dung đang được cập nhật</p>
										</div>
									  </li>
									</ul>
								  </div>' . (isset($_SESSION['member']['id']) ? '<div class="title clear">Giảm giá bằng điểm tặng:</div>
								  <div class="group-box">' . $this->buildPoint2Money($d, $_LNG) . '</div>' : '') . '</div>
							  </div>
							  <div class="col-md-4 col-sm-4">
								<div class="data-container">
								  <div class="title">Đơn hàng<span class="fr"><a href="/gio-hang.html" title="Chỉnh sửa giỏ hàng">Chỉnh sửa</a></span></div>';
                    foreach ($a['list'] as $k => $v) {
                        $html .= '<div class="group-box" style="float:left">
									  <div class="picture">
										<div class="thumbnail"><img alt="' . $v->rewrite . '" src="/thumb/300x300/1:1' . $v->src . '" title="' . $v->title . '" width="84" /></div>
									  </div>
									  <div class="content">
										<div class="pro-name">' . $v->name . '</div>
										<div class="item-price">
										  <ul>
											<li>' . $v->price_txt . '</li>
											<li>x' . $v->qty . '</li>
											<li>' . $v->amount_txt . '</li>
										  </ul>
										</div>
									  </div>
									</div>';
                    }
                    $html .= '<hr class="clear mt10">
								  <!--<div class="group-box mt10">
									<div class="total-amount">Tổng tiền: <span class="fr"></span></div>
								  </div>-->
								  <!--<div class="group-box mt10">
									<div class="total-delivery">Phí vận chuyển: <span class="fr"></span></div>
								  </div>-->' . (isset($_SESSION['member']['id']) ? '<div class="group-box mt10">
									<div class="total-delivery">Giảm giá với điểm tặng: <span class="fr" id="pns_minus">0 đ</span></div>
								  </div>' : '') . '<div class="group-box mt10">
								    <div id="pns_total" style="display:none">' . $a['total'] . '</div>
									<div class="total-purchase">Tổng tiền thanh toán: <span class="fr b c-red" id="total">' . $a['total_txt'] . '</span></div>
								  </div>
								</div>
							  </div>
							</div>
						  </div>
						  <div class="prev">
							<button class="button button-control" type="button" onclick="window.location.href=\'/thanh-toan.html?STEP=CUSTOMER_INFO' . (!isset($_SESSION['member']['id']) ? '&CHECKOUT_NOT_REG' : '') . '\'"><span>Trở lại <b>Thông tin mua hàng</b></span></button>						
							<div class="button-divider"></div>
						  </div>
						  <div class="next">
							<button class="button button-control" id="order" name="order" type="submit"><span>Thực hiện <b>Đặt hàng</b></span></button>
							<div class="button-divider"></div>
						  </div>';
                    foreach ($billing as $b => $c) {
                        $html .= '<input type="hidden" id="' . $b . '" name="' . $b . '" value="' . $c . '" />';
                    }
                    $html .= '<input type="hidden" id="PNSDOTVN_STEP" name="PNSDOTVN_STEP" value="CONFIRM_ORDER" />
						</form>
						<div class="showdata corner_5" id="showdata" onclick="jQuery(this).css("display", "none")">
						  <h4>' . $_LNG->contact->errors->note . '</h4>
						  <ol>
							<li><label for="shipping_name" class="error">' . $_LNG->checkout->errors->shipping_name . '</label></li>
							<li><label for="shipping_phone" class="error">' . $_LNG->checkout->errors->shipping_phone . '</label></li>
							<li><label for="shipping_mobile" class="error">' . $_LNG->checkout->errors->shipping_mobile . '</label></li>' . #'<li><label for="shipping_country" class="error">' . $_LNG->checkout->errors->shipping_country . '</label></li>' .
                        '<li><label for="shipping_city" class="error">' . $_LNG->checkout->errors->shipping_city . '</label></li>
							<li><label for="shipping_district" class="error">' . $_LNG->checkout->errors->shipping_district . '</label></li>
							<li><label for="shipping_ward" class="error">' . $_LNG->checkout->errors->shipping_ward . '</label></li>
							<li><label for="shipping_address" class="error">' . $_LNG->checkout->errors->shipping_address . '</label></li>							  
						  </ol>
						  <a class="close" href="javascript:void(0);" onclick="jQuery("div.showdata").fadeOut(300).hide(1)"></a>
						</div>	
						<script type="text/javascript"> 
						var REDIRECT_URL = "' . ((MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->others->finished->rewrite . EXT) . '"; 
						var CART_URL = "' . ((MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->cart->rewrite . EXT) . '"; 
						var RETURN_URL = "' . ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : DS) . '"; 
						</script>					  
						<script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
						<script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
						<script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
						<script type="text/javascript" src="' . JS_PATH . 'JS.checkout.js"></script>
					  </div>
					</div>
					<script src="' . JS_PATH . 'tmm_form_wizard_custom.js"></script> 
					<script>
					jQuery(document).ready(function(){
						$(\'.form-wizard input[type="checkbox"]\').click(function(){
							if($(this).attr("name")=="chkNotSame"){
								$(".other-delivery-add").toggle();
							}						
							else
						    $(".other-delivery-add").hide();
						});
						$(\'.form-wizard input[type="radio"]\').click(function(){						
							if($(this).attr("name")=="payment_method"){
								$("#bankinfo").toggle();
							}
							else
						  $("#bankinfo").hide();
						});
					});
					</script> 
				  </div>';
                    break;
                case 'CUSTOMER_INFO' :
                    if (MEMBER_SYS && !isset($_GET['CHECKOUT_NOT_REG'])) {
                        $m = $this->getMember($d, $_LNG);
                    }
                    if (isset($_SESSION['PNSDOTVN_BILLING'])) {
                        $billing = unserialize($_SESSION['PNSDOTVN_BILLING']);
                    }
                    $html = '<div id="content" class="col-md-12">
					  <link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_style.css" />
					  <link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_layout.css" />
					  <link rel="stylesheet" href="' . CSS_PATH . 'fontello.css" />
					  <script src="' . JS_PATH . 'tmm_form_wizard_custom.js"></script> 
					  <!--[if lt IE 9]>
					  <script src="' . JS_PATH . 'respond.min.js"></script>
					  <![endif]-->
					  <div class="form-container">
						<div id="tmm-form-wizard" class="container substrate">
						  <div class="row">
							<div class="col-xs-12">
							  <h2 class="form-login-heading"><a href="https://chauruachen.pns.vn" rel="nofollow">Trang chủ</a> > Các bước <span>đặt hàng</span></h2>
							</div>
						  </div>
						  <div class="row stage-container">
							<div class="stage tmm-success col-md-4 col-sm-4">
							  <div class="stage-header head-number">1</div>
							  <div class="stage-content">
								<h3 class="stage-title">Đăng ký/Đăng nhập</h3>
							  </div>
							</div>
							<div class="stage tmm-current col-md-4 col-sm-4">
							  <div class="stage-header head-number">2</div>
							  <div class="stage-content">
								<h3 class="stage-title">Thông tin tài khoản</h3>
							  </div>
							</div>
							<div class="stage col-md-4 col-sm-4">
							  <div class="stage-header head-number">3</div>
							  <div class="stage-content">
								<h3 class="stage-title">Xác nhận đặt hàng</h3>
							  </div>
							</div>
						  </div>
						  <div class="row">
							<div class="col-xs-12">
							  <div class="form-header">
								<div class="form-title form-icon title-icon-user"> <b>Thông tin</b> tài khoản</div>
								<div class="steps"> Bước 2 - 3 </div>
							  </div>
							</div>
						  </div>
						  <form id="frmCheckout" name="frmCheckout" method="post" action="/thanh-toan.html?STEP=CONFIRM_ORDER" enctype="application/x-www-form-urlencoded" role="form">
							<div class="form-wizard">
							  <div class="row">
								<div class="col-md-8 col-sm-7">
								  <div class="row">
									<div class="col-md-4 col-sm-4">
									  <fieldset class="input-block">
										<label for="billing_salution">Chức danh</label>
										<div class="dropdown">
										  <select name="billing_salution" class="dropdown-select">
											<option value="1">Ông</option>
											<option value="2">Bà</option>
											<option value="3">Cô</option>
										  </select>
										</div>
									  </fieldset>
									</div>
									<div class="col-md-6 col-sm-6">
									  <fieldset class="input-block">
										<label for="billing_name">Họ và tên</label>
										<input id="billing_name" name="billing_name" type="text" class="{validate:{required:true}}" maxlength="64" autocomplete="off" value="' . (isset($billing['billing_name']) ? $billing['billing_name'] : (isset($m->name) ? $m->name : (isset($_GET['TEST_MODE'])) ? 'Phuong Nam Solution' : '')) . '" placeholder="Họ và tên" required />
										<div class="tooltip">
										  <p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để giao hàng, lưu đơn hàng</p>
										  <span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
									  </fieldset>
									</div>
								  </div>' . (!isset($m->id) && isset($_GET['CHECKOUT_NOT_REG']) ? '<div class="row">
									<div class="col-md-12 col-sm-12">
									  <fieldset class="input-block">
										<label for="billing_email">Email</label>
										<input id="billing_email" name="billing_email" type="text" class="form-icon form-icon-mail {validate:{required:true,email:true}}" maxlength="64" autocomplete="off" value="' . (isset($billing['billing_email']) ? $billing['billing_email'] : (isset($_GET['TEST_MODE']) ? 'info@pns.vn' : '')) . '" placeholder="Địa chỉ E-Mail" required />
										<div class="tooltip">
										  <p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để xác nhận đơn hàng.</p>
										  <span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
									  </fieldset>
									</div>
								  </div>' : '') . '<div class="row">
									<div class="col-md-6 col-sm-6">
									  <fieldset class="input-block">
										<label for="billing_phone">Số điện thoại</label>
										<input id="billing_phone" name="billing_phone" type="text" class="form-icon form-icon-phone {validate:{required:false, number:true, rangelength:[6,12]}}" maxlength="12" autocomplete="off" value="' . (isset($billing['billing_phone']) ? $billing['billing_phone'] : (isset($m->phone) ? $m->phone : (isset($_GET['TEST_MODE']) ? '0826919777' : ''))) . '" placeholder="Số điện thoại" required />
										<div class="tooltip">
										  <p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để chúng tôi xác nhận đơn hàng và giao hàng.</p>
										  <span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
									  </fieldset>
									</div>
									<div class="col-md-6 col-sm-6">
									  <fieldset class="input-block">
										<label for="billing_mobile">Số điện thoại di động</label>
										<input id="billing_mobile" name="billing_mobile" type="text" class="form-icon form-icon-phone {validate:{required:true, number:true, rangelength:[6,12]}}" maxlength="12" autocomplete="off" value="' . (isset($billing['billing_mobile']) ? $billing['billing_mobile'] : (isset($m->mobile) ? $m->mobile : (isset($_GET['TEST_MODE']) ? '0935777527' : ''))) . '" placeholder="Số điện thoại di động" required />
										<div class="tooltip">
										  <p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để chúng tôi xác nhận đơn hàng và giao hàng.</p>
										  <span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
									  </fieldset>
									</div>
								  </div>
								  <div class="row">
									<div class="col-md-4 col-sm-4">
									  <fieldset class="input-block">
										<label for="billing_city">Tỉnh/Thành</label>
										<div class="dropdown">' . '<span id="billing_city">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 1 and pid = 0', 'id', 'billing_city', 'name', 'id', (isset($billing['billing_city']) ? $billing['billing_city'] : (isset($m->city_id) ? $m->city_id : 51)), ['class' => 'dropdown-select sel short city {validate:{required:true}} cus']) . '</span>' . '</div>
									  </fieldset>
									</div>
									<div class="col-md-4 col-sm-4">
									  <fieldset class="input-block">
										<label for="billing_district">Quận/Huyện</label>
										<div class="dropdown">
										  <span id="billing_district">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 2 and pid = ' . (isset($billing['billing_city']) ? $billing['billing_city'] : (isset($m->city_id) ? $m->city_id : 51)), 'name', 'billing_district', 'name', 'id', (isset($billing['billing_district']) ? $billing['billing_district'] : (isset($m->district_id) ? $m->district_id : 621)), [
                            'firstText' => $_LNG->member->errors->district,
                            'class' => 'dropdown-select sel short district {validate:{required:true}} cus',
                        ]) . '</span>										  
										</div>
									  </fieldset>
									</div>
									<div class="col-md-4 col-sm-4">
									  <fieldset class="input-block">
										<label for="billing_ward">Phường/Xã</label>
										<div class="dropdown">
										  <span id="billing_ward">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 3 and pid = ' . (isset($billing['billing_district']) ? $billing['billing_district'] : (isset($m->district_id) ? $m->district_id : 621)), 'name', 'billing_ward', 'name', 'id', (isset($billing['billing_ward']) ? $billing['billing_ward'] : (isset($m->ward_id) ? $m->ward_id : 10072)), [
                            'firstText' => $_LNG->member->errors->ward,
                            'class' => 'dropdown-select sel sel short {validate:{required:true}} cus',
                        ]) . '</span>										  
										</div>
									  </fieldset>
									</div>
								  </div>
								  <div class="row">
									<div class="col-md-12 col-sm-12">
									  <fieldset class="input-block">
										<label for="billing_address">Địa chỉ</label>
										<input id="billing_address" name="billing_address" type="text" class="{validate:{required:true}}" maxlength="200" autocomplete="off" value="' . (isset($billing['billing_address']) ? $billing['billing_address'] : (isset($m->address) ? $m->address : (isset($_GET['TEST_MODE']) ? '205/11/13 Phạm Văn Chiêu' : ''))) . '" placeholder="Địa chỉ" required />
										<div class="tooltip">
										  <p> <b>Tại sao chúng tôi cần thông tin này?</b>Họ và tên của Quý khách, thông tin này được dùng để chúng tôi giao hàng.</p>
										  <span>Thông tin của Quý khách chúng tôi sẽ giữ an toàn, bí mật</span> </div>
									  </fieldset>
									</div>
								  </div>
								</div>
							  </div>
							</div>        
							<div class="prev">
							  <button class="button button-control" type="button" onclick="window.location.href=\'' . (!isset($m->id) ? '/thanh-toan.html?STEP=SIGNIN' : '/') . '\'"><span>Trở lại <b>' . (!isset($m->id) ? 'Đăng ký/Đăng nhập' : 'Trang chủ') . '</b></span></button>
							  <div class="button-divider"></div>
							</div>
							<div class="next">
							  <button class="button button-control" id="order" name="order" type="submit"><span>Tiếp tục <b>Xác nhận đơn hàng</b></span></button>
							  <div class="button-divider"></div>
							</div>' . (!isset($m->id) ? '<input type="hidden" id="CHECKOUT_NOT_REG" name="CHECKOUT_NOT_REG" />' : '') . '<input type="hidden" id="PNSDOTVN_STEP" name="PNSDOTVN_STEP" value="CUSTOMER_INFO" />' . '</form>						  
						  <div class="showdata corner_5" id="showdata" onclick="jQuery(this).css("display", "none")">
							<h4>' . $_LNG->contact->errors->note . '</h4>
							<ol>
							  <li><label for="billing_name" class="error">' . $_LNG->checkout->errors->billing_name . '</label></li>
							  <li><label for="billing_email" class="error">' . $_LNG->checkout->errors->billing_email . '</label></li>
							  <li><label for="billing_phone" class="error">' . $_LNG->checkout->errors->billing_phone . '</label></li>
							  <li><label for="billing_mobile" class="error">' . $_LNG->checkout->errors->billing_mobile . '</label></li>' . #'<li><label for="billing_country" class="error">' . $_LNG->checkout->errors->billing_country . '</label></li>' .
                        '<li><label for="billing_city" class="error">' . $_LNG->checkout->errors->billing_city . '</label></li>
							  <li><label for="billing_district" class="error">' . $_LNG->checkout->errors->billing_district . '</label></li>
							  <li><label for="billing_ward" class="error">' . $_LNG->checkout->errors->billing_ward . '</label></li>
							  <li><label for="billing_address" class="error">' . $_LNG->checkout->errors->billing_address . '</label></li>							  
							</ol>
							<a class="close" href="javascript:void(0);" onclick="jQuery("div.showdata").fadeOut(300).hide(1)"></a>
						  </div>						  
						  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
						  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
						  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>' . /*'<script type="text/javascript" src="' . JS_PATH . 'JS.checkout.js"></script>' .*/
                        '<script type="text/javascript">
						  $(function() {
							  var container = jQuery(\'div#showdata\');
							  var v = jQuery(\'#frmCheckout\').validate({
								  debug: false,
								  errorContainer: container,
								  errorLabelContainer: jQuery(\'ol\', container),
								  meta: \'validate\'
							  });
						  });
						  </script>		  
						</div>
					  </div>
					</div>';
                    break;
                case 'SIGNIN' :
                    if (isset($_SESSION['member']) && count($_SESSION['member']) > 0) {
                        header('Location: /thanh-toan.html?STEP=CUSTOMER_INFO');
                        exit;
                    }
                    $html = '<div id="content" class="col-md-12">
					  <link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_style.css" />
					  <link rel="stylesheet" href="' . CSS_PATH . 'tmm_form_wizard_layout.css" />
					  <link rel="stylesheet" href="' . CSS_PATH . 'fontello.css" />
					  <script src="' . JS_PATH . 'tmm_form_wizard_custom.js"></script> 
					  <!--[if lt IE 9]>
					  <script src="' . CSS_PATH . 'respond.min.js"></script>
					  <![endif]-->
					  <div class="form-container">
						<div id="tmm-form-wizard" class="container substrate">
						  <div class="row">
							<div class="col-xs-12">
							  <h2 class="form-login-heading"><a href="https://chauruachen.pns.vn" rel="nofollow">Trang chủ</a> > Các bước <span>đặt hàng</span></h2>
							</div>
						  </div>
						  <div class="row stage-container">
							<div class="stage tmm-current col-md-4 col-sm-4">
							  <div class="stage-header head-number">1</div>
							  <div class="stage-content">
								<h3 class="stage-title">Đăng ký/Đăng nhập</h3>
							  </div>
							</div>
							<div class="stage col-md-4 col-sm-4">
							  <div class="stage-header head-number">2</div>
							  <div class="stage-content">
								<h3 class="stage-title">Thông tin tài khoản</h3>
							  </div>
							</div>
							<div class="stage col-md-4 col-sm-4">
							  <div class="stage-header head-number">3</div>
							  <div class="stage-content">
								<h3 class="stage-title">Xác nhận đặt hàng</h3>
							  </div>
							</div>
						  </div>
						  <div class="row">
							<div class="col-xs-12">
							  <div class="form-header">
								<div class="form-title form-icon title-icon-lock"> <b>Account</b> Information </div>
								<div class="steps"> Bước 1 - 3 </div>
							  </div>
							</div>
						  </div>
						  <form id="frmSignin" name="frmSignin" method="post" action="/xu-ly-dang-nhap" enctype="application/x-www-form-urlencoded" role="form">
							<div class="form-wizard">
							  <div class="row">
								<div class="col-md-8 col-sm-7">
								  <fieldset class="input-block">
									<label for="email">Email</label>
									<input type="text" id="email" name="email" class="form-icon form-icon-user {validate: {required:true, email:true}}" placeholder="Địa chỉ E-Mail" required />									
								  </fieldset>
								  <fieldset class="input-block">
									<label for="password">Mật khẩu:</label>
									<input type="password" id="password" name="password" class="form-icon form-icon-lock {validate: {required:true, maxlength:32}}" placeholder="Mật khẩu" required />
								  </fieldset>								  
								</div>
							  </div>
							  <div class="row">Quý khách chưa có tài khoản ? <a class="reg-link" rel="nofollow" href="/dang-ky.html" title="Thanh toán">Đăng ký tại đây</a> hoặc <a class="buy-now-link" rel="nofollow" href="/thanh-toan.html?STEP=CUSTOMER_INFO&CHECKOUT_NOT_REG" title="Thanh toán">mua hàng không cần đăng ký</a></div>
							</div>
							<div class="prev">
							  <button class="button button-control" type="button" onclick="window.location.href=\'/gio-hang.html\'"><span>Trở lại <b>Giỏ hàng</b></span></button>
							  <div class="button-divider"></div>
							</div>
							<div class="next">							  
							  <button class="button button-control" type="submit" id="login" name="login"><span>Tiếp theo <b>Thông tin cá nhân</b></span></button>
							  <div class="button-divider"></div>
							</div>
						  </form>
						  <div class="showdata corner_5" id="showdata" onclick="$(this).css(\'display\', \'none\')">
							<h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
							<ol>
							  <li><label for="email" class="error">Email bắt buộc và phải đúng định dạng.</label></li>
							  <li><label for="password" class="error">Mật khẩu bắt buộc nhập.</label></li>
							</ol>
							<a class="close" href="javascript:void(0);" onclick="$(\'div.showdata\').fadeOut(300).hide(1)"></a>
					  	  </div>
						  <script type="text/javascript"> var REDIRECT_URL = \'/thanh-toan.html?STEP=CUSTOMER_INFO\'; </script>
						  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
						  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
						  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
						  <script type="text/javascript" src="' . JS_PATH . 'JS.signin.js"></script>
						</div>
					  </div>
					</div>';
                    break;
            }
        }
        return $html;
    }

    function buildCheckout2($d, $_LNG, $html = '') {
        $a = $this->getCart($d->code, $_LNG);
        if (isset($a['item']) && $a['item'] > 0 && isset($a['total']) && $a['total'] >= 0 && isset($a['list']) && is_array($a['list']) && count($a['list']) > 0) {
            if (MEMBER_SYS && !CHECKOUT_NOT_REG) {
                header('Location: /dang-nhap.html?PROCEED_TO_CHECKOUT');
                die();
            }
            if (MEMBER_SYS && !isset($_GET['CHECKOUT_NOT_REG'])) {
                $m = $this->getMember($d, $_LNG);
            }
            $html = '<div id="pns-cart">
			  <form id="frmCheckout" name="frmCheckout" method="post" action="/xu-ly-dat-hang" enctype="application/x-www-form-urlencoded">
				<div id="s-info-billing">
				  <div class="head"><h3>' . $_LNG->checkout->billing_title . '</h3></div>
				  <div id="i-customer-billing" class="checkout">
					<ul>
					  <li>
						<div class="input-box">
						  <label for="billing_name">' . $_LNG->member->name . ': <span class="required">*</span></label><br />
						  <input id="billing_name" name="billing_name" type="text" class="inp short {validate:{required:true}} cus" maxlength="64" autocomplete="off" tabindex="1" value="' . (isset($m->name) ? $m->name : '') . '" />
						</div>
						<div class="input-box">
						  <label for="billing_phone">' . $_LNG->member->phone . ':</label><br />
						  <input id="billing_phone" name="billing_phone" type="text" class="inp short {validate:{required:false, number:true, rangelength:[6,12]}} cus" maxlength="12" autocomplete="off" tabindex="2" value="' . (isset($m->phone) ? $m->phone : '') . '" />
						</div>
						<div class="input-box input-end">
						  <label for="billing_mobile">' . $_LNG->member->mobile . ': <span class="required">*</span></label><br />
						  <input id="billing_mobile" name="billing_mobile" type="text" class="inp short {validate:{required:true, number:true, rangelength:[6,12]}} cus" maxlength="12" autocomplete="off" tabindex="3" value="' . (isset($m->mobile) ? $m->mobile : '') . '" />
						</div>
					  </li>
					  <li>' . /*
						'<div class="input-box">
						  <label for="billing_country">' . $_LNG->member->country . ': <span class="required">*</span></label><br />' .
						  $this->SelectWithTable2($d->code, prefixTable . 'country', 'status = 1 and id = 233', 'id', 'billing_country', 'name', 'id', (isset($m->country_id) ? $m->country_id : 233), array('class' => 'sel short {validate:{required:true}} cus', 'tabindex' => 4)) .
						'</div>' .
						*/
                '<div class="input-box">
						  <label for="billing_city">' . $_LNG->member->city . ': <span class="required">*</span></label><br />
						  <span id="billing_city">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 1 and pid = 0', 'id', 'billing_city', 'name', 'id', (isset($m->city_id) ? $m->city_id : 51), [
                    'class' => 'sel short city {validate:{required:true}} cus',
                    'tabindex' => 5,
                ]) . '</span></div>
						<div class="input-box">
						  <label for="billing_district">' . $_LNG->member->district . ': <span class="required">*</span></label><br />
						  <span id="billing_district">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 2 and pid = ' . (isset($m->city_id) ? $m->city_id : 51), 'name', 'billing_district', 'name', 'id', (isset($m->district_id) ? $m->district_id : 621), [
                    'firstText' => $_LNG->member->errors->district,
                    'class' => 'sel short district {validate:{required:true}} cus',
                    'tabindex' => 6,
                ]) . '</span></div>
						<div class="input-box input-end">
						  <label for="billing_ward">' . $_LNG->member->ward . ': <span class="required">*</span></label><br />
						  <span id="billing_ward">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 3 and pid = ' . (isset($m->district_id) ? $m->district_id : 621), 'name', 'billing_ward', 'name', 'id', (isset($m->ward_id) ? $m->ward_id : 10072), [
                    'firstText' => $_LNG->member->errors->ward,
                    'class' => 'sel short {validate:{required:true}} cus',
                    'tabindex' => 6,
                ]) . '</span></div>
					  </li>
					  <li>' . (!isset($m->id) ? '<div class="input-box">
						  <label for="billing_email">Email: <span class="required">*</span></label><br />
						  <input id="billing_email" name="billing_email" type="text" class="inp short {validate:{required:true,email:true}}" maxlength="64" autocomplete="off" tabindex="7" value="" />
						</div>' : '') . '<div class="input-box long">
						  <label for="billing_address">' . $_LNG->member->address . ': <span class="required">*</span></label><br />
						  <input id="billing_address" name="billing_address" type="text" class="inp {validate:{required:true}} cus" maxlength="200" autocomplete="off" tabindex="8" value="' . (isset($m->address) ? $m->address : '') . '" />
						  <span class="required">(' . $_LNG->member->address_format . ')</span>
						  <p class="help-text">' . $_LNG->checkout->billing_note . '</p>
						</div>
					  </li>
					</ul>
				  </div>
				</div>
				<div id="s-info-shipping">
				  <div class="head" style="position:relative;">
					<h3>' . $_LNG->checkout->shipping_title . '</h3>
					<p style="position: absolute; top: 5px; right: 10px; color:#FFF; font-style:italic;">
					  <input type="checkbox" id="chkSame" name="chkSame" style="cursor:pointer;"' . (isset($m->id) ? ' checked="checked"' : '') . ' />
					  <label for="chkSame" style="cursor:pointer;">' . $_LNG->checkout->same_buyer . '</strong></label>
					</p>
				  </div>
				  <div id="i-customer-shipping" class="checkout">
					<ul>
					  <li>
						<div class="input-box">
						  <label for="shipping_name">' . $_LNG->member->name . ': <span class="required">*</span></label><br />
						  <input type="text" id="shipping_name" name="shipping_name" class="inp short {validate:{required:true}} cus" value="' . (isset($m->name) ? $m->name : '') . '" tabindex="8" maxlength="50" autocomplete="off" />
						</div>
						<div class="input-box">
						  <label for="shipping_phone">' . $_LNG->member->phone . ':</label><br />
						  <input type="text" id="shipping_phone" name="shipping_phone" class="inp short {validate:{required:false, number:true, rangelength:[6,12]}} cus" value="' . (isset($m->phone) ? $m->phone : '') . '" tabindex="9" maxlength="12" autocomplete="off" />
						</div>
						<div class="input-box input-end">
						  <label for="shipping_mobile">' . $_LNG->member->mobile . ': <span class="required">*</span></label><br />
						  <input type="text" id="shipping_mobile" name="shipping_mobile" class="inp short {validate:{required:true, number:true, rangelength:[6,12]}} cus" value="' . (isset($m->mobile) ? $m->mobile : '') . '" tabindex="10" maxlength="12" autocomplete="off" />
						</div>
					  </li>
					  <li>' . /*
						'<div class="input-box">
						  <label for="shipping_country">' . $_LNG->member->country . ': <span class="required">*</span></label><br />' .
						  $this->SelectWithTable2($d->code, prefixTable . 'country', 'status = 1 and id = 233', 'id', 'shipping_country', 'name', 'id', 233, array('class' => 'sel short {validate:{required:true}} cus', 'tabindex' => 11)) .
						'</div>' .
						*/
                '<div class="input-box">
						  <label for="shipping_city">' . $_LNG->member->city . ': <span class="required">*</span></label><br />
						  <span id="shipping_city">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 AND lgroup = 1 AND pid = 0', 'id', 'shipping_city', 'name', 'id', (isset($m->city_id) ? $m->city_id : 51), [
                    'class' => 'sel short city {validate:{required:true}} cus',
                    'tabindex' => 12,
                ]) . '</span></div>
						<div class="input-box">
						  <label for="shipping_district">' . $_LNG->member->district . ': <span class="required">*</span></label><br />
						  <span id="shipping_district">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 2 AND pid = ' . (isset($m->city_id) ? $m->city_id : 51), 'name', 'shipping_district', 'name', 'id', (isset($m->district_id) ? $m->district_id : 621), [
                    'firstText' => $_LNG->member->errors->district,
                    'class' => 'sel short district {validate:{required:true}} cus',
                    'tabindex' => 13,
                ]) . '</span></div>
						<div class="input-box input-end">
						  <label for="shipping_ward">' . $_LNG->member->ward . ': <span class="required">*</span></label><br />
						  <span id="shipping_ward">' . $this->SelectWithTable2($d->code, prefixTable . 'location', 'status = 1 and lgroup = 3 AND pid = ' . (isset($m->district_id) ? $m->district_id : 621), 'name', 'shipping_ward', 'name', 'id', (isset($m->ward_id) ? $m->ward_id : 10072), [
                    'firstText' => $_LNG->member->errors->ward,
                    'class' => 'sel short {validate:{required:true}} cus',
                    'tabindex' => 14,
                ]) . '</span></div>
					  </li>
					  <li>
						<div class="input-box long">
						  <label for="shipping_address">' . $_LNG->member->address . ': <span class="required">*</span></label><br />
						  <input type="text" id="shipping_address" name="shipping_address" class="inp {validate:{required:true}} cus" value="' . (isset($m->address) ? $m->address : '') . '" tabindex="14" maxlength="200" />
						  <span class="required">(' . $_LNG->member->address_format . ')</span> </div>
					  </li>
					</ul>
				  </div>
				</div>
				<div id="s-payment-method">
				  <div class="head"><h3>' . $_LNG->checkout->payment->title . '</h3></div>
				  <div id="i-customer-payment" class="checkout">
					<ul>
					  <li>
						<input type="radio" name="payment_method" id="payment_method1" value="1" tabindex="19" checked="checked">
						&nbsp;
						<label for="payment_method1" class="payment">' . $_LNG->checkout->payment->methods->pod . '</label>
					  </li>
					  <li>
						<input type="radio" name="payment_method" id="payment_method2" value="2" tabindex="20" />
						&nbsp;
						<label for="payment_method2" class="payment">' . $_LNG->checkout->payment->methods->transfer . '</label>
						<div id="bankinfo" style="display:none; padding: 10px 0 0 25px; position:relative;"><span id="close" style="position:absolute; top:0px; right:0px; cursor:pointer;">x ' . $_LNG->others->close . '</span>' . $this->showBankInfo($d->code) . '</div>
					  </li>
					</ul>
				  </div>
				</div>' . $this->buildPoint2Money($d, $_LNG) . '<div id="s-review">
				  <div class="head">
					<h3>' . $_LNG->checkout->review->title . '</h3>
				  </div>
				  <div id="i-customer-review" class="checkout">
					<div class="title">' . $_LNG->checkout->review->detail . '</div>
					<div id="order-info">
					  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="orders_prodcuts">
						<tbody>
						  <tr class="cartTableHeading" style="background-color: #E6E2E2">
							<th width="5%">' . $_LNG->cart->no . '</th>
							<th width="10%">' . $_LNG->cart->itempicture . '</th>
							<th scope="col">' . $_LNG->cart->itemname . '</th>
							<th width="17%">' . $_LNG->cart->unitprice . '</th>
							<th width="8%">' . $_LNG->cart->qty . '</th>
							<th width="15%">' . $_LNG->cart->unittotal . '</th>
						  </tr>';
            foreach ($a['list'] as $k => $v) {
                $html .= '<tr>
							<td align="center" class="pl_10">1</td>
							<td><img alt="' . $v->rewrite . '" src="/thumb/300x300/1:1' . $v->src . '" width="55" height="55" title="' . $v->title . '" /></td>
							<td class="left">' . $v->name . '
							  <div class="child-item">' . (!empty($v->code) ? '<label>' . $_LNG->cart->code . ':</label> ' . $v->code . '<br />' : '') . (isset($v->color) && !empty($v->color) ? '<label>' . $_LNG->others->color . ':</label> ' . stripslashes($v->color) . '<br />' : '') . '</div></td>
							<td class="center"><div class="fl pl_20"><span class="fl">' . $v->price_txt . '</span></div></td>
							<td>x' . $v->qty . '</td>
							<td>' . $v->amount_txt . '</td>
						  </tr>';
            }
            $html .= '</tbody>
					  </table>
					  <div id="ottotal" class="clear">
						<div class="totalBox larger forward"><span id="pns_minus">0 đ</span></div>
						<div class="lineTitle larger forward">Giảm giá với điểm tặng:</div>
					  </div>
					  <div id="ottotal" class="clear">
					    <div id="pns_total" style="display:none">' . $a['total'] . '</div>
						<div class="totalBox larger forward"><span id="total">' . $a['total_txt'] . '</span></div>
						<div class="lineTitle larger forward">' . $_LNG->checkout->grand_total . ':</div>
					  </div>
					</div>
					<ul>
					  <li>
						<div class="input-box medium">
						  <label for="order_note">' . $_LNG->checkout->review->note . ':</label><br />
						  <textarea id="order_note" name="order_note" class="tex {validate:{required:false, maxlength:500}}" tabindex="24"></textarea>
						</div>
					  </li>
					</ul>
				  </div>
				</div>
				<div class="b-transaction">
				  <input id="cart" name="cart" type="button" class="button fullbox" tabindex="30" value="' . $_LNG->checkout->button->cart . '" />
				  <input id="return" name="return" type="button" class="button fullbox" tabindex="31" value="' . $_LNG->checkout->button->return . '" />
				  <input id="order" name="order" type="submit" class="button fullbox" tabindex="32" value="' . $_LNG->checkout->button->confirm . '" />
				</div>
				<input type="hidden" id="tax" name="tax" value="0" />
			  </form>
			  <p><span class="required">* ' . $_LNG->contact->required . '</span></p>
			  <div class="showdata corner_5" id="showdata" onclick="jQuery(this).css("display", "none")">
				<h4>' . $_LNG->contact->errors->note . '</h4>
				<ol>
				  <li><label for="billing_name" class="error">' . $_LNG->checkout->errors->billing_name . '</label></li>
				  <li><label for="billing_email" class="error">' . $_LNG->checkout->errors->billing_email . '</label></li>
				  <li><label for="billing_phone" class="error">' . $_LNG->checkout->errors->billing_phone . '</label></li>
				  <li><label for="billing_mobile" class="error">' . $_LNG->checkout->errors->billing_mobile . '</label></li>
				  <li><label for="billing_country" class="error">' . $_LNG->checkout->errors->billing_country . '</label></li>
				  <li><label for="billing_city" class="error">' . $_LNG->checkout->errors->billing_city . '</label></li>
				  <li><label for="billing_district" class="error">' . $_LNG->checkout->errors->billing_district . '</label></li>
				  <li><label for="billing_ward" class="error">' . $_LNG->checkout->errors->billing_ward . '</label></li>
				  <li><label for="billing_address" class="error">' . $_LNG->checkout->errors->billing_address . '</label></li>
				  <li><label for="shipping_name" class="error">' . $_LNG->checkout->errors->shipping_name . '</label></li>
				  <li><label for="shipping_phone" class="error">' . $_LNG->checkout->errors->shipping_phone . '</label></li>
				  <li><label for="shipping_mobile" class="error">' . $_LNG->checkout->errors->shipping_mobile . '</label></li>
				  <li><label for="shipping_country" class="error">' . $_LNG->checkout->errors->shipping_country . '</label></li>
				  <li><label for="shipping_city" class="error">' . $_LNG->checkout->errors->shipping_city . '</label></li>
				  <li><label for="shipping_district" class="error">' . $_LNG->checkout->errors->shipping_district . '</label></li>
				  <li><label for="shipping_ward" class="error">' . $_LNG->checkout->errors->shipping_ward . '</label></li>
				  <li><label for="shipping_address" class="error">' . $_LNG->checkout->errors->shipping_address . '</label></li>
				  <li><label for="order_note" class="error">' . $_LNG->checkout->errors->order_note . '</label></li>
				</ol>
				<a class="close" href="javascript:void(0);" onclick="jQuery("div.showdata").fadeOut(300).hide(1)"></a></div>
			  <script type="text/javascript"> 
			  var REDIRECT_URL = "' . ((MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->others->finished->rewrite . EXT) . '"; 
			  var CART_URL = "' . ((MULTI_LANG ? DS . $def->code2 : '') . DS . $_LNG->cart->rewrite . EXT) . '"; 
			  var RETURN_URL = "' . ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : DS) . '"; 
			  </script>
			  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
			  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
			  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script> 
			  <script type="text/javascript" src="' . JS_PATH . 'JS.checkout.js"></script>
			</div>';
        }
        return $html;
    }

    function buildPoint2Money($d, $_LNG, $html = '') {
        if (POINT_REWARD && isset($_SESSION['member']) && count($_SESSION['member']) > 0) {
            $point = $this->getPoint();
            if ($point > 0) {
                $html = '<div id="s-payment-method">
				  <div class="head">
					<h3>Sử dụng điểm tặng</h3>
				  </div>
				  <div id="i-customer-payment" class="checkout">
					<script>
					$( function() {
					  $( "#slider" ).slider({
						value:0,
						min: 0,
						max: ' . $point . ',
						step: 1,
						slide: function( event, ui ) {
						  var point = ui.value;
						  var money = point * 1000;
						  $( "#pns_point" ).val(point);
						  $( "#pns_point_txt" ).html(point);
						  $( "#pns_minus" ).html(money.formatMoney());
						  $( "#pns_minus_txt" ).html(money.formatMoney());
						  var total = $("#pns_total").html();
						  $( "#total" ).html((total - money).formatMoney());
						}
					  });
					  $( "#pns_point" ).val($( "#slider" ).slider( "value" ) + " đ");
					} );
					</script>
					<p>
					  <label for="amount">Bạn hiện có ' . $point . ' điểm, sử dụng thanh công cụ bên dưới để qui đổi điểm thưởng thành tiền thanh toán cho đơn hàng:&nbsp;</label><br />
					  Sử dụng <span id="pns_point_txt" style="border:0; color:#f6931f; font-weight:bold;">0</span> điểm tương đương <span id="pns_minus_txt" style="border:0; color:#f6931f; font-weight:bold;">0 đ</span>.
					  <input type="hidden" name="pns_point" id="pns_point" readonly />
					</p>	 
					<div id="slider"></div>
				  </div>
				</div>';
            }
        }
        return $html;
    }

    function buildCart($d, $_LNG, $html = '') {
        $html = '<div id="pns-cart">';
        $a = $this->getCart($d->code, $_LNG);
        if (isset($a['item']) && $a['item'] > 0 && isset($a['total']) && $a['total'] >= 0 && isset($a['list']) && is_array($a['list']) && count($a['list']) > 0) {
            $html .= '<div class="clear">
			  <table cellspacing="0" cellpadding="0" id="cartContentsDisplay">
				<tbody>
				  <tr id="sc_pHeading">
					<th id="pNo_title">' . $_LNG->cart->no . '</th>
					<th scope="col" id="pShortName_title">' . $_LNG->cart->itemname . '</th>
					<th id="pPrice_title">' . $_LNG->cart->unitprice . '</th>
					<th id="pQty_title">' . $_LNG->cart->qty . '</th>
					<th id="pTotal_title">' . $_LNG->cart->unittotal . '</th>
					<th id="pTools_title">&nbsp;</th>
				  </tr>';
            $i = 1;
            foreach ($a['list'] as $k => $v) {
                $html .= '<tr id="#item_' . $v->ids . '" class="sc_pContent item_' . $v->ids . ($i == $a['item'] ? ' last' : '') . '">
				  <td class="pNo ordering">' . $i . '</td>
				  <td class="pShortName">
					<img alt="' . $v->rewrite . '" src="/thumb/300x300/1:1' . $v->src . '" width="64" height="64" class="cartImage" title="' . $v->title . '" />
					<a rel="nofollow" href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a>
					<div class="child-item">' . (!empty($v->code) ? '<label>' . $_LNG->cart->code . ':</label> ' . $v->code . '<br />' : '') . (isset($v->color) && !empty($v->color) ? '<label>' . $_LNG->others->color . ':</label> ' . stripslashes($v->color) . '<br />' : '') . '</div>
				  </td>
				  <td class="pPrice">' . ($v->sale_off > 0 ? '<s class="normalprice">' . $v->list_price_txt . '</s><br />' : '') . ($v->price > 0 ? $v->price_txt : $_LNG->contact->title) . '</td>
				  <td class="pQty">
					<span title="' . $_LNG->cart->removeone . '" id="reduce|' . $v->ids . '" name="adjust" class="swSprite p_list_reduce"></span>
					<span id="inp_' . $v->ids . '"><input class="p_list_input" type="text" maxlength="2" id="qty_' . $v->ids . '" name="qty|' . $v->ids . '" value="' . $v->qty . '" /></span>
					<span title="' . $_LNG->cart->addone . '" id="add|' . $v->ids . '" name="adjust" class="swSprite p_list_add"></span>
				  </td>
				  <td class="pTotal" id="pri' . $v->ids . '"><span id="price_' . $v->ids . '">' . ($v->amount > 0 ? $v->amount_txt : $_LNG->contact->title) . '</span></td>
				  <td class="pTools"><input class="remove_item" name="btnRemoveItem" type="button" value="' . $v->ids . '" id="btnRemoveItem_' . $v->ids . '" title="' . $_LNG->cart->removeitem . '" /></td>
				</tr>';
                $i++;
            }
            $html .= '<tr id="sc_pFooter"><td colspan="6"><span>' . $_LNG->cart->subtotal . ':</span>&nbsp;<span id="totalPrice">' . ($a['total'] > 0 ? $a['total_txt'] : $_LNG->contact->title) . '</span></td></tr>
        	  </tbody>
      		</table>			
			</div>
			<div class="b-transaction">
			  <input class="button box" type="button" value="' . $_LNG->others->home->title . '" name="home" /> 
			  <input class="button box" type="button" value="' . $_LNG->cart->backto . '" name="continue" /> 
			  <input class="button box" type="button" value="' . $_LNG->checkout->button->proceed . '" name="checkout" />
			</div>
			<script type="text/javascript"> $(document).ready(function(){ 
			  $("input[name=home]").click(function(){ document.location.href = \'' . HOST . '\'; }); 
			  $("input[name=continue]").click(function(){ document.location.href = \'' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : HOST) . '\'; }); 			  $("input[name=checkout]").click(function(){ document.location.href = \'' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->checkout->rewrite . EXT) . '\'; }); }); 
			</script>';
        }
        else {
            $html .= '<div class="fullbox" style="margin: 5px 15px; padding: 10px; text-align: center; position: relative;">' . str_replace('%%SITE_NAME%%', $this->upcaseFirst(SITE_NAME), $_LNG->cart->empty) . '</div>';
        }
        $html .= '</div>';
        return $html;
    }

    function buildBreadcrumb($d, $_LNG, $html = '') {
        $a = (MULTI_LANG ? DS . $d->code2 : '');
        $html .= ($d->route->name == 'product' && $d->route->type == 'detail' ? '<div class="container-fluid">' : '') . '<div class="row">
		  <div class="breadCrumb module">
			<ul>
			  <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="first"><a href="' . HOST . '" itemprop="url" title="' . SITE_NAME . '"><span itemprop="title"><i class="fa fa-home">Home</i></span></a></li>';
        switch ($d->route->name) {
            case 'product':
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $a . DS . $_LNG->product->rewrite . EXT . '" itemprop="url" title="' . $_LNG->product->title . '"><span itemprop="title">' . $_LNG->product->title . '</span></a></li>' . $this->buildAliasHtml($d->code, $this->getAliasArray($d->code, $this->getCatIdByProId($d->route->id)), 2) . '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="last"><a href="' . $d->url . '" itemprop="url" title="' . $d->title . '"><span itemprop="title">' . $d->title . '</span></a></li>';
                break;
            case 'category':
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $a . DS . $_LNG->product->rewrite . EXT . '" itemprop="url" title="' . $_LNG->product->title . '"><span itemprop="title">' . $_LNG->product->title . '</span></a></li>' . $this->buildAliasHtml($d->code, $this->getAliasArray($d->code, $d->route->id), 2);
                break;
            case 'brand':
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $a . DS . $_LNG->product->brand->rewrite . EXT . '" itemprop="url" title="' . $_LNG->product->brand->title . '"><span itemprop="title">' . $_LNG->product->brand->title . '</span></a></li>' . ($d->route->type == 'detail' ? '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $d->url . '" itemprop="url" title="' . $d->title . '"><span itemprop="title">' . $d->title . '</span></a></li>' : '');
                break;
            case 'supplier':
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $a . DS . 'nha-cung-cap' . EXT . '" itemprop="url" title="Nhà cung cấp"><span itemprop="title">Nhà cung cấp</span></a></li>' . ($d->route->type == 'detail' ? '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $d->url . '" itemprop="url" title="' . $d->title . '"><span itemprop="title">' . $d->title . '</span></a></li>' : '');
                break;
            case 'checkout':
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $a . DS . $_LNG->cart->rewrite . EXT . '" itemprop="url" title="' . $_LNG->cart->title . '"><span itemprop="title">' . $_LNG->cart->title . '</span></a></li>' . '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $d->url . '" itemprop="url" title="' . $d->title . '"><span itemprop="title">' . $d->title . '</span></a></li>';
                break;
            case 'cms':
                $html .= $this->buildAliasHtml($d->code, $this->getMenuAliasArray($d->code, ($d->mid > 0 ? $d->mid : $d->route->id)), 2) . (!empty($d->route->type) && $d->route->type == 'detail' ? '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $d->url . '" itemprop="url" title="' . $d->title . '"><span itemprop="title">' . $d->title . '</span></a></li>' : '');
                break;
            default:
                $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $d->url . '" itemprop="url" title="' . $d->title . '"><span itemprop="title">' . $d->title . '</span></a></li>';
                break;
        }
        $html .= '</ul></div></div>' . ($d->route->name == 'product' && $d->route->type == 'detail' ? '</div>' : '');
        return $html;
    }

    function buildCatalogMenu($l, $pid = 0, $t = 1, $lv = 1, $a = '', & $html = '') {
        switch ($t) {
            case 1 :
                if ($lv == 1) {
                    $a = $this->getCatalogMenu($l, $pid);
                }
                if (isset($a) && is_array($a) && count($a)) {
                    $html .= '<ul class="bl1">';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<li><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a></li>';
                        }
                    }
                    $html .= '</ul>';
                }
                break;
            case 2 :
                if ($lv == 1) {
                    $a = $this->getCatalogMenu($l, $pid, 2);
                }
                if (isset($a) && is_array($a) && count($a)) {
                    $html .= '<div class="categories">
					  <div class="title beefup__head">Danh mục</div>
					  <ul class="beefup__body">';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $t = $this->countProduct($c->id, 'catalog');
                            $html .= '<li><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a>' . ($t > 0 ? ' (' . $t . ')' : '') . '</li>';
                        }
                    }
                    $html .= '</ul></div>';
                }
                break;
            case 3 :
                if ($lv == 1) {
                    $a = $this->getCatalogMenu($l, $pid);
                }
                if (isset($a) && is_array($a) && count($a)) {
                    $html .= '<select id="search-selectBox" name="standard-dropdown" class="cat" style="width: 200px;">
					<option value="0">Tất cả danh mục</option>';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<option value="' . $c->id . '">' . $c->name . '</option>';
                        }
                    }
                    $html .= '</select>';
                }
                break;
            default :
                if ($lv == 1) {
                    $a = $this->getCatalogMenu($l);
                }
                if (isset($a) && is_array($a) && count($a)) {
                    $html .= '<ul' . ($lv == 1 ? ' id="navmenu-v"' : '') . '>';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<li><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a>';
                            if (isset($v['child']) && is_array($v['child']) && count($v['child'])) {
                                $this->buildCatalogMenu($l, $pid, $t, $lv + 1, $v['child'], $html);
                            }
                            $html .= '</li>';
                        }
                    }
                    $html .= '</ul>';
                }
                break;
        }
        return $html;
    }

    function buildKeyword($k, $html = '') {
        if (!empty($k)) {
            $i = explode(',', $k);
            if (is_array($i) && ($c = count($i)) > 0) {
                for ($j = 0; $j < $c; $j++) {
                    $i[$j] = trim($i[$j]);
                    $t[] = '<a href="' . (MULTI_LANG ? DS . $d->code2 : '') . DS . 'tim-kiem' . EXT . '?keyword=' . str_replace(' ', '+', $i[$j]) . '" title="' . $i[$j] . '" rel="tag nofollow">' . $i[$j] . '</a>';
                }
                if (isset($t) && is_array($t) && count($t) > 0) {
                    $html = implode(', ', $t);
                }
            }
        }
        return $html;
    }

    /**
     * @param $d
     * @param $p
     * @param string $html
     *
     * @return string
     */
    function buildPicture($d, $p, $html = '') {
        switch ($d->route->name) {
            case 'product' :
                if (!empty($p->picture)) {
                    $i = explode(';', $p->picture);
                    if (is_array($i) && ($c = count($i)) > 0) {
                        $html = '<div id="slider-o-i" class="flexslider-o-i">
                                  <ul id="glasscase" class="gc-start">';
                        for ($j = 0; $j < $c; $j++) {
                            $html .= '<li><img class="" alt="' . $p->rewrite . '" src="' . $i[$j] . '" title="' . $p->title . '" /></li>';
                        }
                        $html .= '</ul></div>';
                    }
                }
                break;
        }
        return $html;
    }

    function buildSearch($d, $u) {
        $data = isset($_POST) ? $_POST : '';
        $a = (isset($data['keyword']) && !empty($data['keyword'])) ? $u->checkValues($data['keyword']) : (isset($_GET['keyword']) && !empty($_GET['keyword']) ? str_replace('+', ' ', $u->checkValues($_GET['keyword'])) : '');
        if (!empty($a)) {
            $d->search = $a;
            $b = 'k=' . str_replace(' ', '+', $a);
        }
        $c = (isset($data['standard-dropdown']) && $data['standard-dropdown'] > 0) ? $u->checkValues($data['standard-dropdown']) : (isset($_GET['catalog']) && $_GET['catalog'] > 0 ? $u->checkValues($_GET['catalog']) : '');
        if (!empty($c)) {
            $d->catalog_search = $c;
            $e = 'catalog=' . $c;
        }
        if (isset($_POST) && !empty($_POST) && count($_POST) > 0) {
            header('Location: ' . (MULTI_LANG ? DS . $d->code2 : '') . DS . 'tim-kiem' . EXT . (!empty($b) ? '?' . $b : '') . (isset($e) && !empty($e) ? '&' . $e : ''));
            exit;
        }
    }

    function buildNewProduct($d, $_LNG, $html = '') {
        $d->PageNo = PAGE;
        $d->PageUrl = $d->url;
        $d->Pagenumber = PAGE_NUMBER;
        $d->ModePaging = MODE_PAGING;
        $d->route->name = 'new';
        $a = $this->getProduct($d);
        if (isset($a['list']) && is_array($a['list']) && isset($a['total']) && $a['total'] > 0) {
            $i = 0;
            foreach ($a['list'] as $k => $v) {
                $o = $v->outofstock ? TRUE : FALSE;
                $html .= '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6" data-wow-delay="' . ($i * 100) . 'ms">
				  <div class="border box-img"> <span class="over-layer-0"></span>' . (!empty($v->lcolor) && !empty($v->lname) ? '<span class="label ' . $v->lcolor . '">' . $v->lname . '</span>' : '') . #($v->new ? '<div class="sale-box">New</div>' : '') .
                    ($v->sale_off > 0 ? '<span class="label red">-' . $v->sale_off . '%</span>' : '') . '<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/250x250' . $v->src . '" title="' . $v->title . '" /></a></div>
					<div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
					<div class="gr-price">' . ($v->list_price > 0 && $v->list_price > $v->price ? ' <span class="list-price"><span>' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span> </div>
				  </div>
				</div>';
                $i++;
            }
        }
        $d->route->name = 'home';
        return $html;
    }

    function buildHotProduct($d, $_LNG, $html = '') {
        $d->PageNo = PAGE;
        $d->PageUrl = $d->url;
        $d->Pagenumber = PAGE_NUMBER;
        $d->ModePaging = MODE_PAGING;
        $d->route->name = 'hot';
        $a = $this->getProduct($d);
        if (isset($a['list']) && is_array($a['list']) && isset($a['total']) && $a['total'] > 0) {
            foreach ($a['list'] as $k => $v) {
                $o = $v->outofstock ? TRUE : FALSE;
                $html .= '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">
				  <div class="border box-img"> <span class="over-layer-0"></span>' . (!empty($v->lcolor) && !empty($v->lname) ? '<span class="label ' . $v->lcolor . '">' . $v->lname . '</span>' : '') . ($v->new ? '<div class="sale-box">New</div>' : '') . ($v->sale_off > 0 ? '<span class="label red">-' . $v->sale_off . '%</span>' : '') . '<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/250x250' . $v->src . '" title="' . $v->title . '" /></a></div>
					<div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
					<div class="gr-price">' . ($v->list_price > 0 && $v->list_price > $v->price ? ' <span class="list-price"><span>' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span> </div>
				  </div>
				</div>';
            }
        }
        $d->route->name = 'home';
        return $html;
    }

    function buildProduct($d, $_LNG, $html = '') {
        $z = (!in_array($d->route->name, [
            'related',
            'inbrand',
        ]) ? TRUE : FALSE);
        if ($z) {
            $d->display = DEFAULT_PRODUCT_DISPLAY;
            if (Cookie::Exists('PRODUCT_DISPLAY') && !Cookie::IsEmpty('PRODUCT_DISPLAY') && array_key_exists(Cookie::Get('PRODUCT_DISPLAY'), $d->PRODUCT_DISPLAY)) {
                $d->display = $d->PRODUCT_DISPLAY[Cookie::Get('PRODUCT_DISPLAY')];
            }
            else {
                Cookie::Set('PRODUCT_DISPLAY', array_search(DEFAULT_PRODUCT_DISPLAY, $d->PRODUCT_DISPLAY), Cookie::OneYear);
            }
            $d->orderby = DEFAULT_PRODUCT_SORTING;
            $d->seleted = 'PRODUCT_CREATED_DESC';
            if (Cookie::Exists('PRODUCT_SORTING') && !Cookie::IsEmpty('PRODUCT_SORTING') && array_key_exists(Cookie::Get('PRODUCT_SORTING'), $d->PRODUCT_SORTING)) {
                $d->seleted = Cookie::Get('PRODUCT_SORTING');
                $d->orderby = $d->PRODUCT_SORTING[$d->seleted]['query'];
            }
            else {
                Cookie::Set('PRODUCT_SORTING', array_search(DEFAULT_PRODUCT_SORTING, $d->PRODUCT_SORTING), Cookie::OneYear);
            }
        }
        $d->PageNo = PAGE;
        $d->PageUrl = $d->url;
        $d->Pagenumber = PAGE_NUMBER;
        $d->ModePaging = MODE_PAGING;
        $a = $this->getProduct($d);
        if (isset($a['list']) && is_array($a['list']) && isset($a['total']) && $a['total'] > 0) {
            $b = ($d->route->name == 'brand' && $d->route->type == 'all' ? TRUE : FALSE);
            $s = ($d->route->name == 'supplier' && $d->route->type == 'all' ? TRUE : FALSE);
            if (isset($a['page']) && !empty($a['page'])) {
                @$d->toolbar->page = $a['page'];
            }
            @$d->toolbar->total = $a['total'];
            @$d->toolbar->brand = $b;
            @$d->toolbar->supplier = $s;
            $tb = $this->buildToolbar($d);
            $html = ($z ? (isset($d->image) && !empty($d->image) && $d->route->name == 'category' ? '<div class="cat-banner visible-md visible-lg"><img alt="" src="' . $d->image . '" title="" onerror="$(this).css({display:\'none\'})" /></div>' : '') . '<div class="text visible-md visible-lg">' . (isset($d->desc) ? $d->desc : '') . '</div>' . '<div id="central" class="mt5">' . (in_array($d->route->name, ['category']) ? '<link href="' . CSS_PATH . 'bootstrap-rating.css" rel="stylesheet" />
			  <script type="text/javascript" src="' . JS_PATH . 'bootstrap-rating.js"></script>' : '') . $tb . '<section class="products ' . $d->route->name . '">
    			<div class="content">' : '<section class="h-products products bg-white mt10" style="border:0px;width:100%">
			  <div class="title title-ember">Sản phẩm ' . ($d->route->name == 'inbrand' ? 'cùng thương hiệu' : 'liên quan') . ':</div>
			  <div id="normal-imglist-' . $d->route->name . '" class="util-carousel normal-imglist content">');
            $i = 0;
            foreach ($a['list'] as $k => $v) {
                $o = !$b && !$s && $v->outofstock ? TRUE : FALSE;
                if ($z) {
                    if ($d->display == 'PRODUCT_DISPLAY_GRID' || $b || $d->route->name == 'search') {
                        /*if (!$b && !$s) {
                            $t1 = $d->route->name;
                            $t2 = $d->route->id;
                            if ($d->route->name == 'brand') {
                                $d->route->name = 'product';
                                $d->route->id = $v->brand;
                                $t3 = $this->getBrand($d);
                            }
                            if ($d->route->name == 'supplier') {
                                $d->route->name = 'product';
                                $d->route->id = $v->supplier;
                                $t3 = $this->getSupplier($d);
                            }
                            if (!empty($t3) && is_array($t3) && count($t3) == 1)
                                $brand = $t3[0];
                            $d->route->name = $t1;
                            $d->route->id = $t2;
                        }*/
                        $html .= /*
						'<div class="block col-md-3 col-sm-4 col-xs-6">
						  <div class="border">' .
							(!$b && $v->new ? '<div class="icon-new"></div>' : '') .
							(!$b && isset($brand) ? '<div class="brand"><a href="' . $brand->href . '" title="' . $brand->title . '"><img alt="' . $brand->rewrite . '" src="' . $brand->src . '" height="15" title="' . $brand->title . '" /></a></div>' : '') .
							'<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/300x300' . $v->src . '" title="' . $v->title . '" /></a></div>
							<div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>' .
							(!$b ? '<div class="g-price"><span class="list-price">' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span>' . ($v->list_price > $v->price ? '<span class="price">' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span>' : '') . '</div>' : '') .
							(!$b && $d->add2cart ? 
							'<div class="bottom">
							  <div class="order">
								<input type="hidden" name="product_' . $v->id . '" id="product_' . $v->id . '" value="' . $v->id . '" />
								<input type="button" id="' . $v->id . '" name="btnAddtoCart" value="Đặt hàng" title="' . $v->title . '"' . ($o ? ' disabled="disabled" style="cursor:not-allowed"' : '') . ' />
							  </div>
							</div>' : '') .
						  '</div>
						</div>';
						*/
                            '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6" data-wow-delay="' . ($i * 100) . 'ms">
						  <div class="border box-img"> <span class="over-layer-0"></span>' . (!$b && !$s && !empty($v->lcolor) && !empty($v->lname) ? '<span class="label ' . $v->lcolor . '">' . $v->lname . '</span>' : '') . (!$b && !$s && $v->new ? '<div class="sale-box">New</div>' : '') . (!$b && !$s && $v->sale_off ? '<span class="label red">-' . $v->sale_off . '%</span>' : '') . '<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="' . (!$b ? '/thumb/250x250' : '') . $v->src . '" title="' . $v->title . '" /></a></div>
							<div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>' . (!$b && !$s ? '<div class="gr-price">' . ($v->list_price > $v->price ? ' <span class="list-price"><span>' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span> </div>' . ($v->rating_vote > 0 ? '<div class="rating"><input type="hidden" class="rating" value="' . ($v->rating_point / $v->rating_vote) . '" disabled="disabled" /></div>' : '') : '') . '</div>						  
						</div>';
                    }
                    else {
                        $html .= '<div class="block detail">
							<div class="col-left col-md-9">
								<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/300x300' . $v->src . '" title="' . $v->title . '" onerror="$(this).css({display:\'none\'})"></a></div>
								<div class="name"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
								<div class="description">' . $v->introtext . '</div>
							  </div>
							  <div class="col-right col-md-3">
								<div class="price"><span class="price-new">' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span>' . ($v->list_price > $v->price ? '<br /><span class="price-old">' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span>' : '') . '</div>' . (!$b && $d->add2cart ? '<div class="order">
								  <input type="hidden" name="product_' . $v->id . '" id="product_' . $v->id . '" value="' . $v->id . '" />
								  <input type="button" id="' . $v->id . '" name="btnAddtoCart" value="Thêm vào giỏ hàng" title="' . $v->title . '" />
								</div>' : '') . '</div>						  
						</div>';
                    }
                }
                else {
                    if ($d->route->name != 'inbrand') {
                        $t1 = $d->route->name;
                        $t2 = $d->route->id;
                        $d->route->name = 'product';
                        $d->route->id = $v->brand;
                        $t3 = $this->getBrand($d);
                        if (!empty($t3) && is_array($t3) && count($t3) == 1) {
                            $brand = $t3[0];
                        }
                        $d->route->name = $t1;
                        $d->route->id = $t2;
                    }
                    $html .= /*
					'<div class="item">
					  <div class="block">
						<div class="border">' .
						  ($v->new ? '<div class="icon-new"></div>' : '') .
						  ($v->list_price > $v->price ? '<div class="icon-sale"></div>' : '') .
						  #'<div class="brand"><a href="#"><img src="example_images/icon_9.png" height="7" alt="#" /></a></div>' .
						  (isset($brand) ? '<div class="brand"><a href="' . $brand->href . '" title="' . $brand->title . '"><img alt="' . $brand->rewrite . '" src="' . $brand->src . '" height="15" title="' . $brand->title . '" /></a></div>' : '') .
						  '<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/300x300' . $v->src . '" title="' . $v->title . '" /></a></div>
						  <div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
						  <div class="g-price"><span class="list-price">' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span>' . ($v->list_price > $v->price ? '<span class="price">' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span>' : '') . '</div>
						</div>
					  </div>
					</div>';
					*/
                        '<div class="item">
					  <div class="block">
						<div class="border">
						  <div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/250x250' . $v->src . '" title="' . $v->title . '" /></a></div>
						  <div class="proname"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
						  <div class="gr-price">' . ($v->list_price > $v->price ? ' <span class="list-price"><span>' . $this->pricevnd($v->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span> </div>
						</div>
					  </div>
					</div>';
                }
                $i++;
            }
            $html .= ($z ? '</div>
  			  </section>' . str_replace('head_toolbar', 'foot_toolbar', $tb) . $this->buildMenu('m-catalog', $d) . '<div class="desc-cat">
				<h1>' . $d->name . '</h1>' . #'<div class="text">' . (isset($d->desc) ? $d->desc : '') . '</div>' .
                '</div>
			</div><div id="scroll_loading" class="hidden">Đang tải..</div>' . (in_array($d->route->name, [
                    'allproduct',
                    'category',
                ]) ? $this->buildScrollingScript($d) : '') : '</div></section>
			<script type="text/javascript">
			$(function(){$(".normal-imglist").utilCarousel({pagination:!1,navigationText:[\'<i class="icon-left-open-big"></i>\',\'<i class=" icon-right-open-big"></i>\'],navigation:!0,rewind:!1,breakPoints : [[1900, 5], [1200, 5], [992, 3], [768, 2], [480, 2]]})});
			</script>');
        }
        else {
            $html .= '<div class="clear">Sản phẩm hiện đang được cập nhật.</div>';
        }
        return $html;
    }

    function buildToolbar($d, $html = '') {
        switch ($d->route->name) {
            case 'allproduct' :
                $q = ALLPRODUCT_PRODUCT_ITEM;
                break;
            case 'category' :
                $q = CATALOG_PRODUCT_ITEM;
                break;
            case 'search' :
                $q = SEARCH_PRODUCT_ITEM;
                break;
            case 'brand' :
                $q = ($d->route->type == 'detail' ? BRAND_PRODUCT_ITEM : BRAND_ITEM);
                break;
            default:
                $q = ALLPRODUCT_PRODUCT_ITEM;
                break;
        }
        $e = PAGE * $q;
        $t = $d->toolbar->total;
        $title = ($d->route->name == 'brand' && $d->route->type == 'all' ? 'Thương hiệu' : ($d->route->name == 'supplier' && $d->route->type == 'all' ? 'Nhà cung cấp' : 'Sản phẩm'));
        $html = '<div id="head_toolbar" class="toolbars">
		  <div class="pager">
			<div class="left col-md-3 visible-lg visible-md">' . $title . ' từ ' . ((PAGE - 1) * $q + 1) . ' - ' . ($e > $t ? $t : $e) . ' / [<span class="b">' . $t . '</span> ' . $title . ']</div>
			<div class="right col-md-9 col-sm-12 col-xs-12">' . (isset($d->toolbar->page) ? $d->toolbar->page : '') . '</div>
		  </div>';
        if (PRODUCT_SORTING && isset($d->PRODUCT_SORTING) && !$d->toolbar->brand && !$d->toolbar->supplier && $d->route->name != 'search') {
            $html .= '<div id="' . $this->buildToolbarID($d) . '" class="sorter">
				<div class="views col-md-0 col-sm-0 col-xs-0">Xem dạng: 
				  <a rel="nofollow" id="PRODUCT_DISPLAY_GRID" class="grid display' . ($d->display == 'PRODUCT_DISPLAY_GRID' ? ' current active_grid' : '') . '" href="javascript:;" title="Dạng ô lưới">Dạng ô lưới</a>&nbsp;
				  <a rel="nofollow" id="PRODUCT_DISPLAY_LIST" class="list display' . ($d->display == 'PRODUCT_DISPLAY_LIST' ? ' current active_list' : '') . '" href="javascript:;" title="Dạng danh sách">Dạng danh sách</a></div>
				<div class="sort col-md-12 col-sm-12 col-xs-12">
				  <select name="sorting" id="sorting">';
            foreach ($d->PRODUCT_SORTING as $k => $v) {
                $html .= '<option value="' . $k . '"' . (isset($d->seleted) && strtoupper($d->seleted) == $k ? ' selected="selected"' : '') . '>' . $v['title'] . '</option>';
            }
            $html .= '</select>
				</div>
			  </div>';
        }
        $html .= '</div>';
        return $html;
    }

    function buildToolbarID($d, $id = '') {
        switch ($d->route->name) {
            case 'allproduct' :
                switch ($d->route->type) {
                    case 'new':
                        $id = $d->route->id . '_' . 'NEWPRODUCT' . '_' . $d->route->name;
                        break;
                    case 'top':
                        $id = $d->route->id . '_' . 'TOPPRODUCT' . '_' . $d->route->name;
                        break;
                    case 'hot':
                        $id = $d->route->id . '_' . 'HOTPRODUCT' . '_' . $d->route->name;
                        break;
                    case 'promo':
                        $id = $d->route->id . '_' . 'PROMOPRODUCT' . '_' . $d->route->name;
                        break;
                    case 'favorite':
                        $id = $d->route->id . '_' . 'FAVORITEPRODUCT' . '_' . $d->route->name;
                        break;
                    case 'featured':
                        $id = $d->route->id . '_' . 'FEATUREDPRODUCT' . '_' . $d->route->name;
                        break;
                    default:
                        $id = $d->route->id . '_' . 'ALLPRODUCT' . '_' . $d->route->name;
                        break;
                }
                break;
            case 'brand' :
                switch ($d->route->type) {
                    case 'detail':
                        $id = $d->route->id . '_' . $d->rewrite . '_' . $d->route->name;
                        break;
                    default:
                        $id = $d->route->id . '_' . 'ALLBRAND' . '_' . $d->route->name;
                        break;
                }
                break;
            default:
                $id = $d->route->id . '_' . $d->rewrite . '_' . $d->route->name;
                break;
        }
        if (!empty($id)) {
            $id = strtoupper($id . '_PAGE_' . $d->PageNo);
        }
        return $id;
    }

    function buildNews($d, $_LNG, $html = '') {
        #$this->printr($d);
        $d->PageNo = PAGE;
        $d->PageUrl = $d->url;
        $d->Pagenumber = PAGE_NUMBER;
        $d->ModePaging = MODE_PAGING;
        $a = $this->getNews($d);
        if (isset($a['list']) && is_array($a['list']) && count($a['list'])) {
            switch ($d->route->name) {
                case 'MAYBE_INTERESTED' :
                    $html = '<div class="related">
					  <div class="title title-grey">Có thể bạn quan tâm</div>
					  <ul class="">';
                    foreach ($a['list'] as $k => $v) {
                        $html .= '<li><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a> (' . date('d/m', strtotime($v->created)) . ')</li>';
                    }
                    $html .= '</ul>
					</div>';
                    break;
                case 'related2' :
                    $html = '<div id="c-same">
					  <div class="s-title">' . $_LNG->cms->others->title . '</div>
					  <div class="s-list">
						<ul>';
                    foreach ($a['list'] as $k => $v) {
                        $html .= '<li><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></li>';
                    }
                    $html .= '</ul></div></div>';
                    break;
                case 'related' :
                    $html = '<div class="other-news" style="clear:both">
					  <div class="title box-title title-grey">Tin khác</div>
					  <div id="other-news" class="util-carousel top-nav-box">';
                    foreach ($a['list'] as $k => $v) {
                        $html .= '<div class="item"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->alt . '" src="/thumb/210x150/1.4:1' . $v->src . '" title="' . $v->title . '" /><span>' . $v->name . '</span></a></div>';
                    }
                    $html .= '</div>
					  <script type="text/javascript" src="' . JS_PATH . 'viewer.min.js" ></script>
					  <script type="text/javascript" src="' . JS_PATH . 'script.viewer.js" ></script>
					  <script> $(function() { $("#other-news").utilCarousel({ navigation : true, navigationText : [\'<i class="icon-left-open-big"></i>\', \'<i class="icon-right-open-big"></i>\'], breakPoints : [[1900, 5], [1200, 4], [992, 3], [768, 2], [480, 1]] }); }); </script>
					</div>';
                    break;
                case 'home' :
                    $html .= '<section class="last-news">';
                    $html .= mb_convert_encoding($d->footer_html, "UTF-8");
                    $html .= '</section>';

                    $html .= '<section class="last-news">
					  <div class="title mb10 box-line"><span>Tin mới</span></div>';
                    foreach ($a['list'] as $k => $v) {
                        $html .= '<div class="block col-md-3 col-sm-6 col-xs-6 wow fadeInUp animated" data-wow-delay="0ms">
							<div class="view view-fifth">
						  		<div class="picture"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="' . $v->src . '" title="' . $v->title . '" /></a></div>
							</div>
						  	<div class="name"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
						  	<div class="description">' . strip_tags($v->introtext) . '</div>						  
						</div>';
                    }
                    $html .= '</section>';
                    break;
                case 'tag' :
                case 'cms' :
                    switch ($d->display) {
                        case 'NEWS' :
                        case 'LIST' :
                            #$this->printr($a);
                            if (!isset($a['catalog'])) {
                                $html .= '<div id="news" class="row">
								  <div id="content">
									<div id="t-list" class="top">';
                                $html .= '<div id="list-view" class="col-md-9">' . ($d->route->name == 'tag' ? '<h1>' . $d->route->id . ', thông tin hình ảnh video về ' . $d->route->id . '</h1>' : '');
                                foreach ($a['list'] as $k => $v) {
                                    $html .= '<div class="block">						
									  <div class="t-list-pic-s col-md-3"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/300x300/2:1.5' . $v->src . '" title="' . $v->title . '" /></a></div>
									  <div class="t-list-content-s col-md-9">                      		  
										<h2 class="t-list-title"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></h2>
										<div class="t-list-date"><span class="time">' . date('H:i', $v->created) . ' </span>| ' . date('d/m/Y', $v->created) . '</div>' . '<div class="t-list-desc visible-lg visible-md">' . (!empty($v->introtext) ? $v->introtext : $this->takeShortText(strip_tags($v->content), 100)) . '</div>' . '</div>									  
									</div>';
                                }
                                $html .= '</div><div class="col-md-3 dyn-html-list">' . (isset($d->html) ? $d->html : '') . '</div>';
                                if (isset($a['page']) && !empty($a['page'])) {
                                    $html .= '<div id="pagination">' . $a['page'] . '</div>';
                                }
                                $html .= '</div></div></div>';
                            }
                            else {
                                #$this->printr($a['catalog']);
                                $f = 0;
                                if ($a['total'] >= 4) {
                                    $f = 1;
                                    $n1 = $a['list'][0];
                                    $n2 = $a['list'][1];
                                    $n3 = $a['list'][2];
                                    $n4 = $a['list'][3];
                                }
                                $html = '<div class="clearfix news-list" itemprop="mainContentOfPage" itemtype="http://schema.org/WebPageElement" itemscope="itemscope">' . ($f ? '<div class="news-list-top">
									<div class="col-md-6">
									  <div class="view view-fifth"><div class="entry feature" style="background-image:url(' . $n1->src . ')"> <a href="' . $n1->href . '" title="' . $n1->title . '"> <span class="overlay"></span><h2 class="subject" itemprop="headline">' . $n1->name . '</h2></a>
										<div class="date">
										  <meta class="entry-published updated" content="' . date("Y-m-d\TH:i:sO", $n1->created) . '" itemprop="datePublished" title="' . date("l, F d, Y, g:i a", $n1->created) . '">
										  <meta itemprop="dateModified" content="' . date("Y-m-d\TH:i:sO", $n1->modified) . '">' . date('d/m/Y', $n1->created) . '</div>
									  </div>
									  </div>
									</div>
									<div class="col-md-6 feature">
									  <div class="clear">
										<div class="view view-fifth"><div class="entry feature-2" style="background-image:url(' . $n2->src . ')"> <a href="' . $n2->href . '" title="' . $n2->title . '"><span class="overlay"></span><h2 class="subject" itemprop="headline">' . $n2->name . '</h2></a>
										  <div class="date">
											<meta class="entry-published updated" content="' . date("Y-m-d\TH:i:sO", $n2->created) . '" itemprop="datePublished" title="' . date("l, F d, Y, g:i a", $n2->created) . '">
											<meta itemprop="dateModified" content="' . date("Y-m-d\TH:i:sO", $n2->modified) . '">' . date('d/m/Y', $n2->created) . '</div>
										</div>
										</div>
									  </div>
									  <div class="clear row">
										<div class="col-md-6">
										  <div class="view view-fifth"><div class="entry feature-3" style="background-image:url(' . $n3->src . ')"> <a href="' . $n3->href . '" title="' . $n3->title . '"><span class="overlay"></span><h2 class="subject" itemprop="headline">' . $n3->name . '</h2></a>
											<div class="date">
											  <meta class="entry-published updated" content="' . date("Y-m-d\TH:i:sO", $n3->created) . '" itemprop="datePublished" title="' . date("l, F d, Y, g:i a", $n3->created) . '">
											  <meta itemprop="dateModified" content="' . date("Y-m-d\TH:i:sO", $n3->modified) . '">' . date('d/m/Y', $n3->created) . '</div>
										  </div>
										  </div>
										</div>
										<div class="col-md-6">
										  <div class="view view-fifth"><div class="entry feature-3" style="background-image:url(' . $n4->src . ')"> <a href="' . $n4->href . '" title="' . $n4->title . '"><span class="overlay"></span><h2 class="subject" itemprop="headline">' . $n4->name . '</h2></a>
											<div class="date">
											  <meta class="entry-published updated" content="' . date("Y-m-d\TH:i:sO", $n4->created) . '" itemprop="datePublished" title="' . date("l, F d, Y, g:i a", $n4->created) . '">
											  <meta itemprop="dateModified" content="' . date("Y-m-d\TH:i:sO", $n4->modified) . '">' . date('d/m/Y', $n4->created) . '</div>
										  </div>
										  </div>
										</div>
									  </div>
									</div>
								  </div>' : '');
                                if (count($a['catalog'] > 0)) {
                                    foreach ($a['catalog'] as $v) {
                                        #$this->printr($v);
                                        $c = $v['item'];
                                        $n1 = $v['list'][0];
                                        $d->route->id = $n1->id;
                                        $cmt = $this->countComment($d);
                                        $html .= '<div class="news-list-bottom clear">
										  <div class="title col-md-12">
											<div class="br-bt"><span><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a></span></div>
										  </div>
										  <div class="col-md-5">
											<div class="left-post"><div class="view view-fifth"><div class="picture"><a class="thumbnail-link" href="' . $n1->href . '" title="' . $n1->title . '"> <img alt="' . $n1->rewrite . '" src="/thumb/365x260/1.4:1' . $n1->src . '"  width="365" height="260" title="' . $n1->title . '" /> </a></div></div>
											  <h2 class="subject" itemprop="headline"><a rel="bookmark" href="' . $n1->href . '" itemprop="url" title="' . $n1->title . '">' . $n1->name . '</a></h2>
											  <div class="meta">
												<time class="date published" datetime="' . date("Y-m-d\TH:i:sO", $n1->created) . '" itemprop="datePublished" title="' . date("l, F d, Y, g:i a", $n1->created) . '">' . date('d/m/Y', $n1->created) . '</time>' . ($cmt > 0 ? '&nbsp;<span class="comment"><a rel="nofollow" href="' . $n1->href . '#comments" itemprop="discussionURL">' . $cmt . ' bình luận</a></span>' : '') . ' </div>
											  <div class="summary" itemprop="description">
												<p>' . (!empty($n1->introtext) ? $n1->introtext : $this->takeShortText(strip_tags($n1->content), 100)) . '</p>
											  </div>
											</div>
										  </div>
										  <div class="col-md-7">
											<div class="right-post">';
                                        if (count($v['list']) > 1) {
                                            for ($i = 1; $i <= 3; $i++) {
                                                $n2 = $v['list'][$i];
                                                $d->route->id = $n2->id;
                                                $cmt = $this->countComment($d);
                                                $html .= '<div class="small-post"> <a class="thumbnail-link" href="' . $n2->href . '" title="' . $n2->title . '"> <img width="100" height="100" alt="' . $n2->rewrite . '" src="/thumb/120x120/1:1' . $n2->src . '" title="' . $n2->title . '" /> </a>
													  <h2 class="subject" itemprop="headline"><a href="' . $n2->href . '" itemprop="url" title="' . $n2->title . '">' . $n2->name . '</a></h2>
													  <div class="meta">
														<time class="date published" datetime="' . date("Y-m-d\TH:i:sO", $n2->created) . '" itemprop="datePublished" title="' . date("l, F d, Y, g:i a", $n2->created) . '">' . date('d/m/Y', $n2->created) . '</time>' . ($cmt > 0 ? '&nbsp;<span class="comment"><a rel="nofollow" href="' . $n2->href . '#comments" itemprop="discussionURL">' . $cmt . ' bình luận</a></span>' : '') . ' </div>
													  <div class="summary" itemprop="description">
														<p>' . (!empty($n2->introtext) ? $n2->introtext : $this->takeShortText(strip_tags($n2->content), 100)) . '</p>
													  </div>
													</div>';
                                            }
                                        }
                                        $html .= '</div>
										  </div>
										</div>';
                                    }
                                }
                                $html .= '</div>';
                            }
                            break;
                        case 'FIRST' :
                        case 'DETAIL' :
                            $html .= '<div id="news" class="">
							  <div id="content">
								<div id="t-list" class="top">';
                            $v = $a['list'][0];
                            #$this->printr($v);
                            $f = $d->display == 'DETAIL' ? TRUE : FALSE;
                            $html .= '<div class="block"> 
							  <h1 class="c-title">' . $v->name . '</h1>' . $this->buildCmsRating($v) . ($f ? '<div class="c-fulltime">' . $_LNG->cms->posted . ' ' . date('H:i', $v->created) . ', ' . date('d/m/Y', $v->created) . '</div>
							  <div class="c-desc">' . $v->introtext . '</div>' . (!empty($v->html1) ? '<div class="dyn-html-detail">' . $v->html1 . '</div>' : '') : '') . '</div>
							<div class="c-detail">' . $v->content . '</div>' . (!empty($v->html1) ? '<div class="dyn-html-detail">' . $v->html1 . '</div>' : '');
                            $d->route->name = 'MAYBE_INTERESTED';
                            $html .= ($f ? $this->buildNews($d, $_LNG) : '');
                            $d->route->name = 'related';
                            $html .= ($f ? $this->buildNews($d, $_LNG) : '');
                            $v->href = HOST . $v->href;
                            $v->loggedin = $this->chkLoggedin();
                            $v->ctype = 'ARTICLE';
                            $html .= '<div class="clear box-title mt20">Bình luận Facebook:</div>
	  						<div class="fb-comments clear mt10" data-href="' . $v->href . '" data-numposts="10" data-width="100%" data-mobile="Auto-detected" data-order-by="time"></div>' . '<script type="text/javascript">var  ITEM_ID = ' . $v->id . '; var ITEM_TYPE = "ARTICLE"</script>
							<script type="text/javascript" src="' . JS_PATH . 'JS.loadcomment.js"></script>' . '<div class="clear box-title mt20">Bình luận:</div>' . '<div class="comment">' . $this->buildProductComment($v) . '</div>';
                            $html .= '<div class="clear box-title mt20 visible-lg visible-md">Tags:</div>
							<div class="clear">Từ khóa liên quan: ' . $this->buildTag($d->keywords) . '</div>' . '</div></div></div>';
                            break;
                    }
                    break;
                case 'cms2' :
                    $html .= '<div id="news" class="">
  					  <div id="content">
    					<div id="t-list" class="top">';
                    switch ($d->display) {
                        case 'NEWS' :
                        case 'LIST' :
                            $html .= '<div id="list-view" class="display">';
                            foreach ($a['list'] as $k => $v) {
                                $html .= '<div class="block">						
								  <div class="t-list-pic-s col-md-3"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="/thumb/300x300/2:1.5' . $v->src . '" title="' . $v->title . '" /></a></div>
								  <div class="t-list-content-s col-md-9">                      		  
									<div class="t-list-title"><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></div>
									<div class="t-list-date"><span class="time">' . date('H:i', $v->created) . ' </span>| ' . date('d/m/Y', $v->created) . '</div>' . '<div class="t-list-desc">' . (!empty($v->introtext) ? $v->introtext : $this->takeShortText(strip_tags($v->content), 100)) . '</div>' . '</div>
								</div>';
                            }
                            $html .= '</div>';
                            if (isset($a['page']) && !empty($a['page'])) {
                                $html .= '<div id="pagination">' . $a['page'] . '</div>';
                            }
                            break;
                        case 'FIRST' :
                        case 'DETAIL' :
                            $v = $a['list'][0];
                            $f = $d->display == 'DETAIL' ? TRUE : FALSE;
                            $html .= '<div class="block"> 
							  <h1 class="c-title">' . $v->name . '</h1>' . ($f ? '<div class="c-fulltime">' . $_LNG->cms->posted . ' ' . date('H:i', $v->created) . ', ' . date('d/m/Y', $v->created) . '</div>
							  
							  <div class="c-desc">' . $v->introtext . '</div>' : '') . '</div>
							<div class="c-detail">' . $v->content . '</div>';
                            $d->route->name = 'related';
                            $html .= ($f ? $this->buildNews($d, $_LNG) : '');
                            break;
                    }
                    $html .= '</div></div></div>';
                    break;
            }
        }
        return $html;
    }

    function buildBrand($d, $_LNG, $html = '') {
        if ($d->route->name == 'product') {
            $d->route->id = $d->brand;
        }
        $a = $this->getBrand($d);
        if (isset($a) && is_array($a) && count($a)) {
            switch ($d->route->name) {
                case 'home' :
                    $html = '<section class="brand">
					  <h3 class="title">THƯƠNG HIỆU SIMPLE CARRY SẢN XUẤT VÀ PHÂN PHỐI</h3>            
					  <div id="normal-imglist" class="util-carousel normal-imglist">';
                    foreach ($a as $k => $v) {
                        $html .= '<div class="item"><a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="' . $v->src . '" title="' . $v->title . '" /></a></div>';
                    }
                    $html .= '</div></section>';
                    break;
                case 'product' :
                    $v = $a[0];
                    //$html = '<a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="' . $v->src . '" height="15" title="' . $v->title . '" /></a>';
                    $html = '<a href="' . $v->href . '" title="' . $v->title . '">' . $v->title . '</a>';
                    break;
                case 'left' :
                    $html = '<div class="brand visible-md visible-lg mt10">
					  <div class="title">Thương hiệu</div>
					  <ul class="scroll-pane">';
                    $p = !in_array($d->route->tmp, [
                        'category',
                        'allproduct',
                        'search',
                    ]) ? TRUE : FALSE;
                    foreach ($a as $k => $v) {
                        $f = isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand']) && str_replace('+', ' ', $_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand']) == $v->name ? TRUE : FALSE;
                        $t = $this->countProduct($v->id, 'brand');
                        $html .= '<li' . ($f && !$p ? ' class="filter"' : '') . '><a href="' . ($p ? '/san-pham' . EXT : '') . '?brand=' . $v->name . '" title="' . $v->title . '">' . $v->name . '</a>' . ($t > 0 ? ' (' . $t . ')' : '') . ($f && !$p ? '<a title="Xóa bộ lọc" rel="nofollow" class="remove" href="?REMOVE_PRODUCT_BRAND_FILTER">x</a>' : '') . '</li>';
                        $tmp[] = $v->name;
                    }
                    if (isset($tmp) && is_array($tmp) && count($tmp) > 0 && isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand']) && !in_array($_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand'], $tmp)) {
                        $name = $_SESSION['PNSDOTVN_PRODUCT_FILTER']['brand'];
                        $html .= '<li class="filter"><a href="' . ($p ? '/san-pham' . EXT : '') . '?brand=' . str_replace(' ', '+', $name) . '" title="' . $name . '">' . $name . '</a>' . (!$p ? '<a title="Xóa bộ lọc" rel="nofollow" class="remove" href="?REMOVE_PRODUCT_BRAND_FILTER">x</a>' : '') . '</li>';
                    }
                    $html .= '</ul></div>';
                    break;
            }
        }
        return $html;
    }

    function buildSupplier($d, $_LNG, $html = '') {
        if ($d->route->name == 'product') {
            $d->route->id = $d->supplier;
        }
        $a = $this->getSupplier($d);
        if (isset($a) && is_array($a) && count($a)) {
            switch ($d->route->name) {
                case 'product' :
                    $v = $a[0];
                    #$html = '<a href="' . $v->href . '" title="' . $v->title . '"><img alt="' . $v->rewrite . '" src="' . $v->src . '" height="15" title="' . $v->title . '" /></a>';
                    $html = '<a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a>';
                    break;
                case 'left' :
                    $html = '<div class="brand supplier mt10">
					  <div class="title">Nhà cung cấp</div>
					  <ul class="scroll-pane">';
                    $p = !in_array($d->route->tmp, [
                        'category',
                        'allproduct',
                        'search',
                    ]) ? TRUE : FALSE;
                    foreach ($a as $k => $v) {
                        $f = isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier']) && str_replace('+', ' ', $_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier']) == $v->name ? TRUE : FALSE;
                        $t = $this->countProduct($v->id, 'supplier');
                        $html .= '<li' . ($f && !$p ? ' class="filter"' : '') . '><a href="' . ($p ? '/san-pham' . EXT : '') . '?supplier=' . str_replace(' ', '+', $v->name) . '" title="' . $v->title . '">' . $v->name . '</a>' . ($t > 0 ? ' (' . $t . ')' : '') . ($f && !$p ? '<a title="Xóa bộ lọc" rel="nofollow" class="remove" href="?REMOVE_PRODUCT_SUPPLIER_FILTER">x</a>' : '') . '</li>';
                        $tmp[] = $v->name;
                    }
                    if (isset($tmp) && is_array($tmp) && count($tmp) > 0 && isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier']) && !in_array($_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier'], $tmp)) {
                        $name = $_SESSION['PNSDOTVN_PRODUCT_FILTER']['supplier'];
                        $html .= '<li class="filter"><a href="' . ($p ? '/san-pham' . EXT : '') . '?supplier=' . str_replace(' ', '+', $name) . '" title="' . $name . '">' . $name . '</a>' . (!$p ? '<a rel="nofollow" class="remove" href="?REMOVE_PRODUCT_SUPPLIER_FILTER">x</a>' : '') . '</li>';
                    }
                    $html .= '</ul></div>';
                    break;
            }
        }
        return $html;
    }

    function buildHomeCatalog($d, $_LNG, $html = '') {
        $a = $this->getCatalog($d, TRUE);
        if (isset($a) && is_array($a) && count($a)) {
            $i = 1;
            foreach ($a as $k => $v) {
                if (isset($v['catalog']) && isset($v['child']['list']) && is_array($v['child']['list']) && count($v['child']['list'])) {
                    $c = $v['catalog'];
                    $ida = $this->getChildCategory($c->id);
                    $d->ids = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) : $c->id;
                    $html .= '<section class="products mt40">
					  <h2 class="title box-line"><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a></h2>					  
					  <div class="row">
						<div class="col-md-12">
					  	  <div id="normal-imglist-' . $c->rewrite . '" class="util-carousel normal-imglist wow fadeIn animated">';
                    $p = $this->getProduct($d);
                    if (isset($p) && is_array($p) && count($p) > 0) {
                        foreach ($p['list'] as $k2 => $v2) {
                            $html .= '<div class="item">
							  <div class="block">
								<div class="border">' . (!empty($v2->lcolor) && !empty($v2->lname) ? '<span class="label ' . $v2->lcolor . '">' . $v2->lname . '</span>' : '') . '<div class="picture"><a href="' . $v2->href . '" title="' . $v2->title . '"><img alt="' . $v2->rewrite . '" src="/thumb/250x250' . $v2->src . '" title="' . $v2->title . '" /></a></div>
								  <div class="proname"><a href="' . $v2->href . '" title="' . $v2->title . '">' . $v2->name . '</a></div>
								  <div class="gr-price">' . ($v2->list_price > $v2->price ? ' <span class="list-price"><span>' . $this->pricevnd($v2->list_price, $_LNG->product->currency) . '</span></span>' : '') . ' <span class="price"><span>' . ($v2->price > 0 ? $this->pricevnd($v2->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span> </div>' . ($d->add2cart ? '<div class="bottom">
									<div class="order">
									  <input type="hidden" name="product_' . $v2->id . '" id="product_' . $v2->id . '" value="' . $v2->id . '" />
									  <input type="button" id="' . $v2->id . '" name="btnAddtoCart" value="Đặt hàng" title="' . $v2->title . '" />
									</div>
								  </div>' : '') . '</div>
							  </div>
							</div>';
                        }
                    }
                    $html .= '</div>
						</div>
					  </div>
					  <script type="text/javascript">
					  $(function(){$("#normal-imglist-' . $c->rewrite . '").utilCarousel({pagination:!1,navigationText:[\'<i class="icon-left-open-big"></i>\',\'<i class=" icon-right-open-big"></i>\'],navigation:!0,rewind:!1,breakPoints : [[1900, 5], [1200, 5], [992, 3], [768, 2], [480, 2]]})});
					  </script> 
					</section>' . (!empty($c->html) ? '<div class="row"><div class="col-md-12 cat-tag"><span class="overlay"></span>' . $c->html . '</div></div>' : '');
                }
                $i++;
            }
        }
        return $html;
    }

    function buildAdvertising($p, $u, $_LNG, $html = '') {
        $a = $this->getAdvertising($p);
        if (isset($a) && is_array($a) && count($a)) {
            switch ($p) {
                case 'left2' :
                    $html = '<div class="left-block box best-seller mt10 visible-md visible-lg">
					  <div class="title title-dark">Quảng cáo</div>
					  <div class="box col-md-12">
					    <ul class="">';
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<li><a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" title="' . $t . '"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" /></a></li>';
                    }
                    $html .= '</ul>
					  </div>
					</div>';
                    break;
                case 'left' :
                    $html = '<div class="title mt10">Quảng cáo</div>
					<div class="pns-banner">';
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" title="' . $t . '"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" /></a>';
                    }
                    $html .= '</div>';
                    break;
                case 'slide2' :
                    $html = '<section class="feature">
					  <script type="text/javascript" src="/themes/default/js/jquery.flexslider.js"></script>
					  <script type="text/javascript">
					  $(document).ready(function() {
						$(".flexslider").flexslider({ animation: "slide", controlNav: false, directionNav:true, smoothHeight: true, slideshowSpeed: 8000, animationSpeed: 500 });
					  });
					  </script>
					  <link rel="stylesheet" type="text/css" href="/themes/default/css/flexslider.css" media="screen" />
					  <div class="flexslider">
						<ul class="slides">';
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<li><a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" title="' . $t . '"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" /></a></li>';
                    }
                    $html .= '</ul></div></section>';
                    break;
                case 'slide' :
                    $html = '<div class="row">
  					  <div class="col-md-9 col-xs-12">
						<section class="slider mb10">
						  <link rel="stylesheet" href="' . CSS_PATH . 'slippry.css" media="all" />
						  <script type="text/javascript" src="' . JS_PATH . 'slippry.min.js"></script>
						  <div class="">
							<ul id="slider-1">';
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<li><a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" title="' . $t . '"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" /></a></li>';
                    }
                    $html .= '</ul>
						  </div>
						  <script type="text/javascript">
						  jQuery("#slider-1").slippry({slippryWrapper:\'<div class="sy-box portfolio-slider" />\',adaptiveHeight:!1,start:"random",loop:!1,captionsSrc:"li",captions:"custom",captionsEl:".external-captions",transition:"fade",easing:"linear",continuous:!1,auto:!1});
						  </script>
						</section>
					  </div>
					  <div class="col-md-3 col-xs-12 ads-home">' . $this->buildAdvertising('right-home', $u, $_LNG) . '</div>
					</div>';
                    break;
                case 'right-home' :
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" title="' . $t . '" class="col-md-12 col-xs-6 p-0"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" width="100%" /></a>';
                    }
                    break;
                case 'home' :
                    $html = '<section class="banner-main-top-home visible-md visible-lg">
  					  <div class="row">';
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<div class="col-md-4">
						  <div class="box"><a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" class="" title="' . $t . '"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" /></a></div>
						</div>';
                    }
                    $html .= '</div>
					</section>';
                    break;
                case 'partner' :
                    $html = '<section class="partner">
					  <div class="col-md-12">
						<h3 class="title">Đối tác</h3>
						<div id="normal-imglist2" class="util-carousel normal-imglist">';
                    foreach ($a as $k => $v) {
                        $t = str_replace('"', '', stripslashes($v->name));
                        $html .= '<div class="item"><a' . ($v->nofollow ? ' rel="nofollow"' : '') . ' href="' . $v->href . '" target="' . $v->target . '" title="' . $t . '"><img alt="' . $u->generate_url_from_text($v->name) . '" src="' . $v->src . '" title="' . $t . '" /></a></div>';
                    }
                    $html .= '</div>
					  </div>
					  <script src="' . JS_PATH . 'jquery.utilcarousel.min.js"></script> 
					  <script>
					  $(function() {
						  $("#normal-imglist").utilCarousel({
							  pagination : false,
							  navigationText : [\'<i class="icon-left-open-big"></i>\', \'<i class=" icon-right-open-big"></i>\'],
							  navigation : true,
							  rewind : false
						  });
						  $("#normal-imglist2").utilCarousel({
							  pagination : false,
							  responsiveMode : "itemWidthRange",
							  itemWidthRange : [100, 130],
							  navigationText : [\'<i class="icon-left-open-big"></i>\', \'<i class=" icon-right-open-big"></i>\'],
							  navigation : true,
							  rewind : false
						  });
					  });
				  	  </script> 
					</section>';
                    break;
            }
        }
        return $html;
    }

    function buildMenu($p, $d, $lv = 1, $a = '', & $html = '') {
        if ($lv == 1) {
            $a = $this->getMenu($p, $d);
        }
        if (isset($a) && is_array($a) && count($a)) {
            switch ($p) {
                case 'main' :
                    $html .= '<ul>';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<li' . ($lv == 1 && $d->route->id == $c->id ? ' class="active_top"' : '') . '><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a>';
                            if (isset($v['child']) && is_array($v['child']) && count($v['child'])) {
                                $this->buildMenu($p, $d, $lv + 1, $v['child'], $html);
                            }
                            $html .= '</li>';
                        }
                    }
                    $html .= '</ul>';
                    break;
                case 'catalog2' :
                    $html .= '<ul class="' . ($lv == 1 ? 'mobile-sub wsmenu-list' : 'wsmenu-submenu') . '">';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<li><a href="' . $c->href . '" title="' . $c->title . '">' . ($lv > 1 ? '<i class="fa fa-angle-right"></i>' : '') . $c->name . ($lv == 1 ? ' <span class="arrow"></span>' : '') . '</a>';
                            if (isset($v['child']) && is_array($v['child']) && count($v['child'])) {
                                $this->buildMenu($p, $d, $lv + 1, $v['child'], $html);
                            }
                            $html .= '</li>';
                        }
                    }
                    $html .= '</ul>';
                    break;
                case 'catalog' :
                    $html .= '<ul>';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<li><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a>';
                            if (isset($v['child']) && is_array($v['child']) && count($v['child'])) {
                                $this->buildMenu($p, $d, $lv + 1, $v['child'], $html);
                            }
                            $html .= '</li>';
                        }
                    }
                    $html .= '</ul>';
                    break;
                case 'm-catalog' :
                    $html .= ($lv == 1 ? '<section class="main-block visible-xs">
					  <div class="title">Danh mục sản phẩm<span class="cat-button"><i class="fa fa-list" aria-hidden="true"></i></span></div>
					  <script type="text/javascript" src="' . JS_PATH . 'jquery.navgoco.min.js"></script> 
					  <script type="text/javascript">
					  $(document).ready(function() {
						$("#cate").navgoco({accordion: true});
						$(".cat-button").live(\'click\',function () {
						  $("#cate").toggle("slow");
						}); 
					  });
					  </script>
					  <link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'jquery.navgoco.css" media="screen" />
					  <div>' : '');
                    $html .= '<ul' . ($lv == 1 ? ' id="cate" class="nav"' : '') . '>';
                    foreach ($a as $k => $v) {
                        if (isset($v['catalog'])) {
                            $c = $v['catalog'];
                            $html .= '<li><a rel="nofollow" href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a>';
                            if (isset($v['child']) && is_array($v['child']) && count($v['child'])) {
                                $this->buildMenu($p, $d, $lv + 1, $v['child'], $html);
                            }
                            $html .= '</li>';
                        }
                    }
                    $html .= '</ul>';
                    $html .= ($lv == 1 ? '</div></section>' : '');
                    break;
            }
        }
        return $html;
    }

    function buildLocation($l, $pid = '', $id = '', $html = '') {
        $a = $this->getLocation($l, $pid);
        if (is_array($a) && !empty($a) && count($a) > 0) {
            $html = '<select id="' . $l . '" name="' . $l . '" class="select ' . $l . ' hide {validate: {required:true}}">';
            foreach ($a as $b => $c) {
                $html .= '<option value="' . $c->id . '"' . ($c->id == $id ? ' selected="selected"' : '') . '>' . $c->name . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }

    function buildWishlist($d, $_LNG, $html = '') {
        $tmp = $d->route->position;
        $d->route->position = 'member';
        $w = $this->getWishlist($d, $_LNG);
        $d->route->position = $tmp;
        if (!empty($w) && is_array($w) && count($w) > 0) {
            $id = array_keys($w);
            $d->ids = implode(',', $id);
            $a = $this->getWishlist($d, $_LNG);
            if (!empty($a['list']) && is_array($a['list']) && count($a['list']) > 0 && isset($a['list'])) {
                $b = $a['list'];
                $html = '<div id="wishlist">' . '<h1>Sản phẩm yêu thích (' . $a['total'] . ')</h1>' . '<table class="table table-bordered" id="wishlist-table" width="100%" cellspacing="0">' . '<colgroup>' . '<col width="20%" />' . '<col width="44%" />' . '<col width="15%" />' . '<col width="16%" />' . '<col width="5%" />' . '</colgroup>' . '<thead>' . '<tr class="first last">' . '<th>Hình ảnh</th>' . '<th>Sản phẩm</th>' . '<th>Thêm vào ngày</th>' . '<th>Thêm vào giỏ hàng</th>' . '<th>&nbsp;</th>' . '</tr>' . '</thead>' . '<tbody>';
                $i = 1;
                foreach ($b as $c) {
                    $html .= '<tr id="item_' . $c->id . '" class="' . ($i == 1 ? 'first ' : '') . ($i == $a['total'] ? 'last ' : '') . ($i == 2 || $i % 2 == 0 ? 'even' : 'odd') . '">' . '<td class="center"><a href="' . $c->href . '" title="' . $c->title . '"><img alt="' . $c->rewrite . '" src="/thumb/300x300/1:1' . $c->pic . '" width="120" height="120" title="' . $c->title . '" onerror="$(this).css({display:\'none\'})" /></a></td>' . '<td><a href="' . $c->href . '" title="' . $c->title . '"><strong>' . $c->name . '</strong></a>' . '<p class="price">' . ($c->list_price > 0 && $c->list_price > $c->price ? '<span class="list_price">' . $this->pricevnd($c->list_price, $_LNG->product->currency) . '</span>' . ($c->sale_off > 0 ? ' <span class="sale_off">(-' . $c->sale_off . '%)</span>' : '') . '<br />' : '') . '<span class="price">' . ($c->price > 0 ? $this->pricevnd($c->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span>' . '</p>' . '</td>' . '<td class="center">' . date('d/m/Y', $w[$c->id]) . '</td>' . '<td class="center">' . '<input type="hidden" name="product_' . $c->id . '" id="product_' . $c->id . '" value="' . $c->id . '" />' . '<button type="submit" id="' . $c->id . '" name="btnAddtoCart" class="order-now" title="' . $c->title . '"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng</button>' . '</td>' . '<td class="center"><a rel="nofollow" id="' . $c->id . '" class="removeWishlist" href="javascript:;" title="Loại bỏ sản phẩm này">[x]</a></td>' . '</tr>';
                    $i++;
                }
                $html .= '</tbody>' . '<tfoot><tr><td class="pagination" colspan="5">' . (isset($a['page']) && !empty($a['page']) ? $a['page'] : '') . '</td></tr></tfoot>' . '</table>' . '</div>';
            }
        }
        else {
            $html = '<div class="clear">Danh sách yêu thích hiện đang rỗng.</div>';
        }
        return $html;
    }

    function buildOrder($d, $_LNG, $html = '') {
        $html = '<div id="order">';
        if (isset($_GET['VIEW_ORDER_DETAIL']) && isset($_GET['CODE']) && !empty($_GET['CODE'])) {
            $d->oid = $this->checkValues($_GET['CODE']);
            $d->route->position = 'detail';
        }
        else {
            $d->route->position = 'list';
        }
        $a = $this->getOrder($d);
        #$this->printr($a);
        if (!empty($a) && is_array($a) && count($a) > 0) {
            switch ($d->route->position) {
                case 'list' :
                    if (isset($a['total']) && $a['total'] > 0 && isset($a['list']) && count($a['list']) > 0) {
                        $html .= '<h1>' . $_LNG->order->title . '</h1>
						<table class="table table-striped table-bordered" id="my-orders-table">
						  <thead>
							<tr>
							  <th>' . $_LNG->order->code . ' #</th>
							  <th>' . $_LNG->order->ordered . '</th>
							  <th>' . $_LNG->order->to . '</th>
							  <th>' . $_LNG->order->total . '</th>
							  <th>' . $_LNG->order->status->title . '</th>
							  <th>&nbsp;</th>
							</tr>
						  </thead>
						<tbody>';
                        $i = 1;
                        foreach ($a['list'] as $b) {
                            $html .= '<tr class="' . ($i == 1 ? 'first' : '') . ($i == 2 || $i % 2 == 0 ? ' even' : ' odd') . ($i == $a['total'] ? ' last' : '') . '">
							  <td><a href="?VIEW_ORDER_DETAIL&CODE=' . $b->order_code . '" title="' . $_LNG->order->view . '">' . $b->order_code . '</a></td>
							  <td>' . date('d/m/Y', $b->ordered) . '</td>
							  <td>' . stripslashes($b->shipping_name) . '</td>
							  <td><span class="price">' . $this->pricevnd($b->cost, $_LNG->product->currency) . '</span></td>
							  <td><a href="?VIEW_ORDER_DETAIL&CODE=' . $b->order_code . '" title="' . $_LNG->order->view . '"> <em>' . $this->getOrderStatus($b->status) . '</em> </a></td>
							  <td class="last"><a href="?VIEW_ORDER_DETAIL&CODE=' . $b->order_code . '" title="' . $_LNG->order->view . '">' . $_LNG->order->view . '</a></td>
							</tr>';
                            $i++;
                        }
                        $html .= '</tbody></table>';
                        if (isset($a['page']) && !empty($a['page'])) {
                            $html .= '<div id="pagination">' . $a['page'] . '</div>';
                        }
                    }
                    break;
                case 'detail' :
                    if (isset($a['order']) && isset($a['list']) && count($a['list']) > 0) {
                        $b = $a['order'];
                        $html .= '<h1>' . $_LNG->order->detail . ' ' . $b->order_code . ' | ' . $_LNG->order->status->title . ': ' . $this->getOrderStatus($b->status) . '</h1>
						<div id="order-history-' . $b->id . '" class="history">					
						  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="orders_prodcuts">
							<tbody>
							  <tr class="cartTableHeading" style="background-color: #E6E2E2">
							    <th width="50%"><strong>' . $_LNG->checkout->billing_title . '</strong></th>
							    <th width="50%"><strong>' . $_LNG->checkout->shipping_title . '</strong></th>
							  </tr>
							  <tr>
								<td class="left"><strong>' . stripslashes($b->billing_name) . '</strong><br />' . stripslashes($b->billing_address) . '<br />' . $b->billing_ward_name . ', ' . $b->billing_district_name . ', ' . $b->billing_city_name . '<br />' . $b->billing_country_name . '<br />
								T: ' . $b->billing_phone . ' | C: ' . $b->billing_mobile . '</td>
								<td class="left"><strong>' . stripslashes($b->shipping_name) . '</strong><br />' . stripslashes($b->shipping_address) . '<br />' . $b->shipping_ward_name . ', ' . $b->shipping_district_name . ', ' . $b->shipping_city_name . '<br />' . $b->shipping_country_name . '<br />
								T: ' . $b->shipping_phone . ' | C: ' . $b->shipping_mobile . '</td>
							  </tr>
							  <tr class="cartTableHeading" style="background-color: #E6E2E2">
							    <th width="50%"><strong>' . $_LNG->checkout->payment->title . '</strong></th>
							    <th width="50%"><strong>' . $_LNG->checkout->tax->title . '</strong></th>
							  </tr>
							  <tr>
							    <td class="left">' . $b->payment_name . '</td>
							    <td class="left">' . (!empty($b->company) && !empty($b->company_address) && !empty($b->tax_code) ? '<strong>' . stripslashes($b->company) . '</strong><br />' . $_LNG->checkout->tax->address . ': ' . stripslashes($b->company_address) . '<br />' . $_LNG->checkout->tax->code . ': ' . $b->tax_code : '') . '</td>
							  </tr>
							</tbody>
						  </table>
						  <div style="margin:10px 0;"><strong>' . $_LNG->checkout->review->note . ':</strong> ' . stripslashes($b->order_note) . '</div>
							<h3>' . $_LNG->order->list . '</h3>
							<table border="0" width="100%" cellspacing="0" cellpadding="0" class="orders_prodcuts">
							  <tbody>
								<tr class="cartTableHeading" style="background-color: #E6E2E2">
								  <th width="5%"><strong>' . $_LNG->cart->no . '</strong></th>
								  <th scope="col"><strong>' . $_LNG->cart->itemname . '</strong></th>
								  <th width="17%"><strong>' . $_LNG->cart->unitprice . '</strong></th>
								  <th width="10%"><strong>' . $_LNG->cart->qty . '</strong></th>
								  <th width="17%"><strong>' . $_LNG->cart->unittotal . '</strong></th>
								</tr>';
                        $i = 1;
                        foreach ($a['list'] as $c) {
                            $id = explode('|', $c->product_id);
                            $d = unserialize($c->info);
                            #$c = $this->getColorbyCode($id[2], $d->code);
                            #$n = $this->getProductName($id[0], $d->code);
                            $html .= '<tr>
							  <td class="right">' . $i . '</td>
							  <td class="left"><strong>' . (!empty($n) ? $n : stripslashes($c->name)) . '</strong>
								<div style="font-size: 11px; margin-top: 5px; line-height: 15px;">' . (!empty($d['code']) ? '<label>' . $_LNG->cart->code . ':</label> ' . stripslashes($d['code']) . '<br />' : '') . (isset($id[2]) && !empty($id[2]) ? '<label>' . $_LNG->others->color . ':</label> ' . $d['color'] : '') . '</div>
							  </td>
							  <td class="center"><div class="fl pl_20"><span class="fl">' . $this->pricevnd($c->price, $_LNG->product->currency) . '</span></div></td>
							  <td>x' . $c->quantity . '</td>
							  <td align="right">' . $this->pricevnd($c->quantity * $c->price, $_LNG->product->currency) . '</td>
							</tr>';
                            $i++;
                        }
                        $html .= '</tbody></table>' . ($b->point > 0 && $b->point_amount > 0 ? '<div id="point_ottotal" class="clear"><div class="totalBox larger forward"><span id="point_total">-' . $this->pricevnd($b->point_amount, $_LNG->product->currency) . '</span></div><div class="lineTitle larger forward">Sử dụng điểm tặng (' . $b->point . ' điểm):</div></div>' : '') . '<div id="ottotal" class="clear"><div class="totalBox larger forward"><span id="total">' . $this->pricevnd($b->cost, $_LNG->product->currency) . '</span></div><div class="lineTitle larger forward">' . $_LNG->checkout->grand_total . ':</div></div>				
						</div>';
                    }
                    break;
            }
        }
        $html .= '</div>';
        return $html;
    }

    function buildAllcatalog($d, $lv = 1, $a = '', & $html = '') {
        if ($lv == 1) {
            $a = $this->getCatalogMenu($d->code);
        }
        if (isset($a) && is_array($a) && count($a)) {
            $html .= ($lv == 1 ? '<div class="col-md-12">
			  <script type="text/javascript">
$(document).ready(function(){$("#pinBoot").pinterest_grid({no_columns:4,padding_x:10,padding_y:10,margin_bottom:50,single_column_breakpoint:700})}),function(a,b,c,d){function j(b,c){this.element=b,this.options=a.extend({},f,c),this._defaults=f,this._name=e,this.init()}var g,h,i,e="pinterest_grid",f={padding_x:10,padding_y:10,no_columns:3,margin_bottom:50,single_column_breakpoint:700};j.prototype.init=function(){var d,c=this;a(b).resize(function(){clearTimeout(d),d=setTimeout(function(){c.make_layout_change(c)},11)}),c.make_layout_change(c),setTimeout(function(){a(b).resize()},500)},j.prototype.calculate=function(c){var d=this,f=0,j=a(this.element);j.width();h=a(this.element).children(),i=c===!0?j.width()-d.options.padding_x:(j.width()-d.options.padding_x*d.options.no_columns)/d.options.no_columns,h.each(function(){a(this).css("width",i)}),g=d.options.no_columns,h.each(function(b){var e,h=0,j=0,k=a(this),l=k.prevAll();e=c===!1?b%g:0;for(var n=0;n<g;n++)k.removeClass("c"+n);b%g===0&&f++,k.addClass("c"+e),k.addClass("r"+f),l.each(function(b){a(this).hasClass("c"+e)&&(j+=a(this).outerHeight()+d.options.padding_y)}),h=c===!0?0:b%g*(i+d.options.padding_x),k.css({left:h,top:j})}),this.tallest(j),a(b).resize()},j.prototype.tallest=function(b){for(var c=[],d=0,e=0;e<g;e++){var f=0;b.find(".c"+e).each(function(){f+=a(this).outerHeight()}),c[e]=f}d=Math.max.apply(Math,c),b.css("height",d+(this.options.padding_y+this.options.margin_bottom))},j.prototype.make_layout_change=function(c){a(b).width()<c.options.single_column_breakpoint?c.calculate(!0):c.calculate(!1)},a.fn[e]=function(b){return this.each(function(){a.data(this,"plugin_"+e)||a.data(this,"plugin_"+e,new j(this,b))})}}(jQuery,window,document);
</script>
			  <style type="text/css">
			  #pinBoot {
				  position: relative;
				  max-width: 100%;
				  width: 100%;
			  }
			  img {
				  width: 100%;
				  max-width: 100%;
				  height: auto;
			  }
			  .white-panel {
				  position: absolute;
				  background: #f7f7f7;
				  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3);
				  padding: 0px;
				  margin-left:1px;
			  }
			  .white-panel h1 {
				  font-size: 1em;
			  }
			  .white-panel h1 a {
				  color: #A92733;
			  }
			  .white-panel:hover {
				  box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.5);
				  margin-top: -5px;
				  -webkit-transition: all 0.3s ease-in-out;
				  -moz-transition: all 0.3s ease-in-out;
				  -o-transition: all 0.3s ease-in-out;
				  transition: all 0.3s ease-in-out;
			  }
			  #main .store-directory {
				  clear:both;
				  overflow:hidden;
			  }
			  #main .store-directory .title {
				  color:#E47911;
				  font-size:2em;
				  text-transform:uppercase;
				  margin-top:10px;
			  }
			  #main .store-directory h2 {
				  font-size:1.2em;
				  color:#E47911;
				  font-weight:bold;
				  padding:0 10px;
			  }
			  #main .store-directory table {
				  margin-top:20px;
			  }
			  #main .store-directory td {
				  vertical-align:top;
				  width:302px;
			  }
			  #main .store-directory .level-2 {
				  padding:10px;
				  margin-bottom:20px;
			  }
			  #main .store-directory .level-2 li {
				  padding:5px 0;
			  }
			  #main .store-directory .level-2 li a {
				  color:#555;
			  }
			  #main .store-directory .level-2 li a:hover {
				  color:#E47911;
			  }
			  .text-link {
				  clear:both;
				  overflow:hidden;
				  margin-top:20px;
			  }
			  .text-link li {
				  padding:5px 0;
			  }
			  .text-link strong {
				  color:#E47911;
				  font-size:1.1em;
			  }
			  </style>
			  <section id="pinBoot"  class="store-directory">' : '') . ($lv == 2 ? '<div class="level-2">
    			<ul>' : '');
            foreach ($a as $k => $v) {
                if (isset($v['catalog'])) {
                    $c = $v['catalog'];
                    $html .= ($lv == 1 ? '<div class="white-panel">
					  <div><img alt="' . $c->rewrite . '" src="' . $c->picture . '" title="' . $c->title . '" /></div>
					  <h2>' . $c->name . '</h2>' : '') . ($lv == 2 ? '<li><a href="' . $c->href . '" title="' . $c->title . '">' . $c->name . '</a></li>' : '');
                    if (isset($v['child']) && is_array($v['child']) && count($v['child'])) {
                        $this->buildAllcatalog($d, $lv + 1, $v['child'], $html);
                    }
                    $html .= ($lv == 1 ? '</div>' : '');
                }
            }
            $html .= ($lv == 2 ? '</ul></div>' : '') . ($lv == 1 ? '</section>			
			<div class="text-link">' . $this->getTextlink('allcatalog') . '</div>			
			</div>' : '');
        }
        return $html;
    }

    function buildProductAZ($d, $html = '') {
        if (isset($_GET['alphabet']) && in_array($_GET['alphabet'], range('A', 'Z'))) {
            $a = $_GET['alphabet'];
            $html = '<div class="all-products">
			  <h2>Tất cả sản phẩm</h2>
			  <div class="block">
				<ul class="alphabet">';
            foreach (range('A', 'Z') as $c) {
                $html .= '<li><a' . ($a == $c ? ' class="select"' : '') . ' href="?alphabet=' . $c . '" title="' . $c . '">' . $c . '</a></li>';
            }
            $html .= '</ul>
			  </div>';
            $d->url .= '?alphabet=' . $a;
            $d->alphabet = $a;
            $d->PageSize = 100;
            $b = $this->getProductAZ($d);
            if (isset($b['list']) && is_array($b['list']) && isset($b['total']) && $b['total'] > 0) {
                $html .= '<div class="block">
					<dl>
					  <dt>' . $a . '</dt>';
                foreach ($b['list'] as $k => $v) {
                    $html .= '<dd><a href="' . $v->href . '" title="' . $v->title . '">' . $v->name . '</a></dd>';
                }
                $html .= '</dl>
				  </div>' . (isset($b['page']) && !empty($b['page']) ? '<div class="clear">' . $b['page'] . '</div>' : '');
            }
            $html .= '</div>';
        }
        else {
            $html = '<div class="col-md-12"> 
			  <script type="text/javascript"></script>
			  <style type="text/css">
			  #main .alphabet{}
			  #main .alphabet li{
				  float:left;
				  margin:10px 20px;
				  }
			  #main .alphabet li a{
				  color:#005BAB;
				  font-weight:bold;
				  font-size:10em;}
			  </style>
			  <div class="alphabet">
				<ul>';
            foreach (range('A', 'Z') as $c) {
                $html .= '<li><a href="?alphabet=' . $c . '">' . $c . '</a></li>';
            }
            $html .= '</ul>
			  </div>
			</div>';
        }
        return $html;
    }

    /**
     * @param $d
     * @param string $html
     *
     * @return string
     */
    function buildPrevNextProduct($d, $html = '') {
        $a = $this->getPrevNextProduct($d);
        if (isset($a['list']) && count($a['list']) > 2 && isset($a['nav']) && ($c = count($a['nav'])) > 2) {
            $b = array_search($d->product, $a['nav']);
            if ($b > 0 && $b < $c - 1) {
                $prev = $b - 1;
                $next = $b + 1;
            }
            else {
                if ($b == 0) {
                    $prev = $c - 1;
                    $next = $b + 1;
                }
                else {
                    if ($b == ($c - 1)) {
                        $prev = $b - 1;
                        $next = 0;
                    }
                }
            }
            $p = $a['list'][$a['nav'][$prev]];
            $n = $a['list'][$a['nav'][$next]];
            $html = ' | <a href="' . $p->href . '" title="' . $p->title . '">
            <i class="fa fa-step-backward" aria-hidden="true"></i></a> - <a href="' . $n->href . '" title="' . $n->title . '">
            <i class="fa fa-step-forward" aria-hidden="true"></i></a>';
        }
        return $html;
    }

    /**
     * @param $d
     * @param $p
     * @param $_LNG
     * @param string $html
     *
     * @return string
     */
    function buildAlsoBoughtProduct($d, $p, $_LNG, $html = '') {
        $a = $this->getAlsoBoughtProduct($d);
        if (isset($a['list']) && count($a['list']) > 0) {
            $t1 = $t2 = $t3 = '';
            $i = 1;
            foreach ($a['list'] as $k => $v) {
                $src = explode(';', $v->src);
                $t1 .= ',[' . $i . ',' . $v->price . ']';
                $t2 .= '<li id="imgatt_' . $i . '" class="img-pg fl"><a href="' . $v->href . '" title="' . $v->name . '"><img alt="' . $v->rewrite . '" src="' . $src[0] . '" title="' . $v->name . '" border="0" /></a></li>';
                $t3 .= '<p>
                      <input type="checkbox" name="plist[]" value="' . $v->id . '" checked="checked" id="plist_' . $i . '" class="plist" />
                      <span id="spantext_' . $i . '" class="bold">
                        <a href="' . $v->href . '" title="' . $v->name . '">' . $v->name . '</a> - <span class="c-red">' . ($v->price > 0 ? $this->pricevnd($v->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span></p>';
                $i++;
            }
            $html = '<script>var proattarray = [[0,' . $p->price . ']' . $t1 . '];</script>
                <div class="f-b-together mt20 clear">
                  <div class="fbt-title">Sản phẩm mua kèm</div>
                  <div>
                    <ul>
                      <li id="imgatt_0" class="img-pg fl"><a href="' . $p->src . '" title="' . $p->name . '"><img alt="' . $p->rewrite . '" src="' . $p->src . '" title="' . $p->name . '" border="0" /></a></li>' . $t2 . '</ul>
                    <div class=""><strong>Giá bán tất cả:</strong> <span id="sumprice" class="c-red bold">' . ($a['price'] > 0 ? $this->pricevnd($a['price'] + $p->price, '') : $_LNG->contact->title) . '</span> ' . $_LNG->product->currency . '</div>
                    <div class="mt10">
                      <button type="submit" id="btnAlsoBought" name="btnAlsoBought" class="add-all-to-cart" title="Mua tất cả"><i class="fa fa-shopping-cart"></i> Mua tất cả</button>
                    </div>
                  </div>
                  <div id="" class="attlist mt10 clear">
                    <p>
                      <input type="checkbox" name="plist[]" value="' . $p->id . '" checked="checked" id="plist_0" class="plist"  />
                      <span id="spantext_0" class="bold" ><span class="bold">Sản phẩm: </span><a href="' . $p->src . '" title="' . $p->name . '">' . $p->name . '</a> - <span class="c-red">' . ($p->price > 0 ? $this->pricevnd($p->price, $_LNG->product->currency) : $_LNG->contact->title) . '</span></span></p>' . $t3 . '</div>
                </div>';
        }
        return $html;
    }

    function buildAddonFilter($d, $_LNG, $html = '') {
        if (ADDON_FILTER && in_array($d->route->tmp, [
                'allproduct',
                'category',
            ])) {
            if (UPDATE_ADDON_FILTER && isset($d->UPDATE_ADDON) && count($d->UPDATE_ADDON) > 0) {
                $html = '<div id="updated-addon" class="categories visible-md visible-lg mt10">
				  <div class="title beefup__head">Cập nhật</div>
				  <ul class="beefup__body">';
                $p = !in_array($d->route->tmp, [
                    'category',
                    'allproduct',
                    'search',
                ]) ? TRUE : FALSE;
                foreach ($d->UPDATE_ADDON as $k => $v) {
                    $d->day = $v['day'];
                    $t = $this->countProduct($d, 'updated');
                    $f = isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['updated']) && $_SESSION['PNSDOTVN_PRODUCT_FILTER']['updated'] == $k ? TRUE : FALSE;
                    $html .= '<li class="item"><a href="?updated=' . $k . '" title="' . $v['title'] . '">' . $v['title'] . '</a> (<span class="pro-count">' . $t . '</span>)' . ($f && !$p ? '<a title="Xóa bộ lọc" rel="nofollow" class="remove" href="?REMOVE_PRODUCT_UPDATED_FILTER">x</a>' : '') . '</li>';
                }
                $html .= '</ul>
				</div>';
            }
            if (SALEOFF_ADDON_FILTER && isset($d->SALEOFF_ADDON) && count($d->SALEOFF_ADDON) > 0) {
                $html .= '<div id="saledoff-addon" class="categories visible-md visible-lg mt10">
				  <div class="title beefup__head">Giảm giá</div>
				  <ul class="beefup__body">';
                $p = !in_array($d->route->tmp, [
                    'category',
                    'allproduct',
                    'search',
                ]) ? TRUE : FALSE;
                foreach ($d->SALEOFF_ADDON as $k => $v) {
                    $d->fsale = $v['from'];
                    $d->tsale = $v['to'];
                    $t = $this->countProduct($d, 'saleoff');
                    $f = isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']['saleoff']) && $_SESSION['PNSDOTVN_PRODUCT_FILTER']['saleoff'] == $k ? TRUE : FALSE;
                    $html .= '<li class="item"><a href="?saleoff=' . $k . '" title="' . $v['title'] . '">' . $v['title'] . '</a> (<span class="pro-count">' . $t . '</span>)' . ($f && !$p ? '<a title="Xóa bộ lọc" rel="nofollow" class="remove" href="?REMOVE_PRODUCT_SALEOFF_FILTER">x</a>' : '') . '</li>';
                }
                $html .= '</ul>
				</div>';
            }
        }
        return $html;
    }

    function buildTag($k, $html = '') {
        if (!empty($k)) {
            $i = explode(',', $k);
            if (is_array($i) && ($c = count($i)) > 0) {
                for ($j = 0; $j < $c; $j++) {
                    $i[$j] = trim($i[$j]);
                    $t[] = '<a href="' . (MULTI_LANG ? DS . $d->code2 : '') . DS . 'tag' . DS . str_replace(' ', '+', $i[$j]) . EXT . '" title="' . $i[$j] . '" rel="tag nofollow">' . $i[$j] . '</a>';
                }
                if (isset($t) && is_array($t) && count($t) > 0) {
                    $html = implode(', ', $t);
                }
            }
        }
        return $html;
    }

    /**
     * @return string
     * @throws \Exception
     */
    function buildSaleAjax() {
        $conditions = [];
        $conditions[] = [
            'field' => 'sale_ajax',
            'type' => '=',
            'value' => 1
        ];
        $conditions[] = [
            'field' => 'outofstock',
            'type' => '=',
            'value' => 0
        ];
        $orderBys = [];
        $orderBys[] = [
            'field' => 'modified',
            'type' => 'desc',
        ];
        $productSale = $this->selectData(prefixTable . 'product', $conditions, $orderBys, 8, 0);
        $totalproductSale = $this->selectData(prefixTable . 'product', $conditions, $orderBys);
        $html = '<section class="products mt40">';
        $html .= '<h2 class="title box-line"><a href="/sale.html" >Giảm giá</a></h2>';
        $html .= '<div class="content">';
        if ($productSale) {
            foreach ($productSale as $product) {
                $conditions = [
                    [
                        'field' => 'id',
                        'type'  => '=',
                        'value' => $product['id'],
                    ],
                ];
                $pDesc      = $this->selectData(prefixTable . 'product_desc', $conditions);
                if ($pDesc) {
                    $pDesc = reset($pDesc);
                    $pDesc = $pDesc['rewrite'];
                    $href  = '/san-pham/' . $pDesc . '-' . $product['id'] . '.html';
                }
                $imgUrl = explode(';', $product['picture']);
                $imgUrl = reset($imgUrl);

                $html .= '<div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">';
                $html .= '<div class="border box-img">';
                $html .= '<div class="picture">';
                $html .= '<a href="'.$href.'" title="'.$product['name'].'">';
                $html .= '<img alt="'.$product['name'].'" src="'.$imgUrl.'" title="'.$product['name'].'"></a>';
                $html .= '</div>';
                $html .= '<div class="proname"><a href="'.$href.'" title="'.$product['name'].'">'.$product['name'].'</a></div>';
                $html .= '<div class="gr-price">
                            <span class="list-price"><span>'.number_format($product['list_price']).' đ</span></span>
                            <span class="price"><span>'.number_format($product['price']).' đ</span></span>
                        </div>';
                $html .= '</div></div>';
            }
        }
        if(count($totalproductSale) > 8) {
            $html .= '<div class="text-center" style="margin:3em;">';
            $html .= '<a class="btn btn-primary" href="/sale.html">';
            $html .= 'Xem thêm</a';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</section>';

        return $html;
    }

    /**
     * @return string
     * @throws \Exception
     */
    function buildListCategory() {
        $sql = "SELECT DISTINCT cid FROM " . prefixTable . "product WHERE sale_ajax = 1";
        $categories = $this->executeSql($sql, TRUE);
        $html = '<div class="categories is-open">';
        $html .= '<div class="title">Danh mục</div>';
        $html .= '<div id="sale-hot" class="row"><div class="span12 pagination-centered">';
              foreach ($categories as $cate) {
                  $conditions = [];
                  $conditions[] = [
                      'field' => 'id',
                      'type'  => '=',
                      'value' => $cate['cid']
                  ];
                  $category = $this->selectData(prefixTable . 'category', $conditions);
                  $category = reset($category);
                  $html .= '<div class="checkbox">
                            <label><input type="checkbox" value="'.$cate['cid'].'">'.$category['name'].'</label>
                          </div>';
              }
        $html.='</div></div>';
        $html .= '</div>';
        return $html;
    }
}

$pns = new PNS_DOT_VN();