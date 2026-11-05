<?php
require __DIR__ . '/../includes/helpers.php';require_admin();require __DIR__ . '/../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {http_response_code(405);exit;}
verify_csrf();
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if ($id) {$s=$pdo->prepare('DELETE FROM productos WHERE id = ?');$s->execute([$id]);}
header('Location: index.php');exit;
