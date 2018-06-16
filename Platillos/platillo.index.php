<?php
require_once 'platillo.dto.php';
require_once 'platillo.dao.php';

// Logica
$alm = new Platillo();
$model = new PlatilloDAO();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'actualizar':
			$alm->__SET('id',              $_REQUEST['id']);
			$alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Precio',          $_REQUEST['Precio']);
            $alm->__SET('Precio_Platillo', $_REQUEST['Precio_Platillo']);
			$alm->__SET('Ingredientes',    $_REQUEST['Ingredientes']);
			$alm->__SET('Categoria',       $_REQUEST['Categoria']);
			$alm->__SET('Status',   	   $_REQUEST['Status']);
			$alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$alm->__SET('FechaDeDegreso',  $_REQUEST['FechaDeDegreso']);
			$model->Actualizar($alm);
			header('Location: platillo.index.php');
			break;

		case 'registrar':
            $alm->__SET('Nombre',          $_REQUEST['Nombre']);
            $alm->__SET('Precio',          $_REQUEST['Precio']);
            $alm->__SET('Precio_Platillo', $_REQUEST['Precio_Platillo']);
            $alm->__SET('Ingredientes',    $_REQUEST['Ingredientes']);
            $alm->__SET('Categoria',       $_REQUEST['Categoria']);
			$alm->__SET('Status',          'Activo');
		    $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$model->Registrar($alm);
			header('Location: platillo.index.php');
			break;

		case 'eliminar':
			$model->Eliminar($_REQUEST['id']);
			header('Location: platillo.index.php');
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
        <h1 align="center">Platillos</h1>
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
                            <th style="text-align:left;">Precio Compra</th>
                            <td><input type="number" name="Precio" value="<?php echo $alm->__GET('Precio'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Precio Venta</th>
                            <td><input type="number" name="Precio_Platillo" value="<?php echo $alm->__GET('Precio_Platillo'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Ingredientes</th>
                            <td><input type="text" name="Ingredientes" value="<?php echo $alm->__GET('Ingredientes'); ?>" style="width:100%;" /></td>
                        </tr>
						<tr>
                            <th style="text-align:left;">Categoria</th>
                            <td><input type="text" name="Categoria" value="<?php echo $alm->__GET('Categoria'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="Status" value="<?php echo $alm->__GET('Status'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Ingreso</th>
                            <td><input type="date" name="FechaDeIngreso" value="<?php echo $alm->__GET('FechaDeIngreso'); ?>" style="width:100%;" /></td>
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
                            <th style="text-align:left;">Nombre</th>
                            <th style="text-align:left;">Precio Compra</th>
                            <th style="text-align:left;">Precio Venta</th>
							<th style="text-align:left;">Ingredientes</th>
                            <th style="text-align:left;">Categoria</th>
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
                            <td><?php echo $r->__GET('Precio'); ?></td>
                            <td><?php echo $r->__GET('Precio_Platillo'); ?></td>
							<td><?php echo $r->__GET('Ingredientes'); ?></td>
                            <td><?php echo $r->__GET('Categoria'); ?></td>
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
