<?php
class Cliente{
	private $id;
	private $Nombre;
	private $Apellido_paterno;
	private $Apellido_materno;
	private $Status;
	private $FechaDeIngreso;
	private $FechaDeEgreso;
	private $Correo;
	private $RFC;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}