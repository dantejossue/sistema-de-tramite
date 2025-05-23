<?php
require '../../model/model_tramite.php';
$MU = new Modelo_Tramite();

$id_tramite = $_POST['id_tramite']; // Recibe el ID del trámite desde AJAX
$ruta_archivo = $MU->Obtener_Archivo_Tramite($id_tramite);

echo json_encode(['archivo' => $ruta_archivo]);
?>

