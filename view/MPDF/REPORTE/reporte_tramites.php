<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../conexion.php'; // Aquí debes tener $mysql definido como mysqli

date_default_timezone_set('America/Lima');

// Datos de empresa
$empresa = $mysql->query("SELECT * FROM empresa LIMIT 1")->fetch_assoc();
$razon = $empresa['emp_razon'];
$telefono = $empresa['emp_telefono'];
$email = $empresa['emp_email'];
$logo = $empresa['emp_logo'];
$ugel = $empresa['emp_ugel'];

// Cargar imagen en base64 (evita errores de ruta)
$logoBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/logo-mixto.jpg"));
$ugelBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/$ugel"));

// Consulta de trámites
$query = "
SELECT 
  t.tramite_id,
  t.tramite_nrodocumento,
  td.tipodo_descripcion,
  t.tramite_fecharegistro,
  t.tramite_asunto,
  ao.area_nombre AS area_origen,
  ad.area_nombre AS area_destino,
  t.tramite_estado,
  CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS remitente,
  t.remitente_dni,
  t.remitente_celular,
  t.remitente_email
FROM tramite t
LEFT JOIN tipo_documento td ON td.tipodocumento_id = t.tipodocumento_id
LEFT JOIN area ao ON ao.area_id = t.area_origen
LEFT JOIN area ad ON ad.area_id = t.area_destino
ORDER BY t.tramite_fecharegistro DESC
";

$resultado = $mysql->query($query);

$html = '
<style>
  body {
    font-family: sans-serif;
    font-size: 10pt;
  }
  .h2 {
    font-size: 14pt;
    font-weight: bold;
    margin-bottom: 10px;
  }
  .h3 {
    font-size: 12pt;
    background: #0a4661;
    color: #fff;
    text-align: center;
    padding: 6px;
    margin: 10px 0;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 6px;
    border: 1px solid #ccc;
    font-size: 9pt;
    text-align: center;
  }
  .logo {
    width: 70px;
    height: 70px;
  }
</style>

<table>
  <tr>
    <td width="20%"><img src="data:image/jpg;base64,' . $logoBase64 . '" class="logo" /></td>
    <td width="60%" align="center">
      <div class="h2">'.$razon.'</div>
      <p><b>Teléfono:</b> '.$telefono.'</p>
      <p><b>Email:</b> '.$email.'</p>
    </td>
    <td width="20%"><img src="data:image/jpg;base64,' . $ugelBase64 . '" class="logo" /></td>
  </tr>
</table>

<div class="h3">REPORTE DE TRÁMITES</div>
<p><b>Fecha:</b> '.date("d/m/Y").' &nbsp;&nbsp; <b>Hora:</b> '.date("H:i:s").'</p>
<p><b>Total de registros:</b> '.$resultado->num_rows.'</p>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nro Doc</th>
      <th>Tipo</th>
      <th>Fecha</th>
      <th>Asunto</th>
      <th>Área Origen</th>
      <th>Área Destino</th>
      <th>Estado</th>
      <th>Remitente</th>
      <th>DNI</th>
      <th>Celular</th>
    </tr>
  </thead>
  <tbody>';

while ($row = $resultado->fetch_assoc()) {
  $html .= '
    <tr>
      <td>'.$row['tramite_id'].'</td>
      <td>'.$row['tramite_nrodocumento'].'</td>
      <td>'.$row['tipodo_descripcion'].'</td>
      <td>'.date('d/m/Y', strtotime($row['tramite_fecharegistro'])).'</td>
      <td>'.$row['tramite_asunto'].'</td>
      <td>'.$row['area_origen'].'</td>
      <td>'.$row['area_destino'].'</td>
      <td>'.$row['tramite_estado'].'</td>
      <td>'.$row['remitente'].'</td>
      <td>'.$row['remitente_dni'].'</td>
      <td>'.$row['remitente_celular'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

// Generar PDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']); // Horizontal (Landscape)
$mpdf->WriteHTML($html);
$mpdf->Output("Reporte_Tramites.pdf", "I");
