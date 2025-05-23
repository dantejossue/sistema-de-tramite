<?php
    require '../../model/model_usuario.php';
    $MU = new Modelo_Usuario();  //Instanciamos
    $id = strtoupper(htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8')); 
    $estatus = strtoupper(htmlspecialchars($_POST['estatus'],ENT_QUOTES,'UTF-8')); 
    $consulta = $MU->Modificar_Usuario_Estatus($id,$estatus);
    echo $consulta;

?>