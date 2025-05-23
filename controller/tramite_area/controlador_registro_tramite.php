<?php
require '../../model/model_tramite_area.php';
$MU = new Modelo_TramiteArea();  //Instanciamos

$idtra = strtoupper(htmlspecialchars($_POST['idtra'], ENT_QUOTES, 'UTF-8'));
$orig = strtoupper(htmlspecialchars($_POST['orig'], ENT_QUOTES, 'UTF-8'));
$dest = strtoupper(htmlspecialchars($_POST['dest'], ENT_QUOTES, 'UTF-8'));
$desc = strtoupper(htmlspecialchars($_POST['desc'], ENT_QUOTES, 'UTF-8'));
$idusu = strtoupper(htmlspecialchars($_POST['idusu'], ENT_QUOTES, 'UTF-8'));
$nombrearchivo = strtoupper(htmlspecialchars($_POST['nombrearchivo'], ENT_QUOTES, 'UTF-8'));

if (isset($nombrearchivo)) { //nos permite ver si la variable esta vacia o no
    $ruta = "";
} else {
    $ruta = 'controller/tramite_area/documentos/' . $nombrearchivo;
}

$consulta = $MU->Registrar_Tramite($idtra, $orig, $dest, $desc, $idusu, $ruta);
echo $consulta;
if ($consulta == 1) {
    if (isset($nombrearchivo)) { //nos permite ver si la variable esta vacia o no
        if (move_uploaded_file($_FILES['archivoobj']['tmp_name'], "documentos/" . $nombrearchivo));
    }
}
