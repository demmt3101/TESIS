<?php
  /*TODO: Llamando Cadena de Conexion */
  require_once("config/conexion.php");

  if(isset($_POST["enviar"]) and $_POST["enviar"]=="si"){
    require_once("models/Usuario.php");
    /*TODO: Inicializando Clase */
    $usuario = new Usuario();
    $usuario->login();
  }

  function mostrarError($mensaje) {
    ?>
      <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <strong class="d-block d-sm-inline-block-force">Error!</strong> <?php echo htmlspecialchars($mensaje); ?>
      </div>
    <?php
  }

  function mostrarExito($mensaje) {
    ?>
      <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <strong class="d-block d-sm-inline-block-force">Éxito!</strong> <?php echo htmlspecialchars($mensaje); ?>
      </div>
    <?php
  }

  $mensajeError = "";
  if (isset($_GET["m"])){
    switch($_GET["m"]){
      case "1":
        $mensajeError = "Datos Incorrectos";
        break;
      case "2":
        $mensajeError = "Campos vacios";
        break;
    }
  }

  // Comprueba si hay un mensaje de éxito en la sesión
  $mensajeExito = "";
  if (isset($_SESSION['success'])) {
    $mensajeExito = $_SESSION['success'];
    unset($_SESSION['success']);
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
    <title>CERTIPUCE | Acceso</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  </head>

  <body>

    <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">
      <form action="" method="post">
        <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
          <!-- Capturando mensaje de error -->
          <?php
            if (!empty($mensajeError)){
              mostrarError($mensajeError);
            }
          ?>

          <div class="signin-logo tx-center tx-28 tx-bold tx-inverse"><span class="tx-normal"></span> CERTIPUCE <span class="tx-normal"></span></div>

          <div class="tx-center mg-b-30">Certificados y Diplomas</div>

          <div class="form-group">
              <input type="text" id="usu_correo" name="usu_correo" class="form-control" placeholder="Ingrese Correo Electronico">
          </div>
          <div class="form-group">
              <input type="password" id="usu_pass" name="usu_pass" class="form-control" placeholder="Ingrese Contraseña">
          </div>
          <input type="hidden" name="enviar" class="form-control" value="si">
          <button type="submit" class="btn btn-info btn-block">Acceder</button>
          <!-- Aquí está el nuevo botón de registro -->
          <a href="register.php" class="btn btn-primary btn-block">Registrar Usuario</a>
        </div>
      </form>
    </div>

    <script src="public/lib/jquery/jquery.js"></script>
    <script src="public/lib/popper.js/popper.js"></script>
    <script src="public/lib/bootstrap/bootstrap.js"></script>

    <script src="descargaMasiva.js" type="module"></script>
    <script src="admindetallecertificado.js" type="module"></script>

  </body>
</html>
