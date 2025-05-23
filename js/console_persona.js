var tbl_persona;
function listar_persona(){
    tbl_persona = $("#tabla_persona").DataTable({
        "ordering":false,   
        "bLengthChange":true,
        "searching": { "regex": false },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "destroy":true,
        "async": false ,
        "processing": true,
        "ajax":{
            "url":"../controller/persona/controlador_listar_persona.php",
            type:'POST'
        },
        "columns":[
            {"defaultContent":""},
            {"data":"persona_id",

            render: function(data,type,row){
                return `<button class='mfoto btn btn-warning' data-id='${row.persona_id}' data-foto='../${data}'><i class='fas fa-user-circle'></i></button>`;
            }
        },
            {"data":"per_nrodocumento"},
            {"data":"per"},
            {"data":"per_movil"},
            {"data":"per_email"},
            {"data":"per_direccion"},
            {"data":"per_estado",

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
    tbl_persona.on('draw.td',function(){
      var PageInfo = $("#tabla_persona").DataTable().page.info();
      tbl_persona.column(0, {page: 'current'}).nodes().each(function(cell, i){
        cell.innerHTML = i + 1 + PageInfo.start;
      });
    });
}

$('#tabla_persona').on('click','.editar',function(){
    var data = tbl_persona.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_persona.row(this).child.isShown()){
        var data = tbl_persona.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
    $("#modal_editar").modal('show');
    console.log(data);
    document.getElementById('txt_idpersona').value=data.persona_id;
    document.getElementById('txt_nro_editar').value=data.per_nrodocumento;
    document.getElementById('txt_nom_editar').value=data.per_nombre;
    document.getElementById('txt_apepa_editar').value=data.per_apepat;
    document.getElementById('txt_apema_editar').value=data.per_apemat;
    document.getElementById('txt_fnac_editar').value=data.per_fechanacimiento;
    document.getElementById('txt_movil_editar').value=data.per_movil;
    document.getElementById('txt_dire_editar').value=data.per_direccion;
    document.getElementById('txt_email_editar').value=data.per_email;
    document.getElementById('select_estatus_editar').value=data.per_estado;

})

//MODAL FOTOOOO
$('#tabla_persona').on('click','.mfoto',function(){
    var data = tbl_persona.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_persona.row(this).child.isShown()){
        var data = tbl_persona.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable

    // Verificar si la persona tiene foto
    let foto = data.per_foto ? "../" + data.per_foto : "../uploads/default.jpg";
    
    document.getElementById("foto_actual").src = foto;
    $("#modalfoto").modal('show');
    document.getElementById("persona_id").value = data.persona_id;

})


function AbrirRegistro(){
    $("#modal_registro").modal({backdrop:'static',Keyboard:false})
    $("#modal_registro").modal('show');
}

function Registrar_Persona(){
    let nro = document.getElementById('txt_nro').value;
    let nom = document.getElementById('txt_nom').value;
    let apepa = document.getElementById('txt_apepa').value;
    let apema = document.getElementById('txt_apema').value;
    let fnac = document.getElementById('txt_fnac').value;
    let movil = document.getElementById('txt_movil').value;
    let dire = document.getElementById('txt_dire').value;
    let email = document.getElementById('txt_email').value;
    if(nro.length==0 || nom.length==0 || apepa.length==0 || apema.length==0 || fnac.length==0 || movil.length==0 || dire.length==0 || email.length==0){
        return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
    }

    // Validar DNI
    if (!validarDNI(nro)) {
        return Swal.fire("Mensaje de Advertencia", "El DNI debe contener exactamente 8 dígitos numéricos", "warning");
    }

    if(validar_email(email)){

    }else{
        return Swal.fire("Mensaje de Advertencia","El formato de email es incorrecto","warning")
    }

    $.ajax({
        "url":"../controller/persona/controlador_registro_persona.php",
        type:'POST',
        data:{
            nro:nro,
            nom:nom,
            apepa:apepa,
            apema:apema,
            fnac:fnac,
            movil:movil,
            dire:dire,
            email:email
        }
    }).done(function(resp){
        if(resp>0){
            if(resp==1){
                Swal.fire("Mensaje de Confirmación","Nuevo persona registrado","success").then((value)=>{
                    document.getElementById('txt_nro').value="";
                    document.getElementById('txt_nom').value="";
                    document.getElementById('txt_apepa').value="";
                    document.getElementById('txt_apema').value="";
                    document.getElementById('txt_fnac').value="";
                    document.getElementById('txt_movil').value="";
                    document.getElementById('txt_dire').value="";
                    document.getElementById('txt_email').value="";
                    tbl_persona.ajax.reload();
                    $("#modal_registro").modal('hide');
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El Nro de documento ingresado ya se encuentra en la base de datos","warning");
            }
        }else{
            return Swal.fire("Mensaje de Error","No se completó el registro","error");
        }
    })
}

function Modificar_Persona(){
    let id = document.getElementById('txt_idpersona').value;
    let nro = document.getElementById('txt_nro_editar').value;
    let nom = document.getElementById('txt_nom_editar').value;
    let apepa = document.getElementById('txt_apepa_editar').value;
    let apema = document.getElementById('txt_apema_editar').value;
    let fnac = document.getElementById('txt_fnac_editar').value;
    let movil = document.getElementById('txt_movil_editar').value;
    let dire = document.getElementById('txt_dire_editar').value;
    let email = document.getElementById('txt_email_editar').value;
    let esta = document.getElementById('select_estatus_editar').value;
    if(id.length==0 || nro.length==0 || nom.length==0 || apepa.length==0 || apema.length==0 || fnac.length==0 || movil.length==0 || dire.length==0 || email.length==0 || esta.length==0){
        return Swal.fire("Mensaje de Advertencia","Tiene campos vacíos","warning");
    }

    // Validar DNI
    if (!validarDNI(nro)) {
        return Swal.fire("Mensaje de Advertencia", "El DNI debe contener exactamente 8 dígitos numéricos", "warning");
    }

    if(validar_email(email)){

    }else{
        return Swal.fire("Mensaje de Advertencia","El formato de email es incorrecto","warning")
    }

    $.ajax({
        "url":"../controller/persona/controlador_modificar_persona.php",
        type:'POST',
        data:{
            id:id,
            nro:nro,
            nom:nom,
            apepa:apepa,
            apema:apema,
            fnac:fnac,
            movil:movil,
            dire:dire,
            email:email,
            esta:esta
        }
    }).done(function(resp){
        if(resp>0){
            if(resp==1){
                Swal.fire("Mensaje de Confirmación","Datos actualizados","success").then((value)=>{
                    tbl_persona.ajax.reload();
                    $("#modal_editar").modal('hide');
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El Nro de Documento ingresado ya se encuentra en la base de datos","warning");
            }
        }else{
            return Swal.fire("Mensaje de Error","No se completó la modificación","error");
        }
    })
}

function validar_email(email) {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function validarDNI(dni) {
    // Expresión regular: exactamente 8 dígitos numéricos
    var regex = /^[0-9]{8}$/;
    return regex.test(dni);
}



function subirFoto() {
    let id = document.getElementById("persona_id").value;
    let fotoInput = document.getElementById("nueva_foto");
    let foto = fotoInput.files.length > 0 ? fotoInput.files[0] : null;

    console.log("ID enviado:", id);
    console.log("Archivo seleccionado:", foto);

    if (!id) {
        alert("No se encontró el ID de la persona.");
        return;
    }

    if (!foto) {
        alert("Seleccione una imagen antes de continuar.");
        return;
    }

    let formData = new FormData();
    formData.append("id", id);
    formData.append("foto", foto);

    fetch("../controller/persona/controlador_subir_foto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(datos => {
        console.log("Respuesta del servidor:", datos);

        if (datos.success) {
            alert("Foto actualizada correctamente.");
            let timestamp = new Date().getTime();
            document.getElementById("foto_actual").src = "../" + datos.foto + "?t=" + timestamp;
            $("#modalfoto").modal("hide");
            tbl_persona.ajax.reload();
        } else {
            alert("X " + datos.message);
        }
    })
    .catch(error => console.error("Error en la subida:", error));
}





