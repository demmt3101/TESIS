<!-- modalplantilla.php -->
<div id="modalplantilla" class="modal fade">
  <div class="modal-dialog modal-lg">
    <form method="post" id="form_upload_excel" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Carga Masiva de Usuarios</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <label>Seleccionar archivo Excel</label>
          <input type="file" name="file" id="file" class="form-control" accept=".xls, .xlsx" required />
        </div>
        <div class="modal-footer">
          <button type="submit" name="upload" id="upload" class="btn btn-success">Subir</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>
