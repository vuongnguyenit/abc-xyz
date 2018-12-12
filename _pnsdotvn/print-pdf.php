<?php

require_once './lib/vendor/autoload.php';

$pricePost = $_POST['price'];
$totalPost = $_POST['total'];
$quantityPost = $_POST['quantity'];
$imagePost = $_POST['image'];
$namePost = $_POST['name'];
$arrayProcts = array_column($pricePost, 'id');
$data = [];
foreach ($pricePost as $price) {
    $key = array_search($price['id'], $arrayProcts);
    $data[$arrayProcts[$key]]['price'] = $price['price'];
}

foreach ($totalPost as $price) {
    $key = array_search($price['id'], $arrayProcts);
    $data[$arrayProcts[$key]]['total'] = $price['total'];
}
foreach ($quantityPost as $quantity) {
    $key = array_search($quantity['id'], $arrayProcts);
    $data[$arrayProcts[$key]]['qty'] = $quantity['qty'];
}
foreach ($imagePost as $image) {
    $key = array_search($image['id'], $arrayProcts);
    $data[$arrayProcts[$key]]['img'] = $image['img'];
}
foreach ($namePost as $name) {
    $key = array_search($name['id'], $arrayProcts);
    $data[$arrayProcts[$key]]['name'] = $name['name'];
}

$tax = @$_POST['tax'];
$subtotal = array_sum(array_column($totalPost, 'total'));
$priceTax = 0;
if (empty($tax)) {
    $total = $subtotal;
} else {
    $priceTax = $subtotal / 100 * $tax;
    $total = $subtotal + $priceTax;
}

$mpdf = new mPDF('utf-8', 'A4', 0, '', 5, 5, 5, 5);
$mpdf->keep_table_proportions = false;
$html = '<html lang="en">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style>
                div.w100per {width: 900px;}
                table th {border: 1px solid grey; text-align: center; width: 10%}
                table td {border: 1px solid grey; text-align: center; width: 10%}
            </style>
            <body style="font-size: 13px;">
            <main>';
$html .= '<div class="w100per" style="text-align: center">
    <h2>BẢNG BÁO GIÁ THIẾT BỊ</h2>
</div>';
$html .= '<div class="w100per">
            <table style="overflow: wrap" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="item">
                    <th>Hình ảnh</th>
                    <th style="width: 40%">Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá bán lẻ</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>';
$html .= '<tbody>';
foreach ($data as $pid => $item) {
    $html .= '<tr>';
    $html .= '<td style="text-align: center"><img src="' . $item['img'] . '" alt="" width="6%"></td>';
    $html .= '<td style="text-align: left">' . $item['name'] . '</td>';
    $html .= '<td style="text-align: center;width: 10%">' . $item['qty'] . '</td>';
    $html .= '<td style="text-align: right">' . number_format($item['price']) . 'VNĐ</td>';
    $html .= '<td style="text-align: right">' . number_format($item['total']) . 'VNĐ</td>';
    $html .= '</tr>';
}
$html .= '<tr>
    <td colspan="4" style="text-align: right">Tồng</td>
    <td>' . number_format($subtotal) . 'VNĐ</td>
</tr>';
$html .= '<tr>
    <td colspan="4" style="text-align: right">VAT</td>
    <td>' . $tax . '%</td>
</tr>';
$html .= '<tr>
    <td colspan="4" style="text-align: right">Tiền VAT</td>
    <td>' . number_format($priceTax) . 'VNĐ</td>
</tr>';
$html .= '<tr>
    <td colspan="4" style="text-align: right">Tổng cộng</td>
    <td>' . number_format($total) . 'VNĐ</td>
</tr>';
$html .= '</tbody>';
$html .= '</table></div>';
$mpdf->WriteHTML($html);
$mpdf->SetHTMLFooter('
            <table width="100%">
                <tr>
                    <td align="center">Helllo</td>
                </tr>
            </table>');

$mpdf->SetHTMLHeader('<table width="100%">
                <tr>
                    <td align="center">Helllo</td>
                </tr>
            </table>');
$file_name = 'pdf/' . time() . '.pdf';
$mpdf->Output($file_name, 'D');
?>


