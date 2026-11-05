<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true]);
}
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void {
    if (!hash_equals(csrf_token(), (string)($_POST['csrf'] ?? ''))) { http_response_code(403); exit('Solicitud inválida. Vuelve a cargar la página.'); }
}
function require_admin(): void {
    if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
}

function cart_count(): int { return array_sum(array_map('intval', $_SESSION['cart'] ?? [])); }
function cart_items(PDO $pdo): array {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_map('intval', array_keys($cart));
    $q = $pdo->prepare('SELECT id,nombre,precio,activo FROM productos WHERE id IN (' . implode(',', array_fill(0,count($ids),'?')) . ')');
    $q->execute($ids);
    $rows = [];
    foreach ($q->fetchAll() as $product) {
        if (!(int)$product['activo']) continue;
        $qty = max(1,min(20,(int)($cart[$product['id']] ?? 0)));
        $product['cantidad'] = $qty;
        $product['subtotal'] = round((float)$product['precio'] * $qty,2);
        $rows[] = $product;
    }
    return $rows;
}
