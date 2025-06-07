<script src="../js/console_area.js?rev=<?php echo time(); ?>"></script>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-11">
                <h1 class="m-0">
                    <center><b>MANTENIMIENTO DE ÁREA</b></center>
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-1">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-file-signature"></i>&nbsp; Área</li>
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
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title"> <b> Listado de Areas</b></h5>
                        <button id="btn" class="btn btn-danger btn-sm float-right" onclick="AbrirRegistro()"> <i class="fas fa-plus"></i> Nuevo registro</button>
                    </div>
                    <div class="card-body">
                        <a href="MPDF/REPORTE/reporte_areas.php" target="_blank" class="btn btn-a bg-gray-dark">
                            <i class="fas fa-file-pdf"></i> Generar Reporte
                        </a><br><br>
                        <table id="tabla_area" class="table table-data" style="width:100%" border="1px">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Area</th>
                                    <th>Fecha de Registro</th>
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

<!-- Modal Registrar -->
<div class="modal fade" id="modal_registro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">REGISTRO DE AREA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="">AREA</label>
                        <input type="text" class="form-control" id="txt_area">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="Registrar_Area()">REGISTRAR</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Area -->
<div class="modal fade" id="modal_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">EDITAR DATOS DE AREA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="">AREA</label>
                        <input type="text" class="form-control" id="txt_area_editar">
                        <input type="text" id="txt_idarea" hidden>
                    </div>
                    <div class="col-12">
                        <label for="">Estado</label>
                        <select name="" id="select_estado" class="form-control select2" style="width:100%">
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="Modificar_Area()">MODIFICAR</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tabla_area').DataTable();
        listar_area();
    });

    $('#modal_registro').on('shown.bs.modal', function() {
        $('#txt_area').trigger('focus')
    })
</script>