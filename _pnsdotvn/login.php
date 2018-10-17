<?php
error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
ini_set('session.gc_maxlifetime', 60*60*5);
date_default_timezone_set('Asia/Saigon');
ob_start('ob_gzhandler');
#ob_start("zlip output compression");
session_start();
if(isset($_SESSION['user']) AND ($_SESSION['user']!="")) header("Location:index.php");
require_once str_replace('\\','/',dirname(__FILE__)).'/defineConst.php';
require_once str_replace('\\','/',dirname(__FILE__)).'/class/class.admin.php';
require_once str_replace('\\','/',dirname(dirname(__FILE__))).'/class/class.utls.php';
require_once str_replace('\\','/',dirname(dirname(__FILE__))).'/plugins/captcha/rand.php';
$_SESSION['captcha_id'] = $strcaptcha;

$dbf->docType();
echo "<html>";	
$dbf->openHead();
$titleWebsite="..:: Administrator - Phuong Nam Solution ::..";
$title="LOGIN SYSTEM";
$arrayCSS=array("/_pnsdotvn/style/login.pack.css");
$arrayJS=array("/_pnsdotvn/js/jquery-1.3.2.min.js","/_pnsdotvn/js/jquery.validate.pack.js","/_pnsdotvn/js/jquery.form.js","/_pnsdotvn/js/login.pack.js");
$dbf->displayHead("utf-8","60",$titleWebsite,"","","","/_pnsdotvn/images/lock.ico",$arrayCSS,$arrayJS);		
$dbf->closeHead();
	
?>
<body>
<div style="width:100%">
<form id="frmLogin" name="frmLogin" method="post" action="process.php"/>
<div class="swap">
          <table border="0" cellpadding="" cellspacing="0" width="100%" class="main">
              <tr>
                  <td class="login"></td>
                  <td class="loginText">HỆ THỐNG ĐĂNG NHẬP</td>
              </tr>
              <tr>
                  <td colspan="2"><hr size="1" /></td>
              </tr>
              <tr>
                  <td colspan="2">
                  		Điền Tên đăng nhập, Mật khẩu và Mã bảo vệ vào các ô bên dưới. Sau đó bạn chọn đăng nhập để vào hệ thống quản lý website.
                      <!--Enter a valid Username and Password. Then click the "Sign In" button to access the Control Administrator.-->
                  </td>
              </tr>
              <tr>
                  <td colspan="2"><hr size="1" /></td>
              </tr>
              <tr>
                  <td colspan="2"><span id="result" class="saodo"></span></td>
              </tr>
              <tr>
                  <td class="padding">Tên đăng nhập:</td>
                  <td><input type="text"  maxlength="30" name="username" id="username" /></td>
              </tr>
              <tr>
                  <td class="padding">Mật khẩu:</td>
                  <td><input type="password"  maxlength="30" name="password" id="password" /></td>
              </tr>
              <tr>
                  <td class="padding">Mã bảo vệ:</td>
                  <td><input type="text" maxlength="50"  name="captcha" id="captcha" /></td>
              </tr>
              <tr>
                  <td>
                    <div id="captchaimage">
                        <a href="javascript:void(0);" id="refreshimg" title="Click to refresh image"><img src="../plugins/captcha/image.php?<?php echo time()?>" border="0" /></a>
                    </div>

                  </td>
                  <td>
                      <input type="submit" class="button" name="submit" id="submit" value="Đăng nhập" />
                      <input type="reset" class="button" name="reset" id="reset" value="Tạo lại" />
                      <input type="hidden" class="button" name="returnURL" id="returnURL" value="<?php echo (isset($_GET["returnURL"]) AND !empty($_GET["returnURL"])) ? $_GET["returnURL"] : ''?>" />
                  </td>
              </tr>
              <tr>
                  <td colspan="2"><hr size="1" /></td>
              </tr>
              <!--<tr>
                  <td colspan="2">
                      If you have forgotten your password, click on the "Forgot your Password" link to have a reminder sent to you at the e-mail address you specified during registration.
                  </td>
              </tr>
              <tr>
                  <td colspan="2"><hr size="1" /></td>
              </tr>
              <tr>
                  <td colspan="2" class="center"><a href="javascript:void(0);">Forgot Password?</a></td>
              </tr>
              <tr>
                  <td colspan="2" height="10"></td>
              </tr>-->
          </table>
</div>
</form>
</div>
</body>
</html><?php ob_end_flush()?>