var tbl_tramite;
function listar_tramite(){
    tbl_tramite = $("#tabla_tramite").DataTable({
        "ordering":true,  
        "bLengthChange":true,
        "searching": { "regex": false },
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "pageLength": 10,
        "destroy":true,
        "async": false ,
        "processing": true,
        "ajax":{
            "url":"../controller/tramite/controlador_listar_tramite.php",
            type:'POST',
            "data": function(d) {
                let fechaInicio = $("#reporte_fecha_inicio").val();
                let fechaFin = $("#reporte_fecha_fin").val();
                let estado = $("#reporte_estado").val();
                let area = $("#select_area_filtro").val();

                // Si ambas fechas están llenas, las enviamos
                d.fecha_inicio = (fechaInicio !== "" && fechaFin !== "") ? fechaInicio : "";
                d.fecha_fin    = (fechaInicio !== "" && fechaFin !== "") ? fechaFin    : "";

                // Enviamos estado y área aunque las fechas estén vacías
                d.estado = estado;
                d.area   = area;
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
                render: function(data,type,row){
                    let estado = '';
                    let badgeExtra = '';
                    const estadoActual = data;
                    const fechaPendiente  = row.pendiente_fecha;

                    // Calcular días transcurridos desde la fecha de registro
                    if (estadoActual === 'PENDIENTE' || estadoActual === 'EN ESPERA') {
                        const fechaInicio = new Date(fechaPendiente );
                        const hoy = new Date();
                        const diffTime = Math.abs(hoy - fechaInicio);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        if (diffDays >= 2) {
                            badgeExtra = ` <span class="badge bg-danger">+${diffDays} días</span>`;
                        }
                    }

                    // Badge de estado
                    if (estadoActual === 'PENDIENTE') {
                        estado = '<span class="badge bg-warning">PENDIENTE</span>';
                    } else if (estadoActual === 'RECHAZADO') {
                        estado = '<span class="badge bg-danger">RECHAZADO</span>';
                    } else if (estadoActual === 'ARCHIVADO') {
                        estado = '<span class="badge bg-gray">ARCHIVADO</span>';
                    } else {
                        estado = '<span class="badge bg-success">ACEPTADO</span>';
                    }

                    return estado + badgeExtra;
                }
            },
        ],
  
        "language":idioma_espanol,
        select: true
    });
}

$('#tabla_tramite').on('click','.seguimiento',function(){
    var data = tbl_tramite.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_tramite.row(this).child.isShown()){
        var data = tbl_tramite.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
    $("#modalseguir").modal('show');
    document.getElementById('nro_expe1').innerHTML = data.tramite_id;
    listar_tramite_seguimiento(data.tramite_id);
})


$('#tabla_tramite').on('click','.mas',function(){
    var data = tbl_tramite.row($(this).parents('tr')).data();//En tamaño escritorio
    if(tbl_tramite.row(this).child.isShown()){
        var data = tbl_tramite.row(this).data();
    }//Permite llevar los datos cuando es tamaño celular y usas  el responsive de datatable
    $("#modal_mas").modal('show');
    document.getElementById('nro_expe').innerHTML = data.tramite_id;

    //DATOS DEL REMITENTE
    document.getElementById('txt_dni').value=data.remitente_dni;
    document.getElementById('txt_nom').value=data.remitente_nombre;
    document.getElementById('txt_apepat').value=data.remitente_apepat;
    document.getElementById('txt_apemat').value=data.remitente_apemat;
    document.getElementById('txt_celular').value=data.remitente_celular;
    document.getElementById('txt_email').value=data.remitente_email;
    document.getElementById('txt_dire').value=data.remitente_direccion;
    if(data.tramite_doc_representacion.toUpperCase() == "A NOMBRE PROPIO"){
        $("#rad_presentacion1").prop('checked',true);
        document.getElementById('div_juridico').style.display = "none";
    }
    if(data.tramite_doc_representacion.toUpperCase() == "A OTRA PERSONA NATURAL"){
        $("#rad_presentacion2").prop('checked',true);
        document.getElementById('div_juridico').style.display = "none";
    }
    if(data.tramite_doc_representacion.toUpperCase() == "PERSONA JURÍDICA"){
        $("#rad_presentacion3").prop('checked',true);
        document.getElementById('div_juridico').style.display = "block";
    }
    document.getElementById('rad_presentacion1').value=data.tramite_doc_representacion;
    document.getElementById('rad_presentacion2').value=data.tramite_doc_representacion;
    document.getElementById('rad_presentacion3').value=data.tramite_doc_representacion;
    document.getElementById('txt_ruc').value=data.tramite_doc_ruc;
    document.getElementById('txt_razon').value=data.tramite_doc_razon;

    // Datos del documento
    $("#select_area_p").select2().val(data.area_origen).trigger('change.select2');
    $("#select_area_d").select2().val(data.area_destino).trigger('change.select2');
    $("#select_tipo").select2().val(data.tipodocumento_id).trigger('change.select2');
    document.getElementById('txt_ndocumento').value=data.tramite_nrodocumento;
    document.getElementById('txt_folio').value=data.tramite_folio;
    document.getElementById('txt_asunto').value=data.tramite_asunto;

    // Obtener archivo usando Ajax
    $.ajax({
        url: '/controller/tramite/controlador_obtener_archivo.php',
        type: 'POST',
        data: { id_tramite: data.tramite_id },
        dataType: 'json',
        success: function(response) {
            console.log("🔍 Respuesta del servidor:", response); // 👈 Muestra la respuesta en la consola
            if (response.archivo) {
                mostrarArchivoTramite(response.archivo);
            } else {
                $("#archivo_preview").html("<p>No hay archivo adjunto</p>");
            }
        },
        error: function() {
            console.error("❌ Error AJAX:", error); // 👈 Muestra el error exacto
            $("#archivo_preview").html("<p>Error al cargar el archivo</p>");
        }
    });
})

function Cargar_select_Area(){
    $.ajax({
        "url":"../controller/usuario/controlador_cargar_select_area.php",
        type:'POST',
    }).done(function(resp){
        let data = JSON.parse(resp);
        if(data.length>0){
            let cadena = "<option value=' ' selected disabled>Seleccione un Area</option>";
            for (let i = 0; i < data.length; i++) {
                cadena+="<option value='"+data[i][0]+"'>"+data[i][1]+"</option>";
            }
            document.getElementById('select_area_p').innerHTML=cadena;
            document.getElementById('select_area_d').innerHTML=cadena;
        }else{
            cadena+="<option value=''>No hay areas disponibles</option>";
            document.getElementById('select_area_p').innerHTML=cadena;
            document.getElementById('select_area_d').innerHTML=cadena;
        }
    })
}

function Cargar_select_Area_filtrado(){
    $.ajax({
        url: "../controller/usuario/controlador_cargar_select_area.php",
        type: 'POST',
    }).done(function(resp) {
        let data = JSON.parse(resp);
        let cadena = "<option value='0'>Todos</option>"; // ✅ Agrega opción 'Todos'
        
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                cadena += "<option value='" + data[i][0] + "'>" + data[i][1] + "</option>";
            }
        } else {
            cadena += "<option value=''>No hay áreas disponibles</option>";
        }
        document.getElementById('select_area_filtro').innerHTML = cadena;
    });
}

function Cargar_Select_Tipo(){
    $.ajax({
        "url":"../controller/tramite/controlador_cargar_select_tipo.php",
        type:'POST',
    }).done(function(resp){
        let data = JSON.parse(resp);
        if(data.length>0){
            let cadena = "<option value=' ' selected disabled>SELECCIONAR TIPO DOCUMENTO</option>";
            for (let i = 0; i < data.length; i++) {
                cadena+="<option value='"+data[i][0]+"'>"+data[i][1]+"</option>";
            }
            document.getElementById('select_tipo').innerHTML=cadena;
        }else{
            cadena+="<option value=''>No hay areas disponibles</option>";
            document.getElementById('select_tipo').innerHTML=cadena;
        }
    })
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
                    $("#contenido_principal").load("tramite/view_tramite.php");
                });
            }else{
                Swal.fire("Mensaje de Advertencia","El Usuario ingresado ya se encuentra en la base de datos","warning");
            }
        }
    });
    return false;
}

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

function mostrarArchivoTramite(archivoRuta) {
    if (!archivoRuta || archivoRuta.trim() === "") {
        $("#archivo_preview").html("<p>No hay archivo adjunto</p>");
        return;
    }

    // Construcción de la URL asegurando que tenga "controller/tramite/documentos/"
    let baseURL = window.location.origin;
    let fullURL = baseURL + '/' + archivoRuta;

    console.log("🔍 URL corregida para el archivo:", fullURL); // 👈 Para depuración

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



