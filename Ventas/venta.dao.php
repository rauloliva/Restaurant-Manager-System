<?php
require_once dirname(__DIR__).'/pdo.php';
require_once "venta.dto.php";

class VentaDAO{
	private $pdo;

	public function __construct($pdo){
		$this->pdo = $pdo;
	}

	public function Listar($condition){
		try{
			$result = array();
			$stm = $this->pdo->prepare("SELECT * FROM ventas".$condition);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Venta();
				$alm->__SET('id', $r->id_venta);
				$alm->__SET('Total', $r->total_venta);
				$alm->__SET('Hora', $r->hora_venta);
				$alm->__SET('Fecha', $r->fecha_venta);
				$alm->__SET('IdCliente', $r->id_cliente);
				$alm->__SET('IdEmpleado', $r->id_empleado);
				$alm->__SET('Id_venta_platillo', $r->id_venta_platillo);
				$result[] = $alm;
			}
			return $result;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	public function Obtener($id){
		try{
			$stm = $this->pdo->prepare("SELECT * FROM ventas WHERE id_venta = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Venta();
			$alm->__SET('id', $r->id_venta);
			$alm->__SET('Total', $r->total_venta);
			$alm->__SET('Hora', $r->hora_venta);
			$alm->__SET('Fecha', $r->fecha_venta);
			$alm->__SET('IdCliente', $r->id_cliente);
			$alm->__SET('IdEmpleado', $r->id_empleado);
			$alm->__SET('Id_venta_platillo', $r->id_venta_platillo);
			return $alm;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	public function Registrar(Venta $data){
		try{
		$sql = "INSERT INTO  ventas (total_venta,hora_venta,fecha_venta,
		 id_cliente,id_empleado,id_venta_platillo)VALUES(?,?,
		 ?,?,?,?)";
		$this->pdo->prepare($sql)->execute(
			array(
					$data->__GET('Total'),
					$data->__GET('Hora'),
					$data->__GET('Fecha'),
					$data->__GET('IdCliente'),
					$data->__GET('IdEmpleado'),
					$data->__GET('Id_venta_platillo')
				)
			);
			$info = array("msg" => "La venta ha sido realizada con exito","color" => "blue");
			return $info;
		} catch (Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al guardar","color" => "red");
			return $info;
			die($e->getMessage());
		}
	}
}

if(isset($_GET['value'])){
	if(isset($_GET['column'])){
		$_GET['value'] = $_GET['value'] == " = ''" ? " LIKE '%%'" : $_GET['value'];
		$pdo = new PDO_connection();
		$stm = $pdo->query("SELECT * FROM ventas WHERE ".$_GET['column']."".$_GET['value']);
		if($stm->rowCount() > 0){
			while($obj = $stm->fetch(PDO::FETCH_OBJ)){
				$arr[] = array(
					"id" => $obj->id_venta,
					"total" => $obj->total_venta,
					"hora" => $obj->hora_venta,
					"fecha" => $obj->fecha_venta,
					"id_cliente" => $obj->id_cliente,
					"id_empleado" => $obj->id_empleado,
					"id_venta_platillo" => $obj->id_venta_platillo,
					"msg" => ""
				);
			}
			echo json_encode($arr);
		}else{
			$error[] = array("msg" => "No information");
			echo json_encode($error);
		}
	}
}
?>