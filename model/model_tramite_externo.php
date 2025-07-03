<?php

require_once 'model_conexion.php';

class Modelo_Tramite extends conexionBD{

    public function Listar_Tramite(){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_LISTAR_TRAMITES()";
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

    // Método ajustado para los trámites externos
    public function Registrar_Tramite($dni,$nom,$apt,$apm,$cel,$ema,$dir,$vpresentacion,$ruc,$raz,$arp,$ard,$tip,$ndo,$asu,$ruta,$fol,$idusu){
        $c = conexionBD::conexionPDO();

        // Para el trámite externo, las áreas son siempre "EXTERNO" y "MESA DE PARTES"
        $arp = 19; // Área de origen para trámite externo
        $ard = 17; // Área de destino siempre "MESA DE PARTES"

        $sql = "CALL SP_REGISTRAR_TRAMITE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query = $c->prepare($sql);

        // Enlazar parámetros
        $query->bindParam(1, $dni);
        $query->bindParam(2, $nom);
        $query->bindParam(3, $apt);
        $query->bindParam(4, $apm);
        $query->bindParam(5, $cel);
        $query->bindParam(6, $ema);
        $query->bindParam(7, $dir);
        $query->bindParam(8, $vpresentacion);
        $query->bindParam(9, $ruc);
        $query->bindParam(10, $raz);
        $query->bindParam(11, $arp); // Usamos "EXTERNO"
        $query->bindParam(12, $ard); // Usamos "MESA DE PARTES"
        $query->bindParam(13, $tip);
        $query->bindParam(14, $ndo);
        $query->bindParam(15, $asu);
        $query->bindParam(16, $ruta);
        $query->bindParam(17, $fol);
        $query->bindParam(18, $idusu);

        // Ejecutar la consulta
        $query->execute();

        // Retorna el ID del trámite si fue exitoso
        if ($row = $query->fetchColumn()) {
            return $row;
        }

        conexionBD::cerrar_conexion();
    }

    public function Registrar_Tramite_Pago($dni,$nom,$apt,$apm,$cel,$ema,$dir,$vpresentacion,$ruc,$raz,$arp,$ard,$tip,$ndo,$asu,$ruta,$fol,$idusu,$monto){
        $c = conexionBD::conexionPDO();

        // Para el trámite externo, las áreas son siempre "EXTERNO" y "MESA DE PARTES"
        $arp = 19; // Área de origen para trámite externo
        $ard = 17; // Área de destino siempre "MESA DE PARTES"

        $sql = "CALL SP_REGISTRAR_TRAMITE_EXTERNO(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query = $c->prepare($sql);

        // Enlazar parámetros
        $query->bindParam(1, $dni);
        $query->bindParam(2, $nom);
        $query->bindParam(3, $apt);
        $query->bindParam(4, $apm);
        $query->bindParam(5, $cel);
        $query->bindParam(6, $ema);
        $query->bindParam(7, $dir);
        $query->bindParam(8, $vpresentacion);
        $query->bindParam(9, $ruc);
        $query->bindParam(10, $raz);
        $query->bindParam(11, $arp); // Usamos "EXTERNO"
        $query->bindParam(12, $ard); // Usamos "MESA DE PARTES"
        $query->bindParam(13, $tip);
        $query->bindParam(14, $ndo);
        $query->bindParam(15, $asu);
        $query->bindParam(16, $ruta);
        $query->bindParam(17, $fol);
        $query->bindParam(18, $idusu);
        $query->bindParam(19, $monto, PDO::PARAM_STR);

        // Ejecutar la consulta
        $query->execute();

        // Retorna el ID del trámite si fue exitoso
        if ($row = $query->fetchColumn()) {
            return $row;
        }

        conexionBD::cerrar_conexion();
    }

    public function Listar_Tramite_Seguimiento($id){
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_LISTAR_TRAMITE_SEGUIMIENTO(?)";
        $arreglo = array();
        $query = $c->prepare($sql);
        $query->bindParam(1, $id);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($resultado as $resp){
            $arreglo["data"][]=$resp;
        }
        return $arreglo;
        conexionBD::cerrar_conexion();
    }

    public function Obtener_Archivo_Tramite($id_tramite) {
        $c = conexionBD::conexionPDO();
        $sql = "CALL SP_OBTENER_ARCHIVO_TRAMITE(?)"; // Procedimiento almacenado
        $query = $c->prepare($sql);
        $query->bindParam(1, $id_tramite, PDO::PARAM_STR);
        $query->execute();
    
        if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            return $row['archivo']; // Devuelve solo la ruta del archivo
        }
        return null; // Si no encuentra archivo, retorna null
        conexionBD::cerrar_conexion();
    }
}

?>
