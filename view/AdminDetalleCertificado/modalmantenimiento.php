<div id="modalmantenimiento" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bd-0">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Seleccionar Usuarios : </h6>
            </div>
            <!-- Formulario Mantenimiento -->
            <div class="modal-body">
                <table id="usuario_data" class="table display responsive nowrap">
                    <thead>
                        <tr>
                        <th><input type="checkbox" id="seleccionar_todos"> Selecciona</th> <!-- Checkbox para seleccionar todos -->
                        <th class="wd-15p">Nombre</th>
                            <th class="wd-15p">Ape.Paterno</th>
                            <th class="wd-15p">Correo</th>
                            <th class="wd-15p">Curso</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button name="action" onclick="registrardetalle()" class="btn btn-outline-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium"><i class="fa fa-check"></i> Guardar</button>
                <button type="reset" class="btn btn-outline-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" aria-label="Close" aria-hidden="true" data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Cuando se cambia el estado del checkbox "seleccionar_todos"
    $('#seleccionar_todos').change(function() {
        // Obtenemos el estado actual del checkbox
        var isChecked = $(this).prop('checked');
        
        // Seleccionamos todos los checkboxes de las filas de la tabla
        $('#usuario_data tbody input[type="checkbox"]').each(function() {
            // Establecemos el estado de cada checkbox de acuerdo al checkbox "seleccionar_todos"
            $(this).prop('checked', isChecked);
        });
    });

    // Evento para deseleccionar "seleccionar_todos" si se desmarca un checkbox individual
    $('#usuario_data tbody').on('change', 'input[type="checkbox"]', function() {
        var allChecked = true;
        $('#usuario_data tbody input[type="checkbox"]').each(function() {
            if (!$(this).prop('checked')) {
                allChecked = false;
            }
        });
        $('#seleccionar_todos').prop('checked', allChecked);
    });
});
</script>
