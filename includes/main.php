<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$flg = (isset($def->route->name) && !in_array($def->route->name, array('home', 'product', 'cms', 'signup', 'signin', 'checkout', 'allcatalog', 'productaz')) ? true : false);
echo 
'<main id="main-wrapper" class="container">' .
  '<div id="main" class="row">' ;	
  	if ($flg) {
	  echo '<div class="col-md-2 mt10"><div id="left">';
	  include PNSDOTVN_INC . DS . 'left' . PHP;  
	  echo '</div></div>
	  <div class="col-md-10 mt10">';
	}
	  include PNSDOTVN_INC . DS . 'route' . PHP;
	echo ($flg ? '</div>' : '');
	if (isset($def->route->name) && !in_array($def->route->name, array('signup', 'signin', 'checkout'))) {
		$tmp = $def->route->name;
		$def->route->name = 'home';
		$pns->showHTML(
		  $pns->buildViewedProduct($_LNG)
		);
		$def->route->name = $tmp;
	}
echo
  '</div>' .
'</main>';