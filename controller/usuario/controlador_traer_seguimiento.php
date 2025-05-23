<?php
    require '../../model/model_usuario.php';
    $MU = new Modelo_Usuario();  //Instanciamos
    $numero = strtoupper(htmlspecialchars($_POST['numero'],ENT_QUOTES,'UTF-8')); 
    $dni = strtoupper(htmlspecialchars($_POST['dni'],ENT_QUOTES,'UTF-8'));
    $consulta = $MU->Cargar_select_Datos_Seguimiento($numero,$dni); //// Llama al método del modelo
    echo json_encode($consulta); // Convierte el resultado en JSON para enviarlo al AJAX del frontend.
 
?>