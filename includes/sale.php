<?php
if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}
 $html = $pns->buildBreadcrumb($def, $_LNG);
 $pns->showHTML($html);
$conditions = [];
$conditions[] = [
    'field' => 'sale_ajax',
    'type' => '=',
    'value' => 1,
];
$conditions[] = [
    'field' => 'outofstock',
    'type' => '=',
    'value' => 0,
];
$orderBys = [];
$orderBys[] = [
    'field' => 'modified',
    'type' => 'desc',
];
$productSale = $pns->selectData(prefixTable . 'product', $conditions, $orderBys, 4, 0);
$totalproductSale = $pns->selectData(prefixTable . 'product', $conditions, $orderBys);
?>
<section class="products search">
    <div class="content">
        <?php if ($productSale) {
            foreach ($productSale as $product) {
                $conditions = [
                    [
                        'field' => 'id',
                        'type' => '=',
                        'value' => $product['id'],
                    ],
                ];
                $pDesc = $pns->selectData(prefixTable . 'product_desc', $conditions);
                if ($pDesc) {
                    $pDesc = reset($pDesc);
                    $pDesc = $pDesc['rewrite'];
                    $href = '/san-pham/' . $pDesc . '-' . $product['id'] . '.html';
                }
                $imgUrl = explode(';', $product['picture']);
                $imgUrl = reset($imgUrl);
                ?>

                <div class="block box col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="border box-img"><span
                                class="over-layer-0"></span>
                        <div class="picture">
                            <a href="<?= $href ?>"
                               title="<?= $product['name'] ?>">
                                <img alt="<?= $product['name'] ?>"
                                     src="<?= $imgUrl ?>"
                                     title="<?= $product['name'] ?>">
                            </a>
                        </div>
                        <div class="proname">
                            <a href="<?= $href ?>"
                               title="<?= $product['name'] ?>"><?= $product['name'] ?></a>
                        </div>
                        <div class="gr-price">
                            <span class="list-price"><span><?= number_format($product['list_price']) ?>
                                    đ</span></span>
                            <span class="price"><span><?= number_format($product['price']) ?>
                                    đ</span></span>
                        </div>
                    </div>
                </div>

            <?php }
        } ?>
    </div>
    <? if(count($totalproductSale) > 4) : ?>
        <div class="text-center">
            <a href="javascript:void(0);" id="loadMore" class="btn btn-default">Xem thêm</a>
        </div>
    <?php endif; ?>
</section>
