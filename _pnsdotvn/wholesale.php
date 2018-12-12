<?php
$host = '139.99.46.39';
$db = 'vuongnt777_crc';
$username = 'vuongnt777_crc';
$password = 'hn14Ih5E_t!LkUL2';
$dsn = "mysql:host=$host;dbname=$db";
// create a PDO connection with the configuration data
$conn = new PDO($dsn, $username, $password);
try {
    // delete item
    $action = isset($_GET['action']) ? $_GET['action'] : NULL;
    $productId = isset($_GET['product_id']) ? $_GET['product_id'] : NULL;
    if($action == 'delete-doc') {
        $pos = isset($_GET['pos']) ? $_GET['pos'] : NULL;
        $product = getProduct($conn, $productId);
        $folder = $_SERVER['DOCUMENT_ROOT'] . '/_pnsdotvn/documents/';
        $doc = unserialize($product['doc']);
        $file = $doc[$pos];
        $fileLink = $folder .  $file['file'];

        unset($doc[$pos]);
        $sql = 'update dynw_product set doc = :doc where id =:id ';
        updateProduct($conn, $sql, [
            'doc' => serialize($doc),
            'id' => $productId,
        ]);
        if (file_exists($fileLink)) {
            unlink($fileLink);
        }
        die(json_encode(array(
            'message' => 'success'
        )));
    }
    $sid = isset($_GET['sid']) ? $_GET['sid'] : NULL;

    $quantityFrom = isset($_GET['quantity_from']) ? $_GET['quantity_from'] : NULL;
    $quantityTo = isset($_GET['quantity_to']) ? $_GET['quantity_to'] : NULL;
    $wholesalePrice = isset($_GET['wholesale_price']) ? $_GET['wholesale_price'] : NULL;

    $data = [];
    // get product info
    $product = getProduct($conn, $productId);
    $wholesale = [];
    if(!empty($action) && $action == 'delete') {
        // get product info
        $product = getProduct($conn, $productId);
        $wholesale = unserialize($product['wholesale']);
        foreach ($wholesale as $value) {
            if($sid == $value['id']) {
                unset($value);
                continue;
            }
            $data[] = $value;
        }
    } else {
        if (!empty($product['wholesale'])) {
            $wholesale = unserialize($product['wholesale']);
            $last = end($wholesale);
            $newWholesale = [
                'id' => intval($last['id']) + 1,
                'quantity_from' => $quantityFrom,
                'quantity_to' => $quantityTo,
                'wholesale_price' => $wholesalePrice
            ];
            array_push($wholesale, $newWholesale);
            $data = $wholesale;
        }
        else {
            $id = 0;
            $data = [
                [
                    'id' => $id,
                    'quantity_from' => $quantityFrom,
                    'quantity_to' => $quantityTo,
                    'wholesale_price' => $wholesalePrice
                ]
            ];
        }
    }
    // display a message if connected to database successfully
    if ($productId) {
        $sql = 'update dynw_product set wholesale = :wholesale where id =:id ';
        updateProduct($conn, $sql, [
            'wholesale' => serialize($data),
            'id' => $productId,
        ]);
        $product = getProduct($conn, $productId);
        die (json_encode(array(
            'wholesale' => unserialize($product['wholesale']),
            'product_id' => $productId
        )));
    }
} catch (PDOException $e) {
    // report error message
    echo $e->getMessage();
}
/**
 * @param $conn
 * @param $productId
 *
 * @return mixed
 */
function getProduct($conn, $productId) {
    try {
        $sql = 'select * from dynw_product where id=:id';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id' => $productId,
        ]);
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $product;
    } catch (PDOException $e) {
        // report error message
        echo $e->getMessage();
    }
}

/**
 * @param $conn
 * @param $sql
 * @param $dataUpdate
 */
function updateProduct($conn, $sql, $dataUpdate) {
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($dataUpdate);
    } catch (PDOException $e) {
        // report error message
        echo $e->getMessage();
    }
}