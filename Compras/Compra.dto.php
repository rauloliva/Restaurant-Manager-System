<?php
class Compra{
	private $id;
	private $Hora;
	private $Fecha;
	private $Total;
	private $FolioProv;
	private $IdEmpleado;
	private $IdProveedor;
	private $NombreEmpl;
	private $NombreProv;
	private $NombreProducto;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}
