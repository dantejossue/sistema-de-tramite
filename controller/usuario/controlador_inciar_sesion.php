<?php

require '../../model/model_usuario.php'; //importamos el modelo

$MU = new Modelo_Usuario(); //Instanciamos el modelo usuario

//Capturamos los datos enviados por el formulario usando POST
$usu = htmlspecialchars($_POST['u'], ENT_QUOTES, 'UTF-8'); //Captura el usuario enviado por el formulario, y lo limpia de caracteres especiales(UTF-8)
$con = htmlspecialchars($_POST['c'], ENT_QUOTES, 'UTF-8'); //Captura la contraseña enviada por el formulario, y lo limpia de caracteres especiales(UTF-8)

//llamamos a la función que verifica si el usuario existe
$consulta = $MU->Verificar_Usuario($usu, $con); //Llama a la función en model_usuario.php. Busca el usuario en la base de datos y devuelve los datos si existe.

//Si la consulta retorna un valor, significa que el usuario existe y enviamos los datos en formato JSON
if(count($consulta) > 0){
    echo json_encode($consulta);
}else{
    echo 0; // Si el usuario no existe o la contraseña es incorrecta
}

?>