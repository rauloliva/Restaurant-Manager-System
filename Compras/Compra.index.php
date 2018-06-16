<?php
require_once 'Compra.dto.php';
require_once 'Compra.dao.php';

// Logica
$alm = new Compra();
$model = new CompraDAO();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'registrar':
            $alm->__SET('Hora',          $_REQUEST['Hora']);
            $alm->__SET('Fecha',         $_REQUEST['Fecha']);
            $alm->__SET('Total',         $_REQUEST['Total']);
            $alm->__SET('FolioProv',     $_REQUEST['FolioProv']);
            $alm->__SET('IdEmpleado',    $_REQUEST['IdEmpleado']);
            $alm->__SET('IdProveedor',   $_REQUEST['IdProveedor']);
			$alm->__SET('NombreEmpl',    $_REQUEST['NombreEmpl']);
			$alm->__SET('NombreProv',    $_REQUEST['NombreProv']);
			$alm->__SET('NombreProducto',$_REQUEST['NombreProducto']);
			$model->Registrar($alm);
			header('Location: Compra.index.php');
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
        <h1 align="center">Compras</h1>
        <div class="pure-g">
            <div class="pure-u-1-12">
                <!--?action=registrar-->
                <form action="?action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="id" value="<?php echo $alm->__GET('id'); ?>" />

                    <table style="width:500px;">
                        <tr>
                            <th style="text-align:left;">Hora</th>
                            <td><input type="time" name="Hora" value="<?php echo $alm->__GET('Hora'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha</th>
                            <td><input type="date" name="Fecha" value="<?php echo $alm->__GET('Fecha'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Total</th>
                            <td><input type="number" name="Total" value="<?php echo $alm->__GET('Total'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Folio Proveedor</th>
                            <td><input type="text" name="FolioProv" value="<?php echo $alm->__GET('FolioProv'); ?>" style="width:100%;" /></td>
                        </tr>

												<tr>
                            <th style="text-align:left;">Nombre Empleado</th>
                            <td><input type="text" name="NombreEmpl" value="<?php echo $alm->__GET('NombreEmpl'); ?>" style="width:100%;" /></td>
                        </tr>

												<tr>
                            <th style="text-align:left;">Nombre Proveedor</th>
                            <td><input type="text" name="NombreProv" value="<?php echo $alm->__GET('NombreProv'); ?>" style="width:100%;" /></td>
                        </tr>

                        <tr>
                            <th style="text-align:left;">Id Empleado</th>
                            <td><input type="number" name="IdEmpleado" value="<?php echo $alm->__GET('IdEmpleado'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Id Proveedor</th>
                            <td><input type="number" name="IdProveedor" value="<?php echo $alm->__GET('IdProveedor'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Nombre Producto</th>
                            <td><input type="text" name="NombreProducto" value="<?php echo $alm->__GET('NombreProducto'); ?>" style="width:100%;" /></td>
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
                            <th style="text-align:left;">Hora</th>
                            <th style="text-align:left;">Fecha</th>
                            <th style="text-align:left;">Total</th>
                            <th style="text-align:left;">Folio Proveedor</th>
                            <th style="text-align:left;">Id Empleado</th>
                            <th style="text-align:left;">Id Proveedor</th>
                            <th style="text-align:left;">Nombre Emplado</th>
                            <th style="text-align:left;">Nombre Proveedor</th>
                            <th style="text-align:left;">Nombre Producto</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Hora'); ?></td>
                            <td><?php echo $r->__GET('Fecha'); ?></td>
                            <td><?php echo $r->__GET('Total'); ?></td>
                            <td><?php echo $r->__GET('FolioProv'); ?></td>
                            <td><?php echo $r->__GET('IdEmpleado'); ?></td>
                            <td><?php echo $r->__GET('IdProveedor'); ?></td>
                            <td><?php echo $r->__GET('NombreEmpl'); ?></td>
                            <td><?php echo $r->__GET('NombreProv'); ?></td>
                            <td><?php echo $r->__GET('NombreProducto'); ?></td>
                            <td>
                                <a href="?action=editar&id=<?php echo $r->id; ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>

    </body>
</html>
