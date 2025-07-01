<script src="../js/console_tramite_externo.js?rev=
	<?php echo time(); ?>">
</script>

<link rel="stylesheet" href="../plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Main content -->

<div class="content mr-5 ml-5 mt-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-success">
                            <!-- AVISO INFORMATIVO -->
                            <div class="alert alert-info mt-3" role="alert">
                                <strong>Nota:</strong> Si el procedimiento que estás realizando tiene un costo según el <a href="assets/docs/tupa.pdf" target="_blank">TUPA institucional</a>, realiza el pago y consígnalo en el campo “Monto pagado”. El comprobante debe estar dentro del documento adjunto (FUT).
                            </div>
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Datos del Remitente</b>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label for="" style="font-size:small;">Nº DNI<span class="span-red"> (*)</span></label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="txt_dni" maxlength="8">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-success" onclick="Buscar_reniec()"><i class="fa fa-search"></i></button>
                                            </div>
                                            <!-- /btn-group -->
                                        </div>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="" style="font-size:small;">NOMBRE<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_nom" placeholder="Ingrese su nombre">
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="" style="font-size:small;">APELLIDO PATERNO<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_apepat" placeholder="Ingrese su apellido paterno">
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="" style="font-size:small;">APELLIDO MATERNO<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_apemat" placeholder="Ingrese su apellido materno">
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="" style="font-size:small;">CELULAR<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_celular" maxlength="9" placeholder="Ingrese su número de celular">
                                    </div>
                                    <div class="col-6 form-group" hidden>
                                        <label for="" style="font-size:small;">TELEFONO FIJO</label>
                                        <input type="text" class="form-control" id="txt_fijo" placeholder="Ingrese su número de teléfono fijo">
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="" style="font-size:small;">EMAIL<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_email" placeholder="Por favor ingrese un correo válido">
                                        <!-- Indicaciones adicionales debajo del campo -->
                                        <small class="form-text text-muted">Recuerda que este será el correo en el que recibirás la respuesta a tu trámite.</small>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="" style="font-size:small;">DIRECCION<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_dire" placeholder="Ingrese su dirección">
                                    </div>
                                    <div class="col-12">
                                        <label for="" style="font-size:small;">EN REPRESENTACION<span class="span-red"> (*)</span></label>
                                    </div>
                                    <div class="col-12 row text-center justify-content-center">
                                        <div class="col-4 form-group clearfix">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="r1" checked value="natural" id="rad_presentacion1" disabled>
                                                <label for="rad_presentacion1" style="font-weight:normal;font-size:samll">Persona Natural</label>
                                            </div>
                                        </div>
                                        <div class="col-4 form-group clearfix">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="r1" value="juridica" id="rad_presentacion2" disabled>
                                                <label for="rad_presentacion2" style="font-weight:normal;font-size:samll">Persona Jurídica</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" id="div_juridico" style="display:none">
                                        <div class="row">
                                            <div class="col-4 form-group">
                                                <label for="" style="font-size:small;">RUC</label>
                                                <input type="text" class="form-control" id="txt_ruc" placeholder="Ingrese el RUC de la empresa">
                                            </div>
                                            <div class="col-8 form-group">
                                                <label for="" style="font-size:small;">RAZON SOCIAL</label>
                                                <input type="text" class="form-control" id="txt_razon" placeholder="Ingrese la razón social de la empresa">
                                            </div>
                                        </div>
                                    </div>


                                    <span class="span-red">Campos Obligatorios (*)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Datos del Documento</b>
                                </h3>
                            </div>
                            <div class="card-body">
                                <!-- <div class="g-recaptcha" data-sitekey="6LcR2WErAAAAALa0xtui58Vw4stoC9hRYIKMbyMn"></div>
                                <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
                                <div class="row">
                                    <!-- <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">PROCEDENCIA DEL DOCUMENTO</label>
                                                    <select class="js-example-basic-single" id="select_area_p" style="width:100%">
                                                        <option value="MESA DE PARTES"></option>
                                                    </select>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">AREA DE DESTINO</label>
                                                    <select class="js-example-basic-single" id="select_area_d" style="width:100%" aria-placeholder="Seleccione Area">
                                                    </select>
                                                </div> -->
                                    <div class="col-12 form-group">
                                        <label for="" style="font-size:small;">TIPO DOCUMENTO<span class="span-red"> (*)</span></label>
                                        <select class="js-example-basic-single" id="select_tipo" style="width:100%"></select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="" style="font-size:small;">Nº DOCUMENTO<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_ndocumento"></input>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="" style="font-size:small;">ASUNTO DEL TRAMITE<span class="span-red"> (*)</span></label>
                                        <textarea id="txt_asunto" rows="3" class="form-control" style="resize:none" placeholder="INGRESE EL ASUNTO DEL DOCUMENTO"></textarea>
                                    </div>
                                    <div class="col-8 form-group">
                                        <label for="txt_archivo" style="font-size:small;">ADJUNTAR DOCUMENTO<span class="span-red"> (*)</span></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="txt_archivo">
                                            <label class="custom-file-label" for="txt_archivo">Seleccionar archivo</label>
                                        </div>
                                    </div>
                                    <!-- <div class="col-8 form-group">
                                        <div class="custom-file">
                                            <label for="" style="font-size:small;">Adjuntar documento<span class="span-red"> (*)</span></label>
                                            <input type="file" class="custom-file-input" id="txt_archivo">
                                        </div>
                                    </div> -->
                                    <div class="col-4 form-group">
                                        <label for="" style="font-size:small;">Nº FOLIO<span class="span-red"> (*)</span></label>
                                        <input type="text" class="form-control" id="txt_folio">
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="txt_monto_pago" style="font-size:small;">MONTO A PAGAR (OPCIONAL)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="txt_monto_pago" placeholder="Ej: 15.00">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-info" type="button" data-toggle="modal" data-target="#modal_yape_qr">
                                                    <i class="fas fa-qrcode"></i>&nbsp; Ver QR
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12"><br>
                                        <div class="form-group clearfix">
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess1" onclick="validar_informacion()">
                                                <label for="checkboxSuccess1" style="text-align:top">Declaro bajo penualidad que toda la
                                                    información proporcionada es correcta y verídica<span class="span-red"> (*)</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="text-align:center">
                                        <button class="btn btn-success btn-lg" onclick="Registrar_Tramite()" id="btn_registro">REGISTRAR TRAMITE</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<script>
    $('#txt_archivo').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        Cargar_Select_Tipo();
        Cargar_select_Area();
        // Mostrar el div de "Persona Jurídica" si es que se seleccionó ese tipo de persona
        $('input[name="r1"]').on('change', function() {
            if ($(this).val() == 'juridica') {
                $('#div_juridico').show();
            } else {
                $('#div_juridico').hide();
            }
        });

        // Si ya está seleccionada Persona Jurídica en la URL, mostrar los campos correspondientes
        if ($('#rad_presentacion2').is(':checked')) {
            $('#div_juridico').show();
        }
    });

    validar_informacion();

    function validar_informacion() {
        if (document.getElementById('checkboxSuccess1').checked == false) {
            $("#btn_registro").addClass("disabled");
        } else {
            $("#btn_registro").removeClass("disabled");
        }
    }

    $('input[type="file"]').on('change', function() {
        var ext = $(this).val().split('.').pop().toLowerCase(); // Convertir a minúsculas para una comparación uniforme

        if ($(this).val() != '') {
            if (ext === "pdf") { // Solo permitir archivos PDF
                if ($(this)[0].files[0].size > 31457280) { // Tamaño máximo de 30MB
                    Swal.fire("El archivo seleccionado es demasiado pesado",
                        "<label style='color:#980000;'>Selecciona un archivo más liviano</label>",
                        "warning");
                    $("#txt_archivo").val(""); // Limpiar el campo de archivo
                    return;
                }

                $("#txtformato").val(ext); // Guardar la extensión
            } else {
                $("#txt_archivo").val(""); // Limpiar el campo de archivo
                Swal.fire("Solo se permiten archivos PDF", "", "error"); // Mostrar mensaje de error
            }
        }
    });
</script>