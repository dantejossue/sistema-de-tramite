function iniciar_sesion() {
  //Capturando los valores de los inputs
  let usu = document.getElementById("txt_usuario").value;
  let con = document.getElementById("txt_contra").value;

  //Validando que los campos no estén vacíos
  if (usu.length == 0 || con.length == 0) {
    return Swal.fire({
      icon: "warning",
      title: "Mensaje de Advertencia",
      text: "Llene los campos de la sesión",
      heightAuto: false,
    });
  }

  //Enviando los datos al backend(controlador) con AJAX
  //---------------------------------------------------
  $.ajax({
    //Usa AJAX de jQuery para enviar los datos sin recargar la página
    url: "controller/usuario/controlador_inciar_sesion.php",
    type: "POST", //Indica que la solicitud se enviará como un formulario POST
    data: {
      //Envía al backend los datos como un objeto JSON (usu y con son las credenciales ingresadas).
      u: usu,
      c: con,
    },

    //PROCESAR LA REPSUESTA DEL SERVIDOR
    //Ejecuta esta funcion cuando el servidor responde
  }).done(function (resp) {
    let data = JSON.parse(resp); //Convierte la respuesta del servidor en un objeto JSON
    if (data.length > 0) {
      //Si el servidor devuelve datos, significa que el usuario existe y la contraseña es correcta
      if (data[0].usu_estado == "INACTIVO") {
        //Accede al índice 7 del arreglo, que contiene el estado del usuario
        return Swal.fire({
          icon: "warning",
          title: "Advertencia",
          text:
            "El usuario " + usu + " está INACTIVO, contacte al administrador",
          heightAuto: false,
        });
      }

      $.ajax({
        //hace otra peticion AJAX a controlador_Crear_sesion.php para guardar los datos en $_SESSION
        url: "controller/usuario/controlador_crear_sesion.php",
        type: "POST",
        data: {
          idusuario: data[0][0],
          usu: data[0][1],
          rol: data[0].usu_rol,
          area: data[0].area_id,
          area_nombre: data[0].area_nombre,
          usu_persona: data[0].usu_persona, 
        },
      }).done(function (r) {
        //Metodo de jQuery AJAX que se ejecuta cuando la solicitud AJAX fue exitosa, la funcion recibe la respuesta del servidor y la procesa -> r(respuesta del controlador_crear_sesion.php)
        let timerInterval;
        Swal.fire({
          title: "Bienvenido al Sistema!",
          html: "Serás redireccionado en <b></b> milliseconds.",
          timer: 2000,
          timerProgressBar: true,
          heightAuto: false,
          didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
              timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
          },
          willClose: () => {
            clearInterval(timerInterval);
          },
        }).then((result) => {
          /* Read more about handling dismissals below */
          if (result.dismiss === Swal.DismissReason.timer) {
            location.reload();
          }
        });
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Usuario o contraseña incorrectos",
        heightAuto: false,
      });
    }
  });
}

function salir() {
  window.location =
    "/controller/usuario/controlador_cerrar_sesion.php"; //pagina donde tienes tus consultas para borrar
}

var tbl_usuario; // lo declaramos como una variable global
function listar_usuario() {
  tbl_usuario = $("#tabla_usuario").DataTable({
    //Aquí estamos inicializando DataTables en la tabla con ID tabla_usuario.
    "ordering": false, //Desactiva el ordenamiento de columnas.
    "bLengthChange": true, //Permite cambiar el número de registros a mostrar por página.
    "searching": { "regex": false }, //Habilita la barra de búsqueda sin expresiones regulares avanzadas.
    "lengthMenu": [
      //Define cuántos registros se pueden mostrar por página (10, 25, 50, 100 o todos).
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ],
    "pageLength": 10, // Por defecto, muestra 10 registros por página.
    "destroy": true, //Si la tabla ya existe, la destruye antes de volver a cargarla (evita duplicados).
    "async": false, //Fuerza que la carga de datos sea síncrona (espera antes de ejecutar más código).
    "processing": true, //Muestra un indicador de "Cargando..." mientras se obtienen los datos.
    "ajax": {
      "url": "../controller/usuario/controlador_listar_usuario.php", //para obtener los datos de los usuarios desde el backend.
      type: "POST",
    },
    "columns":[
      {"defaultContent":""}, //Columna para numeración (contador)
      {"data":"usu_usuario" }, //Muestra el valor del campo usu_usuario que viene del JSON.
      {"data":"area_nombre" },
      {"data":"usu_rol" },
      {"data":"npersona" },
      {"data":"usu_estado", //Se obtiene usu_estado de la base de datos.
        render:function(data, type, row) {
          //Se usa render para transformar visualmente algunos datos, especialmente estados y botones
          if (data == 'ACTIVO'){
            return '<span class="badge bg-success">ACTIVO</span>'
          } else {
            return '<span class="badge bg-danger">INACTIVO</span>'
          }
        }
      },
      {
        "data":"usu_estado",
        render:function(data, type, row) {
          //se unsa render para formatear el estado
          if (data == 'ACTIVO') {
            return "<button class='editar btn btn-primary btn-sm'><i class='fa fa-edit'></i></button>&nbsp;\
            <button class='contra btn btn-warning btn-sm'><i class='fa fa-key'></i></button>&nbsp;\
            <button class=' btn btn-success btn-sm' disabled><i class='fa fa-check'></i></button>&nbsp;\
            <button class='desactivar btn btn-danger btn-sm'><i class='fa fa-times-circle'></i></button>"; //habilitado para desactivarlo
          }else{
            return "<button class='editar btn btn-primary btn-sm'><i class='fa fa-edit'></i></button>&nbsp;\
            <button class='contra btn btn-warning btn-sm'><i class='fa fa-key'></i></button>&nbsp;\
            <button class='activar btn btn-success btn-sm'><i class='fa fa-check'></i></button>&nbsp;\
            <button class='btn btn-danger btn-sm' disabled><i class='fa fa-times-circle'></i></button>"; //  Deshabilitado porque ya está inactivo.
          }
        }
      }
    ],

    language: idioma_espanol,
    select: true,
  });
  //contador
  tbl_usuario.on("draw.td", function () {
    //Cada vez que la tabla se dibuja (draw.td), se numeran las filas automáticamente.
    var PageInfo = $("#tabla_usuario").DataTable().page.info(); //Se obtiene la PageInfo para calcular el número correcto en cada página.
    tbl_usuario
      .column(0, { page: "current" })
      .nodes()
      .each(function (cell, i) {
        cell.innerHTML = i + 1 + PageInfo.start;
      }); // Se asigna un número a cada fila en la primera columna (column(0)).
  });  
}

//MODAL - EDITAR USUARIO
$('#tabla_usuario').on('click','.editar',function(){
  var data = tbl_usuario.row($(this).parents('tr')).data();//En tamaño escritorio
  if(tbl_usuario.row(this).child.isShown()){
      var data = tbl_usuario.row(this).data();
  }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
  $("#modal_editar").modal('show');
  console.log(data);
  document.getElementById('txt_idusuario').value=data.usu_id;
  document.getElementById('txt_usu_editar').value=data.usu_usuario;
  $("#select_persona_editar").select2().val(data.persona_id).trigger('change.select2');
  $("#select_area_editar").select2().val(data.area_id).trigger('change.select2');
  $("#select_rol_editar").select2().val(data.usu_rol).trigger('change.select2');
})

//MODAL - EDITAR CONTRASEÑA USUARIO
$('#tabla_usuario').on('click','.contra',function(){
  var data = tbl_usuario.row($(this).parents('tr')).data();//En tamaño escritorio
  if(tbl_usuario.row(this).child.isShown()){
      var data = tbl_usuario.row(this).data();
  }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
  $("#modal_contra").modal('show');
  document.getElementById('txt_idusuario_contra').value=data.usu_id;
  document.getElementById('txt_usuario_contra').value=data.usu_contra;
})

//MODAL - ACTIVAR USUARIO
$('#tabla_usuario').on('click','.activar',function(){
  var data = tbl_usuario.row($(this).parents('tr')).data();//En tamaño escritorio
  if(tbl_usuario.row(this).child.isShown()){
      var data = tbl_usuario.row(this).data();
  }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
  Swal.fire({
      title: '¿Desea activar al usuario '+data.usu_usuario+'?',
      text: "Una vez activado el usuario tendrá acceso al sistema!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí"
    }).then((result) => {
      if (result.isConfirmed) {
          Modificar_Estatus_Usuario(parseInt(data.usu_id),'ACTIVO',data.usu_usuario);
      }
    });
})

//MODAL - DESACTIVAR USUARIO
$('#tabla_usuario').on('click','.desactivar',function(){
  var data = tbl_usuario.row($(this).parents('tr')).data();//En tamaño escritorio
  if(tbl_usuario.row(this).child.isShown()){
      var data = tbl_usuario.row(this).data();
  }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
  Swal.fire({
      title: '¿Desea desactivar al usuario '+data.usu_usuario+'?',
      text: "Una vez desactivado el usuario no tendrá acceso al sistema!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí"
    }).then((result) => {
      if (result.isConfirmed) {
          Modificar_Estatus_Usuario(parseInt(data.usu_id),'INACTIVO',data.usu_usuario);
      }
    });
  
})

//MODAL - REGISTRO USUARIO
function AbrirRegistro(){
  $("#modal_registro").modal({backdrop:'static',Keyboard:false})
  $('#modal_registro').modal('show');
}

//funcion para registrar usuario
function Registrar_Usuario(){
  let usu = document.getElementById('txt_usu').value;
  let con = document.getElementById('txt_con').value;
  let idp = document.getElementById('select_persona').value;
  let ida = document.getElementById('select_area').value;
  let rol = document.getElementById('select_rol').value;
  if(usu.length==0 || con.length==0 || idp.length==0 || ida.length==0 || rol.length==0){
      return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
  }

  $.ajax({ //enviar datos mediante el ajax, se usa jQuery para enviar los datos al controller, Los datos capturados se envían como un objeto (data).
      "url":"../controller/usuario/controlador_registro_usuario.php",
      type:'POST',
      data:{
          usu:usu,
          con:con,
          idp:idp,
          ida:ida,
          rol:rol       
      }
  
  //manejo de la respuesta del servidor
  }).done(function(resp){  //Cuando el backend responde, ejecuta esta función para manejar la respuesta.
      if(resp>0){
          if(resp==1){ //Si resp == 1 significa que el usuario se registró correctamente
              Swal.fire("Mensaje de Confirmación","Nuevo Usuario registrado","success").then((value)=>{
                  document.getElementById('txt_usu').value=""; //Limpia los campos del formulario.
                  document.getElementById('txt_con').value="";
                  document.getElementById('select_persona').value="";
                  document.getElementById('select_area').value="";
                  document.getElementById('select_rol').value="";
                  tbl_usuario.ajax.reload(); //Recarga la tabla de usuarios
                  $("#modal_registro").modal('hide');
              });
          }else if(resp == 2) {
            Swal.fire("Mensaje de Advertencia", "El Usuario ingresado ya existe", "warning").then((value)=>{
              document.getElementById('txt_usu').value=""; //Limpia los campos del formulario.
              document.getElementById('txt_con').value="";
            });
          }else if(resp == 3) {
            Swal.fire("Mensaje de Advertencia", "Esta persona ya tiene un usuario registrado", "warning").then((value)=>{
              document.getElementById('txt_usu').value=""; //Limpia los campos del formulario.
              document.getElementById('txt_con').value="";
            });
          }
      }else{
          return Swal.fire("Mensaje de Error","No se completó el registro","error");
      }
  })
}

//cargar los selects
function Cargar_select_Persona(){
  $.ajax({ //Realizar la Petición AJAX para Obtener los Datos
      "url":"../controller/usuario/controlador_cargar_select_persona.php", //Especifica la URL del controlador que obtendrá los datos desde la base de datos.
      type:'POST', //Se usa POST en lugar de GET porque, aunque no enviamos datos en la solicitud, es una buena práctica evitar exponer parámetros en la URL.
  }).done(function(resp){  //Cuando la solicitud AJAX se completa, el backend devuelve una respuesta en formato JSON.
      let data = JSON.parse(resp); //Convierte la respuesta JSON del servidor en un objeto de JavaScript.
      if(data.length>0){ //Verifica si la respuesta contiene registros.
          let cadena = ""; // Si hay datos en data, se genera una cadena de opciones <option> para el select.
          for (let i = 0; i < data.length; i++) { 
              cadena+="<option value='"+data[i][0]+"'>"+data[i][1]+"</option>"; //data[i][0] → ID de la persona (valor del option). data[i][1] → Nombre de la persona (texto visible en el select).
          }
          //Insertar los Datos en los select del Modal
          document.getElementById('select_persona').innerHTML=cadena; //Llena el select en el formulario de registro de usuario.
          document.getElementById('select_persona_editar').innerHTML=cadena; //Llena el select en el formulario de edicion de usuario.
      }else{
          cadena+="<option value=''>No hay personas disponibles</option>";
          document.getElementById('select_persona').innerHTML=cadena;
          document.getElementById('select_persona_editar').innerHTML=cadena;
      }
  })
}

function Cargar_select_Area(){
  $.ajax({
      "url":"../controller/usuario/controlador_cargar_select_area.php",
      type:'POST',
  }).done(function(resp){
      let data = JSON.parse(resp);
      if(data.length>0){
          let cadena = "";
          for (let i = 0; i < data.length; i++) {
              cadena+="<option value='"+data[i][0]+"'>"+data[i][1]+"</option>";
          }
          document.getElementById('select_area').innerHTML=cadena;
          document.getElementById('select_area_editar').innerHTML=cadena;
      }else{
          cadena+="<option value=''>No hay areas disponibles</option>";
          document.getElementById('select_area').innerHTML=cadena;
          document.getElementById('select_area_editar').innerHTML=cadena;
      }
  })
}

function Modificar_Usuario(){
  let id = document.getElementById('txt_idusuario').value;
  let idp = document.getElementById('select_persona_editar').value;
  let ida = document.getElementById('select_area_editar').value;
  let rol = document.getElementById('select_rol_editar').value;
  if(id.length==0 || idp.length==0 || ida.length==0 || rol.length==0){
      return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
  }

  $.ajax({
      "url":"../controller/usuario/controlador_modificar_usuario.php",
      type:'POST',
      data:{
          id:id,
          idp:idp,
          ida:ida,
          rol:rol
      }
  }).done(function(resp){
      if(resp>0){
              Swal.fire("Mensaje de Confirmación","Datos del usuario actualizados","success").then((value)=>{
                  tbl_usuario.ajax.reload();
                  $("#modal_editar").modal('hide');
              });
      }else{
          return Swal.fire("Mensaje de Error","No se completó la modificación","error");
      }
  })
}

function Modificar_Usuario_Contra(){
  let id = document.getElementById('txt_idusuario_contra').value;
  let con = document.getElementById('txt_contra_nueva').value;
  console.log("ID Usuario: ", id);
  console.log("Nueva Contraseña: ", con);
  if(id.length==0 || con.length==0){
      return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
  }

  $.ajax({
      "url":"../controller/usuario/controlador_modificar_usuario_contra.php",
      type:'POST',
      data:{
          id:id,
          con:con
      }
  }).done(function(resp){
      if(resp>0){
              Swal.fire("Mensaje de Confirmación","Contraseña del usuario actualizada","success").then((value)=>{
                  document.getElementById('txt_contra_nueva').value="";
                  tbl_usuario.ajax.reload();
                  $("#modal_contra").modal('hide');
              });
      }else{
          return Swal.fire("Mensaje de Error","No se completó la modificación","error");
      }
  })
}

function Modificar_Estatus_Usuario(id,estatus,user){
    let esta = estatus;
    if(esta=="INACTIVO"){
        esta = "Desactivó";
    }else{
        esta 
    }
    $.ajax({
        "url":"../controller/usuario/controlador_modificar_usuario_estatus.php",
        type:'POST',
        data:{
            id:id,
            estatus:estatus,
            user:user
        }
    }).done(function(resp){
        if(resp>0){
                Swal.fire("Mensaje de Confirmación","Se "+esta+" con Éxito el usuario "+user+".","success").then((value)=>{
                    tbl_usuario.ajax.reload();
                    $("#modal_contra").modal('hide');
                });
        }else{
            return Swal.fire("Mensaje de Error","No se completó la modificación","error");
        }
    })
}


///seguimiento Tramite
function Traer_Datos_Seguimiento(){
  let numero = document.getElementById('txt_numero').value;
  let dni = document.getElementById('txt_dni').value;
  if(numero.length == 0 || dni.length == 0){
    return Swal.fire("Mensaje de Advertencia!","Llene los campos vacíos","warning");
  }
  $.ajax({ //Realizar la Petición AJAX para Obtener los Datos
      "url":"/controller/usuario/controlador_traer_seguimiento.php", //Especifica la URL del controlador que obtendrá los datos desde la base de datos.
      type:'POST',
      data: {
        numero:numero,
        dni:dni
      }
  }).done(function(resp){  //Cuando la solicitud AJAX se completa, el backend devuelve una respuesta en formato JSON.
      let data = JSON.parse(resp); //Convierte la respuesta JSON del servidor en un objeto de JavaScript.
      var cadena = "";
      if(data.length>0){ //Verifica si la respuesta contiene registros.
        document.getElementById("div_buscador").style.display = "block";
        document.getElementById("div_detalle_tramite").style.display = "block";  // ← mostramos el card naranja

        // Rellenar los datos en las celdas
        document.getElementById("celdaexpe").innerText = data[0][0];     // ID Trámite
        document.getElementById("celdanro").innerText = data[0][1];      // N° Documento
        document.getElementById("celdatipo").innerText = data[0][6];     // Tipo documento
        document.getElementById("celdasunto").innerText = data[0][5];    // Asunto

        document.getElementById("celdadni").innerText = data[0][2];      // DNI
        document.getElementById("celdanombre").innerText = data[0][3];   // Apellidos y nombres
        document.getElementById("celdaruc").innerText = data[0][7];      // RUC
        document.getElementById("celdaenti").innerText = data[0][8];     // Entidad
        document.getElementById('lbl_titulo').innerHTML = "<b>Seguimiento del Trámite: "+data[0][0]+"-"+data[0][3]+"</b>"
          cadena = '<div class="timeline">'+
                        '<div class="time-label" >'+
                          '<span class="bg-red">'+'Fecha Inicio: &nbsp;'+data[0][4]+'</span>'+
                        '</div>'; 
            //Ajax para el detalle del seguimiento            
            $.ajax({ //Realizar la Petición AJAX para Obtener los Datos
                  "url":"/controller/usuario/controlador_traer_seguimiento_detalle.php", //Especifica la URL del controlador que obtendrá los datos desde la base de datos.
                  type:'POST',
                  data: {
                    codigo:data[0][0]
                  }
              }).done(function(resp){  
                  let datadetalle = JSON.parse(resp); 
                  if(datadetalle.length>0){ 
                      for (let i = 0; i < datadetalle.length; i++) {
                        cadena+='<div>'+
                                  '<i class="fas fa-envelope bg-blue"></i>'+
                                  '<div class="timeline-item">'+
                                    '<span class="time"><i class="fas fa-clock"></i><b>&nbsp;'+datadetalle[i][3]+'</b></span>'+
                                    '<h3 class="timeline-header"><a href="#">Ubicación del Trámite: '+datadetalle[i][2]+
                                    '</a> - <b>Estado: '+datadetalle[i][5]+' </b></h3>'+
                                    '<div class="timeline-body">'+datadetalle[i][4]+''+
                                    '</div>'+
                                 '</div>'+
                               '</div>'
                    }
                    cadena+='</div>';
                    document.getElementById("div_seguimiento").innerHTML = cadena;
                  }
              })

              /////
                        
          for (let i = 0; i < data.length; i++) { 
             
          }

          console.log(data);
          
      }else{
        document.getElementById("div_buscador").style.display = "none";
      }
  })
}