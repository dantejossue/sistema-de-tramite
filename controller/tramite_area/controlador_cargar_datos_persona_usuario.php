<?php
require '../../model/model_conexion.php';

$id = $_POST['idusuario'];
$conexion = new conexionBD();               // Crear instancia de la clase
$pdo = $conexion->conexionPDO();           // Obtener conexión PDO

$sql = "SELECT p.per_nrodocumento, p.per_nombre, p.per_apepat, p.per_apemat, p.per_movil, p.per_email, p.per_direccion,
               u.area_id, a.area_nombre
        FROM usuario u
        INNER JOIN persona p ON u.persona_id = p.persona_id
        INNER JOIN area a ON a.area_id = u.area_id
        WHERE u.usu_id = ?";

$consulta = $pdo->prepare($sql);
$consulta->execute([$id]);
$data = $consulta->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
