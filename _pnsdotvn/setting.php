<?php
define('MOD_DIR_UPLOAD', '/media/images/others/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/');

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . '/plugins/upload/class.upload.php');
$col       = [
    'id'       => 'Id',
    'name'     => 'Đề mục',
    'type'     => 'Vị trí',
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
    if (isset($_FILES['content']['name']) && is_array($_FILES['content']['name']) && count($_FILES['content']['name']) > 0) {
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

        for ($i = 0; $i < count($_FILES['content']['name']); $i++) {
            if (!empty($_FILES['content']['name'][$i])) {
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


    if (isset($_GET['edit']) && !empty($_GET['edit']) ) {
        $_id                 = (int) $_GET['edit'];
        $data['status']      = isset($_POST['status']) && $_POST['status'] == 1 ? (int) $_POST['status'] : 0;
        $data['modified']    = date('Y-m-d H:i:s');
        $data['modified_by'] = 1;
        if (isset($_FILES['content']['name'])  && count($_FILES['content']['name']) > 0) {
            $oldData = $dbf->getArray(prefixTable . 'setting_desc', 'id = "' . $_id . '" and lang = "vi-VN"', '', 'stdObject');
            $oldData = end($oldData);
            $dir = $dbf->pnsdotvn_get_dir_upload(3);
            $uploaddir = MOD_ROOT_URL . $dir . '/';
            $dir_writable = substr(sprintf('%o', fileperms($uploaddir)), -4);
            if ( $dir_writable != '0777') {
                chmod($uploaddir, 0777);
            }
            $value['vi-VN']['content'] = '/media/images/others/'. $dir . '/' . $_FILES['content']['name'];
            $uploadfile = $uploaddir . basename($_FILES['content']['name']);
            move_uploaded_file($_FILES['content']['tmp_name'], $uploadfile);
        } else {
            $value['vi-VN']['content'] = addslashes($dbf->compressHtml(trim($_POST['content'])));
        }

        $affect = $dbf->updateTable(prefixTable . 'setting', $data, 'display = 1 AND id = ' . $_id);
        if ($affect > 0) {
            $langs = array_keys($value);
            foreach ($langs as $lang) {
                $dbf->updateTable(prefixTable . 'setting_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);
            }

            $_SESSION['message'] = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu';
            header('location: setting.php');
        }
    }
    else {
        $msg = 'Vui lòng nhập đầy đủ dữ liệu.';
        $err = 1;
    }
}
if(isset($_POST['insert'])){
    if(!empty($_POST['type'])) {
        if($_POST['type'] == 'file') {
            if (isset($_FILES['content']['name'])  && count($_FILES['content']['name']) > 0) {
                $dir = $dbf->pnsdotvn_get_dir_upload(3);
                $uploaddir = MOD_ROOT_URL . $dir . '/';
                $content = '/media/images/others/'. $dir . '/' . $_FILES['content']['name'];
                $uploadfile = $uploaddir . basename($_FILES['content']['name']);
                move_uploaded_file($_FILES['content']['tmp_name'], $uploadfile);
            }
        } else {
            $content = $_POST['content'];
        }
        $fields = [
            'name',
            'type',
            'display',
            'status',
            'created',
            'created_by',
            'form_type',
        ];
        $created = date('Y-m-d H:i:s');
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $values = [
            @$_POST['name'],
            @$_POST['position'],
            $status,
            $status,
            $created,
            '1',
            $_POST['type'],
        ];
        $idSetting = $dbf->insertData(prefixTable . 'setting', $fields, $values);
        $fields = [
            'id',
            'lang',
            'content',
            'default_value'
        ];
        $values = [
            $idSetting,
            'vi-VN',
            $content,
            ''
        ];
        $dbf->insertData(prefixTable . 'setting_desc', $fields, $values);
        $_SESSION['message'] = "Thêm dữ liệu thành công!";
        header('location: setting.php');
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
        $form_type   = $row['form_type'];

        $vi = $dbf->getArray(prefixTable . 'setting_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
    }
} ?>
<?php $dbf->FormUpload('frm', [
    'action' => '',
    'method' => 'post',
    'class'  => 'validate',
]);
?>
<script type="text/javascript" src="js/yetii.js"></script>
<script type="text/javascript" src="../plugins/editors2/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../plugins/editors2/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="js/custom.js"></script>

<?php if($isInsert) : ?>
    <div id="panelForm" class="panelForm">
        <table id="mainTable" cellpadding="0" cellspacing="0">
            <?php echo $dbf->returnTitleMenu($titleMenu) ?>
            <tr>
                <td colspan="4" class="txtdo" align="center"><?php echo $msg ?></td>
            </tr>
            <tr>
                <td class="boxGrey"><?php echo $col['name'] ?></td>
                <td class="boxGrey2">
                    <input name="name" id="name" type="text" class="nd3" value=""/>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Vị trí</td>
                <td class="boxGrey2">
                    <input name="position" id="position" type="text" class="nd3" value=""/>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Form type</td>
                <td class="boxGrey2">
                    <select name="type" id="type">
                        <option value="">-------Chọn type-------</option>
                    <?php
                    $sql = 'SELECT DISTINCT form_type FROM '.prefixTable . "setting";
                    $result = $dbf->executeSql($sql, TRUE);
                    $result = array_column($result, 'form_type');
                    foreach ($result as $row) {?>
                        <option value="<?= $row ?>"><?= $row ?></option>
                    <?php } ?>
                    </select>
                </td>
            </tr>
            <tr class="content-label">
                <td class="boxGrey" valign="top">Nội dung</td>
                <td class="boxGrey2">
                    <div id="tab-container-1">
                        <div class="tab" id="tieng-viet">
                            <textarea name="content" id="ckeditor" class="content"></textarea>
                            <textarea name="" id="textarea" class="nd3 content" ></textarea>
                            <input name="" type="file" id="file" style="width:250px;" class="content"/>
                            <input name="" type="text" id="textfield" class="nd3 content"  />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">&nbsp;</td>
                <td class="boxGrey2">Lưu ý: Không thay đổi các giá trị nằm trong
                    cặp dấu <strong>{}</strong>.
                </td>
            </tr>
            <tr>
                <td class="boxGrey"><?php echo $col['status'] ?></td>
                <td class="boxGrey2">
                    <input type="checkbox" name="status" id="status" value="1"  />
                    Kích hoạt?
                </td>
            </tr>
            <input type="hidden" name="insert" id="insert" value="1"  />
            <tr>
                <td class="boxGrey"></td>
                <td height="1" align="center" class="boxGrey2">
                    <div id="insert"><?php echo(($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '')) ?></div>
                </td>
            </tr>
        </table>
    </div>
<?php endif; ?>
<?php if ($isEdit) : ?>
    <!-- form -->
    <div id="panelForm" class="panelForm">
        <table id="mainTable" cellpadding="0" cellspacing="0">
            <?php echo $dbf->returnTitleMenu($titleMenu) ?>
            <tr>
                <td colspan="4" class="txtdo" align="center"><?php echo $msg ?></td>
            </tr>
            <tr>
                <td class="boxGrey"><?php echo $col['name'] ?></td>
                <td class="boxGrey2">
                    <input name="name" id="name" type="text" class="nd3" value="<?php echo isset($name) && !empty($name) ? $name : '' ?>" disabled/>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Vị trí</td>
                <td class="boxGrey2">
                    <input name="position" id="position" type="text" class="nd3" value="<?= isset($name) && !empty($name) ? $name : '' ?>"/>
                </td>
            </tr>
            <?php if ($form_type == 'ckeditor') : ?>
                <tr>
                    <td class="boxGrey" valign="top">Nội dung</td>
                    <td class="boxGrey2">
                        <div id="tab-container-1">
                            <div class="clear" style="clear:both;"></div>
                            <div class="tab"
                                 id="tieng-viet">
                                <textarea name="content" id="ckeditor" cols="75"
                                          rows="20"><?php echo (isset($vi[0]->content) && !empty($vi[0]->content)) ? stripslashes($vi[0]->content) : '' ?></textarea>
                                <?php if($isEdit) : ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php elseif ($form_type == 'textarea') : ?>
                <tr>
                    <td class="boxGrey">Nội dung</td>
                    <td class="boxGrey2"><textarea name="content" class="nd3"
                                                   id="content"
                                                   type="text">
                            <?php echo isset($vi[0]->content) && !empty($vi[0]->content) ? $vi[0]->content : '' ?></textarea>
                    </td>
                </tr>
            <?php elseif ($form_type == 'file') : ?>
                <tr>
                    <td class="boxGrey">Nội dung</td>
                    <td class="boxGrey2">
                        <?php echo isset($vi[0]->content) && !empty($vi[0]->content) ? '<img src="resize.php?from=..' . $vi[0]->content . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                        <input type="file" name="content" id="picture"
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
<?php endif; ?>
<?php if (!$isEdit && !$isInsert) {
    echo $dbf->returnTitleMenuTable($titleMenu);
    if(isset($_POST['delete']) && !empty($_POST['id'])) {
        $ids = $_POST['id'];
        if(count($ids) > 1) {
            $dbf->deleteData(prefixTable .'setting', 'id', $ids);
            $dbf->deleteData(prefixTable .'setting_desc', 'id', $ids);
        } else {
            $id = end($ids);
            $dbf->deleteData(prefixTable .'setting', 'id', $id);
            $dbf->deleteData(prefixTable .'setting_desc', 'id', $id);
        }
        $msg = "Dã xóa thành công " . count($ids) . ' item';
        echo '<span class="txtdo" align="center">'.$msg.'</span>';
    }
    if(!empty($_SESSION['message'])) {
        echo '<span class="txtdo" align="center">'.$_SESSION['message'].'</span>';
        unset($_SESSION['message']);
    }
    $url      = 'setting.php?';
    $PageSize = 50;
    $mang     = $dbf->paging(prefixTable . 'setting', 'display = 1', 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
    ?>
    <div id="panelAction" class="panelAction">
        <div class="panelActionContent" style="float: left; padding: 5px">
            <table id="panelTable" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cellAction1">
                        <a href="/_pnsdotvn/setting.php?insert" class="new-style">Thêm</a>
                    </td>
                    <td class="cellAction1 deleleAction">
                        <a href="javascript:void(0);" class="new-style">Xóa</a>
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

