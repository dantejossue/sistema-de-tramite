<?php
session_start();
include '../../model/model_conexion.php';
$conexionBD = new conexionBD();
$conexion = $conexionBD->conexionPDO();

$area_id = $_SESSION['S_AREA_ID'];

$sql = "SELECT COUNT(*) AS nuevos, 
               MAX(tramite_id) AS ultimo_id 
        FROM tramite 
        WHERE tramite_estado = 'PENDIENTE' 
        AND area_destino = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([$area_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
  'nuevos' => $row['nuevos'],
  'ultimo_id' => $row['ultimo_id'],
  'area_id' => $area_id
]);
?>
