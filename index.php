<?php 
/*FIXME: once on production, make sure to change all urls, src, href from the project
  getting rid of '/Restaurante_website/'*/
require_once "pdo.php";
session_start();
if(isset($_GET['log'])){
    $pdo = new PDO_connection();
    $stm = $pdo->prepare("SELECT * FROM `empleados` WHERE id_empleado=?");
    $stm->execute(array($_GET['id_user']));
    if($r = $stm->fetch(PDO::FETCH_OBJ)){
        $foto = empty($r->foto_empleado) ? '/Restaurante_website/Imagenes/userNotFound.png' : 'data:image;base64,'.$r->foto_empleado; 
        $_SESSION[$r->id_empleado.'foto_empleado'] = $foto;
        $_SESSION[$r->id_empleado.'tipo'] = $r->tipo_empleado;
        $_SESSION[$r->id_empleado.'user'] = $r->nombre_usuario;
    }
    $location = $_SESSION[$_GET['id_user']."tipo"] == 'Mesero' ? 'Venta_platillo/salon_comedor.php' : 'principal.php';
    header('Location: '.$location.'?id='.$_GET['id_user']);
}

if(isset($_GET['id_cerrar'])){
    unset($_SESSION[$_GET['id_cerrar'].'user']);
    unset($_SESSION[$_GET['id_cerrar'].'tipo']);
    unset($_SESSION[$_GET['id_cerrar'].'foto_empleado']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='Imagenes/logo_frame.png' id="icon" rel="shortcut icon" />
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/Restaurante_website/bootstrap/css/style.css" rel="stylesheet">
  <script src="bootstrap/js/jquery.min.js"></script>
  <title>Restaurante Log In</title>
  <style>
    .container{
      margin-top: 65px;
      opacity:.9;
      background-image: url('/Restaurante_website/Imagenes/container_logo.png');
      box-shadow: 0px 0px 30px 25px orange;
      float: left;
      margin: 4%;
      margin-left: 7.5%;
    }

    .msg-require{
      width: 225px;
      height: 37px;
      color: white;
      background: rgb(198,16,16);
      border-radius: 12px;
      border: 2px solid white;
      font-size: 22px;
      margin-top: 7%;
      padding-left: 5px;
    }

    .form{
      float:left;
      margin-right: 23.5%
    }

    /*styles in phones*/
    @media only screen and (max-width:600px){
      body{
        background: brown;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif
      }
      .container{
        width: 100%;
        padding-top: 0;
        padding-left: 8%;
        margin:1%
      }
      img{
        width: 100%;
        margin-top: 5%;
        border-radius: 15px;
      }
    }
    /*styles for phones in landscape*/
    @media only screen and (orientation: landscape) and (max-width:600px){
      .container{
        width: 100%;
        padding-top: 0;
        padding-left: 25%;
        margin:1%
      }
      img{
        width: 70%;
        margin-top: 5%;
        border-radius: 15px;
      }
    }

    /*styles for desktop*/
    @media only screen and (min-width:600px){
      .msg-require{
        float: right;
        margin-top: 0%;
        width: 150px;
      }
    }
  </style>
</head>

<body class="body-logIn"><br>
  <div class="container">
    <div class="form">
      <form>
        <h1 class="mt-5">Restaurante</h1>
        <p>Usuario</p>
        <input required type="text" id="user" placeholder="Usuario / Correo">
        <div class="msg-require" id="msg-user" hidden>Campo vacio</div>
        <p>Contraseña</p>
        <input required type="password" id="pwd" placeholder="Contraseña">
        <div class="msg-require" id="msg-pwd" hidden>Campo vacio</div>
        <br><br>
        <button id="log_in" type="button">Iniciar Sesion</button>
      </form>
    </div>
    <img src="Imagenes/logo_frame.png" width='380' height='375'/>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="modal_msg">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="title-modal" class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <h5 id="body"></h5>
        </div>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $("#log_in").click(function() {
      if(validateAccess()) {
        gettingAccess()
      }
    })

    function validateAccess() {
      var component = ""
      document.getElementById('user').innerHTML
      if ($("#user").val() == '' && $("#pwd").val() == '') {
        component = ".msg-require"
      } else if ($("#user").val() == '') {
        component = "#msg-user"
      } else if ($("#pwd").val() == '') {
        component = "#msg-pwd"
      }
      if(component !== "") {
        $('.form').attr('style','margin-right: 8.9%')
        $(component).removeAttr("hidden")
        $(component).hide()
        $(component).slideToggle(1000)
        setTimeout(() => {
          $(component).slideToggle(1500)
          setTimeout(() => {
            $('.form').attr('style','margin-right: 23.5%')  
          }, 1500);
        }, 2600); 
        return false
      }
      return true
    }

    gettingAccess = function () {
      $.ajax({
        type: "GET",
        url: "SesionDAO.php",
        data: "username=" + $("#user").val() + "&password=" + $("#pwd").val(),
        success: function(json) {
          if (json[0].id != null) {
            if (json[0].status == "Desactivado") {
              $("#title-modal").html("Empleado Dado de baja")
              $("#body").html(`El empleado se encuentra dado de baja<br>no puede iniciar sesion`)
              $("#modal_msg").modal('show')
            } else {
              location = "index.php?log=1&id_user=" + json[0].id
            }
          }else{
            $("#title-modal").html("Empleado no reconocido")
            $("#body").html(json[0].msg)
            $("#modal_msg").modal('show')
            $("#pwd").val("")
          }
        }
      })
    }
  </script>
  <!--library for the modal's working-->
  <script src="/Restaurante_website/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>