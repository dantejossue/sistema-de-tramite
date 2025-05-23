var tbl_area; // lo declaramos como una variable global
function listar_area() {
  tbl_area = $("#tabla_area").DataTable({
    //Aquí estamos inicializando DataTables en la tabla con ID tabla_area.
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
      "url": "../controller/area/controlador_listar_area.php", //para obtener los datos de los usuarios desde el backend.
      type: "POST",
    },

    "columns": [
      { "defaultContent":""}, //Columna para numeración (contador)
      { "data": "area_nombre"}, //Muestra el valor del campo usu_usuario que viene del JSON.
      { "data": "area_fecha_registro"},
      {
        "data": "area_estado", //Se obtiene usu_estado de la base de datos.
        render: function (data, type, row) {
          //Se usa render para transformar visualmente algunos datos, especialmente estados y botones
          if (data == 'ACTIVO') {
            return '<span class="badge bg-success">ACTIVO</span>';
          } else {
            return '<span class="badge bg-danger">INACTIVO</span>';
          }
        },
      },
      {"defaultContent":"<button class='editar btn btn-primary btn-sm'><i class='fa fa-edit'></i></button>"},
    ],

    language: idioma_espanol,
    select: true,
  });
  //contador
  tbl_area.on("draw.td", function () {
    //Cada vez que la tabla se dibuja (draw.td), se numeran las filas automáticamente.
    var PageInfo = $("#tabla_area").DataTable().page.info(); //Se obtiene la PageInfo para calcular el número correcto en cada página.
    tbl_area
      .column(0, { page: "current" })
      .nodes()
      .each(function (cell, i) {
        cell.innerHTML = i + 1 + PageInfo.start;
      }); // Se asigna un número a cada fila en la primera columna (column(0)).
  });
}

$('#tabla_area').on('click','.editar',function(){
  var data = tbl_area.row($(this).parents('tr')).data();//En tamaño escritorio
  if(tbl_area.row(this).child.isShown()){
      var data = tbl_area.row(this).data();
  }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
  $("#modal_editar").modal('show');
  document.getElementById('txt_area_editar').value=data.area_nombre;
  document.getElementById('txt_idarea').value=data.area_id;
  document.getElementById('select_estado').value=data.area_estado;
})


function AbrirRegistro(){
  $("#modal_registro").modal({backdrop:'static',keyboard:false})
  $("#modal_registro").modal('show');
}

function Registrar_Area(){
  let area = document.getElementById('txt_area').value;
  if(area.length==0){
      return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
  }

  $.ajax({
      "url":"../controller/area/controlador_registro_area.php",
      type:'POST',
      data:{
          a:area
      }
  }).done(function(resp){
      if(resp>0){
          if(resp==1){
              Swal.fire("Mensaje de Confirmación","Nuevo área registrado","success").then((value)=>{
                  document.getElementById('txt_area').value="";
                  tbl_area.ajax.reload();
                  $("#modal_registro").modal('hide');
              });
          }else{
              Swal.fire("Mensaje de Advertencia","El Area: "+area+" ya se encuentra en la base de datos","warning");
          }
      }else{
          return Swal.fire("Mensaje de Error","No se completó el registro","error");
      }
  })
}

function Modificar_Area(){
  let id = document.getElementById('txt_idarea').value;
  let area = document.getElementById('txt_area_editar').value;
  let esta = document.getElementById('select_estado').value;
  if(area.length==0 || id.length==0){
      return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
  }

  $.ajax({
      "url":"../controller/area/controlador_modificar_area.php",
      type:'POST',
      data:{
          id:id,
          are:area,
          esta:esta
      }
  }).done(function(resp){
      if(resp>0){
          if(resp==1){
              Swal.fire("Mensaje de Confirmación","Datos actualizados","success").then((value)=>{
                  tbl_area.ajax.reload();
                  $("#modal_editar").modal('hide');
              });
          }else{
              Swal.fire("Mensaje de Advertencia","El Area: "+area+" ya se encuentra en la base de datos","warning");
          }
      }else{
          return Swal.fire("Mensaje de Error","No se completó la modificación","error");
      }
  })
}