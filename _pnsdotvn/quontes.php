<?php
define('MOD_DIR_UPLOAD', '/media/images/others/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/');

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . '/plugins/upload/class.upload.php');

$titleMenu = 'QUẢN LÝ DANH SÁCH BÁO GIÁ';
$col       = [
    'id'         => 'ID',
    'full_name'  => 'Họ & tên',
    'email'      => 'Email',
    'phone'      => 'Số điện thoại',
    'registered' => 'Ngày đăng ký',
    'qstatus'    => 'Trạng thái',
];
$iscatURL  = (isset($_GET['caturl']) && !empty($_GET['caturl'])) ? $_GET['caturl'] : 0;
?>
<?php
if (!$isEdit) {

    ?>
    <?php
    echo $dbf->returnTitleMenuTable($titleMenu);
    $url  = 'quontes.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
    $mang = $dbf->paging(prefixTable . 'quontes', $condition, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
    ?>
    <input type="hidden" name="is-quontes" value="true" id="is-quontes">
    <div id="panelAction" class="panelAction">
        <div class="panelActionContent" style="float: left; padding: 5px">
            <table id="panelTable" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td class="cellAction1 deleleAction">
                        <a href="javascript:void(0);" class="new-style">Xóa</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    $dbf->normalForm('frm', [
        'action' => '',
        'method' => 'post',
        'class'  => 'validate',
    ])

    ?>
    <div id="panelView" class="panelView">
        <?php
        $orderBy = [
            [
                'field' => 'registered',
                'type'  => 'DESC',
            ],
        ];
        $quontes = $dbf->selectData(prefixTable . 'quontes', [], $orderBy, 2, 0);
        if ($quontes) { ?>
            <table id='mainTable' cellpadding='0' cellspacing='0'>
                <tr>
                    <td class="titleBottom" style="width:7%">
                        <input type="checkbox" name="chkall" id="chkall" value="1" onclick="docheck(this.checked,0);" class="checkAll"> √
                    </td>
                    <td class="titleBottom">Họ & tên</td>
                    <td class="titleBottom">Email</td>
                    <td class="titleBottom">Số điện thoại</td>
                    <td class="titleBottom">Ngày đăng ký</td>
                    <td class="titleBottom">Trạng thái</td>
                </tr>
                <?php foreach ($quontes as $quonte): ?>
                    <tr style="cursor:pointer">
                        <td class="cell2">
                            <input type="checkbox" name="chk" value="<?= $quonte['id'] ?>" onclick="docheckone();" class="checkDelete">
                        </td>
                        <td class="cell2">
                            <div class="inlinecell" style="cursor:pointer">
                                <a href="quontes.php?edit=<?= $quonte['id'] ?>"><?= $quonte['full_name'] ?></a>
                            </div>
                        </td>
                        <td class="cell2">
                            <div class="inlinecell" style="cursor:pointer">
                                <a href="quontes.php?edit=<?= $quonte['id'] ?>"><?= $quonte['email'] ?></a>
                            </div>
                        </td>
                        <td class="cell2">
                            <div class="inlinecell" style="cursor:pointer">
                                <a href="quontes.php?edit=<?= $quonte['id'] ?>"><?= $quonte['phone'] ?></a>
                            </div>
                        </td>
                        <td class="cell2">
                            <div class="inlinecell" style="cursor:pointer">
                                <a href="quontes.php?edit=<?= $quonte['id'] ?>"><?= $quonte['registered'] ?></a>
                            </div>
                        </td>
                        <td class="cell2">
                            <div class="inlinecell" style="cursor:pointer">
                                <a href="quontes.php?edit=<?= $quonte['id'] ?>">
                                    <?php
                                    $statusText = '';
                                    if ($quonte['status'] == 0) {
                                        $statusText = 'Đang xử lý';
                                    }
                                    if ($quonte['status'] == 1) {
                                        $statusText = 'Đã gửi báo giá';
                                    }
                                    echo $statusText;
                                    ?>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php } ?>
    </div>
<?php } ?>
<?php
if ($isEdit) {
    die('hello');
}
?>
