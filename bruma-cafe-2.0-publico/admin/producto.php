<?php
require __DIR__ . '/../includes/helpers.php'; require_admin(); require __DIR__ . '/../includes/db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$producto = ['nombre'=>'','descripcion'=>'','precio'=>'','categoria'=>'Bebidas','activo'=>1];
if ($id) {
  $s=$pdo->prepare('SELECT * FROM productos WHERE id = ?'); $s->execute([$id]); $producto=$s->fetch();
  if (!$producto) { http_response_code(404); exit('Producto no encontrado'); }
}
$error='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 verify_csrf();
 $nombre=trim((string)($_POST['nombre'] ?? ''));
 $descripcion=trim((string)($_POST['descripcion'] ?? ''));
 $precio=(string)($_POST['precio'] ?? '');
 $categoria=(string)($_POST['categoria'] ?? '');
 $activo=isset($_POST['activo']) ? 1:0;
 $producto=compact('nombre','descripcion','precio','categoria','activo');
 if ($nombre==='' || mb_strlen($nombre)>100 || $descripcion==='' || mb_strlen($descripcion)>255 || !preg_match('/^\d{1,7}(?:\.\d{1,2})?$/',$precio) || (float)$precio<=0 || !in_array($categoria,['Bebidas','Postres','Comidas'],true)) $error='Completa los campos correctamente (precio positivo, máximo dos decimales).';
 else {
  if ($id) { $s=$pdo->prepare('UPDATE productos SET nombre=?,descripcion=?,precio=?,categoria=?,activo=? WHERE id=?'); $s->execute([$nombre,$descripcion,$precio,$categoria,$activo,$id]); }
  else { $s=$pdo->prepare('INSERT INTO productos (nombre,descripcion,precio,categoria,activo) VALUES (?,?,?,?,?)'); $s->execute([$nombre,$descripcion,$precio,$categoria,$activo]); }
  header('Location: index.php');exit;
 }
}
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Producto · Bruma Café</title><link rel="stylesheet" href="../assets/styles.css"></head><body><header class="top"><nav class="wrap nav"><a class="brand" href="../index.php">bruma<span>.</span>café</a><a href="index.php">← Panel</a></nav></header><main class="wrap admin-main"><form method="post" class="contact-card"><h2><?= $id ? 'Editar producto' : 'Nuevo producto' ?></h2><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Nombre</label><input name="nombre" maxlength="100" value="<?= e($producto['nombre']) ?>" required><label>Descripción</label><textarea name="descripcion" maxlength="255" rows="3" required><?= e($producto['descripcion']) ?></textarea><div class="cols"><div><label>Precio (USD)</label><input name="precio" type="number" min="0.01" max="9999999" step="0.01" value="<?= e($producto['precio']) ?>" required></div><div><label>Categoría</label><select name="categoria"><?php foreach (['Bebidas','Postres','Comidas'] as $cat): ?><option value="<?= e($cat) ?>" <?= $producto['categoria']===$cat?'selected':'' ?>><?= e($cat) ?></option><?php endforeach; ?></select></div></div><p><label><input style="width:auto" type="checkbox" name="activo" <?= $producto['activo']?'checked':'' ?>> Visible en el menú</label></p><button class="btn">Guardar producto</button></form></main></body></html>
