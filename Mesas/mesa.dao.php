<?php
require_once "mesa.dto.php";
require_once dirname(__DIR__).'/pdo.php';

class MesaDAO{
	private $pdo;

	public function __construct($pdo){
		$this->pdo = $pdo;
	}

	public function Listar($condition){
		try{
			$result = array();
			$stm = $this->pdo->prepare("SELECT * FROM mesas".$condition);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$alm = new Mesa();
				$alm->__SET('mesa',$r->id);
				$alm->__SET('mesero', $r->mesero);
				$alm->__SET('estatus', $r->Estatus);	
				$result[] = $alm;
			}
			return $result;
		}
		catch(Exception $e){
			die($e->getMessage());
		}
	}

	public function Action($num_mesas,$num_mesas_actual){
		if($num_mesas > $num_mesas_actual){
			$info = $this->AddMesas($num_mesas,$num_mesas_actual);
		}else{
			$info = $this->RemoveMesas($num_mesas,$num_mesas_actual);
		}
		return $info;
	}

	private function AddMesas($mesas,$mesas_actual){
		try {
			for ($i = $mesas_actual+1; $i <= $mesas; $i++) { 
				$stm = $this->pdo->prepare("INSERT INTO mesas VALUES (?,'',0,'','')");
				$stm->execute(array($i));
			}
			$res = $mesas - $mesas_actual;
			$msg = ($res == 1) ? "mesa ha sido agregada con exito" : "mesas han sido agregadas con exito" ;
			$info = array("msg" => $res." ".$msg,"color"=>"blue");
			return $info;
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	private function RemoveMesas($mesas,$mesas_actual){
		try {
			$stm = $this->pdo->prepare("DELETE FROM mesas WHERE id > ?");
			$stm->execute(array($mesas));

			$res = $mesas_actual - $mesas;
			$msg = ($res == 1) ? "mesa ha sido eliminada con exito" : "mesas han sido eliminadas con exito" ;
			$info = array("msg" => $res." ".$msg,"color"=>"blue");
			return $info;
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}

$pdo = new PDO_connection();

if(isset($_GET['mesa'])){
	$stm = $pdo->prepare("DELETE FROM mesas WHERE id = ?");
	$stm->execute(array($_GET['mesa']));
	$arr[] = array("flag" => "1");
	echo json_encode($arr);
}

if(isset($_GET['list'])){
	if(isset($_GET['column'])){
		if($_GET['column'] == "id" && $_GET['list'] != "" ){
			$stm = $pdo->prepare("SELECT * FROM mesas WHERE ".$_GET['column']." = ?");	
			$stm->execute([$_GET['list']]);
		}else if($_GET['column'] == "mesero"){
			$stm = $pdo->prepare("SELECT * FROM mesas WHERE ".$_GET['column']." LIKE ?");
			$stm->execute([$_GET['list']."%"]);	
		}else{
			if($_GET['list'] == "seleccionar"){
				$stm = $pdo->prepare("SELECT * FROM mesas");	
				$stm->execute();	
			}else{
				$stm = $pdo->prepare("SELECT * FROM mesas WHERE ".$_GET['column']." = ?");	
				$stm->execute([$_GET['list']]);
			}
		}
		if($stm->rowCount() > 0){
			while($obj = $stm->fetch(PDO::FETCH_OBJ)){
				$arr[] = array(
					"mesa" => $obj->id,
					"mesero" => $obj->mesero,
					"estatus" => $obj->Estatus,
					"msg" => ""
				);
			}
			echo json_encode($arr);
		}else{
			$error[] = array("msg" => "No informacion");
			echo json_encode($error);
		}
	}
}
?>