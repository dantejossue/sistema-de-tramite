<?php


session_start(); //Esta función inicia una sesión o recupera una sesión existente.

if (isset($_SESSION['S_ID'])) {  //Comprueba si la variable de sesión S_ID existe. Si la sesión está activa, significa que el usuario ya inició sesión.
    header('Location: view/index.php'); // Entonces, lo redirige al dashboard o panel administrativo (view/index.php)
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso al Sistema</title>
    <link rel="icon" href="assets/img/logo mixto.jpg">
    <link rel="stylesheet" href="assets/css/login.css">

</head>

<body>
    <div class="formulario">
        <a href="index.php"><img class="logo" src="assets/img/logo mixto.jpg" alt="Logo Colegio"></a>
        <h1>Inicio de Sesión</h1>
        <h3 class="h3-titulo">Sistema de Trámite Documentario</h3>
        <div class="form">
            <div class="usuario">
                <label>Usuario:</label>
                <input id="txt_usuario" type="text">
            </div>
            <div class="contrasena">
                <label>Contraseña:</label>
                <input id="txt_contra" type="password">
            </div>
            <button class="btn_ingresar" type="submit" onclick="iniciar_sesion()">Ingresar</button><br><br>
            <!-- <div class="recordar"><a href="RecuperarContrasena">¿Olvidó su contraseña?</a></div>-->
            <a href="registrar_tramite.php" class="btn_mesa_partes">
                <img class="img-hover" src="https://www.munijesusmaria.gob.pe/wp-content/uploads/2020/07/mesadepartes-imagen-1.jpg" alt="Logo mesa de partes virtual">
            </a>
        </div>
    </div>


    <!-- Agrega un parametro rev en valor de la funcion time() en php, para generar un url unica para cada carga de la pagina(evitar cache del navegador)-->
    <script src="js/console_usuario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="plantilla/plugins/jquery/jquery.min.js"></script>
</body>

</html>