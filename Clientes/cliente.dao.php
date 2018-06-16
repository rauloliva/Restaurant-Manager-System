<?php
class ClienteDAO{
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
			$stm = $this->pdo->prepare("SELECT * FROM clientes WHERE status_cliente='Activo'");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Cliente();
				$alm->__SET('id', $r->id_cliente);
				$alm->__SET('Nombre', $r->nombre_cliente);
				$alm->__SET('Apellido_paterno', $r->ap_cliente);
				$alm->__SET('Apellido_materno', $r->am_cliente);
				$alm->__SET('Status', $r->status_cliente);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_cliente);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_cliente);		
				$alm->__SET('Correo', $r->correo_cliente);
				$alm->__SET('RFC', $r->RFC_cliente);
				$result[] = $alm;
			}
			return $result;
		
	
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	

	public function Obtener($id){
		try{
			$stm = $this->pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Cliente();
			$alm->__SET('id', $r->id_cliente);
			$alm->__SET('Nombre', $r->nombre_cliente);
			$alm->__SET('Apellido_paterno', $r->ap_cliente);
			$alm->__SET('Apellido_materno', $r->am_cliente);
			$alm->__SET('Status', $r->status_cliente);
			$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_cliente);
			$alm->__SET('FechaDeEgreso', $r->fecha_degreso_cliente);
			$alm->__SET('Correo', $r->correo_cliente);
			$alm->__SET('RFC', $r->RFC_cliente);
			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Eliminar($id){
		try{
			$stm = $this->pdo->prepare("UPDATE clientes SET status_cliente=? WHERE id_cliente=?");			          
			$stm->execute(array('Desactivado',$id));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function Actualizar(Cliente $data){
		try{
			$sql = "UPDATE clientes SET nombre_cliente=?, ap_cliente=?, am_cliente=?, 
             status_cliente=?, fecha_ingreso_cliente=?, 
			 fecha_degreso_cliente=?, correo_cliente=?, RFC_cliente=? WHERE id_cliente=?";

			$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('Nombre'),
					$data->__GET('Apellido_paterno'),
					$data->__GET('Apellido_materno'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso'),
					$data->__GET('FechaDeEgreso'),
					$data->__GET('Correo'),
					$data->__GET('RFC'),
					$data->__GET('id'),
					)
				);
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Registrar(Cliente $data){
		try {
		$sql = "INSERT INTO  clientes (nombre_cliente,ap_cliente,am_cliente,
		 status_cliente,fecha_ingreso_cliente,correo_cliente,RFC_cliente)VALUES(?,?,
		 ?,?,?,?,?)";
		$data->__SET('id',$this->getID());
		$this->pdo->prepare($sql)->execute(
			array(
					$data->__GET('Nombre'),
					$data->__GET('Apellido_paterno'),
					$data->__GET('Apellido_materno'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso'),
					$data->__GET('Correo'),
					$data->__GET('RFC')
				)
			);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function getID(){
		try{
			$stm=$this->pdo->prepare("SELECT id_cliente FROM clientes GROUP BY id_cliente DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r==null?10000:$r->id_cliente+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}
