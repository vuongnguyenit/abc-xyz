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
			<div class="logo col-md-3 col-sm-6 col-xs-12 visible-lg visible-md">' . (isset($def->logo) && !empty($def->logo) ? '<a href="https://chauruachen.pns.vn" title="chau rua chen gia re"><img alt="chau rua chen gia re" src="' . $def->logo . '" title="chau rua chen gia re" onerror="$(this).css({display:\'none\'})" /></a>' : '') . '</div>
			<div class="search col-md-6 col-sm-6 col-xs-12">
			  <form id="myform" name="frmSearch" action="/tim-kiem.html" method="post" enctype="application/x-www-form-urlencoded">
				<input type="text" id="keyword" name="keyword" class="search_keyword ac_input" maxlength="128" placeholder="Nhập tên sản phẩm cần tìm" />' .
				$pns->buildCatalogMenu($def->code, 0, 3) .
				'<button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
				<div id="search_autocomplete" class="search-autocomplete"></div>
			  </form>		  		  		  		  		  	  
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 account"></div>
		  </div>	  
		</div>	
	  </div>'; 

	$html .=  
	'<link rel="stylesheet" href="/themes/default/css/menu-mega-2/css/solid-menu.css" type="text/css">
	<div id="wrapper">
	  <section id="nav-section">
		<div class="container solid-menus" id="solidMenus">
		  <nav class="navbar navbar-default navbar-dark navbar-static-top no-border-radius no-margin navbar-hover">
			<div class="container" id="navbar-inner-container">
			  <div class="navbar-header">
				<button type="button" class="navbar-toggle navbar-toggle-left" data-toggle="collapse" data-target="#solidMenu"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand navbar-brand-center" href="https://chauruachen.pns.vn" rel="nofollow"><span class="visible-lg visible-md">Home</span><span class="visible-xs mobile-logo"><img alt="logo chau rua chen" src="/themes/default/images/chauruachen.pns.vn_mobile.png" height="20" /></span></a></div>
			  <div class="collapse navbar-collapse" id="solidMenu">' .
				(isset($def->mainmenu) && !empty($def->mainmenu) ? $def->mainmenu : '') .	
				'<ul class="nav navbar-nav navbar-right">
				  <li class="dropdown p-static" data-animation="fadeIn">' . $pns->buildUserCP2($def, $_LNG) . '</li>
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
	<script src="/themes/default/css/menu-mega-2/plugin/jquery-easing/jquery.easing.js" type="text/javascript"></script>	
	<script src="/themes/default/css/menu-mega-2/js/solid-menu.js" type="text/javascript"></script>';
	  
	  
	$html .= '</header>';
}
$pns->showHTML($html);