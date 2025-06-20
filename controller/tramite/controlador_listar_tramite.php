<?php
require '../../model/model_tramite.php';
$MU = new Modelo_Tramite();

$fechaini = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fechafin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
$estado = $_POST['estado'] ?? '';
$area = isset($_POST['area']) && $_POST['area'] !== '' ? intval($_POST['area']) : 0;

header('Content-Type: application/json');

try {
    if ($fechaini != '' || $fechafin != '' || $estado != '' || $area != 0) {
        $consulta = $MU->Listar_Tramite_Filtrado($fechaini, $fechafin, $estado, $area);
    } else {
        $consulta = $MU->Listar_Tramite();
    }

    echo json_encode(is_array($consulta) ? $consulta : ["data" => []]);
} catch (Exception $e) {
    echo json_encode(["data" => [], "error" => $e->getMessage()]);
}
?>
