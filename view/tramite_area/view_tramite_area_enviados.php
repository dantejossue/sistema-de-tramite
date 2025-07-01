<?php
session_start();

date_default_timezone_set('America/Lima');
?>
<script src="../js/console_tramite_area_enviados.js?rev=<?php echo time(); ?>"></script>
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
          <li class="breadcrumb-item"><i class="nav-icon fas fa-paper-plane"></i>&nbsp; T. Enviados</li>
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
        <div class="card card-danger card-outline">
          <!-- /.card-header -->
          <div class="card-header">
            <h3 class="card-title card-header-title" style="font-size: 1.3rem;"><b>Listado de Trámites</b></h3>
            <!-- <div style="float:right;">
              <label>Listar por: </label>
              <select class="select-reporte select-info" id="">
                <option value="">Todos</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="ACEPTADO">ACEPTADO</option>
                <option value="RECHAZADO">RECHAZADO</option>
                <option value="ARCHIVADO">ARCHIVADO</option>
              </select>
            </div> -->
          </div>
          <!-- /.card-body -->
          <div class="card-body">
            <!-- <a Target="_blank" class="btn btn-flat btn-a bg-gray-dark" href="MPDF/REPORTE/reporte_tramite_area.php?usu_id= id="ReportUsu">
              <i class="nav-iconfas fas fa-file-pdf"></i>&nbsp; Generar Reporte </a> <br><br> -->
            <div class="row justify-content-center mb-3">
              <div class="col-md-2">
                <label>Fecha Inicio</label>
                <input type="date" id="reporte_fecha_inicio" class="form-control" onchange="tbl_tramite.ajax.reload();">
              </div>
              <div class="col-md-2">
                <label>Fecha Fin</label>
                <input type="date" id="reporte_fecha_fin" class="form-control" onchange="tbl_tramite.ajax.reload();">
              </div>
              <div class="col-md-2 text-center">
                <label>&nbsp;</label><br>
                <button class="btn btn-outline-danger w-100" onclick="generarReporteFiltrado()">
                  <i class="fas fa-file-pdf">&nbsp;</i> Reporte Filtrado
                </button>
              </div>
              <div class="col-md-2 text-center">
                <label>&nbsp;</label><br>
                <a target="_blank" class="btn btn-outline-dark w-100" href="MPDF/REPORTE/reporte_tramite_area.php?usu_id=<?= $_SESSION['S_ID']; ?>">
                  <i class="fas fa-file-pdf">&nbsp;</i> Reporte General
                </a>
              </div>
            </div>
            <table id="tabla_tramite" class="table table-hover table-data">
              <thead>
                <tr>
                  <th rowspan="2" class="align-middle">Nro Seguimiento</th>
                  <th rowspan="2" class="align-middle">Nro Documento</th>
                  <th rowspan="2" class="align-middle">Tipo Documento</th>
                  <th colspan="2" class="align-middle">Remitente</th>
                  <th colspan="2" class="align-middle">Localización</th>
                  <th rowspan="2" class="align-middle">Mas datos</th>
                  <th rowspan="2" class="align-middle">Seguimiento</th>
                  <th rowspan="2" class="align-middle">Estado Documento</th>
                </tr>
                <tr>
                  <th>DNI</th>
                  <th>Datos</th>
                  <th>Área Origen</th>
                  <th>Área Actual</th>
                </tr>
              </thead>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->

</div>
<!-- /.content -->

<!-- MODAL MAS INFORMACION-->
<div class="modal fade" id="modal_mas">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" id="modal-header">
        <h4 class="modal-title modal-title-h4" id="modal-title">Datos del Trámite</h4>&nbsp;&nbsp;
        <h4 id="nro_expe" class="modal-title modal-title-h4" style="color: brown;"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12 col-sm-12">
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">Datos del Documento</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="true">Datos del Remitente</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Archivo</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                  <div class="row">
                    <div class="col-12">
                      <div class="col-12 form-group">
                        <label for="" style="font-size:small;">PROCEDENCIA DEL DOCUMENTO</label>
                        <select class="js-example-basic-single" id="select_area_p" style="width:100%" disabled></select>
                      </div>
                      <div class="col-12 form-group">
                        <label for="" style="font-size:small;">AREA DE DESTINO</label>
                        <select class="js-example-basic-single" id="select_area_d" style="width:100%" disabled>
                        </select>
                      </div>
                      <div class="col-12 form-group">
                        <label for="" style="font-size:small;">TIPO DOCUMENTO</label>
                        <select class="js-example-basic-single" id="select_tipo" style="width:100%" disabled></select>
                      </div>
                      <div class="col-12">
                        <div class="row">
                          <div class="col-6 form-group">
                            <label for="" style="font-size:small;">Nº DOCUMENTO</label>
                            <input type="text" class="form-control" id="txt_ndocumento" disabled></input>
                          </div>
                          <div class="col-6 form-group">
                            <label for="" style="font-size:small;">Nº FOLIO</label>
                            <input type="text" class="form-control" id="txt_folio" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 form-group">
                        <label for="" style="font-size:small;">ASUNTO DEL TRAMITE</label>
                        <textarea id="txt_asunto" rows="3" class="form-control" style="resize:none" placeholder="INGRESE EL ASUNTO DEL DOCUMENTO" disabled></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                  <div class="row">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-6 form-group">
                          <label for="" style="font-size:small;">Nº DNI</label>
                          <input type="text" class="form-control" id="txt_dni" maxlength="8" disabled>
                        </div>
                        <div class="col-6 form-group">
                          <label for="" style="font-size:small;">NOMBRE</label>
                          <input type="text" class="form-control" id="txt_nom" disabled>
                        </div>
                        <div class="col-6 form-group">
                          <label for="" style="font-size:small;">APELLIDO PATERNO</label>
                          <input type="text" class="form-control" id="txt_apepat" disabled>
                        </div>
                        <div class="col-6 form-group">
                          <label for="" style="font-size:small;">APELLIDO MATERNO</label>
                          <input type="text" class="form-control" id="txt_apemat" disabled>
                        </div>
                        <div class="col-6 form-group">
                          <label for="" style="font-size:small;">CELULAR</label>
                          <input type="text" class="form-control" id="txt_celular" disabled>
                        </div>
                        <div class="col-6 form-group">
                          <label for="" style="font-size:small;">EMAIL</label>
                          <input type="text" class="form-control" id="txt_email" disabled>
                        </div>
                        <div class="col-12 form-group">
                          <label for="" style="font-size:small;">DIRECCION</label>
                          <input type="text" class="form-control" id="txt_dire" disabled>
                        </div>
                        <div class="col-12">
                          <label for="" style="font-size:small;">EN REPRESENTACION</label>
                        </div>
                        <div class="col-12 row">
                          <div class="col-4 form-group clearfix">
                            <div class="icheck-success d-inline">
                              <input type="radio" name="r1" checked value="A Nombre Propio" id="rad_presentacion1" disabled>
                              <label for="rad_presentacion1" style="font-weight:normal;font-size:samll">A Nombre Propio</label>
                            </div>
                          </div>
                          <div class="col-4 form-group clearfix">
                            <div class="icheck-success d-inline">
                              <input type="radio" name="r1" value="A Otra Persona Natural" id="rad_presentacion2" disabled>
                              <label for="rad_presentacion2" style="font-weight:normal;font-size:samll">A Otra Persona Natural</label>
                            </div>
                          </div>
                          <div class="col-4 form-group clearfix">
                            <div class="icheck-success d-inline">
                              <input type="radio" name="r1" value="Persona Jurídica" id="rad_presentacion3" disabled>
                              <label for="rad_presentacion3" style="font-weight:normal;font-size:samll">Persona Jurídica</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-12" id="div_juridico" style="display:none">
                          <div class="row">
                            <div class="col-4 form-group">
                              <label for="" style="font-size:small;">RUC</label>
                              <input type="text" class="form-control" id="txt_ruc" disabled>
                            </div>
                            <div class="col-8 form-group">
                              <label for="" style="font-size:small;">RAZON SOCIAL</label>
                              <input type="text" class="form-control" id="txt_razon" disabled>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                  <div id="tab_archivo" style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                    <!-- <p>No hay archivo adjunto</p> -->
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>



<!-- MODAL SEGUIMIENTO-->
<div class="modal fade" id="modalseguir">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" id="modal-header">
        <h4 class="modal-title modal-title-h4" id="modal-title">Seguimiento del Trámite: Expediente</h4>&nbsp;&nbsp;
        <h4 id="nro_expe1" class="modal-title modal-title-h4" style="color: brown;"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="tablaSeguimiento" class="table table-hover table-data">
          <thead>
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Procedencia</th>
              <th>Descripción</th>
            </tr>
          </thead>
          <tbody>
          </tbody>

          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Procedencia</th>
              <th>Descripción</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn1 btn-success" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>
  $(document).ready(function() {
    // Primero carga los select tipo, luego área, y luego la tabla
    Cargar_Select_Tipo(function() {
      Cargar_select_Area(function() {
        listar_tramite(); // Solo aquí se ejecuta la tabla
      });
    });

    // Activa select2
    $('.js-example-basic-single').select2();

    // Cambios en las fechas
    $('#reporte_fecha_inicio, #reporte_fecha_fin').change(function() {
      if (typeof tbl_tramite !== 'undefined') {
        tbl_tramite.ajax.reload(); // Solo si está definido
      }
    });
  });

  function generarReporteFiltrado() {
    let fechaInicio = $("#reporte_fecha_inicio").val();
    let fechaFin = $("#reporte_fecha_fin").val();
    let estado = $("#select-vista-estado").val();
    let usuId = <?= $_SESSION['S_ID']; ?>; // Suponiendo que tienes el ID de usuario en la sesión

    // Crear la URL del reporte con los filtros aplicados
    let url = `MPDF/REPORTE/reporte_tramite_area_filtrado.php?usu_id=${usuId}&fi=${fechaInicio}&ff=${fechaFin}&estado=${estado}`;

    // Redirigir al usuario al reporte
    window.open(url, '_blank');
  }
</script>