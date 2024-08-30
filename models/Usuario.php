<?php
    class Usuario extends Conectar{
        /*TODO: Funcion para login de acceso del usuario */
        public function login() {
            $conectar = parent::conexion();
            parent::set_names();
            if (isset($_POST["enviar"])) {
                $correo = $_POST["usu_correo"] ?? null;
                $pass = $_POST["usu_pass"] ?? null;
                if (empty($correo) || empty($pass)) {
                    // En caso estén vacíos correo y contraseña, devolver al index con mensaje = 2
                    header("Location:" . Conectar::ruta() . "index.php?m=2");
                    exit();
                } else {
                    $sql = "SELECT * FROM tm_usuario WHERE usu_correo=? AND usu_pass=? AND est=1";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $correo);
                    $stmt->bindValue(2, $pass);
                    $stmt->execute();
                    $resultado = $stmt->fetch();
                    if (is_array($resultado) && count($resultado) > 0) {
                        $_SESSION["usu_id"] = $resultado["usu_id"];
                        $_SESSION["usu_nom"] = $resultado["usu_nom"];
                        $_SESSION["usu_ape"] = $resultado["usu_apep"] . ' ' . $resultado["usu_apem"];
                        $_SESSION["usu_correo"] = $resultado["usu_correo"];
                        $_SESSION["rol_id"] = $resultado["rol_id"];
                        // Si todo está correcto, redirigir a home
                        header("Location:" . Conectar::ruta() . "view/UsuHome/");
                        exit();
                    } else {
                        // En caso no coincidan el usuario o la contraseña
                        header("Location:" . Conectar::ruta() . "index.php?m=1");
                        exit();
                    }
                }
            }
        }

        /*TODO: Mostrar todos los cursos en los cuales esta inscrito un usuario */
        public function get_cursos_x_usuario($usu_id) {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
                        td_curso_usuario.curd_id,
                        tm_curso.cur_id,
                        tm_curso.cur_nom,
                        tm_curso.cur_descrip,
                        tm_curso.cur_fechini,
                        tm_curso.cur_fechfin,
                        tm_usuario.usu_id,
                        tm_usuario.usu_nom,
                        tm_usuario.usu_apep,
                        tm_usuario.usu_apem,
                        tm_instructor.inst_id,
                        tm_instructor.inst_nom,
                        tm_instructor.inst_apep,
                        tm_instructor.inst_apem
                    FROM td_curso_usuario 
                    INNER JOIN tm_curso ON td_curso_usuario.cur_id = tm_curso.cur_id 
                    INNER JOIN tm_usuario ON td_curso_usuario.usu_id = tm_usuario.usu_id 
                    INNER JOIN tm_instructor ON tm_curso.inst_id = tm_instructor.inst_id
                    WHERE td_curso_usuario.usu_id = ?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
        }

        /*TODO: Mostrar todos los cursos en los cuales esta inscrito un usuario */
        public function get_cursos_x_usuario_top10($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT 
                td_curso_usuario.curd_id,
                tm_curso.cur_id,
                tm_curso.cur_nom,
                tm_curso.cur_descrip,
                tm_curso.cur_fechini,
                tm_curso.cur_fechfin,
                tm_usuario.usu_id,
                tm_usuario.usu_nom,
                tm_usuario.usu_apep,
                tm_usuario.usu_apem,
                tm_instructor.inst_id,
                tm_instructor.inst_nom,
                tm_instructor.inst_apep,
                tm_instructor.inst_apem
                FROM td_curso_usuario INNER JOIN 
                tm_curso ON td_curso_usuario.cur_id = tm_curso.cur_id INNER JOIN
                tm_usuario ON td_curso_usuario.usu_id = tm_usuario.usu_id INNER JOIN
                tm_instructor ON tm_curso.inst_id = tm_instructor.inst_id
                WHERE 
                td_curso_usuario.usu_id = ?
                AND td_curso_usuario.est = 1
                LIMIT 10";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        public  function get_cursos_usuario_x_id($cur_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT 
                td_curso_usuario.curd_id,
                tm_curso.cur_id,
                tm_curso.cur_nom,
                tm_curso.cur_descrip,
                tm_curso.cur_fechini,
                tm_curso.cur_fechfin,
                tm_usuario.usu_id,
                tm_usuario.usu_nom,
                tm_usuario.usu_apep,
                tm_usuario.usu_apem,
                tm_instructor.inst_id,
                tm_instructor.inst_nom,
                tm_instructor.inst_apep,
                tm_instructor.inst_apem
                FROM td_curso_usuario INNER JOIN 
                tm_curso ON td_curso_usuario.cur_id = tm_curso.cur_id INNER JOIN
                tm_usuario ON td_curso_usuario.usu_id = tm_usuario.usu_id INNER JOIN
                tm_instructor ON tm_curso.inst_id = tm_instructor.inst_id
                WHERE 
                tm_curso.cur_id = ?
                AND td_curso_usuario.est = 1";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $cur_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /*TODO: Mostrar todos los datos de un curso por su id de detalle */
        public function get_curso_x_id_detalle($curd_id){
            $conectar = parent::conexion();
            parent::set_names();
<<<<<<< Updated upstream
            $sql="SELECT 
                td_curso_usuario.curd_id,
                tm_curso.cur_id,
                tm_curso.cur_nom,
                tm_curso.cur_descrip,
                tm_curso.cur_fechini,
                tm_curso.cur_fechfin,
                tm_usuario.usu_id,
                tm_usuario.usu_nom,
                tm_usuario.usu_apep,
                tm_usuario.usu_apem,
                tm_curso.cur_img,
                tm_instructor.inst_id,
                tm_instructor.inst_nom,
                tm_instructor.inst_apep,
                tm_instructor.inst_apem
                FROM td_curso_usuario INNER JOIN 
                tm_curso ON td_curso_usuario.cur_id = tm_curso.cur_id INNER JOIN
                tm_usuario ON td_curso_usuario.usu_id = tm_usuario.usu_id INNER JOIN
                tm_instructor ON tm_curso.inst_id = tm_instructor.inst_id
                WHERE 
                td_curso_usuario.curd_id = ?";
            $sql=$conectar->prepare($sql);
=======
            $sql = "SELECT 
                        td_curso_usuario.curd_id,
                        tm_curso.cur_id,
                        tm_curso.cur_nom,
                        tm_curso.cur_descrip,
                        tm_curso.cur_fechini,
                        tm_curso.cur_fechfin,
                        tm_curso.cur_img,       -- Incluye el campo cur_img aquí
                        tm_usuario.usu_id,
                        tm_usuario.usu_nom,
                        tm_usuario.usu_apep,
                        tm_usuario.usu_apem,
                        tm_instructor.inst_id,
                        tm_instructor.inst_nom,
                        tm_instructor.inst_apep,
                        tm_instructor.inst_apem
                    FROM td_curso_usuario 
                    INNER JOIN tm_curso ON td_curso_usuario.cur_id = tm_curso.cur_id 
                    INNER JOIN tm_usuario ON td_curso_usuario.usu_id = tm_usuario.usu_id 
                    INNER JOIN tm_instructor ON tm_curso.inst_id = tm_instructor.inst_id
                    WHERE td_curso_usuario.curd_id = ?";
            $sql = $conectar->prepare($sql);
>>>>>>> Stashed changes
            $sql->bindValue(1, $curd_id);
            $sql->execute();
            return $sql->fetchAll();
        }
        

        /*TODO: Cantidad de Cursos por Usuario */
        public function get_total_cursos_x_usuario($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT count(*) as total FROM td_curso_usuario WHERE usu_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /*TODO: Mostrar los datos del usuario segun el ID */
        public function get_usuario_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario WHERE est=1 AND usu_id=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /*TODO: Actualizar la informacion del perfil del usuario segun ID */
        public function update_usuario_perfil($usu_id, $usu_nom, $usu_apep, $usu_apem, $usu_pass, $usu_telf) {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "UPDATE tm_usuario 
                    SET
                        usu_nom = ?,
                        usu_apep = ?,
                        usu_apem = ?,
                        usu_pass = ?,
                        usu_telf = ?
                    WHERE
                        usu_id = ?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_apep);
            $sql->bindValue(3, $usu_apem);
            $sql->bindValue(4, $usu_pass);
            $sql->bindValue(5, $usu_telf);
            $sql->bindValue(6, $usu_id);
            $sql->execute();
            return $sql->fetchAll();
        }
        

        /*TODO: Funcion para insertar usuario */
<<<<<<< Updated upstream
        public function insert_usuario($usu_nom,$usu_apep,$usu_apem,$usu_correo,$usu_pass,$usu_sex,$usu_telf,$rol_id){
            $conectar= parent::conexion();
=======
        public function insert_usuario($usu_nom, $usu_apep, $usu_apem, $usu_correo, $usu_pass, $usu_telf, $rol_id){
            $conectar = parent::conexion();
>>>>>>> Stashed changes
            parent::set_names();
            $sql = "INSERT INTO tm_usuario (usu_id, usu_nom, usu_apep, usu_apem, usu_correo, usu_pass, usu_telf, rol_id, fech_crea, est) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, now(), '1');";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_apep);
            $sql->bindValue(3, $usu_apem);
            $sql->bindValue(4, $usu_correo);
            $sql->bindValue(5, $usu_pass);
            $sql->bindValue(6, $usu_telf);
            $sql->bindValue(7, $rol_id);
            $sql->execute();
<<<<<<< Updated upstream
            return $resultado=$sql->fetchAll();
        }
    
=======
            return $resultado = $sql->fetchAll();
        }               
                       
>>>>>>> Stashed changes

        /*TODO: Funcion para actualizar usuario */
        public function update_usuario($usu_id, $usu_nom, $usu_apep, $usu_apem, $usu_correo, $usu_pass, $usu_telf, $rol_id){
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "UPDATE tm_usuario SET usu_nom=?, usu_apep=?, usu_apem=?, usu_correo=?, usu_pass=?, usu_telf=?, rol_id=? WHERE usu_id=?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_apep);
            $sql->bindValue(3, $usu_apem);
            $sql->bindValue(4, $usu_correo);
            $sql->bindValue(5, $usu_pass);
<<<<<<< Updated upstream
            $sql->bindValue(6, $usu_sex);
            $sql->bindValue(7, $usu_telf);
            $sql->bindValue(8, $rol_id);
            $sql->bindValue(9, $usu_id);
=======
            $sql->bindValue(6, $usu_telf);
            $sql->bindValue(7, $rol_id);
            $sql->bindValue(8, $usu_id);
>>>>>>> Stashed changes
            $sql->execute();
            return $resultado = $sql->fetchAll();
        }
        

        public function delete_usuario($usu_id) {
            $conectar = parent::conexion();
            parent::set_names();
            
            $sql = "DELETE FROM tm_usuario WHERE usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            
            $stmt->execute();
        }
      

        /*TODO: Listar todas las categorias */
        public function get_usuario(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario WHERE est = 1";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /*TODO: Listar todas las categorias */
        public function get_usuario_modal($cur_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario 
                WHERE est = 1
                AND usu_id not in (select usu_id from td_curso_usuario where cur_id=? AND est=1)";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $cur_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        
        public function get_usuario_por_correo($usu_correo) {
            $conectar = parent::conexion();
            parent::set_names();
            
            $sql = "SELECT * FROM tm_usuario WHERE usu_correo = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_correo);
            $stmt->execute();
            
            return $stmt->fetchAll();
        }
    
        public function generate_numeric_password($length = 6) {
            return substr(str_shuffle("0123456789"), 0, $length);
        }
    
        public function register_masive($usu_nom, $usu_correo, $curso, $usu_pass) {
            $conectar = parent::conexion();
            parent::set_names();
            
            try {
                $conectar->beginTransaction();
                
                // Check if the user email is already registered
                $sql_check = "SELECT * FROM tm_usuario WHERE usu_correo = ?";
                $stmt_check = $conectar->prepare($sql_check);
                $stmt_check->bindValue(1, $usu_correo);
                $stmt_check->execute();
                
                if ($stmt_check->rowCount() > 0) {
                    $conectar->rollBack();
                    return "Correo ya registrado";
                }
                
                $sql = "INSERT INTO tm_usuario (usu_nom, usu_correo, curso, usu_pass, rol_id, est) VALUES (?, ?, ?, ?, 1, 1)";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $usu_nom);
                $stmt->bindValue(2, $usu_correo);
                $stmt->bindValue(3, $curso);
                $stmt->bindValue(4, $usu_pass);
                
                if ($stmt->execute()) {
                    $conectar->commit();
                    return true;
                } else {
                    $conectar->rollBack();
                    return false;
                }
            } catch (Exception $e) {
                $conectar->rollBack();
                return false;
            }
        }
    
        // New function to validate email format
        public function validate_email_format($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    
        public function register($usu_nom, $usu_correo, $usu_pass) {
            $conectar = parent::conexion();
            parent::set_names();
            
            $sql = "INSERT INTO tm_usuario (usu_nom, usu_correo, usu_pass, rol_id, est) VALUES (?, ?, ?, 1, 1)";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_nom);
            $stmt->bindValue(2, $usu_correo);
            $stmt->bindValue(3, $usu_pass);
            
            return $stmt->execute();
        }
}
?>