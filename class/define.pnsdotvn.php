<?php
define ('PAGE', (isset($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1));
define ('MODE_PAGING', 'Toolbar');
define ('PAGE_NUMBER', 15);
define ('POINT_LIST_ITEM', 20);
define ('ORDER_LIST_ITEM', 8);
define ('HOME_PRODUCT_ITEM', 8);
define ('NEW_PRODUCT_ITEM', 8);
define ('HOT_PRODUCT_ITEM', 8);
define ('VIEWED_PRODUCT_ITEM', 12);
define ('WISHLIST_PRODUCT_ITEM', 10);
define ('CATALOG_PRODUCT_ITEM', 32);
define ('ALLPRODUCT_PRODUCT_ITEM', 32);
define ('PROMO_PRODUCT_ITEM', 32);
define ('FLASHSALE_PRODUCT_ITEM', 32);
define ('SEARCH_PRODUCT_ITEM', 32);
define ('BRAND_PRODUCT_ITEM', 32);
define ('BRAND_ITEM', 32);
define ('SUPPLIER_PRODUCT_ITEM', 32);
define ('SUPPLIER_ITEM', 32);
define ('HOME_ARTICLE_ITEM', 4);
define ('MENU_ARTICLE_ITEM', 10);
define ('RELATED_ARTICLE_ITEM', 5);
define ('MAYBE_INTERESTED_NEWS_ITEM', 10);
define ('INPRODUCT_RELATED_NEWS_ITEM', 15);
define ('PRODUCT_MIN_PRICE', 10000);
define ('PRODUCT_MAX_PRICE', 20000000);
define ('PRODUCT_MIN_PRICE_DEFAULT', 100000);
define ('PRODUCT_MAX_PRICE_DEFAULT', 2000000);
define ('PRODUCT_STEP_PRICE', 100000);
define ('PRODUCT_FILTER', true);
define ('PRODUCT_SORTING', true);
define ('COMMENT_WORD_NUMBER', 100);
$PRODUCT_SORTING = array(
	'PRODUCT_NAME_ASC' => array(
		'title' => 'Tên SP: A - Z',
		'query' => 't2.name ASC'),
	'PRODUCT_NAME_DESC' => array(
		'title' => 'Tên SP: Z - A',
		'query' => 't2.name DESC'),
	'PRODUCT_PRICE_ASC' => array(
		'title' => 'Giá SP: Thấp - Cao',
		'query' => 't1.price ASC'),
	'PRODUCT_PRICE_DESC' => array(
		'title' => 'Giá SP: Cao - Thấp',
		'query' => 't1.price DESC'),
	'PRODUCT_CREATED_DESC' => array(
		'title' => 'Sản phẩm: Mới đăng',
		'selected' => 'selected',
		'query' => 't1.created DESC'),
	'PRODUCT_CREATED_ASC' => array(
		'title' => 'Sản phẩm: Đăng cũ',
		'query' => 't1.created ASC'),
);
define('DEFAULT_PRODUCT_SORTING', $PRODUCT_SORTING['PRODUCT_CREATED_DESC']['query']);
$PRODUCT_DISPLAY = array(
	'PRODUCT_DISPLAY_LIST' => 'PRODUCT_DISPLAY_LIST', 
	'PRODUCT_DISPLAY_GRID' => 'PRODUCT_DISPLAY_GRID');	
define('DEFAULT_PRODUCT_DISPLAY', $PRODUCT_DISPLAY['PRODUCT_DISPLAY_GRID']);
define('SIGNUP_CONFIRM', FALSE);
define ('ADDON_FILTER', TRUE);
define ('UPDATE_ADDON_FILTER', TRUE);
$UPDATE_ADDON = array(
	'7d' => array(
		'title' => '7 ngày qua',
		'day' => 7),
	'14d' => array(
		'title' => '14 ngày qua',
		'day' => 14),
	'30d' => array(
		'title' => '30 ngày qua',
		'day' => 30)
);
define ('SALEOFF_ADDON_FILTER', TRUE);
$SALEOFF_ADDON = array(
	'sale1' => array(
		'title' => 'Dưới 10%',
		'from' => 1,
		'to' => 9),
	'sale2' => array(
		'title' => 'Từ 10% - 30%',
		'from' => 10,
		'to' => 30),
	'sale3' => array(
		'title' => 'Từ 30% - 50%',
		'from' => 30,
		'to' => 50)
);
define ('TAG_ARTICLE_ITEM', 10);