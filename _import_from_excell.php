<?php

// ===============================
// 1. CONFIG (DB mapping target)
// ===============================
$CONFIG = [
    'group_key' => 'order_id',

    'header_fields' => [
        'order_id' => 'order_id',
        'customer' => 'customer_name',
        'date'     => 'order_date'
    ],

    'item_fields' => [
        'product' => 'product_name',
        'qty'     => 'qty',
        'price'   => 'price'
    ]
];


// ===============================
// 2. READ CSV / EXCEL (CSV assumed)
// ===============================
function readCSV($file){

    $rows = [];

    if(($handle = fopen($file, "r")) !== FALSE){

        $headers = fgetcsv($handle);

        while(($data = fgetcsv($handle)) !== FALSE){

            $row = [];

            foreach($headers as $i => $h){
                $row[$h] = $data[$i] ?? null;
            }

            $rows[] = $row;
        }

        fclose($handle);
    }

    return $rows;
}


// ===============================
// 3. GROUP ENGINE (CORE LOGIC)
// ===============================
function buildStructuredOrders($rows, $groupKey){

    $orders = [];

    foreach($rows as $row){

        $id = $row[$groupKey];

        if(!isset($orders[$id])){

            $orders[$id] = [
                'header' => $row,
                'items'  => []
            ];
        }

        $orders[$id]['items'][] = $row;
    }

    return array_values($orders);
}


// ===============================
// 4. MAPPING ENGINE
// ===============================
function mapOrder($order, $config){

    $header = [];

    foreach($config['header_fields'] as $source => $target){
        $header[$target] = $order['header'][$source] ?? null;
    }

    $items = [];

    foreach($order['items'] as $item){

        $tmp = [];

        foreach($config['item_fields'] as $source => $target){
            $tmp[$target] = $item[$source] ?? null;
        }

        $items[] = $tmp;
    }

    return [$header, $items];
}


// ===============================
// 5. MAIN IMPORT FUNCTION
// ===============================
function importOrders($file, $config){

    $rows = readCSV($file);

    $grouped = buildStructuredOrders($rows, $config['group_key']);

    foreach($grouped as $order){

        list($header, $items) = mapOrder($order, $config);

        saveToDatabase($header, $items);
    }

    return true;
}


// ===============================
// 6. DATABASE INSERT (PDO LAYER)
// ===============================
function saveToDatabase($header, $items){

    global $connection;

    // insert order header
    $sql = "INSERT INTO order_title (order_id, customer_name, order_date)
            VALUES (:order_id, :customer_name, :order_date)";

    $stmt = $connection->prepare($sql);
    $stmt->execute($header);

    $orderId = $header['order_id'];

    // insert items
    foreach($items as $item){

        $sql = "INSERT INTO order_details (order_id, product_name, qty, price)
                VALUES (:order_id, :product_name, :qty, :price)";

        $item['order_id'] = $orderId;

        $stmt = $connection->prepare($sql);
        $stmt->execute($item);
    }
}


// ===============================
// 7. RUN IMPORT
// ===============================

$file = "orders.csv";

importOrders($file, $CONFIG);

echo "Import Done";

?>