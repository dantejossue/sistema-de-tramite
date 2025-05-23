<?php
    require '../../model/model_usuario.php';
    $MU = new Modelo_Usuario();  //Instancia un objeto de Modelo_Usuario, lo que permite utilizar sus métodos, como Modificar_Usuario, que se encargan de interactuar con la base de datos.
    $id = strtoupper(htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8')); //Captura los datos enviados desde el frontend a través de la solicitud POST
    $idp = strtoupper(htmlspecialchars($_POST['idp'],ENT_QUOTES,'UTF-8')); 
    $ida = strtoupper(htmlspecialchars($_POST['ida'],ENT_QUOTES,'UTF-8')); 
    $rol = strtoupper(htmlspecialchars($_POST['rol'],ENT_QUOTES,'UTF-8')); 

    //Llama al método Modificar_Usuario de la clase Modelo_Usuario.
    //Le pasa los datos del usuario que se quieren modificar (ID, persona, área y rol).
    $consulta = $MU->Modificar_Usuario($id,$idp,$ida,$rol);
    echo $consulta; //Devuelve la respuesta al frontend

?>