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
    <link rel="icon" href="assets/img/logo mixto.png">
    <link rel="stylesheet" href="assets/css/mesapartes.css">
    <link rel="stylesheet" href="plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">


    <!-- Select2 -->
    <link rel="stylesheet" href="plantilla/plugins/select2/css/select2.min.css"> <!-- modifiqué solo el height:28px a height: none por si algun dia lo necesite -->

</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-dark" style="background-color: #084b8a;">
            <div class="container">
                <!-- Logo + Título -->
                <a href="registrar_tramite.php" class="navbar-brand d-flex align-items-center">
                    <img src="assets/img/logo mixto.png" alt="Logo" style="width: 50px; height: 50px;" class="mr-2">
                    <span class="font-weight-bold text-white">I.E.P MIXTO SAN LUIS</span>
                </a>

                <!-- Botón de colapso -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Enlaces -->
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto mr-2">
                        <li class="nav-item mr-2">
                            <a href="index.php" class="btn btn-light btn-sm rounded-pill px-3 text-dark font-weight-bold" style="border:none;">
                                <i class="fa fa-user mr-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item mr-2">
                            <a href="registrar_tramite.php" class="btn btn-light btn-sm rounded-pill px-3 text-dark font-weight-bold" style="border:none;">
                                <i class="fa fa-plus mr-1"></i> Registrar Trámite
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="seguimiento.php" class="btn btn-light btn-sm rounded-pill px-3 text-dark font-weight-bold" style="border:none;">
                                <i class="fa fa-search mr-1"></i> Seguimiento Trámite
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12"><br>
                        <h1 class="m-0 mb-3 text-center" style="font-size: 1.5rem;"><b>MESA DE PARTES VIRTUAL</b></h1>
                        <div class="card card-danger card-outline">
                            <!-- /.card-header -->
                            <div class="card-header">
                                <h3 class="card-title card-header-title" style="font-size: 1.3rem;"><b>Registrar Trámite</b></h3>
                            </div>
                            <div id="contenido_principal">
                                <div id="loading" style="display: none;">
                                    <div class="text-center">
                                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-body ml-5" style="margin-right: 9rem;">
                                    <form id="formulario1">
                                        <div class="form-group text-center mb-4">
                                            <label class="mr-3 font-weight-bold">Tipo de Persona:</label>
                                            <div class="icheck-primary d-inline mr-4">
                                                <input type="radio" id="persona_natural" name="tipo_persona" value="natural">
                                                <label for="persona_natural">Persona natural</label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="persona_juridica" name="tipo_persona" value="juridica">
                                                <label for="persona_juridica">Persona jurídica</label>
                                            </div>
                                        </div>

                                        <div class="form-group d-flex align-items-center">
                                            <label for="input_dni_ruc" class="font-weight-bold mb-0" style="min-width: 100px;">DNI/RUC:</label>
                                            <input type="text" class="form-control" id="input_dni_ruc" maxlength="8" placeholder="(Seleccione tipo de persona)" onkeypress="return soloNumeros(event)" required>
                                        </div>

                                        <div class="form-group" style="margin-left: 6rem;">
                                            <p class="mb-1"><strong>Formato único de trámite (FUT):</strong> <a href="assets/docs/fut.pdf" download>Descargar</a></p>
                                            <p class="text-justify text-dark">
                                                Para empezar un trámite en la institución y quienes no tengan un documento que anexar, se debe descargar, llenar,
                                                firmar este FUT y adjuntar en el siguiente formulario para ser considerado un trámite válido.
                                            </p>

                                            <p class="text-justify text-dark">
                                                <strong>NOTA:</strong> La mesa de partes virtual (MPV) <a href="https://tramite.undc.edu.pe/mesadepartes/" target="_blank">https://tramite.mixto.edu.pe/mesadepartes/</a> está habilitada todos los días,
                                                las 24 horas del día y los siete días de la semana para la presentación de documentos.
                                            </p>

                                            <p class="text-justify text-dark mb-0"><strong>También puedes hacerlo presencialmente:</strong></p>
                                            <ul class="text-dark">
                                                <li><strong>Paso 1:</strong> Entrega tus documentos</li>
                                                <li>Acércate a la mesa de partes del local Administrativo ubicado en "Los chemos - San Luis - Cañete", de lunes a viernes de <strong>08:00 a.m. a 01:00 p.m.</strong> y de <strong>2:00 p.m. a 5:00 p.m.</strong></li>
                                            </ul>

                                            <p class="text-justify text-dark">
                                                Una vez recibida la documentación, se enviará vía correo electrónico un código web para que efectúe el seguimiento a través del módulo de consulta del Sistema de Trámite Documentario.
                                            </p>

                                            <p class="text-justify text-dark">
                                                Para consultar el estado de su trámite ingresará el código web. Asimismo, le brindamos el
                                                <a href="#" target="_blank">Directorio Institucional</a> con la finalidad que se contacte con el área que corresponda según el seguimiento realizado, teniendo en cuenta el horario de atención.
                                            </p>
                                        </div>

                                        <div class="form-group mt-4" style="margin-left: 6rem;">
                                            <div class="icheck-success">
                                                <input type="checkbox" id="acepto_terminos">
                                                <label for="acepto_terminos">
                                                    Acepto los <a href="#" target="_blank">términos y condiciones</a> y la <a href="#" target="_blank">política de privacidad de los datos</a>.
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="text-center">
                                        <button class="btn btn-success btn-lg px-4" id="btn_siguiente" onclick="cargar_contenido('contenido_principal','registrar_tramite_externo.php')">Siguiente</button>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

    </div>
    <!-- ./wrapper -->

    <!-- MODAL REGISTRAR PAGO -->
    <div class="modal fade" id="modal_yape_qr">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-wallet"></i>&nbsp; Método de pago: Yape</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-dark mb-2">
                        <strong>Realiza el pago únicamente si el TUPA indica que tu trámite tiene un costo.</strong><br>
                        Adjunta el comprobante de pago en el documento fut.
                    </p>

                    <!-- Imagen QR o logo de Yape -->
                    <img src="assets/img/qr_yape.jpg" alt="QR Yape" class="img-fluid mb-3" style="max-height: 180px;">

                    <!-- Información de pago -->
                    <div class="mb-3">
                        <h5 class="mb-0 text-primary">997 558 341</h5>
                        <small class="text-dark">Colegio Mixto San Luis</small><br>
                        <small class="text-dark">Carmen Yauri Aburto</small>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plantilla/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="plantilla/dist/js/adminlte.min.js"></script>

    <script src="js/console_tramite_externo.js?rev=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <script src="plantilla/plugins/select2/js/select2.full.min.js"></script>

</body>

</html>

<script>
    function cargar_contenido(id, vista) {
        // Mostrar el "loading"
        $('#loading').show();

        // Cargar el contenido
        $("#" + id).load(vista, function(response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el contenido: " + xhr.status + " " + xhr.statusText);
            } else {
                console.log("Contenido cargado con éxito");
            }
            // Esconder el "loading" cuando el contenido se haya cargado
            $('#loading').hide();
        });
    }

    $('#btn_siguiente').on('click', function(e) {
        e.preventDefault();

        var dni = $('#input_dni_ruc').val();
        var tipoPersona = $('input[name="tipo_persona"]:checked').val();

        // Guardar en sessionStorage
        sessionStorage.setItem('dni', dni);
        sessionStorage.setItem('tipo_persona', tipoPersona);

        cargar_contenido('contenido_principal', 'registrar_tramite_externo.php');
    });
</script>