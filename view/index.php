<?php
//si no existe la sesion nos lleva al login
session_start();
if (!isset($_SESSION['S_ID'])) {
  header('Location: ../index.php');
}

?>


<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SistemaTramiteDocumentario</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plantilla/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plantilla/build/fonts/ionicons.css">
  <link rel="stylesheet" href="../plantilla/build/fonts/feather.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../plantilla/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="../plantilla/build/css/style.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <link rel="icon" href="../assets/img/logo mixto.png">

  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plantilla/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plantilla/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plantilla/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plantilla/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plantilla/plugins/summernote/summernote-bs4.min.css">

  <link rel="stylesheet" href="../utilitario/DataTables/datatables.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="../plantilla/plugins/select2/css/select2.min.css">
  <!-- modifiqué solo el height:28px a height: none por si algun dia lo necesite -->

</head>

<body class="hold-transition sidebar-mini">


  <!-- MODAL CONFIRMACION CERRAR SESION -->
  <div class="modal fade" id="mimodal" aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirmación:</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <p>¿Seguro que quiere cerrar la Sesión Actual?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn1 btn-danger" data-dismiss="modal">No. Continuar </button>
          <button type="button" class="btn btn1 btn-primary" onclick="salir()">Sí. Salir</button>
        </div>
      </div>
    </div>
  </div>



  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-info navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu 
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>-->

        <li class="demo-navbar-user nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><b><i class="fas fa-user"></i>&nbsp;
              <?php
              $nombre = $_SESSION['S_USU_NOMBRE'];
              echo $nombre;
              ?>
            </b>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item btn-general" id="Fot" data-toggle="modal">
              <i class="feather icon-user text-muted"></i> &nbsp; Cambiar Foto</a>
            <a class="dropdown-item btn-general" id="Conf" data-toggle="modal">
              <i class="feather icon-settings text-muted"></i> &nbsp; Datos del Perfil</a>
            <a class="dropdown-item btn-general" id="contra" data-toggle="modal">
              <i class="feather icon-settings text-muted"></i> &nbsp; Cambiar Contraseña</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item btn-general" data-toggle="modal" href="#mimodal">
              <i class="feather icon-power text-danger"></i> &nbsp; Salir</a>
          </div>
        </li>


        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link bg-lightblue">
        <img src="../assets/img/logo mixto.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light"><b>IEP MIXTO SAN LUIS</b></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center">
          <!-- <div class="image">
            <img src="../plantilla/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div> -->
          <div class="badge badge-danger" style="font-size: 1rem;">
            <a href="#" class="d-block ml-2"><?php echo "AREA:  " . $_SESSION['S_AREA_NOMBRE']; ?></a>
          </div>

          <input type="text" id="txtprincipalarea" value="<?php echo $_SESSION['S_AREA_ID'] ?>" hidden>

        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php if ($_SESSION['S_ROL'] == 'ADMINISTRADOR(A)') { ?>
              <li class="nav-item">
                <a href="index.php"
                  class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-home"></i>
                  <p>
                    Inicio
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tramite/view_tramite.php')" class="nav-link">
                  <i class="nav-icon fas fa-file-signature"></i>
                  <p>
                    Trámites
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','usuario/view_usuario.php')" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Usuarios
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','persona/view_persona.php')" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>
                    Personal
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','area/view_area.php')" class="nav-link">
                  <i class="nav-icon fas fa-th"></i>
                  <p>
                    Áreas
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tipo_documento/view_tipodocumento.php')"
                  class="nav-link">
                  <i class="nav-icon fas fa-file"></i>
                  <p>
                    Tipos Documentos
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Procedimientos Admin
                </p>
              </a>
             </li>-->
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','busqueda/view_busqueda.php')" class="nav-link">
                  <i class="nav-icon fas fa-search-minus"></i>
                  <p>
                    Seguimiento de Trámites
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','busqueda/view_busqueda.php')" class="nav-link">
                  <i class="nav-icon fas fa-file-contract"></i>
                  <p>
                    Reportes
                  </p>
                </a>
              </li>
            <?php
            }
            ?>
            <?php if ($_SESSION['S_ROL'] == 'SECRETARIO(A)') { ?>
              <li class="nav-item">
                <a href="index.php"
                  class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-home"></i>
                  <p>
                    Inicio
                  </p>
                </a>
              </li>

              <!-- Menú Trámites DESPLEGABLE -->
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="nav-icon fas fa-inbox"></i>
                  <p>
                    Trámites Recibidos
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_pendientes.php'); abrirPendientes();" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Pendientes
                        <span class="right badge badge-danger" id="badge_pendientes" style="display:none;">0</span>
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_aceptados.php')" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Aceptados</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_rechazados.php')" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Rechazados</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_archivados.php')" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Archivados</p>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area.php')"
                  class="nav-link">
                  <i class="nav-icon fas fa-file-signature"></i>
                  <p>
                    Trámites
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_enviados.php')"
                  class="nav-link">
                  <i class="nav-icon fas fa-paper-plane"></i>
                  <p>
                    Trámites Derivados
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_registro.php')"
                  class="nav-link">
                  <i class="nav-icon fas fa-plus"></i>
                  <p>
                    Nuevo Trámite
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_seguimiento.php')"
                  class="nav-link">
                  <i class="nav-icon fas fa-search-minus"></i>
                  <p>
                    Rastrear Trámites
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a onclick="cargar_contenido('contenido_principal','tramite_area/view_tramite_area_reportes.php')"
                  class="nav-link">
                  <i class="nav-icon fas fa-file-contract"></i>
                  <p>
                    Reportes
                  </p>
                </a>
              </li>
            <?php
            }
            ?>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <input type="text" id="txtprincipalid" value="<?php echo $_SESSION['S_ID'] ?>" hidden>
    <input type="text" id="txtprincipalusu" value="<?php echo $_SESSION['S_USU'] ?>" hidden>
    <input type="text" id="txtprincipalrol" value="<?php echo $_SESSION['S_ROL'] ?>" hidden>
    <div class="content-wrapper" id="contenido_principal">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-11">
              <h1 class="m-0">
                <center><b>SISTEMA DE TRÁMITE DOCUMENTARIO</b></center>
              </h1>
            </div><!-- /.col -->
            <div class="col-sm-1">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><i class="nav-icon fas fa-home"></i>&nbsp; Inicio</li>
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
              <div class="card card-primary card-outline">
                <div class="card-header d-flex">
                  <h5 class="m-0"><i class="ion ion-md-folder-open mr-1"></i>&nbsp; <b>RESUMEN DE TRÁMITES EN GENERAL:
                    </b></h5>&nbsp;&nbsp;&nbsp;
                  <h3 id="lbl_tramites" class="card-title card-header-title text-bold" style="font-size: 1.3rem;"></h3>
                  &nbsp;
                  <h3 id="lbl_tramites" class="card-title card-header-title" style="font-size: 1.3rem;"><b> Trámites en
                      total</b></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <?php if ($_SESSION['S_ROL'] == 'ADMINISTRADOR(A)' || $_SESSION['S_ROL'] == 'SECRETARIO(A)') { ?>
                      <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h5><b>ACEPTADOS</b></h5>
                            <h3 id="lbl_tramite_aceptado">0</h3>
                            <p>Total de documentos</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-check-circle"></i>
                          </div>
                          <!-- <a href="#" class="small-box-footer">Mas... <i class="fas fa-arrow-circle-right"></i></a>-->
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h5><b>PENDIENTES</b></h5>
                            <h3 id="lbl_tramite_pendiente">0</h3>
                            <p>Total de documentos</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                          </div>
                          <!-- <a href="#" class="small-box-footer">Mas... <i class="fas fa-arrow-circle-right"></i></a>-->
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-4 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h5><b>RECHAZADOS</b></h5>
                            <h3 id="lbl_tramite_rechazado">0</h3>
                            <p>Total de documentos</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-times-circle"></i>
                          </div>
                          <!-- <a href="#" class="small-box-footer">Mas... <i class="fas fa-arrow-circle-right"></i></a>-->
                        </div>
                      </div>
                      <!-- ./col -->
                    <?php } ?>


                  </div>
                </div>
              </div>
              <!-- CARRUSEL HMTL -->
              <div class="card card-olive card-outline">
                <div class="card-header d-flex">
                  <h5 class="m-0"><i class="far fa-bell"></i>&nbsp; <b>PANEL INFORMATIVO DE TRÁMITE DOCUMENTARIO
                    </b></h5>
                </div>
                <section class="anuncio-carrusel">
                  <div class="card-body">
                    <div class="carrusel-container">
                      <span id="prev-btn" class="arrow">&#10094;</span>
                      <div class="carrusel-slide">
                        <img src="../assets/img/carrusel_1.png" alt="Anuncio 1" />
                      </div>
                      <div class="carrusel-slide">
                        <img src="../assets/img/carrusel_2.png" alt="Anuncio 2" />
                      </div>
                      <div class="carrusel-slide">
                        <img src="../assets/img/carrusel_3.png" alt="Anuncio 3" />
                      </div>
                      <span id="next-btn" class="arrow">&#10095;</span>
                    </div>
                  </div>
                </section>
              </div>
            </div>
            <!-- /.col-md-6 -->
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
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        Anything you want
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2025 <a href="https://adminlte.io">IEP MIXTO SAN LUIS</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->


  <script>
    function cargar_contenido(id, vista) {
      $("#" + id).load(vista);
    }

    var idioma_espanol = {
      select: {
        rows: "%d fila seleccionada"
      },
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ning&uacute;n dato disponible en esta tabla",
      "sInfo": "Registros del (_START_ al _END_) total de _TOTAL_ registros",
      "sInfoEmpty": "Registros del (0 al 0) total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "<b>No se encontraron datos</b>",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }


    function sololetras(e) {
      var key = e.keyCode || e.which;
      var teclado = String.fromCharCode(key).toLowerCase();
      var letras = "qwertyuiopasdfghjklñzxcvbnm ";
      var especiales = "8-37-38-46-164";
      var teclado_especial = false;

      for (var i in especiales) {
        if (key == especiales[i]) {
          teclado_especial = true;
          break;
        }
      }

      if (letras.indexOf(teclado) == -1 && !teclado_especial) {
        return false;
      }
    }

    function soloNumeros(e) {
      var tecla = (document.all) ? e.keyCode : e.which;
      if (tecla == 8) {
        return true;
      }
      var patron = /[0-9]/;
      var tecla_final = String.fromCharCode(tecla);
      return patron.test(tecla_final);
    }

    function validar_email(email) {
      var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    function validarDNI(dni) {
      // Expresión regular: exactamente 8 dígitos numéricos
      var regex = /^[0-9]{8}$/;
      return regex.test(dni);
    }
  </script>



  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- jQuery -->
  <script src="../plantilla/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../plantilla/dist/js/adminlte.min.js"></script>
  <!-- dataTables bootstrap 4 -->
  <script src="../utilitario/DataTables/datatables.min.js"></script>

  <script src="../js/console_usuario.js"></script>
  <script src="../js/console_area.js"></script>
  <script src="../js/console_tipodocumento.js"></script>
  <script src="../js/console_persona.js"></script>


  <!-- Select2 -->
  <script src="../plantilla/plugins/select2/js/select2.full.min.js"></script>

  <script>
    <?php if ($_SESSION['S_ROL'] == 'ADMINISTRADOR(A)') { ?>
      Traer_Widget();

      function Traer_Widget() {
        $.ajax({
          "url": "../controller/usuario/controlador_cargar_widget.php",
          type: 'POST',
        }).done(function(resp) {
          let data = JSON.parse(resp);
          if (data.length > 0) {
            document.getElementById('lbl_tramites').innerHTML = data[0][0]; // total de trámites
            document.getElementById('lbl_tramite_aceptado').innerHTML = data[0][1]; // aceptados
            document.getElementById('lbl_tramite_pendiente').innerHTML = data[0][2]; // pendientes
            document.getElementById('lbl_tramite_rechazado').innerHTML = data[0][3]; // rechazados
          }
        })
      }
    <?php } ?>

    <?php if ($_SESSION['S_ROL'] == 'SECRETARIO(A)') { ?>
      Traer_Widget_Area();

      function Traer_Widget_Area() {
        $.ajax({
          url: '../controller/usuario/controlador_cargar_widget_area.php',
          type: 'POST',
        }).done(function(resp) {
          let data = JSON.parse(resp);
          if (data.length > 0) {
            document.getElementById('lbl_tramites').innerHTML = data[0][0];
            document.getElementById('lbl_tramite_aceptado').innerHTML = data[0][1];
            document.getElementById('lbl_tramite_pendiente').innerHTML = data[0][2];
            document.getElementById('lbl_tramite_rechazado').innerHTML = data[0][3];
          }
        });
      }

      let prevPendientes = 0;

      setInterval(() => {
        $.ajax({
          url: '../controller/tramite_area/controlador_verificar_pendientes.php',
          method: 'POST',
          success: function(resp) {
            const data = JSON.parse(resp);
            if (data.nuevos > prevPendientes) {
              $('#badge_pendientes').text(data.nuevos).show();
              const audio = new Audio('../assets/sonidos/new_tramit.mp3');
              audio.play();
            }
            prevPendientes = data.nuevos;
          }
        });
      }, 5000);


      function abrirPendientes() {
        // Carga el módulo normalmente
        cargar_contenido('contenido_principal', 'tramite_area/view_tramite_area_pendientes.php');

        // Oculta el badge y reinicia el contador
        $('#badge_pendientes').hide().text('');
        notificados = 0;
      }

    <?php } ?>

    // Al hacer clic en cualquier enlace del sidebar, quitamos la clase 'active' de todos y se la asignamos solo al clickeado
    $(document).on('click', '.nav-sidebar a', function() {
      $('.nav-sidebar a').removeClass('active');
      $(this).addClass('active');
    });


    // script para carrusel de inicio (comunicado)
    document.addEventListener("DOMContentLoaded", () => {
      const slides = document.querySelectorAll('.carrusel-slide');
      let indice = 0;

      function actualizarCarrusel() {
        slides.forEach((slide, i) => {
          slide.classList.remove('prev', 'active', 'next');
          if (i === indice) {
            slide.classList.add('active');
          } else if (i === (indice - 1 + slides.length) % slides.length) {
            slide.classList.add('prev');
          } else if (i === (indice + 1) % slides.length) {
            slide.classList.add('next');
          }
        });
      }

      document.getElementById("next-btn").addEventListener("click", () => {
        indice = (indice + 1) % slides.length;
        actualizarCarrusel();
      });

      document.getElementById("prev-btn").addEventListener("click", () => {
        indice = (indice - 1 + slides.length) % slides.length;
        actualizarCarrusel();
      });

      actualizarCarrusel();
    });
  </script>

</body>

</html>