<?php 
    session_start(); //Iniciar sesion en PHP o recupera una sesión existente si ya está iniciada.

    //REcibir los datos enviados desde el frontend(AJAX) 
    //Se capturan los datos enviados desde console_usuario.js usando $_POST.
    $idusuario = htmlspecialchars($_POST['idusuario'],ENT_QUOTES,'UTF-8');  //Contiene el ID del usuario autenticado.
    $usuario = htmlspecialchars($_POST['usu'],ENT_QUOTES,'UTF-8'); //Contiene el nombre del usuario autenticado.
    $rol = htmlspecialchars($_POST['rol'],ENT_QUOTES,'UTF-8'); //Contiene el rol del usuario (Ejemplo: Secretario(a), Administrador).
    $area_id = htmlspecialchars($_POST['area'],ENT_QUOTES,'UTF-8'); 
    $area_nombre = htmlspecialchars($_POST['area_nombre'], ENT_QUOTES, 'UTF-8'); // Ahora recibimos el nombre del área
    $usu_persona = htmlspecialchars($_POST['usu_persona'], ENT_QUOTES, 'UTF-8'); // Nombre del usuario autenticado

    //Se guardan los datos del usuario autenticado en variables de sesión ($_SESSION).
    //Estas variables estarán disponibles en todas las páginas del sistema mientras el usuario no cierre sesión.
    $_SESSION['S_ID']=$idusuario;
    $_SESSION['S_USU']=$usuario;
    $_SESSION['S_ROL']=$rol;
    $_SESSION['S_AREA_ID'] = $area_id;
    $_SESSION['S_AREA_NOMBRE'] = $area_nombre; // Guardamos el nombre del área en sesión
    $_SESSION['S_USU_NOMBRE']=$usu_persona;

?>