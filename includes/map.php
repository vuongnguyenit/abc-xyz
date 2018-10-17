<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$html = $pns->buildBreadcrumb($def, $_LNG);
$html .=
'<div id="google-map">
  <script type="text/javascript">window.onload = function () { initialize(); }</script>
  <script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyCDxxE97Ak0hsCRbhmdjMoyXSmCCPdlzLw&language=' . $def->code2 . '"></script>
  <script type="text/javascript">
  var latitude = "' . (isset($def->latitude) && !empty($def->latitude) ? $def->latitude : '10.833') . '";
  var longitude = "' . (isset($def->longitude) && !empty($def->longitude) ? $def->longitude : '106.664') . '";
  var COMPANY_EMAIL = "' . (isset($def->mailcontact) && !empty($def->mailcontact) ? $def->mailcontact : 'info@pns.vn') . '";
  var COMPANY_NAME = "' . (isset($def->company) && !empty($def->company) ? $def->company : 'Công ty TNHH Giải Pháp Trực Tuyến Phương Nam') . '";
  var COMPANY_PHONE = "' . (isset($def->phone) && !empty($def->phone) ? $def->phone : '(08) 62.919.777') . '";
  var COMPANY_ADDRESS = "' . (isset($def->address) && !empty($def->address) ? $def->address : '205/11/13 Phạm Văn Chiêu, Phường 14, Quận Gò Vấp, Tp.Hồ Chí Minh') . '";
  var COMPANY_EMAIL = "' . (isset($def->mailcontact) && !empty($def->mailcontact) ? $def->mailcontact : 'info@pns.vn') . '";
  </script>
  <script type="text/javascript" src="' . JS_PATH . 'JS.map.js"></script>
  <div class="clear map-right">
    <div id="map_canvas"></div>
  </div>
</div>';
$pns->showHTML($html);