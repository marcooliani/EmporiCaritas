<?php
    session_start();

    $data_export = date("Ymd");
    $filename="tipologie_".$data_export.".xls";
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$filename");
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
        <th>TIPOLOGIA</th>
        <th>STOCK PZ</th>
        <th>UM</th>
        <th>STOCK UM</th>
    </tr>
  </thead>

  <tbody>
    <?php
        foreach($ris as $k=>$row) {
            print("<tr>");
            print("<td>".ucwords($row['descrizione_tipologia'])."</td>");
            print("<td>".ucwords($row['stock'])."</td>");
            print("<td>".ucwords($row['um'])."</td>");
            print("<td>".ucwords($row['quantita_stock'])."</td>");
            print("</tr>");
        }
    ?>
  </tbody>
</table>

</body>
</html>
