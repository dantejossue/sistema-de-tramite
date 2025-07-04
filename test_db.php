<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=sis_tramite;port=3306", "root", "admin");
    echo "✅ Conexión OK";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}