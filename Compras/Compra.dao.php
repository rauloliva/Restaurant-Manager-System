<?php
class CompraDAO{
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
			$stm = $this->pdo->prepare("SELECT * FROM compras");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Compra();
				$alm->__SET('id', $r->id_compra);
				$alm->__SET('Hora', $r->hora_compra);
				$alm->__SET('Fecha', $r->fecha_compra);
				$alm->__SET('Total', $r->total_compra);
				$alm->__SET('FolioProv', $r->folio_proveedor);
				$alm->__SET('IdEmpleado', $r->id_empleado);
				$alm->__SET('IdProveedor', $r->id_proveedor);
				$alm->__SET('NombreEmpl', $r->nombre_empleado);
				$alm->__SET('NombreProv', $r->nombre_proveedor);
				$alm->__SET('NombreProducto',$r->nombre_producto);
				$result[] = $alm;
			}
			return $result;


		}catch(Exception $e){
			die($e->getMessage());
		}
	}


	public function Obtener($id){
		try{
			$stm = $this->pdo->prepare("SELECT * FROM compras WHERE id_compra = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Compra();
			$alm->__SET('id', $r->id_compra);
			$alm->__SET('Hora', $r->hora_compra);
			$alm->__SET('Fecha', $r->fecha_compra);
			$alm->__SET('Total', $r->total_compra);
			$alm->__SET('FolioProv', $r->folio_proveedor);
			$alm->__SET('IdEmpleado', $r->id_empleado);
			$alm->__SET('IdProveedor', $r->id_proveedor);
			$alm->__SET('NombreEmpl', $r->nombre_empleado);
			$alm->__SET('NombreProv', $r->nombre_proveedor);
			$alm->__SET('NombreProducto',$r->nombre_producto);

			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Registrar(Compra $data){
		try {
		$sql = "INSERT INTO  Compras (hora_compra,fecha_compra,total_compra,
		 folio_proveedor,id_empleado,id_proveedor,nombre_empleado,nombre_proveedor,nombre_producto)VALUES(?,?,
		 ?,?,?,?,?,?,?)";
		$data->__SET('id',$this->getID());
		$this->pdo->prepare($sql)->execute(
			array(
					$data->__GET('Hora'),
					$data->__GET('Fecha'),
					$data->__GET('Total'),
					$data->__GET('FolioProv'),
					$data->__GET('IdEmpleado'),
					$data->__GET('IdProveedor'),
					$data->__GET('NombreEmpl'),
					$data->__GET('NombreProv'),
					$data->__GET('NombreProducto')
				)
			);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function getID(){
		try{
			$stm=$this->pdo->prepare("SELECT id_compra FROM compras GROUP BY id_compra DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r==null?10000:$r->id_compra+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}
