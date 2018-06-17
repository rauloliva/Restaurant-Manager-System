<?php
class Venta{
	private $id;
	private $Total;
	private $Hora;
	private $Fecha;
	private $IdCliente;
	private $IdEmpleado;
	private $Id_venta_platillo;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}