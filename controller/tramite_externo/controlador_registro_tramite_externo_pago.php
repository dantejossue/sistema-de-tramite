<?php
    require '../../model/model_tramite_externo.php';
    $MU = new Modelo_Tramite();  //Instanciamos

    //DATOS DEL REMITENTE
    $dni = strtoupper(htmlspecialchars($_POST['dni'],ENT_QUOTES,'UTF-8'));
    $nom = strtoupper(htmlspecialchars($_POST['nom'],ENT_QUOTES,'UTF-8'));
    $apt = strtoupper(htmlspecialchars($_POST['apt'],ENT_QUOTES,'UTF-8'));
    $apm = strtoupper(htmlspecialchars($_POST['apm'],ENT_QUOTES,'UTF-8'));
    $cel = strtoupper(htmlspecialchars($_POST['cel'],ENT_QUOTES,'UTF-8'));
    $ema = strtoupper(htmlspecialchars($_POST['ema'],ENT_QUOTES,'UTF-8'));
    $dir = strtoupper(htmlspecialchars($_POST['dir'],ENT_QUOTES,'UTF-8'));
    $vpresentacion = strtoupper(htmlspecialchars($_POST['vpresentacion'],ENT_QUOTES,'UTF-8'));
    $ruc = strtoupper(htmlspecialchars($_POST['ruc'],ENT_QUOTES,'UTF-8'));
    $raz = strtoupper(htmlspecialchars($_POST['raz'],ENT_QUOTES,'UTF-8'));

    //DATOS DEL DOCUMENTO
    $arp = 'EXTERNO';
    $ard = 'MESA DE PARTES';
    $tip = strtoupper(htmlspecialchars($_POST['tip'],ENT_QUOTES,'UTF-8'));
    $ndo = strtoupper(htmlspecialchars($_POST['ndo'],ENT_QUOTES,'UTF-8'));
    $asu = strtoupper(htmlspecialchars($_POST['asu'],ENT_QUOTES,'UTF-8'));
    $nombrearchivo = strtoupper(htmlspecialchars($_POST['nombrearchivo'],ENT_QUOTES,'UTF-8'));
    $fol = strtoupper(htmlspecialchars($_POST['fol'],ENT_QUOTES,'UTF-8'));
    $idusu = ($_POST['idusu'] === 'null') ? NULL : $_POST['idusu'];
    $monto = isset($_POST['mon']) && $_POST['mon'] !== "" ? $_POST['mon'] : null;


    $ruta = 'controller/tramite_externo/documentos/'.$nombrearchivo;
    $consulta = $MU->Registrar_Tramite_Pago($dni,$nom,$apt,$apm,$cel,$ema,$dir,$vpresentacion,$ruc,$raz,$arp,$ard,$tip,$ndo,$asu,$ruta,$fol,$idusu,$monto);
    echo $consulta;
    if ($consulta !== false && strlen($consulta) > 0) {
        // Depuración: Revisar si el archivo fue enviado correctamente
        if (!isset($_FILES['archivoobj'])) {
            echo "❌ ERROR: No se recibió ningún archivo.";
            exit;
        }

        if ($_FILES['archivoobj']['error'] != 0) {
            echo "❌ ERROR al subir archivo. Código de error: " . $_FILES['archivoobj']['error'];
            exit;
        }

        // echo "✅ Archivo recibido en PHP: " . $_FILES['archivoobj']['name'];
        // echo "<br>Tamaño: " . $_FILES['archivoobj']['size'] . " bytes";
        // echo "<br>Tipo: " . $_FILES['archivoobj']['type'];
        // echo "<br>Ruta temporal: " . $_FILES['archivoobj']['tmp_name'];

        // Ruta donde se guardará el archivo
        $directorio_destino = __DIR__ . "/documentos/";

        // Crear la carpeta si no existe
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        // Ruta final del archivo
        $ruta_final = $directorio_destino . $nombrearchivo;

        // Intentar mover el archivo
        move_uploaded_file($_FILES['archivoobj']['tmp_name'], $ruta_final);
    }

?>