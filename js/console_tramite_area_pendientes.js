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
            $('#rad_presentacion1').prop('checked', tipo.includes("NATURAL"));
            $('#rad_presentacion2').prop('checked', tipo.includes("OTRA"));
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
                d.estado = 'PENDIENTE';  // Enviamos el estado
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
            {"defaultContent":"<button class='mas btn btn-danger btn-sm' style='padding: 2px 5px; font-size: 0.75rem;'><i class='fa fa-search'></i></button>"},
            {"defaultContent":"<button class='seguimiento btn btn-success btn-sm' style='padding: 2px 5px; font-size: 0.75rem;'><i class='fa fa-search'></i></button>"},
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

                        let claseColor = 'bg-black'; // predeterminado: 0-1 días
                        if (diffDays === 2) {
                            claseColor = 'bg-green';
                        } else if (diffDays === 3) {
                            claseColor = 'bg-amber';
                        } else if (diffDays >= 4) {
                            claseColor = 'bg-danger';
                        }

                        return `<span class="badge ${claseColor}" style="font-size: 0.8rem;">PENDIENTE</span>`;
                    }
                }
            },
            {"data":"tramite_estado",
                render: function(data,type,row){

                    let areaActual = row.area_destino_id;

                    if(data=='PENDIENTE' && areaActual == 'MESA DE PARTES'){
                        return "<button class='accion btn btn-info btn-sm' style='padding: 2px 5px; font-size: 0.75rem;'><i class='fas fa-share-square'></i></button>&nbsp";
                    }else if(data=='PENDIENTE' && areaActual != 'MESA DE PARTES'){
                        return "<button class='accion btn btn-info btn-sm' style='padding: 2px 5px; font-size: 0.75rem;'><i class='fas fa-share-square'></i></button>&nbsp;\
                                <button class='aceptar btn btn-secondary btn-sm' style='padding: 2px 5px; font-size: 0.75rem;'><i class='fas fa-ellipsis-h text-white'></i></button>";
                    }else{
                        return "<button class='archivar btn btn-warning btn-sm' style='padding: 2px 5px; font-size: 0.75rem;'><i class='fas fa-archive'></i></button>"
                    }
                }
            },
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

function mostrarArchivoTramite(archivoRuta) {
    if (!archivoRuta || archivoRuta.trim() === "") {
        $("#archivo_preview").html("<p>No hay archivo adjunto</p>");
        return;
    }

    let baseURL = window.location.origin;
    let fullURL = baseURL + '/' + archivoRuta;
    let extension = archivoRuta.split('.').pop().toLowerCase();
    let previewHTML = "";

    if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        previewHTML = `<img src="${fullURL}" class="img-fluid" style="max-width: 100%; height: auto;">`;
    } else if (extension === "pdf") {
        previewHTML = `<iframe src="${fullURL}" width="100%" height="500px"></iframe>`;
    } else {
        previewHTML = `<a href="${fullURL}" target="_blank" class="btn btn-primary">Descargar Archivo</a>`;
    }

    $("#archivo_preview").html(previewHTML);
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