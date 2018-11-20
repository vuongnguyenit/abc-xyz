<?php
define('MOD_DIR_UPLOAD', '/media/images/others/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/');

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . '/plugins/upload/class.upload.php');

$titleMenu = 'QUẢN LÝ DANH SÁCH BÁO GIÁ';
$col = [
    'id' => 'ID',
    'full_name' => 'Họ & tên',
    'email' => 'Email',
    'phone' => 'Số điện thoại',
    'registered' => 'Ngày đăng ký',
    'qstatus' => 'Trạng thái'
];
?>

<?php
echo $dbf->returnTitleMenuTable($titleMenu);
$url = 'quontes.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
$mang = $dbf->paging(prefixTable . 'quontes', $condition, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
echo $dbf->panelAction($mang[1], true, false, true, false);

$dbf->normalForm('frm', array('action' => '', 'method' => 'post', 'class' => 'validate'))

?>
<div id="panelView" class="panelView">
    <?php $dbf->normalView($col, "quontes.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg) ?>
</div>
