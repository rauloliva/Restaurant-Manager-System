<?php
class Sesion {
    private $id;
    private $user;
    private $pwd;
    private $status;
    private $msg;
    private $tipo;
    private $foto;
    private $correo;

    public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}
?>