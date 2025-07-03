<!-- INICIO DEL CONTENIDO -->
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
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-search-minus"></i>&nbsp; Búsqueda</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary" id="insert">
                    <div class="card-header">
                        <h3 class="card-title font-w-600 d-flex-gap"><i class="fas fa-search"></i>BÚSQUEDA DE EXPEDIENTES</h3>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <p>*Para realizar la búsqueda de un documento presentado debe de ingresar el
                            Número de Expediente del Documento y seleccionar el año de presentación:</p>
                        <br>
                        <div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Nro. Expediente:</label><label class="span-red">(*)</label>
                                                <input id="txt_numero" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label>Nro. DNI:</label><label class="span-red">(*)</label>
                                                <input type="text" id="txt_dni" class="form-control" maxlength="8" onkeypress="return soloNumeros(event)">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <label for="">&nbsp;</label><br>
                                            <button class="btn btn-danger" style="width:100%" onclick="Traer_Datos_Seguimiento()"><i class="fa fa-search"></i>&nbsp; Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    </div>


    <script src="/js/console_usuario.js?rev=<?php echo time(); ?>"></script>
    <script>
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