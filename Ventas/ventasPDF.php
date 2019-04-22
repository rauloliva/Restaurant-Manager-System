<?php
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();

    require "venta.dao.php";
    require_once dirname(__DIR__).'/pdo.php';

    $pdo = new PDO_connection();
    $dao = new VentaDAO($pdo);
    $condition = "";
    $info_filtro = "";
    if(isset($_GET['val'])){
        if($_GET['val'] != ""){
            $condition = " WHERE ".$_GET['col']."".$_GET['val'];
            $info_filtro = "Filtrado por ".preg_split('/_/',$_GET['col'])[0];
        }
        
    }
    $ventas = $dao->Listar($condition);
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
        padding-left:15px;
        padding-right:15px;
        font-size: 20px;
        text-align: center;
        background: brown;
        color:white
    }
    .cell{
        font-size: 17px;
        height: 25px;
        padding-left:15px;
        padding-right:15px;
        text-align: center
    }
    table{
        margin-left: 9.5%
    }
    img{
        margin-bottom: 5px
    }
</style>
<page backtop="1mm" backbottom="5mm" backleft="0mm" backright="20mm">
 <div class="title">
    <h1>Ventas</h1>
    <img src="logo_frame.png" width="100" height="100">
    <h3><?php echo $info_filtro?></h3>
 </div><br>
 
  <table border="1">
      <thead>
          <tr>
              <th class="cellHeader">ID</th>
              <th class="cellHeader">Total</th>
              <th class="cellHeader">Hora</th>
              <th class="cellHeader">Fecha</th>
              <th class="cellHeader">ID Cliente</th>
              <th class="cellHeader">ID Empleado</th><br>
          </tr>
      </thead>
      <?php 
        foreach ($ventas as $vnt) {
            echo "<tr>
                <td class='cell'>".$vnt->__GET('id')."</td>
                <td class='cell'>".$vnt->__GET('Total')."</td>
                <td class='cell'>".$vnt->__GET('Hora')."</td>
                <td class='cell'>".$vnt->__GET('Fecha')."</td>
                <td class='cell'>".$vnt->__GET('IdCliente')."</td>
                <td class='cell'>".$vnt->__GET('IdEmpleado')."</td>
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
      $html2pdf->Output('Ventas.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }
?>