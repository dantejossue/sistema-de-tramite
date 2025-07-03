function Cargar_select_Area(callback) {
    $.ajax({
        url: "../controller/usuario/controlador_cargar_select_area.php",
        type: "POST"
    }).done(function(resp) {
        let data = JSON.parse(resp);
        let cadena = "<option value='' disabled selected>Seleccione un Área</option>";

        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                cadena += `<option value="${data[i][0]}">${data[i][1]}</option>`;
            }
        } else {
            cadena += "<option value=''>No hay áreas disponibles</option>";
        }

        $("#select_area_p").html(cadena);
        $("#select_area_d").html(cadena);

        // Aplicamos select2 (si aún no está aplicado)
        $('#select_area_p').select2();
        $('#select_area_d').select2();

        if (typeof callback === 'function') callback();
    });
}

function Cargar_Select_Tipo(callback) {
    $.ajax({
        url: "../controller/tramite/controlador_cargar_select_tipo.php",
        type: "POST"
    }).done(function(resp) {
        let data = JSON.parse(resp);
        let cadena = "<option value='' disabled selected>SELECCIONAR TIPO DOCUMENTO</option>";

        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                cadena += `<option value="${data[i][0]}">${data[i][1]}</option>`;
            }
        } else {
            cadena += "<option value=''>No hay tipos disponibles</option>";
        }

        $("#select_tipo").html(cadena);
        $('#select_tipo').select2();

        if (typeof callback === 'function') callback();
    });
}

function Cargar_select_Area_Destino(areaId) {
    $.ajax({
        url: "../controller/usuario/controlador_cargar_select_area.php",
        type: "POST"
    }).done(function(resp) {
        let data = JSON.parse(resp);
        let cadena = "<option value='' disabled selected>Seleccione un Área</option>";

        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                if (data[i][0] !== areaId) {
                    cadena += `<option value="${data[i][0]}">${data[i][1]}</option>`;
                }
            }
        } else {
            cadena += "<option value=''>No hay áreas disponibles</option>";
        }

        $("#select_area_d_deri").html(cadena);
        $('#select_area_d_deri').select2();
    });
}

function prepararYMostrarModalMas(data) {
    // Cargar selects primero
    Cargar_select_Area(function () {
        Cargar_Select_Tipo(function () {
            // Llenado de datos de remitente
            $('#txt_dni').val(data.remitente_dni);
            $('#txt_nom').val(data.remitente_nombre);
            $('#txt_apepat').val(data.remitente_apepat);
            $('#txt_apemat').val(data.remitente_apemat);
            $('#txt_celular').val(data.remitente_celular);
            $('#txt_email').val(data.remitente_email);
            $('#txt_dire').val(data.remitente_direccion);

            // Presentación
            let tipo = data.tramite_doc_representacion.toUpperCase();
            $('#rad_presentacion1').prop('checked', tipo.includes("NATURAL","A NOMBRE PROPIO"));
            $('#rad_presentacion2').prop('checked', tipo.includes("OTRA PERSONA NATURAL"));
            $('#rad_presentacion3').prop('checked', tipo.includes("JURIDICA"));
            $('#div_juridico').toggle(tipo.includes("JURIDICA"));

            $('#txt_ruc').val(data.tramite_doc_ruc);
            $('#txt_razon').val(data.tramite_doc_razon);

            // Documentos
            $('#select_area_p').val(data.area_origen).trigger('change.select2');
            $('#select_area_d').val(data.area_destino).trigger('change.select2');
            $('#select_tipo').val(data.tipodocumento_id).trigger('change.select2');

            $('#txt_ndocumento').val(data.tramite_nrodocumento);
            $('#txt_folio').val(data.tramite_folio);
            $('#txt_asunto').val(data.tramite_asunto);

            // Mostrar archivo
            if (data.tramite_archivo) {
                $('#tab_archivo').html(`<p><strong>Documento Adjunto:</strong></p><a href="/${data.tramite_archivo}" target="_blank">📄 Ver documento</a>`);
            } else {
                $('#tab_archivo').html(`<p>No se adjuntó ningún documento.</p>`);
            }

            // Mostrar modal
            $('#nro_expe').html(data.tramite_id);
            $('#modal_mas').modal('show');
        });
    });

    console.log("Datos del trámite:", data); // Para depuración
    mostrarArchivoTramite(data.tramite_archivo); // Mostrar el archivo del trámite
}


var tbl_tramite;
function listar_tramite(){
    tbl_tramite = $("#tabla_tramite").DataTable({
        "ordering":false,   
        "bLengthChange":true,
        "searching": { "regex": false },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "destroy":true,
        "async": false ,
        "processing": true,
        "ajax":{
            "url":"../controller/tramite_area/controlador_listar_tramite.php",
            type:'POST',
            data: function(d){
                let idusuario = document.getElementById('txtprincipalid').value; // Obtener el ID del usuario
                let fechaInicio = $("#reporte_fecha_inicio").val();
                let fechaFin = $("#reporte_fecha_fin").val();

                // Si ambas fechas están llenas, las enviamos
                d.idusuario = idusuario;  // Asegúrate de enviar el ID del usuario
                d.fecha_inicio = (fechaInicio !== "" && fechaFin !== "") ? fechaInicio : "";
                d.fecha_fin = (fechaInicio !== "" && fechaFin !== "") ? fechaFin : "";
                d.estado = '';  // Enviamos el estado
            }
        },
        "columns":[
            {"data":"tramite_id"},
            {"data":"tramite_nrodocumento"},
            {"data":"tipodo_descripcion"},
            {"data":"remitente_dni"},
            {"data":"datos_remitente"},
            {"data":"area_origen_id"},
            {"data":"area_destino_id"},
            {"defaultContent":"<button class='mas btn btn-danger btn-sm'><i class='fa fa-search'></i></button>"},
            {"defaultContent":"<button class='seguimiento btn btn-success btn-sm'><i class='fa fa-search'></i></button>"},
            {"data":"tramite_estado",
                render: function(data, type, row) {
                    let estado = '';
                    let badgeExtra = '';
                    const estadoActual = data;
                    const fechaPendiente  = row.pendiente_fecha;

                    if (estadoActual === 'PENDIENTE' && fechaPendiente ) {
                        const fechaInicio = new Date(fechaPendiente );
                        const hoy = new Date();
                        const diffTime = Math.abs(hoy - fechaInicio);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        if (diffDays >= 2) {
                            badgeExtra = ` <span class="badge bg-danger">+${diffDays} días</span>`;
                        }
                    }

                    if (estadoActual === 'PENDIENTE') {
                        estado = '<span class="badge bg-warning">PENDIENTE</span>';
                    } else if (estadoActual === 'RECHAZADO') {
                        estado = '<span class="badge bg-danger">RECHAZADO</span>';
                    } else {
                        estado = '<span class="badge bg-success">ACEPTADO</span>';
                    }

                    return estado + badgeExtra;
                }
            },
            // {"data":"tramite_estado",
            //     render: function(data,type,row){

            //         let areaActual = row.area_destino_id;

            //         if(data=='PENDIENTE' && areaActual == 'MESA DE PARTES'){
            //             return "<button class='accion btn btn-info btn-sm'><i class='fas fa-share-square'></i></button>&nbsp";
            //         }else if(data=='PENDIENTE' && areaActual != 'MESA DE PARTES'){
            //             return "<button class='accion btn btn-info btn-sm'><i class='fas fa-share-square'></i></button>&nbsp;\
            //                     <button class='aceptar btn btn-secondary btn-sm'><i class='fas fa-ellipsis-h text-white'></i></button>";
            //         }else{
            //             return "<button class='archivar btn btn-warning btn-sm'><i class='fas fa-archive'></i></button>"
            //         }
            //     }
            // },
        ],
  
        "language":idioma_espanol,
        select: true
    });
}

$('#tabla_tramite').on('click', '.seguimiento', function() {
    var data = tbl_tramite.row($(this).parents('tr')).data();
    if(tbl_tramite.row(this).child.isShown()) {
        data = tbl_tramite.row(this).data();
    }

    $("#modalseguir").modal('show');
    document.getElementById('nro_expe1').innerHTML = data.tramite_id;
    listar_tramite_seguimiento(data.tramite_id);
});

$('#tabla_tramite').on('click', '.mas', function () {
    var data = tbl_tramite.row($(this).closest('tr')).data();
    if (tbl_tramite.row(this).child.isShown()) {
        data = tbl_tramite.row(this).data(); // Modo responsive
    }

    prepararYMostrarModalMas(data); // ← Aquí llamas la función encapsulada
});

$('#tabla_tramite').on('click','.accion',function(){
    var data = tbl_tramite.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_tramite.row(this).child.isShown()){
        var data = tbl_tramite.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
    $("#modalderivar").modal('show');
    document.getElementById('nro_expe2').innerHTML = data.tramite_id;
    document.getElementById('idorigen').value = data.area_destino_id;
    Cargar_select_Area_Destino(data.area_destino);
    document.getElementById('txt_idtramite_de').value = data.tramite_id;
    document.getElementById('txt_idarea_origen').value = data.area_destino; 
    
})


var dataGlobal = null; // Variable global para reutilizar
$('#tabla_tramite').on('click','.aceptar',function(){
    var data = tbl_tramite.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_tramite.row(this).child.isShown()){
        var data = tbl_tramite.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable

    dataGlobal = data;
    $("#modalacept").modal('show');
    document.getElementById('nro_expe4').innerHTML = data.tramite_id;
    document.getElementById('nrodoc_acept').value = data.tramite_nrodocumento;
    document.getElementById('nrofol_acept').value = data.tramite_folio;
    document.getElementById('nroexpe_acept').value = data.tramite_id;
    document.getElementById('estado_acept').value = data.tramite_estado;
    document.getElementById('tipodoc_acept').value = data.tipodo_descripcion;
    document.getElementById('asunto_acept').value = data.tramite_asunto;
})

$('#tabla_tramite').on('click','.archivar',function(){
    var data = tbl_tramite.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_tramite.row(this).child.isShown()){
        var data = tbl_tramite.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
    $("#modalarchivar").modal('show');
    document.getElementById('nro_expe3').innerHTML = data.tramite_id;   
})


var tbl_seguimiento;
function listar_tramite_seguimiento(id){
    tbl_seguimiento = $("#tablaSeguimiento").DataTable({
        "ordering":true,   
        "bLengthChange":true,
        "searching": { "regex": false },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "destroy":true,
        "async": false ,
        "processing": true,
        "ajax":{
            "url":"../controller/tramite/controlador_listar_seguimiento_tramite.php",
            type:'POST',
            data: {
                id:id
            }
        },
        "columns":[
            {"defaultContent":""},
            {"data":"mov_fecharegistro"},
            {"data":"area_nombre"},
            {"data":"mov_descripcion"},
        ],
  
        "language":idioma_espanol,
        select: true
    });
    //Contador
    tbl_seguimiento.on('draw.td',function(){
        var PageInfo = $("#tablaSeguimiento").DataTable().page.info();
        tbl_seguimiento.column(0, {page: 'current'}).nodes().each(function(cell, i){
          cell.innerHTML = i + 1 + PageInfo.start;
        });
      });
}

function Buscar_reniec() {
    let dni = document.getElementById("txt_dni").value.trim();

    if (dni.length !== 8 || isNaN(dni)) {
        alert("Ingrese un DNI válido de 8 dígitos.");
        return;
    }

    let url = `../controller/tramite/controlador_consulta_dni.php?dni=${dni}`;

    fetch(url)
    .then(response => response.json())
    .then(datos => {
        console.log("Respuesta de la API:", datos);

        if (datos.success) {
            // Llenar los campos con los datos obtenidos
            document.getElementById("txt_nom").value = datos.data.nombres;
            document.getElementById("txt_apepat").value = datos.data.apellidoPaterno;
            document.getElementById("txt_apemat").value = datos.data.apellidoMaterno;
        } else {
            alert("DNI no encontrado en la API.");
        }
    })
    .catch(error => console.error("Error en la petición:", error));
}

function limpiarderivacion(){
    document.getElementById('iddescripcion').value ="";
}


function Registrar_Derivacion(){
    //capturando datos
    //Datos del remitente
    let idtra = document.getElementById('txt_idtramite_de').value;
    let orig = document.getElementById('txt_idarea_origen').value;
    let dest = document.getElementById('select_area_d_deri').value;
    let desc = document.getElementById('iddescripcion').value;
    let arc = document.getElementById('file_derivacion').value;
    let idusu = document.getElementById('txtprincipalid').value;

    let accion =  document.getElementById('select_accion').value;

    let nombrearchivo = "";

    if(accion === 'DERIVAR' && dest === 'Seleccione un Area'){
        Swal.fire("Mensaje de Advertencia","Seleccionar el Area de Destino","warning");
        return;
    }
    if(arc==""){

    }else{
        let f = new Date();
        let extension = arc.split('.').pop(); //DOCUMENTO.PPT
        nombrearchivo="ARCH"+f.getDate()+""+(f.getMonth()+1)+""+f.getFullYear()+""+f.getHours()+""+f.getMilliseconds()+"."+extension;
    }
    let formData = new FormData();
    let archivoobj = $("#file_derivacion")[0].files[0]; //El objeto del archivo adjuntado

    formData.append("idtra",idtra);
    formData.append("orig",orig);
    formData.append("dest",dest);
    formData.append("desc",desc);
    formData.append("idusu",idusu);
    formData.append("nombrearchivo",nombrearchivo);
    formData.append("archivoobj",archivoobj);
    
    
    $.ajax({
        url:'../controller/tramite_area/controlador_registro_tramite.php',
        type:'POST',
        data:formData,
        contentType:false,
        processData:false,
        success:function(resp){
            if(resp.length>0){
                Swal.fire("Mensaje de Confirmación","Nuevo Trámite Derivado o Finalizado ","success").then((value)=>{
                    $("#modalderivar").modal('hide');
                    tbl_tramite.ajax.reload();  //vuelva a actualizar la tabla
                });
            }else{
                Swal.fire("Mensaje de Advertencia","No se pudo completar el proceso","warning");
            }
        }
    });
    return false;
}

function Registrar_Tramite(){
    //capturando datos
    //Datos del remitente
    let dni = document.getElementById('txt_dni').value;
    let nom = document.getElementById('txt_nom').value;
    let apt = document.getElementById('txt_apepat').value;
    let apm = document.getElementById('txt_apemat').value;
    let cel = document.getElementById('txt_celular').value;
    let ema = document.getElementById('txt_email').value;
    let dir = document.getElementById('txt_dire').value;
    let idusu = document.getElementById('txtprincipalid').value;

    let presentacion = document.getElementsByName('r1');
    let vpresentacion = "";
    for(let i=0;i<presentacion.length;i++){
        if(presentacion[i].checked){
            vpresentacion=presentacion[i].value;
        }
    }

    let ruc = document.getElementById('txt_ruc').value;
    let raz = document.getElementById('txt_razon').value;

    //Datos del documento
    let arp = document.getElementById('select_area_p').value;
    let ard = document.getElementById('select_area_d').value;
    let tip = document.getElementById('select_tipo').value;
    let ndo = document.getElementById('txt_ndocumento').value;
    let asu = document.getElementById('txt_asunto').value;
    let arc = document.getElementById('txt_archivo').value;
    let fol = document.getElementById('txt_folio').value;

    
    if(arc.length==0){
        return Swal.fire("Mensaje de Advertencia","Seleccione algún tipo de documento","warning");
    }
    
    let extension = arc.split('.').pop(); //DOCUMENTO.PPT
    let nombrearchivo = "";
    let f = new Date();

    if(arc.length>0){
        nombrearchivo="ARCH"+f.getDate()+""+(f.getMonth()+1)+""+f.getFullYear()+""+f.getHours()+""+f.getMilliseconds()+"."+extension;
    }

    if(dni.length==0 || nom.length==0 || apt.length==0 || apm.length==0 || cel.length==0 || ema.length==0 || dir.length==0 ){
        return Swal.fire("Mensaje de Advertencia","Llene todos los datos del remitente","warning");
    }

    if(arp.length==0 || ard.length==0 || tip.length==0 || ndo.length==0 || asu.length==0 || fol.length==0 ){
        return Swal.fire("Mensaje de Advertencia","Llene todos los datos del documento","warning");
    }

    let formData = new FormData();
    let archivoobj = $("#txt_archivo")[0].files[0]; //El objeto del archivo adjuntado

    //Datos del remitente
    formData.append("dni",dni);
    formData.append("nom",nom);
    formData.append("apt",apt);
    formData.append("apm",apm);
    formData.append("cel",cel);
    formData.append("ema",ema);
    formData.append("dir",dir);
    formData.append("vpresentacion",vpresentacion);
    formData.append("ruc",ruc);
    formData.append("raz",raz);
    /////////////

    //Datos del documento
    formData.append("arp",arp);
    formData.append("ard",ard);
    formData.append("tip",tip);
    formData.append("ndo",ndo);
    formData.append("asu",asu);
    formData.append("nombrearchivo",nombrearchivo);
    formData.append("fol",fol);
    formData.append("archivoobj",archivoobj);
    formData.append("idusu",idusu);

    $.ajax({
        url:'../controller/tramite/controlador_registro_tramite.php',
        type:'POST',
        data:formData,
        contentType:false,
        processData:false,
        success:function(resp){
            if(resp.length>0){
                Swal.fire("Mensaje de Confirmación","Nuevo Trámite registrado codigo "+resp,"success").then((value)=>{
                    window.open("MPDF/REPORTE/ticket_tramite.php?codigo="+resp+"#zoom=100");
                    $("#contenido_principal").load("tramite_area/view_tramite_area_registro.php");
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El Usuario ingresado ya se encuentra en la base de datos","warning");
            }
        }
    });
    return false;
}


function cambiarEstadoTramite(nuevoEstado,data) {
    let area_destino = data.area_destino;
    let idtramite = document.getElementById('nroexpe_acept').value;
    let descripcion = document.getElementById('des').value;
    let idusuario = document.getElementById('txtprincipalid').value;
    
    $.ajax({
        url:'../controller/tramite_area/controlador_estado_tramite.php',
        type: 'POST',
        data: {
            idtramite: idtramite,
            estado: nuevoEstado,
            descripcion: descripcion,
            idusuario: idusuario,
            area_destino: area_destino
        }
    }).done(function(resp) {
        if (resp > 0) {
            Swal.fire("Éxito", "El trámite fue " + nuevoEstado.toLowerCase(), "success").then(() => {
                $("#modalacept").modal("hide");
                tbl_tramite.ajax.reload();
            });
        } else {
            Swal.fire("Error", "No se pudo cambiar el estado", "error");
        }
    });
}