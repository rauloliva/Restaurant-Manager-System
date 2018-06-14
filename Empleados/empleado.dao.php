<?php
class EmpleadoDAO{
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
			$stm = $this->pdo->prepare("SELECT * FROM empleados WHERE status_empleado='Activo'");
			$stm->execute();

			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
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
			return $alm;
		} catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function Eliminar($id){
		try{
			$stm = $this->pdo->prepare("UPDATE empleados SET status_empleado=? WHERE id_empleado=?");			          
			$stm->execute(array('Desactivado',$id));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function Actualizar(Empleado $data){
		try{
			$sql = "UPDATE empleados SET nombre_empleado=?, ap_empleado=?, am_empleado=?, 
             fecha_nac_empleado=?, sexo_empleado=?, direccion_empleado=?, correo_empleado=?, telefono_empleado=?,
             tipo_empleado=?, sueldo_empleado=?, nombre_usuario=?, contrasena_usuario=?,
             status_empleado=?, fecha_ingreso_empleado=?, fecha_degreso_empleado=? WHERE id_empleado=?";

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

	public function Registrar(Empleado $data){
		try {
		$sql = "INSERT INTO empleados (nombre_empleado,ap_empleado,
		 am_empleado,fecha_nac_empleado,sexo_empleado,direccion_empleado,correo_empleado,telefono_empleado,
		 tipo_empleado,sueldo_empleado,nombre_usuario,contrasena_usuario,status_empleado,fecha_ingreso_empleado)VALUES(?,?,
		 ?,?,?,?,?,?,?,?,?,?,?,?)";
		$data->__SET('id',$this->getID());
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
			$stm=$this->pdo->prepare("SELECT id_empleados FROM Empleados GROUP BY id_empleados DESC LIMIT 1");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			return $r->id_empleados==0?10000:$r->id_empleados+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}