<?php
    require '../../model/model_persona.php';
    $MU = new Modelo_Persona();  //Instanciamos
    $consulta = $MU->Listar_Persona();
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