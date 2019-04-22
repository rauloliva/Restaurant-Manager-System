<?php
    require_once "venta.dao.php";
    require_once "venta.dto.php";
    require_once dirname(__DIR__).'/pdo.php';

    $pdo = new PDO_connection();
    $dto = new Venta();
    $dao = new VentaDAO($pdo);

    session_start();
    if(isset($_GET['id']) && isset($_GET['mesa_selected'])){
        $mesa = $_GET['mesa_selected'];
        $user = $_SESSION[$_GET['id'].'user'];
        $tipo = $_SESSION[$_GET['id'].'tipo'];
        $ID = $_GET['id'];
        $foto = $_SESSION[$_GET['id'].'foto_empleado'];
    }else{
        header("Location: /Restaurante_website/404/index.html");
    }

    $cat = isset($_GET['categoria']) ? $_GET['categoria'] : "'='";
    $dishes = $pdo->query("SELECT * FROM platillos WHERE categoria_platillo = '".$cat."' AND status_platillo = 'activo'");

    if(isset($_GET['msg'])){
        $info = array("msg" => "Comanda realizada con exito","color" => "blue");
    }else{
        $info = array("msg" => "","color" => "");
    }

    //verifying if there's already something saved in the table
    $res = $pdo->query("SELECT orden FROM mesas WHERE id=".$mesa);
    $orden = $res->fetch(PDO::FETCH_OBJ);

    if(isset($_GET['cerrar'])){
        $res = $pdo->query("UPDATE mesas SET id_mesero=0, orden='', estatus = 'Abandono' WHERE id = ".$_GET['mesa_selected']);
        if($res){
            header("Location: salon_comedor.php?id=".$_GET['id']);
        }
    }

    if(isset($_GET['cuenta'])){
        //get all the orders in the specific table
        $array_platillos = "";$array_cantidades = "";$array_precios = "";$total = 0;
        $r = $pdo->query("SELECT orden FROM mesas WHERE id = ".$mesa);
        if($row = $r->fetch(PDO::FETCH_OBJ)){
            foreach (preg_split("/;/",$row->orden) as $key => $orden) {
                foreach (preg_split("/,/",$orden) as $key => $item) {
                    if($key == 0){
                        $array_platillos .= $item.",";
                    }else if($key == 1){
                        $array_cantidades .= $item.",";
                    }else{
                        $array_precios .= $item.",";
                        $total = ($total + (int) str_replace("$","",$item));
                    }
                }
            }
        }
        //after getting all the orders clean the row in the DB
        $pdo->query("UPDATE mesas SET id_mesero=0, orden='', estatus = 'Terminada' WHERE id = ".$mesa);
        $dto->__SET('Nombre', substr($array_platillos,0,-2));
        $dto->__SET('Cantidad', substr($array_cantidades,0,-1));
        $dto->__SET('Precio', substr($array_precios,0,-1));
        $dto->__SET('Venta_total',$total);
        $id_venta_platillo = $dao->Registrar($dto);
        header("Location: /Restaurante_website/Ventas/venta.index.php?id=".$_GET['id']."&cuenta=".$id_venta_platillo);
    }
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
    <script src="/Restaurante_website/bootstrap/js/popper.js"></script>
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
    <title>Mesero</title> 
    <style>
        .container-pedido{
            width: 590px;
            height: 240px;
            border-radius: 8px;
            padding-left: 76px;
            padding-right: 10px;
            padding-top: 5px;
            background-image: url('/Restaurante_website/Imagenes/container_pedidos.png');
            background-repeat:none;
            overflow-y: auto;
        }
        th,td{
            text-align:center;
            font-size: 22px;
        }
        .tr{
            border-bottom: 3px solid black;
        }
        .btn-pedido{
            width: 100px;
            height: 30px;
            padding-top: 0px;
        }
        .container-msg{
            color: white;
            background: red;
            margin-top: 80px;
            margin-left: 195px;
            border-radius: 5px;
            width: 300px;
            height: 70px
        }
        .dropdown-item:hover{
            background: #856404;
            color:white;
            transition: .6s
        }
        .background-menu{
            text-align: center;
            width: 300px;
            height: 50px;
            margin-left: 255px;
            background: blue;
            border-radius: 5px;
        }
    </style>
</head>
<body class="body-mesero">
    <div class="header">
        <h1>Mesero <i style="padding-left: 25px">Mesa: <?php echo $mesa;?></i></h1>
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li class="nav-headers" style="border-radius: 5px 0 0 5px">
                <a class="nav-link" href="/Restaurante_website/Venta_platillo/salon_comedor.php?id=<?php echo $_GET['id'];?>">Salon Comedor</a>
            </li>
            <li class="nav-item dropdown nav-headers">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Categorias</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#>">Todo</a>
                    <a class="dropdown-item" href="#">Desayuno</a>
                    <a class="dropdown-item" href="#">Platillo fuerte</a>
                    <a class="dropdown-item" href="#">Postre</a>
                    <a class="dropdown-item" href="#">Bebida</a>
                </div>
            </li>
            <li class="nav-item nav-headers" style="border-radius: 0 5px 5px 0">
                <a class="nav-link" href="#" style="cursor:default">
                    <img class="nav-foto" src="<?php echo $foto?>" width="39px" height="33px" class=".img_empleado">
                </a>
            </li>
        </ul><br>
    </div><br>
    <div id="container_msg" class="container-msg-default"><?php echo $info['msg'];?></div><br>
    <div class="container-dishes-venta-platillo">
        <div class="background-menu">
            <h2>Menu</h2>
        </div>
        <?php
            if($dishes->rowCount() == 0){
                echo "<div class='container-msg'><p>No se encontraron items<br>en la categoria de ".$_GET['categoria']."s</p></div>";
            }
            while($row = $dishes->fetch(PDO::FETCH_OBJ)){
                if($row->foto_platillo != ""){
                    echo "<img onclick='image_selected(".$row->id_platillo.")' class='platillos-dash' src='data:image;base64,".$row->foto_platillo."' id='".$row->id_platillo."'>";
                }else{
                    echo "<img onclick='image_selected(".$row->id_platillo.")' class='platillos-dash' src='/Restaurante_website/Imagenes/userNotFound.png' id='".$row->id_platillo."'>";
                }
                echo "<input type='hidden' value='".$row->nombre_platillo."' id='nombre_".$row->id_platillo."' >";
                echo "<input type='hidden' value='".$row->precio_platillo."' id='precio_".$row->id_platillo."' >";
            }
        ?>
    </div><br>
    <div class="container-comanda">
        <table style="padding-left: 500px">
            <tr>
                <td>
                    <h5 style="display: inline-block;">Nombre del Mesero:</h5>
                </td>
                <td>
                    <label style="display: inline-block; width: 210px;" id="nombre_mesero"><?php echo $user;?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <h5 style="display: inline-block;">Nombre del Platillo:</h5>
                </td>
                <td>
                    <label style="display: inline-block; width: 210px;" id="nombre_platillo">Nombre</label>
                </td>
            </tr>
            <tr>
                <td>
                    <h5 style="display: inline-block;">Precio del Platillo:</h5>
                </td>
                <td>
                    <label style="display: inline-block; width: 210px;" id="precio_platillo">Precio</label>
                </td>
            </tr>
            <tr>
                <td width="240px">
                    <h5 style="display: inline-block;">Cantidad:</h5>
                </td>
                <td>
                    <input oninput="prepareOrder()" style="display: inline-block; padding-left: 10px;" id="cantidad_platillo" placeholder="Cantidad" type="number" min="1" max="100"><br>
                </td>
            </tr>
            <tr>
                <td>
                    <h5 style="display: inline-block;">Total:</h5>
                </td>
                <td>
                    <label style="display: inline-block; width: 210px;" id="total">$0</label>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" id="cerrar_mesa">Terminar Servicio</button>
                </td>
                <td align="center">
                    <button type="button" id="agregar_comanda">Agregar pedido</button>
                </td>
            </tr>
        </table>
    </div><br>
    <div class="container-send-comanda">
        <div class="container-pedido">
            <h3 style="color:black">Pedidos</h3>
            <table id="table_pedidos" cellpadding="15">
                <thead id="table_order">
                    <tr class="tr">
                        <th>Platillos</th>
                        <th>Cantidad</th>             
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div><br>
        <button onclick="printComanda()">Imprimir Comanda</button>
        <button onclick="cuenta()">Cuenta</button>
    </div>
    <!-- Modal in case there's no order in the DB -->
    <div class="modal fade" id="modal_cannot_cuenta">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Imposible ir a cuenta</h4>
                </div>
                <div class="modal-body">
                    <h5>Esta accion no puede ser ejecutada<br>Debido a la falta ordenes de la mesa</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal in case there's no order to print -->
    <div class="modal fade" id="modal_cannot_print">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Imposible Imprimir</h4>
                </div>
                <div class="modal-body">
                    <h5>Esta accion no puede ser ejecutada<br>Debido a la falta de información de la comanda</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal show order's data -->
    <div class="modal fade" id="modal_comanda">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos de la Comanda</h4>
                </div>
                <div class="modal-body modal_body"></div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cerrar</button>
                    <button id="print">Imprimir Ticket</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".dropdown-item").click(function(){
            $('.container-dishes-venta-platillo').slideToggle(500)
            var categoria = $(this).text() === 'Todo' ? "'='" : $(this).text()
            $.ajax({
                type: "GET",
                url: "venta.dao.php?categoria="+categoria,
                success: function(data){
                    var json = eval(data)
                    if(json[0].msg == ""){
                        var content = `<div class='background-menu'><h2>${json[0].titulo}</h2></div>`
                        for (let i = 0; i < json.length; i++) {
                            if(json[i].image != ""){
                                content += `<img onclick='image_selected(${json[i].id})' class='platillos-dash' src='data:image;base64,${json[i].image}' id='${json[i].id}'>`
                            }else{
                                content += `<img onclick='image_selected(${json[i].id})' class='platillos-dash' src='/Restaurante_website/Imagenes/userNotFound.png' id='${json[i].id}'>`
                            }
                            content += `<input type='hidden' value='${json[i].nombre}' id='nombre_${json[i].id}'>`
                            content += `<input type='hidden' value='${json[i].precio}' id='precio_${json[i].id}'>`
                        }
                        $('.container-dishes-venta-platillo').empty()
                        $('.container-dishes-venta-platillo').html(content)
                        $('.container-dishes-venta-platillo').slideToggle(500)
                    }
                }
            })
        })

        var mesa = 0
        window.onload = function(){
            mesa = '<?php echo $mesa?>';
            //display the message from the backend in a model otherwise its hidden
            if("<?php echo isset($info['msg'])? $info['msg'] : ''?>" == ""){
                    $("#container_msg").hide()
            }else if("<?php echo isset($info['msg'])?  $info['color'] : ''?>" == "blue"){
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
            history.replaceState({}, document.title, "/Restaurante_website/Venta_Platillo/mesero.php?id=<?php echo $_GET['id']?>&mesa_selected="+mesa)
        }

        var size_ordered = 0
        $("#agregar_comanda").click(function(){
            var platillo = $("#nombre_platillo").text(), precio =
                $("#total").text(), cantidad = $("#cantidad_platillo").val(), precio_platillo = $("#precio_platillo").text()
                pedido = ""
            if(platillo !== "Nombre"){
                if(!isRepeated(platillo)){
                    $("#table_order").after(`<tr class='tr' id='row_${size_ordered}'>
                                                <td id='nom_${size_ordered}'>${platillo}</td>
                                                <td id='cant_${size_ordered}'>${cantidad}</td>
                                                <td id='price_${size_ordered}'>${precio}</td>
                                                <td><button onClick='Aumentar(${size_ordered},${precio_platillo})' id='sum_${size_ordered}' class='btn-pedido'>Aumentar</button>
                                                    <button onClick='Quitar(${size_ordered})' id='quit_${size_ordered}' class='btn-pedido btn-remove-hover'>Quitar</button>
                                                </td></tr>`)
                    $("#nombre_platillo").text("Nombre")
                    $("#cantidad_platillo").val("")
                    $("#total").text("$0")
                    $("#precio_platillo").text("Precio")
                    size_ordered++
                }
            }
        })

        isRepeated = function(dish) {
            if(size_ordered > 0){
                for (let i = 0; i < size_ordered; i++) {
                    if($("#nom_"+i).text() === dish){
                        return true
                    }
                }
            }
            return false
        }

        function Aumentar(id,precio_platillo){
            var cantidad = parseInt($('#cant_'+id).text()) + 1
            var precio = precio_platillo * cantidad
            $('#cant_'+id).text(cantidad)
            $('#price_'+id).text("$"+precio)
        }

        function Quitar(id){
            $("#row_"+id).fadeToggle(400)    
            setTimeout(() => {
                $("#row_"+id).remove()    
            }, 400)
        }
 
        function printComanda() {
            var orderFinal = [], size_orderFinal = 0
            //getting the orders from the table order
            var rows = $("#table_pedidos").children()
            if(rows.length > 1){
                for(let i = 1; i < rows.length; i++){
                    var id = rows[i].id.replace("row_","")
                    orderFinal[size_orderFinal++] = [
                        $("#nom_"+id).text(), $("#cant_"+id).text(), $("#price_"+id).text()
                    ]
                }
                //save the orders to mesas table
                var orden = ""
                orderFinal.forEach(element => orden += element+";");
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head><title>Imprimir Comanda</title>');
                mywindow.document.write('</head><body >');
                mywindow.document.write('<h1>Comanda</h1><h2>Mesa '+mesa+'</h2>');
                var comanda = `<br><table><thead><tr class='tr' style='padding-left: 10px'><th>Platillos</th><th>Cantidad</th></tr></thead>`
                orderFinal.map(row => {
                    comanda += `<tr class='tr' style='padding-left: 10px'>
                                    <td style='text-align: center'>${row[0]}</td>
                                    <td style='text-align: center'>${row[1]}</td>
                                </tr>`
                })
                comanda += "</table>"
                $(".modal_body").html(comanda)
                mywindow.document.write($(".modal_body").html());
                mywindow.document.write('</body></html>');
                mywindow.print();
                mywindow.close();
                $.ajax({
                    type: "GET",
                    url: "venta.dao.php",
                    data: `orden=${orden}&mesa=${mesa}`,
                    success: function(data){
                        json = eval(data)
                        if(json){
                            location = "mesero.php?id=<?php echo $_GET['id']?>&mesa_selected="+mesa+"&msg=1"
                        }
                    }
                })
            }else{
                $('#modal_cannot_print').modal('show')
            }
        }

        function image_selected(id){
            var id_platillo = id
            var nom_platillo = $("#nombre_"+id_platillo).val()
            var precio_platillo = $("#precio_"+id_platillo).val()
            $("#nombre_platillo").text(nom_platillo)
            $("#precio_platillo").text(parseInt(precio_platillo))
            $("#cantidad_platillo").val(1)
            $("#total").text("$"+precio_platillo)
        }

        $("#cerrar_mesa").click(() => location = "mesero.php?id=<?php echo $_GET['id']?>&cerrar=1&mesa_selected=<?php echo $mesa;?>")

        function prepareOrder(){
            var cantidad = $("#cantidad_platillo").val()
            var precio = $("#precio_platillo").text()
            if(cantidad != null && precio != 'Precio'){
                var total = (cantidad*precio)
                $("#total").text("$"+total)
            }
        }

        function cuenta(){
            const orden = "<?php echo $orden->orden?>"
            if(orden === ""){
                $("#modal_cannot_cuenta").modal('show')
            }else{
                location = "mesero.php?id=<?php echo $_GET['id']?>&mesa_selected=<?php echo $mesa;?>&cuenta=1";
            }
        }
    </script>
</body>
</html>
