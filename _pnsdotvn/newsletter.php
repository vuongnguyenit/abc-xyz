<?php
require_once('index_table.php');

$col = array('id' => 'ID', 'name' => 'Họ & tên', 'email' => 'Email', 'phone' => 'Số điện thoại', 'registered' => 'Ngày đăng ký');
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : '';
$clickurl = '#';
$titleMenu = 'QUẢN LÝ DANH SÁCH NEWSLETTER';

error_reporting(E_ALL);

if($isDelete)
{
	$arrayid = substr($_POST['arrayid'], 0, -1);
	$id_array = explode(',',$arrayid);
	$affect = $dbf->deleteDynamic(prefixTable . 'newsletter', 'id in (' . $arrayid . ')');
    if($affect > 0) $msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
}

if($subInsert)
{
	
}

if($subUpdate)
{
	
}

if($isEdit)
{

} ?>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<?php $dbf->normalForm('frm', array('action' => '', 'method' => 'post', 'class' => 'validate')) ?>
<?php if(!$isEdit && !$isInsert) {
	echo $dbf->returnTitleMenuTable($titleMenu);
	#$PageNo = isset($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;	
	$url = 'newsletter.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
	$condition = !empty($iscatURL) ? 'position = "' . $iscatURL . '"' : '1=1';
	$mang = $dbf->paging(prefixTable . 'newsletter', $condition, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
	echo $dbf->panelAction($mang[1], 1, 0, 1, 0) ?>
<!-- view -->
<div id="panelView" class="panelView">
  <?php $dbf->normalView($col, "newsletter.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg) ?>
</div>
<!-- end view-->
<?php } ?>
<input type="hidden" name="arrayid" id="arrayid" />
</form>
</body></html><?php ob_end_flush() ?>