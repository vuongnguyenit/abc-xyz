<?php
if (! defined('PHUONG_NAM_SOLUTION')) 
{
  header('Location: /errors/403.shtml');	
  die();
}

$html = 
$pns->buildBreadcrumb($def, $_LNG) .
'<div id="chkorder">
  <div class="chkorder-box">
    <div class="top">
      <form id="frmChkorder" name="frmChkorder" action="/xu-ly-kiem-tra-don-hang" method="post" enctype="application/x-www-form-urlencoded">
        <h1>Kiểm tra đơn hàng của bạn</h1>
        <p>Điền vào các thông tin bên dưới để xem tình trạng của Đơn hàng.</p>        
        <input id="code" name="code" type="text" class="form-control input {validate: {required:true}}" maxlength="16" autocomplete="off" placeholder="Mã đơn hàng" />
        <input id="email" name="email" type="text" class="form-control input {validate: {required:true, email:true}}" maxlength="64" autocomplete="off" placeholder="Địa chỉ email" />
        <input id="send" name="send" type="submit" class="submit button btn btn-lg" value="Kiểm tra" />
      </form>
    </div>
  </div>
  <div class="showdata corner_5" id="showdata" onClick="$(this).css(\'display\',\'none\')">
    <h4>Vui lòng thực hiện các vấn đề dưới đây</h4>
    <ol>
      <li><label for="code" class="error">Mã đơn hàng bắt buộc nhập.</label></li>
      <li><label for="email" class="error">Email bắt buộc và phải đúng định dạng.</label></li>      
    </ol>
    <a class="close" href="javascript:void(0);" onClick="$(\'div.showdata\').fadeOut(300).hide(1)"></a>
  </div>
  <script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>
  <script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>
  <script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>
  <script type="text/javascript" src="' . JS_PATH . 'JS.chkorder.js"></script>
  <div id="chkorder-result"></div>
</div>';
$pns->showHTML($html);