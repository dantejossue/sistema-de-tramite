<?php
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'sis_tramite';
$user = getenv('DB_USER') ?: 'admin';
$pass = getenv('DB_PASS') ?: 'admin';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Conexión exitosa a la base de datos '$db' en '$host'<br>";

    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if ($tables) {
        echo "📂 Tablas encontradas:<br>";
        foreach ($tables as $table) {
            echo "- $table<br>";
        }
    } else {
        echo "⚠️ No hay tablas en la base de datos.";
    }

} catch(PDOException $e) {
    echo "❌ Error al conectar: " . $e->getMessage();
}
?>
