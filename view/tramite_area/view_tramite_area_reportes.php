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
                                                <label>Nro. Expediente:</label>
                                                <input type="number" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label>DNI:</label>
                                                <input type="number" class="form-control" maxlength="8">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                                <button type="button" id="btnBusca" class="btn btn2 btn-danger"><i class="fa fa-search"></i>BUSCAR</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="FormBuscar">
                                <div class="form-group row justify-content-between">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="d-flex align-items-center">
                                                <label class="w-75 text-right mr-3">Nro Expediente:</label>
                                                <input type="email" class="form-control" id="idexpb" onkeypress="return validaNumericos(event)" maxlength="6">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="d-flex align-items-center">
                                                <label class="w-75 text-right mr-3">DNI:</label>
                                                <input type="email" class="form-control" id="iddnii" onkeypress="return validaNumericos(event)" maxlength="8">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div>
                                                <button type="button" id="btnBusca" class="btn btn2 btn-danger"><i class="fa fa-search"></i>BUSCAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    </div>