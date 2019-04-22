<?php
require_once 'cliente.dto.php';
require_once 'cliente.dao.php';
require_once dirname(__DIR__).'/pdo.php';

$pdo = new PDO_connection();
$alm = new Cliente();
$model = new ClienteDAO($pdo);
$id_row = 0; //enum the rows in the table

session_start();
$tipo = $_SESSION[$_GET['id'].'tipo'] or header("Location: /Restaurante_website/404/index.html");
$foto = $_SESSION[$_GET['id'].'foto_empleado'];

$info = array("msg" => isset($_GET['msg']) ? $_GET['msg'] : '' ,"color" => isset($_GET['color']) ? $_GET['color'] : '');

if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
        case 'actualizar':
            $alm->__SET('id',              $_REQUEST['id_cliente']);
            $alm->__SET('Nombre',          $_REQUEST['Nombre']);
            $alm->__SET('Apellido_paterno',$_REQUEST['Apellido_paterno']);
            $alm->__SET('Apellido_materno',$_REQUEST['Apellido_materno']);
            $alm->__SET('Status',          $_REQUEST['Status']);
            $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
            $alm->__SET('FechaDeEgreso',   $_REQUEST['FechaDeEgreso']);
            $alm->__SET('Correo',          $_REQUEST['Correo']);
            $alm->__SET('RFC',             $_REQUEST['RFC']);
            $info = $model->Actualizar($alm);
            header('Location: cliente.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
			break;

        case 'registrar':
			$alm->__SET('Nombre',          $_REQUEST['Nombre']);
            $alm->__SET('Apellido_paterno',$_REQUEST['Apellido_paterno']);
            $alm->__SET('Apellido_materno',$_REQUEST['Apellido_materno']);
            $alm->__SET('Status',          'Activo');
            $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
            $alm->__SET('Correo',          $_REQUEST['Correo']);
            $alm->__SET('RFC',             $_REQUEST['RFC']);
            $info = $model->Registrar($alm);
			header('Location: cliente.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
			break;

        case 'eliminar':
            $info = $model->Eliminar($_REQUEST['id_cliente']);
            header('Location: cliente.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
            break;

		case 'editar':
            $alm = $model->Obtener($_REQUEST['id_cliente']);
            break;
	}
}
$arrayListar = $model->Listar("");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/Restaurante_website/Imagenes/logo_frame.png"/>
    <link href="/Restaurante_website/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Restaurante_website/bootstrap/css/style.css" rel="stylesheet">
    <script src="/Restaurante_website/bootstrap/js/jquery.min.js"></script>
    <title>Clientes</title>
    <style>th{color: black}</style>
</head>
<body class="body-clientes">
    <div class="header">
        <h1>Clientes</h1>
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li class="nav-item nav-headers" style="border-radius: 5px 0 0 5px">
                <a class="nav-link" href="/Restaurante_website/principal.php?id=<?php echo $_GET['id'];?>">Dashboard</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Empleados/empleado.index.php?id=<?php echo $_GET['id'];?>">Empleados</a>
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
                <a class="nav-link" id="pdf" href="clientesPDF.php">
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
    <div id="container_msg" class="container-msg-default"><?php echo $info['msg'];?></div><br>
    <div class="container_form form-clientes">
        <form enctype="multipart/form-data" action="?id=<?php echo $_GET['id'];?>&action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" style="margin-bottom:30px;">
            <input type="hidden" id="id" name="id_cliente" value="<?php echo $alm->__GET('id'); ?>" />
            <input type="hidden" id="FechaDeEgreso" name="FechaDeEgreso" value="<?php echo $alm->__GET('FechaDeEgreso'); ?>" />
            <input id="fechaActual" type="hidden" name="FechaDeIngreso" 
                value="<?php
                    $var = $alm->__GET('FechaDeIngreso');
                    echo $var != "" && $var != null ? $var : date("Y-m-d");
                ?>" style="width:100%;"/>
            <table class="form" align="center" style="width:655px;">
                <tr>
                    <th style="text-align:left;">Nombre</th>
                    <td><input required placeholder="Nombre" id="nombre" type="text" name="Nombre" value="<?php echo $alm->__GET('Nombre'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Apellido Paterno</th>
                    <td><input required placeholder="Apellido paterno" id="ap" type="text" name="Apellido_paterno" value="<?php echo $alm->__GET('Apellido_paterno'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Apellido Materno</th>
                    <td><input required id="am" placeholder="Apellido materno" type="text" name="Apellido_materno" value="<?php echo $alm->__GET('Apellido_materno'); ?>" style="width:100%;" /></td>
                </tr> 
                <tr>
                    <th style="text-align:left;">Correo</th>
                    <td><input required placeholder="Correo" onblur="validateEmail()" id="correo" type="text" name="Correo" value="<?php echo $alm->__GET('Correo'); ?>" style="width:100%;"/></td>
                </tr>
                <tr>
                    <th style="text-align:left;">RFC</th>
                    <td><input oninput="formattingRFC()" onblur=validateRFC() required placeholder="RFC" id="RFC" type="text" name="RFC" value="<?php echo $alm->__GET('RFC'); ?>" style="width:100%;" /></td>
                </tr>
                <tr id="status_option">
                    <th style="text-align:left;">Estatus</th>
                    <td>
                        <select id="status" class="selected" name="Status" style="width:100%;">
                            <option value="Activo" <?php echo $alm->__GET('Status')=='Activo'?'selected':''; ?>>Activo</option>
                            <option value="Desactivado" <?php echo $alm->__GET('Status')=='Desactivado'?'selected':''; ?>>Desactivado</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button id="submit" type="submit" name="submit">Guardar</button>
                        <button id="clear" type="button">Limpiar</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div class="container_search">
        <h3>Buscar Cliente</h3>
            <h5>Filtros</h5>
            <table class="form" align="center" style="width:555px;">
                <tr>
                    <th>Nombre del Cliente:</th>
                    <td><input type="text" style="width: 100%;" placeholder="Nombre" name="search_nombre" id="search_nombre"></td>
                </tr>
                <tr>
                    <th>Correo del Cliente:</th>
                    <td><input type="text" style="width: 100%;" placeholder="Correo" name="search_correo" id="search_correo"></td>
                </tr>
                <tr>
                    <th>RFC del Cliente:</th>
                    <td><input type="text" style="width: 100%;" placeholder="RFC" name="search_RFC" id="search_RFC"></td>
                </tr>
            </table> 
    </div><br>
    <div class="container_table_small">
        <table>
            <thead>
                <tr class="row_selected">
                    <th style="text-align:center;" class="th_lista th-border-left">Nombre</th>
                    <th style="text-align:center;" class="th_lista">Apellido Paterno</th>
                    <th style="text-align:center;" class="th_lista">Apellido Materno</th>
                    <th style="text-align:center;" class="th_lista">Correo</th>
                    <th style="text-align:center;" class="th_lista">Status</th>
                    <th style="text-align:center;" class="th_lista">RFC</th>
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
                        <td class="td_lista"><?php echo $r->__GET('Nombre'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Apellido_paterno'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Apellido_materno'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Correo'); ?></td>
                        <td class="td_lista" id="cli_status_<?php echo $r->id;?>"><?php echo $r->__GET('Status'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('RFC'); ?></td>
                        <td class="td_lista">
                            <form action="?id=<?php echo $_GET['id'];?>&action=editar&id_cliente=<?php echo $r->id;?>" method="post">
                                <button>Editar</button>
                            </form>
                        </td>
                        <td class="td_lista">
                            <button id="<?php echo $r->id;?>" name="delete" type="button" class="btn-remove-hover">Eliminar</button>
                        </td>
                        <td><input type="hidden" value="<?php echo $r->__GET('id')?>"/></td>
                    </tr>
                <?php endforeach;?>
        </table>    
    </div>
    <!-- Modal: employee has been removed before -->
    <div class="modal fade" id="modal_cli_activo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cliente Desactivado</h4>
                </div>
                <div class="modal-body">
                    <h5>El Cliente ya ha sido dado de baja con anterioridad</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>  
    <!-- Modal: change the status so the employee could be updated -->
    <div class="modal fade" id="modal_cli_desactivado">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cliente todavia Desactivado</h4>
                </div>
                <div class="modal-body">
                    <h5>El Cliente sigue estando de baja<br>Se necesita cambiar el estatus</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div> 
    <!-- Modal: confirm to remove client -->
    <div class="modal fade" id="modal_remove_cli">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Cliente</h4>
                </div>
                <div class="modal-body">
                    <h5>El Cliente Sera eliminado ¿Continuar?</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cancelar</button>
                    <button class="remove btn-remove-hover" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal the email is not valid -->
    <div class="modal fade" id="modal_email_invalido">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Correo Invalido</h4>
                </div>
                <div class="modal-body">
                    <h5>El correo ingresado no es un correo valido</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal the RFC is not valid -->
    <div class="modal fade" id="modal_RFC_invalido">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">RFC Invalido</h4>
                </div>
                <div class="modal-body">
                    <h5>El RFC ingresado no es valido</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function(){
            //display the message from the backend in a model otherwise its hidden
            if("<?php echo $info['msg']?>" == ""){
                $("#container_msg").hide()
            }else if("<?php echo $info['color']?>" == "blue"){
                $("#container_msg").attr("class","container-msg-success")
                setTimeout(() => {
                    $("#container_msg").slideToggle(900)
                }, 2000);
            }else{
                $("#container_msg").attr("class","container-msg-failure")
                setTimeout(() => {
                    $("#container_msg").slideToggle(900)
                }, 3000);
            }
            history.replaceState({}, document.title, "/Restaurante_website/Clientes/cliente.index.php?id=<?php echo $_GET['id'];?>")
        }

        function formattingRFC(){
            var rfc = $("#RFC").val()
            if(rfc.length <= 15 && rfc !== ''){
                //find every character in the string and set it to upper
                const caracter = rfc[rfc.length-1]
                if(!/[0-9]{1}/.test(caracter)){
                    rfc = rfc.substring(0,rfc.length-1)+caracter.toUpperCase()
                    $("#RFC").val(rfc)
                }
                if (rfc.length === 4 || rfc.length === 11) {
                    $("#RFC").val(rfc+'-')
                }
            }else{
                rfc = rfc.substring(0,rfc.length-1)
                $("#RFC").val(rfc)
            }
        }

        function validateRFC(){
            const rfc = $("#RFC").val()
            if(!/[A-Z]{4}[-]{1}[0-9]{6}[-]{1}[A-Z]{2}[0-9]{1}/.test(rfc) && rfc !== ''){
                $('#modal_RFC_invalido').modal('show')
                $("#RFC").val('')
            }
        }

        //function for the remove button
        function Remove_Client(id){
            var status = $("#cli_status_"+id).text()
            if(status == "Desactivado"){
                $("#modal_cli_activo").modal('show')
            }else{
                $(".remove").attr("id",id)
                $("#modal_remove_cli").modal('show')
            }
        }

        //every button with this class
        $("button.btn-remove-hover").click(function(){
            Remove_Client($(this).attr("id"))
        })

        //the remove button in the model
        $(".remove").click(function(){
            var id = $(this).attr("id")
            window.location = "cliente.index.php?id=<?php echo $_GET['id'];?>&action=eliminar&id_cliente="+id
        })

        function validateEmail() {
            const email = document.getElementById('correo').value
            var expression = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!expression.test(email) && email != ''){
                $("#modal_email_invalido").modal('show')
                document.getElementById('correo').value = ''
            }   
        }

        //while inactive, make sure the the client is now active before saving the modifications
        $("#submit").click(event,function(){
            var status = $("#status").val()
            if(status == 'Desactivado'){
                $("#modal_cli_desactivado").modal('show')
                event.preventDefault()
            }
        })
        
        //if the client is active disable the status's field
        var status = "<?php echo $alm->__GET('Status');?>"
        if(status == '' || status == 'Activo'){
            $("#status_option").attr("hidden","true")
        }

        $("#clear").click(() => {
            $("input").val("")
            $("#img").attr("src","/Restaurante_website/Imagenes/userNotFound.png")
            history.replaceState({}, document.title, "/Restaurante_website/Clientes/cliente.index.php?id=<?php echo $_GET['id'];?>")
        })

        //list records
        $('#search_nombre').keyup(function(){
            var value = $(this).val()
            listar_ajax(value,"nombre_cliente")
        })

        $('#search_correo').keyup(function(){
            var value = $(this).val()
            listar_ajax(value,"correo_cliente")
        })

        $('#search_RFC').keyup(function(){
            var value = $(this).val()
            listar_ajax(value,"RFC_cliente")
        })

        let f_lap = true, id_rows = 0

        function listar_ajax(value,column){
            $("a#pdf").attr("href","clientesPDF.php?value="+value+"&column="+column)
            /*
                first keyup is controled by php variable
                form second keyup and forward is controled by javascript variable
            */
            id_rows = f_lap ? "<?php echo $id_row;?>" : id_rows
            f_lap = false
            $.ajax({
                type: "GET",
                url: "cliente.dao.php",
                data: "list="+value+"&column="+column,
                success: function(data){
                    var json = eval(data)
                    //remove all the records in the table
                    for(index = 0; index <= id_rows; index++){
                        $("#"+index).remove()
                    }
                    var id_row = 0
                    if(json && json[0].msg == ""){
                        for(let i = 0; i < json.length; i++){
                            $("thead").append(`<tr class='row_selected' id="${(++id_rows)}">
                                <td class='td_lista'>${json[i].nombre}</td>
                                <td class='td_lista'>${json[i].ap}</td>
                                <td class='td_lista'>${json[i].am}</td>
                                <td class='td_lista'>${json[i].correo}</td>
                                <td class='td_lista'>${json[i].status}</td>
                                <td class='td_lista'>${json[i].RFC}</td>
                                <td class='td_lista'>
                                    <form action='?id=${<?php echo $_GET['id'];?>}&action=editar&id_cliente=${json[i].id}' method='post'>
                                        <button>Editar</button>
                                    </form>
                                </td>
                                <td class='td_lista'>
                                    <button id="${json[i].id}" onclick='Remove_Client(${json[i].id})' name='delete' type='button' class='btn-remove-hover'>Eliminar</button>
                                </td>
                            </tr>`)
                        }
                    }else{
                        var str = ""
                        for(let i = 0; i < 8; i++ ){
                            str += "<td class='td_lista'>"+json[0].msg+"</td>"
                        }
                        $("thead").append(`<tr class='row_selected' id="${(++id_rows)}">${str}</tr>`)
                    }
                }
            })
        }
    </script>
    
    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
