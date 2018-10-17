<?php
/**********----*********/
/* PHUONG NAM SOLUTION */
/**********----*********/

$PageSize = 15;
$PageNo = (isset($_GET['PageNo']) && !empty($_GET['PageNo'])) ? (int) $_GET['PageNo'] : 1;
$Page = (isset($_GET['page']) && !empty($_GET['page'])) ? (int) $_GET['page'] : 1;
$Pagenumber=10;
$ModePaging="Full";

define('PHP','.php');
define('EXT','.html');
define('SITE_NAME','chauruachen.pns.vn');
define('SITENAME','chauruachen.pns.vn');
define('DOMAINNAME','chauruachen.pns.vn');
define('HOST','https://' . $_SERVER['HTTP_HOST']);
define('SHOP_NAME','[chauruachen.pns.vn]');
define('ADMIN_ZONE', HOST . '/_pnsdotvn');
define('THEMES_PATH', '/themes/default/');
define('IMAGES_PATH', THEMES_PATH . 'images/');
define('CSS_PATH', THEMES_PATH . 'css/');
define('JS_PATH', THEMES_PATH . 'js/');
define('ADAY',24*60*60);
define('TIME',time());
define('MAXTIME',5*60);
define('CACHING_EXPIRE',60*60*2);
define('prefixTable','dynw_');
define('YOUR_IP', $_SERVER['SERVER_ADDR']);
define('SEND_MAIL', (YOUR_IP <> '127.0.0.1' ? TRUE : FALSE));
define('TEST_MODE', (YOUR_IP <> '127.0.0.1' ? FALSE : TRUE));
define('PNSDOTVN_TEST_MODE', isset($_GET['PNSDOTVN_TEST_MODE']) ? TRUE : FALSE);
define('TAX_RATE', 10);
define('COOKIE_NAME','PNSDOTVN_AUTH');  
define('COOKIE_TIME', 3600 * 24 * 30);
define('max_quantity',999);
define('MULTI_LANG', FALSE);
define('WATERMARK', FALSE);
define('MEMBER_SYS', TRUE);
define('CHECKOUT_NOT_REG', TRUE);
define('CART_SYS', TRUE);
define('SORTER_TOOL', FALSE);
define('POINT_REWARD', TRUE);
#$arrayLang = array('vi-VN', 'en-US');
$arrayLang = array('vi-VN');
$defaultLang = 'vi-VN';
$defaultLangAdmin = 'vi-VN';
$currentPage = $_SERVER['REQUEST_URI'];

define('ORDER_ITEM_NUMBER', 20);
define('ORDER_PAGE_NUMBER', 10);

$isUpdate=(isset($_GET['update']))?true:false;
$isEdit=(isset($_GET['edit']))?true:false;
$isInsert=(isset($_GET['insert']))?true:false;
$isDelete=(isset($_GET['delete']))?true:false;
$isView=(!$isEdit && !$isInsert && !$isDelete && !$isUpdate)?true:false;
$subInsert=(isset($_POST["subinsert"]))?true:false;
$subUpdate=(isset($_POST["subupdate"]))?true:false;
$disabledControl=(isset($_GET['edit']))?" disabled='disabled' ":"";
$showControl=(isset($_GET['edit']))?"display:''":"display:none";
$hideControl=(isset($_GET['edit']))?"display:none":"display:''";
$status=1;
$position=0;

#$changeState=str_replace("&view","",$changeState);
#$changeState=str_replace("view","",$changeState);
$queryString=$_SERVER['QUERY_STRING'];
$queryString=str_replace("&&","&",$queryString);
$changeState=str_replace("&insert","",$queryString);
$changeState=str_replace("insert","",$queryString);
$changeState=str_replace("&edit","",$changeState);
$changeState=str_replace("edit","",$changeState);
$changeState=str_replace("&delete","",$changeState);
$changeState=str_replace("delete","",$changeState);

$pageCurrent=$_SERVER['PHP_SELF'];
$mangpage=explode("/",$pageCurrent);
$pageAdmin=$_SERVER["REQUEST_URI"];

$statusAction=array("1"=>"<img style='position:relative;left:18px;' src='images/tick.png' border='0' />","0"=>"<img style='position:relative;left:18px;' src='images/publish_x.png' border='0' />");
$statusChecked=array("1"=>"checked='checked'","0"=>"");
$statusChecked2=array("1"=>"checked","0"=>"");

define("ALPHA8",' style="cursor:pointer"');
define("ALPHA5",' style="cursor:pointer"');
define("ALPHA9",' style="cursor:pointer"');
define("mouse","style='cursor:pointer' onmouseover=\"this.style.backgroundColor='';\" onmouseout=\"this.style.backgroundColor='';\"");
define("pathPicture","..");
define("defaultPicture","../media/images/others/imagehere.png");
$picture="/media/images/others/imagehere.png";

define('LIMIT_USER_PRODUCT_WIDTH', true);
define('MAX_USER_PRODUCT_IMAGE_WIDTH', 1280);
define('LIMIT_USER_PRODUCT_HEIGHT', true);
define('MAX_USER_PRODUCT_IMAGE_HEIGHT', 1280);
#define('THUMBNAIL_USER_PRODUCT_IMAGE_WIDTH', 80);
define('PREFIX_IMAGE_NAME', 'chauruachen-pns-vn');

define("insertIMG","<a  href='?".$changeState."&insert'><img ".ALPHA5." src='images/new.jpg' border='0' title='Thêm' /></a>");
define("insertText","<a id='lnkaction' href='?".$changeState."&insert'>Thêm</a>");

define("selectIMG","<a href='#' onclick=\"docheck(!document.frm.chkall.checked,2);\"><img ".ALPHA5." src='images/trash.jpg' border='0' title='Thêm' /></a>");
define("selectText","<a id='lnkaction' onclick=\"docheck(!document.frm.chkall.checked,2);\" href='#'>Chọn</a>");

define("deleteIMG","<a href='#' onclick=\"return deleteCommon('?".$queryString."');\" ><img ".ALPHA5." src='images/delete.jpg' border='0' title='Xóa' /></a>");
define("deleteText","<a id='lnkaction' href='#' onclick=\"return deleteCommon('?".$queryString."');\">Xóa</a>");

define("viewIMG","<a href='?".$changeState."'><img ".ALPHA5." src='images/view.jpg' border='0' title='Xem' /></a>");
define("viewText","<a id='lnkaction' href='?".$changeState."'>Xem</a>");

define("mouse_over"," style='cursor:pointer;' onmouseover=\"this.style.backgroundColor='#fbfbfb';\" onmouseout=\"this.style.backgroundColor='#ffffff';\"");
define("mouse_over2","  onmouseover=\"this.style.backgroundColor='#f9f9f9';\" onmouseout=\"this.style.backgroundColor='#ffffff';\"");

$arrayBannerType = array("image" => "Dạng hình Logo, Banner", "flash" => "Dạng đồ họa Flash");
$arrayBannerPosition = array(
	'slide' => 'Slide Trang chủ (1170x488px)', 
	'home' => 'Banner Home (375x195px)',
	#'home2' => 'Home 2 (190x240px)',
	'left' => 'Banner Trái (250x250px)',
	'right-home' => 'Banner Phải Trang chủ (277x145px)',
	#'partner' => 'Logo Đối tác (230x120px)',
	#'fleft' => 'Banner Chạy Trái (110x400px)', 
	#'fright' => 'Banner Chạy Phải (110x400px)'
	);
$arrayTextlinkPosition = array(
	'allcatalog' => 'Danh mục sản phẩm', 	
	'topnewhome' => 'SP mới trang chủ (Top)', 	
	'botnewhome' => 'SP mới trang chủ (Bottom)', 	
);	
$arrayTarget = array("_blank" => "Mở cửa sổ mới (_blank)", "_self" => "Mở của sổ tại trang hiện tại (_self)");
$arraySaleOff = array("5" => "5%", "10" => "10%", "15" => "15%", "20" => "20%", "25" => "25%", "30" => "30%", "35" => "35%", "40" => "40%", "45" => "45%", "50" => "50%");
$arrayOnlineType = array('yahoo' => 'Yahoo Messenger', 'skype' => 'Skype');
$arraySale=array(0=>"0",5=>"5%",10=>"10%",15=>"15%",20=>"20%",30=>"30%",40=>"40%",50=>"50%");
$arrayVAT=array(0=>"0",5=>"5%",10=>"10%");
$arrayExt=array(".jpg",".gif",".png");
$arrayErrorFile=array("1"=>"","-1"=>"Hình 1 bắt buộc phải có","2"=>"File upload không thành công","3"=>"File không đúng định dạng GIF, JPG, PNG","4"=>"Kích thước file phải nhỏ 100 KByte","5"=>"Độ rộng hình ít nhất là 475px và cao từ 300px trở lên");
$arrayPaymentName = array('pod' => 'Thanh toán sau khi nhận hàng.',
					  	  'transfer' => 'Chuyển khoản qua tài khoản ngân hàng hoặc ATM.');
$arrayPaymentID = array(1 => 'pod',
					  	2 => 'transfer');						  
#$arraySalutation = array('Mr.' => 'Ông', 'Mrs.' => 'Bà', 'Ms.' => 'Cô');						  
$arrayDisplay = array(
	#'HOME' => 'Trang chủ', 
	#'CONTACT' => 'Liên hệ', 
	'FIRST' => 'Bài viết đầu tiên', 
	'LIST' => 'Danh sách bài viết', 
	'NEWS' => 'Danh sách tin tức',
	#'VIDEO' => 'Danh sách video'
);

#$sorting = array('price-ascending' => 't1.price ASC', 'price-descending' => 't1.price DESC', 'name-ascending' => 't2.name ASC', 'name-descending' => 't2.name DESC', 'created-ascending' => 't1.created ASC', 'created-descending' => 't1.created DESC');
#$arraySort = array('price-ascending' => 'Giá bán: từ thấp đến cao', 'price-descending' => 'Giá bán: từ cao đến thấp', 'name-ascending' => 'Tên sản phẩm: từ A đến Z', 'name-descending' => 'Tên sản phẩm: từ Z đến A', 'created-ascending' => 'Sản phẩm: đăng cũ', 'created-descending' => 'Sản phẩm: mới đăng');
#define("DEFAULT_SORT", $sorting['created-descending']);

$arraySalutation = array('mr.' => 'Ông', 'mrs.' => 'Bà', 'ms.' => 'Cô');

/*
$arrayORDERBY = array(
	'price-ascending' 		=> 't1.price ASC', 
	'price-descending' 		=> 't1.price DESC', 
	'name-ascending' 		=> 't2.name ASC', 
	'name-descending' 		=> 't2.name DESC',	 
	'created-ascending' 	=> 't1.created ASC',
	'created-descending' 	=> 't1.created DESC');
	
$arraySORTING = array(
	'price-ascending' 		=> 'Giá bán: từ thấp đến cao', 
	'price-descending' 		=> 'Giá bán: từ cao đến thấp', 
	'name-ascending' 		=> 'Tên sản phẩm: từ A đến Z', 
	'name-descending' 		=> 'Tên sản phẩm: từ Z đến A', 
	'created-descending' 	=> 'Sản phẩm: mới đăng', 
	'created-ascending' 	=> 'Sản phẩm: đăng cũ');
	
define('DEFAULT_PRODUCT_SORTING', $arrayORDERBY['created-descending']);

$arrayDISPLAY = array(
	'DISPLAY_LIST' => 'DISPLAY_LIST', 
	'DISPLAY_GRID' => 'DISPLAY_GRID');
	
define('DEFAULT_PRODUCT_DISPLAY', $arrayDISPLAY['DISPLAY_GRID']);
*/

#$arraySMTPSERVER = array("host"=>"smtp.gmail.com","user"=>"no-reply@dynwebsite.com","password"=>"AAAaaa123!@#","from"=>"no-reply@" . SITE_NAME,"fromname"=>SHOP_NAME);

/*$arraySMTPSERVER = array(
	'host' => 'smtp.elasticemail.com',
	'user' => 'phuongnamservice@gmail.com',
	'password' => 'e47372cd-3c83-46c2-9b36-8d4068252eef',
	'port' => 2525,
	'secure' => 'tls',
	'auth' => true,
	'from' => 'no-reply@pns.vn',
	//'from' => 'no-reply@' . SITE_NAME,
	'fromname' => SHOP_NAME);*/

$arraySMTPSERVER = array(
	'host' => 'smtp.elasticemail.com',
	'user' => 'service@dynwebsite.com',
	'password' => '8f22d1e7-00bb-4a23-96eb-09d1069c3436',
	'port' => 2525,
	'secure' => 'tls',
	'auth' => true,
	'from' => 'no-reply@dynwebsite.com',
	'fromname' => SHOP_NAME);

/*$arraySMTPSERVER = array(
	'host' => 'sng019.arandomserver.com',
	'user' => 'crc@mx1.pns.vn',
	'password' => 'VkxIN5rUw@,l',
	'port' => 465,
	'secure' => 'ssl',
	'auth' => true,
	'from' => 'no-reply@pns.vn',
	'fromname' => SHOP_NAME);*/

/*$arraySMTPSERVER = array(
	'host' => 'notify.sieuthinhanh.vn',
	'user' => 'no-replay@notify.sieuthinhanh.vn',
	'password' => 'VkxIN5rUw@,l',
	'port' => 465,
	'secure' => 'ssl',
	'auth' => true,
	'from' => 'no-reply@pns.vn',
	'fromname' => SHOP_NAME);*/

/*
$arraySMTPSERVER = array(
	'host' => 'smtp.elasticemail.com',
	'user' => 'hienshop.net@gmail.com',
	'password' => 'e0b0d5eb-62f5-4938-b79a-e989c53c36a4',
	'port' => 2525,
	'secure' => 'tls',
	'auth' => true,
	'from' => 'no-reply@hienshop.net',
	//'from' => 'no-reply@' . SITE_NAME,
	'fromname' => SHOP_NAME);
	
$arrayWarranty = array(
	'03T' => '3 tháng',
	'12T' => '12 tháng',
	'24T' => '24 tháng',
	'36T' => '36 tháng'
);

$arraySource = array(
	'CHG' => 'Chính hãng',
	'CTY' => 'Công ty'
);

$arrayTiviType = array(
	'TV OLED Màn Hình Cong',
	'TV OLED Màn Hình Phẳng',
	'Tivi LED',
	'Tivi 3D',
	'Tivi LCD',
	'Tivi CRT'
);
*/

$arrayOrigin = array(
	'VNM' => 'Việt Nam',
	'KOR' => 'Hàn Quốc',
	'VKR' => 'Việt - Hàn',
	'JPN' => 'Nhật Bản',
	'CHN' => 'Trung Quốc',
	'MYS' => 'Malaysia',
	'USA' => 'Mỹ',
	'CHE' => 'Thụy Sĩ',
	'SGP' => 'Singapore',
	'DEU' => 'Đức'
);

$arrayPriceFilter = array(
	'duoi-200000d' => 'Dưới 200.000 đ',
	'tu-200000d-500000d' => 'Từ 200.000 đ - 500.000 đ',
	'tren-500000d' => 'Trên 500.000 đ'
);

$arrayPriceFilterSQL = array(
	'duoi-200000d' => 't1.price < 200000',
	'tu-200000d-500000d' => 't1.price BETWEEN 200000 AND 500000',
	'tren-500000d' => 't1.price > 500000'
);