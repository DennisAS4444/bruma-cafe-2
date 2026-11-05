<?php
require __DIR__ . '/../includes/helpers.php';
$_SESSION = []; session_regenerate_id(true); session_destroy();
header('Location: login.php'); exit;
