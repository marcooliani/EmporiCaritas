<?php
// Lascio nel blocco try sia la connessione al db, sia la creazione delle tabelle
// in quanto la exec() in questo caso non mi ritorna il numero di righe modificate
// nell'operazione: quindi l'unico modo per avere controllo sull'effettiva riuscita
// dell'operazione è mettere tutto nell blocco try
try {
	$conn = new PDO("pgsql:host=localhost;port=5432;dbname=emporiodb_centrale", 'emporiocentrale', 'emporiocentrale');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 * Creo le tabelle del db centrale (il db e l'utente devono essere stati
 * creati precedentemente)
 */
$sql = "GRANT ALL PRIVILEGES ON DATABASE emporiodb_centrale TO emporiocentrale;

		CREATE TABLE comuni (
    		cod_istat VARCHAR(10),
    		nome_comune VARCHAR(50),
    		provincia VARCHAR(20),
    		cap VARCHAR(5),
    		regione VARCHAR(20),

    		PRIMARY KEY(cod_istat)
		);

        CREATE TABLE nazioni (
            cod_internazionale VARCHAR(5),
            nome_nazione VARCHAR(100),

            PRIMARY KEY(cod_internazionale)
        );

		CREATE TABLE tabella_carbonaro (
    		famigliari INT,
    		punti_famiglia INT,
    		coefficiente REAL,
    		punti_corretti INT
		);

		CREATE TABLE um (
    		val_um VARCHAR(10),
    		descrizione VARCHAR(50),
            tipo VARCHAR(20),

    		PRIMARY KEY(val_um)
		);

		CREATE TABLE categorie (
    		id_categoria SERIAL,
    		descrizione_categoria VARCHAR(255),
    		limite_spesa_max INT,
    		limite_mese_max INT,
            tipo_um VARCHAR(20),

   			PRIMARY KEY(id_categoria),
    		UNIQUE(descrizione_categoria)
		);

		CREATE TABLE tipologie (
    		id_tipologia SERIAL,
    		categoria INT,
    		descrizione_tipologia VARCHAR(255),
    		warning_qta_minima INT,
    		danger_qta_minima INT,
    		punti INT,
            eta_min INT,
            eta_max INT,

    		PRIMARY KEY(id_tipologia),
    		UNIQUE(descrizione_tipologia),
    		FOREIGN KEY(categoria) REFERENCES categorie(id_categoria)
        		ON UPDATE CASCADE
        		ON DELETE CASCADE
		);

		CREATE TABLE lista_empori (
    		id_emporio BIGINT,
    		nome_emporio VARCHAR(255),
			dbname VARCHAR(100),
			dbuser VARCHAR(100),
			dbpass VARCHAR(100),
			dbhost VARCHAR(100) DEFAULT 'localhost',
			dbport INT DEFAULT 5432,

    		PRIMARY KEY(id_emporio),
			UNIQUE(nome_emporio)
		);

		CREATE TABLE centralized_users (
			login VARCHAR(20),
            password VARCHAR(100),
            ruolo VARCHAR(50),
            cognome VARCHAR(100),
            nome VARCHAR(100),
            logged BOOL DEFAULT false,
            last_login TIMESTAMP,

            PRIMARY KEY(login)
		);

		GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO emporiocentrale;
		GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO emporiocentrale;

        INSERT INTO categorie VALUES('0', 'Senza categoria', '10000', '10000');
        INSERT INTO tipologie VALUES('0', '0', 'Senza Tipologia', '0', '0', '0');
        INSERT INTO centralized_users VALUES('amministratore', '".pg_escape_string("p4$\$w0Rd")."', 'super', '', '');
        ";

	echo "Creazione tabelle database .....\n";
	$conn->exec($sql); 
	echo "Completato\n\n";
}

catch(PDOException $e) {  
	echo "Error : " . $e->getMessage() . "<br/>";  
	die();  
}  

include("/var/www/emporio/script/functions.php");

/*
 * Importo l'elenco dei Comuni italiani
 */
$sql_comune_vuoto = "INSERT INTO comuni VALUES('000000', '', '', '', '')";
$count = $conn->exec($sql_comune_vuoto);

$fd = fopen("/var/www/emporio/public/files/listacomuni.txt", "r");

echo "Import lista comuni .....\n";
if($fd) {
	while(($data = fgetcsv($fd, "|", ";")) !== false) {
		$sql_comuni = "INSERT INTO comuni VALUES('".$data[0]."', '".pg_escape_string(accentate_html(strtolower($data[1])))."', '".$data[2]."', '".$data[5]."', '".$data[3]."')";
 		$count = $conn->exec($sql_comuni);
	}

	echo "Completato\n\n";
}
else {
	echo "Fallito\n\n";
}

fclose($fd);

/* 
 * Importo la lista delle nazioni 
 */
$sql_nazione_vuoto = "INSERT INTO nazioni VALUES('00', '')";
$count = $conn->exec($sql_nazione_vuoto);

$fd = fopen("/var/www/emporio/public/files/nazionalita.txt", "r");

echo "Import lista nazioni .....\n";
if($fd) {
    while(($data = fgetcsv($fd, "|", ";")) !== false) {
        $sql_nazioni = "INSERT INTO nazioni VALUES('".$data[0]."', '".pg_escape_string(accentate_html(strtolower($data[1])))."')";
        $count = $conn->exec($sql_nazioni);
    }

    echo "Completato\n\n";
}
else {
    echo "Fallito\n\n";
}

fclose($fd);

/*
 * Importo la tabella di conversione di Carbonaro, per avere i punti per le famiglie
 * a seconda dei componenti del nucleo famigliare
 */
$fd = fopen("/var/www/emporio/public/files/tabella_carbonaro.txt", "r");

echo "Import tabella Carbonaro .....\n";
if($fd) {
	while(($data = fgetcsv($fd, "|", ";")) !== false) {
		$sql_tabella = "INSERT INTO tabella_carbonaro VALUES('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."')";
 		$conn->exec($sql_tabella);
	}

	echo "Completato\n\n";
}
else {
	echo "Fallito\n\n";
}

fclose($fd);

/*
 * Popolo la tabelle delle unità di misura
 */
$fd = fopen("/var/www/emporio/public/files/misure.txt", "r");

echo "Import unita` di misura .....\n";
if($fd) {
	while(($data = fgetcsv($fd, "|", ";")) !== false) {
  		$sql = "INSERT INTO um VALUES('".$data[0]."', '".$data[1]."', '".$data[2]."')";
		$conn->exec($sql);
	}

	echo "Completato\n\n";
}
else {
	echo "Fallito\n\n";
}


fclose($fd);

?>
