<?php
require '../../model/model_tramite_area.php';
$MU = new Modelo_TramiteArea();

// Obtener los parámetros de entrada
$idusuario = strtoupper(htmlspecialchars($_POST['idusuario'] ?? '', ENT_QUOTES, 'UTF-8'));
$fechaini = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fechafin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

$estado = 'PENDIENTE'; // <--- SIEMPRE filtra por PENDIENTE

header('Content-Type: application/json');

try {
    // Solo trámites PENDIENTES
    $consulta = $MU->Listar_Tramite_Derivados($idusuario, $estado, $fechaini, $fechafin);

    if (is_array($consulta) && isset($consulta['data'])) {
        echo json_encode($consulta);
    } else {
        echo json_encode(["data" => []]);
    }
} catch (Exception $e) {
    echo json_encode(["data" => [], "error" => $e->getMessage()]);
}
?>
