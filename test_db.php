<?php
$host = getenv('DB_HOST');        // tramite-db.internal
$port = getenv('DB_PORT');        // 3306
$db   = getenv('DB_NAME');        // sis_tramite
$user = getenv('DB_USER');        // root
$pass = getenv('DB_PASSWORD');    // admin

$conn = new mysqli($host, $user, $pass, $db, (int)$port);

// Mostrar error si falla
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}
echo "✅ Conexión exitosa";
?>