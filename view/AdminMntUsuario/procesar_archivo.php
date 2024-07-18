<?php
// Incluir la librería PhpSpreadsheet
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Verificar si se ha enviado un archivo
if ($_FILES['file']['name']) {
    $file_name = $_FILES['file']['name'];
    $temp_file_location = $_FILES['file']['tmp_name'];

    // Identificar el tipo de archivo Excel y crear un lector
    $inputFileType = IOFactory::identify($temp_file_location);
    $reader = IOFactory::createReader($inputFileType);

    // Cargar el archivo Excel
    $spreadsheet = $reader->load($temp_file_location);
    $sheet = $spreadsheet->getActiveSheet();

    $data = [];
    // Iterar sobre cada fila del archivo Excel
    foreach ($sheet->getRowIterator() as $row) {
        $rowData = [];
        // Iterar sobre cada celda de la fila
        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
    }

    // Ahora $data contiene todos los datos del archivo Excel
    // Siguiente paso: insertar estos datos en la base de datos
}
?>
