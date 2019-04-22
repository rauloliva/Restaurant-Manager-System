<?php
class Venta{
	private $id;
	private $Nombre;
	private $Precio;
	private $Cantidad;
	private $IdPlatillo;
	private $Venta_total;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}