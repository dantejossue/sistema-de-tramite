<?php

require_once 'model_conexion.php';

class Modelo_TramiteArea extends conexionBD{

    public function Listar_Tramite($idusuario,$estado){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_LISTAR_TRAMITE_AREA(?,?)";
        $arreglo = array();
        $query = $c->prepare($sql);
        $query -> bindParam(1,$idusuario);
        $query->bindParam(2, $estado);
        $query ->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($resultado as $resp){
            $arreglo["data"][]=$resp;
        }
        return $arreglo;
        conexionBD::cerrar_conexion();
    }

    public function Listar_Tramite_Filtrado($idusuario, $estado, $fechaini, $fechafin){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_LISTAR_TRAMITE_AREA_FILTRO(?, ?, ?, ?)";
        $query = $c->prepare($sql);
        $query->bindParam(1, $idusuario);
        $query->bindParam(2, $estado);
        $query->bindParam(3, $fechaini);
        $query->bindParam(4, $fechafin);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $arreglo["data"] = $resultado;
        return $arreglo;
    }

    public function Listar_Tramite_Derivados($idusuario, $estado, $fechaini, $fechafin) {
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_LISTAR_TRAMITE_AREA_DERIVADOS(?,?,?,?)";  // Añadimos los parámetros de fecha
        $arreglo = array();
        $query = $c->prepare($sql);
        $query->bindParam(1, $idusuario);
        $query->bindParam(2, $estado);
        // Si las fechas están vacías, enviamos NULL
        $query->bindParam(3, $fechaini, PDO::PARAM_STR);
        $query->bindParam(4, $fechafin, PDO::PARAM_STR);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultado) > 0) {
            foreach ($resultado as $resp) {
                $arreglo["data"][] = $resp;
            }
        } else {
            $arreglo["data"] = [];
        }

        return $arreglo;
        conexionBD::cerrar_conexion();
    }

    public function Cargar_Select_Tipo(){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_CARGAR_SELECT_TIPO()";
        $arreglo = array();
        $query = $c->prepare($sql);
        $query ->execute();
        $resultado = $query->fetchAll();
        foreach($resultado as $resp){
            $arreglo[]=$resp;
        }
        return $arreglo;
        conexionBD::cerrar_conexion();
    }

    public function Registrar_Tramite($idtra,$orig,$dest,$desc,$idusu,$ruta){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_REGISTRAR_TRAMITE_DERIVAR(?,?,?,?,?,?)";
        $query = $c->prepare($sql);
        $query -> bindParam(1,$idtra);
        $query -> bindParam(2,$orig);
        $query -> bindParam(3,$dest);
        $query -> bindParam(4,$desc);
        $query -> bindParam(5,$idusu);
        $query -> bindParam(6,$ruta);
        $result = $query->execute(); //execute() ejecuta la consulta SQL con los parámetros vinculados previamente.
        if($result){
            return 1; //Si la ejecución de la consulta fue exitosa ($resul es true), el método devuelve 1
        }else{
            return 0; //Si hubo un error en la ejecución de la consulta ($resul es false), el método devuelve 0.
        }
        conexionBD::cerrar_conexion();
    }

    public function Listar_Tramite_Seguimiento($id){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_LISTAR_TRAMITE_SEGUIMIENTO(?)";
        $arreglo = array();
        $query = $c->prepare($sql);
        $query -> bindParam(1,$id);
        $query ->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($resultado as $resp){
            $arreglo["data"][]=$resp;
        }
        return $arreglo;
        conexionBD::cerrar_conexion();
    }

    public function Cambiar_Estado_Tramite($idtramite, $estado, $descripcion, $idusuario, $area_destino){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_CAMBIAR_ESTADO_TRAMITE(?, ?, ?, ?, ?)";
        $query = $c->prepare($sql);
        $query->bindParam(1, $idtramite);
        $query->bindParam(2, $estado);
        $query->bindParam(3, $descripcion);
        $query->bindParam(4, $idusuario);
        $query->bindParam(5, $area_destino);
        $resultado = $query->execute();
        return $resultado;
    }


}

?>
