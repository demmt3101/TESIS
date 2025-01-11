<?php
/* Iniciar la sesión del usuario */
session_start();

/* Clase para manejar la conexión a la base de datos */
class Conectar {
    protected $dbh;

    /* Método protegido para establecer la conexión con la base de datos */
    protected function Conexion() {
        try {
            /* Cadena de conexión */
            $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=TESIS", "root", "");
            // Configurar el modo de error de PDO para excepciones
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conectar;
        } catch (Exception $e) {
            /* En caso de error en la conexión */
            print "¡Error BD!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /* Método para establecer la codificación de caracteres a UTF-8 */
    public function set_names() {
        return $this->dbh->query("SET NAMES 'utf8'");
    }

    /* Método estático para obtener la ruta principal del proyecto */
    public static function ruta() {
        return "http://localhost:90/TESIS/";
    }
}
?>
