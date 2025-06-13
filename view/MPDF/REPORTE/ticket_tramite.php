<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../conexion.php';

//Creando consulta 
$codigo=$_GET['codigo'];
$query = "SELECT `empresa`.`empresa_id`, `empresa`.`emp_razon`, `empresa`.`emp_email`, `empresa`.`emp_cod`, `empresa`.`emp_telefono`, `empresa`.`emp_direccion`, `empresa`.`emp_logo`
FROM `empresa`";
date_default_timezone_set('America/Lima');
$html = "";
$resultado = $mysql->query($query);
$query2 = "SELECT t1.tramite_id , t1.remitente_dni, CONCAT_WS(' ',t1.remitente_nombre, t1.remitente_apepat, t1.remitente_apemat) AS remitente, t1.tramite_nrodocumento, td.tipodo_descripcion, t1.tramite_fecharegistro FROM tramite t1 LEFT JOIN tipo_documento td  ON td.tipodocumento_id  = t1.tipodocumento_id  where t1.tramite_id = '".$codigo."'";
$resultado2 = $mysql->query($query2);
$razon = "";
$telefono = "";
$email = "";
$codigo = "";
$logo = "";
while($row2 = $resultado->fetch_assoc()){
    $razon = $row2['emp_razon'];
    $telefono = $row2['emp_telefono'];
    $email = $row2['emp_email'];
    $codigo = $row2['emp_cod'];
    $logo = $row2['emp_logo'];
}
while($row=$resultado2->fetch_assoc()){
    $html.='
    <style>
        @page {
            margin: 10mm;
            margin-header: 0mm;
            margin-footer: 0mm;
            odd-footer-name: html_myFooter1;
        }
    </style>    
    <table width="100%">
        <tr>
            <td align = "center">
                <img width="30%" align="center" src="../../../assets/img/logo-mixto.jpg">
            </td>
        </tr>
    </table>     
    <span style="font-size:12px"><b><br>Número de Expediente:
        </b> '.$row['tramite_id'].'
    </span><br>
    <span style="font-size:12px"><b><br>Fecha - Hora:
        </b> '.date('d/m/Y H:i:s', strtotime($row['tramite_fecharegistro'])).'
    </span><br>
    <span style="font-size:12px"><b><br>Tipo:
        </b> '.$row['tipodo_descripcion'].'
    </span><br>
    <span style="font-size:12px"><b><br>DNI:
        </b> '.$row['remitente_dni'].'
    </span><br>
    <span style="font-size:12px"><b><br>REMITENTE:
        </b> '.$row['remitente'].'
    </span><br>
    <table width="100%" cellpadding="8">
        <tr>
            <td class="barcodecell" align="center">
                <barcode code="'.$row['tramite_id'].'" type="QR" class="barcode" size="1.0" disableborder="1"/>
            </td>
        </tr>
    </table> 

    <htmlpagefooter name="myFooter1"> 
    <table width="100%">
        <tr>
            <td with="50%">
                '.$telefono.'
            </td>
            <td with="50%">
                '.$email.'
            </td>
        </tr>
    </table> 
    </htmlpagefooter>         
    ';
}

// Crear una instancia de mPDF
$mpdf = new \Mpdf\Mpdf(
    ['mode' => 'UTF-8', 'format' => [80,130]]
);
$mpdf->WriteHTML($html);
$mpdf->Output();
?>