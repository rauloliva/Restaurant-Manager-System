<?php
require_once "platillo.dto.php";
require_once dirname(__DIR__).'/pdo.php';

class PlatilloDAO{
	private $pdo;
	
	public function __construct($pdo){
		$this->pdo = $pdo;
	}
	
	public function Listar($condition){
		try{
			$result = array();
			$stm = $this->pdo->prepare("SELECT * FROM platillos".$condition);
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
				$alm->__SET('Foto', $r->foto_platillo);
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
			$alm->__SET('Foto', $r->foto_platillo);
			return $alm;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function Eliminar($id){
		try{
			$stm = $this->pdo->prepare("UPDATE platillos SET status_platillo=? WHERE fecha_degreso_platillo=? id_platillo=?");			          
			$stm->execute(array(date('Y-m-d'),'Desactivado',$id));
			$info = array("msg" => "El Platillo ha sido dado de baja con exito","color"=>"blue");
			return $info;
		}catch(Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al dar de baja","color"=>"red");
			return $info;
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
						'',
						$data->__GET('id')
					)
				);
			$info = array("msg" => "El Platillo ha sido actualizado con exito","color"=>"blue");
			return $info;
		}catch (Exception $e){
			$info = array("msg" => "Ha ocurrido un problema al actualizar","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}
	
	public function Registrar(Platillo $data){
		try{
			$sql = "INSERT INTO  platillos (nombre_platillo,precio_platillo,precio_platillo_venta,
				ingredientes_platillo,categoria_platillo,foto_platillo,status_platillo,
				fecha_ingreso_platillo)VALUES(?,?,?,?,?,?,?,?)";
			$this->pdo->prepare($sql)->execute(
				array(
						$data->__GET('Nombre'),
						$data->__GET('Precio'),
						$data->__GET('Precio_Platillo'),
						$data->__GET('Ingredientes'),
						$data->__GET('Categoria'),
						$data->__GET('Foto'),
						$data->__GET('Status'),
						$data->__GET('FechaDeIngreso')
					)
				);
			$info = array("msg" => "El Platillo ha sido guardado con exito","color"=>"blue");
			return $info;
		}catch(Exception $e) {
			$info = array("msg" => "Ha ocurrido un problema al guardar","color"=>"red");
			return $info;
			die($e->getMessage());
		}
	}
}

if(isset($_GET['value1'])){
	if(isset($_GET['column'])){
		$pdo = new PDO_connection();
		$consult = "SELECT * FROM platillos WHERE ".$_GET['column'];
		if($_GET['value2'] != "name" && $_GET['value2'] != "cat") {
			$consult .= ">=".$_GET['value1']." and ".$_GET['column']."<=".$_GET['value2'];
		}else if($_GET['value2'] == "name"){
			$consult .= " LIKE '".$_GET['value1']."%'";
		}else if($_GET['value2'] == "cat" && $_GET['value1'] != "null"){
			$consult .= " = '".$_GET['value1']."'";
		}else if($_GET['value2'] == "a" || $_GET['value1'] == "null"){
			$consult .= " LIKE '%%'";
		}
		$stm = $pdo->prepare($consult);
		$stm->execute();
		if($stm->rowCount() > 0){
			while($obj = $stm->fetch(PDO::FETCH_OBJ)){
				$arr[] = array(
					"id" => $obj->id_platillo,
					"nombre" => $obj->nombre_platillo,
					"precio" => $obj->precio_platillo,
					"precio_venta" => $obj->precio_platillo_venta,
					"ingredientes" => $obj->ingredientes_platillo,
					"categoria" => $obj->categoria_platillo,
					"status" => $obj->status_platillo,
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