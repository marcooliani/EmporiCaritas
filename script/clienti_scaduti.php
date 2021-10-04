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

            $sql = "SELECT COUNT(codice_fiscale) AS inscadenza FROM famiglie WHERE scadenza BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d", strtotime("+1 week"))."'";
            $ris = $conn_loc->query($sql);

            $val = $ris->fetch();

            if($val['inscadenza'] > 0 ) {
                $msg = "<font color='#f00'><strong>[SYSTEM]</strong></font> - Ci sono <strong>".$val['inscadenza']."</strong> clienti <strong>in scadenza</strong> 
                        nella prossima settimana";
                $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('clienti_inscadenza', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
                $count = $conn_loc->exec($sql_notifica);
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
