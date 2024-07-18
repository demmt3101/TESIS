<?php
require_once("config/conexion.php");

if(isset($_POST["register"]) && $_POST["register"] == "si") {
    $errors = [];

    // Validación del nombre
    if(empty($_POST["usu_nom"])) {
        $errors[] = "El nombre es requerido.";
    }

    // Validación del correo electrónico
    if(empty($_POST["usu_correo"])) {
        $errors[] = "El correo electrónico es requerido.";
    } elseif(!filter_var($_POST["usu_correo"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo electrónico no tiene un formato válido.";
    }

    // Validación de la contraseña
    if(empty($_POST["usu_pass"])) {
        $errors[] = "La contraseña es requerida.";
    }

    if(!empty($errors)) {
        foreach($errors as $error) {
            mostrarError($error);
        }
    } else {
        require_once("models/Usuario.php");

        // Obtener valores del formulario
        $usu_nom = $_POST['usu_nom'];
        $usu_correo = $_POST['usu_correo'];
        $usu_pass = $_POST['usu_pass'];
        $rol_id = 1; // Asignar el rol deseado, en este caso es 1 según tus indicaciones

        // Inicializar objeto Usuario y llamar a la función register
        $usuario = new Usuario();
        $registrado = $usuario->register($usu_nom, $usu_correo, $usu_pass, $rol_id);

        if ($registrado) {
            header("Location: index.php?m=registro_exitoso");
            exit();
        } else {
            header("Location: register.php?m=error_registro");
            exit();
        }
    }
}

function mostrarError($mensaje) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($mensaje); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
}

$mensajeError = "";
if (isset($_GET["m"])) {
    switch($_GET["m"]) {
        case "1":
            $mensajeError = "Datos Incorrectos";
            break;
        case "2":
            $mensajeError = "Campos vacíos";
            break;
        case "registro_exitoso":
            $mensajeError = "Registro exitoso";
            break;
        case "error_registro":
            $mensajeError = "Error al registrar usuario";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Bracket">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:url" content="http://themepixels.me/bracket">
    <meta property="og:title" content="Bracket">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta property="og:image" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/bracket/img/bracket-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels">
    <link href="public/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="public/lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/bracket.css">
    <title>CERTIPUCE | Registro</title>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
        <form action="register.php" method="post">
            <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
                <!-- Capturando mensaje de error -->
                <?php
                if (!empty($mensajeError)) {
                    mostrarError($mensajeError);
                }
                ?>

                <div class="signin-logo tx-center tx-28 tx-bold tx-inverse"><span class="tx-normal"></span> CERTIPUCE <span class="tx-normal"></span></div>
                <div class="tx-center mg-b-30">Registro de Usuario</div>

                <div class="form-group">
                    <input type="text" id="usu_nom" name="usu_nom" class="form-control" placeholder="Ingrese Nombre" value="<?php echo isset($_POST['usu_nom']) ? $_POST['usu_nom'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <input type="email" id="usu_correo" name="usu_correo" class="form-control" placeholder="Ingrese Correo Electrónico" value="<?php echo isset($_POST['usu_correo']) ? $_POST['usu_correo'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" id="usu_pass" name="usu_pass" class="form-control" placeholder="Ingrese Contraseña" required>
                </div>
                <input type="hidden" name="register" value="si">
                <button type="submit" class="btn btn-info btn-block">Registrar</button>
                <!-- Botón de Cancelar -->
                <a href="index.php" class="btn btn-danger btn-block">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="public/lib/jquery/jquery.js"></script>
    <script src="public/lib/popper.js/popper.js"></script>
    <script src="public/lib/bootstrap/bootstrap.js"></script>
</body>
</html>
