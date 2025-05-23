<?php
    require '../../model/model_usuario.php';
    $MU = new Modelo_Usuario();  //Instanciamos
    //capturando los datos enviados desde el js mediante un formulario, Recibe los datos enviados por POST y los limpia antes de guardarlos en la base de datos.
    $usu = strtoupper(htmlspecialchars($_POST['usu'],ENT_QUOTES,'UTF-8'));  //Se convierte a mayúsculas con strtoupper() y se limpia con htmlspecialchars() para evitar ataques XSS.
    $con = password_hash((htmlspecialchars($_POST['con'],ENT_QUOTES,'UTF-8')),PASSWORD_DEFAULT,['cost'=>12]); //Se limpia y se encripta con password_hash() antes de guardarla.
    $idp = strtoupper(htmlspecialchars($_POST['idp'],ENT_QUOTES,'UTF-8')); 
    $ida = strtoupper(htmlspecialchars($_POST['ida'],ENT_QUOTES,'UTF-8')); 
    $rol = strtoupper(htmlspecialchars($_POST['rol'],ENT_QUOTES,'UTF-8')); 
    $consulta = $MU->Registrar_Usuario($usu,$con,$idp,$ida,$rol); //Llama al método Registrar_Usuario() en model_usuario.php, enviando los datos capturados. Se encarga de insertar los datos en la base de datos mediante un procedimiento almacenado.
    
    echo $consulta;

?>