var tbl_tipodocumento;
function listar_tipodocumento(){
    tbl_tipodocumento = $("#tabla_tipo").DataTable({
        "ordering":false,   
        "bLengthChange":true,
        "searching": { "regex": false },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "destroy":true,
        "async": false ,
        "processing": true,
        "ajax":{
            "url":"../controller/tipo/controlador_listar_tipo.php",
            type:'POST'
        },
        "columns":[
            {"defaultContent":""},
            {"data":"tipodo_descripcion"},
            {"data":"tipodo_fregistro"},
            {"data":"tipodo_estado",
                render: function(data,type,row){
                if(data=='ACTIVO'){
                    return '<span class="badge bg-success">ACTIVO</span>'
                }else{
                    return '<span class="badge bg-danger">INACTIVO</span>'
                }
                }
            },
            {"defaultContent":"<button class='editar btn btn-primary'><i class='fa fa-edit'></i></button>"},
        ],
  
        "language":idioma_espanol,
        select: true
    });
    //Contador
    tbl_tipodocumento.on('draw.td',function(){
      var PageInfo = $("#tabla_tipo").DataTable().page.info();
      tbl_tipodocumento.column(0, {page: 'current'}).nodes().each(function(cell, i){
        cell.innerHTML = i + 1 + PageInfo.start;
      });
    });
}

$('#tabla_tipo').on('click','.editar',function(){
    var data = tbl_tipodocumento.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_tipodocumento.row(this).child.isShown()){
        var data = tbl_tipodocumento.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
    $("#modal_editar").modal('show');
    document.getElementById('txt_tipo_editar').value=data.tipodo_descripcion;
    document.getElementById('txt_idtipo').value=data.tipodocumento_id;
    document.getElementById('select_estatus').value=data.tipodo_estado;
})

function AbrirRegistro(){
    $("#modal_registro").modal({backdrop:'static',Keyboard:false})
    $("#modal_registro").modal('show');
}

function Registrar_Tipo(){

    let tipo= document.getElementById('txt_tipo').value;
    if(tipo.length==0){
        return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
    }

    $.ajax({
        "url":"../controller/tipo/controlador_registro_tipo.php",
        type:'POST',
        data:{
            tipo:tipo
        }
    }).done(function(resp){
        if(resp>0){
            if(resp==1){
                Swal.fire("Mensaje de Confirmación","Nuevo tipo de documento registrado","success").then((value)=>{
                    document.getElementById('txt_tipo').value="";
                    tbl_tipodocumento.ajax.reload();
                    $("#modal_registro").modal('hide');
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El tipo de documento ingresado ya se encuentra en la base de datos","warning");
            }
        }else{
            return Swal.fire("Mensaje de Error","No se completó el registro","error");
        }
    })
}

function Registrar_Tipo(){

    let tipo= document.getElementById('txt_tipo').value;
    if(tipo.length==0){
        return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
    }

    $.ajax({
        "url":"../controller/tipo/controlador_registro_tipo.php",
        type:'POST',
        data:{
            tipo:tipo
        }
    }).done(function(resp){
        if(resp>0){
            if(resp==1){
                Swal.fire("Mensaje de Confirmación","Nuevo tipo de documento registrado","success").then((value)=>{
                    document.getElementById('txt_tipo').value="";
                    tbl_tipodocumento.ajax.reload();
                    $("#modal_registro").modal('hide');
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El tipo de documento ingresado ya se encuentra en la base de datos","warning");
            }
        }else{
            return Swal.fire("Mensaje de Error","No se completó el registro","error");
        }
    })
}

function Modificar_Tipo(){
    let id = document.getElementById('txt_idtipo').value;
    let tipo = document.getElementById('txt_tipo_editar').value;
    let esta = document.getElementById('select_estatus').value;
    if(tipo.length==0 || id.length==0){
        return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
    }

    $.ajax({
        "url":"../controller/tipo/controlador_modificar_tipo.php",
        type:'POST',
        data:{
            id:id,
            tipo:tipo,
            esta:esta
        }
    }).done(function(resp){
        if(resp>0){
            if(resp==1){
                Swal.fire("Mensaje de Confirmación","Datos actualizados","success").then((value)=>{
                    tbl_tipodocumento.ajax.reload();
                    $("#modal_editar").modal('hide');
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El tipo de documento ingresado ya se encuentra en la base de datos","warning");
            }
        }else{
            return Swal.fire("Mensaje de Error","No se completó la modificación","error");
        }
    })
}