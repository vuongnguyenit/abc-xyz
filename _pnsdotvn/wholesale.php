<?php
$host = '139.99.46.39';
$db = 'vuongnt777_crc';
$username = 'vuongnt777_crc';
$password = 'hn14Ih5E_t!LkUL2';
$dsn = "mysql:host=$host;dbname=$db";

try {
    // delete item
    $action = isset($_GET['action']) ? $_GET['action'] : NULL;
    $sid = isset($_GET['sid']) ? $_GET['sid'] : NULL;
    $productId = isset($_GET['product_id']) ? $_GET['product_id'] : NULL;

    $quantityFrom = isset($_GET['quantity_from']) ? $_GET['quantity_from'] : NULL;
    $quantityTo = isset($_GET['quantity_to']) ? $_GET['quantity_to'] : NULL;
    $wholesalePrice = isset($_GET['wholesale_price']) ? $_GET['wholesale_price'] : NULL;
    // create a PDO connection with the configuration data
    $conn = new PDO($dsn, $username, $password);
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
        $stmt = $conn->prepare($sql);

        $result = $stmt->execute([
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
        $sql = 'select wholesale from dynw_product where id=:id';
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