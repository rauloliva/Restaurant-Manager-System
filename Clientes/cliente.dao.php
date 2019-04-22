<?php
require_once "cliente.dto.php";
require_once dirname(__DIR__)."/pdo.php";

class ClienteDAO{
	private $pdo;

	public function __construct($pdo){
		$this->pdo = $pdo;
	}

	public function Listar($condition){
		try{
			$result = array();
			$stm = $this->pdo->prepare("SELECT * FROM clientes".$condition);
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
			$stm = $this->pdo->prepare("UPDATE clientes SET fecha_degreso_cliente=?,status_cliente=? WHERE id_cliente=?");			          
			$stm->execute(array(date('Y-m-d'),'Desactivado',$id));
			$info = array("msg" => "Cliente dado de baja con exito","color"=>"blue");
			return $info;
		} catch (Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al dar de baja","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}

	public function Actualizar(Cliente $data){
		try{
			$sql = "UPDATE clientes SET nombre_cliente=?, ap_cliente=?, am_cliente=?, 
             status_cliente=?, fecha_ingreso_cliente=?, fecha_degreso_cliente=?, 
			 correo_cliente=?, RFC_cliente=? WHERE id_cliente=?";
			$array = array(
				$data->__GET('Nombre'),
				$data->__GET('Apellido_paterno'),
				$data->__GET('Apellido_materno'),
				$data->__GET('Status'),
				$data->__GET('FechaDeIngreso'),
				'',
				$data->__GET('Correo'),
				$data->__GET('RFC'),
				$data->__GET('id')
			);
			$this->pdo->prepare($sql)->execute($array);
			$info = array("msg" => "El Cliente ha sido actualizado con exito","color"=>"blue");
			return $info;
		}catch (Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al dar de baja","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}

	public function Registrar(Cliente $data){
		try {
			$sql = "INSERT INTO  clientes (nombre_cliente,ap_cliente,am_cliente,
			status_cliente,fecha_ingreso_cliente,correo_cliente,RFC_cliente)VALUES(?,?,
			?,?,?,?,?)";
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
			$info = array("msg" => "El Cliente ha sido guardado con exito","color"=>"blue");
			return $info;
		}catch (Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al guardar","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}
}

if(isset($_GET['list'])){
	if(isset($_GET['column'])){
		$pdo = new PDO_connection();
		$stm = $pdo->prepare("SELECT * FROM clientes WHERE ".$_GET['column']." LIKE ? "); 
		$stm->execute([$_GET['list']."%"]);
		if($stm->rowCount() > 0){
			while($obj = $stm->fetch(PDO::FETCH_OBJ)){
				$arr[] = array(
					"id" => $obj->id_cliente,
					"nombre" => $obj->nombre_cliente,
					"ap" => $obj->ap_cliente,
					"am" => $obj->am_cliente,
					"correo" => $obj->correo_cliente,
					"status" => $obj->status_cliente,
					"RFC" => $obj->RFC_cliente,
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