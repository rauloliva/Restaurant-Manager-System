<?php
require_once 'platillo.dto.php';
require_once 'platillo.dao.php';
require_once dirname(__DIR__).'/pdo.php';

$pdo = new PDO_connection();
$alm = new Platillo();
$model = new PlatilloDAO($pdo);
$id_row = 0; //enum the rows in the table

session_start();
if(isset($_GET['id'])){
    $tipo = $_SESSION[$_GET['id'].'tipo'];
    $foto = $_SESSION[$_GET['id'].'foto_empleado'];
}else{
    header("Location: /Restaurante_website/404/index.html");
}

if(isset($_GET['msg'])){
    $info = array("msg" => $_GET['msg'],"color" => $_GET['color']);
}else{
    $info = array("msg" => "","color" => "");
}

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
			$alm->__SET('id',              $_REQUEST['id_platillo']);
			$alm->__SET('Nombre',          $_REQUEST['Nombre']);
			$alm->__SET('Precio',          $_REQUEST['Precio']);
            $alm->__SET('Precio_Platillo', $_REQUEST['Precio_Platillo']);
			$alm->__SET('Ingredientes',    $_REQUEST['Ingredientes']);
			$alm->__SET('Categoria',       $_REQUEST['Categoria']);
			$alm->__SET('Status',   	   $_REQUEST['Status']);
			$alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
			$alm->__SET('FechaDeDegreso',  $_REQUEST['FechaDeDegreso']);
			$info = $model->Actualizar($alm,$foto);
            header('Location: platillo.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
			break;

        case 'registrar':
            $image = "";
            if($_FILES['Foto']['tmp_name'] != null){
                if(getimagesize($_FILES['Foto']['tmp_name']) == TRUE){
                    $image = addslashes($_FILES['Foto']['tmp_name']);
                    $image = file_get_contents($image);
                    $image = base64_encode($image);
                }
            }
            $alm->__SET('Nombre',          $_REQUEST['Nombre']);
            $alm->__SET('Precio',          $_REQUEST['Precio']);
            $alm->__SET('Precio_Platillo', $_REQUEST['Precio_Platillo']);
            $alm->__SET('Ingredientes',    $_REQUEST['Ingredientes']);
            $alm->__SET('Categoria',       $_REQUEST['Categoria']);
			$alm->__SET('Status',          'Activo');
            $alm->__SET('FechaDeIngreso',  $_REQUEST['FechaDeIngreso']);
            $alm->__SET('Foto',            $image);
			$info = $model->Registrar($alm);
			header('Location: platillo.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
			break;

        case 'eliminar':
            $info = $model->Eliminar($_REQUEST['id_platillo']);
            header('Location: platillo.index.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
            break;

		case 'editar':
            $alm = $model->Obtener($_REQUEST['id_platillo']);
            //replaceing '<br>' to '\n'
            $ing = str_replace("<br>","\n",$alm->__GET('Ingredientes'));
            $alm->__SET('Ingredientes',$ing);
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
    <title>Platillos</title>
    <style>th{color: black}</style>
</head>
<body class="body-platillos">
    <div class="header">
        <h1>Platillos</h1> 
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li class="nav-item nav-headers" style="border-radius: 5px 0 0 5px">
                <a class="nav-link" href="/Restaurante_website/principal.php?id=<?php echo $_GET['id'];?>">Dashboard</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Empleados/empleado.index.php?id=<?php echo $_GET['id'];?>">Empleados</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Clientes/cliente.index.php?id=<?php echo $_GET['id'];?>">Clientes</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Ventas/venta.index.php?id=<?php echo $_GET['id'];?>">Ventas</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Mesas/mesa.index.php?id=<?php echo $_GET['id'];?>">Mesas</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" id="pdf" href="platillosPDF.php">
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
    <div class="container_form form-platillos">
        <form enctype="multipart/form-data" action="?id=<?php echo$_GET['id']?>&action=<?php echo $alm->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" style="margin-bottom:30px;">
            <input type="hidden" id="id" name="id_platillo" value="<?php echo $alm->__GET('id'); ?>" />        
            <input id="fechaActual" type="hidden" name="FechaDeIngreso" 
                value="<?php
                    $var = $alm->__GET('FechaDeIngreso');
                    echo $var != "" && $var != null ? $var : date("Y-m-d");
                ?>" style="width:100%;"/>
            <table class="form" align="center" style="width:555px;">
                <tr>
                    <th style="text-align:left;">Nombre</th>
                    <td><input required placeholder="Nombre" id="nombre" class="selected" type="text" name="Nombre" value="<?php echo $alm->__GET('Nombre'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Precio</th>
                    <td><input required placeholder="Precio" id="precio" class="selected" type="text" name="Precio" value="<?php echo $alm->__GET('Precio'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Precio Platillo</th>
                    <td><input required placeholder="Precio Platillo" id="precio_platillo" class="selected" type="text" name="Precio_Platillo" value="<?php echo $alm->__GET('Precio_Platillo'); ?>" style="width:100%;" /></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Ingredientes</th>
                    <td><textarea required placeholder="Ingredientes" id="ingredientes" class="selected" type="text" name="Ingredientes" style="width:100%; height:150px;" ><?php echo $alm->__GET('Ingredientes'); ?></textarea></td>
                </tr>
                <tr>
                    <th style="text-align:left;">Categoria</th>
                    <td>
                        <select class="selected" name="Categoria" style="width: 100%">
                            <option value="Platillo fuerte" <?php echo $alm->__GET('Categoria')=='Platillo fuerte'?'selected':''; ?>>Platillo Fuerte</option>
                            <option value="Desayuno" <?php echo $alm->__GET('Categoria')=='Desayuno'?'selected':''; ?>>Desayuno</option>
                            <option value="Aperitivo" <?php echo $alm->__GET('Categoria')=='Aperitivo'?'selected':''; ?>>Aperitivo</option>
                            <option value="Postre" <?php echo $alm->__GET('Categoria')=='Postre'?'selected':''; ?>>Postre</option>
                            <option value="Bebida" <?php echo $alm->__GET('Categoria')=='Bebida'?'selected':''; ?>>Bebida</option>
                        </select>
                    </td>
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
                    <?php
                        if($alm->__GET('id') != null){
                            $stm = $pdo->prepare("SELECT foto_platillo FROM `platillos` WHERE id_platillo = ?");
                            $stm->execute([$alm->__GET('id')]);
                            if($row = $stm->fetch(PDO::FETCH_OBJ)){
                                if($row->foto_platillo != ""){
                                    echo "<td><img id='img' src='data:image;base64,".$row->foto_platillo."' class='foto-modules' style='cursor:default;'></td>";
                                }else{
                                    echo "<td><img id='img' src='/Restaurante_website/Imagenes/dishNotFound.jpg' class='foto-modules' style='cursor:default;'></td>";         
                                }
                            }
                        }else{
                            echo "<td><img id='img' src='/Restaurante_website/Imagenes/dishNotFound.jpg' class='foto-modules' style='cursor:default;'></td>";
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
                        <button type="submit" id="submit" name="submit">Guardar</button>
                        <button id="clear">Limpiar</button>
                    </td>
                </tr>
            </table>
        </form>
    </div><br>
    <div class="container_search con_search_platillos">
        <h3>Buscar Platillo</h3>
        <h5 style="float: left;">Filtros</h5>
        <table class="form" align="center" style="width:555px;">
            <tr>
                <th>Nombre del Platillo:</th>
                <td><input type="text" style="width: 100%;" placeholder="Nombre" name="search_nombre" id="search_nombre"></td>
            </tr>
            <tr>
                <th>Precio (Rango)</th>
                <td><input type="number" style="width: 100%;" placeholder="Precio" name="search_precio1" id="search_precio1"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="number" style="width: 100%;" placeholder="Precio" name="search_precio2" id="search_precio2"></td>
            </tr>
            <tr>
                <th>Categoria</th>
                <td>
                    <select id="search_categoria" name="search_categoria" style="width: 100%">
                        <option id="cat_0" value="null">[-------------------------]</option>
                        <option id="cat_1" value="Platillo fuerte">Platillo Fuerte</option>
                        <option id="cat_2" value="Desayuno">Desayuno</option>
                        <option id="cat_3" value="Bebida">Bebida</option>
                        <option id="cat_4" value="Postre">Postre</option>
                    </select>
                </td>
            </tr>
        </table> 
    </div><br>
    <div class="container_table_small">
        <table>
            <thead>
                <tr class="row_selected">
                    <th style="text-align:center;" class="th_lista th-border-left">Nombre</th>
                    <th style="text-align:center;" class="th_lista th-border-righ">Precio</th>
                    <th style="text-align:center;" class="th_lista th-border-righ">Precio Platillo</th>
                    <th style="text-align:center;" class="th_lista th-border-righ">Ingredientes</th>
                    <th style="text-align:center;" class="th_lista th-border-righ">Categoria</th>
                    <th style="text-align:center;" class="th_lista th-border-righ">Status</th>
                    <th style="text-align:center;" class="th_lista th-border-righ">Editar</th>
                    <th style="text-align:center;" class="th_lista th-border-right">Eliminar</th>
                </tr>
            </thead>
                <?php 
                    if($arrayListar == null){
                        echo "<tr>";
                        for($i = 0; $i < 10; $i++){
                            echo "<td>No hay Informacion</td>";
                        }
                        echo "</td>";
                    }
                ?>        
                <?php foreach($arrayListar as $r): ?>
                    <tr class="row_selected" id="<?php echo $id_row++;?>">
                        <td class="td_lista"><?php echo $r->__GET('Nombre'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Precio'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Precio_Platillo'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Ingredientes'); ?></td>
                        <td class="td_lista"><?php echo $r->__GET('Categoria'); ?></td>
                        <td class="td_lista" id="dish_status_<?php echo $r->id;?>"><?php echo $r->__GET('Status'); ?></td>
                        <td class="td_lista">
                            <form action="?id=<?php echo$_GET['id']?>&action=editar&id_platillo=<?php echo $r->id;?>" method="post">
                                <button>Editar</button>
                            </form>
                        </td>
                        <td class="td_lista">
                            <button id="<?php echo $r->id;?>" name="delete" type="submit" class="btn-remove-hover">Eliminar</button>
                        </td>
                        <td><input type="hidden" value="<?php echo $r->__GET('id'); ?>"/></td>
                    </tr>
                <?php endforeach; ?>
        </table>    
    </div> 
    <!-- Modal: dish has been removed before -->
    <div class="modal fade" id="modal_dish_activo" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="color:red" class="modal-title">Platillo Desactivado</h4>
                </div>
                <div class="modal-body">
                    <h5 style="color:black">El platillo ya ha sido dado de baja con anterioridad</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: change the status so the dish could be updated -->
    <div class="modal fade" id="modal_dish_desactivado" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="color:red" class="modal-title">Platillo todavia Desactivado</h4>
                </div>
                <div class="modal-body">
                    <h5 style="color:black">El platillo sigue estando de baja<br>Se necesita cambiar el estatus</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div> 
    <!-- Modal: confirm to remove dish -->
    <div class="modal fade" id="modal_remove_dish" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="color:red" class="modal-title">Eliminar Platillo</h4>
                </div>
                <div class="modal-body">
                    <h5 style="color:black">El platillo Sera eliminado ¿Continuar?</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cancelar</button>
                    <button class="remove btn-remove-hover" data-dismiss="modal">Aceptar</button>
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
            history.replaceState({}, document.title, "/Restaurante_website/Platillos/platillo.index.php?id=<?php echo $_GET['id'];?>")
        }

        //function for the remove button  
        function Remove_Dish(id){
            var status = $("#dish_status_"+id).text()
            if(status == "Desactivado"){
                $("#modal_dish_activo").modal('show')
            }else{
                $(".remove").attr("id",id)
                $("#modal_remove_dish").modal('show')
            }
        }

        //every button with this class
        $("button.btn-remove-hover").click(function(){
            Remove_Dish($(this).attr("id"))
        })

        //the remove button in the model
        $(".remove").click(function(){
            var id = $(this).attr("id")
            location = "platillo.index.php?id=<?php echo $_GET['id'];?>&action=eliminar&id_platillo="+id
            id = 0
        })

        $("#clear").click(() => {
            $("input").val("")
            $("textarea").text("")
            $("#dueño").attr("selected","true")
            $("#img").attr("src","/Restaurante_website/Imagenes/dishNotFound.jpg")
            history.replaceState({}, document.title, "/Restaurante_website/Platillos/platillo.index.php?id=<?php echo $_GET['id'];?>")
        })

        //list records
        $('#search_nombre').keyup(function(){
            var value = $(this).val()
            listar_ajax(value,"name","nombre_platillo")
        })
        
        $('#search_precio2').keyup(function(){
            var value1 = $('#search_precio1').val()
            if(value1 != ""){
                var value2 = $(this).val()
                listar_ajax(value1,value2,"precio_platillo_venta")
            }
        })

        $('#search_categoria').change(function(){
            var value = ""
            const options = $(this).children()
            for (let i = 0; i < options.length; i++) {
                if($("#"+options[i].id).is(":selected")) {
                    value = options[i].value;
                }   
            }
            listar_ajax(value,"cat","categoria_platillo")
        })

        let f_lap = true, id_rows = 0

        function listar_ajax(value,value2,column){
            $("a#pdf").attr("href","platillosPDF.php?value1="+value+"&value2="+value2+"&col="+column)
            /*
                first keyup is controled by php variable
                form second keyup and forward is controled by javascript variable
            */
            id_rows = f_lap ? "<?php echo $id_row;?>" : id_rows
            f_lap = false
            $.ajax({
                type: "GET",
                url: "platillo.dao.php",
                data: "value1="+value+"&value2="+value2+"&column="+column,
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
                                <td class='td_lista'>${json[i].precio}</td>
                                <td class='td_lista'>${json[i].precio_venta}</td>
                                <td class='td_lista'>${json[i].ingredientes}</td>
                                <td class='td_lista'>${json[i].categoria}</td>
                                <td class='td_lista'>${json[i].status}</td>
                                <td class='td_lista'>
                                    <form action='?id=${<?php echo $_GET['id']?>}&action=editar&id=${json[i].id}' method='post'>
                                        <button>Editar</button>
                                    </form>
                                </td>
                                <td class='td_lista'>
                                    <button id="${json[i].id}" onclick='Remove_Dish(${json[i].id})' name='delete' type='button' class='btn-remove-hover'>Eliminar</button>
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

        //show the selected image in the img tag
        $("#my-file").change(function(){
            $('#img')[0].src = (window.URL ? URL : webkitURL).createObjectURL(this.files[0]);
        })
        
        //cross site scripting
        function removeXSS(){
            var ingre = $("#ingredientes").val()
            ingre = ingre.replace(/</g,'').replace(/>/g,'').replace('/','')
            $("#ingredientes").val(ingre)
        }

        //while inactive, make sure the the employee is now active before saving the modifications
        $("#submit").click(event,function(){
            removeXSS()
            var status = $("#status").val()
            if(status == 'Desactivado'){
                $("#modal_dish_desactivado").modal('show')
                event.preventDefault()
            }
            /*
                writting '<br>' instead of '\n'
                to get better format in pdf file */
            var ingredientes = ""
            var value = $("textarea#ingredientes").val()
            for(let i = 0; i < value.length; i++){
                if(value[i] === '\n'){
                    ingredientes += '<br>'
                    continue
                }
                ingredientes += value[i]
            }
            $("textarea#ingredientes").val(ingredientes)
        })

        $("a").click(function(){
            var style = $(this).attr("class");
            if(style == "disabled"){
                alert("No tienes acceso a esta ventana")
            }
        })

        $("#search_precio1").change(function(){
            if($("#search_precio1").text() != ''){
                $("#search_precio2").attr("required","true")
            }
        })

        var status = "<?php echo $alm->__GET('Status');?>"
        if(status == '' || status == 'Activo'){
            $("#status_option").attr("hidden","true")
        }

        //Input File
        document.querySelector("html").classList.add('js');
        var fileInput  = document.querySelector( ".input-file" ),  
            button     = document.querySelector( ".input-file-trigger" ),
            the_return = document.querySelector(".file-return");
        button.addEventListener( "keydown", function( event ) {  
            if ( event.keyCode == 13 || event.keyCode == 32 ) {  
                fileInput.focus();  
            }  
        })
        button.addEventListener( "click", function( event ) {
            fileInput.focus();
            return false;
        })  
        fileInput.addEventListener( "change", function( event ) {  
            the_return.innerHTML = this.value.replace("C:\\fakepath\\","")
        })
    </script>
    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
