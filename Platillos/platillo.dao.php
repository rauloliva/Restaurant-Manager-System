<?php
class PlatilloDAO{
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
			$stm = $this->pdo->prepare("SELECT * FROM platillos WHERE status_platillo='Activo'");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Platillo();
				$alm->__SET('id', $r->id_platillo);
				$alm->__SET('Nombre', $r->nombre_platillo);
				$alm->__SET('Precio', $r->precio_platillo);
				$alm->__SET('Precio_Platillo', $r->precio_platillo_venta);
				$alm->__SET('Ingredientes', $r->ingredientes_platillo);
				$alm->__SET('Categoria', $r->categoria_platillo);
				$alm->__SET('Status', $r->status_platillo);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_platillo);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_platillo);
				$result[] = $alm;
			}
			return $result;
		
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function Obtener($id){
		try{
			$stm = $this->pdo->prepare("SELECT * FROM platillos WHERE id_platillo = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Platillo();
				$alm->__SET('id', $r->id_platillo);
				$alm->__SET('Nombre', $r->nombre_platillo);
				$alm->__SET('Precio', $r->precio_platillo);
				$alm->__SET('Precio_Platillo', $r->precio_platillo_venta);
				$alm->__SET('Ingredientes', $r->ingredientes_platillo);
				$alm->__SET('Categoria', $r->categoria_platillo);
				$alm->__SET('Status', $r->status_platillo);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_platillo);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_platillo);
			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}
	
	public function Eliminar($id){
		try{
			$stm = $this->pdo->prepare("UPDATE platillos SET status_platillo=? WHERE id_platillo=?");			          
			$stm->execute(array('Desactivado',$id));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
	
	public function Actualizar(Platillo $data){
		try{
			$sql = "UPDATE platillos SET nombre_platillo=?, precio_platillo=?, precio_platillo_venta=?, 
            ingredientes_platillo=?, categoria_platillo=?, 
			status_platillo=?, fecha_ingreso_platillo=?, fecha_degreso_platillo=? WHERE id_platillo=?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('Nombre'),
					$data->__GET('Precio'),
					$data->__GET('Precio_Platillo'),
					$data->__GET('Ingredientes'),
					$data->__GET('Categoria'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso'),
					$data->__GET('FechaDeEgreso'),
					$data->__GET('id')
					)
				);
		} catch (Exception $e){
			die($e->getMessage());
		}
	}
	
	public function Registrar(Platillo $data){
		try {
		$sql = "INSERT INTO  platillos (id_platillo,nombre_platillo,precio_platillo,precio_platillo_venta,
		ingredientes_platillo,categoria_platillo,status_platillo,
		fecha_ingreso_platillo)VALUES(?,?,?,?,?,?,?,?)";
		$data->__SET('id',$this->getID());
		$this->pdo->prepare($sql)->execute(
			array(
					$data->__GET('id'),
					$data->__GET('Nombre'),
					$data->__GET('Precio'),
					$data->__GET('Precio_Platillo'),
					$data->__GET('Ingredientes'),
					$data->__GET('Categoria'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	public function getID(){
		try{
			$stm=$this->pdo->prepare("SELECT id_platillo FROM platillos GROUP BY id_platillo DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r->id_platillo==0?10000:$r->id_platillo+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

}