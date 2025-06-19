<?php
require '../../model/model_tramite_area.php';
$MU = new Modelo_TramiteArea();

// Obtener los parámetros de entrada
$idusuario = strtoupper(htmlspecialchars($_POST['idusuario'] ?? '', ENT_QUOTES, 'UTF-8'));  // Asegurarse que el usuario esté en mayúsculas
$estado = strtoupper(htmlspecialchars($_POST['estado'] ?? '', ENT_QUOTES, 'UTF-8')); // Filtrar por estado
$fechaini = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null; // Validar fecha inicio
$fechafin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null; // Validar fecha fin

header('Content-Type: application/json');  // Indicar que la respuesta es JSON

try {
    // Si el estado es 'todos', no aplicar filtro
    if ($estado === 'todos') {
        $estado = '';  // No filtrar por estado
    }
    
    // Siempre usar el filtro de fechas y estado
    $consulta = $MU->Listar_Tramite_Filtrado($idusuario, $estado, $fechaini, $fechafin);

    // Retornar la consulta en formato JSON
    echo json_encode(is_array($consulta) ? $consulta : ["data" => []]);
} catch (Exception $e) {
    // Si ocurre un error, retornarlo en formato JSON
    echo json_encode(["data" => [], "error" => $e->getMessage()]);
}
?>
