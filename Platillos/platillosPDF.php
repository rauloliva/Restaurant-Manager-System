<?php
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();

    require "platillo.dao.php";
    require_once dirname(__DIR__).'/pdo.php';

    $pdo = new PDO_connection();
    $dao = new PlatilloDAO($pdo);
    $consult = "";
    $info_filtro = "";
    if(isset($_GET['value1'])){
        if($_GET['value1'] != ""){
            $consult = " WHERE ".$_GET['col'];
            if($_GET['value2'] != "name" && $_GET['value2'] != "cat") {
                $consult .= ">=".$_GET['value1']." and ".$_GET['col']."<=".$_GET['value2'];
            }else if($_GET['value2'] == "name"){
                $consult .= " LIKE '".$_GET['value1']."%'";
            }else if($_GET['value2'] == "cat" && $_GET['value1'] != "null"){
                $consult .= " = '".$_GET['value1']."'";
            }else if($_GET['value2'] == "a" || $_GET['value1'] == "null"){
                $consult .= " LIKE '%%'";
            }
            $info_filtro = "Filtrado por ".preg_split('/_/',$_GET['col'])[0];
        }
        
    }
    $platillos = $dao->Listar($consult);
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
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    table{
        margin-left: 5%
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
 
  <table border="1" style="width:100%">
      <thead>
          <tr>
              <th class="cellHeader">ID</th>
              <th class="cellHeader">Nombre</th>
              <th class="cellHeader">Precio</th>
              <th class="cellHeader">Ingredientes</th>
              <th class="cellHeader">Categoria</th>
          </tr>
      </thead>
      <?php 
        foreach ($platillos as $plt) {
            echo "<tr>
                <td class='cell'>".$plt->__GET('id')."</td>
                <td class='cell'>".$plt->__GET('Nombre')."</td>
                <td class='cell'>".$plt->__GET('Precio')."</td>
                <td class='cell'>".$plt->__GET('Ingredientes')."</td>
                <td class='cell'>".$plt->__GET('Categoria')."</td>
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
      $html2pdf->Output('Platillos.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }
?>