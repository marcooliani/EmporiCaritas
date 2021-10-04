<?php
    session_start();

    $data_export = date("Ymd");
    $filename="donatori_".$data_export.".xls";
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
        <th>RAGIONE SOCIALE</th>
        <th>COGNOME</th>
        <th>NOME</th>
        <th>INDIRIZZO</th>
        <th>LOCALIT&Agrave;</th>
        <th>COMUNE</th>
        <th>CAP</th>
        <th>TELEFONO</th>
        <th>EMAIL</th>
        <th>DONATORE DA</th>
    </tr>
  </thead>

  <tbody>
    <?php 
        foreach($ris as $k=>$row) {
            print("<tr>");
            print("<td>".ucwords($row['ragione_sociale'])."</td>");
            print("<td>".ucwords($row['cognome'])."</td>");
            print("<td>".ucwords($row['nome'])."</td>");
            print("<td>".ucwords($row['indirizzo'])."</td>");
            print("<td>".ucwords($row['localita'])."</td>");
            print("<td>".ucwords($row['nome_comune'])."</td>");
            print("<td>".$row['cap']."</td>");
            print("<td>".$row['telefono']."</td>");
            print("<td>".$row['email']."</td>");
            print("<td>".date("d/m/Y", strtotime($row['fornitore_da']))."</td>");
            print("</tr>");
        }
    ?>
  </tbody>
</table>

</body>
</html>
