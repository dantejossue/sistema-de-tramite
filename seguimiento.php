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


    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="plantilla/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="plantilla/dist/js/adminlte.min.js"></script>

    <script src="js/console_usuario.js?rev=<?php echo time();?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Select2 -->
    <script src="plantilla/plugins/select2/js/select2.full.min.js"></script>

</body>

</html>