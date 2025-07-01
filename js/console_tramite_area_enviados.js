var tbl_tramite;
function listar_tramite() {
    tbl_tramite = $("#tabla_tramite").DataTable({
        "ordering": false,
        "bLengthChange": true,
        "searching": { "regex": false },
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "pageLength": 10,
        "destroy": true,
        "async": false,
        "processing": true,  // Mantener el indicador de 'procesando' mientras se cargan los datos
        "ajax": {
            "url": "../controller/tramite_area/controlador_listar_tramite_derivados.php",
            type: 'POST',
            data: function(d) {
                let idusuario = document.getElementById('txtprincipalid').value; // Obtener el ID del usuario
                let fechaInicio = $("#reporte_fecha_inicio").val();
                let fechaFin = $("#reporte_fecha_fin").val();

                // Si ambas fechas están llenas, las enviamos
                d.idusuario = idusuario;  // Asegúrate de enviar el ID del usuario
                d.fecha_inicio = (fechaInicio !== "" && fechaFin !== "") ? fechaInicio : "";
                d.fecha_fin = (fechaInicio !== "" && fechaFin !== "") ? fechaFin : "";
            },
        },
        "columns": [
            { "data": "tramite_id" },
            { "data": "tramite_nrodocumento" },
            { "data": "tipodo_descripcion" },
            { "data": "remitente_dni" },
            { "data": "datos_remitente" },
            { "data": "area_origen_nombre" },
            { "data": "area_destino_nombre" },
            {
                "defaultContent": "<button class='mas btn btn-danger btn-sm'><i class='fa fa-search'></i></button>"
            },
            {
                "defaultContent": "<button class='seguimiento btn btn-success btn-sm'><i class='fa fa-search'></i></button>"
            },
            {
                "data": "mov_estado",
                render: function(data, type, row) {
                    let estadoActual = row.mov_estado;
                    if (estadoActual === 'DERIVADO') {
                        return '<span class="badge bg-primary">DERIVADO</span>';
                    }
                }
            }
        ],
        "language": idioma_espanol,
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
    if(data.tramite_doc_representacion.toUpperCase() == "NATURAL"){
        $("#rad_presentacion1").prop('checked',true);
        document.getElementById('div_juridico').style.display = "none";
    }
    if(data.tramite_doc_representacion.toUpperCase() == "A OTRA PERSONA NATURAL"){
        $("#rad_presentacion2").prop('checked',true);
        document.getElementById('div_juridico').style.display = "none";
    }
    if(data.tramite_doc_representacion.toUpperCase() == "JURIDICA"){
        $("#rad_presentacion3").prop('checked',true);
        document.getElementById('div_juridico').style.display = "block";
    }
    document.getElementById('rad_presentacion1').value=data.tramite_doc_representacion;
    document.getElementById('rad_presentacion2').value=data.tramite_doc_representacion;
    document.getElementById('rad_presentacion3').value=data.tramite_doc_representacion;
    document.getElementById('txt_ruc').value=data.tramite_doc_ruc;
    document.getElementById('txt_razon').value=data.tramite_doc_razon;

    //DATOS DEL DOCUMENTO
    //  AQUI RECARGAS LAS AREAS antes de asignar valor
    Cargar_select_Area(function(){
        $("#select_area_p").select2().val(data.area_origen).trigger('change.select2');
        $("#select_area_d").select2().val(data.area_destino).trigger('change.select2');
    });
    $("#select_tipo").select2().val(data.tipodocumento_id).trigger('change.select2');
    document.getElementById('txt_ndocumento').value=data.tramite_nrodocumento;
    document.getElementById('txt_folio').value=data.tramite_folio;
    document.getElementById('txt_asunto').value=data.tramite_asunto;

    if (data.tramite_archivo && data.tramite_archivo !== "") {
    document.getElementById('tab_archivo').innerHTML = `
        <p><strong>Documento Adjunto:</strong></p>
        <a href="/${data.tramite_archivo}" target="_blank">📄 Ver documento</a>`;
    } else {
        document.getElementById('tab_archivo').innerHTML = `
            <p>No se adjuntó ningún documento.</p>
        `;
    }

    console.log(data);

})

function Cargar_select_Area(callback){
    $.ajax({
        "url":"../controller/usuario/controlador_cargar_select_area.php",
        type:'POST',
    }).done(function(resp){
        let data = JSON.parse(resp);
        let cadena = "";

        if(data.length>0){
            for (let i = 0; i < data.length; i++) {
                cadena+="<option value='"+data[i][0]+"'>"+data[i][1]+"</option>";
            }
        }else{
            cadena+="<option value=''>No hay areas disponibles</option>";
        }

        document.getElementById('select_area_p').innerHTML=cadena;
        document.getElementById('select_area_d').innerHTML=cadena;

        if (callback) callback(); // ← ejecuta luego de terminar
    });
}


function Cargar_Select_Tipo(callback){
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

        if (callback) callback();
    })
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