<?php
define('MOD_DIR_UPLOAD', '/media/images/others/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/');

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . '/plugins/upload/class.upload.php');
$col       = [
    'id'       => 'Id',
    'name'     => 'Đề mục',
    'type'     => 'Loại',
    'created'  => 'Ngày tạo',
    'modified' => 'Ngày sửa',
    'status'   => 'Trạng thái',
];
$titleMenu = 'QUẢN LÝ CẤU HÌNH';
$iscatURL  = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : 0;
$action    = !empty($_GET['action']) ? $_GET['action'] : NULL;
if ($subUpdate) {
    $err = 0;

    // BEGIN: UPLOAD PICTURE
    if (isset($_FILES['picture']['name']) && is_array($_FILES['picture']['name']) && count($_FILES['picture']['name']) > 0) {
        $dir = $dbf->pnsdotvn_get_dir_upload(3);

        $arr_pic = [];
        foreach ($_FILES['picture'] as $k => $l) {
            foreach ($l as $i => $v) {
                if (!array_key_exists($i, $arr_pic)) {
                    $arr_pic[$i] = [];
                }
                $arr_pic[$i][$k] = $v;
            }
        }

        for ($i = 0; $i < count($_FILES['picture']['name']); $i++) {
            if (!empty($_FILES['picture']['name'][$i])) {
                $pic['type']             = 'image';
                $pic['path']             = MOD_ROOT_URL;
                $pic['dir']              = $dir;
                $pic['file']             = $arr_pic[$i];
                $pic['change_name']      = TRUE;
                $pic['w']                = 1300;
                $pic['thum']             = TRUE;
                $pic['change_name_thum'] = FALSE;
                $pic['w_thum']           = 150;
                $upload                  = new Upload($pic['file']);
                $result                  = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
                if (empty($result['err'])) {
                    $_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
                }
            }
        }
    }

    if (isset($_POST['picture_name']) && !empty($_POST['picture_name']) && is_array($_POST['picture_name']) && count($_POST['picture_name']) > 0) {
        foreach ($_POST['picture_name'] as $_k => $_v) {
            if (!empty($_POST['picture_name'][$_k])) {
                $_picture_name[] = $_v;
            }
        }
    }
    $picture = isset($_picture_name) && count($_picture_name) > 0 ? implode(';', $_picture_name) : '';

    if (isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0) {
        foreach ($_picture as $k => $v) {
            if (isset($_picture[$k])) {
                $_picture_name[$k] = $v;
            }
        }
        $picture = implode(';', $_picture_name);
    }

    #print_r($data['picture']);
    #exit;
    // END: UPLOAD PICTURE

    if (isset($_GET['edit']) && !empty($_GET['edit']) /*&& (isset($_POST['content']) && !empty($_POST['content']) || isset($picture) && !empty($picture))*/) {
        $_id                 = (int) $_GET['edit'];
        $data['status']      = isset($_POST['status']) && $_POST['status'] == 1 ? (int) $_POST['status'] : 0;
        $data['modified']    = date('Y-m-d H:i:s');
        $data['modified_by'] = 1;

        $value['vi-VN']['content'] = isset($picture) && !empty($picture) ? $picture : addslashes($dbf->compressHtml(trim($_POST['content'])));
        #$value['en-US']['content'] = !empty($_POST['content_en']) ? $dbf->compressHtml(trim($_POST['content_en'])) : $value['vi-VN']['content'];

        $affect = $dbf->updateTable(prefixTable . 'setting', $data, 'display = 1 AND id = ' . $_id);
        if ($affect > 0) {
            $langs = array_keys($value);
            foreach ($langs as $lang) {
                $dbf->updateTable(prefixTable . 'setting_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);
            }

            $msg = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu';
        }
    }
    else {
        $msg = 'Vui lòng nhập đầy đủ dữ liệu.';
        $err = 1;
    }
}

if ($isEdit) {
    $rst = $dbf->getDynamic(prefixTable . 'setting', 'display = 1 AND id = ' . (int) $_GET['edit'], '');
    if ($rst) {
        $row    = $dbf->nextData($rst);
        $id     = $row['id'];
        $name   = stripslashes($row['name']);
        $type   = $row['type'];
        $status = $row['status'];

        $vi = $dbf->getArray(prefixTable . 'setting_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
        #$en = $dbf->getArray(prefixTable . 'setting_desc', 'id = "' . $id . '" and lang = "en-US"', '', 'stdObject');
    }
} ?>
    <script type="text/javascript" src="js/yetii.js"></script>
    <style type="text/css">
        #tab-container-1-nav {list-style: none;margin: 0;padding: 0px;}
        #tab-container-1-nav :after { /*clearing without presentational markup, IE gets extra treatment*/display: block;clear: both;content: " ";}
        #tab-container-1-nav li {float: left;padding-right: 5px;}
        #tab-container-1-nav li.activeli a {color: #B84120;font-size: 12px;}
        #tab-container-1-nav a {background: #E6E6E6; display: block;color: #333333; /*font-weight: bold;*/text-align: center;text-decoration: none;padding-right: 7px;border-top: 1px solid #DBDBDB;border-left: 1px solid #DBDBDB;border-right: 1px solid #DBDBDB;}
        #tab-container-1-nav a span {display: block;line-height: 25px;padding-left: 15px;padding-right: 8px;}
        #tab-container-1-nav a:hover {color: #B84120;}
        #tab-container-1-nav a:hover span {}
        #tab-container-1-nav .tabs-selected {top: 1px;position: relative;}
        #tab-container-1-nav .tabs-selected a {background: #FFF;color: #B84120;}
        #tab-container-1-nav .tabs-selected a span {background: #FFF;}
        #tab-container-1 .tab {border-top: 1px solid #dbdbdb; /*padding:5px 5px;*/background: #FFF;overflow: hidden;}
    </style>
    <script type="text/javascript"
            src="../themes/default/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script language="javascript">
        jQuery(document).ready(function () {
            jQuery('#frm').validate({
                rules: {
                    content: {
                        required: true
                    }
                },
                messages: {
                    content: {
                        required: "Nhập nội dung."
                    }
                }
            });
        });
    </script>
<?php $dbf->FormUpload('frm', [
    'action' => '',
    'method' => 'post',
    'class'  => 'validate',
]);
?>
<?php if ($isEdit || $isInsert) { ?>
    <!-- form -->
    <div id="panelForm" class="panelForm">
        <table id="mainTable" cellpadding="0" cellspacing="0">
            <?php echo $dbf->returnTitleMenu($titleMenu) ?>
            <tr>
                <td colspan="4" class="txtdo"
                    align="center"><?php echo $msg ?></td>
            </tr>
            <tr>
                <td class="boxGrey"><?php echo $col['name'] ?></td>
                <td class="boxGrey2"><input name="name" id="name" type="text"
                                            class="nd3"
                                            value="<?php echo isset($name) && !empty($name) ? $name : '' ?>"
                                            disabled/></td>
            </tr>
            <?php if (in_array($type, [
                'banking',
                'note',
                'header',
                'footer',
                'footer1',
                'footer2',
                'footer3',
                'contact',
                'why_choose_us',
                'help',
                'footer_menu',
                'pd_desc',
                'googlemap',
                'mainmenu',
            ])) : ?>
                <tr>
                    <td class="boxGrey" valign="top">Nội dung</td>
                    <td class="boxGrey2">
                        <script type="text/javascript"
                                src="../plugins/editors2/ckeditor/ckeditor.js"></script>
                        <script type="text/javascript"
                                src="../plugins/editors2/ckfinder/ckfinder.js"></script>
                        <div id="tab-container-1">
                            <ul id="tab-container-1-nav">
                                <li><a href="#tieng-viet"><span><!--Tiếng Việt--></span></a>
                                </li>
                                <!--<li><a href="#tieng-anh"><span>Tiếng Anh</span></a></li>-->
                            </ul>
                            <div class="clear" style="clear:both;"></div>
                            <div class="tab"
                                 id="tieng-viet"><?php #echo $ckeditor->doDisplay('content', (isset($vi[0]->content) && !empty($vi[0]->content) ? stripslashes($vi[0]->content) : ''), '100%', '200', 'Default') ?>
                                <textarea name="content" id="content" cols="75"
                                          rows="20"><?php echo (isset($vi[0]->content) && !empty($vi[0]->content)) ? stripslashes($vi[0]->content) : '' ?></textarea>
                                <script type="text/javascript">
                                    var editor = CKEDITOR.replace('content', {
                                        language: 'vi',
                                        toolbar: 'Default',
                                        height: '300px',
                                        width: '100%',
                                        filebrowserBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
                                        filebrowserImageBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
                                        filebrowserFlashBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                                        filebrowserImageUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                        filebrowserFlashUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                    });
                                    CKFinder.setupCKEditor(editor, '../');
                                </script>
                            </div>
                            <!--<div class="tab" id="tieng-anh"><?php #echo $ckeditor->doDisplay('content_en', (isset($en[0]->content) && !empty($en[0]->content) ? stripslashes($en[0]->content) : ''), '100%', '200', 'Default') ?></div>-->
                        </div>
                        <script type="text/javascript">var tabber1 = new Yetii({id: 'tab-container-1'});</script>
                    </td>
                </tr>
            <?php elseif (in_array($type, [
                'title',
                'keywords',
                'description',
                'date1',
                'ga',
                'fbcode',
                'addthis',
                'histats',
            ])) : ?>
                <tr>
                    <td class="boxGrey">Nội dung</td>
                    <td class="boxGrey2"><textarea name="content" class="nd3"
                                                   id="content"
                                                   type="text">
                            <?php echo isset($vi[0]->content) && !empty($vi[0]->content) ? $vi[0]->content : '' ?></textarea>
                    </td>
                </tr>
            <?php elseif (in_array($type, ['logo', 'banner'])) : ?>
                <tr>
                    <td class="boxGrey">Nội dung</td>
                    <td class="boxGrey2">
                        <?php echo isset($vi[0]->content) && !empty($vi[0]->content) ? '<img src="resize.php?from=..' . $vi[0]->content . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                        <input type="file" name="picture[]" id="picture"
                               style="width:250px;"/>
                        <input type="hidden" name="picture_name[]"
                               id="picture_name"
                               value="<?php echo isset($vi[0]->content) && !empty($vi[0]->content) ? $vi[0]->contents : '' ?>"/>
                    </td>
                </tr>
            <?php else : ?>
                <tr>
                    <td class="boxGrey">Nội dung</td>
                    <td class="boxGrey2">
                        <input type="text" class="nd3" name="content"
                               id="content"
                               value="<?php echo(isset($vi[0]->content) && !empty($vi[0]->content) ? $vi[0]->content : '') ?>"/>
                    </td>
                </tr>
            <?php endif ?>
            <tr>
                <td class="boxGrey">&nbsp;</td>
                <td class="boxGrey2">Lưu ý: Không thay đổi các giá trị nằm trong
                    cặp dấu <strong>{}</strong>.
                </td>
            </tr>
            <tr>
                <td class="boxGrey"><?php echo $col['status'] ?></td>
                <td class="boxGrey2">
                    <input type="checkbox" name="status" id="status"
                           value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> />
                    Kích hoạt?
                </td>
            </tr>
            <tr>
                <td class="boxGrey"></td>
                <td height="1" align="center" class="boxGrey2">
                    <div id="insert"><?php echo(($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '')) ?></div>
                </td>
            </tr>
        </table>
    </div>
    <!-- end Form-->
<?php } ?>
<?php if (empty($action)) {
    echo $dbf->returnTitleMenuTable($titleMenu);
    $url      = 'setting.php?';
    $PageSize = 50;
    $mang     = $dbf->paging(prefixTable . 'setting', 'display = 1', 'id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
    ?>
    <div id="panelAction" class="panelAction">
        <div class="panelActionContent" style="float: left; padding: 5px">
            <table id="panelTable" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cellAction1">
                        <button>Thêm</button>
                    </td>
                    <td class="cellAction">
                        <button>Xóa</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div id="panelView" class="panelView">
        <?php $dbf->normalView($col, 'setting.php', $mang, $statusAction, '&caturl=' . $iscatURL) ?>
    </div>
    <!-- end view-->
<?php } ?>
<?php ob_end_flush() ?>