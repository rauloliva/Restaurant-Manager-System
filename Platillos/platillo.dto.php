<?php
class Platillo{
	private $id;
	private $Nombre;
	private $Precio;
	private $Precio_Platillo;
	private $Ingredientes;
	private $Categoria;
	private $Status;
	private $FechaDeIngreso;
	private $FechaDeEgreso;
	private $Foto;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}