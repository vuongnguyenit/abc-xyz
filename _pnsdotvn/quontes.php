<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
// delete item
if (isset($_POST['delete']) && !empty($_POST['id'])) {
    if (isset($_POST['delete']) && !empty($_POST['id'])) {
        $ids = $_POST['id'];
        if (count($ids) > 1) {
            $dbf->deleteData(prefixTable . 'quontes', 'id', $ids);
        }
        else {
            $id = end($ids);
            $dbf->deleteData(prefixTable . 'quontes', 'id', $id);
        }
        $msg = "Dã xóa thành công " . count($ids) . ' item';
        echo '<span class="txtdo" align="center">' . $msg . '</span>';
    }
    if (!empty($_SESSION['message'])) {
        echo '<span class="txtdo" align="center">' . $_SESSION['message'] . '</span>';
        unset($_SESSION['message']);
    }
}
// list quontes
if (!$isEdit) {
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
                'field' => 'id',
                'type'  => 'DESC',
            ],
        ];
        $quontes = $dbf->selectData(prefixTable . 'quontes', [], $orderBy, 20, 0);
        if ($quontes) { ?>
            <table id='mainTable' cellpadding='0' cellspacing='0'>
                <tr>
                    <td class="titleBottom" style="width:7%">
                        <input type="checkbox" name="chkall" id="chkall"
                               value="1" onclick="docheck(this.checked,0);"
                               class="checkAll"> √
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
                            <input type="checkbox" name="chk"
                                   value="<?= $quonte['id'] ?>"
                                   onclick="docheckone();"
                                   class="checkDelete">
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
<?php }
if ($isEdit) { ?>

    <div id="panelForm" class="panelForm">
        <table id="mainTable" cellpadding="0" cellspacing="0">
            <?php
            echo $dbf->returnTitleMenu($titleMenu);
            $conditions = [
                [
                    'field' => 'id',
                    'type'  => '=',
                    'value' => @$_GET['edit'],
                ],
            ];
            $quonte     = $dbf->selectData(prefixTable . 'quontes', $conditions, []);
            $quonte     = end($quonte);
            ?>
            <tr>
                <td class="boxGrey">Ngày đăng ký</td>
                <td class="boxGrey2">
                    <strong><?= $quonte['registered'] ?></strong>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Thông tin người đăng ký</td>
                <td class="boxGrey2" style="line-height: 20px;">Họ & tên:
                    <strong><?= @$quonte['full_name'] ?></strong><br/>
                    Email: <?= @$quonte['email'] ?><br/>
                    Điện thoại: <?= @$quonte['phone'] ?><br/>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Ghi chú</td>
                <td class="boxGrey2">
                    <textarea id="order_memo" name="order_memo" class="nd2">
                        <?= @$quonte['content'] ?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Chi tiết đơn hàng</td>
                <td class="boxGrey2"><?php
                    $listIds = unserialize(@$quonte['list']);
                    if (!empty($listIds)) { ?>
                        <table id="mainTable" cellpadding="4" cellspacing="0"
                               border="1" style="border-collapse:collapse;">
                            <tr>
                                <td width="10%" align="center"><strong>Hình
                                        ảnh</strong></td>
                                <td width="45%" align="center"><strong>Sản
                                        phẩm</strong></td>
                                <td width="10%" align="center"><strong>Số
                                        lượng</strong></td>
                                <td width="15%" align="center"><strong>Giá bán
                                        lẻ</strong></td>
                                <td width="15%" align="center"><strong>Thành
                                        tiền</strong></td>
                            </tr>
                            <?php foreach ($listIds as $item) {
                                $conditions = [
                                    [
                                        'field' => 'id',
                                        'type'  => '=',
                                        'value' => @$item['product_id'],
                                    ],
                                ];
                                $product    = $dbf->selectData(prefixTable . 'product', $conditions, []);
                                $product    = end($product);
                                $imgUrl     = explode(';', $product['picture']);
                                $imgUrl     = reset($imgUrl); ?>
                                <tr>
                                    <td align="center" valign="middle">
                                        <img src="<?= $imgUrl ?>"
                                             alt="<?= $product['name'] ?>"
                                             width="25%">
                                    </td>
                                    <td style="vertical-align: middle"><?= $product['name'] ?></td>
                                    <td align="center"
                                        style="vertical-align: middle"><?= $item['quantity'] ?></td>
                                    <td align="center"
                                        style="vertical-align: middle">
                                        <input type="text" name="price[]"
                                               value="" size="20"
                                               data-id="<?php $item['product_id'] ?>">
                                    </td>
                                    <td align="center"
                                        style="vertical-align: middle">
                                        <input type="text" name="total[]"
                                               value="" size="20"
                                               data-id="<?php $item['product_id'] ?>">
                                    </td>
                                </tr>
                            <?php }
                            ?>
                            <tr>
                                <td colspan="5" style="text-align: right">
                                    <label for="tax"><strong>Thuế</strong></label>
                                    <input type="text" name="tax" id="tax">
                                </td>
                            </tr>
                        </table>
                    <?php } ?></td>
            </tr>
            <tr>
                <td class="boxGrey"></td>
                <td class="boxGrey2" height="1" align="center">
                    <div id="insert">
                        <table style="padding-bottom:8px;padding-top:5px;"
                               cellspacing="2" cellpadding="0" border="0">
                            <tbody>
                            <tr>
                                <td>
                                    <input class="new-style" name="exportPdf"
                                           id="exportPdf" type="button"
                                           class="btncenter" value="Xuất PDF"
                                           style="cursor: pointer">
                                </td>
                                <td>
                                    <input class="new-style" name="btnhuy"
                                           id="btnhuy" type="button"
                                           onclick="huy();" class="btncenter"
                                           value="Quay lại"
                                           style="cursor: pointer">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php } ?>
<?php
require_once 'lib/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'mode'           => 'utf-8',
    'tempDir'        => 'tmp',
    'autoLangToFont' => TRUE,
    'format'         => 'A4',
]);
$mpdf->shrink_tables_to_fit = 1;
$html = '<h1>heoo</h1>';
$mpdf->WriteHTML($html);
$mpdf->SetHTMLFooter('
            <table width="100%">
                <tr>
                    <td align="center">({PAGENO}/{nbpg})</td>
                </tr>
            </table>');

ob_clean();
$mpdf->Output(date('Ymdhis') . '_call_number_.pdf', 'D');
?>
