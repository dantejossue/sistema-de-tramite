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
            "url":"controller/tramite/controlador_listar_tramite.php",
            type:'POST'
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
                if(data=='PENDIENTE'){
                    return '<span class="badge bg-warning">PENDIENTE</span>'
                }else if(data=='PENDIENTE'){
                    return '<span class="badge bg-danger">RECHAZADO</span>'
                }else{
                    return '<span class="badge bg-success">FINALIZADO</span>'

                }
                }
            },
        ],
  
        "language":idioma_espanol,
        select: true
    });
    console.log(data);
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
    if(data.tramite_doc_representacion == "A Nombre Propio"){
        $("#rad_presentacion1").prop('checked',true);
    }
    if(data.tramite_doc_representacion == "A Otra Persona Natural"){
        $("#rad_presentacion2").prop('checked',true);
    }
    if(data.tramite_doc_representacion == "Persona Jurídica"){
        $("#rad_presentacion3").prop('checked',true);
    }
    document.getElementById('rad_presentacion1').value=data.tramite_doc_representacion;
    document.getElementById('rad_presentacion2').value=data.tramite_doc_representacion;
    document.getElementById('rad_presentacion3').value=data.tramite_doc_representacion;
    document.getElementById('txt_ruc').value=data.tramite_doc_ruc;
    document.getElementById('txt_razon').value=data.tramite_doc_razon;

    //DATOS DEL DOCUMENTO
    $("#select_area_p").select2().val(data.area_origen).trigger('change.select2');
    $("#select_area_d").select2().val(data.area_destino).trigger('change.select2');
    $("#select_tipo").select2().val(data.tipodocumento_id).trigger('change.select2');
    document.getElementById('txt_ndocumento').value=data.tramite_nrodocumento;
    document.getElementById('txt_folio').value=data.tramite_folio;
    document.getElementById('txt_asunto').value=data.tramite_asunto;
})

function Cargar_select_Area(){
    $.ajax({
        "url":"controller/usuario/controlador_cargar_select_area.php",
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

function Cargar_Select_Tipo(){
    $.ajax({
        "url":"controller/tramite/controlador_cargar_select_tipo.php",
        type:'POST',
    }).done(function(resp){
        let data = JSON.parse(resp);
        if(data.length>0){
            let cadena = "<option value=' ' selected disabled>Seleccione tipo de Documento</option>";
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
    let idusu = null;
    let mon = document.getElementById('txt_monto_pago').value;

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
    let arp = 19;
    let ard = 17;
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

    // Validar el tamaño del archivo
    if (archivoobj && archivoobj.size > 5 * 1024 * 1024) {  // 5MB en bytes
        return Swal.fire("Archivo demasiado grande", "El tamaño máximo permitido es 5MB.", "error");
    }

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
    formData.append("mon", mon);

    $.ajax({
        url:'controller/tramite_externo/controlador_registro_tramite_externo_pago.php',
        type:'POST',
        data:formData,
        contentType:false,
        processData:false,
        success:function(resp){
            if(resp.length>0){
                Swal.fire("Mensaje de Confirmación","Nuevo Trámite registrado codigo "+resp,"success").then((value)=>{
                     // ✅ Limpia los datos temporales
                    sessionStorage.removeItem('dni');
                    sessionStorage.removeItem('tipo_persona');
                    // ✅ Redirige a la página de reporte
                    window.open("view/MPDF/REPORTE/ticket_tramite.php?codigo="+resp+"#zoom=100");
                    $("#contenido_principal").load("registrar_tramite_externo.php");
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
            "url":"controller/tramite/controlador_listar_seguimiento_tramite.php",
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

    let url = `controller/tramite/controlador_consulta_dni.php?dni=${dni}`;

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


$('#ver_pago').on('click',function(){
    $("#modal_registrar_pago").modal('show');
    // document.getElementById('nro_expe1').innerHTML = data.tramite_id;
    // listar_tramite_seguimiento(data.tramite_id);
})


function soloNumeros(e) {
      var tecla = (document.all) ? e.keyCode : e.which;
      if (tecla == 8) {
        return true;
      }
      var patron = /[0-9]/;
      var tecla_final = String.fromCharCode(tecla);
      return patron.test(tecla_final);
    }

$(document).ready(function() {
    // Inicialmente deshabilitar el botón "Siguiente" y el campo DNI/RUC
    $('#btn_siguiente').prop('disabled', true);
    $('#input_dni_ruc').prop('disabled', true); // Bloquear el campo DNI/RUC al inicio

    // Función para verificar si el formulario está completo
    function verificarFormulario() {
        var dniRuc = $('#input_dni_ruc').val().trim(); // Obtener el valor del DNI/RUC
        var personaSeleccionada = $('input[name="tipo_persona"]:checked').length > 0; // Verificar si algún radio está seleccionado
        var terminosAceptados = $('#acepto_terminos').prop('checked'); // Verificar si el checkbox está marcado

        // Si el radio está seleccionado, el DNI/RUC no está vacío y los términos están aceptados, habilitar el botón
        if (personaSeleccionada && dniRuc !== "" && terminosAceptados) {
            $('#btn_siguiente').prop('disabled', false);
        } else {
            $('#btn_siguiente').prop('disabled', true);
        }
    }

    // Cuando se cambie la selección del radio button
    $('input[name="tipo_persona"]').on('change', function() {
        // Activar el input y cambiar el placeholder
        $('#input_dni_ruc').prop('disabled', false); // Habilitar el campo DNI/RUC

        if ($('#persona_natural').is(':checked')) {
            $('#input_dni_ruc').attr('placeholder', 'Ingrese su DNI (8 dígitos)');
            $('#input_dni_ruc').attr('maxlength', '8');
        } else if ($('#persona_juridica').is(':checked')) {
            $('#input_dni_ruc').attr('placeholder', 'Ingrese su RUC (11 dígitos)');
            $('#input_dni_ruc').attr('maxlength', '11');
        }

        // Verificar el estado del formulario
        verificarFormulario();
    });

    // Cuando se escriba en el campo DNI/RUC
    $('#input_dni_ruc').on('input', function() {
        // Verificar el estado del formulario
        verificarFormulario();
    });

    // Cuando se marque el checkbox de "Aceptar los términos"
    $('#acepto_terminos').on('change', function() {
        // Verificar el estado del formulario
        verificarFormulario();
    });

    let isFormChanged = false;  // Variable para verificar si el formulario fue modificado

    // Detectamos cualquier cambio en los campos del formulario
    $('input, select, textarea').on('change', function() {
        isFormChanged = true;
    });

    // Cuando el formulario es enviado, marcamos que no hay cambios pendientes
    $('#formulario1').on('submit', function() {
        isFormChanged = false;
    });

    // Evento antes de que la página sea cerrada o recargada
    window.addEventListener('beforeunload', function (e) {
        if (isFormChanged) {
            // Configuramos el mensaje de confirmación
            const message = "Tienes cambios no guardados en el formulario. ¿Estás seguro de que deseas salir?";
            
            // Para algunos navegadores antiguos, necesitamos esta línea:
            (e || window.event).returnValue = message;

            // Para los navegadores más modernos
            return message;
        }
    });

    const dni = sessionStorage.getItem('dni');
    const tipoPersona = sessionStorage.getItem('tipo_persona');

    if (tipoPersona === 'natural') {
        $('#rad_presentacion1').prop('checked', true);
        $('#txt_dni').val(dni).prop('readonly', true); // Hacer el campo DNI de sólo lectura
    } else if (tipoPersona === 'juridica') {
        $('#rad_presentacion2').prop('checked', true);
        $('#div_juridico').show(); // Mostrar RUC/razón si aplica
        $('#txt_ruc').val(dni).prop('readonly', true); // Hacer el campo RUC de sólo lectura
    }

});

