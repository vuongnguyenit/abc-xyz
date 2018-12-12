<?php
define('MOD_DIR_UPLOAD', '/media/images/products/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/products/');

#include(dirname(dirname(__FILE__)) . '/plugins/editors/ckeditor/ckeditor.php');
#$ckeditor = new plgEditorCkeditor();

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . "/plugins/upload/class.upload.php");

$utls = new Utilities();
$col = [
    'id' => 'ID',
    'name' => 'Tên sản phẩm',
    'picture' => 'Hình',
    'cid' => 'Loại sản phẩm',
    'ordering' => 'Thứ tự',
    'created' => 'Ngày tạo',
    'modified' => 'Ngày sửa',
    'status' => 'Trạng thái',
];
$picture = '/media/images/others/imagehere.png';
$picture2 = '/media/images/others/imagehere.png';
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ DANH SÁCH SẢN PHẨM';
#$_product = array('vi-VN' => 'san-pham', 'en-US' => 'product');
$_product = ['vi-VN' => 'san-pham'];

if ($isDelete) {
    $arrayid = substr($_POST['arrayid'], 0, -1);
    $id_array = explode(',', $arrayid);
    $affect = $dbf->deleteDynamic(prefixTable . 'product', 'id in (' . $arrayid . ')');
    if ($affect > 0) {
        $dbf->deleteDynamic(prefixTable . 'product_desc', 'id in (' . $arrayid . ')');
        $dbf->deleteDynamic(prefixTable . 'url_alias', 'route = "product" and type = "detail" and related_id in (' . $arrayid . ')');
        $msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu';
    }
}
if ($isEdit) {
    $rst = $dbf->getDynamic(prefixTable . 'product', 'id = ' . (int)$_GET["edit"], '');
    $row = $dbf->nextObject($rst);
}
if ($subInsert) {
    $_j_color = '';
    $_j_price = '';
    $_picture = '';
    $err = 0;
    if (isset($_POST['cid']) && $_POST['cid'] > 0 && isset($_POST['name']) && !empty($_POST['name'])) {
        if (!empty($_POST['code'])) {
            $data['code'] = ($utls->checkValues($_POST['code']));
        }
        $data['name'] = ($utls->checkValues($_POST['name']));
        #$data['picture']     	= $utls->checkValues($_POST['pictureText']);
        /*for($i = 2; $i <= 3; $i++)
        {
              if($_POST['pictureText' . $i] <> $picture)
                $data['picture'] .= ';' . $utls->checkValues($_POST['pictureText' . $i]);
        }*/
        $data['cid'] = (int) $_POST['cid'];
        $data['brand'] = (int) $_POST['brand'];
        $data['supplier'] = (int) $_POST['supplier'];
        $data['list_price'] = $_POST['list_price'] > 0 ? (int) $_POST['list_price'] : ($_POST['price'] > 0 ? (int) $_POST['price'] : 0);
        $data['sale_off'] = $_POST['sale_off'] > 0 ? (int) $_POST['sale_off'] : 0;
        $data['price'] = $_POST['price'] > 0 ? (int) $_POST['price'] : $data['list_price'];
        $data['origin'] = ($utls->checkValues($_POST['origin']));
        $data['status'] = isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
        $data['qty'] = (int) $_POST['qty'];
        $data['outofstock'] = isset($_POST['outofstock']) ? $_POST['outofstock'] : 0;
        #$data['top']         	= isset($_POST['top']) && $_POST['top'] == 1 ? 1 : 0;
        $data['hot'] = isset($_POST['hot']) && $_POST['hot'] == 1 ? 1 : 0;
        #$data['favorite']       = isset($_POST['favorite']) && $_POST['favorite'] == 1 ? 1 : 0;
        $data['new'] = isset($_POST['new']) && $_POST['new'] == 1 ? 1 : 0;
        $data['promo'] = isset($_POST['promo']) && $_POST['promo'] == 1 ? 1 : 0;
        $data['flashsale'] = isset($_POST['flashsale']) && $_POST['flashsale'] == 1 ? 1 : 0;
        #$data['new_date']		= $data['new'] == 1 && isset($_POST['new_date']) && !empty($_POST['new_date']) ? $utls->checkValues($_POST['new_date']) : '';
        #$data['comingsoon']		= isset($_POST['comingsoon']) && $_POST['comingsoon'] == 1 ? 1 : 0;
        #$data['featured']     	= isset($_POST['featured']) && $_POST['featured'] == 1 ? 1 : 0;
        $data['ordering'] = isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 999;
        $data['ordering2'] = isset($_POST['ordering2']) && $_POST['ordering2'] >= 0 ? (int) $_POST['ordering2'] : 1;
        $data['created'] = date('Y-m-d H:i:s');
        $data['created_by'] = 1;
        $data['modified'] = $data['created'];
        $data['modified_by'] = $data['created_by'];
        #$data['origin'] 		= (isset($_POST['origin']) && in_array($_POST['origin'], array_keys($arrayOrigin)) ? $utls->checkValues($_POST['origin']) : '');
        #$data['warranty']		= ($utls->checkValues($_POST['warranty']));
        #$data['material']		= ($utls->checkValues($_POST['material']));
        #$data['capacity']		= ($utls->checkValues($_POST['capacity']));
        #$data['promo_txt']		= ($utls->checkValues($_POST['promo_txt']));
        $data['lname'] = ($utls->checkValues($_POST['lname']));
        $data['lcolor'] = ($utls->checkValues($_POST['lcolor']));

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
                    $pic['type'] = 'image';
                    $pic['path'] = MOD_ROOT_URL;
                    $pic['dir'] = $dir;
                    $pic['file'] = $arr_pic[$i];
                    $pic['change_name'] = TRUE;
                    $pic['w'] = 1200;
                    $pic['thum'] = TRUE;
                    $pic['change_name_thum'] = FALSE;
                    $pic['w_thum'] = 120;
                    $upload = new Upload($pic['file']);
                    $result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
                    if (empty($result['err'])) {
                        $_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
                        #$data['picture'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
                        #$data['filetype'] = $result['type'];
                        #$data['filesize'] = $result['size'];
                        if (WATERMARK && in_array($result['type'], [
                                'jpg',
                                'jpeg',
                                'png',
                            ])) {
                            $utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'], str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'], str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png", 300, $result['type']);
                        }
                    }
                }
            }

            #echo '<pre>';
            #print_r($_picture);
            #echo '</pre>';
            #exit;

        }
        $data['picture'] = isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0 ? implode(';', $_picture) : '/media/images/others/imagehere.png';

        #print_r($data['picture']);
        #exit;
        // END: UPLOAD PICTURE


        $tmp_po = '';
        if (isset($_POST['product_option_data']) && is_array($_POST['product_option_data']) && isset($_POST['product_option_id']) && is_array($_POST['product_option_id'])) {
            $po1 = $_POST['product_option_data'];
            $po2 = $_POST['product_option_id'];
            $c1 = count($po1);
            $c2 = count($po2);
            if ($c1 == $c2) {
                for ($i = 0; $i < $c1; $i++) {
                    if (isset($po2[$i]) && !empty($po2[$i]) && isset($po1[$i])) {
                        #$tmp_po[$po2[$i]] = !empty($po1[$i]) ? $po1[$i] : '';
                        $oid = !empty($po1[$i]) ? $po1[$i] : '';
                        $tmp_po[$po2[$i]] = [
                            'id' => $oid,
                            'oid' => $po2[$i],
                            'fsearch' => 'opt' . $po2[$i] . '_' . $oid,
                        ];
                    }
                }
            }
        }
        $data['joption'] = serialize($tmp_po);


        $_tmp = @$_POST['color'];
        if (!empty($_tmp) && count($_tmp) > 0) {
            foreach ($_tmp as $_item) {
                $_color[] = $_item;
                $array = $dbf->getArray(prefixTable . 'color', 'status = 1 and code = "' . $_item . '"', '', 'stdObject');
                if (!empty($array) && count($array) == 1) {
                    $_j_color[$array[0]->id] = [
                        'id' => $array[0]->id,
                        'code' => $array[0]->code,
                        'price' => (isset($_POST['price_color-' . $_item]) && $_POST['price_color-' . $_item] > 0 ? $_POST['price_color-' . $_item] : $data['price']),
                    ];
                }

            }
            $data['color'] = implode(';', $_color);
        }
        $data['info'] = serialize(['color' => $_j_color]);

        //2018.07.27
        $_tmp = @$_POST['product_size'];
        if (!empty($_tmp) && count($_tmp) > 0) {
            foreach ($_tmp as $_item) {
                $_prices[] = $_item;
                $array = $dbf->getArray(prefixTable . 'product_size', 'status = 1 and code = "' . $_item . '"', '', 'stdObject');
                if (!empty($array) && count($array) == 1) {
                    $_j_price[$array[0]->id] = [
                        'id' => $array[0]->id,
                        'code' => $array[0]->code,
                        'price' => (isset($_POST['price_size-' . $_item]) && $_POST['price_size-' . $_item] > 0 ? $_POST['price_size-' . $_item] : $data['price']),
                    ];
                }

            }
            $data['product_size'] = implode(';', $_prices);
        }
        $data['infoPdS'] = serialize(['product_size' => $_j_price]);

        $value['vi-VN']['name'] = $data['name'];
        $value['vi-VN']['rewrite'] = strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
        $value['vi-VN']['introtext'] = !empty($_POST['introtext_vi']) ? $dbf->compressHtml(trim($_POST['introtext_vi'])) : '';
        $value['vi-VN']['description'] = !empty($_POST['description_vi']) ? $dbf->compressHtml(trim($_POST['description_vi'])) : '';
        $value['vi-VN']['guide'] = !empty($_POST['guide_vi']) ? $dbf->compressHtml(trim($_POST['guide_vi'])) : '';
        $value['vi-VN']['specification'] = !empty($_POST['specification_vi']) ? $dbf->compressHtml(trim($_POST['specification_vi'])) : '';
        $value['vi-VN']['promodesc'] = !empty($_POST['promodesc_vi']) ? $dbf->compressHtml(trim($_POST['promodesc_vi'])) : '';
        $value['vi-VN']['metatitle'] = !empty($_POST['metatitle_vi']) ? ($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
        $value['vi-VN']['metakey'] = !empty($_POST['metakey_vi']) ? ($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
        $value['vi-VN']['metadesc'] = !empty($_POST['metadesc_vi']) ? ($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];


        $affect = $dbf->insertTable(prefixTable . 'product', $data);
        if ($affect > 0) {
            $_id = $affect;

            $langs = array_keys($value);
            foreach ($langs as $lang) {
                $value[$lang]['id'] = $_id;
                $value[$lang]['lang'] = $lang;
                $dbf->insertTable(prefixTable . 'product_desc', $value[$lang]);

                #$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $_product[$lang] . '/' . $_id . '-' . $value[$lang]['rewrite']) . EXT;
                $_url_alias = strtolower(substr($lang, 0, -3) . '/' . $_product[$lang] . '/' . $value[$lang]['rewrite'] . '-' . $_id) . EXT;
                $dbf->insertTable(prefixTable . 'url_alias', [
                    'url_alias' => $_url_alias,
                    'route' => 'product',
                    'type' => 'detail',
                    'related_id' => $affect,
                    'lang' => $lang,
                ]);
            }

            $msg = 'Đã thêm dòng (' . $affect . ') trong cơ sở dữ liệu.';
        }
    }
    else {
        $msg = 'Vui lòng nhập đầy đủ dữ liệu.';
        $err = 1;
    }
}

if ($subUpdate) {
    $_j_color = '';
    $_j_price = '';
    $err = 0;

    /*echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    exit;*/

    if (isset($_GET['edit']) && !empty($_GET['edit']) && isset($_POST['cid']) && $_POST['cid'] > 0 && isset($_POST['name']) && !empty($_POST['name'])) {
        $_id = (int) $_GET['edit'];
        $data['code'] = ($utls->checkValues($_POST['code']));
        $data['name'] = ($utls->checkValues($_POST['name']));
        #$data['picture']     	= $utls->checkValues($_POST['pictureText']);
        /*for($i = 2; $i <= 3; $i++)
        {
              if($_POST['pictureText' . $i] <> $picture)
                $data['picture'] .= ';' . $utls->checkValues($_POST['pictureText' . $i]);
        }*/
        $data['cid'] = (int) $_POST['cid'];
        $data['brand'] = (int) $_POST['brand'];
        $data['supplier'] = (int) $_POST['supplier'];
        $data['list_price'] = $_POST['list_price'] > 0 ? (int) $_POST['list_price'] : 0;
        $data['sale_off'] = $_POST['sale_off'] > 0 ? (int) $_POST['sale_off'] : 0;
        $data['price'] = $_POST['price'] > 0 ? (int) $_POST['price'] : $data['list_price'];
        $data['origin'] = ($utls->checkValues($_POST['origin']));
        $data['status'] = isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
        $data['qty'] = (int) $_POST['qty'];
        $data['outofstock'] = isset($_POST['outofstock']) && $_POST['outofstock'] == 1 ? 1 : 0;
        #$data['top']         	= isset($_POST['top']) && $_POST['top'] == 1 ? 1 : 0;
        $data['hot'] = isset($_POST['hot']) && $_POST['hot'] == 1 ? 1 : 0;
        #$data['favorite']       = isset($_POST['favorite']) && $_POST['favorite'] == 1 ? 1 : 0;
        $data['new'] = isset($_POST['new']) && $_POST['new'] == 1 ? 1 : 0;
        $data['promo'] = isset($_POST['promo']) && $_POST['promo'] == 1 ? 1 : 0;
        $data['flashsale'] = isset($_POST['flashsale']) && $_POST['flashsale'] == 1 ? 1 : 0;
        $data['ordering'] = isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 999;
        $data['ordering2'] = isset($_POST['ordering2']) && $_POST['ordering2'] >= 0 ? (int) $_POST['ordering2'] : 1;
        $data['modified'] = date('Y-m-d H:i:s');
        $data['modified_by'] = 1;
        $data['lname'] = ($utls->checkValues($_POST['lname']));
        $data['lcolor'] = ($utls->checkValues($_POST['lcolor']));

        // BEGIN: UPLOAD PICTURE
        if (isset($_FILES['picture']['name']) && is_array($_FILES['picture']['name']) && count($_FILES['picture']['name']) > 0) {
            /*echo '<pre>';
            print_r($_FILES['picture']['name']);
            echo '</pre>';
            exit;*/

            /*echo '<pre>';
            print_r($_POST['picture_name']);
            echo '</pre>';*/

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
                    $pic['type'] = 'image';
                    $pic['path'] = MOD_ROOT_URL;
                    $pic['dir'] = $dir;
                    $pic['file'] = $arr_pic[$i];
                    $pic['change_name'] = TRUE;
                    $pic['w'] = 1200;
                    $pic['thum'] = TRUE;
                    $pic['change_name_thum'] = FALSE;
                    $pic['w_thum'] = 120;
                    $upload = new Upload($pic['file']);
                    $result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
                    if (empty($result['err'])) {
                        $_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
                        #$data['picture'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
                        #$data['filetype'] = $result['type'];
                        #$data['filesize'] = $result['size'];
                        if (WATERMARK && in_array($result['type'], [
                                'jpg',
                                'jpeg',
                                'png',
                            ])) {
                            $utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'], str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'], str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png", 300, $result['type']);
                        }
                    }
                }
            }

            /*echo '<pre>';
            print_r($_picture);
            echo '</pre>';*/
            #exit;

        }

        if (isset($_POST['picture_name']) && !empty($_POST['picture_name']) && is_array($_POST['picture_name']) && count($_POST['picture_name']) > 0) {
            foreach ($_POST['picture_name'] as $_k => $_v) {
                if (!empty($_POST['picture_name'][$_k])) {
                    $_picture_name[] = $_v;
                }
            }
        }
        $data['picture'] = implode(';', $_picture_name);

        if (isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0) {
            foreach ($_picture as $k => $v) {
                if (isset($_picture[$k])) {
                    $_picture_name[$k] = $v;
                }
            }
            $data['picture'] = implode(';', $_picture_name);

            /*echo '<pre>';
            print_r($_picture_name);
            echo '</pre>';*/
        }

        #print_r($data['picture']);
        #exit;
        // END: UPLOAD PICTURE


        $tmp_po = '';
        if (isset($_POST['product_option_data']) && is_array($_POST['product_option_data']) && isset($_POST['product_option_id']) && is_array($_POST['product_option_id'])) {
            $po1 = $_POST['product_option_data'];
            $po2 = $_POST['product_option_id'];
            $c1 = count($po1);
            $c2 = count($po2);
            if ($c1 == $c2) {
                for ($i = 0; $i < $c1; $i++) {
                    if (isset($po2[$i]) && !empty($po2[$i]) && isset($po1[$i])) {
                        $oid = !empty($po1[$i]) ? $po1[$i] : '';
                        $tmp_po[$po2[$i]] = [
                            'id' => $oid,
                            'oid' => $po2[$i],
                            'fsearch' => 'opt' . $po2[$i] . '_' . $oid,
                        ];
                    }
                }
            }
        }
        $data['joption'] = serialize($tmp_po);


        $_tmp = @$_POST['color'];
        if (!empty($_tmp) && count($_tmp) > 0) {
            foreach ($_tmp as $_item) {
                $_color[] = $_item;
                $array = $dbf->getArray(prefixTable . 'color', 'status = 1 and code = "' . $_item . '"', '', 'stdObject');
                if (!empty($array) && count($array) == 1) {
                    $_j_color[$array[0]->id] = [
                        'id' => $array[0]->id,
                        'code' => $array[0]->code,
                        'price' => (isset($_POST['price_color-' . $_item]) && $_POST['price_color-' . $_item] > 0 ? $_POST['price_color-' . $_item] : $data['price']),
                    ];
                }

            }
            $data['color'] = implode(';', $_color);
        }
        #$data['info'] = serialize(array('size' => $_j_size, 'color' => $_j_color));
        //		$data['info'] = serialize(array('color' => $_j_color));

        //2018.07.27
        $_tmp = @$_POST['product_size'];
        $_prices = [];
        if (!empty($_tmp) && count($_tmp) > 0) {
            foreach ($_tmp as $_item) {
                $_prices[] = $_item;
                $array = $dbf->getArray(prefixTable . 'product_size', 'status = 1 and code = "' . $_item . '"', '', 'stdObject');
                if (!empty($array) && count($array) == 1) {
                    $_j_price[$array[0]->id] = [
                        'id' => $array[0]->id,
                        'code' => $array[0]->code,
                        'price' => (isset($_POST['price_size-' . $_item]) && $_POST['price_size-' . $_item] > 0 ? $_POST['price_size-' . $_item] : $data['price']),
                    ];
                }

            }
            $data['product_size'] = implode(';', $_prices);
        }

        $data['sale_ajax'] = isset($_POST['sale_ajax']) ? $_POST['sale_ajax'] : 0;

        $data['info'] = serialize([
            'size' => $_j_price,
            'color' => $_j_color,
        ]);//

        $value['vi-VN']['name'] = $data['name'];
        $value['vi-VN']['rewrite'] = strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
        $value['vi-VN']['introtext'] = !empty($_POST['introtext_vi']) ? $dbf->compressHtml(trim($_POST['introtext_vi'])) : '';
        $value['vi-VN']['description'] = !empty($_POST['description_vi']) ? $dbf->compressHtml(trim($_POST['description_vi'])) : '';
        $value['vi-VN']['guide'] = !empty($_POST['guide_vi']) ? $dbf->compressHtml(trim($_POST['guide_vi'])) : '';
        $value['vi-VN']['specification'] = !empty($_POST['specification_vi']) ? $dbf->compressHtml(trim($_POST['specification_vi'])) : '';
        $value['vi-VN']['promodesc'] = !empty($_POST['promodesc_vi']) ? $dbf->compressHtml(trim($_POST['promodesc_vi'])) : '';
        $value['vi-VN']['metatitle'] = !empty($_POST['metatitle_vi']) ? ($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
        $value['vi-VN']['metakey'] = !empty($_POST['metakey_vi']) ? ($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
        $value['vi-VN']['metadesc'] = !empty($_POST['metadesc_vi']) ? ($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];

        // upload document
        $targetfolder = "documents/";
        $targetfolder = $targetfolder . basename($_FILES['document']['name']);
        $documents = unserialize($row->doc);
        if (move_uploaded_file($_FILES['document']['tmp_name'], $targetfolder)) {
            $fileName = $_FILES['document']['name'];
        }
        if($documents) {
            $newFile = [
                'label' => $_POST['document-label'],
                'file' => $fileName
            ];
            array_push($documents, $newFile);
        } else {
            $documents = [
                [
                    'label' => $_POST['document-label'],
                    'file' => $fileName
                ]
            ];
        }
        $data['doc'] = serialize($documents);

        $affect = $dbf->updateTable(prefixTable . 'product', $data, 'id = ' . $_id);
        if ($affect > 0) {
            $langs = array_keys($value);
            foreach ($langs as $lang) {
                $dbf->updateTable(prefixTable . 'product_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);

                #$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $_product[$lang] . '/' . $_id . '-' . $value[$lang]['rewrite']) . EXT;
                $_url_alias = strtolower(substr($lang, 0, -3) . '/' . $_product[$lang] . '/' . $value[$lang]['rewrite'] . '-' . $_id) . EXT;
                $dbf->updateTable(prefixTable . 'url_alias', ['url_alias' => $_url_alias], 'route = "product" and type = "detail" and lang = "' . $lang . '" and related_id = ' . $_id);
            }

            $msg = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu.';
        }
    }
    else {
        $msg = 'Vui lòng nhập đầy đủ dữ liệu.';
        $err = 1;
    }
}

if ($isEdit) {
    if ($row) {
        $id = $row->id;
        $code = stripslashes($row->code);
        $mangpicture = explode(';', $row->picture);
        $list_price = $row->list_price;
        $sale_off = $row->sale_off;
        $price = $row->price;
        $cid = $row->cid;
        $brand = $row->brand;
        $supplier = $row->supplier;
        $info = unserialize($row->info);

        $status = $row->status;
        $qty = $row->qty;
        $outofstock = $row->outofstock;
        $hot = $row->hot;
        $new = $row->new;
        $promo = $row->promo;
        $flashsale = $row->flashsale;
        $saleAjax = $row->sale_ajax;
        $ordering = $row->ordering;
        $ordering2 = $row->ordering2;

        $warranty = $row->warranty;
        $promo_txt = $row->promo_txt;
        $origin = $row->origin;
        $related = unserialize($row->jrelated);
        $option = unserialize($row->joption);
        $lname = $row->lname;
        $lcolor = $row->lcolor;
        $alsobuy = unserialize($row->jalsobuy);

        $vi = $dbf->getArray(prefixTable . 'product_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
    }
} ?>
    <script type="text/javascript"
            src="../plugins/editors2/ckeditor/ckeditor.js"></script>
    <script type="text/javascript"
            src="../plugins/editors2/ckfinder/ckfinder.js"></script>
    <script type="text/javascript"
            src="../themes/default/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script language="javascript">
        jQuery(document).ready(function () {
            jQuery('#frm').validate({
                rules: {
                    cid: {
                        required: true
                    },
                    name: {
                        required: true
                    }
                },
                messages: {
                    cid: {
                        required: "Chọn loại sản phẩm."
                    },
                    name: {
                        required: "Nhập tên sản phẩm."
                    }
                }
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="style/jquery-ui.css"/>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript">$(function () {
            $(".tabs").tabs();
        });</script>
    <style type="text/css">
        .tab {
            border-top: 1px solid #dbdbdb !important;
            background: #FFF !important;
            overflow: hidden !important;
        }

        ul#color, ul#size {
            list-style: none;
        }

        ul#color li {
            float: left;
            font-size: 11px;
            width: 200px;
            padding: 5px;
            margin: 5px;
            border: 1px solid #DFDFDF;
            text-align: left;
        }

        .mbox ul, .abox ul, .mbox2 ul, .abox2 ul {
            padding: 0px;
        }

        .mbox ul li, .abox ul li, .mbox2 ul li, .abox2 ul li {
            list-style: none;
            list-style-type: none;
        }

        .mbox ul li a, .abox ul li a, .mbox2 ul li a, .abox2 ul li a {
            color: #000;
            font-size: 12px;
        }

        .abox ul li, .abox2 ul li {
            padding: 3px;
        }

        .mbox ul li a:hover, .abox ul li a:hover, .mbox2 ul li a:hover, .abox2 ul li a:hover {
            color: #DA251C;
            font-size: 12px;
        }

        .mbox ul li a:active, .mbox2 ul li a:active {
            color: #DA251C;
        }

        div.selected, div.selected2 {
            width: 600px;
            height: 150px;
            overflow: scroll;
            border: 1px solid #CCC;
            border: 1px solid #CCC;
            padding: 0px 20px;
        }

        .selected p, .selected2 p {
            clear: both;
            width: 100%;
            margin: 5px 0 0 0;
            cursor: pointer;
        }

        .selected p:hover, .selected2 p:hover {
            color: #DA251C;
        }
    </style>
    <script language="javascript">
        jQuery(document).ready(function () {
            jQuery('div.mbox a').live('click', function () {
                var id = jQuery(this).attr('id');
                var rid = jQuery('input#lid').val();
                jQuery.ajax({
                    url: "process_category.php",
                    type: "post",
                    data: {
                        'action': 'getList',
                        'id': id,
                        'rid': rid,
                        'rand': Math.random()
                    },
                    dataType: "json",
                    success: function (j) {
                        if (j.code == 'success') {
                            var _html = '';
                            var r = j.data;
                            _html += '<ul>';
                            jQuery.each(r, function (i) {
                                _html += '<li><a id="' + r[i].id + '" href="javascript:;">' + r[i].name + '</a></li>';
                            });
                            _html += '</ul>';
                            jQuery('div.abox').html(_html);
                        }
                        else {
                            alert(j.msg);
                        }
                    }
                });
                return false;
            });

            jQuery('div.abox a').live('dblclick', function () {
                var article = jQuery('input#lid').val();
                var id = jQuery(this).attr('id');
                var name = jQuery(this).text();
                if (jQuery('p#selected_' + id).length == 1) {
                    alert('Sản phẩm đã tồn tại trong danh sách liên quan.');
                    return false;
                }
                jQuery.ajax({
                    url: 'process_category.php',
                    type: 'post',
                    data: {
                        'action': 'add-item',
                        'article': article,
                        'id': id,
                        'rand': Math.random()
                    },
                    dataType: "json",
                    success: function (j) {
                        if (j.code == 'success') {
                            jQuery('div.selected').append('<p id="selected_' + id + '">' + name + '</p>');
                        }
                        else {
                            alert(j.msg);
                        }
                    }
                });
                return false;
            });

            jQuery('div.selected p').live('dblclick', function () {
                var article = jQuery('input#lid').val();
                var selected = jQuery(this).attr('id');
                selected = selected.replace('selected_', '');
                jQuery.ajax({
                    url: 'process_category.php',
                    type: 'post',
                    data: {
                        'action': 'remove-selected',
                        'article': article,
                        'selected': selected,
                        'rand': Math.random()
                    },
                    dataType: 'json',
                    success: function (j) {
                        if (j.code == 'success') {
                            jQuery('p#selected_' + selected).remove();
                            return false;
                        }
                    }
                });
                return false;
            });

        });
    </script>
    <script language="javascript">
        jQuery(document).ready(function () {
            jQuery('div.mbox2 a').live('click', function () {
                var id = jQuery(this).attr('id');
                var rid = jQuery('input#lid').val();
                jQuery.ajax({
                    url: "process_category.php",
                    type: "post",
                    data: {
                        'action': 'getList',
                        'id': id,
                        'rid': rid,
                        'rand': Math.random()
                    },
                    dataType: "json",
                    success: function (j) {
                        if (j.code == 'success') {
                            var _html = '';
                            var r = j.data;
                            _html += '<ul>';
                            jQuery.each(r, function (i) {
                                _html += '<li><a id="' + r[i].id + '" href="javascript:;">' + r[i].name + '</a></li>';
                            });
                            _html += '</ul>';
                            jQuery('div.abox2').html(_html);
                        }
                        else {
                            alert(j.msg);
                        }
                    }
                });
                return false;
            });

            jQuery('div.abox2 a').live('dblclick', function () {
                var article = jQuery('input#lid').val();
                var id = jQuery(this).attr('id');
                var name = jQuery(this).text();
                if (jQuery('p#selected2_' + id).length == 1) {
                    alert('Sản phẩm đã tồn tại trong danh sách mua kèm.');
                    return false;
                }
                jQuery.ajax({
                    url: 'process_category.php',
                    type: 'post',
                    data: {
                        'action': 'add-item-alsobuy',
                        'article': article,
                        'id': id,
                        'rand': Math.random()
                    },
                    dataType: "json",
                    success: function (j) {
                        if (j.code == 'success') {
                            jQuery('div.selected2').append('<p id="selected2_' + id + '">' + name + '</p>');
                        }
                        else {
                            alert(j.msg);
                        }
                    }
                });
                return false;
            });

            jQuery('div.selected2 p').live('dblclick', function () {
                var article = jQuery('input#lid').val();
                var selected = jQuery(this).attr('id');
                selected = selected.replace('selected2_', '');
                jQuery.ajax({
                    url: 'process_category.php',
                    type: 'post',
                    data: {
                        'action': 'remove-selected-alsobuy',
                        'article': article,
                        'selected': selected,
                        'rand': Math.random()
                    },
                    dataType: 'json',
                    success: function (j) {
                        if (j.code == 'success') {
                            jQuery('p#selected2_' + selected).remove();
                            return false;
                        }
                    }
                });
                return false;
            });

        });
    </script>
<?php $dbf->FormUpload('frm', [
    'action' => '',
    'method' => 'post',
    'class' => 'validate',
]) ?>
<?php if ($isEdit || $isInsert) { ?>
    <!-- form -->
    <div id="panelForm" class="panelForm">
        <table id="mainTable" cellpadding="0" cellspacing="0">
            <?php echo $dbf->returnTitleMenu($titleMenu) ?>
            <tr>
                <td class="txtdo" colspan="2"
                    align="center"><?php echo $msg ?></td>
            </tr>
            <tr>
                <td class="boxGrey2" colspan="2">
                    <div id="ui-tabs">
                        <div class="tabs">
                            <ul id="tab-container-1-nav">
                                <li><a href="#thong-tin-chung"><span>Thông tin chung <span
                                                    style="color:#DA251C;">(*)</span></span></a>
                                </li>
                                <li><a href="#thuoc-tinh-san-pham"><span>Thuộc tính sản phẩm <span
                                                    style="color:#DA251C;">(*)</span></span></a>
                                </li>
                                <li><a href="#hinh-anh-kich-co-mau-sac"><span>Hình ảnh / Màu sắc
                                            <!-- / Kích cỡ--> <span
                                                    style="color:#DA251C;">(*)</span></span></a>
                                </li>
                                <li><a href="#san-pham-lien-quan"><span>Sản phẩm liên quan</span></a>
                                </li>
                                <li><a href="#san-pham-mua-kem"><span>Sản phẩm mua kèm</span></a>
                                </li>
                                <li><a href="#thong-tin-khac"><span>Thông tin khác</span></a>
                                </li>
                            </ul>
                            <div id="thong-tin-chung">
                                <div id="ui-tabs">
                                    <div class="tabs">
                                        <ul id="tab-container-1-nav">
                                            <!--<li><a href="#tieng-viet"><span>Tiếng Việt (*)</span></a></li>-->
                                            <!--<li><a href="#tieng-anh"><span>Tiếng Anh</span></a></li>-->
                                        </ul>
                                        <div id="tieng-viet">
                                            <table id="mainTable"
                                                   cellpadding="0"
                                                   cellspacing="0">
                                                <tr>
                                                    <td class="boxGrey"><?php echo $col['name'] ?>
                                                        <span style="color:#DA251C">(*)</span>
                                                    </td>
                                                    <td class="boxGrey2"><input
                                                                name="name"
                                                                id="name"
                                                                type="text"
                                                                class="nd3"
                                                                value="<?php echo isset($vi[0]->name) && !empty($vi[0]->name) ? stripslashes($vi[0]->name) : '' ?>"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"
                                                        class="boxGrey2">
                                                        <div id="ui-tabs">
                                                            <div class="tabs">
                                                                <ul id="tab-container-1-nav">
                                                                    <li>
                                                                        <a href="#mo-ta-ngan-vi-VN"><span>Mô tả ngắn</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#mo-ta-chi-tiet-vi-VN"><span>Mô tả chi tiết</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#khuyen-mai-vi-VN"><span>Khuyến mãi</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#thong-so-ky-thuat-vi-VN"><span>Thông số kỹ thuật</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#huong-dan-su-dung-vi-VN"><span>Hướng dẫn sử dụng</span></a>
                                                                    </li>
                                                                </ul>
                                                                <div id="mo-ta-ngan-vi-VN"><?php #echo $ckeditor->doDisplay('introtext_vi', (isset($vi[0]->introtext) && !empty($vi[0]->introtext) ? stripslashes($vi[0]->introtext) : ''), '100%', '100', 'Normal') ?>
                                                                    <textarea
                                                                            name="introtext_vi"
                                                                            id="introtext_vi"
                                                                            cols="75"
                                                                            rows="20"><?php echo (isset($vi[0]->introtext) && !empty($vi[0]->introtext)) ? stripslashes($vi[0]->introtext) : '' ?></textarea>
                                                                    <script type="text/javascript">
                                                                        var editor = CKEDITOR.replace('introtext_vi', {
                                                                            language: 'vi',
                                                                            toolbar: 'Normal',
                                                                            height: '100px',
                                                                            width: '100%',
                                                                            filebrowserBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
                                                                            filebrowserImageBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
                                                                            filebrowserFlashBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                                                                            //filebrowserUploadUrl : '<?php //#echo HOST ?>///plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                                            filebrowserImageUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                                                            filebrowserFlashUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                                                        });
                                                                        CKFinder.setupCKEditor(editor, '../');
                                                                    </script>
                                                                </div>
                                                                <div id="mo-ta-chi-tiet-vi-VN"><?php #echo $ckeditor->doDisplay('description_vi', (isset($vi[0]->description) && !empty($vi[0]->description) ? stripslashes($vi[0]->description) : ''), '100%', '300', 'Default') ?>
                                                                    <textarea
                                                                            name="description_vi"
                                                                            id="description_vi"
                                                                            cols="75"
                                                                            rows="20"><?php echo (isset($vi[0]->description) && !empty($vi[0]->description)) ? stripslashes($vi[0]->description) : '' ?></textarea>
                                                                    <script type="text/javascript">
                                                                        var editor = CKEDITOR.replace('description_vi', {
                                                                            language: 'vi',
                                                                            toolbar: 'Default',
                                                                            height: '300px',
                                                                            width: '100%',
                                                                            filebrowserBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
                                                                            filebrowserImageBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
                                                                            filebrowserFlashBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                                                                            //filebrowserUploadUrl : '<?php //#echo HOST ?>///plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                                            filebrowserImageUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                                                            filebrowserFlashUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                                                        });
                                                                        CKFinder.setupCKEditor(editor, '../');
                                                                    </script>
                                                                </div>
                                                                <div id="khuyen-mai-vi-VN">
                                                                    <textarea
                                                                            name="promodesc_vi"
                                                                            id="promodesc_vi"
                                                                            cols="75"
                                                                            rows="20"><?php echo (isset($vi[0]->promodesc) && !empty($vi[0]->promodesc)) ? stripslashes($vi[0]->promodesc) : '' ?></textarea>
                                                                    <script type="text/javascript">
                                                                        var editor = CKEDITOR.replace('promodesc_vi', {
                                                                            language: 'vi',
                                                                            toolbar: 'Default',
                                                                            height: '300px',
                                                                            width: '100%',
                                                                            filebrowserBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
                                                                            filebrowserImageBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
                                                                            filebrowserFlashBrowseUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                                                                            //filebrowserUploadUrl : '<?php //#echo HOST ?>///plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                                            filebrowserImageUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                                                            filebrowserFlashUploadUrl: '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                                                        });
                                                                        CKFinder.setupCKEditor(editor, '../');
                                                                    </script>
                                                                </div>
                                                                <div id="thong-so-ky-thuat-vi-VN"><?php #echo $ckeditor->doDisplay('specification_vi', (isset($vi[0]->specification) && !empty($vi[0]->specification) ? stripslashes($vi[0]->specification) : ''), '100%', '300', 'Default') ?>
                                                                    <textarea
                                                                            name="specification_vi"
                                                                            id="specification_vi"
                                                                            cols="75"
                                                                            rows="20"><?= stripslashes($vi[0]->specification)  ?></textarea>
                                                                    <script type="text/javascript">
                                                                        var editor = CKEDITOR.replace('specification_vi', {
                                                                            language: 'vi',
                                                                            toolbar: 'Default',
                                                                            height: '300px',
                                                                            width: '100%',
                                                                            filebrowserBrowseUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
                                                                            filebrowserImageBrowseUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
                                                                            filebrowserFlashBrowseUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                                                                            //filebrowserUploadUrl : '<?php //##echo HOST ?>///plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                                            filebrowserImageUploadUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                                                            filebrowserFlashUploadUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                                                        });
                                                                        CKFinder.setupCKEditor(editor, '../');
                                                                    </script>
                                                                </div>
                                                                <div id="huong-dan-su-dung-vi-VN">
                                                                    <textarea
                                                                            name="guide_vi"
                                                                            id="guide_vi"
                                                                            cols="75"
                                                                            rows="20"><?php echo (isset($vi[0]->guide) && !empty($vi[0]->guide)) ? stripslashes($vi[0]->guide) : '' ?></textarea>
                                                                    <script type="text/javascript">
                                                                        var editor = CKEDITOR.replace('guide_vi', {
                                                                            language: 'vi',
                                                                            toolbar: 'Default',
                                                                            height: '300px',
                                                                            width: '100%',
                                                                            filebrowserBrowseUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
                                                                            filebrowserImageBrowseUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
                                                                            filebrowserFlashBrowseUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
                                                                            //filebrowserUploadUrl : '<?php //##echo HOST ?>///plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                                            filebrowserImageUploadUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                                                            filebrowserFlashUploadUrl: '<?php #echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                                                                        });
                                                                        CKFinder.setupCKEditor(editor, '../');
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="boxGrey">Thẻ tiêu
                                                        đề [Meta Title]
                                                    </td>
                                                    <td class="boxGrey2"><input
                                                                name="metatitle_vi"
                                                                id="metatitle_vi"
                                                                type="text"
                                                                class="nd3"
                                                                value="<?php echo isset($vi[0]->metatitle) && !empty($vi[0]->metatitle) ? stripslashes($vi[0]->metatitle) : '' ?>"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="boxGrey">Thẻ từ
                                                        khóa [Meta Keywords]
                                                    </td>
                                                    <td class="boxGrey2">
                                                        <textarea
                                                                name="metakey_vi"
                                                                id="metakey_vi"
                                                                type="text"
                                                                class="nd3"/><?php echo isset($vi[0]->metakey) && !empty($vi[0]->metakey) ? stripslashes($vi[0]->metakey) : '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="boxGrey">Thẻ mô
                                                        tả [Meta Description]
                                                    </td>
                                                    <td class="boxGrey2">
                                                        <textarea
                                                                name="metadesc_vi"
                                                                id="metadesc_vi"
                                                                type="text"
                                                                class="nd3"/><?php echo isset($vi[0]->metadesc) && !empty($vi[0]->metadesc) ? stripslashes($vi[0]->metadesc) : '' ?></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="thuoc-tinh-san-pham">
                                <table id="mainTable" cellpadding="0"
                                       cellspacing="0">
                                    <tr>
                                        <td class="boxGrey">Mã sản phẩm</td>
                                        <td class="boxGrey2"><input name="code"
                                                                    id="code"
                                                                    type="text"
                                                                    class="nd1"
                                                                    value="<?php echo (isset($code) && !empty($code)) ? $code : '' ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey"><?php echo $col["cid"] ?>
                                            <span style="color:#DA251C">(*)</span>
                                        </td>
                                        <td class="boxGrey2"><?php echo $dbf->generateRecursiveSelect("cid", "name", "id", (isset($cid) && $cid !== NULL) ? $cid : $iscatURL, prefixTable . "category", 0, ['firstText' => 'Chọn loại sản phẩm']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Thương hiệu</td>
                                        <td class="boxGrey2"><?php echo $dbf->SelectWithTable(prefixTable . 'brand', 'status = 1', 'ordering, name', 'brand', 'name', 'id', (isset($brand) && !empty($brand) ? $brand : ''), [
                                                'firstText' => 'Chọn thương hiệu',
                                                'class' => 'cbo',
                                            ]) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Nhà cung cấp</td>
                                        <td class="boxGrey2"><?php echo $dbf->SelectWithTable(prefixTable . 'supplier', 'status = 1', 'ordering, name', 'supplier', 'name', 'id', (isset($supplier) && !empty($supplier) ? $supplier : ''), [
                                                'firstText' => 'Chọn nhà cung cấp',
                                                'class' => 'cbo',
                                            ]) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Đơn giá</td>
                                        <td class="boxGrey2"><?php echo $dbf->input("list_price", [
                                                "type" => "text",
                                                "class" => "nd1",
                                                "value" => (isset($list_price)) ? $list_price : 0,
                                                "onfocus" => "fo(this);",
                                                "onblur" => "lo(this);",
                                            ]) ?> (VNĐ)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Giảm giá</td>
                                        <td class="boxGrey2"><?php echo $dbf->input("sale_off", [
                                                "type" => "text",
                                                "class" => "nd1",
                                                "value" => (isset($sale_off)) ? $sale_off : 0,
                                                "onfocus" => "fo(this);",
                                                "onblur" => "lo(this);",
                                            ]) ?> (%)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Giá bán</td>
                                        <td class="boxGrey2"><?php echo $dbf->input("price", [
                                                "type" => "text",
                                                "class" => "nd1",
                                                "value" => (isset($price)) ? $price : 0,
                                                "onfocus" => "fo(this);",
                                                "onblur" => "lo(this);",
                                            ]) ?> (VNĐ)
                                        </td>
                                    </tr>
                                    <?php echo $dbf->showProductOption($option) ?>
                                    <tr>
                                        <td class="boxGrey">Nhãn hàng</td>
                                        <td class="boxGrey2">
                                            <input name="lname" id="lname"
                                                   type="text" class="nd1"
                                                   value="<?php echo (isset($lname) && !empty($lname)) ? $lname : '' ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Màu nhãn hàng</td>
                                        <td class="boxGrey2">
                                            <?php $arrayLabelColor = [
                                                'blue' => 'Xanh dương',
                                                'green' => 'Xanh lá cây',
                                                'orange' => 'Cam',
                                                'red' => 'Đỏ',
                                            ];
                                            echo $dbf->SelectWithNormalArray($arrayLabelColor, (isset($lcolor) && !empty($lcolor) ? $lcolor : ''), 'lcolor', [
                                                'firstText' => 'Chọn màu nhãn hàng',
                                                'class' => 'cbo',
                                            ]) ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="hinh-anh-kich-co-mau-sac">
                                <div id="ui-tabs">
                                    <div class="tabs">
                                        <ul id="tab-container-1-nav">
                                            <li><a href="#hinh-anh"><span>Hình ảnh <span
                                                                style="color:#DA251C;">(*)</span></span></a>
                                            </li>
                                            <li>
                                                <a href="#mau-sac"><span>Màu sắc</span></a>
                                            </li>
                                            <li>
                                                <a href="#kich-co"><span>Kích cỡ</span></a>
                                            </li>
                                            <li>
                                                <a href="#wholesale"><span>Giá sỉ</span></a>
                                            </li>
                                            <li>
                                                <a href="#document"><span>Document</span></a>
                                            </li>
                                        </ul>
                                        <div id="hinh-anh" class="tab"
                                             style="padding: 10px;">
                                            <table border="0" cellpadding="0"
                                                   cellspacing="0">
                                                <tr>
                                                    <td><?php #echo $dbf->generateChoicePicture('picture',  'pictureText',  isset($mangpicture[0]) ? $mangpicture[0] : $picture) ?>
                                                        <?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? '<img src="resize.php?from=..' . $mangpicture[0] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name"
                                                               value="<?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? $mangpicture[0] : '' ?>"/>
                                                    </td>
                                                    <td><?php #echo $dbf->generateChoicePicture('picture2', 'pictureText2', isset($mangpicture[1]) ? $mangpicture[1] : $picture) ?>
                                                        <?php echo isset($mangpicture[1]) && !empty($mangpicture[1]) ? '<img src="resize.php?from=..' . $mangpicture[1] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture2"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name2"
                                                               value="<?php echo isset($mangpicture[1]) && !empty($mangpicture[1]) ? $mangpicture[1] : '' ?>"/>
                                                    </td>
                                                    <td><?php #echo $dbf->generateChoicePicture('picture3', 'pictureText3', isset($mangpicture[2]) ? $mangpicture[2] : $picture) ?>
                                                        <?php echo isset($mangpicture[2]) && !empty($mangpicture[2]) ? '<img src="resize.php?from=..' . $mangpicture[2] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture3"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name3"
                                                               value="<?php echo isset($mangpicture[2]) && !empty($mangpicture[2]) ? $mangpicture[2] : '' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($mangpicture[3]) && !empty($mangpicture[3]) ? '<img src="resize.php?from=..' . $mangpicture[3] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture4"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name4"
                                                               value="<?php echo isset($mangpicture[3]) && !empty($mangpicture[3]) ? $mangpicture[3] : '' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($mangpicture[4]) && !empty($mangpicture[4]) ? '<img src="resize.php?from=..' . $mangpicture[4] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture5"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name5"
                                                               value="<?php echo isset($mangpicture[4]) && !empty($mangpicture[4]) ? $mangpicture[4] : '' ?>"/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php echo isset($mangpicture[5]) && !empty($mangpicture[5]) ? '<img src="resize.php?from=..' . $mangpicture[5] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture6"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name6"
                                                               value="<?php echo isset($mangpicture[5]) && !empty($mangpicture[5]) ? $mangpicture[5] : '' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($mangpicture[6]) && !empty($mangpicture[6]) ? '<img src="resize.php?from=..' . $mangpicture[6] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture7"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name7"
                                                               value="<?php echo isset($mangpicture[6]) && !empty($mangpicture[6]) ? $mangpicture[6] : '' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($mangpicture[7]) && !empty($mangpicture[7]) ? '<img src="resize.php?from=..' . $mangpicture[7] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture8"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name8"
                                                               value="<?php echo isset($mangpicture[7]) && !empty($mangpicture[7]) ? $mangpicture[7] : '' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($mangpicture[8]) && !empty($mangpicture[8]) ? '<img src="resize.php?from=..' . $mangpicture[8] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture9"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name9"
                                                               value="<?php echo isset($mangpicture[8]) && !empty($mangpicture[8]) ? $mangpicture[8] : '' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($mangpicture[9]) && !empty($mangpicture[9]) ? '<img src="resize.php?from=..' . $mangpicture[9] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                                                        <input type="file"
                                                               name="picture[]"
                                                               id="picture10"
                                                               style="width:250px;"/>
                                                        <input type="hidden"
                                                               name="picture_name[]"
                                                               id="picture_name10"
                                                               value="<?php echo isset($mangpicture[9]) && !empty($mangpicture[9]) ? $mangpicture[9] : '' ?>"/>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="mau-sac" class="tab">
                                            <?php $rst1 = $dbf->getDynamic(prefixTable . 'color', 'status = 1', 'ordering, name');
                                            if ($dbf->totalRows($rst1) > 0) {
                                                echo '<ul style="list-style: none;">';
                                                while ($row1 = $dbf->nextObject($rst1)) {

                                                    echo '<li style="float: left; clear:both; width: auto; padding: 5px; margin: 5px; border: 1px solid #DFDFDF; width:300px;">
					                            <input type="checkbox" name="color[]" id="color-' . $row1->code . '" value="' . $row1->code . '"' . (isset($info['color'][$row1->id]) && $info['color'][$row1->id]['id'] == $row1->id ? ' checked="checked"' : '') . ' />' . stripslashes($row1->name) . ' - Giá bán <input name="price_color-' . $row1->code . '" id="price_color-' . $row1->code . '" type="text" style="width:150px" value="' . (isset($info['color'][$row1->id]['price']) ? $info['color'][$row1->id]['price'] : 0) . '" />' . '</li>';
                                                }
                                                echo '</ul>';
                                            } ?>
                                        </div>
                                        <div id="kich-co" class="tab">
                                            <?php $rst1 = $dbf->getDynamic(prefixTable . 'product_size', 'status = 1', 'ordering, name');
                                            if ($dbf->totalRows($rst1) > 0) {
                                                echo '<ul style="list-style: none;">';
                                                while ($row1 = $dbf->nextObject($rst1)) {
                                                    echo '<li style="float: left; clear:both; width: auto; padding: 5px; margin: 5px; border: 1px solid #DFDFDF; width:300px;">
					                                <input type="checkbox" name="product_size[]" id="product_size-' . $row1->code . '" value="' . $row1->code . '"' . (isset($info['size'][$row1->id]) && $info['size'][$row1->id]['id'] == $row1->id ? ' checked="checked"' : '') . ' />' . stripslashes($row1->name) . ' - Giá bán <input name="price_size-' . $row1->code . '" id="price_size-' . $row1->code . '" type="text" style="width:150px" value="' . (isset($info['size'][$row1->id]['price']) ? $info['size'][$row1->id]['price'] : 0) . '" />' . '</li>';
                                                }
                                                echo '</ul>';
                                            } ?>
                                        </div>
                                        <div id="wholesale" class="tab"
                                             style="padding: 10px;">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <label for="#quantity-from">Số lượng:</label>
                                                        <?= str_repeat('&nbsp;',3)?>
                                                        <label for="#quantity-from">Từ</label>&nbsp;
                                                        <select name="quantity-from" id="quantity-from">
                                                            <?php
                                                            $numberFrom = range(0, 100);
                                                            foreach ($numberFrom as $from) {
                                                                echo "<option value='$from'>$from</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?= str_repeat('&nbsp;',3)?>
                                                        <label for="#quantity-to">Đến</label>&nbsp;
                                                        <label for="" class="wholesale-quantity error" style="display: none">Số lương "từ" phải lớn hơn "đến"</label>
                                                        <select name="quantity-to" id="quantity-to">
                                                            <?php
                                                            $numberTo = range(0, 100);
                                                            foreach ($numberFrom as $to) {
                                                                echo "<option value='$to'>$to</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><?= str_repeat('-',47)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <label for="#quantity-from">Giá sỉ</label>
                                                        <label for="" class="wholesale-price error" style="display: none">Bạn chưa nhập đúng giá</label>
                                                        <?= str_repeat('&nbsp;',9)?>
                                                        <input type="text" name="wholesale-price" placeholder="Giá sĩ" id="wholesale-price">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><?= str_repeat('-',47)?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input type="button" value="Thêm" id="add-wholesale" data-id="<?= $row->id ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div id="wholesale-data">
                                                            <table>
                                                                <?php
                                                                    $wholesale = unserialize($row->wholesale);
                                                                    if(count($wholesale) > 0) {
                                                                ?>
                                                                <tr>
                                                                    <th>Số lượng</th>
                                                                    <th>Giá</th>
                                                                    <th></th>
                                                                </tr>
                                                                <?php
                                                                        foreach ($wholesale as $whSale) {
                                                                            echo '<tr class="content-sale">';
                                                                            echo '<td>'.$whSale['quantity_from']. '->'.$whSale['quantity_to'].  '</td>';
                                                                            echo '<td>'.number_format($whSale['wholesale_price']).'VNĐ</td>';
                                                                            echo '<td><a href="javascript:void(0);" class="delete-whole-sale" data-sid="'.$whSale['id'].'" data-id="' .$row->id .'">Xóa</a></td>';
                                                                            echo '</tr>';
                                                                        }
                                                                    }
                                                                ?>
                                                            </table>

                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="document" class="tab"
                                             style="padding: 10px;">
                                            <input type="file"
                                                   name="document"
                                                   id="upload-document"
                                                   style="width:250px;"/>
                                            <label for="#document-label">Label</label>
                                            <input type="text"
                                                   name="document-label"
                                                   id="document-label"
                                                   style="width:250px;"/>
                                            <table cellspacing="1" cellpadding="1" border="1" style="margin-top: 20px">
                                                <tr>
                                                    <th style='padding: 5px'>Label</th>
                                                    <th style='padding: 5px'>File</th>
                                                    <th style='padding: 5px'>Xóa</th>
                                                </tr>
                                                <?php
                                                    $doc = unserialize($row->doc);
                                                    foreach ($doc as $pos => $item) {
                                                        if($item) {
                                                            echo "<tr>";
                                                            echo "<td style='padding: 5px'>{$item['label']}</td>";
                                                            echo "<td style='padding: 5px'>{$item['file']}</td>";
                                                            echo "<td style='text-align:center;padding: 5px'><a data-pid='{$row->id}' data-position='{$pos}' href='javascript:void(0);' class='delete-doc'>X</a></td>";
                                                            echo "</tr>";
                                                        }

                                                    }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="san-pham-lien-quan">
                                <table id="mainTable" cellpadding="0"
                                       cellspacing="0">
                                    <tr>
                                        <td class="boxGrey"
                                            style="width:250px;">Danh mục sản
                                            phẩm
                                        </td>
                                        <td class="boxGrey2">
                                            <div class="mbox"
                                                 style="width:600px; height:200px; overflow:scroll; border:1px solid #CCC; border: 1px solid #CCC; padding: 0px 20px;"><?php echo $dbf->showMenu() ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Danh sách sản
                                            phẩm/dịch vụ
                                        </td>
                                        <td class="boxGrey2">
                                            <div class="abox"
                                                 style="width:600px; height:150px; overflow:scroll; border:1px solid #CCC; border: 1px solid #CCC; padding: 0px 20px;"></div>
                                            <p style="color:#DA251C; font-style:italic;">
                                                * Click chuột đúp để thêm vào
                                                danh sách sản phẩm liên
                                                quan.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Danh sách đã chọn
                                        </td>
                                        <td class="boxGrey2">
                                            <div class="selected"><?php echo isset($related) && !empty($related) ? $dbf->showRelated($related) : '' ?></div>
                                            <p style="color:#DA251C; font-style:italic;">
                                                * Click chuột đúp để loại bỏ
                                                khỏi danh sách sản phẩm liên
                                                quan.</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="san-pham-mua-kem">
                                <table id="mainTable" cellpadding="0"
                                       cellspacing="0">
                                    <tr>
                                        <td class="boxGrey"
                                            style="width:250px;">Danh mục sản
                                            phẩm
                                        </td>
                                        <td class="boxGrey2">
                                            <div class="mbox2"
                                                 style="width:600px; height:200px; overflow:scroll; border:1px solid #CCC; border: 1px solid #CCC; padding: 0px 20px;"><?php echo $dbf->showMenu() ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Danh sách sản phẩm
                                        </td>
                                        <td class="boxGrey2">
                                            <div class="abox2"
                                                 style="width:600px; height:150px; overflow:scroll; border:1px solid #CCC; border: 1px solid #CCC; padding: 0px 20px;"></div>
                                            <p style="color:#DA251C; font-style:italic;">
                                                * Click chuột đúp để thêm vào
                                                danh sách sản phẩm mua kèm.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Danh sách đã chọn
                                        </td>
                                        <td class="boxGrey2">
                                            <div class="selected2"><?php echo isset($alsobuy) && !empty($alsobuy) ? $dbf->showAlsoBuy($alsobuy) : '' ?></div>
                                            <p style="color:#DA251C; font-style:italic;">
                                                * Click chuột đúp để loại bỏ
                                                khỏi danh sách sản phẩm mua
                                                kèm.</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="thong-tin-khac">
                                <table id="mainTable" cellpadding="0"
                                       cellspacing="0">
                                    <tr>
                                        <td class="boxGrey">Số lượng trong kho
                                        </td>
                                        <td class='boxGrey2'><?php echo $dbf->input('qty', [
                                                'type' => 'text',
                                                'class' => 'nd1',
                                                'value' => (isset($qty)) ? $qty : 0,
                                                'onfocus' => 'fo(this);',
                                                'onblur' => 'lo(this);',
                                            ]) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey"><?php echo $col['ordering'] ?></td>
                                        <td class='boxGrey2'><?php echo $dbf->input('ordering', [
                                                'type' => 'text',
                                                'class' => 'nd1',
                                                'value' => (isset($ordering)) ? $ordering : 999,
                                                'onfocus' => 'fo(this);',
                                                'onblur' => 'lo(this);',
                                            ]) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Thứ tự</td>
                                        <td class='boxGrey2'><?php echo $dbf->input('ordering2', [
                                                'type' => 'text',
                                                'class' => 'nd1',
                                                'value' => (isset($ordering2)) ? $ordering2 : 1,
                                                'onfocus' => 'fo(this);',
                                                'onblur' => 'lo(this);',
                                            ]) ?> [hiển thị tại danh mục trang
                                            chủ từ lớn => nhỏ]
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey">Tùy chọn</td>
                                        <td class="boxGrey2">
                                            <input type="checkbox"
                                                   name="outofstock"
                                                   id="outofstock"
                                                   value="1" <?php echo strtr((isset($outofstock)) ? $outofstock : '', $statusChecked) ?> />
                                            Hết hàng ? <br/>
                                            <input type="checkbox" name="new"
                                                   id="new"
                                                   value="1" <?php echo strtr((isset($new)) ? $new : '', $statusChecked) ?> />
                                            SP mới ?
                                            <!--<span id="new_date"><?php #$dbf->showNewProductDateDropdown($new_date) ?></span> <br /> -->
                                            <!--<input type="checkbox" name="comingsoon" id="comingsoon" value="1" <?php #echo strtr((isset($comingsoon)) ? $comingsoon : '', $statusChecked) ?> /> SP sắp về ?-->
                                            <!--<input type="checkbox" name="featured" id="featured" value="1" <?php #echo strtr((isset($featured)) ? $featured : '', $statusChecked) ?> /> SP nổi bật ?-->
                                            <!--<input type="checkbox" name="top" id="top" value="1" <?php #echo strtr((isset($top)) ? $top : '', $statusChecked) ?> /> SP nối bật ?<br />-->
                                            <input type="checkbox" name="hot"
                                                   id="hot"
                                                   value="1" <?php echo strtr((isset($hot)) ? $hot : '', $statusChecked) ?> />
                                            SP bán chạy ?<br/>
                                            <!--<input type="checkbox" name="favorite" id="favorite" value="1" <?php #echo strtr((isset($favorite)) ? $favorite : '', $statusChecked) ?> /> SP ưa chuộng ? -->
                                            <input type="checkbox" name="promo"
                                                   id="hot"
                                                   value="1" <?php echo strtr((isset($promo)) ? $promo : '', $statusChecked) ?> />
                                            SP khuyến mãi ?<br/>
                                            <input type="checkbox"
                                                   name="flashsale"
                                                   id="flashsale"
                                                   value="1" <?php echo strtr((isset($flashsale)) ? $flashsale : '', $statusChecked) ?> />
                                            Flash sale ?<br/>
                                            <input type="checkbox"
                                                   name="sale_ajax"
                                                   id="sale_ajax"
                                                   value="1" <?= ($saleAjax == 1) ? "checked" : '' ?>/>
                                            Sale ajax ?<br/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="boxGrey"><?php echo $col['status'] ?></td>
                                        <td width="379" class="boxGrey2"><input
                                                    type="checkbox"
                                                    name="status" id="status"
                                                    value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> />
                                            Kích hoạt?
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="boxGrey"></td>
                <td height="1" align="center" class="boxGrey2">
                    <div id="insert"><?php echo ($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '') ?></div>
                </td>
            </tr>
            <tr>
                <td class="boxGrey" colspan="2"><span
                            style="color:#DA251C">(*)</span> Bắt buộc nhập
                </td>
            </tr>
        </table>
        <?php if ($isEdit) : ?>
            <input name="lid" id="lid" type="hidden"
                   value="<?php echo isset($id) && !empty($id) ? $id : '' ?>"/>
            <!--<input name="lname" id="lname" type="hidden" value="<?php #echo isset($name) && !empty($name) ? $name : '' ?>" />
    <input name="lrewrite" id="lrewrite" type="hidden" value="<?php #echo isset($rewrite) && !empty($rewrite) ? $rewrite : '' ?>" />
    <input name="luri" id="luri" type="hidden" value="<?php #echo isset($uri) && !empty($uri) ? $uri : '' ?>" />-->
        <?php endif ?>
    </div>
    <?php if ($isInsert && !empty($msg))
        echo '<script type="text/javascript">huy(); </script>' ?>
    <!-- end Form-->
<?php } ?>
<?php if (!$isEdit && !$isInsert) {
    $_c_key = '';
    $_tu_khoa = '';
    $q['tu-khoa'] = (isset($_POST['tu-khoa']) && !empty($_POST['tu-khoa'])) ? trim(mysql_escape_string($_POST['tu-khoa'])) : (isset($_GET['tu-khoa']) && !empty($_GET['tu-khoa']) ? str_replace('+', ' ', trim(mysql_escape_string($_GET['tu-khoa']))) : '');
    if (!empty($q['tu-khoa'])) {
        #$_keyw[] = "t1.id like '%" . $q['tu-khoa'] . "%'";
        $_keyw[] = "t1.name like '%" . $q['tu-khoa'] . "%'";
        $_c_key = " AND (" . implode(" OR ", $_keyw) . ")";
        $_tu_khoa = "&tu-khoa=" . str_replace(" ", "+", $q['tu-khoa']);
    }
    if (isset($_POST) && !empty($_POST) && count($_POST) > 0) {
        header('Location:' . HOST . '/' . '_pnsdotvn' . '/' . 'product.php' . '?' . $_tu_khoa);
        exit;
    }

    echo $dbf->returnTitleMenuTable($titleMenu);
    $url = 'product.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
    $condition = ($iscatURL > 0) ? 't1.cid = ' . $iscatURL : '1=1';
    $mang = $dbf->pagingJoin(prefixTable . 'product', prefixTable . 'category', ['name' => 'cid'], 'inner join', $condition . $_c_key, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, 't1.cid = t2.id');
    echo $dbf->panelAction($mang[1]);
    echo $dbf->selectFilter('cbokho', 'name', 'id', $iscatURL, 'Chọn loại danh mục:', prefixTable . 'category', [
        'firstText' => 'Chọn danh mục',
        'onchange' => "redirect('" . $_SERVER['PHP_SELF'] . "?&caturl='+this.value);",
    ], 0, 1) ?>
    <!-- view -->
    <div id="panelView"
         class="panelView"><?php $dbf->normalView($col, 'product.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg = '') ?>
    </div>
    <!-- end view-->
<?php } ?>
    <input type="hidden" name="arrayid" id="arrayid"/>
    </form>
    <script type="text/javascript" src="js/custom.js"></script>
    </body>
    </html><?php ob_end_flush() ?>