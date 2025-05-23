<?php
require_once 'model_conexion.php'; //importamos el archivo de conexión que contiene la clase conexionBD, esto permite conectarse con la bd usando PDO

class Modelo_Area extends conexionBD
{ //extends conexionBD → Indica que Modelo_Usuario hereda de conexionBD. Esto permite usar los métodos de conexionBD dentro de Modelo_Usuario.

    public function Listar_Area(){
        $c = conexionBD::conexionPDO(); 
        $sql = "CALL SP_LISTAR_AREA()";
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

    public function Registrar_Area($area){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_REGISTRAR_AREA(?)";
        $query = $c->prepare($sql);
        $query -> bindParam(1,$area);
        $query ->execute();
        if($row = $query->fetchColumn()){
            return $row;
        }
        conexionBD::cerrar_conexion();
    }

    public function Modificar_Area($id,$area,$esta){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_MODIFICAR_AREA(?,?,?)";
        $query = $c->prepare($sql);
        $query -> bindParam(1,$id);
        $query -> bindParam(2,$area);
        $query -> bindParam(3,$esta);
        $query ->execute();
        if($row = $query->fetchColumn()){
            return $row;
        }
        conexionBD::cerrar_conexion();
    }
}