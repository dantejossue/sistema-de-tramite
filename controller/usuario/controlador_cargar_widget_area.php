<?php
session_start(); // ¡Importante! Necesario para acceder a $_SESSION

require '../../model/model_usuario.php';

$MU = new Modelo_Usuario();  // Instancia del modelo

// Validamos si el ID del usuario está disponible
if (isset($_SESSION['S_ID'])) {
    $idusuario = $_SESSION['S_ID'];  // Obtenemos el ID del usuario desde la sesión

    $consulta = $MU->Cargar_Widget_Por_Area($idusuario); // Llamamos al procedimiento

    echo json_encode($consulta); // Enviamos la respuesta en formato JSON al frontend
} else {
    echo json_encode(["error" => "No se encontró la sesión de usuario."]);
}
?>
