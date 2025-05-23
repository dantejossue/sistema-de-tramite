<?php
    require '../../model/model_tramite_area.php';
    $MU = new Modelo_TramiteArea();  //Instanciamos
    $idusuario = strtoupper(htmlspecialchars($_POST['idusuario'],ENT_QUOTES,'UTF-8')); 
    $consulta = $MU->Listar_Tramite($idusuario);
    if($consulta){
        echo json_encode($consulta);
    }else{
        echo '{"sEcho":1,
               "iTotalRecords": "0",
               "iTotalDisplayRecords": "0",
               "aaData": [ ]
              }';
    }
?>