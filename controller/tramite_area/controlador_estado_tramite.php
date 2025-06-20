<?php
require '../../model/model_tramite_area.php';
$MU = new Modelo_TramiteArea();

$idtramite = htmlspecialchars($_POST['idtramite'], ENT_QUOTES, 'UTF-8');
$estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8');
$descripcion = strtoupper(htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8'));
$idusuario = htmlspecialchars($_POST['idusuario'], ENT_QUOTES, 'UTF-8');
$area_destino = htmlspecialchars($_POST['area_destino'], ENT_QUOTES, 'UTF-8');


$consulta = $MU->Cambiar_Estado_Tramite($idtramite, $estado, $descripcion, $idusuario, $area_destino);
echo $consulta;

?>