<?php
    require '../../model/model_tipo.php';
    $MU = new Modelo_Tipo();  //Instanciamos
    $id= strtoupper(htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8')); 
    $tipo = strtoupper(htmlspecialchars($_POST['tipo'],ENT_QUOTES,'UTF-8')); 
    $esta = strtoupper(htmlspecialchars($_POST['esta'],ENT_QUOTES,'UTF-8')); 
    $consulta = $MU->Modificar_Tipo($id,$tipo,$esta);
    echo $consulta;

?>