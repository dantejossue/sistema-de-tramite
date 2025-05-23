<?php
require '../../model/model_conexion.php';
require '../../vendor/autoload.php'; //Se carga la librería de Laravel (vendor/autoload.php) para usar Filesystem, que ayuda a manejar archivos y directorios.

use Illuminate\Filesystem\Filesystem; //Esta es una clase de Laravel para manejar archivos y directorios.Permite verificar si un directorio existe, crearlo y eliminar archivos.

$conexionClass = new conexionBD();
$conexion = $conexionClass->conexionPDO(); //Se instancia la conexión a la base de datos a través de la clase conexionBD, estableciendo $conexion como objeto PDO.

header("Content-Type: application/json"); //Se establece el encabezado JSON para devolver respuestas en este formato.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null; //Se extraen el ID de la persona y el archivo de la imagen de la solicitud.
    $foto = $_FILES['foto'] ?? null;

    if (!$id || !$foto) { //Si el ID o la foto no están presentes, se devuelve un mensaje de error y se detiene el script.
        echo json_encode(["success" => false, "message" => "ID o archivo no recibido."]);
        exit;
    }

    if ($foto['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => "Error al subir la imagen."]);
        exit;
    }

    $filesystem = new Filesystem();
    $directorio = "../../controller/persona/FOTO/";

    //Aquí se usa Laravel Filesystem para comprobar si la carpeta FOTO/ existe y, si no, la crea.
    if (!$filesystem->exists($directorio)) {
        $filesystem->makeDirectory($directorio, 0755, true);
    }

    $extensiones = ['jpg', 'jpeg', 'png'];
    $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensiones)) {
        echo json_encode(["success" => false, "message" => "Formato no permitido."]);
        exit;
    }

    // Obtener foto actual
    $consulta = $conexion->prepare("SELECT per_foto FROM persona WHERE persona_id = ?");
    $consulta->execute([$id]);
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    $foto_actual = $resultado['per_foto'] ?? '';

    if ($foto_actual && $filesystem->exists("../../" . $foto_actual) && $foto_actual !== "uploads/default.jpg") {
        $filesystem->delete("../../" . $foto_actual);
    }

    $nuevoNombre = "foto_" . $id . "." . $extension;
    $rutaCompleta = $directorio . $nuevoNombre;

    if (move_uploaded_file($foto['tmp_name'], $rutaCompleta)) {
        $rutaBD = "controller/persona/FOTO/" . $nuevoNombre;

        $update = $conexion->prepare("UPDATE persona SET per_foto = ? WHERE persona_id = ?");
        $resultado = $update->execute([$rutaBD, $id]);

        if ($resultado) {
            echo json_encode(["success" => true, "foto" => $rutaBD]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar la base de datos.", "error" => $update->errorInfo()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al mover el archivo."]);
    }
}
?>
