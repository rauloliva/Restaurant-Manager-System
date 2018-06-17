<?php
require_once 'venta.dto.php';
require_once 'venta.dao.php';

// Logica
$alm = new Venta();
$model = new VentaDAO();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'registrar':
            $alm->__SET('Total',            $_REQUEST['Total']);
            $alm->__SET('Hora',             $_REQUEST['Hora']);
            $alm->__SET('Fecha',            $_REQUEST['Fecha']);
            $alm->__SET('IdCliente',        $_REQUEST['IdCliente']);
            $alm->__SET('IdEmpleado',       $_REQUEST['IdEmpleado']);
            $alm->__SET('Id_venta_platillo',$_REQUEST['Id_venta_platillo']);
			$model->Registrar($alm);
			header('Location: venta.index.php');
            break;
            
		case 'editar':
			$alm = $model->Obtener($_REQUEST['id']);
            break;
        case 'error':
            echo "No puedes editar una venta ya realizada";
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
        <script>
            function msg() {
                alert("hola");
            }
        </script>
        <h1 align="center">Ventas</h1>
        <div class="pure-g">
            <div class="pure-u-1-12">
                
                <form action="?action=<?php echo $alm->id > 0 ? 'error' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="id" value="<?php echo $alm->__GET('id'); ?>" />
                    
                    <table style="width:500px;">
                        <tr>
                            <th style="text-align:left;">Total</th>
                            <td><input type="number" name="Total" value="<?php echo $alm->__GET('Total'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Hora</th>
                            <td><input type="time" name="Hora" value="<?php echo $alm->__GET('Hora'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha</th>
                            <td><input type="date" name="Fecha" value="<?php echo $alm->__GET('Fecha'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Id Cliente</th>
                            <td><input type="number" name="IdCliente" value="<?php echo $alm->__GET('IdCliente'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Id Empleado</th>
                            <td><input type="number" name="IdEmpleado" value="<?php echo $alm->__GET('IdEmpleado'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Id Venta Platillo</th>
                            <td><input type="number" name="Id_venta_platillo" value="<?php echo $alm->__GET('Id_venta_platillo'); ?>" style="width:100%;" /></td>
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
                            <th style="text-align:left;">Total</th>
                            <th style="text-align:left;">Hora</th>
                            <th style="text-align:left;">Fecha</th>
                            <th style="text-align:left;">Id Cliente</th>
                            <th style="text-align:left;">Id Empleado</th>
                            <th style="text-align:left;">Id Venta Platillo</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Total'); ?></td>
                            <td><?php echo $r->__GET('Hora'); ?></td>
                            <td><?php echo $r->__GET('Fecha'); ?></td>
                            <td><?php echo $r->__GET('IdCliente'); ?></td>
                            <td><?php echo $r->__GET('IdEmpleado'); ?></td>
                            <td><?php echo $r->__GET('Id_venta_platillo'); ?></td>
                            <td>
                                <a href="?action=editar&id=<?php echo $r->id; ?>">Traer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>     
              
            </div>
        </div>

    </body>
</html>