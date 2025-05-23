<?php
    require '../../model/model_tipo.php';
    $MU = new Modelo_Tipo();  //Instanciamos
    $consulta = $MU->Listar_Tipo();
    if($consulta){
        echo json_encode($consulta);
    }else{
        echo '{"sEcho":1,
               "iTotalRecords": "0",
               "iTotalDisplayRecords": "0"
               "aaData": [ ]
              }';
    }

?>