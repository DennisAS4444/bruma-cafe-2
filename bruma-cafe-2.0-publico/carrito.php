<?php
require __DIR__.'/includes/helpers.php';
require __DIR__.'/includes/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  verify_csrf();
  $accion=(string)($_POST['accion']??'');
  $id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
  if ($accion==='vaciar') $_SESSION['cart']=[];
  elseif ($id && $id>0) {
    if ($accion==='agregar') {
      $q=$pdo->prepare('SELECT id FROM productos WHERE id=? AND activo=1');$q->execute([$id]);
      if ($q->fetch()) $_SESSION['cart'][$id]=min(20,(int)($_SESSION['cart'][$id]??0)+1);
    } elseif ($accion==='actualizar') {
      $cantidad=filter_input(INPUT_POST,'cantidad',FILTER_VALIDATE_INT);
      if ($cantidad!==null && $cantidad!==false && $cantidad>=0 && $cantidad<=20) {
        if ($cantidad===0) unset($_SESSION['cart'][$id]);else $_SESSION['cart'][$id]=$cantidad;
      }
    } elseif ($accion==='quitar') unset($_SESSION['cart'][$id]);
  }
  header('Location: carrito.php',true,303);exit;
}
$items=cart_items($pdo);$total=array_sum(array_column($items,'subtotal'));
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tu carrito · Bruma Café</title><link rel="stylesheet" href="assets/styles.css"></head><body><header class="top"><nav class="wrap nav"><a class="brand" href="index.php">bruma<span>.</span>café</a><div class="links"><a href="index.php#menu">← Seguir comprando</a></div></nav></header><main class="wrap admin-main"><div class="eyebrow">Tu selección</div><h1 class="page-title">Carrito de compras</h1><?php if (!$items): ?><section class="panel"><p>Tu carrito está vacío.</p><a class="btn" href="index.php#menu">Ver menú</a></section><?php else: ?><section class="panel"><div class="cart-items"><?php foreach($items as $item): ?><div class="cart-item"><div><strong><?=e($item['nombre'])?></strong><p class="muted">$<?=number_format((float)$item['precio'],2)?> por unidad</p></div><form method="post" class="qty-form"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="accion" value="actualizar"><input type="hidden" name="id" value="<?=(int)$item['id']?>"><label>Cantidad <input type="number" name="cantidad" min="0" max="20" value="<?=(int)$item['cantidad']?>" required></label><button class="btn small">Actualizar</button></form><strong>$<?=number_format($item['subtotal'],2)?></strong><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="accion" value="quitar"><input type="hidden" name="id" value="<?=(int)$item['id']?>"><button class="text-button">Quitar</button></form></div><?php endforeach; ?></div><div class="cart-footer"><strong>Total: $<?=number_format($total,2)?></strong><a class="btn" href="pedido.php">Continuar al pedido</a></div></section><?php endif; ?></main><footer><div class="wrap">Bruma Café · Proyecto de demostración, sin pagos online.</div></footer></body></html>