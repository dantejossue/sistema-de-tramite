<?php
    require '../../model/model_usuario.php';
    
    $MU = new Modelo_Usuario();  //Instanciamos
    $consulta = $MU->Cargar_Widget(); //// Llama al método del modelo
    echo json_encode($consulta); // Convierte el resultado en JSON para enviarlo al AJAX del frontend.
 
?>