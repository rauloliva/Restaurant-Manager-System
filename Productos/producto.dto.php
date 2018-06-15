<?php
class Producto{
	private $id;
	private $Existencia;
	private $Categoria;
	private $Nombre;
	private $Precio;
	private $Medida;
	private $Gestor_Min;
	private $Gestor_Max;
	private $Status;
	private $FechaDeIngreso;
	private $FechaDeEgreso;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}