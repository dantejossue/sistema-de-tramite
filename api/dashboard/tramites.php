<?php
// File: api/dashboard/tramites.php
require '../../model/model_conexion.php';
header('Content-Type: application/json');

try {
    $conexion = new conexionBD();
    $pdo = $conexion->conexionPDO();

    // Trámites por estado
    $stmt1 = $pdo->query("SELECT tramite_estado AS estado, COUNT(*) AS cantidad FROM tramite GROUP BY tramite_estado");
    $estados = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Trámites por área de destino
    $stmt2 = $pdo->query("SELECT a.area_nombre AS area, COUNT(*) AS cantidad FROM tramite t JOIN area a ON t.area_destino = a.area_id GROUP BY a.area_nombre");
    $areas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Montos de trámites por mes (del año actual)
    $stmt3 = $pdo->query("SELECT DATE_FORMAT(tramite_fecharegistro, '%M') AS mes, SUM(tramite_monto_pago) AS total FROM tramite WHERE YEAR(tramite_fecharegistro) = YEAR(CURDATE()) GROUP BY MONTH(tramite_fecharegistro)");
    $pagos = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // Trámites por tipo de documento
    $stmt4 = $pdo->query("SELECT td.tipodo_descripcon AS tipo, COUNT(*) AS cantidad FROM tramite t JOIN tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id GROUP BY td.tipodo_descripcon");
    $tipos = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "estados" => $estados,
        "areas" => $areas,
        "pagos" => $pagos,
        "tipos" => $tipos
    ]);

    $conexion->cerrar_conexion();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
