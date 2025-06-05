<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MesaPartesVirtual</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../plantilla/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../plantilla/dist/css/adminlte.css">

    <link rel="icon" href="../assets/img/logo mixto.jpg">

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php"><img src="../assets/img/logo mixto.jpg" alt="logo"></a></li>
                <li>
                    <h2><b>INSTITUCION EDUCATIVA 21001</b></h2>
                </li>
                <li><a id="btn-regresar" class="btn btn-sm btn-success" href="../"><b>Ir a login</b></a></li>
            </ul>
        </nav>
    </header>
    <div class="main">
        <!-- /.col-md-8 -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0"><b>MESA DE PARTES VIRTUAL</b></h5>
                </div>
                <section class="content">
                    <a id="seguimiento" class="btn btn-primary"><i class="fas fa-search"></i>&nbsp; Seguimiento</a>
                    <div class="container-fluid">
                        <div id="fila1" class="row">
                            <!-- left column -->
                            <div class="col-md-6">
                                <!-- general form elements -->
                                <div id="card1" class="card card-info">
                                    <div id="card-header" class="card-header">
                                        <h3 id="titulo1" class="card-title"><b> DATOS DEL REMITENTE</b></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- formulario para la mesa de partes -->
                                    <form>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label> Tipo de persona: </label><span class="span-red"> (*)</span>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input type="radio" id="rd1" name="rd" checked><label>&nbsp; Natural</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="radio" id="rd2" name="rd" checked><label>&nbsp; Jurídica</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div id="" class="row">
                                                    <div class="col-sm-4">
                                                        <label>DNI</label><span class="span-red"> (*)</span>
                                                        <input type="text" class="form-control" maxlength="8" minlength="8">
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div id="btn-validar">
                                                            <input id="" type="button" class="btn btn-success" value="Validar">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Nombres </label><span class="span-red"> (*)</span>
                                                        <input type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="">Apellido Paterno</label><span class="span-red"> (*)</span>
                                                        <input type="text" class="form-control" id="exampleInputPassword1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="">Apellido Materno</label><span class="span-red"> (*)</span>
                                                        <input type="text" class="form-control" id="exampleInputPassword1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">N° Celular</label><span class="span-red"> (*)</span>
                                                <input type="number" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Dirección</label><span class="span-red"> (*)</span>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Correo</label><span class="span-red"> (*)</span>
                                                <input type="email" class="form-control">
                                            </div>
                                            <div>
                                                <span class="span-red">Campos Obligatorios (*)</span>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- general form elements -->
                                <div id="card2" class="card card-maroon">
                                    <div id="card-header" class="card-header">
                                        <h3 id="titulo1" class="card-title"><b> DATOS DEL TRÁMITE</b></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <!-- form start -->
                                    <form>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Tipo de documento</label><span class="span-red"> (*)</span>
                                                <select name="" id="" class="form-control">
                                                    <option value="informe" class="form-control">INFORME</option>
                                                    <option value="informe" class="form-control">SOLICITUD</option>
                                                    <option value="informe" class="form-control">MEMORANDUM</option>
                                                    <option value="informe" class="form-control">OFICIO</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="">N° Documento</label><span class="span-red"> (*)</span>
                                                        <input type="number" class="form-control" id="exampleInputPassword1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="">N° Folios</label><span class="span-red"> (*)</span>
                                                        <input type="number" class="form-control" id="exampleInputPassword1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Asunto</label><span class="span-red"> (*)</span>
                                                <textarea id="asunto" class="form-control" placeholder="INGRESE EL ASUNTO DEL DOCUMENTO"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Adjuntar el Archivo PDF.</label><span class="span-red"> (*)</span><br>
                                                <input type="file"><br><br>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox">
                                                <span>&nbsp; Declaro que la información proporcionada es válida y verídica. Y Acepto que las comunicaciones sean enviadas a la dirección de corre y celular que proporcione.</span><span class="span-red"> (*)</span>
                                            </div>
                                            <input type="submit" class="btn-enviarTramite" value="Enviar Trámite">
                                        </div>
                                        <!-- /.card-body -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>


        <!-- jQuery -->
        <script src="../plantilla/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../plantilla/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../plantilla/dist/js/adminlte.min.js"></script>
</body>

</html>