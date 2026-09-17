<?php
//https://goushtbazar.com//wp-json/custom/v1/products?token=javad2839

// اتصال به دیتابیس سایت مقصد
$host = 'localhost';
$db   = 'your_db';
$user = 'db_user';
$pass = 'db_pass';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// -------------------- تابع خواندن API --------------------
function fetch_api_data($url, $token) {
    $url .= (strpos($url, '?') === false ? '?' : '&') . 'token=' . urlencode($token);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $response = curl_exec($ch);
    if(curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return false;
    }
    curl_close($ch);

    $data = json_decode($response, true);
    if(!$data) {
        echo "Invalid JSON from API: $url\n";
        return false;
    }
    return $data;
}

// -------------------- ذخیره محصولات --------------------
$products_api = 'https://wp-shop.com/wp-json/custom/v1/products';
$products_api = 'https://goushtbazar.com//wp-json/custom/v1/products';
$token = 'javad2839';

$page = 1;
do {
    $data = fetch_api_data($products_api . '&page=' . $page, $token);
    if(!$data || empty($data)) break;

    foreach($data as $product) {
        $stmt = $pdo->prepare("
            INSERT INTO products (id, name, slug, price, regular_price, sale_price, stock_status, image, link, date_created, date_modified, categories, tags, brand, attributes, variations)
            VALUES (:id,:name,:slug,:price,:regular_price,:sale_price,:stock_status,:image,:link,:date_created,:date_modified,:categories,:tags,:brand,:attributes,:variations)
            ON DUPLICATE KEY UPDATE
            name=VALUES(name),
            price=VALUES(price),
            regular_price=VALUES(regular_price),
            sale_price=VALUES(sale_price),
            stock_status=VALUES(stock_status),
            image=VALUES(image),
            link=VALUES(link),
            date_created=VALUES(date_created),
            date_modified=VALUES(date_modified),
            categories=VALUES(categories),
            tags=VALUES(tags),
            brand=VALUES(brand),
            attributes=VALUES(attributes),
            variations=VALUES(variations)
        ");

        $stmt->execute([
            ':id' => $product['id'],
            ':name' => $product['name'] ?? '',
            ':slug' => $product['slug'] ?? '',
            ':price' => $product['price'] ?? 0,
            ':regular_price' => $product['regular_price'] ?? 0,
            ':sale_price' => $product['sale_price'] ?? 0,
            ':stock_status' => $product['stock_status'] ?? '',
            ':image' => $product['image'] ?? '',
            ':link' => $product['link'] ?? '',
            ':date_created' => $product['date_created'] ?? null,
            ':date_modified' => $product['date_modified'] ?? null,
            ':categories' => isset($product['categories']) ? json_encode($product['categories']) : null,
            ':tags' => isset($product['tags']) ? json_encode($product['tags']) : null,
            ':brand' => $product['brand'] ?? null,
            ':attributes' => isset($product['attributes']) ? json_encode($product['attributes']) : null,
            ':variations' => isset($product['variations']) ? json_encode($product['variations']) : null,
        ]);
    }

    $page++;
} while(!empty($data));

echo "Products imported.\n";

// -------------------- ذخیره کاربران --------------------
//https://goushtbazar.com//wp-json/custom/v1/users?token=javad2839&page=1&limit=20000
$users_api = 'https://goushtbazar.com//wp-json/custom/v1/users';
$page = 1;
do {
    $data = fetch_api_data($users_api . '&page=' . $page, $token);
    if(!$data || empty($data['users'])) break;

    foreach($data['users'] as $user) {
        $stmt = $pdo->prepare("
            INSERT INTO users (id, username, email, name, first_name, last_name, phone, city, roles, registered)
            VALUES (:id,:username,:email,:name,:first_name,:last_name,:phone,:city,:roles,:registered)
            ON DUPLICATE KEY UPDATE
            username=VALUES(username),
            email=VALUES(email),
            name=VALUES(name),
            first_name=VALUES(first_name),
            last_name=VALUES(last_name),
            phone=VALUES(phone),
            city=VALUES(city),
            roles=VALUES(roles),
            registered=VALUES(registered)
        ");

        $stmt->execute([
            ':id' => $user['id'],
            ':username' => $user['username'] ?? '',
            ':email' => $user['email'] ?? '',
            ':name' => $user['name'] ?? '',
            ':first_name' => $user['first_name'] ?? '',
            ':last_name' => $user['last_name'] ?? '',
            ':phone' => $user['phone'] ?? '',
            ':city' => $user['city'] ?? '',
            ':roles' => isset($user['roles']) ? json_encode($user['roles']) : null,
            ':registered' => $user['registered'] ?? null,
        ]);
    }
    $page++;
} while(!empty($data['users']));

echo "Users imported.\n";

// -------------------- ذخیره سفارش‌ها --------------------
$orders_api = 'https://goushtbazar.com//wp-json/custom/v1/orders';
$page = 1;
do {
    $data = fetch_api_data($orders_api . '&page=' . $page, $token);
    if(!$data || empty($data['orders'])) break;

    foreach($data['orders'] as $order) {
        // جدول تیتر سفارش
        $stmt = $pdo->prepare("
            INSERT INTO orders (id, status, total, customer_name, customer_phone, date_created)
            VALUES (:id,:status,:total,:customer_name,:customer_phone,:date_created)
            ON DUPLICATE KEY UPDATE
            status=VALUES(status),
            total=VALUES(total),
            customer_name=VALUES(customer_name),
            customer_phone=VALUES(customer_phone),
            date_created=VALUES(date_created)
        ");
        $stmt->execute([
            ':id' => $order['id'],
            ':status' => $order['status'] ?? '',
            ':total' => $order['total'] ?? 0,
            ':customer_name' => $order['customer']['name'] ?? '',
            ':customer_phone' => $order['customer']['phone'] ?? '',
            ':date_created' => $order['date_created'] ?? null,
        ]);

        // جدول آیتم‌های سفارش
        if(!empty($order['items'])) {
            foreach($order['items'] as $item) {
                $stmt2 = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, name, qty, total)
                    VALUES (:order_id,:product_id,:name,:qty,:total)
                    ON DUPLICATE KEY UPDATE
                    qty=VALUES(qty),
                    total=VALUES(total)
                ");
                $stmt2->execute([
                    ':order_id' => $order['id'],
                    ':product_id' => $item['product_id'],
                    ':name' => $item['name'],
                    ':qty' => $item['qty'],
                    ':total' => $item['total'],
                ]);
            }
        }
    }
    $page++;
} while(!empty($data['orders']));

echo "Orders imported.\n";

?>