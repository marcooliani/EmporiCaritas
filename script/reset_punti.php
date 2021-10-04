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

			$sql = "UPDATE famiglie SET punti_residui = punti_totali";
			$conn_loc->exec($sql);

            $msg = "<font color='#f00'><strong>[SYSTEM]</strong></font> - I <strong>punti famiglia mensili</strong> sono stati ripristinati ";
            $sql_notifica = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('puntireset_ok', '".pg_escape_string($msg)."', '".date("Y-m-d H:i:s")."', false)";
            $count = $conn_loc->exec($sql_notifica);
		}

		catch(PDOException $e) {
    		echo "Error : " . $e->getMessage() . "<br/>";
    	//	die();
		}
	}
}

catch(PDOException $e) {
    echo "Error : " . $e->getMessage() . "<br/>";
    die();
}

?>
