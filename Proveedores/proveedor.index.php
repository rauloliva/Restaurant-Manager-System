<?php
require_once 'proveedor.dto.php';
require_once 'proveedor.dao.php';

// Logica
$alm = new Proveedor();
$model = new ProveedorDAO();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'actualizar':
			$alm->__SET('id',              $_REQUEST['id']);
			$alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Correo',          $_REQUEST['Correo']);
            $alm->__SET('Direccion',       $_REQUEST['Direccion']);
			$alm->__SET('Telefono',        $_REQUEST['Telefono']);
			$alm->__SET('Status',          $_REQUEST['Status']);
			$alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$alm->__SET('FechaDeEgreso',   $_REQUEST['FechaDeEgreso']);
			$alm->__SET('Folio',           $_REQUEST['Folio']);
			$model->Actualizar($alm);
			header('Location: proveedor.index.php');
			break;

		case 'registrar':
            $alm->__SET('Nombre',          $_REQUEST['Nombre']);
            $alm->__SET('Correo',          $_REQUEST['Correo']);
            $alm->__SET('Direccion',       $_REQUEST['Direccion']);
            $alm->__SET('Telefono',        $_REQUEST['Telefono']);
            $alm->__SET('Status',          'Activo');
            $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
            $alm->__SET('Folio',           $_REQUEST['Folio']);
			$model->Registrar($alm);
			header('Location: proveedor.index.php');
			break;

		case 'eliminar':
			$model->Eliminar($_REQUEST['id']);
			header('Location: proveedor.index.php');
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
        <h1 align="center">Proveedores</h1>
        <div class="pure-g">
            <div class="pure-u-1-12">
                
                <form action="?action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="id" value="<?php echo $alm->__GET('id'); ?>" />
                    
                    <table style="width:500px;">
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <td><input type="text" name="Nombre" value="<?php echo $alm->__GET('Nombre'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Correo</th>
                            <td><input type="email" name="Correo" value="<?php echo $alm->__GET('Correo'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Direccion</th>
                            <td><textarea name="Direccion" style="width:100%;" ><?php echo $alm->__GET('Direccion'); ?></textarea></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Telefono</th>
                            <td><input type="number" name="Telefono" value="<?php echo $alm->__GET('Telefono'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="Status" value="<?php echo $alm->__GET('Status'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Ingreso</th>
                            <td><input type="date" name="FechaDeIngreso" value="<?php echo $alm->__GET('FechaDeIngreso'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Folio</th>
                            <td><input type="text" name="Folio" value="<?php echo $alm->__GET('Folio'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" class="pure-button pure-button-primary">Guardar</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <table class="pure-table pure-table-horizontal">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <th style="text-align:left;">Correo</th>
                            <th style="text-align:left;">Direccion</th>
                            <th style="text-align:left;">Telefono</th>
                            <th style="text-align:left;">Status</th>
                            <th style="text-align:left;">Fecha Ingreso</th>
                            <th style="text-align:left;">Fecha Egreso</th>
                            <th style="text-align:left;">Folio</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Nombre'); ?></td>
                            <td><?php echo $r->__GET('Correo'); ?></td>
                            <td><?php echo $r->__GET('Direccion'); ?></td>
                            <td><?php echo $r->__GET('Telefono'); ?></td>
                            <td><?php echo $r->__GET('Status'); ?></td>
                            <td><?php echo $r->__GET('FechaDeIngreso'); ?></td>
                            <td><?php echo $r->__GET('FechaDeEgreso'); ?></td>
                            <td><?php echo $r->__GET('Folio'); ?></td>
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
