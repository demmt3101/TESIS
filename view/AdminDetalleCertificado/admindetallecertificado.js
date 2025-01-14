var usu_id = $("#usu_idx").val();

function init() {}

$(document).ready(function () {
  $("#cur_id").select2();

  combo_curso();

  /* Obtener Id de combo curso */
  $("#cur_id").change(function () {
    $("#cur_id option:selected").each(function () {
      cur_id = $(this).val();

      /* Listado de datatable */
      $("#detalle_data").DataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5"],
        ajax: {
          url: "../../controller/usuario.php?op=listar_cursos_usuario",
          type: "post",
          data: { cur_id: cur_id },
        },
        bDestroy: true,
        responsive: true,
        bInfo: true,
        iDisplayLength: 10,
        order: [[0, "desc"]],
        language: {
          sProcessing: "Procesando...",
          sLengthMenu: "Mostrar _MENU_ registros",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sInfo:
            "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          sInfoEmpty:
            "Mostrando registros del 0 al 0 de un total de 0 registros",
          sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
          sInfoPostFix: "",
          sSearch: "Buscar:",
          sUrl: "",
          sInfoThousands: ",",
          sLoadingRecords: "Cargando...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
          },
          oAria: {
            sSortAscending:
              ": Activar para ordenar la columna de manera ascendente",
            sSortDescending:
              ": Activar para ordenar la columna de manera descendente",
          },
        },
      });
    });
  });
});

$(document).ready(function () {
  $("#btnDescargaMasiva").click(async function () {
    // Obtener el valor seleccionado del combo
    const cur_id = $("#cur_id").val();

    // Mostrar el valor en la consola
    console.log("Valor seleccionado:", cur_id);

    var curd_ids = [];

    try {
      // Realizar la consulta AJAX para obtener los curd_ids
      let response = await $.ajax({
        url: "../../controller/usuario.php?op=listar_cursos_detalle_usuario",
        type: "POST",
        data: { cur_id: cur_id }, // ID del curso que quieres consultar
        dataType: "json",
      });

      // Verificar la estructura de la respuesta
      console.log("Respuesta del servidor:", response);

      // Verificar si la respuesta contiene datos
      if (response && response.length > 0) {
        curd_ids = response; // Aquí ya tienes el array con los curd_ids
      } else {
        console.log("No hay registros para generar certificados");
        return;
      }
    } catch (error) {
      console.error("Error al obtener los curd_ids:", error);
    }

    // Crear un ZIP
    var zip = new JSZip();
    var pdfFolder = zip.folder("certificados");

    // Procesar cada ID para generar el PDF
    for (let i = 0; i < curd_ids.length; i++) {
      let curd_id = curd_ids[i];

      // Llamar a la API para obtener los datos
      let response = await $.post(
        "../../controller/usuario.php?op=mostrar_curso_detalle",
        { curd_id: curd_id }
      );
      let data = JSON.parse(response);

      // Crear un canvas para generar la imagen
      let canvas = document.createElement("canvas");
      let ctx = canvas.getContext("2d");
      canvas.width = 800;
      canvas.height = 600;

      // Dibujar la imagen base
      let image = new Image();
      image.src = data.cur_img;

      await new Promise((resolve) => {
        image.onload = function () {
          ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

          // Añadir texto al canvas
          ctx.font = "40px Arial";
          ctx.textAlign = "center";
          ctx.textBaseline = "middle";
          let x = canvas.width / 2;
          ctx.fillText(
            data.usu_nom + " " + data.usu_apep + " " + data.usu_apem,
            x,
            250
          );

          ctx.font = "30px Arial";
          ctx.fillText(data.cur_nom, x, 320);

          ctx.font = "18px Arial";
          ctx.fillText(
            data.inst_nom + " " + data.inst_apep + " " + data.inst_apem,
            x,
            420
          );
          ctx.font = "15px Arial";
          ctx.fillText("Instructor", x, 450);

          ctx.font = "15px Arial";
          ctx.fillText(
            "Fecha de Inicio : " +
              data.cur_fechini +
              " / " +
              "Fecha de Finalización : " +
              data.cur_fechfin,
            x,
            490
          );

          resolve();
        };
      });

      // Convertir canvas a imagen
      var imgData = canvas.toDataURL("image/png");
      const { jsPDF } = window.jspdf;
      let doc = new jsPDF("l", "mm", "a4");

      // Dimensiones del PDF
      const pdfWidth = doc.internal.pageSize.getWidth();
      const pdfHeight = doc.internal.pageSize.getHeight();

      // Escalar la imagen al 90% del tamaño del PDF
      const imgWidth = pdfWidth * 0.9;
      const imgHeight = (canvas.height / canvas.width) * imgWidth;

      // Calcular posición centrada
      const xOffset = (pdfWidth - imgWidth) / 2;
      const yOffset = (pdfHeight - imgHeight) / 2;

      // Añadir imagen al PDF
      doc.addImage(imgData, "PNG", xOffset, yOffset, imgWidth, imgHeight);

      // Añadir el PDF al ZIP
      var pdfData = doc.output("blob");
      pdfFolder.file(`Certificado_${curd_id}.pdf`, pdfData);
    }

    // Generar y descargar el ZIP
    zip.generateAsync({ type: "blob" }).then(function (content) {
      saveAs(content, "Certificados.zip");
    });
  });
});

function eliminar(curd_id) {
  swal
    .fire({
      title: "Eliminar!",
      text: "Desea Eliminar el Registro?",
      icon: "error",
      confirmButtonText: "Si",
      showCancelButton: true,
      cancelButtonText: "No",
    })
    .then((result) => {
      if (result.value) {
        $.post(
          "../../controller/curso.php?op=eliminar_curso_usuario",
          { curd_id: curd_id },
          function (data) {
            $("#detalle_data").DataTable().ajax.reload();

            Swal.fire({
              title: "Correcto!",
              text: "Se Elimino Correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            });
          }
        );
      }
    });
}

function combo_curso() {
  $.post("../../controller/curso.php?op=combo", function (data) {
    $("#cur_id").html(data);
  });
}

function certificado(curd_id) {
  console.log(curd_id);
  window.open("../Certificado/index.php?curd_id=" + curd_id + "", "_blank");
}

function nuevo() {
  if ($("#cur_id").val() == "") {
    Swal.fire({
      title: "Error!",
      text: "Seleccionar Curso",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
  } else {
    var cur_id = $("#cur_id").val();
    listar_usuario(cur_id);
    $("#modalmantenimiento").modal("show");
  }
}

function listar_usuario(cur_id) {
  $("#usuario_data").DataTable({
    aProcessing: true,
    aServerSide: true,
    dom: "Bfrtip",
    buttons: ["copyHtml5", "excelHtml5", "csvHtml5"],
    ajax: {
      url: "../../controller/usuario.php?op=listar_detalle_usuario",
      type: "post",
      data: { cur_id: cur_id },
    },
    bDestroy: true,
    responsive: true,
    bInfo: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo:
        "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sInfoPostFix: "",
      sSearch: "Buscar:",
      sUrl: "",
      sInfoThousands: ",",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
      },
      oAria: {
        sSortAscending:
          ": Activar para ordenar la columna de manera ascendente",
        sSortDescending:
          ": Activar para ordenar la columna de manera descendente",
      },
    },
  });
}

function registrardetalle() {
  table = $("#usuario_data").DataTable();
  var usu_id = [];

  table.rows().every(function (rowIdx, tableLoop, rowLoop) {
    cell1 = table.cell({ row: rowIdx, column: 0 }).node();
    if ($("input", cell1).prop("checked") == true) {
      id = $("input", cell1).val();
      usu_id.push([id]);
    }
  });

  if (usu_id == 0) {
    Swal.fire({
      title: "Error!",
      text: "Seleccionar Usuarios",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
  } else {
    /* Creando formulario */
    const formData = new FormData($("#form_detalle")[0]);
    formData.append("cur_id", cur_id);
    formData.append("usu_id", usu_id);

    $.ajax({
      url: "../../controller/curso.php?op=insert_curso_usuario",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        data = JSON.parse(data);

        data.forEach((e) => {
          e.forEach((i) => {
            console.log(i["curd_id"]);
          });
        });
      },
    });

    /* Recargar datatable de los usuarios del curso */
    $("#detalle_data").DataTable().ajax.reload();

    $("#usuario_data").DataTable().ajax.reload();
    /* ocultar modal */
    $("#modalmantenimiento").modal("hide");
  }
}

init();
