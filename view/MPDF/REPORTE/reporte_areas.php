<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../conexion.php'; // Asegúrate de que este define $mysql correctamente

date_default_timezone_set('America/Lima');

// Validar conexión
if (!isset($mysql)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Consulta de empresa
$query = "SELECT * FROM empresa";
$resultado = $mysql->query($query);

$razon = $telefono = $email = $direccion = $logo = "";

if ($row2 = $resultado->fetch_assoc()) {
    $razon = $row2['emp_razon'];
    $telefono = $row2['emp_telefono'];
    $email = $row2['emp_email'];
    $direccion = $row2['emp_direccion'];
    $logo = $row2['emp_logo'];
    $ugel = $row2['emp_ugel'];
}

// Cargar imagen en base64 (evita errores de ruta)
$logoBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/logo-mixto.jpg"));
$ugelBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/$ugel"));

$consulta = $mysql->query("SELECT * FROM area");

$html = '
<style>
  body { font-family: sans-serif; font-size: 11pt; margin: 0 10pt; }
  .h2 { font-size: 14pt; font-weight: 600; }
  .h3 { font-size: 13pt; background: #0a4661; color: white; text-align: center; padding: 5px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { padding: 6px; border: 1px solid #ccc; text-align: center; }
  .logo { width: 70px; height: 70px; }
</style>

<table>
  <tr>
    <td width="20%"><img src="data:image/jpg;base64,' . $logoBase64 . '" class="logo" /></td>
    <td width="60%" align="center">
      <div class="h2"><b>' . $razon . '</b></div>
      <p><b>Dirección:</b> ' . $direccion . '</p>
      <p><b>Teléfono:</b> ' . $telefono . '</p>
      <p><b>Email:</b> ' . $email . '</p>
    </td>
    <td width="20%"><img src="data:image/jpg;base64,' . $ugelBase64 . '" class="logo" /></td>
  </tr>
</table>

<br>
<div class="h3">REPORTE DE ÁREAS</div>
<p><b>Fecha:</b> ' . date("d/m/Y") . ' &nbsp;&nbsp;&nbsp; <b>Hora:</b> ' . date("H:i:s") . '</p>
<p><b>Total de registros:</b> ' . $consulta->num_rows . '</p>
<br>
<table>
  <thead>
    <tr><th>ID</th><th>ÁREA</th><th>ESTADO</th><th>FECHA REGISTRO</th></tr>
  </thead>
  <tbody>';

while ($row = $consulta->fetch_assoc()) {
    $html .= '
    <tr>
      <td>' . $row['area_id'] . '</td>
      <td>' . $row['area_nombre'] . '</td>
      <td>' . $row['area_estado'] . '</td>
      <td>' . $row['area_fecha_registro'] . '</td>
    </tr>';
}

$html .= '</tbody></table>';


$mpdf = new \Mpdf\Mpdf(['mode' => 'UTF-8', 'format' => 'A4']);
$mpdf->WriteHTML($html);
$mpdf->Output("Reporte_Areas.pdf", "I");
