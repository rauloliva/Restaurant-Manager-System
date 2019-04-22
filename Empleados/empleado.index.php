<?php
require_once 'empleado.dto.php';
require_once 'empleado.dao.php';
require_once dirname(__DIR__).'/pdo.php';

$pdo = new PDO_connection();
$alm = new Empleado();
$model = new EmpleadoDAO($pdo);

$id_row = 0; //enum the rows in the table

session_start();
$tipo = $_SESSION[$_GET['id'].'tipo'] or header("Location: /Restaurante_website/404/index.html");
$foto = $_SESSION[$_GET['id'].'foto_empleado'];

$info = array("msg" => isset($_GET['msg']) ? $_GET['msg'] : '' ,"color" => isset($_GET['color']) ? $_GET['color'] : '');

if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
        case 'actualizar':
            $foto = '';
            if($_FILES['Foto']['tmp_name'] != null){
                if(getimagesize($_FILES['Foto']['tmp_name']) == TRUE){
                    $image = addslashes($_FILES['Foto']['tmp_name']);
                    $image = file_get_contents($image);
                    $image = base64_encode($image);
                    $alm->__SET('Foto', $image);
                    $foto = ',foto_empleado=?';
                }
            }
            if($_REQUEST['needHash'] == 'true'){
                $pwd = password_hash($_REQUEST['Contraseña_visible'],PASSWORD_BCRYPT);
            }else{
                $pwd = $_REQUEST['Contraseña_user'];
            }
            $alm->__SET('id',              $_REQUEST['id_empleado']);
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
            $alm->__SET('Contraseña_user', $pwd);
            $alm->__SET('Status',          $_REQUEST['Status']);
            $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
            $alm->__SET('FechaDeEgreso',   $_REQUEST['FechaDeEgreso']);
            $info = $model->Actualizar($alm,$foto);
            header('Location: empleado.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
			break;

        case 'registrar':
            if($_FILES['Foto']['tmp_name'] != null){
                if(getimagesize($_FILES['Foto']['tmp_name']) == TRUE){
                    $image = addslashes($_FILES['Foto']['tmp_name']);
                    $image = file_get_contents($image);
                    $image = base64_encode($image);
                }
            }else{
                $image = "";
            }
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
            $alm->__SET('Contraseña_user', password_hash($_REQUEST['Contraseña_visible'],PASSWORD_BCRYPT));
            $alm->__SET('Status',          'Activo');
            $alm->__SET('FechaDeIngreso',  date("Y-m-d"));
            $alm->__SET('Foto',            $image);
			$info = $model->Registrar($alm);
			header('Location: empleado.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
			break;

        case 'eliminar':
            $info = $model->Eliminar($_REQUEST['id_empleado']);
            header('Location: empleado.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
            break;
            
		case 'editar':
            $alm = $model->Obtener($_REQUEST['id_empleado']);
            break;
	}
}
$arrayListar = $model->Listar("");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/Restaurante_website/Imagenes/logo_frame.png" />
    <link href="/Restaurante_website/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Restaurante_website/bootstrap/css/style.css" rel="stylesheet">
    <script src="/Restaurante_website/bootstrap/js/jquery.min.js"></script>
    <title>Empleados</title>
    <style>
        @media only screen and (max-width:700px){
            body{
                background: brown;
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif
            }
            .header{
                margin-top: 0;
                margin-left: 0;
                width: 100%;
                height: auto;
                border: 5px solid orange;
                background-repeat: repeat-y
            }
            .container_form{
                width: 100%
            }
        }
        th{color: black;}
    </style>
</head>

<body class="body-empleados">
    <div class="header">
        <h1>Empleados</h1>
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li class="nav-item nav-headers" style="border-radius: 5px 0 0 5px">
                <a class="nav-link" href="/Restaurante_website/principal.php?id=<?php echo $_GET['id'];?>">Dashboard</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Clientes/cliente.index.php?id=<?php echo $_GET['id'];?>">Clientes</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Ventas/venta.index.php?id=<?php echo $_GET['id'];?>">Ventas</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Platillos/platillo.index.php?id=<?php echo $_GET['id'];?>">Platillos</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Mesas/mesa.index.php?id=<?php echo $_GET['id'];?>">Mesas</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" id="pdf" href="empleadosPDF.php">
                    <img src="/Restaurante_website/Imagenes/pdf_icon.png" width="39px" height="33px">
                </a>
            </li>
            <li class="nav-item nav-headers" style="border-radius: 0 5px 5px 0">
                <a class="nav-link" href="#" style="cursor:default">
                    <img class="nav-foto" src="<?php echo $foto?>" width="39px" height="33px" class=".img_empleado">
                </a>
            </li>
        </ul><br>
    </div><br>
    <div id="container_msg" class="container-msg-default">
        <?php echo $info['msg'];?>
    </div><br>
    <div class="container_form form-empleados">
        <form enctype="multipart/form-data" action="?id=<?php echo $_GET['id'];?>&action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>"
            method="post" style="margin-bottom:30px;">
            <input type="hidden" id="id" name="id_empleado" value="<?php echo $alm->__GET('id');?>" />
            <input type="hidden" id="FechaDeEgreso" name="FechaDeEgreso" value="<?php echo $alm->__GET('FechaDeEgreso'); ?>" />
            <input id="fechaActual" type="hidden" name="FechaDeIngreso" value="<?php
                    $fechaIng = $alm->__GET('FechaDeIngreso');
                    echo $fechaIng != "" && $fechaIng != null ? $fechaIng : date("
                Y-m-d"); ?>" style="width:100%;"/>
            <table align="center">
                <tr>
                    <th style="text-align:left;">Nombre</th>
                    <td><input required placeholder="Nombre" id="nombre" type="text" name="Nombre" value="<?php echo $alm->__GET('Nombre'); ?>"
                            style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Apellido Paterno</th>
                    <td><input required placeholder="Apellido paterno" id="ap" type="text" name="Apellido_paterno"
                            value="<?php echo $alm->__GET('Apellido_paterno'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Apellido Materno</th>
                    <td><input required id="am" placeholder="Apellido materno" type="text" name="Apellido_materno"
                            value="<?php echo $alm->__GET('Apellido_materno'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Fecha</th>
                    <td><input required id="fecha" type="date" name="FechaNacimiento" value="<?php echo $alm->__GET('FechaNacimiento'); ?>"
                            style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Sexo</th>
                    <td>
                        <select id="sexo" name="Sexo" style="width:100%;">
                            <option value="Masculino" <?php echo $alm->__GET('Sexo')=='Masculino'?'selected':'';
                                ?>>Masculino</option>
                            <option value="Femenino" <?php echo $alm->__GET('Sexo')=='Femenino'?'selected':'';
                                ?>>Femenino</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;">Direccion</th>
                    <td><textarea required placeholder="Direccion" id="direccion" name="Direccion"><?php echo $alm->__GET('Direccion'); ?></textarea></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Correo</th>
                    <td><input required placeholder="Correo" onblur="validateEmail()" id="correo" type="text" name="Correo"
                            value="<?php echo $alm->__GET('Correo'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Telefono</th>
                    <td><input required placeholder="Telefono" onblur="validateTel()" id="telefono" type="number" name="Telefono"
                            value="<?php echo $alm->__GET('Telefono'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Tipo</th>
                    <td>
                        <select id="tipo" name="Tipo" style="width:100%;">
                            <option id="dueño" value="Dueño" <?php echo $alm->__GET('Tipo')=='Dueño'?'selected':'';
                                ?>>Dueño</option>
                            <option value="Gerente" <?php echo $alm->__GET('Tipo')=='Gerente'?'selected':''; ?>>Gerente</option>
                            <option value="Administrador Ventas" <?php echo $alm->__GET('Tipo')=='Administrador
                                Ventas'?'selected':''; ?>>Administrador Ventas</option>
                            <option value="Administrador Productos" <?php echo $alm->__GET('Tipo')=='Administrador
                                Productos'?'selected':''; ?>>Administrador Productos</option>
                            <option value="Mesero" <?php echo $alm->__GET('Tipo')=='Mesero'?'selected':''; ?>>Mesero</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left;">Sueldo</th>
                    <td><input required placeholder="Sueldo" id="sueldo" type="number" name="Sueldo" value="<?php echo $alm->__GET('Sueldo'); ?>"
                            style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Usuario</th>
                    <td><input required placeholder="Usuario" id="usuario" type="text" name="Nombre_user" value="<?php echo $alm->__GET('Nombre_user'); ?>"
                            style="width:100%;" /></td>
                </tr>
                <tr>
                    <!--store the password here when editing-->
                    <input type="hidden" value="<?php echo $alm->__GET('Contraseña_user')?>" id="contraseña" name="Contraseña_user" />
                    <!--tells if the pwd needs a hash in php-->
                    <input type="hidden" value="" name="needHash" id="needHash" />
                    <th style="text-align:left;">Contraseña</th>
                    <td><input required placeholder="Contraseña" id="contraseña_visible" type="password" name="Contraseña_visible"
                            value="<?php echo $alm->__GET('id') == '' ? '' : '123456'?>" style="width:100%;" /></td>
                </tr>
                <tr id="status_option">
                    <th style="text-align:left;">Estatus</th>
                    <td>
                        <select id="status" name="Status" style="width:100%;">
                            <option value="Activo" <?php echo $alm->__GET('Status')=='Activo'?'selected':''; ?>>Activo</option>
                            <option value="Desactivado" <?php echo $alm->__GET('Status')=='Desactivado'?'selected':'';?>>Desactivado</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <?php
                        if($alm->__GET('id') != null){
                            $stm = $pdo->prepare("SELECT foto_empleado FROM `empleados` WHERE id_empleado = ?");
                            $stm->execute([$alm->__GET('id')]);
                            if($row = $stm->fetch(PDO::FETCH_OBJ)){
                                if($row->foto_empleado != ""){
                                    echo "<td><img id='img' src='data:image;base64,".$row->foto_empleado."' class='foto-modules' style='cursor:default;'></td>";
                                }else{
                                    echo "<td><img id='img' src='/Restaurante_website/Imagenes/userNotFound.png' class='foto-modules' style='cursor:default;'></td>";         
                                }
                            }
                        }else{
                            echo "<td><img id='img' src='/Restaurante_website/Imagenes/userNotFound.png' class='foto-modules' style='cursor:default;'></td>";
                        }
                    ?>
                    <th id="photo" class="input-file-container">
                        <input class="input-file" id="my-file" type="file" name="Foto">
                        <label tabindex="0" for="my-file" class="input-file-trigger">Selecciona una Imagen...</label>
                        <p class="file-return"></p>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="submit" type="submit" name="submit">Guardar</button>
                        <button id="clear" type="button">Limpiar</button>
                    </td>
                </tr>
            </table>
        </form>
    </div><br>
    <div class="container_search">
        <h3>Buscar Empleado</h3>
        <h5 style="float: left;">Filtros</h5>
        <table align="center" style="width:555px">
            <tr>
                <th>Nombre del Empleado:</th>
                <td><input type="text" style="width: 100%;" placeholder="Nombre" name="search_nombre" id="search_nombre"></td>
            </tr>
            <tr>
                <th>Correo del Empleado:</th>
                <td><input type="text" style="width: 100%;" placeholder="Correo" name="search_correo" id="search_correo"></td>
            </tr>
            <tr>
                <th>Telefono del Empleado:</th>
                <td><input type="number" style="width: 100%;" placeholder="Telefono" name="search_telefono" id="search_telefono"></td>
        </table>
    </div><br>
    <div class="container_table">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;" class="th_lista th-border-left">Nombre</th>
                    <th style="text-align:center;" class="th_lista">Apellido Paterno</th>
                    <th style="text-align:center;" class="th_lista">Apellido Materno</th>
                    <th style="text-align:center;" class="th_lista">Nacimiento</th>
                    <th style="text-align:center;" class="th_lista">Sexo</th>
                    <th style="text-align:center;" class="th_lista">Correo</th>
                    <th style="text-align:center;" class="th_lista">Telefono</th>
                    <th style="text-align:center;" class="th_lista">Tipo</th>
                    <th style="text-align:center;" class="th_lista">Sueldo</th>
                    <th style="text-align:center;" class="th_lista">Estatus</th>
                    <th style="text-align:center;" class="th_lista">Editar</th>
                    <th style="text-align:center;" class="th_lista th-border-right">Eliminar</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php 
                    if($arrayListar == null){
                        echo "<tr>";
                        for($i = 0; $i < 12; $i++){
                            echo "<td>No hay Informacion</td>";
                        }
                        echo "</tr>";
                    }
                ?>
            <?php foreach($arrayListar as $r): ?>
            <tr class="row_selected" id="<?php echo $id_row++;?>">
                <td class="td_lista">
                    <?php echo $r->__GET('Nombre'); ?>
                </td>
                <td class="td_lista">
                    <?php echo $r->__GET('Apellido_paterno'); ?>
                </td>
                <td class="td_lista">
                    <?php echo $r->__GET('Apellido_materno'); ?>
                </td>
                <td class="td_lista">
                    <?php echo $r->__GET('FechaNacimiento'); ?>
                </td>
                <td class="td_lista">
                    <?php echo $r->__GET('Sexo');?>
                </td>
                <td class="td_lista">
                    <?php echo $r->__GET('Correo'); ?>
                </td>
                <td class="td_lista">
                    <?php echo $r->__GET('Telefono'); ?>
                </td>
                <td class="td_lista">
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
                        ?>
                </td>
                <td class="td_lista"><?php echo $r->__GET('Sueldo'); ?></td>
                <td class="td_lista" id="emp_status_<?php echo $r->id;?>"><?php echo $r->__GET('Status'); ?></td>
                <td class="td_lista">
                    <form action="?id=<?php echo $_GET['id'];?>&action=editar&id_empleado=<?php echo $r->id;?>" method="post">
                        <button>Editar</button>
                    </form>
                </td>
                <td class="td_lista">
                    <button id="<?php echo $r->id;?>" name="delete" type="button" class="btn-remove-hover">Eliminar</button>
                </td>
                <td><input type="hidden" value="<?php echo $r->__GET('id'); ?>" /></td>
                <td><input type="hidden" value="<?php echo $r->__GET('Contraseña_user'); ?>" /></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <!-- Modal: employee has been removed before -->
    <div class="modal fade" id="modal_emp_activo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Empleado Desactivado</h4>
                </div>
                <div class="modal-body">
                    <h5>El empleado ya ha sido dado de baja con anterioridad</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: change the status, so the employee could be updated -->
    <div class="modal fade" id="modal_emp_desactivado">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Empleado todavia Desactivado</h4>
                </div>
                <div class="modal-body">
                    <h5>El empleado sigue estando de baja<br>Se necesita cambiar el estatus</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: confirm to remove employee -->
    <div class="modal fade" id="modal_remove_emp">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Empleado</h4>
                </div>
                <div class="modal-body">
                    <h5>El empleado Sera eliminado ¿Continuar?</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cancelar</button>
                    <button class="remove btn-remove-hover" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal in case the user wants to delete their profile -->
    <div class="modal fade" id="modal_close">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Accion Denegada</h4>
                </div>
                <div class="modal-body">
                    <h5>Esta accion no puede ser ejecutada</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal the field is not valid -->
    <div class="modal fade" id="modal_campo_invalido">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title_modal"></h4>
                </div>
                <div class="modal-body">
                    <h5 id="content_modal"></h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for a duplicate username -->
    <div class="modal fade" id="modal_usuario_duplicado">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Usuario No disponible</h4>
                </div>
                <div class="modal-body">
                    <h5>El nombre de usuario ya lo esta usando<br>alguien mas</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function () {
            //display the message from the backend in a model otherwise its hidden
            if ("<?php echo $info['msg']?>" == "") {
                $("#container_msg").hide()
            } else if ("<?php echo $info['color']?>" == "blue") {
                $("#container_msg").attr("class", "container-msg-success")
                setTimeout(() => {
                    $("#container_msg").slideToggle(900)
                }, 2000);
            } else {
                $("#container_msg").attr("class", "container-msg-failure")
                setTimeout(() => {
                    $("#container_msg").slideToggle(900)
                }, 3000);
            }
            history.replaceState({}, document.title,
                "/Restaurante_website/Empleados/empleado.index.php?id=<?php echo $_GET['id'];?>")
        }

        $("#usuario").blur(validate_username)

        function validate_username() {
            $.ajax({
                type: "GET",
                url: "empleado.dao.php?val_username=" + $("#usuario").val(),
                success: function (data) {
                    var json = eval(data)
                    if (json[0].msg != "") {
                        $("#usuario").val("")
                        $("#modal_usuario_duplicado").modal('show')
                    }
                }
            })
        }

        //function for the remove button  
        function Remove_Employee(id) {
            if (id !== "<?php echo $_GET['id'];?>") {
                var status = $("#emp_status_" + id).text().trim()
                if (status == "Desactivado") {
                    $("#modal_emp_activo").modal('show')
                } else {
                    $(".remove").attr("id", id)
                    $("#modal_remove_emp").modal('show')
                }
            } else {
                $("#modal_close").modal('show')
            }
        }

        //every button with this class
        $("button.btn-remove-hover").click(function () {
            Remove_Employee($(this).attr("id"))
        })

        //the remove button in the model
        $(".remove").click(function () {
            var id = $(this).attr("id")
            location = "empleado.index.php?id=<?php echo $_GET['id'];?>&action=eliminar&id_empleado=" + id
            id = 0
        })

        function validateTel() {
            const telefono = document.getElementById('telefono').value
            if (telefono != '') {
                if (telefono.length < 8 || telefono.length > 10) {
                    $('#title_modal').html('Telefono Invalido')
                    $('#content_modal').html('El numero ingresado no es un telefono valido')
                    $("#modal_campo_invalido").modal('show')
                    document.getElementById('telefono').value = ''
                }
            }
        }

        function validateEmail() {
            const email = document.getElementById('correo').value
            var expression =
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!expression.test(email) && email != '') {
                $('#title_modal').html('Correo Invalido')
                $('#content_modal').html('El correo ingresado no es un correo valido')
                $("#modal_campo_invalido").modal('show')
                document.getElementById('correo').value = ''
            }
        }

        //if the employee is active disable the status's field
        var status = "<?php echo $alm->__GET('Status');?>"
        if (status == '' || status == 'Activo') {
            $("#status_option").attr("hidden", "true")
        }

        //cross site scripting
        function removeXSS(){
            var nombre = $("#usuario").val()
            nombre = nombre.replace(/</g,"").replace(/>/g,"").replace('/','')
            $("#usuario").val(nombre)
        }

        //while inactive, make sure the the employee is now active before saving the modifications
        $("#submit").click(event, function () {
            removeXSS()
            var status = $("#status").val()
            if (status == 'Desactivado') {
                $("#modal_emp_desactivado").modal('show')
                event.preventDefault()
                return
            }
            //set when to apply password_hash method
            const isUpdate = '<?php echo $alm->__GET('id')?>'
            if (isUpdate !== '') {
                const checkId = $("#contraseña_visible").val()
                if (checkId === '123456') {
                    $("#needHash").val('false')
                } else {
                    $("#needHash").val('true')
                }
            }
        })

        $("a").click(function () {
            var style = $(this).attr("class");
            if (style == "disabled") {
                alert("No tienes acceso a esta ventana")
            }
        })

        $("#clear").click(() => {
            $("input").val("")
            $("textarea").text("")
            $("#dueño").attr("selected", "true")
            $("#img").attr("src", "/Restaurante_website/Imagenes/userNotFound.png")
            history.replaceState({}, document.title,
                "/Restaurante_website/Empleados/empleado.index.php?id=<?php echo $_GET['id'];?>")
        })

        //list records
        $('#search_nombre').keyup(function () {
            var value = $(this).val()
            listar_ajax(value, "nombre_empleado")
        })

        $('#search_correo').keyup(function () {
            var value = $(this).val()
            listar_ajax(value, "correo_empleado")
        })

        $('#search_telefono').keyup(function () {
            var value = $(this).val()
            listar_ajax(value, "telefono_empleado")
        })

        let f_lap = true,
            id_rows = 0

        function listar_ajax(value, column) {
            $("a#pdf").attr("href","empleadosPDF.php?value="+value+"&column="+column)
            /*
                first keyup is controled by php variable
                form second keyup and forward is controled by javascript variable
            */
            id_rows = f_lap ? "<?php echo $id_row;?>" : id_rows
            f_lap = false
            $.ajax({
                type: "GET",
                url: "empleado.dao.php",
                data: "list=" + value + "&column=" + column,
                success: function (data) {
                    var json = eval(data)
                    //remove all the records in the table
                    for (index = 0; index <= id_rows; index++) {
                        $("#" + index).remove()
                    }
                    var id_row = 0
                    if (json && json[0].msg == "") {
                        for (let i = 0; i < json.length; i++) {
                            $("thead").append(
                                `<tr class='row_selected' id="${(++id_rows)}">
                                <td class='td_lista'>${json[i].nombre}</td>
                                <td class='td_lista'>${json[i].ap}</td>
                                <td class='td_lista'>${json[i].am}</td>
                                <td class='td_lista'>${json[i].fecha_nac}</td>
                                <td class='td_lista'>${json[i].sexo}</td>
                                <td class='td_lista'>${json[i].correo}</td>
                                <td class='td_lista'>${json[i].telefono}</td>
                                <td class='td_lista'>${json[i].tipo}</td>
                                <td class='td_lista'>${json[i].sueldo}</td>
                                <td class='td_lista' id='emp_status_${json[i].id}'>${json[i].status}</td>
                                <td class='td_lista'>
                                    <form action='?id=${<?php echo $_GET['id'];?>}&action=editar&id_empleado=${json[i].id}' method='post'>
                                        <button>Editar</button>
                                    </form>
                                </td>
                                <td class='td_lista'>
                                    <button id="${json[i].id}" onclick='Remove_Employee(${json[i].id})' name='delete' type='button' class='btn-remove-hover'>Eliminar</button>
                                </td>
                            </tr>`
                            )
                        }
                    } else {
                        var str = ""
                        for (let i = 0; i <= 11; i++) {
                            str += "<td class='td_lista'>" + json[0].msg + "</td>"
                        }
                        $("thead").append(`<tr class='row_selected' id="${(++id_rows)}">${str}</tr>`)
                    }
                }
            })
        }

        //show the selected image in the img tag
        $("#my-file").change(function () {
            $('#img')[0].src = (window.URL ? URL : webkitURL).createObjectURL(this.files[0]);
        })

        //Input File
        document.querySelector("html").classList.add('js');
        var fileInput = document.querySelector(".input-file"),
            button = document.querySelector(".input-file-trigger"),
            the_return = document.querySelector(".file-return");
        button.addEventListener("keydown", function (event) {
            if (event.keyCode == 13 || event.keyCode == 32) {
                fileInput.focus();
            }
        })
        button.addEventListener("click", function (event) {
            fileInput.focus();
            return false;
        })
        fileInput.addEventListener("change", function (event) {
            the_return.innerHTML = this.value.replace("C:\\fakepath\\", "")
        })
    </script>

    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>