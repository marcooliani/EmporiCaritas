<?php
    session_start();

    $data_export = date("Ymd");
    $filename=$tipo.$anno.$mese.".xls";
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
        <th >RAGIONE SOCIALE</th>
        <th >PRODOTTO</th>
        <th >DESCRIZIONE</th>
        <th >TIPOLOGIA</th>
        <th >CATEGORIA</th>
        <th >QT&Agrave;</th>
    </tr>
  </thead>

  <tbody>
    <?php 
        foreach($ris as $k=>$row) {
            print("<tr>");
            print("<td>".date("d/m/Y", strtotime($row['data_donazione']))."</td>");
            print("<td>".$row['ragione_sociale']."</td>");
            print("<td>".$row['barcode']."</td>");
            print("<td>".$row['descrizione']."</td>");
            print("<td>".ucwords($row['descrizione_tipologia'])."</td>");
            print("<td>".ucwords($row['descrizione_categoria'])."</td>");
            print("<td>".$row['quantita']."</td>");
            print("</tr>");
        }
    ?>
  </tbody>
</table>

</body>
</html>
