<?php
require_once 'empleado.dto.php';
require_once 'empleado.dao.php';

// Logica
$alm = new Empleado();
$model = new EmpleadoDAO();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'actualizar':
			$alm->__SET('id',              $_REQUEST['id']);
			$alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Apellido_paterno',$_REQUEST['Apellido_paterno']);
            $alm->__SET('Apellido_materno',$_REQUEST['Apellido_materno']);
			$alm->__SET('FechaNacimiento', $_REQUEST['FechaNacimiento']);
			$alm->__SET('Sexo',            $_REQUEST['Sexo']);
			$alm->__SET('Direccion',       $_REQUEST['Direccion']);
			$alm->__SET('Correo',          $_REQUEST['Correo']);
			$alm->__SET('Telefono',        $_REQUEST['Telefono']);
			$alm->__SET('Tipo',            $_REQUEST['Tipo']);
			$alm->__SET('Sueldo',          $_REQUEST['Sueldo']);
			$alm->__SET('Nombre_user',     $_REQUEST['Nombre_user']);
			$alm->__SET('Contraseña_user', $_REQUEST['Contraseña_user']);
			$alm->__SET('Status',          $_REQUEST['Status']);
			$alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$alm->__SET('FechaDeEgreso',   $_REQUEST['FechaDeEgreso']);
			$model->Actualizar($alm);
			header('Location: empleado.index.php');
			break;

		case 'registrar':
			$alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Apellido_paterno',$_REQUEST['Apellido_paterno']);
            $alm->__SET('Apellido_materno',$_REQUEST['Apellido_materno']);
			$alm->__SET('FechaNacimiento', $_REQUEST['FechaNacimiento']);
			$alm->__SET('Sexo',            $_REQUEST['Sexo']);
			$alm->__SET('Direccion',       $_REQUEST['Direccion']);
			$alm->__SET('Correo',          $_REQUEST['Correo']);
			$alm->__SET('Telefono',        $_REQUEST['Telefono']);
			$alm->__SET('Tipo',            $_REQUEST['Tipo']);
			$alm->__SET('Sueldo',          $_REQUEST['Sueldo']);
			$alm->__SET('Nombre_user',     $_REQUEST['Nombre_user']);
            $alm->__SET('Contraseña_user', $_REQUEST['Contraseña_user']);
            $alm->__SET('Status',          'Activo');
            $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$model->Registrar($alm);
			header('Location: empleado.index.php');
			break;

		case 'eliminar':
			$model->Eliminar($_REQUEST['id']);
			header('Location: empleado.index.php');
			break;

		case 'editar':
			$alm = $model->Obtener($_REQUEST['id']);
			break;
	}
}

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Anexsoft</title>
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
	</head>
    <body style="padding:15px;">
        <h1 align="center">Empleados</h1>

        <div class="pure-g">
            <div class="pure-u-1-12">
                
                <form action="?action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="id" value="<?php echo $alm->__GET('id'); ?>" />        
                    <input type="hidden" name="Status" value="<?php echo $alm->__GET('Status'); ?>"/>
                        
                    
                    <table style="width:500px;">
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <td><input type="text" name="Nombre" value="<?php echo $alm->__GET('Nombre'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Apellido Paterno</th>
                            <td><input type="text" name="Apellido_paterno" value="<?php echo $alm->__GET('Apellido_paterno'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Apellido Materno</th>
                            <td><input type="text" name="Apellido_materno" value="<?php echo $alm->__GET('Apellido_materno'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha</th>
                            <td><input type="text" name="FechaNacimiento" value="<?php echo $alm->__GET('FechaNacimiento'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Sexo</th>
                            <td>
                                <select name="Sexo" style="width:100%;">
                                    <option value="Masculino" <?php echo $alm->__GET('Sexo')=='Masculino'?'selected':''; ?>>Masculino</option>
                                    <option value="Femenino" <?php echo $alm->__GET('Sexo')=='Femenino'?'selected':''; ?>>Femenino</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Direccion</th>
                            <td><textarea name="Direccion" style="width:100%;"><?php echo $alm->__GET('Direccion'); ?></textarea></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Correo</th>
                            <td><input type="text" name="Correo" value="<?php echo $alm->__GET('Correo'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Telefono</th>
                            <td><input type="text" name="Telefono" value="<?php echo $alm->__GET('Telefono'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Tipo</th>
                            <td>
                                <select name="Tipo" style="width:100%;">
                                    <option value="Dueño" <?php echo $alm->__GET('Tipo')=='Dueño'?'selected':''; ?>>Dueño</option>
                                    <option value="Gerente" <?php echo $alm->__GET('Tipo')=='Gerente'?'selected':''; ?>>Gerente</option>
                                    <option value="Administrador Ventas" <?php echo $alm->__GET('Tipo')=='Administrador Ventas'?'selected':''; ?>>Administrador Ventas</option>
                                    <option value="Administrador Productos" <?php echo $alm->__GET('Tipo')=='Administrador Productos'?'selected':''; ?>>Administrador Productos</option>
                                    <option value="Mesero" <?php echo $alm->__GET('Tipo')=='Mesero'?'selected':''; ?>>Mesero</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Sueldo</th>
                            <td><input type="text" name="Sueldo" value="<?php echo $alm->__GET('Sueldo'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Usuario</th>
                            <td><input type="text" name="Nombre_user" value="<?php echo $alm->__GET('Nombre_user'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Contraseña</th>
                            <td><input type="password" name="Contraseña_user" value="<?php echo $alm->__GET('Contraseña_user'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Ingreso</th>
                            <td><input type="text" name="FechaDeIngreso" value="<?php echo $alm->__GET('FechaDeIngreso'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" class="pure-button pure-button-primary" >Guardar</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <table class="pure-table pure-table-horizontal">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <th style="text-align:left;">Apellido Paterno</th>
                            <th style="text-align:left;">Apellido Materno</th>
                            <th style="text-align:left;">Nacimiento</th>
                            <th style="text-align:left;">Sexo</th>
                            <th style="text-align:left;">Direccion</th>
                            <th style="text-align:left;">Correo</th>
                            <th style="text-align:left;">Telefono</th>
                            <th style="text-align:left;">Tipo</th>
                            <th style="text-align:left;">Sueldo</th>
                            <th style="text-align:left;">Nombre_user</th>
                            <th style="text-align:left;">Contraseña_user</th>
                            <th style="text-align:left;">Status</th>
                            <th style="text-align:left;">Fecha Ingreso</th>
                            <th style="text-align:left;">Fecha Egreso</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Nombre'); ?></td>
                            <td><?php echo $r->__GET('Apellido_paterno'); ?></td>
                            <td><?php echo $r->__GET('Apellido_materno'); ?></td>
                            <td><?php echo $r->__GET('FechaNacimiento'); ?></td>
                            <td><?php echo $r->__GET('Sexo');?></td>
                            <td><?php echo $r->__GET('Direccion'); ?></td>
                            <td><?php echo $r->__GET('Correo'); ?></td>
                            <td><?php echo $r->__GET('Telefono'); ?></td>
                            <td>
                            <?php 
                            $opc=$r->__GET('Tipo'); 
                            if($opc==1){
                                $opc='Dueño';
                            }else if($opc==2){
                                $opc='Gerente';
                            }else if($opc==3){
                                $opc='Administrador Ventas';
                            }else if($opc==4){
                                $opc='Administrador Productos';
                            }else if($opc==5){
                                $opc='Mesero';
                            }
                            echo $opc; 
                            ?></td>
                            <td><?php echo $r->__GET('Sueldo'); ?></td>
                            <td><?php echo $r->__GET('Nombre_user'); ?></td>
                            <td><?php echo $r->__GET('Contraseña_user'); ?></td>
                            <td><?php echo $r->__GET('Status'); ?></td>
                            <td><?php echo $r->__GET('FechaDeIngreso'); ?></td>
                            <td><?php echo $r->__GET('FechaDeEgreso'); ?></td>
                            <td>
                                <a href="?action=editar&id=<?php echo $r->id; ?>">Editar</a>
                            </td>
                            <td>
                                <a href="?action=eliminar&id=<?php echo $r->id; ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>     
              
            </div>
        </div>

    </body>
</html>