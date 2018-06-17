<?php
class VentaDAO{
	private $pdo;

	public function __CONSTRUCT(){
		try{
			$this->pdo = new PDO('mysql:host=127.0.0.1;dbname=Restaurante', 'root', 'raulito10');
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		        
		}
		catch(Exception $e){
			die($e->getMessage());
		}
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
		$sql = "INSERT INTO  ventas (total_venta,hora_venta,fecha_venta,
		 id_cliente,id_empleado,id_venta_platillo)VALUES(?,?,
		 ?,?,?,?)";
		$data->__SET('id',$this->getID());
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
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function getID(){
		try{
			$stm=$this->pdo->prepare("SELECT id_venta FROM ventas GROUP BY id_venta DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r==null?10000:$r->id_venta+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}