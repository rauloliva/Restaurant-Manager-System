<?php
require_once 'mesa.dto.php';
require_once 'mesa.dao.php';
require_once dirname(__DIR__).'/pdo.php';

$pdo = new PDO_connection();
$alm = new Mesa();
$model = new MesaDAO($pdo);

$id_row = 1; //enum the rows in the table
$info = array("msg" => isset($_GET['msg']) ? $_GET['msg'] : '' ,"color" => isset($_GET['color']) ? $_GET['color'] : '');

//get the lastest number of tables
$stm = $pdo->prepare("SELECT * from mesas");
$stm->execute();
$num_mesas_actuales = $stm->rowCount();

//submit action
if(isset($_REQUEST['action'])){
    $num_mesas = $_REQUEST['n_mesas'];
    $info = $model->Action($num_mesas,$num_mesas_actuales);
    //update the number of tables
    $num_mesas_actuales = $num_mesas;
}

session_start();
$tipo = $_SESSION[$_GET['id'].'tipo'] or header("Location: /Restaurante_website/404/index.html");
$foto = $_SESSION[$_GET['id'].'foto_empleado'];

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <title>Mesas</title>
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
        th{
            color: black;
        }
        .th_lista{
            padding-left:46.5px;
            padding-right: 27px
        }
        td{
            padding-left:45px;
            font-size: 22px
        }
        .container_form{
            padding:30px;
            overflow-x:auto;
        }
        h3,h4,h5{
            color:black
        }
        .icon-trash{
            font-family: "Font Awesome 5 Free";
        }
    </style>
</head>

<body class="body-mesas">
    <div class="header">
        <h1>Mesas</h1>
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
                <a class="nav-link" href="/Restaurante_website/Platillos/platillo.index.php?id=<?php echo $_GET['id'];?>">Platillos</a>
            </li>
            <li class="nav-item nav-headers">
                <a class="nav-link" id="pdf" href="mesasPDF.php">
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
    <div class="container_table_small_mini" style="overflow-x:auto;float:left;width:40%;margin-left:80px;height:400px">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;" class="th_lista th-border-left">Mesero</th>
                    <th style="text-align:center;" class="th_lista">Mesa</th>
                    <th style="text-align:center;" class="th_lista">Estatus</th>
                    <th style="text-align:center;" class="th_lista th-border-right">Accion</th>
                </tr>
            </thead>
            <?php 
                foreach ($arrayListar as $mesa) {
                    $id = $mesa->__GET('mesa');
                    echo "<tr class='row_selected' id='".$id_row++."'>
                            <td>".$mesa->__GET('mesero')."</td>
                            <td>".$mesa->__GET('mesa')."</td>
                            <td id='estatus_".$id."'>".$mesa->__GET('estatus')."</td>
                            <td><input type='button' onclick='' value='&#xf2ed;' class='icon-trash' id='".$id."'></td>
                        </tr>";
                }
                $id_row--;
            ?>
        </table>
    </div>
    <div class="container_form" style="float:left;width:45%;margin-left:100px;background:orange;height:400px">
        <h3 align="center">Numero de mesas</h3>
        <h5 id="numero_mesas">Numero de mesas actuales: <?php echo "<span style='color:green;font-weight:bold;font-size:24px'>".$num_mesas_actuales."</span>"?></h5>
        <form action="?id=<?php echo $_GET['id']?>&action=1" method="post">
            <h5>Actulizar Numero de mesa: <input type="number" name="n_mesas" id="n_mesas" min="1" max="100"> </h5>
            <button id="btn_submit" type="submit">Guardar</button>
        </form>
        <h4>Filtros</h4>
        <table align="center" style="width:500px">
            <tr>
                <th>Nombre Mesero:</th>
                <td><input type="text" style="width: 100%;" name="search_mesero" id="search_mesero"></td>
            </tr>
            <tr>
                <th>Mesa:</th>
                <td><input type="number" style="width: 100%;" name="search_mesa" id="search_mesa"></td>
            </tr>
            <tr>
                <th>Estatus:</th>
                <td>
                    <select style="width:100%" id="search_estatus">
                        <option id="cat_0" value="seleccionar">Seleccionar</option>
                        <option id="cat_1" value="atendiendo">Atendiendo</option>
                        <option id="cat_2" value="terminada">Terminada</option>
                        <option id="cat_3" value="abandono">Abandono</option>
                    </select>
                </td>
        </table>
    </div>
    <!-- Modal: the input of tables is the same as the number of tables in DB-->
    <div class="modal fade" id="modal_same_number">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Accion Invalida</h4>
                </div>
                <div class="modal-body">
                    <h5>El numero de mesas que deseas<br>es el mismo numero de mesas actualmente</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: if the field is empty-->
    <div class="modal fade" id="modal_field_empty">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Campo vacio</h4>
                </div>
                <div class="modal-body">
                    <h5>El numero de mesas no se puede actualizar</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal: if the field is empty-->
    <div class="modal fade" id="modal_no_remove">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Accion no permitida</h4>
                </div>
                <div class="modal-body">
                    <h5>Esta mesa esta siendo atendida</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var num_mesas_actuales
        window.onload = function () {
            num_mesas_actuales = "<?php echo $num_mesas_actuales?>"
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
                "/Restaurante_website/Mesas/mesa.index.php?id=<?php echo $_GET['id'];?>")
        }

        $("#btn_submit").click(function(event){
            var num = $("#n_mesas").val()
            if(num == ''){
                $("#modal_field_empty").modal('show')
                event.preventDefault()
            }
        })
        
        $("input.icon-trash").click(function(){
            var mesa = $(this).attr('id')
            ActionTrash(mesa,mesa)
        })

        function eliminar_row(mesa,id){
            ActionTrash(mesa,id)
        }

        function ActionTrash(mesa,id){
            estatus = $("#estatus_"+id).text()
            if(estatus !== "Atendiendo"){
                $.ajax({
                    type: "GET",
                    url: "mesa.dao.php",
                    data: `mesa=${mesa}`,
                    success: function(json){
                        var data = eval(json)
                        if(data){
                            $("#"+id).remove()
                            num_mesas_actuales = parseInt("<?php echo ((int)$num_mesas_actuales) - 1?>")
                            $("h5#numero_mesas").html(`Numero de mesas actuales:
                                <span style='color:green;font-weight:bold;font-size:24px'>${num_mesas_actuales}</span>`)
                        }
                    }
                })
            }else{
                $("#modal_no_remove").modal('show')
            }
        }

        $("button").click(function(event){
            var num_actual = parseInt(num_mesas_actuales)
            var num_mesas = parseInt($("#n_mesas").val())
            if(num_actual === num_mesas){
                $("#modal_same_number").modal('show')
                event.preventDefault()
            }
        })

        //list records
        $('#search_mesero').keyup(function () {
            var value = $(this).val()
            listar_ajax(value, "mesero")
        })

        $('#search_mesa').keyup(function () {
            var value = $(this).val()
            listar_ajax(value, "id")
        })

        $('#search_estatus').change(function(){
            var value = ""
            const options = $(this).children()
            for (let i = 0; i < options.length; i++) {
                if($("#"+options[i].id).is(":selected")) {
                    value = options[i].value;
                }   
            }
            listar_ajax(value,"Estatus")
        })

        let f_lap = true,id_rows = 0
        
        function listar_ajax(value, column) {
            $("a#pdf").attr("href","mesasPDF.php?value="+value+"&column="+column)
            /*
                first keyup is controled by php variable
                form second keyup and forward is controled by javascript variable
            */
            id_rows = f_lap ? "<?php echo $id_row;?>" : id_rows
            f_lap = false
            $.ajax({
                type: "GET",
                url: "mesa.dao.php",
                data: "list=" + value + "&column=" + column,
                success: function (data) {
                    var json = eval(data)
                    //remove all the records in the table
                    for (index = 0; index <= id_rows; index++) {
                        $("#" + index).remove()
                    }
                    if (json && json[0].msg == "") {
                        for (let i = 0; i < json.length; i++) {
                            $("thead").append(
                                `<tr class='row_selected' id="${(++id_rows)}">
                                <td>${json[i].mesero}</td>
                                <td>${json[i].mesa}</td>
                                <td>${json[i].estatus}</td>
                                <td><input type='button' value='&#xf2ed;' class='icon-trash' onclick='eliminar_row(${json[i].mesa},${id_rows})'></td>
                            </tr>`
                            )
                        }
                    } else {
                        var str = ""
                        for (let i = 0; i <= 3; i++) {
                            str += "<td class='td_lista'>" + json[0].msg + "</td>"
                        }
                        $("thead").append(`<tr class='row_selected' id=${(++id_rows)}>${str}</tr>`)
                    }
                }
            })
        }
    </script>

    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>