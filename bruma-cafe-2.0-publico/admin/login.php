<?php
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/db.php';
if (!empty($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $stmt = $pdo->prepare('SELECT id, password_hash FROM administradores WHERE usuario = ? LIMIT 1');
  $stmt->execute([trim((string)($_POST['usuario'] ?? ''))]);
  $admin = $stmt->fetch();
  if ($admin && password_verify((string)($_POST['password'] ?? ''), $admin['password_hash'])) {
    session_regenerate_id(true); $_SESSION['admin_id'] = (int)$admin['id']; header('Location: index.php'); exit;
  }
  $error = 'Usuario o contraseña incorrectos.';
}
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Acceso administrador · Bruma Café</title><link rel="stylesheet" href="../assets/styles.css"></head><body><header class="top"><nav class="wrap nav"><a class="brand" href="../index.php">bruma<span>.</span>café</a><a href="../index.php">Volver al sitio</a></nav></header><main class="wrap admin-main"><form class="contact-card" method="post"><h2>Acceso al panel</h2><p class="muted">Solo para administradores.</p><?php if ($error): ?><p class="alert error"><?= e($error) ?></p><?php endif; ?><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Usuario</label><input name="usuario" required autocomplete="username"><label>Contraseña</label><input name="password" type="password" required autocomplete="current-password"><p><button class="btn">Iniciar sesión</button></p></form></main></body></html>
