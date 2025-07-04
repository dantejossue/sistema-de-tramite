<?php
// Clase para la conexión a la base de datos con PDO usando variables de entorno
class conexionBD {

<<<<<<< HEAD
//Clase para la conexión a la base de datos con PDO
//Encapsula la conexión en un clase reutilizable, para no escribir la conexion en cada archivo donde lo necesitemos
class conexionBD
{
=======
    private $pdo;
>>>>>>> ce048a4c10b4207f5d70efe4c5919f8db6ebb2b4

    // Método para conectarse a la base de datos con PDO
    public function conexionPDO() {

<<<<<<< HEAD
    //Función o método para conectarse a la base de datos con PDO
    public function conexionPDO()
    {

        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');


        try { //Manejo de excepciones

            //Crear la conexion con PDO
            $pdo = new PDO("mysql:host=$host;dbname=$db;port=$port", $user, $password);

            //Configurar el manejo de errores con PDO
=======
        // Obtener los datos desde las variables de entorno
        $host = getenv('DB_HOST') ?: 'db';
        $db = getenv('DB_NAME') ?: 'sis_tramite';
        $user = getenv('DB_USER') ?: 'admin';
        $password = getenv('DB_PASS') ?: 'admin';

        try {
            // Crear la conexión con PDO
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);

            // Configurar el manejo de errores con PDO
>>>>>>> ce048a4c10b4207f5d70efe4c5919f8db6ebb2b4
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Establecer el conjunto de caracteres UTF8
            $pdo->exec('set names UTF8');

<<<<<<< HEAD
        } catch (PDOException $e) {
            echo 'Error al conectarse a la base de datos ' . $e->getMessage();
        }
    }

    //Función para cerrar la conexión a la base de datos
    function cerrar_conexion()
    {
        $this->pdo = null; //Se cierra la conexión al asignarle null 
    }
}
=======
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
>>>>>>> ce048a4c10b4207f5d70efe4c5919f8db6ebb2b4
