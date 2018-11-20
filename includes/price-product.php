<?php

if (!defined('PHUONG_NAM_SOLUTION')) {
    header('Location: /errors/403.shtml');
    die();
}
require_once 'action/class.pagination.php';
// $html = $pns->buildBreadcrumb($def, $_LNG);
// $pns->showHTML($html);
$categories = $pns->getDynamic(prefixTable . 'category', ' parentid = 0');
$brand      = $pns->getDynamic(prefixTable . 'brand');

?>

<div class="manufacturer mt10">
    <div class="block">
        <form class="form-inline" id="price-product" name="frmSearch"
              action="/bang-gia-san-pham.html"
              method="get">
            <div class="form-group">
                <?php $codeValue = isset($_GET['code']) ? $_GET['code'] : '' ?>
                <input type="text" class="form-control"
                       name="<?= isset($_GET['code']) ? 'code' : '' ?>"
                       id="code" placeholder="Nhập mã sản phẩm"
                       value="<?= $codeValue ?>">
            </div>
            <div class="form-group">
                <?php $nameValue = isset($_GET['name']) ? $_GET['name'] : '' ?>
                <input type="text" class="form-control"
                       name="<?= isset($_GET['name']) ? 'name' : '' ?>"
                       id="name" placeholder="Nhập tên sản phẩm"
                       value="<?= $nameValue ?>">
            </div>
            <div class="form-group">
                <select id="category"
                        name="<?= isset($_GET['category']) ? 'category' : '' ?>"
                        class="list">
                    <option value=''>Danh mục sản phẩm</option>
                    <?php $categoryValue = isset($_GET['category']) ? $_GET['category'] : '' ?>
                    <?php
                    if ($dbf->totalRows($categories)) {
                        while ($row = $dbf->nextObject($categories)) {
                            $selected = $categoryValue == $row->id ? 'selected' : '';
                            echo "<option {$selected} value='{$row->id}'>{$row->name}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <select id="brand"
                        name="<?= isset($_GET['brand']) ? 'brand' : '' ?>"
                        class="list">
                    <option value=''>Tất cả nhà cung cấp</option>
                    <?php $brandValue = isset($_GET['brand']) ? $_GET['brand'] : '' ?>
                    <?php
                    if ($dbf->totalRows($brand)) {
                        while ($row = $dbf->nextObject($brand)) {
                            $selected = $brandValue == $row->id ? 'selected' : '';
                            echo "<option {$selected} value='{$row->id}'>{$row->name}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-submit">Tìm
                kiếm
            </button>
        </form>
    </div>
</div>
<?php
if (!isset($_GET['page']) || $_GET['page'] == 0) {
    $page = 1;
}
else {
    $page = (int) $_GET['page'];
}
$numPerPage = 10;
$offset     = ($page - 1) * $numPerPage;
$conditions = [];
if (isset($_GET['code']) && $_GET['code'] != '') {
    $conditions[] = [
        'field' => 'code',
        'type'  => '=',
        'value' => $_GET['code'],
    ];
}
if (isset($_GET['name']) && $_GET['name'] != '') {
    $conditions[] = [
        'field' => 'name',
        'type'  => ' like ',
        'value' => '%' . $_GET['name'] . '%',
    ];
}
if (isset($_GET['category']) && $_GET['category'] != '') {
    $conditions[] = [
        'field' => 'cid',
        'type'  => '=',
        'value' => $_GET['category'],
    ];
}
if (isset($_GET['brand']) && $_GET['brand'] != '') {
    $conditions[] = [
        'field' => 'brand',
        'type'  => '=',
        'value' => $_GET['brand'],
    ];
}
$searchDataTotal = $dbf->selectData(prefixTable . 'product', $conditions);
$searchDataTotal = count($searchDataTotal);
$searchData      = $dbf->selectData(prefixTable . 'product', $conditions, [], $numPerPage, $offset);
?>
<div class="manufacturer mt10">
    <div class="block">
        <table class="table" width="100%">
            <?php if ($searchData) : ?>
                <thead style="font-weight: bold">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Nhà sản xuất</th>
                    <th>Báo giá</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($searchData as $row): ?>
                    <?php
                    $conditions = [
                        [
                            'field' => 'id',
                            'type'  => '=',
                            'value' => $row['id'],
                        ],
                    ];
                    $pDesc      = $dbf->selectData(prefixTable . 'product_desc', $conditions);
                    if ($pDesc) {
                        $pDesc = reset($pDesc);
                        $pDesc = $pDesc['rewrite'];
                        $href  = '/san-pham/' . $pDesc . '-' . $row['id'] . '.html';
                    }
                    $imgUrl = explode(';', $row['picture']);
                    $imgUrl = reset($imgUrl);
                    ?>
                    <tr>
                        <td>
                            <a href="<?= $href ?>">
                                <img src="<?= $imgUrl ?>" width="40"
                                     height="40"/>
                            </a>
                        </td>
                        <td><?= $row['code'] ?></td>
                        <td><a href="<?= $href ?>"><?= $row['name'] ?></a></td>
                        <?php
                        $conditions = [
                            [
                                'field' => 'id',
                                'type'  => '=',
                                'value' => $row['brand'],
                            ],
                        ];
                        $brand      = $dbf->selectData(prefixTable . 'brand', $conditions);
                        if ($brand) {
                            $brand = reset($brand);
                            $brand = $brand['name'];
                        }
                        else {
                            $brand = 'N\A';
                        }
                        ?>
                        <td><?= $brand ?></td>
                        <td><a href="javascript:void(0)" class="add-quotes btn btn-sm btn-primary" data-product-id="<?= $row['id'] ?>">Thêm báo giá</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php else : ?>
                <thead>
                <tr>
                    <th>Không có kết quả tìm kiếm nào</th>
                </tr>
                </thead>
            <?php endif; ?>
        </table>
        <?php
        $config = [
            'total' => $searchDataTotal,
            'limit' => $numPerPage,
            'full' => false,
            'path' => '/bang-gia-san-pham.html'
        ];
        $page = new Pagination($config);
        ?>
        <nav aria-label="Page navigation example">
            <?= $page->getPagination(); ?>
        </nav>
    </div>
</div>
