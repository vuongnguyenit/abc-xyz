<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$html =
'<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8">
<title>' . $def->title . '</title>
<meta name="description" content="' . $def->description . '" />
<meta name="keywords" content="' . $def->keywords . '" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="robots" content="index,follow" />
<meta name="revisit-after" content="1 days" />
<meta property="og:type" content="article" />
<meta property="og:title" content="' . $def->title . '" />
<meta property="og:description" content="' . $def->description . '" />
<meta property="og:url" content="https://chauruachen.pns.vn' . $def->url . '" />
<meta property="og:image" content="' . $def->image . '" />
<meta property="article:published_time" content="' . $def->published_time . '" />
<meta property="article:modified_time" content="' . $def->modified_time . '" />
<meta property="article:author" content="' . $def->author . '" />
<meta property="article:tag" content="' . $def->keywords . '" />
<meta property="og:site_name" content="' . $def->site_name . '" />
<meta property="og:locale" content="' . $def->code4 . '" />
<meta name="geo.placename" content="' . $def->placename . '" />
<meta name="geo.position" content="' . $def->position . '" />
<meta name="geo.region" content="' . $def->region . '" />
<meta name="ICBM" content="' . $def->icbm . '" />
<meta name="DC.title" lang="' . $def->code2 . '" content="' . $def->title . '" />
<meta name="DC.description" lang="' . $def->code2 . '" content="' . $def->description . '" />
<!--<meta name="alexaVerifyID" content="" />
<meta name="twitter:card" content="summary" />-->
<meta name="generator" content="Phuong Nam Solution" />
<link rel="canonical" href="https://chauruachen.pns.vn' . $def->url . '" />
<link rel="apple-touch-icon" href="' . IMAGES_PATH . 'favicon.ico" />
<link rel="icon" type="image/x-icon" href="' . IMAGES_PATH . 'favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="' . IMAGES_PATH . 'favicon.ico" />
<link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'bootstrap.css" media="screen" />
<link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'master.css" media="screen" />
<link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'custom.css" media="screen" />
<script type="text/javascript" src="' . JS_PATH . 'jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'jquery.selectBox.min.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'include.master.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'script-master.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'script.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'jquery.cart.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'functions_main.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'jquery.autocomplete.js"></script>
<script type="text/javascript" src="' . JS_PATH . 'custom.js"></script>
<script type="text/javascript"> var lang = \'' . $def->code . '\'; </script>
<script type="text/javascript">$(function(){ setAutoComplete(); });
$(document).ready(function() {
  $("#search-selectBox").selectBox();
});
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-999566300"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'AW-999566300\');
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-21471530-7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'UA-21471530-7\');
</script>

<!--[if IE 6.0]>
<link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'ie6.css"/>
<iframe src="/ie6.html" align="center" width="980px" height="200" scrolling="no" frameborder="0">
</iframe>
<![endif]-->';
$pns->showHTML($html); 