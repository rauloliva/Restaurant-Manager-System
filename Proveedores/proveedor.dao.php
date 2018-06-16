<?php
class ProveedorDAO{
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
			$stm = $this->pdo->prepare("SELECT * FROM proveedores WHERE status_proveedor='Activo'");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Proveedor();
				$alm->__SET('id', $r->id_proveedor);
				$alm->__SET('Nombre', $r->nombre_proveedor);
				$alm->__SET('Correo', $r->correo_proveedor);
				$alm->__SET('Direccion', $r->direccion_proveedor);
				$alm->__SET('Telefono', $r->telefono_proveedor);
				$alm->__SET('Status', $r->status_proveedor);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_proveedor);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_proveedor);
				$alm->__SET('Folio', $r->folio_proveedor);
				$result[] = $alm;
			}
			return $result;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	

	public function Obtener($id){
		try{
			$stm = $this->pdo->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Proveedor();
			$alm->__SET('id', $r->id_proveedor);
			$alm->__SET('Nombre', $r->nombre_proveedor);
			$alm->__SET('Correo', $r->correo_proveedor);
			$alm->__SET('Direccion', $r->direccion_proveedor);
			$alm->__SET('Telefono', $r->telefono_proveedor);
			$alm->__SET('Status', $r->status_proveedor);
			$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_proveedor);
			$alm->__SET('FechaDeEgreso', $r->fecha_degreso_proveedor);
			$alm->__SET('Folio', $r->folio_proveedor);
			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Eliminar($id){
		try{
			$stm = $this->pdo->prepare("UPDATE proveedores SET status_proveedor=? WHERE id_proveedor=?");			          
			$stm->execute(array('Desactivado',$id));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function Actualizar(Proveedor $data){
		try{
			$sql = "UPDATE proveedores SET nombre_proveedor=?,correo_proveedor=?, 
				direccion_proveedor=?,telefono_proveedor=?,status_proveedor=?,fecha_ingreso_proveedor=?, fecha_degreso_proveedor=?, 
				folio_proveedor=? WHERE id_proveedor=?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('Nombre'),
					$data->__GET('Correo'),
					$data->__GET('Direccion'),
					$data->__GET('Telefono'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso'),
					$data->__GET('FechaDeEgreso'),
					$data->__GET('Folio'),
					$data->__GET('id')
					)
				);
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Registrar(Proveedor $data){
		try {
			$sql = "INSERT INTO  proveedores (id_proveedor,nombre_proveedor,correo_proveedor,direccion_proveedor,
			telefono_proveedor,status_proveedor,fecha_ingreso_proveedor,folio_proveedor)VALUES(?,?,
			?,?,?,?,?,?)";
			$data->__SET('id',$this->getID());
			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('id'),
					$data->__GET('Nombre'),
					$data->__GET('Correo'),
					$data->__GET('Direccion'),
					$data->__GET('Telefono'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso'),
					$data->__GET('Folio')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function getID(){
		try{
			$stm=$this->pdo->prepare("SELECT id_proveedor FROM proveedores GROUP BY id_proveedor DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r==0?10000:$r->id_proveedor+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}
