<?php
    require_once "Platillos/platillo.dao.php";
    require_once "pdo.php";

    $pdo = new PDO_connection();
    $platillos = new PlatilloDAO($pdo);
    $array = $platillos->Listar("");

    session_start();
    $tipo = $_SESSION[$_GET['id'].'tipo'] or header("Location: /Restaurante_website/404/index.html");;
    $ID = $_GET['id'];
    $foto = $_SESSION[$ID.'foto_empleado'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Imagenes/logo_frame.png" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css/style.css" rel="stylesheet">
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <title>Dashboard</title>
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
            .container-dishes{
                margin-left: 0;
                width: 100%;
                height: 300px;
                overflow: scroll;
                border-radius: 15px 15px 0 0;
            }
            .container-session,.container-contact{
                width: 100%;
                margin: 0;
                background-repeat: no-repeat;
            }
            .container-contact{
                margin-top: 9%
            }
            .social-media{
                width: 14%
            }
        }
        .custom-modal{
            background: rgba(247, 178, 101, 0.972);
        }
    </style>
</head>

<body class="body-dashboard">
    <div class="header">
        <h1>Dashboard</h1>
        <!-- Nav -->
        <ul class="nav justify-content-center">
            <li id="empleados" class="nav-item nav-headers" style="border-radius: 5px 0 0 5px">
                <a class="nav-link" href="/Restaurante_website/Empleados/empleado.index.php?id=<?php echo $ID;?>">Empleados</a>
            </li>
            <li id="clientes" class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Clientes/cliente.index.php?id=<?php echo $ID;?>">Clientes</a>
            </li>
            <li id="ventas" class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Ventas/venta.index.php?id=<?php echo $ID;?>">Ventas</a>
            </li>
            <li id="platillos" class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Platillos/platillo.index.php?id=<?php echo $ID;?>">Platillos</a>
            </li>
            <li id="mesas" class="nav-item nav-headers">
                <a class="nav-link" href="/Restaurante_website/Mesas/mesa.index.php?id=<?php echo $ID;?>">Mesas</a>
            </li>
            <li class="nav-item nav-headers nav-close-session" style="border-radius: 0 5px 5px 0">
                <a class="nav-link" data-toggle="modal" data-target="#modal_cerrar_sesion" href="#">Cerrar Sesion</a>
            </li>
        </ul><br>
    </div><br>
    <div class="container-dishes">
        <h2 align="center">Platillos</h2>
        <?php
            $stm = $pdo->prepare("SELECT * FROM `platillos` WHERE status_platillo = 'activo' ORDER BY id_platillo LIMIT 6");
            $stm->execute();
            while($r = $stm->fetch(PDO::FETCH_OBJ)){
                echo "<img data-toggle='collapse' data-target='#collapseExample' class='platillos-dash' src='data:image;base64,".$r->foto_platillo."' id='".$r->id_platillo."'>";
            }
        ?>
        <div class="collapse" id="collapseExample">
            <?php foreach($array as $e): ?>
            <h4 hidden align="center" id="nom-<?php echo $e->__GET("id");?>">
                <?php echo strtoupper($e->__GET("Nombre"));?>
            </h4>
            <?php endforeach;?>

            <?php foreach($array as $e): ?>
            <p hidden align="center" id="ingre-<?php echo $e->__GET("id");?>">
                <?php echo $e->__GET("Ingredientes");?>
            </p>
            <?php endforeach;?>
        </div>
    </div><br>
    <div class="container-session">
        <h4 style="color: black;text-align: center">Sesion iniciada: <strong><i>
                    <?php echo "<br>".$_SESSION[$ID.'user'];?></i></strong></h4>
        <a style='padding-left: 25%;' class='foto-user'><img src='<?php echo $foto?>' id='user' class='foto-dash'></a>
    </div>
    <div class="container-contact">
        <h3 style="padding-left: 130px;font-weight: 700">Medios de contacto</h3>
        <p style="padding-left: 15px;font-weight: 500">Para mas informacion llame al: <i><strong>3319804795</strong></i></p>
        <p style="padding-left: 15px;font-weight: 500">Siguenos en nuestras redes sociales</p>
        <a style="padding-left: 30px;" href="https://www.facebook.com" target="_blank"><img src="Imagenes/facebook_icon.png"
                class="social-media" /></a>
        <a style="padding-left: 30px;" href="https://www.youtube.com" target="_blank"><img src="Imagenes/youtube_icon.png"
                class="social-media" /></a>
        <a style="padding-left: 30px;" href="https://www.twitter.com" target="_blank"><img src="Imagenes/twitter_icon.png"
                class="social-media" /></a>
        <a style="padding-left: 30px;" href="https://www.instagram.com" target="_blank"><img src="Imagenes/instagram_icon.png"
                class="social-media" /></a>
    </div>
    <!-- Modal close session -->
    <div class="modal fade" id="modal_cerrar_sesion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="title-modal" class="modal-title">Cerrar sesion</h4>
                </div>
                <div class="modal-body">
                    <h5 id="body">¿Estas seguro de cerrar la sesion actual?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="cerrar_sesion" class="btn-remove-hover">Cerrar sesion</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function () {
            const tipo = "<?php echo $tipo;?>"
            if (tipo === "Administrador Ventas") {
                $("#empleados").hide();
                $("#clientes").hide();
                $("#platillos").hide();
                $("#mesas").hide();
                $("#ventas").attr("style", "border-radius: 5px 0 0 5px");
            }
        }

        var anterior_ing = "",
            anterior_nom = ""
        $("img").on("click", function () {
            if (anterior_ing != '') {
                $("#" + anterior_ing).hide()
                $("#" + anterior_nom).hide()
            }
            var id = $(this).attr("id")
            $("#ingre-" + id).removeAttr("hidden")
            $("#nom-" + id).removeAttr("hidden")
            $("#ingre-" + id).show()
            $("#nom-" + id).show()
            anterior_ing = "ingre-" + id
            anterior_nom = "nom-" + id
        })
        
        $("#cerrar_sesion").click(() => location = "index.php?id_cerrar=<?php echo $ID?>")
    </script>
    <!--library for the modal's working-->
    <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>