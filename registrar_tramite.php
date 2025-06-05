<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mesa de Partes Virtual - Mixto San Luis</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plantilla/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="plantilla/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plantilla/build/css/style.css">
    <link rel="icon" href="assets/img/logo mixto.jpg">
    <link rel="stylesheet" href="assets/css/mesapartes.css">
    <link rel="stylesheet" href="plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">


    <!-- Select2 -->
    <link rel="stylesheet" href="plantilla/plugins/select2/css/select2.min.css"> <!-- modifiqué solo el height:28px a height: none por si algun dia lo necesite -->

</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="registrar_tramite.php" class="navbar-brand">
                    <img src="assets/img/logo mixto.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">TRAMITE DOCUMENTARIO</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav border-1">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link"><i class="fa fa-user"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="registrar_tramite.php" class="nav-link"><i class="fa fa-plus"></i> Registrar Trámite</a>
                        </li>
                        <li class="nav-item">
                            <a href="seguimiento.php" class="nav-link"><i class="fa fa-search"></i> Seguimiento Trámite</a>
                        </li>
                    </ul>

                    <!-- SEARCH FORM -->
                    <!-- <form class="form-inline ml-0 ml-md-3">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->
                </div>

            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <h1 class="m-0 mb-3 text-center"><b>Realizar Trámite</b></h1>
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="mb-0">Formato único de trámite (FUT):
                                <a href="assets/docs/fut 21001.pdf" download="fut 21001.pdf">fut.pdf</a>
                            </label>
                            <label class="mb-0">Texto único de procedimiento Administrativo (TUPA):
                                <a href="assets/docs/tupa 21001.pdf" download="tupa 21001.pdf">tupa.pdf</a>
                            </label>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <button class="btn btn-outline-info" id="ver_pago"><b>Pagos</b>&nbsp;<i class="fas fa-ticket-alt"></i></button>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
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
                                                    <input type="text" class="form-control" id="txt_nom">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">APELLIDO PATERNO<span class="span-red"> (*)</span></label>
                                                    <input type="text" class="form-control" id="txt_apepat">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">APELLIDO MATERNO<span class="span-red"> (*)</span></label>
                                                    <input type="text" class="form-control" id="txt_apemat">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">CELULAR<span class="span-red"> (*)</span></label>
                                                    <input type="text" class="form-control" id="txt_celular" maxlength="9">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label for="" style="font-size:small;">EMAIL<span class="span-red"> (*)</span></label>
                                                    <input type="text" class="form-control" id="txt_email">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label for="" style="font-size:small;">DIRECCION<span class="span-red"> (*)</span></label>
                                                    <input type="text" class="form-control" id="txt_dire">
                                                </div>
                                                <div class="col-12">
                                                    <label for="" style="font-size:small;">EN REPRESENTACION<span class="span-red"> (*)</span></label>
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


                                                <span class="span-red">Campos Obligatorios (*)</span>
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
                                                    <label for="" style="font-size:small;">Adjuntar documento<span class="span-red"> (*)</span></label>
                                                    <input type="file" class="form-control" id="txt_archivo">
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="" style="font-size:small;">Nº FOLIO<span class="span-red"> (*)</span></label>
                                                    <input type="text" class="form-control" id="txt_folio">
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
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- MODAL REGISTRAR_PAGO -->
    <div class="modal fade" id="modal_registrar_pago">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar Pago</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="container-yape">
                            <img src="assets/img/yape.png" alt="Logo" class="logo-yape">
                            <div class="info-yape">
                                <p>997 558 341</p>
                                <p>Colegio Mixto San Luis</p>
                                <p>Carmen Yauri Aburto</p>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-4 mt-2 mb-2 d-flex">
                            <label class="m-2">Monto(S/): </label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Registrar pago</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.MODAL -->




    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plantilla/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="plantilla/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="plantilla/dist/js/demo.js"></script>

    <script src="js/console_tramite_externo.js?rev=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <script src="plantilla/plugins/select2/js/select2.full.min.js"></script>

</body>

</html>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        Cargar_Select_Tipo();
        Cargar_select_Area();
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