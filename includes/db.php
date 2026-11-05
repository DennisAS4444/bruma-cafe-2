<?php
$configFile = __DIR__ . '/../config.php';
if (!is_file($configFile)) { http_response_code(500); exit('Primero crea config.php a partir de config.example.php.'); }
$config = require $configFile;
try {
    $pdo = new PDO(
        'mysql:host=' . $config['host'] . ';dbname=' . $config['database'] . ';charset=utf8mb4',
        $config['username'], $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
    );
} catch (PDOException $e) { http_response_code(500); exit('No se pudo conectar a la base de datos. Revisa XAMPP y config.php.'); }
