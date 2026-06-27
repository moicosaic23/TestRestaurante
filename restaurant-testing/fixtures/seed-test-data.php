<?php

$configPath = __DIR__ . '/../../app/config.php';

if (!file_exists($configPath)) {
    fwrite(STDERR, "No se encontro app/config.php\n");
    exit(1);
}

$config = include $configPath;
$db = $config['db'];
$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
    $db['host'],
    $db['port'],
    $db['database'],
    $db['charset']
);

$pdo = new PDO($dsn, $db['user'], $db['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function upsertUser(PDO $pdo, string $username, string $password, string $name, ?string $role, int $approved): int
{
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $id = $stmt->fetchColumn();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($id) {
        $stmt = $pdo->prepare('UPDATE users SET password = ?, name = ?, role = ?, approved = ? WHERE id = ?');
        $stmt->execute([$hash, $name, $role, $approved, $id]);
        return (int)$id;
    }

    $stmt = $pdo->prepare('INSERT INTO users (username, password, name, role, approved) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$username, $hash, $name, $role, $approved]);
    return (int)$pdo->lastInsertId();
}

function upsertProduct(PDO $pdo, string $name, string $description, float $price, int $createdBy, int $enabled, ?string $reason = null): int
{
    $stmt = $pdo->prepare('SELECT id FROM products WHERE name = ? ORDER BY id LIMIT 1');
    $stmt->execute([$name]);
    $id = $stmt->fetchColumn();

    if ($id) {
        $stmt = $pdo->prepare('UPDATE products SET description = ?, price = ?, created_by = ?, enabled = ?, disabled_reason = ? WHERE id = ?');
        $stmt->execute([$description, $price, $createdBy, $enabled, $reason, $id]);
        return (int)$id;
    }

    $stmt = $pdo->prepare('INSERT INTO products (name, description, price, created_by, enabled, disabled_reason) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $description, $price, $createdBy, $enabled, $reason]);
    return (int)$pdo->lastInsertId();
}

function createOrder(PDO $pdo, int $waiterId, string $status, array $items, ?string $comment = null): int
{
    $total = 0;
    foreach ($items as $item) {
        $total += $item['qty'] * $item['price'];
    }

    $stmt = $pdo->prepare('INSERT INTO orders (waiter_id, status, total, comment) VALUES (?, ?, ?, ?)');
    $stmt->execute([$waiterId, $status, $total, $comment]);
    $orderId = (int)$pdo->lastInsertId();

    $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)');
    foreach ($items as $item) {
        $stmtItem->execute([$orderId, $item['product_id'], $item['qty'], $item['price']]);
    }

    return $orderId;
}

$adminId = upsertUser($pdo, 'qa_admin', 'qa_admin123', 'QA Admin', 'admin', 1);
$waiterId = upsertUser($pdo, 'qa_waiter', 'qa_waiter123', 'QA Waiter', 'waiter', 1);
$cookId = upsertUser($pdo, 'qa_cook', 'qa_cook123', 'QA Cook', 'cook', 1);
upsertUser($pdo, 'qa_pending', 'qa_pending123', 'QA Pending', 'waiter', 0);
upsertUser($pdo, 'qa_role_change', 'qa_role123', 'QA Role Change', 'waiter', 1);

$cevicheId = upsertProduct($pdo, 'QA Ceviche', 'Producto base para pruebas', 35.00, $cookId, 1);
$ajiId = upsertProduct($pdo, 'QA Aji de gallina', 'Producto base para pruebas', 24.00, $cookId, 1);
$arrozId = upsertProduct($pdo, 'QA Arroz con pollo', 'Producto base para pruebas', 18.00, $cookId, 1);
upsertProduct($pdo, 'QA Producto deshabilitado', 'Producto no visible para mozo', 10.00, $cookId, 0, 'QA sin insumos');
upsertProduct($pdo, 'QA Plato temporal', 'Producto para deshabilitar', 12.50, $cookId, 1);

$stmt = $pdo->prepare('DELETE FROM orders WHERE waiter_id = ?');
$stmt->execute([$waiterId]);

createOrder($pdo, $waiterId, 'pending', [
    ['product_id' => $cevicheId, 'qty' => 1, 'price' => 35.00],
]);
createOrder($pdo, $waiterId, 'pending', [
    ['product_id' => $ajiId, 'qty' => 2, 'price' => 24.00],
]);
createOrder($pdo, $waiterId, 'preparing', [
    ['product_id' => $arrozId, 'qty' => 1, 'price' => 18.00],
]);
createOrder($pdo, $waiterId, 'cancelled', [
    ['product_id' => $cevicheId, 'qty' => 1, 'price' => 35.00],
], 'QA_AUTOMATION pedido cancelado');

echo "Datos de prueba QA preparados correctamente.\n";

