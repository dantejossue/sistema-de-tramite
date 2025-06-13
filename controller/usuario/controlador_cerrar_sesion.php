<?php
    session_start(); //Es obligatorio llamar a session_start(); antes de manipular $_SESSION.
    session_destroy(); //Elimina completamente todos los datos de la sesión en el servidor. Cierra la sesión actual del usuario y borra todas las variables $_SESSION. El usuario ya no podrá acceder a páginas protegidas sin volver a iniciar sesión.
    header('Location: /index.php'); 
?>