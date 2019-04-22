<?php
    require_once "Sesion.php";
    require_once "pdo.php";

    class SesionDAO{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function iniciar_sesion(Sesion $sesion){
            try{
                $stm = $this->pdo->prepare("SELECT contrasena_usuario,id_empleado,status_empleado,
                    tipo_empleado FROM empleados WHERE nombre_usuario=? OR correo_empleado=?");
                $stm->execute(array($sesion->__GET('user'),$sesion->__GET('correo')));
                $r = $stm->fetch(PDO::FETCH_OBJ);
                if(!empty($r)) {
                    if(password_verify($sesion->__GET('pwd'),$r->contrasena_usuario)){
                        $sesion->__SET('status',$r->status_empleado);
                        $sesion->__SET('tipo',$r->tipo_empleado);
                        $sesion->__SET('id',$r->id_empleado);
                        return $sesion;
                    }
                }
                $sesion->msg = "Revisa que tu usuario y contraseña sean correctos";
                return $sesion;
            }catch(Exception $e){
                die($e->getMessage());
            }
        }
    }

    if(isset($_GET['username'])&& isset($_GET['password'])){
        $pdo = new PDO_connection();
        $sesion = new Sesion();
        $sesion->__SET("user",$_GET['username']);
        $sesion->__SET("correo",$_GET['username']);
        $sesion->__SET("pwd",$_GET['password']);
        $dao = new SesionDAO($pdo);
        $sesion = $dao->iniciar_sesion($sesion);
        $employee[] = array(
            "id" => $sesion->__GET("id"),
            "user" => $sesion->__GET("user"),
            "pwd" => $sesion->__GET("pwd"),
            "status" => $sesion->__GET("status"),
            "msg" => $sesion->__GET("msg"),
            "tipo" => $sesion->__GET("tipo"),
        );
        header('Content-Type: text/json');
        echo json_encode($employee);
    }
?>