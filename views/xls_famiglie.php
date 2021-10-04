<?php
    session_start();

    $data_export = date("Ymd");
    $filename="famiglie_".$data_export.".xls";
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
        <th>CODICE FISCALE</th>
        <th>COGNOME</th>
        <th>NOME</th>
        <th>ENTE</th>
        <th>DATA NASCITA</th>
        <th>LUOGO NASCITA</th>
        <th>NAZIONE</th>
        <th>NAZIONALIT&Agrave;</th>
        <th>SESSO</th>
        <th>INDIRIZZO</th>
        <th>LOCALIT&Agrave;</th>
        <th>CAP</th>
        <th>COMUNE</th>
        <th>TELEFONO</th>
        <th>CELLULARE</th>
        <th>EMAIL</th>
        <th>COMPONENTI</th>
        <th>PUNTI TOTALI</th>
        <th>PUNTI RESIDUI</th>
        <th>ESENZIONE</th>
        <th>SCADENZA</th>
        <th>SOSPESO</th>
        <th>SOSPESO DA</th>
        <th>SOSPESO A</th>
    </tr>
  </thead>

  <tbody>
    <?php
        foreach($ris as $k=>$row) {
            print("<tr>");
            print("<td>".ucwords($row['codice_fiscale'])."</td>");
            print("<td>".ucwords($row['cognome'])."</td>");
            print("<td>".ucwords($row['nome'])."</td>");
            print("<td>".ucwords($row['is_ente'])."</td>");
            print("<td>".ucwords($row['data_nascita'])."</td>");
            print("<td>".ucwords($row['luogo_nascita'])."</td>");
            print("<td>".ucwords($row['nazione'])."</td>");
            print("<td>".ucwords($row['nazionalita'])."</td>");
            print("<td>".ucwords($row['sesso'])."</td>");
            print("<td>".ucwords($row['indirizzo'])."</td>");
            print("<td>".ucwords($row['localita'])."</td>");
            print("<td>".$row['cap']."</td>");
            print("<td>".ucwords($row['comune'])."</td>");
            print("<td>".$row['telefono_1']."</td>");
            print("<td>".$row['cellulare']."</td>");
            print("<td>".$row['email']."</td>");
            print("<td>".ucwords($row['num_componenti'])."</td>");
            print("<td>".ucwords($row['punti_totali'])."</td>");
            print("<td>".ucwords($row['punti_residui'])."</td>");
            print("<td>".ucwords($row['esenzione'])."</td>");
            print("<td>".ucwords($row['scadenza'])."</td>");
            print("<td>".ucwords($row['sospeso'])."</td>");
            print("<td>".ucwords($row['sospeso_da'])."</td>");
            print("<td>".ucwords($row['sospeso_a'])."</td>");
            print("</tr>");
        }
    ?>
  </tbody>
</table>

</body>
</html>
