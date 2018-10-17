<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$html = 
$pns->buildBreadcrumb($def, $_LNG) .
'<div class="col-md-12 contact">
  <link rel="stylesheet" type="text/css" href="' . CSS_PATH . 'slick_cfwm.css" media="screen" />
  <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script> 
  <script type="text/javascript" src="' . JS_PATH . 'gmaps.js"></script>
  <h1 class="contact-name">Liên hệ</h1>
  <section id="slick"> 
    <div class="contact-form"> 
      <h2>' . (isset($def->company) ? $def->company : '') . '</h2>
      <p class="intro"> Địa chỉ: ' . (isset($def->address) ? $def->address : '') . '<br />
        Điện thoại: ' . (isset($def->company) ? $def->phone : '') . '<br />
        Hotline: ' . (isset($def->hotline1) ? $def->hotline1 : '') . '<br />
        Email: ' . (isset($def->mailcontact) ? $def->mailcontact : '') . '<br />
        Website: ' . (isset($def->website) ? $def->website : '') . ' </p>
      <div class="w-47 mr-6"> 
        <form action="/xu-ly-lien-he" name="contact" id="contact" method="post">
		  <div class="field">
			<input name="name" placeholder="Họ tên" type="text" id="name" required />
			<span class="entypo-user icon"></span> <span class="slick-tip left">Họ và tên bắt buộc nhập.</span> </div>
		  <div class="field">
			<input name="email" placeholder="Email" type="email" id="email" required />
			<span class="entypo-mail icon"></span> <span class="slick-tip left">Email bắt buộc và phải đúng định dạng.</span> </div>
		  <div class="field">
			<input name="phone" placeholder="Số điện thoại" type="tel" id="phone" />
			<span class="entypo-mobile icon"></span> <span class="slick-tip left">Điện thoại phải là số.</span> </div>
          <div class="field">
			<textarea name="message" placeholder="Nội dung" id="message" class="message" required></textarea>
			<span class="entypo-comment icon"></span> <span class="slick-tip left">Nội dung liên hệ bắt buộc nhập.</span> </div>        
          <input type="submit" value="Gửi" class="send" form="contact" name="send" />
        </form> 
		<script type="text/javascript"> 
		  var SITE_NAME = "' . SITE_NAME . '"; 
		  var Lat = "' . (isset($def->latitude) ? $def->latitude : '10.8482453') . '"; 
		  var Lng = "' . (isset($def->longitude) ? $def->longitude : '106.6468312') . '"
		</script>
		<script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
		<script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
		<script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
		<script type="text/javascript" src="' . JS_PATH . 'JS.contact.js"></script>
      </div>
      <div class="w-47"> 
        <div id="map-canvas" class="map"></div>
      </div>
    </div>
  </section>
</div>';
$pns->showHTML($html);