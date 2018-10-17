<?php
include("../class/class.utls.php");
@include("../class/language/lang.vi-VN.php");
require_once("index_table.php");
require_once  '../class/class.cart.php';
$utls      = new Utilities();
$col       = [
    "id"            => "ID",
    "order_code"    => "Mã đơn hàng",
    "billing_name"  => "Người đặt hàng",
    "shipping_name" => "Người nhận hàng",
    "cost"          => "Tổng tiền",
    "ordered"       => "Ngày đặt",
    "status"        => "Trạng thái",
];
$iscatURL  = (isset($_GET['caturl']) && !empty($_GET['caturl'])) ? $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ ĐƠN HÀNG';

if ($isDelete) {
    $arrayid  = substr($_POST['arrayid'], 0, -1);
    $id_array = explode(',', $arrayid);
    $dbf->deleteDynamic(prefixTable . 'order_detail', 'order_id in (' . $arrayid . ')');
    $affect = $dbf->deleteDynamic(prefixTable . 'order', 'id in (' . $arrayid . ')');
    if ($affect > 0) {
        $msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
    }
}

/*if($subInsert)
{
  	
}*/

if ($subUpdate) {
    if (isset($_GET["edit"]) && $_GET["edit"] > 0) {
        $id = (int) $_GET["edit"];
        $q  = $dbf->Query('SELECT status_memo, status, ordered FROM ' . prefixTable . 'order WHERE id = ' . $id . ' LIMIT 1');
        if ($dbf->totalRows($q)) {
            $r   = $dbf->nextObject($q);
            $tmp = unserialize($r->status_memo);
            if (empty($tmp) || !is_array($tmp) || count($tmp) == 0) {
                $tmp[] = [
                    'status' => $r->status,
                    'date'   => $r->ordered,
                ];
            }
            $data['order_memo'] = $_POST['order_memo'];
            if (isset($_POST['status'])) {
                $data['status'] = $_POST['status'];
            }
            $data['delivered']   = (isset($data['status']) && $data['status'] == 'Completed' ? time() : '');
            $data["modified"]    = time();
            $data["modified_by"] = 1;
            $end                 = end($tmp);
            if (isset($end['status']) && $end['status'] != $data['status'] && isset($data['status'])) {
                $tmp[] = [
                    'status' => $data['status'],
                    'date'   => $data["modified"],
                ];
            }
            /* BEGIN : ADD POINT */
            if (POINT_REWARD && $data['status'] == 'Completed') {
                $q1 = $dbf->Query('SELECT t2.content FROM dynw_setting t1 INNER JOIN dynw_setting_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t1.type = "money2point" LIMIT 1');
                if ($dbf->totalRows($q1)) {
                    $r1 = $dbf->nextObject($q1);
                    if ($r1->content > 0) {
                        $q2 = $dbf->Query('SELECT cost, csid FROM dynw_order WHERE id = ' . $id . ' AND status != "Canceled" AND rewarded = 0 LIMIT 1');
                        if ($dbf->totalRows($q2)) {
                            $r2 = $dbf->nextObject($q2);
                            if ($r2->cost > $r1->content) {
                                $point = floor($r2->cost / $r1->content);
                                if ($point > 0) {
                                    $p = [
                                        'member'  => $r2->csid,
                                        'action'  => 1,
                                        'point'   => $point,
                                        'related' => 1,
                                        'rid'     => $id,
                                        'status'  => 1,
                                        'added'   => time(),
                                    ];
                                    $dbf->insertTable(prefixTable . 'point_history', $p);
                                    $dbf->Query('UPDATE dynw_customer SET point = point + ' . $point . ' WHERE id = ' . $r2->csid . ' LIMIT 1');
                                    $dbf->Query('UPDATE dynw_order SET rewarded = 1 WHERE id = ' . $id . ' LIMIT 1');
                                }
                            }
                        }
                    }
                }
            }
            /* END : ADD POINT */
            /* END : HOAN LAI & HUY DIEM TANG DO HUY DON HANG */
            if (POINT_REWARD && $data['status'] == 'Canceled') {
                $q = $dbf->Query('SELECT point, point_amount, point_award, csid, rewarded FROM dynw_order WHERE id = ' . $id . ' LIMIT 1');
                if ($dbf->totalRows($q)) {
                    $r = $dbf->nextObject($q);
                    if ($r->rewarded == 0) {
                        if ($r->point > 0 && $r->point_amount > 0) {
                            $p = [
                                'member'  => $r->csid,
                                'action'  => 4,
                                'point'   => $r->point,
                                'related' => 1,
                                'rid'     => $id,
                                'status'  => 1,
                                'added'   => time(),
                            ];
                            $dbf->insertTable(prefixTable . 'point_history', $p);
                            $dbf->Query('UPDATE dynw_customer SET point = point + ' . $r->point . ' WHERE id = ' . $r->csid . ' LIMIT 1');
                        }
                    }
                    if ($r->rewarded == 1) {
                        if ($r->point > 0 && $r->point_amount > 0) {
                            $p = [
                                'member'  => $r->csid,
                                'action'  => 4,
                                'point'   => $r->point,
                                'related' => 1,
                                'rid'     => $id,
                                'status'  => 1,
                                'added'   => time(),
                            ];
                            $dbf->insertTable(prefixTable . 'point_history', $p);
                            $dbf->Query('UPDATE dynw_customer SET point = point + ' . $r->point . ' WHERE id = ' . $r->csid . ' LIMIT 1');
                        }
                        if ($r->point_awrard > 0) {
                            $p = [
                                'member'  => $r->csid,
                                'action'  => 3,
                                'point'   => $r->point_award,
                                'related' => 1,
                                'rid'     => $id,
                                'status'  => 1,
                                'added'   => time(),
                            ];
                            $dbf->insertTable(prefixTable . 'point_history', $p);
                            $dbf->Query('UPDATE dynw_customer SET point = point - ' . $r->point_award . ' WHERE id = ' . $r->csid . ' LIMIT 1');
                        }
                    }
                }
            }
            /* END : HOAN LAI & HUY DIEM TANG DO HUY DON HANG */
            $data['status_memo'] = serialize($tmp);
            $affect              = $dbf->updateTable(prefixTable . "order", $data, "id=" . $id);
            if ($affect > 0) {
                $msg = "Đã cập nhật ($affect) dòng trong cơ sở dữ liệu.";
            }
        }
    }
}

if ($isEdit) {
    #$rst = $dbf->getDynamic(prefixTable. "order", "id='" . (int) $_GET['edit'] . "'", "");
    $rst = $dbf->getDynamicJoin(prefixTable . 'order', prefixTable . 'customer', ['email'   => 'email',
                                                                                  'groupid' => 'groupid',
    ], 'inner join', 't1.id="' . (int) $_GET['edit'] . '"', '', 't2.id = t1.csid');
    if ($rst) {
        $row                   = $dbf->nextData($rst);
        $id                    = $row["id"];
        $csid                  = $row["csid"];
        $order_code            = $row["order_code"];
        $cost                  = $row["cost"];
        $ordered               = date('d/m/Y', $row["ordered"]);
        $email                 = $row["email"];
        $groupid               = $row["groupid"];
        $billing_name          = $row["billing_name"];
        $billing_phone         = $row["billing_phone"];
        $billing_mobile        = $row["billing_mobile"];
        $billing_full_address  = $row["billing_full_address"];
        $shipping_name         = $row["shipping_name"];
        $shipping_phone        = $row["shipping_phone"];
        $shipping_mobile       = $row["shipping_mobile"];
        $shipping_full_address = $row["shipping_full_address"];
        $sub_total             = $row["sub_total"];
        $tax_rate              = $row["tax_rate"];
        $tax_amount            = $row["tax_amount"];

        $company         = $row["company"];
        $company_address = $row["company_address"];
        $tax_code        = $row["tax_code"];
        $payment_id      = $row["payment_id"];
        $payment_name    = $row["payment_name"];
        $order_note      = $row["order_note"];

        $status      = $row["status"];
        $order_memo  = $row["order_memo"];
        $status_memo = unserialize($row["status_memo"]);
    }
}
?>
<?php $dbf->normalForm("frm", ["action" => "", "method" => "post"]); ?>
<?php if ($isEdit) { ?>
    <!-- form -->
    <div id="panelForm" class="panelForm">
        <table id="mainTable" cellpadding="0" cellspacing="0">
            <?php echo $dbf->returnTitleMenu($titleMenu) ?>
            <tr>
                <td colspan="4" class="txtdo"
                    align="center"><?php echo $msg ?></td>
            </tr>
            <tr>
                <td class="boxGrey">Mã đơn hàng</td>
                <td class="boxGrey2"><strong><?php echo $order_code ?></strong>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Tổng tiền</td>
                <td class="boxGrey2">
                    <strong><?php echo number_format($cost, 0, ",", ".") . ' VNĐ' ?></strong>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Ngày đặt</td>
                <td class="boxGrey2"><strong><?php echo $ordered ?></strong>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Hình thức thanh toán</td>
                <td class="boxGrey2"><strong>
                        <?php #echo $payment_name?>
                        <?php echo $payment_name ?></strong></td>
            </tr>
            <tr>
                <td class="boxGrey">Thông tin người đặt hàng</td>
                <td class="boxGrey2" style="line-height: 20px;">Họ & tên:
                    <strong><?php echo $billing_name ?></strong>
                    [<?php echo isset($groupid) && $groupid == 2 ? 'Thành viên' : 'Khách lẻ' ?>
                    ]<br/>
                    Email: <?php echo $email ?><br/>
                    Điện thoại: <?php echo $billing_phone ?><br/>
                    Di động: <?php echo $billing_mobile ?><br/>
                    Địa chỉ: <?php echo $billing_full_address ?></td>
            </tr>
            <tr>
                <td class="boxGrey">Thông tin người nhận hàng</td>
                <td class="boxGrey2" style="line-height: 20px;">Họ & tên:
                    <strong><?php echo $shipping_name ?></strong><br/>
                    Điện thoại: <?php echo $shipping_phone ?><br/>
                    Di động: <?php echo $shipping_mobile ?><br/>
                    Địa chỉ: <?php echo $shipping_full_address ?></td>
            </tr>
            <!--<tr>
      <td class="boxGrey">Thông tin xuất hóa đơn</td>
      <td class="boxGrey2" style="line-height: 20px;">Công ty: <?php #echo !empty($company) ? '<strong>' . $company . '</strong>' : '-- Không có thông tin --'?><br />
        Địa chỉ: <?php #echo !empty($company_address) ? $company_address : '-- Không có thông tin --'?><br />
        MST: <?php #echo !empty($tax_code) ? $tax_code : '-- Không có thông tin --'?></td>
    </tr>-->
            <tr>
                <td class="boxGrey">Chi tiết đơn hàng</td>
                <td class="boxGrey2"><?php $detail = $dbf->getArray(prefixTable . 'order_detail', 'order_id="' . $id . '" and customer_id="' . $csid . '"', '', 'stdObject');
                    if (!empty($detail) && count($detail) > 0) { ?>
                        <table id="mainTable" cellpadding="4" cellspacing="0"
                               border="1" style="border-collapse:collapse;">
                            <tr>
                                <td width="5%" align="center">
                                    <strong>ID</strong></td>
                                <td width="40%" align="center"><strong>Sản
                                        phẩm</strong></td>
                                <td width="10%" align="center"><strong>Đơn
                                        giá</strong></td>
                                <td width="10%" align="center">
                                    <strong>Giảm</strong></td>
                                <td width="10%" align="center"><strong>Giá
                                        bán</strong></td>
                                <td width="10%" align="center"><strong>Số
                                        lượng</strong></td>
                                <td width="10%" align="center"><strong>Thành
                                        tiền</strong></td>
                            </tr>
                            <?php foreach ($detail as $item) {
                                $_id  = explode('|', $item->product_id);
                                $info = unserialize($item->info); ?>
                                <tr>
                                    <td align="center"
                                        valign="middle"><?php echo $_id[0] ?></td>
                                    <td><?php echo stripslashes($item->name) ?>
                                        <div style="font-size: 11px; margin-top: 5px; line-height: 15px;">
                                            <?php if (!empty($info['code']))
                                                echo '<label>Mã sản phẩm:</label> ' . stripslashes($info['code']) . '<br />' ?>
                                            <?php
                                                if (!empty($_id[2])) {
                                                    if(!empty($cart->buildColorCode($_id[2]))) {
                                                        echo '<label>Màu sắc:</label> ' . $_id[2];
                                                    }
                                                    if(!empty($cart->buildSizeCode($_id[2]))) {
                                                        echo '<label>Kích thước:</label> ' . $_id[2];
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td align="right"
                                        valign="middle"><?php echo number_format($item->list_price, 0, ",", ".") ?></td>
                                    <td align="center"
                                        valign="middle"><?php echo $item->sale_off ?>
                                        %
                                    </td>
                                    <?php
                                        $product = $cart->getProduct($_id[0]);
                                        if(!empty($_id[2])) {
                                            $price = $cart->getPrice($_id[0], $_id[2]);
                                        } else {
                                            $price = $item->price;
                                            if(!empty($product['wholesale'])) {
                                                $wholesale = unserialize($product['wholesale']);
                                                $price = $cart->getPriceByWholesale($wholesale, $item->quantity);
                                            }
                                        }
                                    ?>
                                    <td align="right"
                                        valign="middle"><?php echo number_format($price, 0, ",", ".") ?></td>
                                    <td align="center"
                                        valign="middle"><?php echo $item->quantity ?></td>
                                    <td align="right"
                                        valign="middle"><?php echo number_format($price * $item->quantity, 0, ",", ".") ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td align="right" colspan="6">Tổng tiền :</td>
                                <td align="right"><?php echo number_format($sub_total, 0, ",", ".") ?></td>
                            </tr>
                            <!--<tr>
            <td align="right" colspan="6">Thuế GTGT (<?php #echo $tax_rate?>%) : </td>
            <td align="right"><?php #echo number_format($tax_amount,0,",",".")?></td>
          </tr>-->
                            <?php if (isset($groupid) && $groupid == 2) : ?>
                                <tr>
                                    <td align="right" colspan="6">Sử dụng điểm
                                        tặng (<?php echo $row['point'] ?> điểm)
                                        :
                                    </td>
                                    <td align="right"><?php echo number_format($row['point_amount'], 0, ",", ".") ?></td>
                                </tr>
                            <?php endif ?>
                            <tr>
                                <td align="right" colspan="6">Tổng cộng :</td>
                                <td align="right">
                                    <strong><?php echo number_format($cost, 0, ",", ".") ?></strong>
                                </td>
                            </tr>
                        </table>
                    <?php } ?></td>
            </tr>
            <tr>
                <td class="boxGrey">Khách hàng ghi chú đơn hàng</td>
                <td class="boxGrey2"><?php echo $order_note ?></td>
            </tr>
            <tr>
                <td class="boxGrey">Tình trạng đơn hàng</td>
                <td class="boxGrey2"><?php echo($status == 'Completed' ? 'Đã hoàn tất' : ($status == 'Canceled' ? 'Đã hủy bỏ' : ($dbf->SelectWithTable(prefixTable . 'order_status', '1=1', '', 'status', 'name', 'id', $status, ['class' => 'cbo'])))) ?></td>
            </tr>
            <tr>
                <td class="boxGrey">Ghi chú đơn hàng</td>
                <td class="boxGrey2"><textarea id="order_memo" name="order_memo"
                                               class="nd2"><?php echo isset($order_memo) ? $order_memo : '' ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="boxGrey">Nhật ký đơn hàng</td>
                <td class="boxGrey2">
                    <?php
                    if (isset($status_memo) && is_array($status_memo) && count($status_memo) > 0) {
                        foreach ($status_memo as $sm) {
                            echo '<p>' . date('d-m-Y H:i', $sm['date']) . ' : ' . $sm['status'] . '</p>';
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="boxGrey"></td>
                <td height="1" align="center" class="boxGrey2">
                    <div id="insert"><?php echo(($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : "")); ?></div>
                </td>
            </tr>
            <tr>
                <td class="boxGrey" colspan="2"><span
                            style="color:#DA251C">(*)</span> Bắt buộc nhập
                </td>
            </tr>
        </table>
    </div>
    <?php if ($isInsert && !empty($msg))
        echo '<script type="text/javascript">huy();</script>' ?>
    <!-- end Form-->
    <?php
}
?>
<?php
if (!$isEdit && !$isInsert) {
    echo $dbf->returnTitleMenuTable($titleMenu);
    $url  = "order.php?" . ((!empty($iscatURL)) ? '&caturl=' . $iscatURL : '');
    $mang = $dbf->pagingJoin(prefixTable . "order", prefixTable . "order_status", ["name" => "status"], "inner join", '1=1', "t1.ordered desc", $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, "t1.status=t2.id");
    echo $dbf->panelAction($mang[1]);

    ?>

    <!-- view -->
    <div id="panelView" class="panelView">
        <?php $dbf->normalView($col, "order.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg = "") ?>
    </div>
    <!-- end view-->
    <?php
}
?>
    <input type="hidden" name="arrayid" id="arrayid"/>
    </form>
    </body>
    </html><?php ob_end_flush() ?>