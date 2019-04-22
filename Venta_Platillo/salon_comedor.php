<?php
    require_once dirname(__DIR__).'/pdo.php';

    //if we dont get a logged user send them to a 404 page
    session_start();
    if(!isset($_GET['id'])){
        header("Location: /Restaurante_website/404/index.html");
    }

    $foto = $_SESSION[$_GET['id'].'foto_empleado'];

    if(isset($_GET['msg'])){
        $info = array("msg" => $_GET['msg'],"color" => $_GET['color']);
    }else{
        $info = array("msg" => "","color" => "");
    }

    $pdo = new PDO_connection();

    if(isset($_GET['mesa_selected'])){
        if(!isset($_GET['yellow'])){ //if the table is yellow then dont modify anything in the DB
            $pdo->query("UPDATE mesas SET id_mesero = ".$_GET['id'].", mesero = '".$_SESSION[$_GET['id'].'user']."', 
                estatus = 'Atendiendo' WHERE id=".$_GET['mesa_selected']);
        }
        header("Location: mesero.php?id=".$_GET['id']."&mesa_selected=".$_GET['mesa_selected']);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="/Restaurante_website/Imagenes/logo_frame.png"/>
    <link href="/Restaurante_website/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Restaurante_website/bootstrap/css/style.css" rel="stylesheet">
    <script src="/Restaurante_website/bootstrap/js/jquery.min.js"></script>
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
    <title>Salon Comedor</title>
    <style>
        .simbologia{
            width:670px;
            text-align: center;
            margin-right:auto;
            margin-left:auto;
            height: 110px;
            background-image: url('/Restaurante_website/Imagenes/simbologia.png');
            opacity: 0.9;
            border-radius: 15px 15px 15px 15px;
            overflow-y: auto;
            align-self: center;
        }
    </style>
</head>
<body class="body-salon-comedor">
    <div class="header">
        <h1>Salon Comedor</h1>
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li class="nav-item nav-headers nav-close-session" style="border-radius: 5px 0 0 5px">
                <a class="nav-link" href="#" id="cerrar_sesion">Cerrar Sesion</a>
            </li>
            <li class="nav-item nav-headers" style="border-radius: 0 5px 5px 0">
                <a class="nav-link" href="#" style="cursor:default">
                    <img class="nav-foto" src="<?php echo $foto?>" width="39px" height="33px" class=".img_empleado">
                </a>
            </li>
        </ul><br>
    </div><br>
    <div id="container_msg" class="container-msg-default"><?php echo $info['msg'];?></div><br>
    <div class="simbologia">
        <h3 align="center" style="color:black">Simbologia</h3>
        <h4 style="display: inline-block;color:black">Disponible</h4>
        <img style="display: inline-block;border-radius: 7px 7px 7px 7px; border: 2px solid black;" src="/Restaurante_website/Imagenes/green_table.png" width="50px" heigth="50px">  
        <h4 style="color:black;display: inline-block;">Atendiendo</h4>
        <img style="display: inline-block;border-radius: 7px 7px 7px 7px; border: 2px solid black;" src="/Restaurante_website/Imagenes/tabla_ocupada.png" width="50px" heigth="50px">  
        <h4 style="color:black;display: inline-block;">Ocupada</h4>
        <img style="display: inline-block;border-radius: 7px 7px 7px 7px; border: 2px solid black;" src="/Restaurante_website/Imagenes/red_table.png" width="50px" heigth="50px">  
    </div><br>
    <div id="container_platillos" class="container-tables">
        <?php
            $res = $pdo->query("SELECT * FROM mesas");
            while($row = $res->fetch(PDO::FETCH_OBJ)){
                if($row->mesero == ""){
                    echo "<img class='mesas' src='/Restaurante_website/Imagenes/Green_table.png' id='mesa_".$row->id."'>";
                    continue;
                }else if($row->mesero == $_SESSION[$_GET['id'].'user']){
                    echo "<img class='mesas' src='/Restaurante_website/Imagenes/tabla_ocupada.png' id='mesa_".$row->id."'>";
                    continue;
                }
                echo "<img class='mesas' src='/Restaurante_website/Imagenes/red_table.png' id='mesa_".$row->id."'>";
            }
        ?>
    </div><br>
    <!-- Modal -->
    <div class="modal fade" id="modal-warning">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Mesa Ocupada</h4>
              </div>
              <div class="modal-body">
                  <h5>Esta mesa ya esta siendo atendida por otro<br>mesero</h5>
              </div>
              <div class="modal-footer">
                  <button data-dismiss="modal">Cerrar</button>
              </div>
            </div>
        </div>
    </div> 
    <!-- Modal close the session -->
    <div class="modal fade" id="modal_close_session">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cerrar Sesion</h4>
                </div>
                <div class="modal-body">
                    <h5>¿Estas seguro de cerrar la sesion actual?</h5>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal">Cerrar</button>
                    <button onclick="location = '/Restaurante_website/index.php?id_cerrar=<?php echo $_GET['id']?>'">Aceptar</button>
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
                }, 3900);
            }
            history.replaceState({}, document.title, "/Restaurante_website/Venta_Platillo/salon_comedor.php?id=<?php echo $_GET['id'];?>")
        }
        $("img.mesas").mouseover(function(){
            var img = $(this).attr("src")
            if(img != "/Restaurante_website/Imagenes/red_table.png" && img != "/Restaurante_website/Imagenes/tabla_ocupada.png"){
                $(this).attr("src","/Restaurante_website/Imagenes/tabla_seleccionada.png")
            }
        })

        $('img.mesas').mouseleave(function(){
            var img = $(this).attr("src")
            if(img != "/Restaurante_website/Imagenes/red_table.png" && img != "/Restaurante_website/Imagenes/tabla_ocupada.png"){
                $(this).attr("src","/Restaurante_website/Imagenes/Green_table.png")
            }
        })

        $("img.mesas").click(function(){
            var img = $(this).attr("src")
            if(img != "/Restaurante_website/Imagenes/red_table.png"){
                var id = $(this).attr("id").replace("mesa_","")
                var yellow_table = ""
                if(img == "/Restaurante_website/Imagenes/tabla_ocupada.png"){
                    yellow_table = "&yellow=1"
                }
                location = "salon_comedor.php?id=<?php echo $_GET['id']?>&mesa_selected="+id+""+yellow_table
            }else{
                $("#modal-warning").modal('show')
            } 
        })

        $("#cerrar_sesion").click(() => $("#modal_close_session").modal('show'))
    </script>
    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>