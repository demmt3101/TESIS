<?php
/* TODO: Llamando a cadena de Conexion */
require_once('D:\XAMPP\htdocs\TESIS\config\conexion.php');
/* TODO: Llamando a la clase Usuario */
require_once('D:\XAMPP\htdocs\TESIS\models\Usuario.php');
require ('D:\XAMPP\htdocs\TESIS\vendor\autoload.php');
use PhpOffice\PhpSpreadsheet\IOFactory;

// Inicializando la clase Usuario
$usuario = new Usuario();

// Verificar si 'op' está definido en $_GET
switch ($_GET["op"]) {

        /* TODO: MicroServicio para poder mostrar el listado de cursos de un usuario con certificado */
        case "listar_cursos":
            $datos=$usuario->get_cursos_x_usuario($_POST["usu_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["cur_nom"];
                $sub_array[] = $row["cur_fechini"];
                $sub_array[] = $row["cur_fechfin"];
                $sub_array[] = $row["inst_nom"]." ".$row["inst_apep"];
                $sub_array[] = '<button type="button" onClick="certificado('.$row["curd_id"].');"  id="'.$row["curd_id"].'" class="btn btn-outline-primary btn-icon"><div><i class="fa fa-id-card-o"></i></div></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);

            break;

        /* TODO: MicroServicio para poder mostrar el listado de los top 10 cursos de un usuario */
        case "listar_cursos_top10":
            $datos=$usuario->get_cursos_x_usuario_top10($_POST["usu_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["cur_nom"];
                $sub_array[] = $row["cur_fechini"];
                $sub_array[] = $row["cur_fechfin"];
                $sub_array[] = $row["inst_nom"]." ".$row["inst_apep"];
                $sub_array[] = '<button type="button" onClick="certificado('.$row["curd_id"].');"  id="'.$row["curd_id"].'" class="btn btn-outline-primary btn-icon"><div><i class="fa fa-id-card-o"></i></div></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);

            break;

        /* TODO: Microservicio para mostrar información detallada de un curso específico */
        case "mostrar_curso_detalle":
            $datos = $usuario->get_curso_x_id_detalle($_POST["curd_id"]);
            $output = array();
            if (is_array($datos) == true and count($datos) <> 0) {
                foreach ($datos as $row) {
                    $output["curd_id"] = $row["curd_id"];
                    $output["cur_id"] = $row["cur_id"];
                    $output["cur_nom"] = $row["cur_nom"];
                    $output["cur_descrip"] = $row["cur_descrip"];
                    $output["cur_fechini"] = $row["cur_fechini"];
                    $output["cur_fechfin"] = $row["cur_fechfin"];
                    $output["cur_img"] = $row["cur_img"];
                    $output["usu_id"] = $row["usu_id"];
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["usu_apep"] = $row["usu_apep"];
                    $output["usu_apem"] = $row["usu_apem"];
                    $output["inst_id"] = $row["inst_id"];
                    $output["inst_nom"] = $row["inst_nom"];
                    $output["inst_apep"] = $row["inst_apep"];
                    $output["inst_apem"] = $row["inst_apem"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Total de Cursos por usuario para el dashboard */
        case "total":
            $datos = $usuario->get_total_cursos_x_usuario($_POST["usu_id"]);
            $output = array();
            if (is_array($datos) == true and count($datos) > 0) {
                foreach ($datos as $row) {
                    $output["total"] = $row["total"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Mostrar información del usuario en la vista perfil */
        case "mostrar":
            $datos = $usuario->get_usuario_x_id($_POST["usu_id"]);
            if(is_array($datos)==true and count($datos)<>0){
                foreach($datos as $row){
                    $output["usu_id"] = $row["usu_id"];
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["usu_apep"] = $row["usu_apep"];
                    $output["usu_apem"] = $row["usu_apem"];
                    $output["usu_correo"] = $row["usu_correo"];
                    $output["usu_pass"] = $row["usu_pass"];
                    $output["usu_telf"] = $row["usu_telf"];
                    $output["rol_id"] = $row["rol_id"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Actualizar datos de perfil */
        case "update_perfil":
            $usuario->update_usuario_perfil(
                $_POST["usu_id"],
                $_POST["usu_nom"],
                $_POST["usu_apep"],
                $_POST["usu_apem"],
                $_POST["usu_pass"],
                $_POST["usu_sex"],
                $_POST["usu_telf"]
            );
            break;

        /* TODO: Guardar y editar cuando se tenga el ID */
        case "guardaryeditar":
            if(empty($_POST["usu_id"])){
                $usuario->insert_usuario($_POST["usu_nom"], $_POST["usu_apep"], $_POST["usu_apem"], $_POST["usu_correo"], $_POST["usu_pass"], $_POST["usu_telf"], $_POST["rol_id"]);
            } else {
                $usuario->update_usuario($_POST["usu_id"], $_POST["usu_nom"], $_POST["usu_apep"], $_POST["usu_apem"], $_POST["usu_correo"], $_POST["usu_pass"], $_POST["usu_telf"], $_POST["rol_id"]);
            }
            break;                

        /* TODO: Eliminar segun ID */
        case "eliminar":
            $usuario->delete_usuario($_POST["usu_id"]);
            break;

        /* TODO: Listar toda la informacion segun formato de datatable */
        case "listar":
            $datos=$usuario->get_usuario();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["usu_nom"];
                $sub_array[] = $row["usu_apep"];
                $sub_array[] = $row["usu_correo"];
                $sub_array[] = $row["curso"];
                $sub_array[] = $row["usu_pass"];
                if ($row["rol_id"]==1) {
                    $sub_array[] = "Usuario";
                }else{
                    $sub_array[] = "Admin";
                }
                $sub_array[] = '<button type="button" onClick="editar('.$row["usu_id"].');"  id="'.$row["usu_id"].'" class="btn btn-outline-warning btn-icon"><div><i class="fa fa-edit"></i></div></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["usu_id"].');"  id="'.$row["usu_id"].'" class="btn btn-outline-danger btn-icon"><div><i class="fa fa-close"></i></div></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Listar todos los usuarios pertenecientes a un curso */
        case "listar_cursos_usuario":
            $datos=$usuario->get_cursos_usuario_x_id($_POST["cur_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["cur_nom"];
                $sub_array[] = $row["usu_nom"]." ".$row["usu_apep"]." ".$row["usu_apem"];
                $sub_array[] = $row["cur_fechini"];
                $sub_array[] = $row["cur_fechfin"];
                $sub_array[] = $row["inst_nom"]." ".$row["inst_apep"];
                $sub_array[] = '<button type="button" onClick="certificado('.$row["curd_id"].');"  id="'.$row["curd_id"].'" class="btn btn-outline-primary btn-icon"><div><i class="fa fa-id-card-o"></i></div></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["curd_id"].');"  id="'.$row["curd_id"].'" class="btn btn-outline-danger btn-icon"><div><i class="fa fa-close"></i></div></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Listar todos los detalles de los usuarios pertenecientes a un curso */
        case "listar_detalle_usuario":
            $datos=$usuario->get_usuario_modal($_POST["cur_id"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = "<input type='checkbox' name='detallecheck[]' value='". $row["usu_id"] ."'>";
                $sub_array[] = $row["usu_nom"];
                $sub_array[] = $row["usu_apep"];
                $sub_array[] = $row["usu_correo"];
                $sub_array[] = $row["curso"];
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Importar datos de usuarios desde un archivo Excel */
        case "import_excel":
            if (!empty($_FILES["file"]["tmp_name"])) {
                $filePath = $_FILES["file"]["tmp_name"];
        
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                    $correos_existentes = [];
        
                    foreach ($sheetData as $row) {
                        if ($row['A'] != 'Nombre') { // Suponiendo que la primera fila contiene los encabezados
                            $usu_nom = $row['A'];
                            $curso = $row['C'];
                            $usu_correo = $row['E'];
        
                            // Verificar si el correo ya existe
                            $correo_existente = $usuario->get_usuario_por_correo($usu_correo);
                            if (count($correo_existente) == 0) {
                                // Generar una contraseña numérica aleatoria
                                $usu_pass = $usuario->generate_numeric_password();
        
                                // Hash de la contraseña
                                $hashedPassword = password_hash($usu_pass, PASSWORD_DEFAULT);
        
                                // Insertar usuario en la base de datos
                                if ($usuario->register_masive($usu_nom, $curso, $usu_correo , $hashedPassword)) {
                                    // Opcionalmente, enviar un correo con la contraseña generada
                                    // $usuario->send_email_with_password($usu_correo, $usu_pass);
                                } else {
                                    echo "Error al registrar el usuario: " . $usu_nom;
                                }
                            } else {
                                $correos_existentes[] = $usu_correo;
                            }
                        }
                    }
                    echo "Usuarios importados con éxito.";
                    if (!empty($correos_existentes)) {
                        echo " Los siguientes correos ya estaban registrados: " . implode(", ", $correos_existentes);
                    }
                } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                    echo "Error al cargar el archivo Excel: " . $e->getMessage();
                }
            } else {
                echo "Por favor, seleccione un archivo Excel.";
            }
            break;

        case "guardar_desde_excel":
            $usuario->insert_usuario($_POST["usu_nom"],$_POST["usu_apep"],$_POST["usu_apem"],$_POST["usu_correo"],$_POST["usu_pass"],$_POST["usu_sex"],$_POST["usu_telf"],$_POST["rol_id"]);
            break;
            
        }
        
?>
