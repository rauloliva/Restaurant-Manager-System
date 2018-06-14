<?php
class Proveedor{
	private $id;
	private $Nombre;
	private $Correo;
	private $Direccion;
	private $Telefono;
	private $Status;
	private $FechaDeIngreso;
	private $FechaDeEgreso;
	private $Folio;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}