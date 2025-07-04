<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');

$mysql = new mysqli($host, $user, $pass, $db);

if ($mysql->connect_error) {
    die("Error de conexión: " . $mysql->connect_error);
}
?>