<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../conexion.php';

date_default_timezone_set('America/Lima');

// Datos de empresa
$empresa = $mysql->query("SELECT * FROM empresa LIMIT 1")->fetch_assoc();
$razon    = $empresa['emp_razon'];
$telefono = $empresa['emp_telefono'];
$email    = $empresa['emp_email'];
$logo     = $empresa['emp_logo'];
$ugel     = $empresa['emp_ugel'];

// Cargar logos en base64
$logoBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/logo-mixto.jpg"));
$ugelBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/$ugel"));

// Consulta de tipos de documento
$resultado = $mysql->query("SELECT * FROM tipo_documento ORDER BY tipodocumento_id ASC");

$html = '
<style>
  body {
    font-family: sans-serif;
    font-size: 11pt;
  }
  .h2 {
    font-size: 14pt;
    font-weight: bold;
    margin-bottom: 10px;
  }
  .h3 {
    font-size: 12pt;
    background: #0a4661;
    color: white;
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

<div class="h3">REPORTE DE TIPOS DE DOCUMENTO</div>
<p><b>Fecha:</b> '.date("d/m/Y").' &nbsp;&nbsp; <b>Hora:</b> '.date("H:i:s").'</p>
<p><b>Total de registros:</b> '.$resultado->num_rows.'</p>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Descripción</th>
      <th>Estado</th>
      <th>Fecha de Registro</th>
    </tr>
  </thead>
  <tbody>';

while ($row = $resultado->fetch_assoc()) {
  $color = $row['tipodo_estado'] == 'ACTIVO' ? 'green' : 'red';

  $html .= '
    <tr>
      <td>'.$row['tipodocumento_id'].'</td>
      <td>'.$row['tipodo_descripcion'].'</td>
      <td style="color:'.$color.';">'.$row['tipodo_estado'].'</td>
      <td>'.date('d/m/Y H:i:s', strtotime($row['tipodo_fregistro'])).'</td>
    </tr>';
}

$html .= '</tbody></table>';

// Generar PDF
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
$mpdf->WriteHTML($html);
$mpdf->Output("Reporte_Tipo_Documento.pdf", "I");
