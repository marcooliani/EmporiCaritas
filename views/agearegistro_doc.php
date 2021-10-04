<?php
    $filename="agea_caricoscarico_".date("Ymd", strtotime($data_report)).".xls";
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"".$filename."\""); 

?>
<html lang=it><head>
<?php echo $css; ?>
<title>Titolo</title></head>
<body>

<?php echo $html; ?>
</body>
</html>

