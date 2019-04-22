<?php
  header("refresh:6;url=/Restaurante_website/index.php");
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>404</title>
  <link rel="stylesheet" href="/Restaurante_website/bootstrap/css/style.css">
  <link rel="stylesheet" href="/Restaurante_website/404/css/style.css">
  <link rel="shortcut icon" href="/Restaurante_website/Imagenes/logo_frame.png"/>
</head>
<body>
  <p class="mega">4<span class="boom"></span>4
    <div class="bola"></div>
  </p>
  <p class="mini">No encontramos la pagina que buscas</p>
  <p class="mini2">Seras redireccionado en <i id="segundos"></i> segundos</p>
  <p class="mini3">Sino da click en el boton de abajo</p>
  <p class="mini home">
    <button onclick="location = '/Restaurante_website/index.php'">
      Pagina Principal</button>
  </p>
  <script src='/Restaurante_website/bootstrap/js/jquery.min.js'></script>
  <script src='/Restaurante_website/bootstrap/js/three.min.js'></script>
  <script src="/Restaurante_website/404/js/index.js"></script>
  <script src="/Restaurante_website/404/js/intervalo.js"></script>
</body>
</html>
