<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}

$html = $pns->buildBreadcrumb($def, $_LNG);
$pns->showHTML($html);
?>
<div class="container">
    <?php if (!empty($_SESSION['quontes']['list'])) : ?>
        <table id="quontes" class="table table-hover table-condensed">
            <thead>
            <tr>
                <th style="width:70%">Sản phẩm</th>
                <th style="width:15%">Số lượng</th>
                <th style="width:15%"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['quontes']['list'] as $productId) { ?>
                <?php
                $conditions = [
                    [
                        'field' => 'id',
                        'type' => '=',
                        'value' => $productId,
                    ]
                ];
                $product = $dbf->selectData(prefixTable . 'product', $conditions);
                $product = reset($product);
                $imgUrl = explode(';', $product['picture']);
                $imgUrl = reset($imgUrl);
                ?>
                <tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-2 hidden-xs"><img src="<?= $imgUrl ?>" alt="..." class="img-responsive"/>
                            </div>
                            <div class="col-sm-10 margin-top-2">
                                <h4 class="nomargin"><?= @$product['name'] ?></h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Quantity">
                        <input type="number" class="form-control text-center" value="1">
                    </td>
                    <td class="actions text-center" data-th="">
                        <button class="btn btn-danger btn-sm delete-quontes" data-product-id="<?= $productId ?>"><i
                                    class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2" class="hidden-xs"></td>
                <td><a href="#" class="btn btn-success btn-block">Báo giá <i class="fa fa-angle-right"></i></a></td>
            </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>
