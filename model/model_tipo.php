<?php
    require_once 'model_conexion.php';

    class Modelo_Tipo extends conexionBD{

        public function Listar_Tipo(){
            $c = conexionBD::conexionPDO();
            $sql = "CALL SP_LISTAR_TIPO_DOCUMENTO()";
            $arreglo = array();
            $query = $c->prepare($sql);
            $query ->execute();
            $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach($resultado as $resp){
                $arreglo["data"][]=$resp;
            }
            return $arreglo;
            conexionBD::cerrar_conexion();
        }

        public function Registrar_Tipo($tipo){
            $c = conexionBD::conexionPDO();
            $sql = "CALL SP_REGISTRAR_TIPO(?)";
            $query = $c->prepare($sql);
            $query -> bindParam(1,$tipo);
            $query ->execute();
            if($row = $query->fetchColumn()){
                return $row;
            }
            conexionBD::cerrar_conexion();
        }

        public function Modificar_Tipo($id,$tipo,$esta){
            $c = conexionBD::conexionPDO();
            $sql = "CALL SP_MODIFICAR_TIPO(?,?,?)";
            $query = $c->prepare($sql);
            $query -> bindParam(1,$id);
            $query -> bindParam(2,$tipo);
            $query -> bindParam(3,$esta);
            $query ->execute();
            if($row = $query->fetchColumn()){
                return $row;
            }
            conexionBD::cerrar_conexion();
        }

    }

?>