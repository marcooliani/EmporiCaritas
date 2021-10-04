<?php
    session_start();

    $data_export = date("Ymd");
    $filename="prodotti_".$data_export.".xls";
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

<title>Prodotti</title></head>
<body>
<table border="1">
  <thead>
    <tr>
        <th>BARCODE</th>
        <th>DESCRIZIONE</th>
        <th>CODICE TIPOLOGIA</th>
        <th>TIPOLOGIA</th>
        <th>UM_1</th>
        <th>CONTENUTO UM1</th>
        <th>AGEA</th>
        <th>CLASSIFICATO</th>
        <th>UM STOCK</th>
        <th>STOCK</th>
    </tr>
  </thead>

  <tbody>
    <?php
        foreach($ris as $k=>$row) {
            print("<tr>");
            print("<td>".ucwords($row['barcode'])."</td>");
            print("<td>".ucwords($row['descrizione'])."</td>");
            print("<td>".ucwords($row['tipologia'])."</td>");
            print("<td>".ucwords($row['descrizione_tipologia'])."</td>");
            print("<td>".$row['um_1']."</td>");
            print("<td>".ucwords($row['contenuto_um1'])."</td>");
            print("<td>".ucwords($row['agea'])."</td>");
            print("<td>".ucwords($row['classificato'])."</td>");
            print("<td>".$row['um_stock']."</td>");
            print("<td>".ucwords($row['stock'])."</td>");
            print("</tr>");
        }
    ?>
  </tbody>
</table>

</body>
</html>
