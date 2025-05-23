<?php
    require '../../model/model_usuario.php';
    $MU = new Modelo_Usuario();  //Instanciamos
    $codigo = strtoupper(htmlspecialchars($_POST['codigo'],ENT_QUOTES,'UTF-8'));
    $consulta = $MU->Traer_Datos_Detalle_Seguimiento($codigo); //// Llama al método del modelo
    echo json_encode($consulta); // Convierte el resultado en JSON para enviarlo al AJAX del frontend.
 
?>