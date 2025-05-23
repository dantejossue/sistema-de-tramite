<?php
require_once 'model_conexion.php'; //importamos el archivo de conexión que contiene la clase conexionBD, esto permite conectarse con la bd usando PDO

class Modelo_Usuario extends conexionBD
{ //extends conexionBD → Indica que Modelo_Usuario hereda de conexionBD. Esto permite usar los métodos de conexionBD dentro de Modelo_Usuario.

    public function Verificar_Usuario($usu, $con)
    { //El objetivo de esta función es verificar si el usuario existe y si la contraseña es correcta.
        $c = conexionBD::conexionPDO(); //Llama al método conexionPDO() de la clase padre conexionBD. Esto permite conectarse a la base de datos. 
        $sql = "CALL SP_VERIFICAR_USUARIO(?)";
        $arreglo = array(); //Array donde guardaremos los resultados de la consulta
        $query = $c->prepare($sql); //Prepara la consulta. 
        $query->bindParam(1, $usu); // Asigna el valor de $usu al parámetro ? en la consulta SQL.
        $query->execute(); // Ejecuta la consulta.
        $resultado = $query->fetchAll(); //Obtiene todos los resultados de la consulta en un array y los guarda en la variable $resultado.
        foreach ($resultado as $resp) { // Recorremos los resultados obtenidos de la base de datos
            if (password_verify($con, $resp['usu_contra'])) {  //Compara la contraseña ingresada con la que está en la BD
                $arreglo[] = $resp; //Si es correcta la contraseña, guardamos los datos del usuario en el array $arreglo.
            }
        }
        return $arreglo; // Retornamos los datos si el usuario es válido, de lo contrario retornamos un array vacío.
        conexionBD::cerrar_conexion(); // Cerramos la conexión (este código no se ejecuta)
    }

    public function Listar_Usuario(){
        $c = conexionBD::conexionPDO(); 
        $sql = "CALL SP_LISTAR_USUARIO()";
        $arreglo = array(); // Inicializar un array vacío para almacenar los datos
        $query = $c->prepare($sql);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los resultados como un array asociativo
        foreach($resultado as $resp){
            $arreglo["data"][] = $resp; //Agregar cada fila al array bajo la clave "data". Esto es necesario porque DataTables espera un formato específico.
        }
        return $arreglo; //Retornar el array con los datos
        conexionBD::cerrar_conexion();
    }

    public function Cargar_select_Persona(){
        $c = conexionBD::conexionPDO(); //// Conectarse a la base de datos
        $sql = "CALL SP_CARGAR_SELECT_PERSONA()";
        $arreglo = array(); // Crear un array vacío para almacenar los datos
        $query = $c->prepare($sql);
        $query->execute();
        $resultado = $query->fetchAll();  // Obtener los datos en un array
        foreach($resultado as $resp){
            $arreglo[] = $resp;  // Llenar el array con los resultados
        }
        return $arreglo; //Retornar el array con los datos
        conexionBD::cerrar_conexion();
    }

    public function Cargar_select_Area(){
        $c = conexionBD::conexionPDO(); //// Conectarse a la base de datos
        $sql = "CALL SP_CARGAR_SELECT_AREA()";
        $arreglo = array(); // Crear un array vacío para almacenar los datos
        $query = $c->prepare($sql);
        $query->execute();
        $resultado = $query->fetchAll();  // Obtener los datos en un array
        foreach($resultado as $resp){
            $arreglo[] = $resp;  // Llenar el array con los resultados
        }
        return $arreglo; //Retornar el array con los datos
        conexionBD::cerrar_conexion();
    }

    public function Registrar_Usuario($usu,$con,$idp,$ida,$rol){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_REGISTRAR_USUARIO(?,?,?,?,?)"; //Llama a un procedimiento almacenado que espera cinco parámetros.
        $query = $c->prepare($sql); //Prepara la consulta para ejecutarla más adelante.
        $query -> bindParam(1,$usu); //Primer parámetro: Usuario ingresado.
        $query -> bindParam(2,$con); //Segundo parámetro: Contraseña encriptada.
        $query -> bindParam(3,$idp); //Tercer parámetro: ID de la persona asociada.
        $query -> bindParam(4,$ida); //Cuarto parámetro: ID del área a la que pertenece.
        $query -> bindParam(5,$rol); //Quinto parámetro: Rol asignado al usuario.
        $query ->execute(); //Ejecuta la consulta preparada con los valores asignados.
        //Obtiene el resultado del procedimiento almacenado.
        if($row = $query->fetchColumn()){ //fetchColumn() → Devuelve una única columna de la primera fila del resultado. // 1 Registro exitoso // 2 Usuario ya existe // 0 Error en la base de datos
            return $row; //Si el registro fue exitoso, retorna un valor (1 o 2, según lo que devuelva el procedimiento).
        }
        conexionBD::cerrar_conexion();
    }

    public function Modificar_Usuario($id,$idp,$ida,$rol){
        $c = conexionBD::conexionPDO(); //Llama a la función conexionPDO() de la clase conexionBD, la cual establece la conexión a la base de datos usando PDO (PHP Data Objects).
        $sql = "CALL SP_MODIFICAR_USUARIO(?,?,?,?)"; //$sql define una consulta SQL que invoca el procedimiento almacenado SP_MODIFICAR_USUARIO en la base de datos.
        $query = $c->prepare($sql); //prepare($sql) prepara la consulta SQL para ejecutarse de manera segura.
        $query -> bindParam(1,$id); //bindParam() se utiliza para vincular los valores de los parámetros de la consulta con las variables de PHP.
        $query -> bindParam(2,$idp);
        $query -> bindParam(3,$ida);
        $query -> bindParam(4,$rol);
        $result = $query ->execute(); //execute() ejecuta la consulta SQL con los parámetros vinculados previamente.
        if($result){
            return 1; //Si la ejecución de la consulta fue exitosa ($resul es true), el método devuelve 1
        }else{
            return 0; //Si hubo un error en la ejecución de la consulta ($resul es false), el método devuelve 0.
        }
        conexionBD::cerrar_conexion();
    }

    public function Modificar_Usuario_Contra($id,$con){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_MODIFICAR_USUARIO_CONTRA(?,?)";
        $query = $c->prepare($sql);
        $query -> bindParam(1,$id);
        $query -> bindParam(2,$con);
        $resul = $query ->execute();
        if($resul){
            return 1;
        }else{
            return 0;
        }
        conexionBD::cerrar_conexion();
    }

    public function Modificar_Usuario_Estatus($id,$estatus){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_MODIFICAR_USUARIO_ESTATUS(?,?)";
        $query = $c->prepare($sql);
        $query -> bindParam(1,$id);
        $query -> bindParam(2,$estatus);
        $resul = $query ->execute();
        if($resul){
            return 1;
        }else{
            return 0;
        }
        conexionBD::cerrar_conexion();
    }

    public function Cargar_select_Datos_Seguimiento($numero,$dni){
        $c = conexionBD::conexionPDO(); //// Conectarse a la base de datos
        $sql = "CALL SP_CARGAR_SEGUIMIENTO_TRAMITE(?,?)";
        $arreglo = array(); // Crear un array vacío para almacenar los datos
        $query = $c->prepare($sql);
        $query -> bindParam(1,$numero);
        $query -> bindParam(2,$dni);
        $query->execute();
        $resultado = $query->fetchAll();  // Obtener los datos en un array
        foreach($resultado as $resp){
            $arreglo[] = $resp;  // Llenar el array con los resultados
        }
        return $arreglo; //Retornar el array con los datos
        conexionBD::cerrar_conexion();
    }

    public function Traer_Datos_Detalle_Seguimiento($codigo){
        $c = conexionBD::conexionPDO(); //// Conectarse a la base de datos
        $sql = "CALL SP_CARGAR_SEGUIMIENTO_TRAMITE_DETALLE(?)";
        $arreglo = array(); // Crear un array vacío para almacenar los datos
        $query = $c->prepare($sql);
        $query -> bindParam(1,$codigo);
        $query->execute();
        $resultado = $query->fetchAll();  // Obtener los datos en un array
        foreach($resultado as $resp){
            $arreglo[] = $resp;  // Llenar el array con los resultados
        }
        return $arreglo; //Retornar el array con los datos
        conexionBD::cerrar_conexion();
    }
    public function Cargar_Widget(){
        $c = conexionBD::conexionPDO(); //// Conectarse a la base de datos
        $sql = "CALL SP_TRAER_WIDGET()";
        $arreglo = array(); // Crear un array vacío para almacenar los datos
        $query = $c->prepare($sql);
        $query->execute();
        $resultado = $query->fetchAll();  // Obtener los datos en un array
        foreach($resultado as $resp){
            $arreglo[] = $resp;  // Llenar el array con los resultados
        }
        return $arreglo; //Retornar el array con los datos
        conexionBD::cerrar_conexion();
    }
}


