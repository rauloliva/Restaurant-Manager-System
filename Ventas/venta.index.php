<?php
require_once 'venta.dto.php';
require_once 'venta.dao.php';
require_once dirname(__DIR__).'/pdo.php';

$pdo = new PDO_connection();
$alm = new Venta();
$model = new VentaDAO($pdo);
$id_row = 0; //enum the rows in the table

session_start();
if(isset($_GET['id'])){
    $tipo = $_SESSION[$_GET['id'].'tipo'];
    $ID = $_GET['id'];
    $user = $_SESSION[$_GET['id'].'user'];
    $foto = $_SESSION[$_GET['id'].'foto_empleado'];
}else{
    header("Location: /Restaurante_website/404/index.html");
}

if(isset($_GET['cuenta'])){
    $stm = $pdo->prepare("SELECT * FROM `venta-platillo` WHERE id_venta_platillo = ?");
    $stm->execute([$_GET['cuenta']]);
    if($row = $stm->fetch(PDO::FETCH_OBJ)){
        $platillos = $row->nombre_venta_platillo;
        $precios = $row->precio_venta_platillo;
        $cantidades = $row->cantidad_platillo;
        $alm->__SET('Total', $row->venta_total);
        $alm->__SET('Id_venta_platillo',$_GET['cuenta']); 
    }
}

if(isset($_REQUEST['registrar'])){
    $time = substr($_REQUEST['Hora'],0,5);
    $alm->__SET('Total',            str_replace("$","",$_REQUEST['Total']));
    $alm->__SET('Hora',             $time);
    $alm->__SET('Fecha',            date("Y-m-d"));
    $alm->__SET('IdCliente',        $_REQUEST['IdCliente']);
    $alm->__SET('IdEmpleado',       $ID);
    $alm->__SET('Id_venta_platillo',$_REQUEST['Id_venta_platillo']);
    $alm->__SET('Status',          'Activo');
    $info = $model->Registrar($alm);
    header('Location: /Restaurante_website/Venta_Platillo/salon_comedor.php?id='.$_GET['id'].'&msg='.$info['msg']."&color=".$info['color']);
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
    <title>Ventas</title>
    <style>
        .img-selected,.img-selected:hover{
            border: 7px solid blue;
        }
        .label-style{
            border: 1px solid blue;
            font-weight: bold;
            font-size: 23px;
            height: 40px;
            color: black;
            background: #4da8be;
            border-radius: 8px 8px 8px 8px; 
        }
        .info-cliente{
            width: auto;
            border: 1px solid white;
        }
        th{  
            color: black
        }
    </style>
</head>
<body class="body-ventas">
    <div class="header">
        <h1>Ventas</h1> 
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li class="nav-item nav-headers" style="border-radius: 5px 0 0 5px">
                <a id="menu_dashboard" class="nav-link" href="/Restaurante_website/principal.php?id=<?php echo $_GET['id'];?>">Dashboard</a>
            </li>
            <li class="nav-item nav-headers">
                <a id="menu_empleados" class="nav-link" href="/Restaurante_website/Empleados/empleado.index.php?id=<?php echo $_GET['id'];?>">Empleados</a>
            </li>
            <li class="nav-item nav-headers">
                <a id="menu_clientes" class="nav-link" href="/Restaurante_website/Clientes/cliente.index.php?id=<?php echo $_GET['id'];?>">Clientes</a>
            </li>
            <li class="nav-item nav-headers">
                <a id="menu_platillos" class="nav-link" href="/Restaurante_website/Platillos/platillo.index.php?id=<?php echo $_GET['id'];?>">Platillos</a>
            </li>
            <li class="nav-item nav-headers" id="salon_comedor">
                <a id="menu_mesas" class="nav-link" href="/Restaurante_website/Mesas/mesa.index.php?id=<?php echo $_GET['id'];?>">Mesas</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" id="pdf" href="ventasPDF.php">
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
    <div class="container_form form-ventas">
        <table align="center" style="width:655px;">
            <tr>
                <th style="text-align:left;">Total</th>
                <td><label id="Total" class="label-style"><?php echo empty($alm->__GET('Total')) ? "Venta no realizada" :$alm->__GET('Total') ;?></label></td>
            </tr>
            <tr>
                <th style="text-align:left;">Hora</th>
                <td><label id="Hora" class="label-style"></label></td>
            </tr>
            <tr>
                <th style="text-align:left;">Fecha</th>
                <td><label id="Fecha" class="label-style"><?php echo date("Y-m-d");?></label></td>
            </tr>
            <tr>
                <th style="text-align:left;">Nombre Cliente</th>
                <td>
                    <select id="nombres_clientes" name="NomCliente" style="width:83%;margin-bottom:7px;">
                        <option value="Seleccionar" id="opt_seleccionar">Seleccionar</option>
                        <?php
                            $stm = $pdo->query("SELECT * FROM clientes where status_cliente = 'Activo'");
                            while($row = $stm->fetch(PDO::FETCH_OBJ)){
                                $nameCliente = $row->nombre_cliente." ".$row->ap_cliente." ".$row->am_cliente;
                                echo "<option value='".$nameCliente."' id='id_cli_".$row->id_cliente."' class='correo_".$row->correo_cliente."'>".$nameCliente."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <th style="text-align:left;">Id Cliente</th>
                <td><label id="IdCliente" class="label-style"></label></td>
            </tr>
            <tr>
                <th style="text-align:left;">Id Empleado</th> 
                <td><label id="IdEmpleado" class="label-style" name="IdEmpleado"><?php echo $ID;?></label></td>
            </tr>
            <tr>
                <th style="text-align:left;">Id Venta Platillo</th>
                <td><label id="Id_venta_platillo" class="label-style"><?php echo empty($alm->__GET('Id_venta_platillo')) ? "Venta no realizada" :$alm->__GET('Id_venta_platillo') ;?></label></td>
            </tr>
            <tr>
                <td id="actions" colspan="2">
                    <button id="submit" type="submit" name="submit">Guardar</button>
                    <button id="clear" type="button">Limpiar</button>
                </td>
            </tr>
        </table>
    </div><br>
    <div class="container_search">
        <h3 style="color:black;font-weight:700;padding-left:30px">Buscar Venta</h3>
        <table align="center" style="width:555px;">
            <tr>
                <th>Fecha de la venta:</th>
                <td><input type="date" style="width: 80%;" placeholder="Fecha" name="search_fecha" id="search_fecha"></td>
            </tr>
            <tr>
                <th>Id del Cliente:</th>
                <td>
                    <select style="width: 80%;" name="search_idCliente" id="search_idCliente">
                        <option id="cli_Seleccionar">Seleccionar</option>
                        <?php
                            $stm = $pdo->query("SELECT id_cliente FROM clientes WHERE status_cliente='activo'");
                            while($row = $stm->fetch(PDO::FETCH_OBJ)){
                                echo "<option id='cli_".$row->id_cliente."'>".$row->id_cliente."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Id del Empleado:</th>
                <td>
                    <select style="width:80%;" name="search_idEmpleado" id="search_idEmpleado">
                        <option id="Seleccionar">Seleccionar</option>
                        <?php
                            $stm = $pdo->query("SELECT id_empleado FROM empleados WHERE status_empleado='activo'");
                            while($row = $stm->fetch(PDO::FETCH_OBJ)){
                                echo "<option id='emp_".$row->id_empleado."'>".$row->id_empleado."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table> 
    </div><br>
    <div class="container_table_small_mini">
        <table id="table">
            <thead>
                <tr class="row_selected">
                    <th style="text-align:left;" class="th_lista">Total</th>
                    <th style="text-align:left;" class="th_lista">Hora</th>
                    <th style="text-align:center; width: 150px;" class="th_lista">Fecha</th>
                    <th style="text-align:left;" class="th_lista">Id Cliente</th>
                    <th style="text-align:left;" class="th_lista">Id Empleado</th>
                    <th style="text-align:left;" class="th_lista">Id Venta Platillo</th>
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
                        echo "</td>";
                    }
                ?>  
                <?php foreach($arrayListar as $r): ?>
                <tr class="row_selected" id="<?php echo $id_row++;?>">
                    <td class="td_lista"><?php echo $r->__GET('Total'); ?></td>
                    <td class="td_lista"><?php echo $r->__GET('Hora'); ?></td>
                    <td class="td_lista" style="text-align: center;"><?php echo $r->__GET('Fecha'); ?></td>
                    <td class="td_lista" style="text-align: center;"><?php echo $r->__GET('IdCliente'); ?></td>
                    <td class="td_lista" style="text-align: center;"><?php echo $r->__GET('IdEmpleado'); ?></td>
                    <td class="td_lista" style="text-align: center;"><?php echo $r->__GET('Id_venta_platillo'); ?></td>
                    <td><input type="hidden" value="<?php echo $r->__GET('id'); ?>"/></td>
                </tr>
                <?php endforeach; ?>
        </table>    
    </div>  
    <!-- Modal Ticket -->
    <div class="modal fade" id="modal_ticket">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos del Ticket</h4>
                </div>
                <div class="modal-body" id="modal-body"></div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cerrar</button>
                    <button id="print">Imprimir Ticket</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal no client selected -->
    <div class="modal fade" id="modal_no_client">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cliente no seleccionado</h4>
                </div>
                <div class="modal-body">
                    <h5>No se ha seleccionado un cliente para esta venta</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal waitre wants to go to 'salon comedor'-->
    <div class="modal fade" id="modal_salon_comedor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Abandonar Venta</h4>
                </div>
                <div class="modal-body">
                    <h5>Toda la informacion de esta mesa se perdera ¿Continuar?</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn-remove-hover" id="btn_salon_comedor">Aceptar</button>
                    <button data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var jsonObj = [], isPaused = false
        window.onload = function(){
            var options =  $('#nombres_clientes').children()
            $("#IdCliente").text(options[0].id == 'opt_seleccionar' ? 'Cliente no seleccionado': options[0].id.replace())
            const tipo = "<?php echo $tipo;?>"
            habilitar(tipo)
            //set Date
            var now = ""
            setInterval(()=>{
                if(!isPaused){
                    var date = new Date(),hour = date.getHours()
                    ,minutes = date.getMinutes(), seconds = date.getSeconds(), time
                    hour = hour <= 9 ? "0"+hour : hour
                    minutes = minutes <= 9 ? "0"+minutes : minutes
                    seconds = seconds <= 9 ? "0"+seconds : seconds
                    time = hour <= 11 ? "AM" : "PM"
                    var now = hour+":"+minutes+":"+seconds+" "+time
                    $("#Hora").text(now)
                }
            },1000)
        }

        var correo = "", nom_cliente = "" // variables used to send an email
        $('#nombres_clientes').change(function(){
            var options = this.children
            for (let index = 0; index < options.length; index++) {
                if($("#"+options[index].id).is(":selected")){
                    nom_cliente = $("#"+options[index].id).val()
                    var id_cliente = options[index].id.replace("id_cli_","")
                    correo = $("#"+options[index].id).attr("class").replace("correo_","")
                    $("#IdCliente").text(id_cliente)
                }
            }
        })

        $("#print").click(function(){
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head><title>Imprimir Ticket</title>');
            mywindow.document.write('</head><body>');
            mywindow.document.write('<h1>Restaurante</h1>');
            mywindow.document.write($("#modal-body").html()+"<h4 style='padding-left: 20px'>Gracias por su visita</h4>");
            mywindow.document.write('</body></html>');
            mywindow.print();
            mywindow.close();
            var obj = JSON.stringify(jsonObj)
            $.ajax({
                type: "GET",
                url: "sendMail.php",
                data: "mail=1&correo="+correo+"&nom_cliente="+nom_cliente+"&data="+obj+"&total=<?php echo $alm->__GET('Total');?>",
                success : function(data){
                    var json = eval(data)
                    if(json[0].msg === "Done"){
                        location = `venta.index.php?id=<?php echo $_GET['id'];?>&registrar=1&user=<?php echo $user;?>&
                                Total=${$("#Total").text()}&IdCliente=${$("#IdCliente").text()}&
                                Id_venta_platillo=${$("#Id_venta_platillo").text()}&
                                Hora=${$("#Hora").text()}`
                    }else{
                        location = "venta.index.php?id=<?php echo $_GET['id'];?>&msg=Ha ocurrido un error&color=red"
                    }
                }
            })
        })

        $("#clear").click(() => {
            $("input").val("")
            $("textarea").text("")
            $("#dueño").attr("selected","true")
            $("#img_cliente").attr("src","/Restaurante_website/Imagenes/userNotFound.png")
            history.replaceState({}, document.title, "/Restaurante_website/Ventas/venta.index.php?id=<?php echo $_GET['id'];?>")
        })

        //list records
        $('#search_idCliente').change(function(){
            var value = ""
            var children = $(this).children()
            for(let i = 0; i < children.length; i++){
                if($("#cli_"+children[i].value).is(":selected")){
                    value = " = \'"+children[i].value+"\'"
                }else if(value == " = \'Seleccionar\'"){
                    value = " LIKE \'%%\'"
                }
            }
            listar(value,"id_cliente")
        })

        $('#search_idEmpleado').change(function(){
            var value = ""
            var children = $(this).children()
            for(let i = 0; i < children.length; i++){
                if($("#emp_"+children[i].value).is(":selected")){
                    value = " = \'"+children[i].value+"\'"
                }else if(value == " = \'Seleccionar\'"){
                    value = " LIKE \'%%\'"
                }
            }
            listar(value,"id_empleado")
        })

        $('#search_fecha').change(function(){
            var value = " = '"+$(this).val()+"'"
            listar(value,"fecha_venta")
        })

        let f_lap = true, id_rows = 0
        function listar(value,column){
            $("a#pdf").attr("href","ventasPDF.php?val="+value+"&col="+column)
            /*
                first keyup is controled by php variable
                form second keyup and forward is controled by javascript variable
            */
            id_rows = f_lap ? "<?php echo $id_row;?>" : id_rows
            f_lap = false
            $.ajax({
                type: "GET",
                url: "venta.dao.php",
                data: "value="+value+"&column="+column,
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
                                <td class='td_lista'>${json[i].total}</td>
                                <td class='td_lista'>${json[i].hora}</td>
                                <td class='td_lista'>${json[i].fecha}</td>
                                <td class='td_lista'>${json[i].id_cliente}</td>
                                <td class='td_lista'>${json[i].id_empleado}</td>
                                <td class='td_lista'>${json[i].id_venta_platillo}</td>
                            </tr>`)
                        }
                    }else{
                        var str = ""
                        for(let i = 1; i <= 6; i++ ){
                            str += "<td class='td_lista'>"+json[0].msg+"</td>"
                        }
                        $("thead").append(`<tr class='row_selected' id="${(++id_rows)}">${str}</tr>`)
                    }
                }
            })
        }

        $("#submit").click(event,function(){
            if($("#opt_seleccionar").is(":selected")){
                $("#modal_no_client").modal('show')
            }else{
                isPaused = true // stop the hour interval
                createTicket()
                $("#modal_ticket").modal('show')
                isPaused = false // continue the hour interval
            }
        })

        createTicket = function(){
            //ticket
            var platillos = "<?php echo isset($platillos) ? $platillos : '';?>".split(",")
                    ,cantidades = "<?php echo isset($cantidades) ? $cantidades : '';?>".split(",")
                    ,precios = "<?php echo isset($precios) ? $precios : '';?>".split(",")
                    ,ticket = `Fecha y Hora:  <?php echo date("Y-m-d");?> ${$("#Hora").text()}<br><br>
                                <table><thead><tr>
                                    <th style='padding-rigth:15px; padding-left:15px'>Platillo</th>
                                    <th style='padding-rigth:15px; padding-left:15px'>Cantidad</th>
                                    <th style='padding-rigth:15px; padding-left:15px'>Precio</th>
                                </tr></thead>`
            for(let i = 0; i < platillos.length; i++){
                ticket += `<tr>
                            <td style='padding-rigth:15px; padding-left:15px'>${platillos[i]}</td>
                            <td style= 'padding-rigth:15px; padding-left:15px; text-align: center'>${cantidades[i]}</td>
                            <td style='padding-rigth:15px; padding-left:15px'>${precios[i]}</td>
                        </tr>`
                jsonObj.push({platillo: platillos[i], cantidad: cantidades[i], precio: precios[i]})    
            }
            ticket += `</table><br>
                        Total: <?php echo isset($_GET['cuenta']) ?  $alm->__GET('Total') : '';?><br>`
            $("#modal-body").html(ticket)
        }

        function habilitar(tipo){
            switch (tipo) {
                case "Dueño":
                case "Gerente":
                    $("#actions").hide()
                    break;
                case "Administrador Ventas":
                    $("#actions").hide()
                    $("#menu_empleados").hide();
                    $("#menu_clientes").hide();
                    $("#menu_platillos").hide();
                    $("#menu_mesas").hide();
                    break;
                case "Mesero":
                    $("#menu_empleados").hide();
                    $("#menu_clientes").hide();
                    $("#menu_dashboard").hide();
                    $("#menu_platillos").hide();
                    $("#menu_mesas").text("Salon Comedor");
                    $("#menu_mesas").attr("href","#");
                    $("#salon_comedor").attr("style","border-radius:5px 0 0 5px")
                    $("#salon_comedor").click(() => $('#modal_salon_comedor').modal('show'))
                    $("#btn_salon_comedor").click(() => location = "/Restaurante_website/Venta_Platillo/salon_comedor.php?id=<?php echo $_GET['id'];?>")
                    $(".container_search").hide()
                    $(".container_table_small_mini").hide()
                    $("#platillos").hide()
            }
        }
    </script>
    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>