<script src="../js/console_tramite_area.js?rev=
	<?php echo time(); ?>">
</script>

<link rel="stylesheet" href="../plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-11">
                <h1 class="m-0">
                    <center><b>MANTENIMIENTO DE TRÁMITE</b></center>
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-1">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-file-signature"></i>&nbsp; Trámites</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-12 -->
            <div class="col-lg-12">
                <div class="card card-danger card-outline">
                    <!-- /.card-header -->
                    <div class="card-header">
                        <h3 class="card-title card-header-title" style="font-size: 1.3rem;"><b>Registro del Trámite</b></h3>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-body">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <b>Datos del Remitente</b>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">Nº DNI</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="txt_dni" maxlength="8">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="btn btn-success" onclick="Buscar_reniec()"><i class="fa fa-search"></i></button>
                                                        </div>
                                                        <!-- /btn-group -->
                                                    </div>
                                                    <input type="hidden" id="txtprincipalid" value="<?php echo $_SESSION['usu_id']; ?>">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">NOMBRE</label>
                                                    <input type="text" class="form-control" id="txt_nom">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">APELLIDO PATERNO</label>
                                                    <input type="text" class="form-control" id="txt_apepat">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">APELLIDO MATERNO</label>
                                                    <input type="text" class="form-control" id="txt_apemat">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">CELULAR</label>
                                                    <input type="text" class="form-control" id="txt_celular">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">EMAIL</label>
                                                    <input type="text" class="form-control" id="txt_email">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">DIRECCION</label>
                                                    <input type="text" class="form-control" id="txt_dire">
                                                </div>
                                                <div class="col-12">
                                                    <label for="" style="font-size:small;">EN REPRESENTACION</label>
                                                </div>
                                                <div class="col-12 row">
                                                    <div class="col-4 form-group clearfix">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="r1" checked value="A Nombre Propio" id="rad_presentacion1">
                                                            <label for="rad_presentacion1" style="font-weight:normal;font-size:samll">A Nombre Propio</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 form-group clearfix">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="r1" value="A Otra Persona Natural" id="rad_presentacion2">
                                                            <label for="rad_presentacion2" style="font-weight:normal;font-size:samll">A Otra Persona Natural</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 form-group clearfix">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="r1" value="Persona Jurídica" id="rad_presentacion3">
                                                            <label for="rad_presentacion3" style="font-weight:normal;font-size:samll">Persona Jurídica</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12" id="div_juridico" style="display:none">
                                                    <div class="row">
                                                        <div class="col-4 form-group">
                                                            <label for="" style="font-size:small;">RUC</label>
                                                            <input type="text" class="form-control" id="txt_ruc">
                                                        </div>
                                                        <div class="col-8 form-group">
                                                            <label for="" style="font-size:small;">RAZON SOCIAL</label>
                                                            <input type="text" class="form-control" id="txt_razon">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <b>Datos del Documento</b>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">PROCEDENCIA DEL DOCUMENTO</label>
                                                    <!-- <input type="text" class="form-control" id="txt_area_p" disabled></input> -->
                                                    <select class="js-example-basic-single" id="select_area_p" style="width:100%" disabled></select>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">AREA DE DESTINO</label>
                                                    <select class="js-example-basic-single" id="select_area_d" style="width:100%" aria-placeholder="Seleccione Area">
                                                    </select>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">TIPO DOCUMENTO</label>
                                                    <select class="js-example-basic-single" id="select_tipo" style="width:100%"></select>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">Nº DOCUMENTO</label>
                                                    <input type="text" class="form-control" id="txt_ndocumento"></input>
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">ASUNTO DEL TRAMITE</label>
                                                    <textarea id="txt_asunto" rows="3" class="form-control" style="resize:none" placeholder="INGRESE EL ASUNTO DEL DOCUMENTO"></textarea>
                                                </div>
                                                <div class="col-8 form-group">
                                                    <label for="" style="font-size:small;">Adjuntar documento</label>
                                                    <input type="file" class="form-control" id="txt_archivo">
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="" style="font-size:small;">Nº FOLIO</label>
                                                    <input type="text" class="form-control" id="txt_folio">
                                                </div>
                                                <div class="col-12"><br>
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" id="checkboxSuccess1" onclick="validar_informacion()">
                                                            <label for="checkboxSuccess1" style="text-align:justify">Declaro bajo penualidad que toda la
                                                                información proporcionada es correcta y verídica</label>
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
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->

</div>
<!-- /.content -->
<script src="/js/console_tramite_area_registro.js"></script>
<script>
    $(document).ready(function() {

        $('.js-example-basic-single').select2();
        Cargar_Select_Tipo();

        // Esperamos a que se cargue el select de áreas, y recién luego cargamos los datos del usuario
        Cargar_select_Area(function() {
            cargar_datos_usuario_logueado();
        });

        cargar_datos_usuario_logueado();
        $("#rad_presentacion1").on('click', function() {
            document.getElementById('div_juridico').style.display = "none";
        });
        $("#rad_presentacion2").on('click', function() {
            document.getElementById('div_juridico').style.display = "none";
        });
        $("#rad_presentacion3").on('click', function() {
            document.getElementById('div_juridico').style.display = "block";
        });
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