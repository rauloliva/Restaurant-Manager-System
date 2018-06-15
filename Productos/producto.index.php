<?php
require_once 'producto.dto.php';
require_once 'producto.dao.php';

// Logica
$alm = new Producto();
$model = new ProductoDAO();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'actualizar':
			$alm->__SET('id',              $_REQUEST['id']);
			$alm->__SET('Existencia',      $_REQUEST['Existencia']);
			$alm->__SET('Categoria',       $_REQUEST['Categoria']);
            $alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Precio',          $_REQUEST['Precio']);
			$alm->__SET('Medida',          $_REQUEST['Medida']);
			$alm->__SET('Gestor_Min',      $_REQUEST['Gestor_Min']);
			$alm->__SET('Gestor_Max',      $_REQUEST['Gestor_Max']);
			$alm->__SET('Status',          $_REQUEST['Status']);
			$alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$alm->__SET('FechaDeEgreso',   $_REQUEST['FechaDeEgreso']);
			$model->Actualizar($alm);
			header('Location: producto.index.php');
			break;

		case 'registrar':
            $alm->__SET('Existencia',      $_REQUEST['Existencia']);
			$alm->__SET('Categoria',       $_REQUEST['Categoria']);
            $alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Precio',          $_REQUEST['Precio']);
			$alm->__SET('Medida',          $_REQUEST['Medida']);
			$alm->__SET('Gestor_Min',      $_REQUEST['Gestor_Min']);
			$alm->__SET('Gestor_Max',      $_REQUEST['Gestor_Max']);
			$alm->__SET('Status',          'Activo');
			$alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$model->Registrar($alm);
			header('Location: producto.index.php');
			break;

		case 'eliminar':
			$model->Eliminar($_REQUEST['id']);
			header('Location: producto.index.php');
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
        <h1 align="center">Productos</h1>
        <div class="pure-g">
            <div class="pure-u-1-12">
                
                <form action="?action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="id" value="<?php echo $alm->__GET('id'); ?>" />
                    
                    <table style="width:500px;">
                        <tr>
                            <th style="text-align:left;">Existencia</th>
                            <td><input type="text" name="Existencia" value="<?php echo $alm->__GET('Existencia'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Categoria</th>
                            <td><input type="text" name="Categoria" value="<?php echo $alm->__GET('Categoria'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <td><input type="text" name="Nombre" value="<?php echo $alm->__GET('Nombre'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Precio</th>
                            <td><input type="text" name="Precio" value="<?php echo $alm->__GET('Precio'); ?>" style="width:100%;" /></td>
                        </tr>
						<tr>
                            <th style="text-align:left;">Medida</th>
                            <td><input type="text" name="Medida" value="<?php echo $alm->__GET('Medida'); ?>" style="width:100%;" /></td>
                        </tr>
						<tr>
                            <th style="text-align:left;">Gestor Minimo</th>
                            <td><input type="text" name="Gestor_Min" value="<?php echo $alm->__GET('Gestor_Min'); ?>" style="width:100%;" /></td>
                        </tr>
						<tr>
                            <th style="text-align:left;">Gestor Maximo</th>
                            <td><input type="text" name="Gestor_Max" value="<?php echo $alm->__GET('Gestor_Max'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="Status" value="<?php echo $alm->__GET('Status'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Ingreso</th>
                            <td><input type="text" name="FechaDeIngreso" value="<?php echo $alm->__GET('FechaDeIngreso'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="FechaDeEgreso" value="<?php echo $alm->__GET('FechaDeEgreso'); ?>" style="width:100%;" /></td>
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
                            <th style="text-align:left;">Existencia</th>
                            <th style="text-align:left;">Categoria</th>
                            <th style="text-align:left;">Nombre</th>
                            <th style="text-align:left;">Precio_Producto</th>
                            <th style="text-align:left;">Medida_Producto</th>
                            <th style="text-align:left;">Gestor_Min</th>
                            <th style="text-align:left;">Gestor_Max</th>
                            <th style="text-align:left;">Status</th>
							<th style="text-align:left;">FechaDeIngreso</th>
							<th style="text-align:left;">FechaDeEgreso</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Existencia'); ?></td>
                            <td><?php echo $r->__GET('Categoria'); ?></td>
                            <td><?php echo $r->__GET('Nombre'); ?></td>
                            <td><?php echo $r->__GET('Precio'); ?></td>
                            <td><?php echo $r->__GET('Medida'); ?></td>
                            <td><?php echo $r->__GET('Gestor_Min'); ?></td>
                            <td><?php echo $r->__GET('Gestor_Max'); ?></td>
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