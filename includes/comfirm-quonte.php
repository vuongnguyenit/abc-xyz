<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}

$html = $pns->buildBreadcrumb($def, $_LNG);
$pns->showHTML($html);
?>
<form class="well form-horizontal" action="/xac-nhan-bao-gia" method="post" id="confirm-quonte">
    <fieldset>
        <input type="hidden" value="quotes" name="action" id="action">
        <input type="hidden" value="confirm" name="type" id="type">
        <?php
            $list = '';
            foreach ($_SESSION['quontes'] as $item) {
                $list .= (string)$item['product_id'] .'-'. (string)$item['quantity'] .'#';
            }
        ?>
        <input type="hidden" value="<?= $list ?>" name="list" id="list">
        <!-- Form Name -->
        <legend>Thông tin khách hàng!</legend>
        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-2 control-label">Họ tên</label>
            <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-user"></i>
                    </span>
                    <input name="name" placeholder="Họ tên"
                           class="form-control" type="text" id="name">
                </div>
            </div>
        </div>
        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-2 control-label">E-Mail</label>
            <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-envelope"></i>
                    </span>
                    <input name="email" placeholder="E-Mail"
                           class="form-control" type="text" id="email">
                </div>
            </div>
        </div>
        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-2 control-label">Điện thoại</label>
            <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-earphone"></i>
                    </span>
                    <input name="phone" placeholder="(845)555-1212"
                           class="form-control" type="text" id="phone">
                </div>
            </div>
        </div>
        <!-- Text area -->
        <div class="form-group">
            <label class="col-md-2 control-label">Nội dung</label>
            <div class="col-md-7 inputGroupContainer">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-pencil"></i>
                    </span>
                    <textarea rows="10" class="form-control" name="content"
                              placeholder="Nội dung" id="content"></textarea>
                </div>
            </div>
        </div>
        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-4">
                <button type="button" class="btn btn-warning send-quonte">Xác nhận báo giá
                    <span class="glyphicon glyphicon-send"></span>
                </button>
            </div>
        </div>
    </fieldset>
</form>
