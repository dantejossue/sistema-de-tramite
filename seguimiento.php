<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mesa de Partes Virtual - 21001</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plantilla/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="plantilla/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plantilla/build/css/style.css">
    <link rel="icon" href="assets/img/logo mixto.png">

    <!-- Select2 -->
    <link rel="stylesheet" href="../plantilla/plugins/select2/css/select2.min.css"> <!-- modifiqué solo el height:28px a height: none por si algun dia lo necesite -->

</head>
<style>
    .table-data td {
  word-break: break-word;       /* Corta palabras largas */
  vertical-align: middle;       /* Centra verticalmente el contenido */
  text-align: center;           /* Centra horizontalmente el contenido */
  padding: 10px;
  max-width: 100%;              /* Para evitar desbordes */
}

.table-data th {
  vertical-align: middle;
  text-align: center;
  white-space: nowrap;
}

.table-data td p {
  margin: 0;
  font-size: 0.9rem; /* Tamaño de fuente más pequeño */
}
</style>
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
                    <div class="col-lg-12"><br>
                        <h1 class="m-0 mb-3 text-center" style="font-size: 1.5rem;"><b>MESA DE PARTES VIRTUAL</b></h1>
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title card-header-title" style="font-size: 1.3rem;"><b>Realizar seguimiento del trámite</b></h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <label for="">Nro Trámite:</label>
                                        <input type="text" class="form-control" id="txt_numero">
                                    </div>
                                    <div class="col-4">
                                        <label for="">Nro DNI:</label>
                                        <input type="text" class="form-control" id="txt_dni">
                                    </div>
                                    <div class="col-3">
                                        <label for="">&nbsp;</label><br>
                                        <button class="btn btn-danger" style="width:100%" onclick="Traer_Datos_Seguimiento()"><i class="fa fa-search"></i>&nbsp; Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-md-12" id="div_detalle_tramite" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-orange">
                                <h5 class="card-title font-w-600 d-flex-gap text-white"><i class="fas fa-file-alt"></i> Información del Trámite</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table border="2" class="table-doc table-data table-striped mb-0" cellspacing="0" cellpadding="5" id="tableDoc">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">
                                                        <h5 class="font-w-600">DATOS DEL DOCUMENTO</h5>
                                                        </font>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Expediente</th>
                                                    <td>
                                                        <p id="celdaexpe"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>N° Documento</th>
                                                    <td>
                                                        <p id="celdanro"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <td>
                                                        <p id="celdatipo"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Asunto</th>
                                                    <td>
                                                        <p id="celdasunto"></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table border="2" class="table-remi table-data table-striped mb-0" cellspacing="0" cellpadding="5" id="tableRemitente">
                                            <tr>
                                                <th colspan="2">
                                                    <h5 class="font-w-600">DATOS DEL REMITENTE</h5>
                                                    </font>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>DNI</th>
                                                <td>
                                                    <p id="celdadni"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Apellidos y Nombres</th>
                                                <td>
                                                    <p id="celdanombre"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>RUC</th>
                                                <td>
                                                    <p id="celdaruc"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Entidad</th>
                                                <td>
                                                    <p id="celdaenti"></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-12" id="div_buscador" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h5 class="card-title m-0" id="lbl_titulo"><b>Seguimiento</b></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12" id="div_seguimiento">
                                        <!-- The time line -->

                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->

                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SECCIÓN DE DETALLE DEL TRÁMITE Y REMITENTE -->
                    <!-- <div class="row mt-3" id="seccion_detalle_tramite" style="display:none;">
                        <div class="col-md-6">
                            <div class="callout callout-success">
                                <h5 class="font-weight-bold">DATOS DEL DOCUMENTO</h5>
                                <table class="table table-bordered table-sm table-striped">
                                    <tr>
                                        <th>Expediente</th>
                                        <td id="celdaexpe"></td>
                                    </tr>
                                    <tr>
                                        <th>N° Documento</th>
                                        <td id="celdanro"></td>
                                    </tr>
                                    <tr>
                                        <th>Tipo</th>
                                        <td id="celdatipo"></td>
                                    </tr>
                                    <tr>
                                        <th>Asunto</th>
                                        <td id="celdasunto"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="callout callout-info">
                                <h5 class="font-weight-bold">DATOS DEL REMITENTE</h5>
                                <table class="table table-bordered table-sm table-striped">
                                    <tr>
                                        <th>DNI</th>
                                        <td id="celdadni"></td>
                                    </tr>
                                    <tr>
                                        <th>Apellidos y Nombres</th>
                                        <td id="celdadatos"></td>
                                    </tr>
                                    <tr>
                                        <th>RUC</th>
                                        <td id="celdaruc"></td>
                                    </tr>
                                    <tr>
                                        <th>Entidad</th>
                                        <td id="celdaenti"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div> -->
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


    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plantilla/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="plantilla/dist/js/adminlte.min.js"></script>

    <script src="js/console_usuario.js?rev=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <script src="plantilla/plugins/select2/js/select2.full.min.js"></script>

</body>

</html>