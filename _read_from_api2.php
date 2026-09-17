<?php
// -------------------- اتصال به دیتابیس --------------------
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

// -------------------- تابع خواندن یک صفحه API --------------------
function fetch_api_page($url, $token, $page) {
    $url .= (strpos($url, '?') === false ? '?' : '&') . "token=" . urlencode($token) . "&page=$page";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $response = curl_exec($ch);
    if(curl_errno($ch)) { echo 'Curl error: ' . curl_error($ch); return false; }
    curl_close($ch);
    $data = json_decode($response, true);
    return $data ?: false;
}

// -------------------- ذخیره محصولات chunk به chunk --------------------
function import_products($pdo, $api_url, $token) {
    $page = 1;
    do {
        $data = fetch_api_page($api_url, $token, $page);
        if(!$data || empty($data)) break;

        foreach($data as $product) {
            $stmt = $pdo->prepare("
                INSERT INTO products 
                (id, name, slug, price, regular_price, sale_price, stock_status, image, link, date_created, date_modified, categories, tags, brand, attributes, variations)
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
        echo "Products page $page imported.\n";
        $page++;
    } while(!empty($data));
}

// -------------------- ذخیره کاربران --------------------
function import_users($pdo, $api_url, $token) {
    $page = 1;
    do {
        $data = fetch_api_page($api_url, $token, $page);
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
        echo "Users page $page imported.\n";
        $page++;
    } while(!empty($data['users']));
}

// -------------------- ذخیره سفارش‌ها chunk به chunk --------------------
function import_orders($pdo, $api_url, $token) {
    $page = 1;
    do {
        $data = fetch_api_page($api_url, $token, $page);
        if(!$data || empty($data['orders'])) break;

        foreach($data['orders'] as $order) {
            // تیتر سفارش
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

            // آیتم‌های سفارش
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
        echo "Orders page $page imported.\n";
        $page++;
    } while(!empty($data['orders']));
}

// -------------------- اجرای import --------------------
$token = 'javad2839';
import_products($pdo, 'https://wp-shop.com/wp-json/custom/v1/products', $token);
import_users($pdo, 'https://wp-shop.com/wp-json/custom/v1/users', $token);
import_orders($pdo, 'https://wp-shop.com/wp-json/custom/v1/orders', $token);

echo "All data imported successfully.\n";
?>