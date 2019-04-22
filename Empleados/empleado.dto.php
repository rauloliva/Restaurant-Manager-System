<?php
class Empleado{
	private $id;
	private $Nombre;
	private $Apellido_paterno;
	private $Apellido_materno;
	private $FechaNacimiento;
	private $Sexo;
	private $Direccion;
	private $Correo;
	private $Telefono;
	private $Tipo;
	private $Sueldo;
	private $Nombre_user;
	private $Contraseña_user;
	private $Status;
	private $FechaDeIngreso;
	private $FechaDeEgreso;
	private $Foto;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}