<?php

try {
    $conn = new PDO("pgsql:host=localhost;port=5432;dbname=emporiodb_centrale", 'emporiocentrale', 'emporiocentrale');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_listaempori = "SELECT * FROM lista_empori";
    $ris = $conn->query($sql_listaempori);

    foreach($ris as $key=>$val) {
        try {
            $conn_loc = new PDO("pgsql:host=".$val['dbhost'].";port=".$val['dbport'].";dbname=".$val['dbname']."", $val['dbuser'], $val['dbpass']);
            $conn_loc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_scadenza = "SELECT lotti.barcode, barcodes.descrizione, lotti.num_lotto lotti.scadenza FROM lotti 
                                INNER JOIN barcodes ON barcodes.barcode = lotti.barcode
                                WHERE barcodes.barcode = '".$val['barcode']."'
                                AND lotti.scadenza <= '".date("Y-m-d", strtotime(date("Y-m-d")."+1 week"))."'";
            $ris_scadenza = $conn_loc->query($sql_scadenza);

            if($ris_scadenza->rowCount() > 0 ) {
                foreach($ris_scadenza as $key=>$val_scadenza) {
                    $msg = "<font color='#f00'><strong>[SYSTEM]</strong></font> - Il <strong>lotto ".$val_scadenza['num_lotto']."</strong>
                                del prodotto <strong>".$val_scadenza['descrizione']."</strong> (".$val_scadenza['bar'].") &egrave; 
                                <strong>in scadenza il ".date("d/m/Y", strtotime($val_scadenza['bar']))."</strong>. Verificare il magazzino!";
                    $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('lotti', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                    $count = $conn_loc->exec($sql_notifica);
                }
            }

        }

        catch(PDOException $e) {
            echo "Error : " . $e->getMessage() . "<br/>";
        //  die();
        }
    }
}

catch(PDOException $e) {
    echo "Error : " . $e->getMessage() . "<br/>";
    die();
}

?>
