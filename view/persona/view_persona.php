    <script src="../js/console_persona.js?rev=<?php echo time(); ?>"></script>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">MANTENIMIENTO DE LOS PERSONALES</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Personal</li>
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
                <h5 class="card-title"> <b> Listado de Personales</b></h5>
                <button id="btn" class="btn btn-danger btn-sm float-right" onclick="AbrirRegistro()"> <i class="fas fa-plus"></i> Nuevo registro</button>
              </div>
              <div class="card-body">
                <table id="tabla_persona" class="table table-hover table-data" style="width:100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Foto</th>
                      <th>DNI</th>
                      <th>Personal</th>
                      <th>Celular</th>
                      <th>Email</th>
                      <th>Dirección</th>
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
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">REGISTRO DEL PERSONAL</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-4">
                <label for="">Nro Documento</label>
                <input type="text" class="form-control" maxlength="8" id="txt_nro" onkeypress="return soloNumeros(event)">
              </div>
              <div class="col-8">
                <label for="">Nombres</label>
                <input type="text" class="form-control" id="txt_nom" onkeypress="return sololetras(event)">
              </div>
              <div class="col-6">
                <label for="">Apellido Paterno</label>
                <input type="text" class="form-control" id="txt_apepa" onkeypress="return sololetras(event)">
              </div>
              <div class="col-6">
                <label for="">Apellido Materno</label>
                <input type="text" class="form-control" id="txt_apema" onkeypress="return sololetras(event)">
              </div>
              <div class="col-6">
                <label for="">Fecha Nacimiento</label>
                <input type="date" class="form-control" id="txt_fnac">
              </div>
              <div class="col-6">
                <label for="">Movil</label>
                <input type="text" class="form-control" id="txt_movil" onkeypress="return soloNumeros(event)">
              </div>
              <div class="col-12">
                <label for="">Dirección</label>
                <input type="text" class="form-control" id="txt_dire">
              </div>
              <div class="col-12">
                <label for="">Email</label>
                <input type="text" class="form-control" id="txt_email">
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" onclick="Registrar_Persona()">REGISTRAR</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for Editing Area -->
    <div class="modal fade" id="modal_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">EDITAR DATOS DEL PERSONAL</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-4">
                <input type="text" id="txt_idpersona" hidden>
                <label for="">Nro Documento</label>
                <input type="text" class="form-control" maxlength="8" id="txt_nro_editar" onkeypress="return soloNumeros(event)">
                <span id="error_dni" style="color: red; font-size: 14px;"></span>
              </div>
              <div class="col-8">
                <label for="">Nombres</label>
                <input type="text" class="form-control" id="txt_nom_editar" onkeypress="return sololetras(event)">
              </div>
              <div class="col-6">
                <label for="">Apellido Paterno</label>
                <input type="text" class="form-control" id="txt_apepa_editar" onkeypress="return sololetras(event)">
              </div>
              <div class="col-6">
                <label for="">Apellido Materno</label>
                <input type="text" class="form-control" id="txt_apema_editar" onkeypress="return sololetras(event)">
              </div>
              <div class="col-6">
                <label for="">Fecha Nacimiento</label>
                <input type="date" class="form-control" id="txt_fnac_editar">
              </div>
              <div class="col-6">
                <label for="">Movil</label>
                <input type="text" class="form-control" id="txt_movil_editar" onkeypress="return soloNumeros(event)">
              </div>
              <div class="col-12">
                <label for="">Dirección</label>
                <input type="text" class="form-control" id="txt_dire_editar">
              </div>
              <div class="col-8">
                <label for="">Email</label>
                <input type="text" class="form-control" id="txt_email_editar">
              </div>
              <div class="col-4">
                <label for="">Estado</label>
                <select name="" id="select_estatus_editar" class="form-control">
                  <option value="ACTIVO">ACTIVO</option>
                  <option value="INACTIVO">INACTIVO</option>
                </select>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" onclick="Modificar_Persona()">MODIFICAR</button>
          </div>
        </div>
      </div>
    </div>


    <!-- MODAL FOTO-->
    <div class="modal fade" id="modalfoto">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title modal-title-h4">CAMBIO DE FOTO DE PERFIL:</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form>
            <div class="modal-body modal-body-center">
              <h1 class="modal-body-title">Foto de perfil Actual</h1>
              <img class="modal-photo" id="foto_actual">
              <br><br>
              <div class="form-group">
                <label>Elegir Foto</label><span class="span-red"> (*)</span>
                <input type="hidden" id="persona_id">
                <input type="file" id="nueva_foto">

              </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn1 btn-danger" data-dismiss="modal">Cancelar </button>
              <button type="button" class="btn btn1 btn-primary" onclick="subirFoto()">Cambiar</button>
            </div>
          </form>
        </div>
      </div>
    </div>



    <script>
      $(document).ready(function() {
        listar_persona();
      });
      $('#modal_registro').on('shown.bs.modal', function() {
        $('#txt_nro').trigger('focus')
      });
      $('#modal_editar').on('shown.bs.modal', function() {
        $('#txt_idpersona').trigger('focus')
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>