<?php
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();

    require "cliente.dao.php";
    require_once dirname(__DIR__).'/pdo.php';

    $pdo = new PDO_connection();
    $dao = new ClienteDAO($pdo);
    $condition = "";
    $info_filtro = "";
    if(isset($_GET['value'])){
        if($_GET['value'] != ""){
            $condition = " where ".$_GET['column']." like '".$_GET['value']."%'";
            $info_filtro = "Filtrado por ".preg_split('/_/',$_GET['column'])[0];
        }
        
    }
    $clientes = $dao->Listar($condition);
    
?>
<style>
    .title{
        width: 110%;
        height: 65px;
        padding-left: 10px;
        background: orange;
        border-radius: 5px;
        text-align:center
    }
    .cellHeader{
        padding-left:5px;
        padding-right:2px;
        font-size: 18px;
        text-align: center;
        background: brown;
        color:white
    }
    .cell{
        font-size: 15.5px;
        height: 25px;
        text-align: center
    }
    img{
        margin-bottom: 5px
    }
</style>
<page backtop="1mm" backbottom="5mm" backleft="0mm" backright="20mm">
 <div class="title">
    <h1>Clientes</h1>
    <img src="logo_frame.png" width="100" height="100">
    <h3><?php echo $info_filtro?></h3>
 </div><br>
 
  <table border="1">
      <thead>
          <tr>
              <th class="cellHeader">ID</th>
              <th class="cellHeader">Nombre</th>
              <th class="cellHeader">Apellido paterno</th>
              <th class="cellHeader">Apellido materno</th>
              <th class="cellHeader">Correo</th>
              <th class="cellHeader">RFC</th><br>
          </tr>
      </thead>
      <?php 
        foreach ($clientes as $clt) {
            echo "<tr>
                <td class='cell'>".$clt->__GET('id')."</td>
                <td class='cell'>".$clt->__GET('Nombre')."</td>
                <td class='cell'>".$clt->__GET('Apellido_paterno')."</td>
                <td class='cell'>".$clt->__GET('Apellido_materno')."</td>
                <td class='cell'>".$clt->__GET('Correo')."</td>
                <td class='cell'>".$clt->__GET('RFC')."</td>
            </tr>";
        }
      ?>
  </table>
</page>

<?php
  $content = ob_get_clean();
  require_once(dirname(__FILE__).'\..\vendor\autoload.php');
  
  try{
      $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 3);
      $html2pdf->pdf->SetDisplayMode('fullpage');
      $html2pdf->writeHTML($content);
      $html2pdf->Output('Clientes.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }
?>