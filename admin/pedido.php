<?php
require __DIR__.'/../includes/helpers.php';require_admin();require __DIR__.'/../includes/db.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);if(!$id){http_response_code(404);exit('Pedido no encontrado');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$estado=(string)($_POST['estado']??'');
 if(in_array($estado,['Pendiente','Preparando','Listo','Entregado','Cancelado'],true)){
  $q=$pdo->prepare('UPDATE pedidos SET estado=? WHERE id=?');$q->execute([$estado,$id]);
  header('Location: pedido.php?id='.$id,true,303);exit;
 }
}
$q=$pdo->prepare('SELECT * FROM pedidos WHERE id=?');$q->execute([$id]);$pedido=$q->fetch();if(!$pedido){http_response_code(404);exit('Pedido no encontrado');}
$q=$pdo->prepare('SELECT * FROM pedido_detalles WHERE pedido_id=?');$q->execute([$id]);$detalles=$q->fetchAll();
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pedido #<?=(int)$id?></title><link rel="stylesheet" href="../assets/styles.css"></head><body><header class="top"><nav class="wrap nav"><a class="brand" href="index.php">bruma<span>.</span>café</a><a href="index.php">← Panel</a></nav></header><main class="wrap admin-main"><h1 class="page-title">Pedido #<?=(int)$id?></h1><section class="panel"><p><strong>Cliente:</strong> <?=e($pedido['cliente_nombre'])?></p><p><strong>Contacto:</strong> <?=e($pedido['cliente_telefono'])?></p><p><strong>Nota:</strong> <?=nl2br(e($pedido['nota']))?></p><h2>Artículos</h2><?php foreach($detalles as $detalle): ?><p><?= (int)$detalle['cantidad'] ?> × <?=e($detalle['producto_nombre'])?> — $<?=number_format((float)$detalle['subtotal'],2)?></p><?php endforeach; ?><h3>Total: $<?=number_format((float)$pedido['total'],2)?></h3><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Estado<select name="estado"><?php foreach(['Pendiente','Preparando','Listo','Entregado','Cancelado'] as $estado): ?><option <?= $pedido['estado']===$estado?'selected':'' ?>><?=e($estado)?></option><?php endforeach; ?></select></label><button class="btn">Guardar estado</button></form></section></main></body></html>