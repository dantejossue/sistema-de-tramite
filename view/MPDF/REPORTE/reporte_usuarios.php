<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../conexion.php';

date_default_timezone_set('America/Lima');

// Empresa
$empresa = $mysql->query("SELECT * FROM empresa LIMIT 1")->fetch_assoc();
$razon   = $empresa['emp_razon'];
$telefono = $empresa['emp_telefono'];
$email    = $empresa['emp_email'];
$logo     = $empresa['emp_logo'];
$ugel     = $empresa['emp_ugel'];

$logoBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/logo-mixto.jpg"));
$ugelBase64 = base64_encode(file_get_contents(__DIR__ . "/../../../assets/img/$ugel"));

// Usuarios
$usuarios = $mysql->query("
  SELECT u.usu_id , u.usu_usuario, u.usu_rol, a.area_nombre, CONCAT_WS(' ',p.per_nombre,p.per_apepat,per_apemat) as nombre, u.usu_estado
  FROM usuario u 
  LEFT JOIN area a ON u.area_id = a.area_id
  LEFT JOIN persona p ON u.persona_id = p.persona_id
");

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

<div class="h3">REPORTE DE USUARIOS</div>
<p><b>Fecha:</b> '.date("d/m/Y").' &nbsp;&nbsp; <b>Hora:</b> '.date("H:i:s").'</p>
<p><b>Total de registros:</b> '.$usuarios->num_rows.'</p>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Usuario</th>
      <th>Rol</th>
      <th>Área</th>
      <th>Nombre</th>
      <th>Estado</th>
    </tr>
  </thead>
  <tbody>';

while ($row = $usuarios->fetch_assoc()) {
  $estadoColor = $row['usu_estado'] == 'ACTIVO' ? 'green' : 'red';
  $html .= '
    <tr>
      <td>'.$row['usu_id'].'</td>
      <td>'.$row['usu_usuario'].'</td>
      <td>'.$row['usu_rol'].'</td>
      <td>'.$row['area_nombre'].'</td>
      <td>'.$row['nombre'].'</td>
      <td><span style="color:'.$estadoColor.';">'.$row['usu_estado'].'</span></td>
    </tr>';
}

$html .= '</tbody></table>';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
$mpdf->WriteHTML($html);
$mpdf->Output("Reporte_Usuarios.pdf", "I");
