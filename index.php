<?php
$domain = 'pns.vn'; // chi 1 domain chinh
$domains = 'www.chauruachen.pns.vn;chauruachen.pns.vn'; // cac doamin cach nhau boi dau cham phay ; , khong su dung http://
#$ips = '103.45.230.205'; // cac ip cach nhau boi dau cham phay ;

if (!in_array ($_SERVER['HTTP_HOST'], explode (';', $domains))) {
	header('Location: http://' . $domain);
	exit;	
}

if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
{
   $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}

error_reporting((isset($_GET['PNSDOTVN_TEST_MODE']) ? -1 : 0));
ini_set('display_errors', (isset($_GET['PNSDOTVN_TEST_MODE']) ? TRUE : FALSE));
ini_set('display_startup_errors', (isset($_GET['PNSDOTVN_TEST_MODE']) ? TRUE : FALSE));
ini_set('session.gc_maxlifetime', 60*60*5);
date_default_timezone_set('Asia/Ho_Chi_Minh');
ob_start('ob_gzhandler');

define('DS', DIRECTORY_SEPARATOR);
define('PHUONG_NAM_SOLUTION', TRUE);
define('PNSDOTVN_ROOT', dirname(__FILE__));
define('PNSDOTVN_ADM', PNSDOTVN_ROOT . DS . '_pnsdotvn');
define('PNSDOTVN_INC', PNSDOTVN_ROOT . DS . 'includes');
define('PNSDOTVN_CLS', PNSDOTVN_ROOT . DS . 'class');
define('PNSDOTVN_ACT', PNSDOTVN_INC . DS . 'action');
define('PNSDOTVN_EMA', PNSDOTVN_INC . DS . 'email');
define('PNSDOTVN_LNG', PNSDOTVN_CLS . DS . 'language');
define('PNSDOTVN_CAP', PNSDOTVN_ROOT . DS . 'plugins' . DS . 'captcha');
session_start();
$_route = isset($_GET['route']) && !empty($_GET['route']) ? strtolower($_GET['route']) : '';
switch($_route) {
  case 'xu-ly-dang-ky-tu-van': include_once PNSDOTVN_ACT . DS . 'consultant.php'; exit; break;
  case 'like-comment': 	
  case 'xu-ly-binh-luan': include_once PNSDOTVN_ACT . DS . 'comment.php'; exit; break;	
  case 'search-suggestion.html': include_once PNSDOTVN_ACT . DS . 'autocomplete.php'; exit; break;	
  case 'xu-ly-dang-ky-nhan-tin': include_once PNSDOTVN_ACT . DS . 'newsletter.php'; exit; break;	
  case 'xu-ly-kiem-tra-don-hang': include_once PNSDOTVN_ACT . DS . 'chkorder.php'; exit; break; 	 
  case 'xu-ly-san-pham-yeu-thich': include_once PNSDOTVN_ACT . DS . 'wishlist.php'; exit; break; 
  case 'xu-ly-dang-ky': include_once PNSDOTVN_ACT . DS . 'signup.php'; exit; break;  
  case 'kich-hoat-tai-khoan': include_once PNSDOTVN_ACT . DS . 'activate.php'; exit; break;
  case 'xu-ly-dang-nhap': include_once PNSDOTVN_ACT . DS . 'signin.php'; exit; break;
  case 'xu-ly-quen-mat-khau': include_once PNSDOTVN_ACT . DS . 'forgotpwd.php'; exit; break;
  case 'yeu-cau-gui-mat-khau': include_once PNSDOTVN_ACT . DS . 'newpwd.php'; exit; break;
  case 'dang-xuat': include_once PNSDOTVN_ACT . DS . 'signout.php'; exit; break;
  case 'xu-ly-thong-tin-thanh-vien': include_once PNSDOTVN_ACT . DS . 'member.php'; exit; break;
  case 'xu-ly-doi-mat-khau': include_once PNSDOTVN_ACT . DS . 'changepwd.php'; exit; break;
  case 'tai-du-lieu': include_once PNSDOTVN_ACT . DS . 'getdata.php'; exit; break;
  case 'mua-tat-ca':
  case 'dat-mua':
  case 'cap-nhat-gio-hang':
  case 'xoa-gio-hang':
  case 'xoa-san-pham': include_once PNSDOTVN_ACT . DS . 'cart.php'; exit; break;
  #case 'tinh-thue-gtgt':
  case 'xu-ly-lien-he': include_once PNSDOTVN_ACT . DS . 'contact.php'; exit; break;
  case 'xu-ly-dat-hang': include_once PNSDOTVN_ACT . DS . 'checkout.php'; exit;
  case 'xu-ly-du-lieu': include_once PNSDOTVN_ACT . DS . 'load-data.php'; exit; break;
  case 'captcha-new-session': include_once PNSDOTVN_CAP . DS . 'newsession.php'; exit; break;
  case 'captcha-require': include_once PNSDOTVN_CAP . DS . 'require.php'; exit; break;
  case 'captcha-process': include_once PNSDOTVN_CAP . DS . 'process.php'; exit; break;
  case 'captcha-image': include_once PNSDOTVN_CAP . DS . 'image.php'; exit; break;  
}
require_once PNSDOTVN_ADM . DS . 'defineConst.php';
require_once PNSDOTVN_CLS . DS . 'define.pnsdotvn.php';
require_once PNSDOTVN_CLS . DS . 'class.BUL' . PHP;
$d = $dbf->checkOldLink ($_route);
if (is_array($d) && count($d) == 2 && $d['f'] == true) {
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: " . HOST . DS . (!MULTI_LANG ? str_replace('vi/', '', $d['uri']) : $d['uri']));
	exit;
}
require_once PNSDOTVN_CLS . DS . 'class.pnsdotvn' . PHP;
require_once PNSDOTVN_CLS . DS . 'class.cart' . PHP;
require_once PNSDOTVN_CLS . DS . 'class.utls' . PHP;
require_once PNSDOTVN_CLS . DS . 'class.cookie' . PHP;
$lang = $defaultLang;
if (MULTI_LANG) {
  if(!Cookie::Exists('lang') || Cookie::IsEmpty('lang')) Cookie::Set('lang', $defaultLang);
  else $lang = Cookie::Get('lang');
}
require_once PNSDOTVN_LNG . DS . 'lang.' . $lang . PHP;
$_LNG = $dbf->arrayToObject($lng);
$dbf->queryEncodeUTF8();
$info = $dbf->getConfig($lang);
$def = $dbf->arrayToObject($info);
$def->UPDATE_ADDON = $UPDATE_ADDON;
$def->SALEOFF_ADDON = $SALEOFF_ADDON;
$pns->buildFilter($def, $_GET);
echo 
'<!DOCTYPE html>' ,
'<!--[if IE 7]>' ,
'<html class="ie ie7" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">' ,
'<![endif]-->' ,
'<!--[if IE 8]>' ,
'<html class="ie ie8" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">' ,
'<![endif]-->' ,
'<!--[if !(IE 7) | !(IE 8)  ]><!-->' ,
'<html lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">' ,
'<!--<![endif]-->' ,
'<head itemscope itemtype="http://schema.org/WebPage">';
  (include PNSDOTVN_INC . DS . 'head' . PHP);
echo  
'</head>' ,
'<body>' ;
(include PNSDOTVN_INC . DS . 'newsletter' . PHP) ;
echo 
'<div id="wrapper">' ,  
  '<div id="page-wrapper">' , 
    '<div id="page">';	  
	  (include PNSDOTVN_INC . DS . 'header' . PHP);
	  (include PNSDOTVN_INC . DS . 'main' . PHP);	  
	  (include PNSDOTVN_INC . DS . 'footer' . PHP);
echo 	  
    '</div>' ,
  '</div>' ,
  '<div id="pnsdotvn-notification" style="background-color:#000; color:#FFF; padding:8px; position:fixed; left:30%; top:10%; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; display:none;"></div>' ,
'</div>' ,
#$dbf->buildFloatingBanner(array('width' => 150)) ,
'<a href="#0" class="cd-top">Top</a>' ,
'<div class="ttb-shadow">
  <div class="mobile-call visible-xs"><a href="tel:+84988753595" target="_blank" onclick="_gaq.push([\'_trackEvent\', \'Contact\', \'Call Button Home\', \'Phone\']);"><i class="fa fa-phone-square"></i> Gọi ngay</a></div>
  <div class="zalo-mobile visible-xs"><a href="https://zalo.me/nguyentuanvuong" rel="nofollow noopener noreferrer">Zalo: 0988.75.35.95</a></div>
  <div class="cart-bottom-mobile visible-xs"><a href="/gio-hang.html" rel="nofollow noopener noreferrer"><span class="cbm-icon"><i class="fa fa-shopping-cart"></i></span> <span class="cbm-icon">Giỏ hàng</span></a></div>
</div>' ,
'<!--<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src=\'https://embed.tawk.to/597c8a970d1bb37f1f7a66c5/default\';
s1.charset=\'UTF-8\';
s1.setAttribute(\'crossorigin\',\'*\');
s0.parentNode.insertBefore(s1,s0);
})();
</script>-->',
'<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter50205973 = new Ya.Metrika2({ id:50205973, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/50205973" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->',
'</body>' ,

'</html>' . "\n" ,
'<!--******************************************************************-->' . "\n" ,
'<!--CÔNG TY TNHH GIẢI PHÁP TRỰC TUYẾN PHƯƠNG NAM - PHUONG NAM SOLUTION-->' . "\n" , 
'<!--Địa chỉ: 205/11/15 Phạm Văn Chiêu, Phường 14, Quận Gò Vấp, Tp.HCM--->' . "\n" , 
'<!--Điện thoại: (028) 62.919.777 | Fax: (08) 3589.1574------------------->' . "\n" ,
'<!--Website: pns.vn | Email: info@pns.vn-------------------------------->' . "\n" ,
'<!--Hotline: 0935.777.527 | Y!M: pnsdotvn | Skype: pnsdotvn------------->' . "\n" ,
'<!--Copyright © 2016 chauruachen.pns.vn--------------------------------------->' . "\n" ,
'<!--******************************************************************-->';
ob_end_flush();