<?php
    require '../../model/model_tipo.php';
    $MU = new Modelo_Tipo();  //Instanciamos
    $tipo = strtoupper(htmlspecialchars($_POST['tipo'],ENT_QUOTES,'UTF-8')); 
    $consulta = $MU->Registrar_Tipo($tipo);
    echo $consulta;

?>