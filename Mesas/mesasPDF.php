<?php
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();

    require "mesa.dao.php";
    require_once dirname(__DIR__).'/pdo.php';

    $pdo = new PDO_connection();
    $dao = new MesaDAO($pdo);
    $condition = "";
    $info_filtro = "";
    if(isset($_GET['value'])){
        if($_GET['value'] != ""){
            $condition = " where ".$_GET['column']." like '".$_GET['value']."%'";
            $info_filtro = "Filtrado por ".preg_split('/_/',$_GET['column'])[0];
        }
        
    }
    $mesas = $dao->Listar($condition);
    
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
        padding-right:5px;
        font-size: 19px;
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
    <h1>Mesas</h1>
    <img src="logo_frame.png" width="100" height="100">
    <h3><?php echo $info_filtro?></h3>
 </div><br>
 
  <table border="1">
      <thead>
          <tr>
              <th class="cellHeader">Mesa</th>
              <th class="cellHeader">Mesero</th>
              <th class="cellHeader">Estatus</th>
          </tr>
      </thead>
      <?php 
        foreach ($mesas as $mesa) {
            echo "<tr>
                <td class='cell'>".$mesa->__GET('mesa')."</td>
                <td class='cell'>".$mesa->__GET('mesero')."</td>
                <td class='cell'>".$mesa->__GET('estatus')."</td>
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
      $html2pdf->Output('Mesas.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }
?>
