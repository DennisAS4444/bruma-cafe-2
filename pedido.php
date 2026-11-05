<?php
require __DIR__.'/includes/helpers.php';require __DIR__.'/includes/db.php';
$items=cart_items($pdo);if (!$items){header('Location: carrito.php');exit;}
$error='';$nombre='';$telefono='';$nota='';
if($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf();
 $nombre=trim((string)($_POST['nombre']??''));$telefono=trim((string)($_POST['telefono']??''));$nota=trim((string)($_POST['nota']??''));
 if(mb_strlen($nombre)<2||mb_strlen($nombre)>100||!preg_match('/^[+0-9 ()-]{7,25}$/',$telefono)||mb_strlen($nota)>500) $error='Revisa tu nombre, teléfono y nota.';
 else {
  try {
   $pdo->beginTransaction();
   $rows=[];$total=0;
   foreach($items as $item) {
    $q=$pdo->prepare('SELECT nombre,precio,activo FROM productos WHERE id=? FOR UPDATE');$q->execute([$item['id']]);$prod=$q->fetch();
    if(!$prod || !(int)$prod['activo']) throw new RuntimeException('Uno de los productos ya no está disponible. Vuelve al carrito.');
    $subtotal=round((float)$prod['precio']*$item['cantidad'],2);$total+=$subtotal;
    $rows[]=['id'=>$item['id'],'nombre'=>$prod['nombre'],'precio'=>$prod['precio'],'cantidad'=>$item['cantidad'],'subtotal'=>$subtotal];
   }
   $q=$pdo->prepare('INSERT INTO pedidos (cliente_nombre,cliente_telefono,nota,total,estado) VALUES (?,?,?,?,?)');
   $q->execute([$nombre,$telefono,$nota,round($total,2),'Pendiente']);$orderId=(int)$pdo->lastInsertId();
   $q=$pdo->prepare('INSERT INTO pedido_detalles (pedido_id,producto_id,producto_nombre,precio_unitario,cantidad,subtotal) VALUES (?,?,?,?,?,?)');
   foreach($rows as $r) $q->execute([$orderId,$r['id'],$r['nombre'],$r['precio'],$r['cantidad'],$r['subtotal']]);
   $pdo->commit();$_SESSION['cart']=[];$_SESSION['last_order']=$orderId;
   header('Location: confirmacion.php',true,303);exit;
  }catch(Throwable $ex){if($pdo->inTransaction()) $pdo->rollBack();$error=$ex instanceof RuntimeException?$ex->getMessage():'No se pudo registrar el pedido. Inténtalo otra vez.';}
 }
}
$total=array_sum(array_column($items,'subtotal'));
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Finalizar pedido · Bruma Café</title><link rel="stylesheet" href="assets/styles.css"></head><body><header class="top"><nav class="wrap nav"><a class="brand" href="index.php">bruma<span>.</span>café</a><a href="carrito.php">← Volver al carrito</a></nav></header><main class="wrap admin-main"><div class="eyebrow">Para recoger en el local</div><h1 class="page-title">Confirma tu pedido</h1><div class="checkout-grid"><form method="post" class="panel"><h2>Tu información</h2><p class="muted">Proyecto de demostración: no se procesan pagos ni se envían pedidos a una cafetería real. Usa datos ficticios.</p><?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Nombre<input name="nombre" maxlength="100" value="<?=e($nombre)?>" required></label><label>Teléfono de contacto<input name="telefono" maxlength="25" value="<?=e($telefono)?>" required></label><label>Notas para el pedido (opcional)<textarea name="nota" maxlength="500" rows="3"><?=e($nota)?></textarea></label><button class="btn">Confirmar pedido de prueba</button></form><aside class="panel"><h2>Resumen</h2><?php foreach($items as $item): ?><p><?= (int)$item['cantidad'] ?> × <?=e($item['nombre'])?> <strong>$<?=number_format($item['subtotal'],2)?></strong></p><?php endforeach; ?><hr><h3>Total: $<?=number_format($total,2)?></h3><p class="muted">Pago en el local (simulado).</p></aside></div></main></body></html>