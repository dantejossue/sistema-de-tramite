<?php
    require '../../model/model_tramite.php';
    $MU = new Modelo_Tramite();  //Instanciamos
    $id = strtoupper(htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8')); 
    $consulta = $MU->Listar_Tramite_Seguimiento($id);
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