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

        $("#select_area_d").html(cadena);
        $('#select_area_d').select2();
    });
}




function cargar_datos_usuario_logueado() {
    let idusuario = document.getElementById('txtprincipalid').value;

    $.ajax({
        url: '../controller/tramite_area/controlador_cargar_datos_persona_usuario.php',
        type: 'POST',
        data: { idusuario: idusuario },
        success: function(resp) {
            let data = JSON.parse(resp);

            if (data) {
                document.getElementById('txt_dni').value = data.per_nrodocumento;
                document.getElementById('txt_nom').value = data.per_nombre;
                document.getElementById('txt_apepat').value = data.per_apepat;
                document.getElementById('txt_apemat').value = data.per_apemat;
                document.getElementById('txt_celular').value = data.per_movil;
                document.getElementById('txt_email').value = data.per_email;
                document.getElementById('txt_dire').value = data.per_direccion;

                // Seleccionamos automáticamente su área de origen
                // document.getElementById('txt_area_p').value = data.area_nombre;

                Cargar_select_Area_Destino(data.area_id);
                console.log('Área cargada:', data.area_id);
                $("#select_area_p").select2().val(data.area_id).trigger('change.select2');
            }
        }
    });

}