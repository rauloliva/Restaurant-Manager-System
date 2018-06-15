<?php
class ProductoDAO{
	private $pdo;

	public function __CONSTRUCT()
	{
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
			$stm = $this->pdo->prepare("SELECT * FROM productos WHERE status_producto='Activo'");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Producto();
				$alm->__SET('id', $r->id_producto);
				$alm->__SET('Existencia', $r->existencia_producto);
				$alm->__SET('Categoria', $r->categoria_producto);
				$alm->__SET('Nombre', $r->nombre_producto);
				$alm->__SET('Precio', $r->precio_producto);
				$alm->__SET('Medida', $r->medida_producto);
				$alm->__SET('Gestor_Min', $r->gestor_min_producto);
				$alm->__SET('Gestor_Max', $r->gestor_max_producto);
				$alm->__SET('Status', $r->status_producto);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_producto);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_producto);		
				$result[] = $alm;
			}
			return $result;
		
	
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function Obtener($id)
	{
		try{
			$stm = $this->pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Producto();
			$alm->__SET('id', $r->id_producto);
				$alm->__SET('Existencia', $r->existencia_producto);
				$alm->__SET('Categoria', $r->categoria_producto);
				$alm->__SET('Nombre', $r->nombre_producto);
				$alm->__SET('Precio', $r->precio_producto);
				$alm->__SET('Medida', $r->medida_producto);
				$alm->__SET('Gestor_Min', $r->gestor_min_producto);
				$alm->__SET('Gestor_Max', $r->gestor_max_producto);
				$alm->__SET('Status', $r->status_producto);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_producto);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_producto);
			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}
	
	public function Eliminar($id)
	{
		try{
			$stm = $this->pdo->prepare("UPDATE productos SET status_producto=? WHERE id_producto=?");			          
			$stm->execute(array('Desactivado',$id));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
	
	public function Actualizar(Producto $data){
		try{
			$sql = "UPDATE productos SET existencia_producto=?, categoria_producto=?, nombre_producto=?, precio_producto=?,
			medida_producto=?, gestor_min_producto=?, gestor_max_producto=?, status_producto=?, 
			fecha_ingreso_producto=?, fecha_degreso_producto=? WHERE id_producto=?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('Existencia'),
					$data->__GET('Categoria'),
					$data->__GET('Nombre'),
					$data->__GET('Precio'),
					$data->__GET('Medida'),
					$data->__GET('Gestor_Min'),
					$data->__GET('Gestor_Max'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso'),
					$data->__GET('FechaDeEgreso'),
					$data->__GET('id'),
					)
				);
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Registrar(Producto $data){
		try {
		$sql = "INSERT INTO productos (id_producto,existencia_producto,	
		 categoria_producto,nombre_producto,precio_producto,medida_producto,gestor_min_producto,gestor_max_producto,
		 status_producto,fecha_ingreso_producto)VALUES(?,?,
		 ?,?,?,?,?,?,?,?)";
		$data->__SET('id',$this->getID());
		$this->pdo->prepare($sql)->execute(
			array(
					$data->__GET('id'),
					$data->__GET('Existencia'),
					$data->__GET('Categoria'),
					$data->__GET('Nombre'),
					$data->__GET('Precio'),
					$data->__GET('Medida'),
					$data->__GET('Gestor_Min'),
					$data->__GET('Gestor_Max'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function getID()
	{
		try{
			$stm=$this->pdo->prepare("SELECT id_producto FROM productos GROUP BY id_producto DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r==null?10000:$r->id_producto+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}