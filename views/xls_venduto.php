<?php
    session_start();

    $data_export = date("Ymd");
    $filename="venduto".$anno.$mese."_".$tipo.".xls";
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename='$filename'");
?>

<html lang=it><head>
<style type="text/css">
th {
        background: #00FFFF;
        font-family: Arial, Helvetica, sans-serif;
        height: 50px;
        text-transform:uppercase;
   }

td {
        font-family: Arial, Helvetica, sans-serif;
        padding:5px;
        font-size:10pt;
        border: thin solid #000;
        text-transform:uppercase;
   }
</style>

<title>Donatori</title></head>
<body>
<table border="1">
  <thead>
    <tr>
        <th >DATA</th>
        <?php if($tipo == "famiglie") { ?>
        <th >FAMIGLIA</th>
        <th >PUNTI FAMIGLIA</th>
        <?php } ?>
        <th >BARCODE</th>
        <th >DESCRIZIONE</th>
        <th >TIPOLOGIA</th>
        <th >CATEGORIA</th>
        <th >PUNTI</th>
        <th >SCONTATO</th>
        <th >QT&Agrave;</th>
        <?php if($tipo == "famiglie") { ?>
        <th >TOTALE</th>
        <?php } ?>
    </tr>
  </thead>

  <tbody>
    <?php 
        foreach($ris as $k=>$row) {
            if($row['scontato']) {
                $offerta = "S&igrave;";
            }
            else { $offerta = "No"; }

            print("<tr>");
            print("<td>".date("d/m/Y", strtotime($row['date']))."</td>");
            if($tipo == "famiglie") {
                print("<td>".ucwords($row['cognome'])." ".ucwords($row['nome'])."</td>");
                print("<td>".$row['punti_totali']."</td>");
            }
            print("<td>".$row['barcode']."</td>");
            print("<td>".$row['descrizione']."</td>");
            print("<td>".ucwords($row['descrizione_tipologia'])."</td>");
            print("<td>".ucwords($row['descrizione_categoria'])."</td>");
            print("<td>".$row['punti']."</td>");
            print("<td>".$offerta."</td>");
            print("<td>".$row['qta']."</td>");
            if($tipo == "famiglie") {
                print("<td>".$row['qta'] * $row['punti']."</td>");
            }

            print("</tr>");
        }
    ?>
  </tbody>
</table>

</body>
</html>
