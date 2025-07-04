<?php
// Clase para la conexión a la base de datos con PDO usando variables de entorno
class conexionBD {

    private $pdo;

    // Método para conectarse a la base de datos con PDO
    public function conexionPDO() {

        // Obtener los datos desde las variables de entorno
        $host = getenv('DB_HOST') ?: 'db';
        $db = getenv('DB_NAME') ?: 'sis_tramite';
        $user = getenv('DB_USER') ?: 'admin';
        $password = getenv('DB_PASS') ?: 'admin';

        try {
            // Crear la conexión con PDO
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);

            // Configurar el manejo de errores con PDO
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Establecer el conjunto de caracteres UTF8
            $pdo->exec('set names UTF8');

            return $pdo; // Retorna la conexión
        } catch (PDOException $e) {
            echo 'Error al conectarse a la base de datos: ' . $e->getMessage();
        }
    }

    // Método para cerrar la conexión
    public function cerrar_conexion() {
        $this->pdo = null;
    }
}
?>
