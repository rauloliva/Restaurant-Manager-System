<?php
require_once "empleado.dto.php";
require_once dirname(__DIR__).'/pdo.php';

class EmpleadoDAO{
	private $pdo;

	public function __construct($pdo){
		$this->pdo = $pdo;
	}

	public function Listar($condition){
		try{
			$result = array();
			$stm = $this->pdo->prepare("SELECT * FROM empleados".$condition);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Empleado();
				$alm->__SET('id',$r->id_empleado);
				$alm->__SET('Nombre', $r->nombre_empleado);
				$alm->__SET('Apellido_paterno', $r->ap_empleado);
				$alm->__SET('Apellido_materno', $r->am_empleado);
				$alm->__SET('FechaNacimiento', $r->fecha_nac_empleado);
				$alm->__SET('Sexo',$r->sexo_empleado);
				$alm->__SET('Direccion', $r->direccion_empleado);
				$alm->__SET('Correo', $r->correo_empleado);
				$alm->__SET('Telefono', $r->telefono_empleado);
				$alm->__SET('Tipo', $r->tipo_empleado);
				$alm->__SET('Sueldo', $r->sueldo_empleado);
				$alm->__SET('Nombre_user', $r->nombre_usuario);
				$alm->__SET('Contraseña_user', $r->contrasena_usuario);
				$alm->__SET('Status', $r->status_empleado);
				$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_empleado);
				$alm->__SET('FechaDeEgreso', $r->fecha_degreso_empleado);	
				$alm->__SET('Foto', $r->foto_empleado);	
				$result[] = $alm;
			}
			return $result;
		}
		catch(Exception $e){
			die($e->getMessage());
		}
	}

	public function Obtener($id){
		try{
			$stm = $this->pdo->prepare("SELECT * FROM empleados WHERE id_empleado = ?");
			$stm->execute(array($id));
			$r = $stm->fetch(PDO::FETCH_OBJ);
			$alm = new Empleado();
			$alm->__SET('id', $r->id_empleado);
			$alm->__SET('Nombre', $r->nombre_empleado);
			$alm->__SET('Apellido_paterno', $r->ap_empleado);
			$alm->__SET('Apellido_materno', $r->am_empleado);
			$alm->__SET('FechaNacimiento', $r->fecha_nac_empleado);
			$alm->__SET('Sexo',$r->sexo_empleado);
			$alm->__SET('Direccion', $r->direccion_empleado);
			$alm->__SET('Correo', $r->correo_empleado);
			$alm->__SET('Telefono', $r->telefono_empleado);
			$alm->__SET('Tipo', $r->tipo_empleado);
			$alm->__SET('Sueldo', $r->sueldo_empleado);
			$alm->__SET('Nombre_user', $r->nombre_usuario);
			$alm->__SET('Contraseña_user', $r->contrasena_usuario);
			$alm->__SET('Status', $r->status_empleado);
			$alm->__SET('FechaDeIngreso', $r->fecha_ingreso_empleado);
			$alm->__SET('FechaDeEgreso', $r->fecha_degreso_empleado);
			$alm->__SET('Foto', $r->foto_empleado);
			$alm->__SET('Status', $r->status_empleado);
			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Eliminar($id){
		try{		
			$stm = $this->pdo->prepare("UPDATE empleados SET status_empleado=?, 
				fecha_degreso_empleado=? WHERE id_empleado=?");			          
			$stm->execute(array('Desactivado',date("Y-m-d"),$id));
			$info = array("msg" => "El Empleado ha sido dado de baja con exito","color"=>"blue");
			return $info;
		}catch(Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al dar de baja","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}

	public function Actualizar(Empleado $data,$foto){
		try{
			$array = array(
				$data->__GET('Nombre'),
				$data->__GET('Apellido_paterno'),
				$data->__GET('Apellido_materno'),
				$data->__GET('FechaNacimiento'),
				$data->__GET('Sexo'),
				$data->__GET('Direccion'),
				$data->__GET('Correo'),
				$data->__GET('Telefono'),
				$data->__GET('Tipo'),
				$data->__GET('Sueldo'),
				$data->__GET('Nombre_user'),
				$data->__GET('Contraseña_user'),
				$data->__GET('Status'),
				$data->__GET('FechaDeIngreso'),
				'',
			);
			$sql = "UPDATE empleados SET nombre_empleado=?, ap_empleado=?, am_empleado=?, 
             fecha_nac_empleado=?, sexo_empleado=?, direccion_empleado=?, correo_empleado=?, telefono_empleado=?,
             tipo_empleado=?, sueldo_empleado=?, nombre_usuario=?, contrasena_usuario=?,
             status_empleado=?, fecha_ingreso_empleado=?, fecha_degreso_empleado=? ".$foto." WHERE id_empleado=?";
			if($foto != ''){
				array_push($array,$data->__GET('Foto'),$data->__GET('id'));
			}else{
				array_push($array,$data->__GET('id'));
			}
			$this->pdo->prepare($sql)->execute($array);
			$info = array("msg" => "El Empleado ha sido actualizado con exito","color"=>"blue");
			return $info;
		}catch (Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al actualizar","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}

	public function Registrar(Empleado $data){
		try {
		$sql = "INSERT INTO empleados (nombre_empleado,ap_empleado,
		 am_empleado,fecha_nac_empleado,sexo_empleado,direccion_empleado,correo_empleado,telefono_empleado,
		 tipo_empleado,sueldo_empleado,nombre_usuario,contrasena_usuario,foto_empleado,status_empleado,fecha_ingreso_empleado)VALUES(?,?,
		 ?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$this->pdo->prepare($sql)->execute(
			array(
					$data->__GET('Nombre'),
					$data->__GET('Apellido_paterno'),
					$data->__GET('Apellido_materno'),
					$data->__GET('FechaNacimiento'),
					$data->__GET('Sexo'),
					$data->__GET('Direccion'),
					$data->__GET('Correo'),
					$data->__GET('Telefono'),
					$data->__GET('Tipo'),
					$data->__GET('Sueldo'),
					$data->__GET('Nombre_user'),
					$data->__GET('Contraseña_user'),
					$data->__GET('Foto'),
					$data->__GET('Status'),
					$data->__GET('FechaDeIngreso')
				)
			);
			$info = array("msg" => "El Empleado ha sido guardado con exito","color"=>"blue");
			return $info;
		}catch(Exception $e) {
			$info = array("msg" => "Ha ocurrido un problema al guardar","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}
}

$pdo = new PDO_connection();

if(isset($_GET['list'])){
	if(isset($_GET['column'])){
		$stm = $pdo->prepare("SELECT * FROM empleados WHERE ".$_GET['column']." LIKE ?");
		$stm->execute([$_GET['list']."%"]);
		if($stm->rowCount() > 0){
			while($obj = $stm->fetch(PDO::FETCH_OBJ)){
				$arr[] = array(
					"id" => $obj->id_empleado,
					"nombre" => $obj->nombre_empleado,
					"ap" => $obj->ap_empleado,
					"am" => $obj->am_empleado,
					"fecha_nac" => $obj->fecha_nac_empleado,
					"sexo" => $obj->sexo_empleado,
					"correo" => $obj->correo_empleado,
					"telefono" => $obj->telefono_empleado,
					"tipo" => $obj->tipo_empleado,
					"sueldo" => $obj->sueldo_empleado,
					"status" => $obj->status_empleado,
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

if(isset($_GET['val_username'])){
	$msg = "";
	$stm = $pdo->prepare("SELECT nombre_usuario FROM empleados");
	$stm->execute();
	while($row = $stm->fetch(PDO::FETCH_OBJ)){
		if($_GET['val_username'] === $row->nombre_usuario){
			$msg = "El nombre de usuario ya lo esta usando<br>alguien mas";
			break;
		}
	}
	$duplicate[] = array("msg" => $msg);
	echo json_encode($duplicate);
}
?>