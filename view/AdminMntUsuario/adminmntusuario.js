var usu_id = $('#usu_idx').val();

function init() {
    $("#usuario_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#usuario_form")[0]);
    $.ajax({
        url: "../../controller/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#usuario_data').DataTable().ajax.reload();
            $('#modalmantenimiento').modal('hide');

            Swal.fire({
                title: 'Correcto!',
                text: 'Se Registró Correctamente',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            })
        }
    });
}

$(document).ready(function () {
    $('#rol_id').select2({
        dropdownParent: $('#modalmantenimiento')
    });

    $('#usuario_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controller/usuario.php?op=listar",
            type: "post"
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
    });

});

function editar(usu_id) {
    $.post("../../controller/usuario.php?op=mostrar", { usu_id: usu_id }, function (data) {
        console.log(data);
        data = JSON.parse(data);
        $('#usu_id').val(data.usu_id);
        $('#usu_nom').val(data.usu_nom);
        $('#usu_apep').val(data.usu_apep);
        $('#usu_apem').val(data.usu_apem);
        $('#usu_correo').val(data.usu_correo);
        $('#usu_pass').val(data.usu_pass);
        $('#usu_sex').val(data.usu_sex).trigger('change');
        $('#rol_id').val(data.rol_id).trigger('change');
        $('#usu_telf').val(data.usu_telf);
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
}

function eliminar(usu_id) {
    Swal.fire({
        title: 'Está seguro de eliminar el usuario?',
        text: 'No podrá revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/usuario.php?op=eliminar", { usu_id: usu_id }, function (data) {
                $('#usuario_data').DataTable().ajax.reload();

                Swal.fire(
                    'Eliminado!',
                    'El usuario ha sido eliminado.',
                    'success'
                );
            });
        }
    });
}

function nuevo() {
    $('#usu_id').val('');
    $('#usu_sex').val('').trigger('change');
    $('#rol_id').val('').trigger('change');
    $('#lbltitulo').html('Nuevo Registro');
    $('#usuario_form')[0].reset();
    $('#modalmantenimiento').modal('show');
}

$(document).on("click", "#btnplantilla", function () {
    $('#modalplantilla').modal('show');
});


function uploadFile() {
    var formData = new FormData($('#formUploadFile')[0]);

    $.ajax({
        url: 'procesar_archivo.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            // Procesar la respuesta según sea necesario
        },
        error: function (error) {
            console.error('Error al subir archivo:', error);
            // Manejar errores, por ejemplo, mostrar un mensaje de error al usuario
        }
    });
}

$(document).ready(function () {
    $('#form_upload_excel').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "../../controller/usuario.php?op=import_excel",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                alert(data);
                $('#file').val('');
                $('#modalplantilla').modal('hide');
                $('#usuario_data').DataTable().ajax.reload();
            }
        });
    });
});

var ExcelToJSON = function () {
    this.parseExcel = function (file) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            //TODO: Recorrido a todas las pestañas
            workbook.SheetNames.forEach(function (sheetName) {
                // Here is your object
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                var json_object = JSON.stringify(XL_row_object);
                UserList = JSON.parse(json_object);

                console.log(UserList)
                for (i = 0; i < UserList.length; i++) {

                    var columns = Object.values(UserList[i])

                    $.post("../../controller/usuario.php?op=guardar_desde_excel", {
                        usu_nom: columns[0],
                        usu_apep: columns[1],
                        usu_apem: columns[2],
                        usu_correo: columns[3],
                        usu_pass: columns[4],
                        usu_sex: columns[5],
                        usu_telf: columns[6],
                        rol_id: columns[7],
                    }, function (data) {
                        console.log(data);
                    });

                }
                /* TODO:Despues de subir la informacion limpiar inputfile */
                document.getElementById("upload").value = null;

                /* TODO: Actualizar Datatable JS */
                $('#usuario_data').DataTable().ajax.reload();
                $('#modalplantilla').modal('hide');
            })
        };
        reader.onerror = function (ex) {
            console.log(ex);
        };

        reader.readAsBinaryString(file);
    };
};

function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object
    var xl2json = new ExcelToJSON();
    xl2json.parseExcel(files[0]);
}

document.getElementById('upload').addEventListener('change', handleFileSelect, false);



init();

