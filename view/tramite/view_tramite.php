<script src="../js/console_tramite.js?rev=<?php echo time(); ?>"></script>
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
          <li class="breadcrumb-item"><i class="nav-icon fas fa-file-signature"></i>&nbsp; Trámites</li>
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
            <button class="btn btn-a bg-success float-right" data-toggle="modal" onclick="cargar_contenido('contenido_principal','tramite/view_tramite_registro.php')">
              <i class="nav-icon fas fa-plus"></i>&nbsp; Nuevo Registro </button>
          </div>
          <!-- /.card-body -->
          <div class="card-body">
            <div class="container">
              <div class="row justify-content-center align-items-end">
                <div class="col-md-2">
                  <label>Fecha Inicio</label>
                  <input type="date" id="reporte_fecha_inicio" class="form-control" onchange="tbl_tramite.ajax.reload();">
                </div>
                <div class="col-md-2">
                  <label>Fecha Fin</label>
                  <input type="date" id="reporte_fecha_fin" class="form-control" onchange="tbl_tramite.ajax.reload();">
                </div>
                <div class="col-md-2">
                  <label>Estado</label>
                  <select id="reporte_estado" class="form-control" onchange="tbl_tramite.ajax.reload();">
                    <option value="">Todos</option>
                    <option value="PENDIENTE">PENDIENTE</option>
                    <option value="ACEPTADO">ACEPTADO</option>
                    <option value="RECHAZADO">RECHAZADO</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label>Área</label>
                  <select class="js-example-basic-single" id="select_area_filtro" style="width:100%;" onchange="tbl_tramite.ajax.reload();"></select>
                </div>
                <div class="col-md-2 text-center">
                  <label>&nbsp;</label><br>
                  <button class="btn btn-outline-danger w-100" onclick="generarReporteFiltrado()">
                    <i class="fas fa-file-pdf">&nbsp;</i> Reporte Filtrado
                  </button>
                </div>
                <div class="col-md-2 text-center">
                  <label>&nbsp;</label><br>
                  <a target="_blank" class="btn btn-outline-dark w-100" href="MPDF/REPORTE/reporte_tramites.php">
                    <i class="fas fa-file-pdf">&nbsp;</i> Reporte General
                  </a>
                </div>
              </div>
            </div><br>
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
                  <div id="archivo_preview" style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                    <p>No hay archivo adjunto</p>
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
    listar_tramite();
    $('.js-example-basic-single').select2();
    $("#rad_presentacion1").on('click', function() {
      document.getElementById('div_juridico').style.display = "none";
    });
    $("#rad_presentacion2").on('click', function() {
      document.getElementById('div_juridico').style.display = "none";
    });
    $("#rad_presentacion3").on('click', function() {
      document.getElementById('div_juridico').style.display = "block";
    });
  });
  Cargar_select_Area_filtrado();
  Cargar_Select_Tipo();
  Cargar_select_Area();

  function generarReporteFiltrado() {
    const fi = $("#reporte_fecha_inicio").val();
    const ff = $("#reporte_fecha_fin").val();
    const estado = $("#reporte_estado").val();
    const area = $("#select_area_filtro").val();

    const url = `MPDF/REPORTE/reporte_tramites_filtro.php?fi=${fi}&ff=${ff}&estado=${estado}&area=${area}`;
    window.open(url, "_blank");
  }
</script>