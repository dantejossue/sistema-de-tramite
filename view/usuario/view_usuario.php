<head>
    <link rel="stylesheet" href="../assets/css/select.css">
</head>


<script src="../js/console_usuario.js?rev=<?php echo time(); ?>"></script>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-11">
                <h1 class="m-0">
                    <center><b>GESTIÓN DE USUARIOS</b></center>
                </h1> 
            </div><!-- /.col -->
            <div class="col-sm-1">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-users"></i>&nbsp; Usarios</li>
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
            <!-- /.col-md-6 -->
            <div class="col-lg-12">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="card-title card-header-title" style="font-size: 1.3rem;"> <b> Listado de Usuarios</b></h5>
                        <button id="btn" class="btn btn-a bg-success float-right" data-toggle="modal" onclick="AbrirRegistro()"> <i class="fas fa-plus"></i> Nuevo registro</button>
                    </div>
                    <div class="card-body">
                        <a Target="_blank" class="btn btn-a bg-gray-dark" href="MPDF/REPORTE/reporte_usuarios.php" id="ReportUsu">
                            <i class="nav-iconfas fas fa-file-pdf"></i>&nbsp; Generar Reporte </a> <br><br>
                        <table id="tabla_usuario" class="ttable table-hover table-data" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Area</th>
                                    <th>Rol</th>
                                    <th>Persona</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->


<!-- Modal REGISTRAR usuario -->
<div class="modal fade" id="modal_registro" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-h4" id="staticBackdropLabel">REGISTRO DE USUARIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <label for="">USUARIO</label>
                        <input type="text" class="form-control" id="txt_usu">
                    </div>
                    <div class="col-6">
                        <label for="">CONTRASEÑA</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="txt_con">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="">PERSONA</label>
                        <select class="form-control select2" id="select_persona" style="width:100%"></select><br><br>
                    </div>
                    <div class="col-6">
                        <label for="">AREA</label>
                        <select class="form-control select2" id="select_area" style="width:100%"></select>
                    </div>
                    <div class="col-6">
                        <label for="">ROL</label>
                        <select class="form-control select2" id="select_rol" style="width:100%">
                            <option value="SECRETARIO(A)">SECRETARIO(A)</option>
                            <option value="ADMINISTRADOR(A)">ADMINISTRADOR(A)</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="Registrar_Usuario()">REGISTRAR</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal EDICION usuario -->
<div class="modal fade" id="modal_editar" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-h4" id="staticBackdropLabel">EDITAR DATOS DE USUARIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="">USUARIO</label>
                        <input type="text" class="form-control" id="txt_usu_editar" disabled><br>
                        <input type="text" id="txt_idusuario" hidden>
                    </div>
                    <div class="col-12">
                        <label for="">PERSONA</label>
                        <select class="form-control select2" id="select_persona_editar" style="width:100%"></select><br><br>
                    </div>
                    <div class="col-6">
                        <label for="">AREA</label>
                        <select class="form-control select2" id="select_area_editar" style="width:100%"></select>
                    </div>
                    <div class="col-6">
                        <label for="">ROL</label>
                        <select class="form-control select2" id="select_rol_editar" style="width: 100%;">
                            <option value="SECRETARIO(A)">SECRETARIO(A)</option>
                            <option value="ADMINISTRADOR(A)">ADMINISTRADOR(A)</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="Modificar_Usuario()">MODIFICAR</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar la contraseña del usuario -->
<div class="modal fade" id="modal_contra" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-h4" id="staticBackdropLabel">EDITAR CONTRASEÑA DE USUARIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label>CONTRASEÑA ACTUAL</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="txt_usuario_contra" disabled>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="">NUEVA CONTRASEÑA</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="txt_contra_nueva">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <input type="text" id="txt_idusuario_contra" hidden>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="Modificar_Usuario_Contra()">MODIFICAR</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword1').addEventListener('click', function(e) {
        // Toggle the type attribute of the password field
        const passwordField = document.getElementById('txt_con');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        // Toggle the icon
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePassword').addEventListener('click', function(e) {
        // Toggle the type attribute of the password field
        const passwordField = document.getElementById('txt_contra_nueva');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        // Toggle the icon
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('togglePassword2').addEventListener('click', function(e) {
        // Toggle the type attribute of the password field
        const passwordField = document.getElementById('txt_usuario_contra');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        // Toggle the icon
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
</script>


<script>
    $(document).ready(function() {
        listar_usuario();
        $('.select2').select2();
        Cargar_select_Persona();
        Cargar_select_Area();
    });
</script>