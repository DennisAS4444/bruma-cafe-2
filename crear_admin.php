<?php
// Ejecutar SOLO en la terminal: php crear_admin.php usuario clave_segura
if (PHP_SAPI !== 'cli') { http_response_code(404); exit('No disponible en navegador'); }
if ($argc !== 3 || strlen($argv[2]) < 12 || strlen($argv[1]) < 3) { exit("Uso: php crear_admin.php usuario clave_de_12_o_mas_caracteres\n"); }
require __DIR__ . '/includes/db.php';
$s = $pdo->prepare('INSERT INTO administradores (usuario, password_hash) VALUES (?, ?)');
try { $s->execute([$argv[1], password_hash($argv[2], PASSWORD_DEFAULT)]); echo "Administrador creado. Borra este archivo cuando termines.\n"; }
catch (PDOException $e) { exit("No se pudo crear el administrador. ¿Ya existe ese usuario?\n"); }
