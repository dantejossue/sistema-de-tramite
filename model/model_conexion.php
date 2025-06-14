<?php

//Clase para la conexión a la base de datos con PDO
//Encapsula la conexión en un clase reutilizable, para no escribir la conexion en cada archivo donde lo necesitemos
class conexionBD{

    private $pdo; //variable para almacenar la conexión

    //Función o método para conectarse a la base de datos con PDO
    public function conexionPDO(){

        $host = 'db';
        $db = 'sis_tramite';
        $user = 'admin';
        $password = 'admin';
        
        try{ //Manejo de excepciones

            //Crear la conexion con PDO
            $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);

            //Configurar el manejo de errores con PDO
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Establecer el conjunto de caracteres UTF8
            $pdo->exec('set names UTF8');
            return $pdo; // Retorna la conexión para ser usada en otras partes del sistema

        }catch(PDOException $e){
            echo 'Error al conectarse a la base de datos '.$e->getMessage();
        }
    }
    
    //Función para cerrar la conexión a la base de datos
    function cerrar_conexion(){
        $this->pdo = null; //Se cierra la conexión al asignarle null 
    }

}

?>