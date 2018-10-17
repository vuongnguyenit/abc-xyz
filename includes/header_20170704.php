<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$flg = (isset($def->route->name) && !in_array($def->route->name, array('signin', 'checkout')) ? true : false);
$html = '';
if ($flg) {
	$html =
	
	'<header id="header-wrapper" class="">
	  <div class="head-banner visible-md visible-lg">' . (isset($def->banner) && !empty($def->banner) ? '<a href="' . HOST . '" title="hien shop"><img alt="banner-hien-shop" src="' . $def->banner . '" title="hien shop" onerror="$(this).css({display:\'none\'})" /></a>' : '') . '</div>
	  <div class="head-toolbars visible-md visible-lg">
		<div class="container">
		  <div class="row">
			<div class="col-md-4 left">Địa chỉ sắm trực tuyến đáng tin cậy</div>
			<div class="col-md-8 right">' . (isset($def->header) && !empty($def->header) ? $def->header : '') . '</div>
		  </div>
		</div>
	  </div>
	  <div id="header" class="container">
		<div class="row">
		  <div class="banner">
			<div class="logo col-md-3 col-sm-6 col-xs-12">' . (isset($def->logo) && !empty($def->logo) ? '<a href="' . HOST . '" title="hien shop"><img alt="logo-hien-shop" src="' . $def->logo . '" title="hien shop" onerror="$(this).css({display:\'none\'})" /></a>' : '') . '</div>
			<div class="search col-md-6 col-sm-6 col-xs-12">
			  <form id="myform" name="frmSearch" action="/tim-kiem.html" method="post" enctype="application/x-www-form-urlencoded">
				<input type="text" id="keyword" name="keyword" class="search_keyword ac_input" maxlength="128" placeholder="Nhập tên sản phẩm cần tìm" />' .
				$pns->buildCatalogMenu($def->code, 0, 3) .
				'<button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
				<div id="search_autocomplete" class="search-autocomplete"></div>
			  </form>		  		  		  		  		  	  
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 account">' .
			  $pns->buildUserCP($def, $cart, $_LNG) .
			'</div>
		  </div>	  
		</div>	
	  </div>
	  <!--<div class="head-menu">
		<link rel="stylesheet" href="' . CSS_PATH . 'mega-menu.css" type="text/css">
		<link rel="stylesheet" href="' . CSS_PATH . 'ionicons.min.css">
		<script type="text/javascript" src="' . JS_PATH . 'megamenu.js"></script> 
		<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script> 
		<script>window.Modernizr || document.write(\'<script src="' . JS_PATH . 'vendor/modernizr-2.8.3.min.js"><\/script>\')</script>
		<div class="menu-container container">
		  <div class="menu row">' .
			(isset($def->mainmenu) && !empty($def->mainmenu) ? $def->mainmenu : '') .		  
		  '</div>
		</div>
	  </div>-->';
	  
	  
	  

	$html .=  
	'<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400|Lato:400" type="text/css">
	<link rel="stylesheet" href="/themes/default/css/menu-mega-2/plugin/bootstrap/bootstrap.min.css" type="text/css">-->
	<link rel="stylesheet" href="/themes/default/css/menu-mega-2/css/solid-menu.css" type="text/css">
	<div id="wrapper">
	  <section id="nav-section">
		<div class="container-fluid solid-menus" id="solidMenus">
		  <nav class="navbar navbar-default navbar-default-dark no-border-radius no-margin">
			<div class="container-fluid" id="navbar-inner-container">
			  <div class="navbar-header">
				<button type="button" class="navbar-toggle navbar-toggle-left" data-toggle="collapse" data-target="#solidMenu"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand navbar-brand-center" href="#"> E-Retail </a> </div>
			  <div class="collapse navbar-collapse" id="solidMenu">' .
				(isset($def->mainmenu) && !empty($def->mainmenu) ? $def->mainmenu : '') .	
				'<ul class="nav navbar-nav navbar-right">
				  <li class="dropdown p-static" data-animation="fadeIn"> <a class="dropdown-toggle" data-toggle="dropdown" href="#" data-title="Login"><i class="icon-user icn-left visible-sm-inline"></i><span class="hidden-sm"> Đăng nhập </span><i class="icon-caret-down m-marker "></i></a>
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
					</div>
				  </li>
				  <li class="dropdown p-static margin-right-2 margin-0-sm"> <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="hidden-lg hidden-md hidden-sm"> Giỏ hàng </span><i class="icon-cart m-marker" style="margin-left: -3px;"></i></a>' .
					(CART_SYS ? $dbf->buildMiniCart2($def->route, $cart, $_LNG, $def->code2, $def->code) : '') .
				  '</li>
				</ul>
			  </div>
			</div>
		  </nav>
		</div>
	  </section>
	</div>
	<script src="/themes/default/js/JS.login.js" type="text/javascript"></script>
	<!--<script src="/themes/default/css/menu-mega-2/plugin/jquery-1.11.1.min.js" type="text/javascript"></script>-->
	<script src="/themes/default/css/menu-mega-2/plugin/jquery-easing/jquery.easing.js" type="text/javascript"></script>
	<!--<script src="/themes/default/css/menu-mega-2/plugin/bootstrap/bootstrap.min.js" type="text/javascript"></script>
	<script src="/themes/default/css/menu-mega-2/plugin/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>-->
	<script src="/themes/default/css/menu-mega-2/js/solid-menu.js" type="text/javascript"></script>';
	  
	  
	$html .= '</header>';
}
$pns->showHTML($html);