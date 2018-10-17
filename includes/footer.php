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
	'<footer id="footer-wrapper">
	  <div class="subscribe">
		<div class="container">
		  <div class="row">
			<div class="col-md-3 visible-lg visible-md">ĐĂNG KÝ NHẬN TIN</div>
			<div class="col-md-6 visible-lg visible-md">
			  <form id="frmRegNewsletter" name="frmRegNewsletter" action="/xu-ly-dang-ky-nhan-tin" method="post" enctype="application/x-www-form-urlencoded" class="f-sub">
				<input type="text" name="newsletter" id="newsletter" class="input-text" maxlength="100" placeholder="Nhập E-mail của bạn" />
				<button name="btnNewsletter" id="btnNewsletter" class="btn-sub">Đăng ký</button>
			  </form>
			</div>
			<div class="col-md-3 col-xs-12 visible-lg visible-md"><i class="fa fa-phone-square" aria-hidden="true"></i> <a href="tel:+84988753595" target="_blank" onclick="_gaq.push([\'_trackEvent\', \'Contact\', \'Call Now Button\', \'Phone\']);">' . (isset($def->hotline1) && !empty($def->hotline1) ? $def->hotline1 : '') . '</a></div>
		  </div>
		</div>
	  </div>
	  <div class="container">    
		<div id="footer" class="row">
		  <div class="about-us col-md-12">
			<div class="row">
			  <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInUp animated visible-lg visible-md" data-wow-delay="0ms">' . (isset($def->footer1) && !empty($def->footer1) ? $def->footer1 : '') . '</div>
			  <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInRight animated visible-lg visible-md" data-wow-delay="0ms">
				<div id="social_block" class="social_block block visible-lg visible-md">
				  <h4 class="title_block">Đối tác vận chuyển</h4>
				  <div class="block_content">' . (isset($def->footer3) && !empty($def->footer3) ? str_replace(array('{icon1}', '{icon2}', '{icon3}', '{icon4}', '{icon5}'), array('<i class="fa fa-facebook"></i>', '<i class="fa fa-twitter"></i>', '<i class="fa fa-rss"></i>', '<i class="fa fa-youtube"></i>', '<i class="fa fa-google-plus"></i>'), $def->footer3) : '') . '</div>
				</div>
			  </div>
			</div>
		  </div>
		  <div class="ftmenu">' . (isset($def->footer2) && !empty($def->footer2) ? $def->footer2 : '') . '</div>
		  <div class="pns visible-lg visible-md">Copyright &copy; 2016' . (date('Y') > 2016 ? ' - ' . date('Y') : '') . ' ' . $dbf->upcaseFirst(SITE_NAME) . ' . <a rel="nofollow" href="http://dynweb.vn" title="Thiết kế web" target="_blank">Thiết kế web</a> - <a rel="nofollow" href="https://pns.vn" title="Phuong Nam Solution" target="_blank">Phuong Nam Solution</a>. <a href="https://maytracdia.pns.vn" title="Máy trắc địa" target="_blank">Máy trắc địa</a></div>
		</div>
	  </div>
	</footer>';
}
$pns->showHTML($html);