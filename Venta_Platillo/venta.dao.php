<?php
require_once dirname(__DIR__).'/pdo.php';

class VentaDAO{
	private $pdo;

	public function __construct($pdo){
		$this->pdo = $pdo;
	}

	public function Listar(){
		try{
			$result = array();
			$stm = $this->pdo->prepare("SELECT * FROM ventas");
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
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Registrar(Venta $data){
		try {
			$sql = "INSERT INTO `venta-platillo` (nombre_venta_platillo,precio_venta_platillo,
			cantidad_platillo,venta_total)VALUES(?,?,?,?)";
			$this->pdo->prepare($sql)->execute(
				array(
						$data->__GET('Nombre'),
						$data->__GET('Precio'),
						$data->__GET('Cantidad'),
						$data->__GET('Venta_total')
					)
				);
			return $this->getID();
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function getID(){
		try{
			$stm=$this->pdo->prepare("SELECT id_venta_platillo FROM `venta-platillo` GROUP BY id_venta_platillo DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r->id_venta_platillo;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}

$pdo = new PDO_connection();

if(isset($_GET['orden'])){
	$stm = $pdo->query("SELECT orden FROM mesas WHERE id = ".$_GET['mesa']);
	$orden = $stm->fetch(PDO::FETCH_OBJ);
	$res = $pdo->query("UPDATE mesas SET orden='".$orden->orden."".$_GET['orden']."' WHERE id=".$_GET['mesa']);
	if($res){
		$array[] = array("msg" => "success");
		echo json_encode($array);
	}
}

//get the dishes by the category
if(isset($_GET['categoria'])){
    $dishes = $pdo->query("SELECT * FROM platillos WHERE categoria_platillo = '".$_GET['categoria']."' AND status_platillo = 'activo'");
	$msg = "";
	if($dishes->rowCount() == 0){
		$msg = "No se encontraron items<br>en la categoria de ".$_GET['categoria']."s";
		$info[] = array("msg" => $msg);
	}else{
		while($row = $dishes->fetch(PDO::FETCH_OBJ)){
			$titulo = $_GET['categoria'] === "'='" ? "Menu" : $row->categoria_platillo."s";
			$info[] = array(
				"titulo" => $titulo,
				"msg" => $msg,
				"nombre" => $row->nombre_platillo,
				"image" => $row->foto_platillo,
				"id" => $row->id_platillo,
				"precio" => $row->precio_platillo,
			);
		}
	}
	echo json_encode($info);
}
?>