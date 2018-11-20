<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}

$html = $pns->buildBreadcrumb($def, $_LNG);
$pns->showHTML($html);
?>
<?php if (!empty($_SESSION['quontes'])) : ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-xs-9">
                                <h5>
                                    <span class="glyphicon glyphicon-shopping-cart"></span>Báo
                                    giá
                                </h5>
                            </div>
                            <div class="col-xs-3">
                                <a href="/bang-gia-san-pham.html"
                                   class="btn btn-primary btn-sm btn-block">
                                    <span class="glyphicon glyphicon-share-alt"></span>
                                    Thêm báo giá khác
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <?php foreach ($_SESSION['quontes'] as $i => $row) { ?>
                        <?php
                        $conditions = [
                            [
                                'field' => 'id',
                                'type'  => '=',
                                'value' => $row['product_id'],
                            ],
                        ];
                        $product    = $dbf->selectData(prefixTable . 'product', $conditions);
                        $product    = reset($product);
                        $imgUrl     = explode(';', $product['picture']);
                        $imgUrl     = reset($imgUrl);
                        ?>
                        <div class="row <?= $i == (count($_SESSION['quontes']) - 1) ? '' : 'border-bottom' ?> " style="padding: 10px">
                            <div class="col-xs-2">
                                <img width="60%" src="<?= $imgUrl ?>"
                                     alt="<?= $product['name'] ?>"
                                     class="img-responsive"/>
                            </div>
                            <div class="col-xs-4">
                                <h4 class="product-name">
                                    <strong><?= $product['name'] ?></strong>
                                </h4>
                            </div>
                            <div class="col-xs-6">
                                <div class="col-xs-6 text-right">
                                </div>
                                <div class="col-xs-4">
                                    <input type="number"
                                           class="form-control input-sm quonte-quantity"
                                           value="<?= $row['quantity'] ?>" data-product-id="<?= $row['product_id'] ?>">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button"
                                            class="btn btn-link btn-xs delete-quonte"
                                            data-product-id="<?= $row['product_id'] ?>">
                                        <span class="glyphicon glyphicon-trash"> </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="panel-footer">
                    <div class="row text-center">
                        <div class="col-xs-3">
                            <button type="button" class="btn btn-default btn-sm btn-block update-quonte">
                                Cập nhật báo giá
                            </button>
                        </div>
                        <div class="col-xs-6"></div>
                        <div class="col-xs-3">
                            <a href="/xac-nhan-bao-gia.html" class="btn btn-success btn-block">
                                Yêu cầu báo giá&nbsp;
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
<div class="row">
    <div class="col-xs-12">
        <p class="text-center" style="padding: 15px">
            Không có sản phẩm nào trong báo giá - <a href="/bang-gia-san-pham.html">Tiếp tục thêm báo giá</a>
        </p>
    </div>
</div>
<?php endif; ?>
