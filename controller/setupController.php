<?php

class setupController extends BaseController {
	public function index() {
		if($_REQUEST['createdb'] == "ok") {
			$id_emporio = date("ymdHis");
			$cfg = new iniParser("config.ini");  

			$tool = $cfg->get("database"); 
			$cfg->setValue("database","db_name", $_REQUEST['dbname']); 
			$cfg->setValue("database","db_username", $_REQUEST['dbuser']); 
			$cfg->setValue("database","db_password", $_REQUEST['dbpass']); 
			$cfg->setValue("database","db_hostname", $_REQUEST['dbhost']); 
			$cfg->setValue("database","db_port", $_REQUEST['dbport']); 

			$cfg->save("config.ini");	

			$cfg = new iniParser("config.ini");  
			$tool = $cfg->get("database"); 
			$cfg->get("emporio");
			$cfg->setValue("emporio","idemporio", $id_emporio);
			$cfg->setValue("emporio","tipologia", $_REQUEST['tipo_emporio']);
			$cfg->save("config.ini");	

			$this->crea_db($id_emporio, $_REQUEST['dbname'], $_REQUEST['dbuser'], $_REQUEST['dbpass'], $_REQUEST['dbhost'], $_REQUEST['dbport'], $_REQUEST['tipo_emporio']);
		}

		else {
			$this->registry->template->show("setup");
		}
	}

	private function crea_db($id_emporio, $db, $user, $pass, $host, $port, $tipologia) {
		try {
			$conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);

			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "GRANT ALL PRIVILEGES ON DATABASE ".$db." TO ".$user.";";

			if($tipologia == "rete") {
				$sql .= "CREATE EXTENSION IF NOT EXISTS postgres_fdw;
						CREATE SERVER emporio_centrale FOREIGN DATA WRAPPER postgres_fdw OPTIONS (host 'localhost', dbname 'emporiodb_centrale', port '5432');
						CREATE USER MAPPING FOR ".$user." SERVER emporio_centrale OPTIONS (user 'emporiocentrale', password 'emporiocentrale');
						";
			}
			
			if($tipologia == "rete") {
				$sql .= "CREATE FOREIGN TABLE IF NOT EXISTS comuni (
    						cod_istat VARCHAR(10),
    						nome_comune VARCHAR(50),
    						provincia VARCHAR(20),
    						cap VARCHAR(5),
    						regione VARCHAR(20)
						) SERVER emporio_centrale; 

                        CREATE FOREIGN TABLE IF NOT EXISTS nazioni (
                            cod_internazionale VARCHAR(5),
                            nome_nazione VARCHAR(100)
                        ) SERVER emporio_centrale;
						";
			}

			else {
				$sql .= "CREATE TABLE comuni (
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
						";
			}

			$sql .= "CREATE TABLE users (
    				login VARCHAR(20) NOT NULL,
    				password VARCHAR(100) NOT NULL,
    				ruolo VARCHAR(50) NOT NULL,
    				cognome VARCHAR(100),
    				nome VARCHAR(100),
                    logged BOOL DEFAULT 'f',

    				PRIMARY KEY(login)
				);

				CREATE TABLE enti (
    				id_ente SERIAL,
    				ragione_sociale VARCHAR(255),
    				inserito_il DATE,
    				note TEXT,

    				PRIMARY KEY(id_ente)
				);

				CREATE TABLE famiglie (
    				codice_fiscale VARCHAR(20),
    				cognome VARCHAR(100) NOT NULL,
    				nome VARCHAR(100),
    				is_ente BOOLEAN NOT NULL DEFAULT false,
    				data_nascita DATE,
    				luogo_nascita VARCHAR(255),
    				nazione VARCHAR(100),
    				nazionalita VARCHAR(100),
    				sesso VARCHAR(10),
    				ente_proponente INT,
    				indirizzo VARCHAR(255),
    				localita VARCHAR(255),
    				cap VARCHAR(10),
    				comune VARCHAR(10),
    				telefono_1 VARCHAR(15),
    				cellulare VARCHAR(15),
    				email VARCHAR(255),
    				num_componenti INT,
    				punti_totali INT NOT NULL,
    				punti_residui INT NOT NULL,
    				esenzione BOOLEAN NOT NULL DEFAULT false,
    				giorno_1 VARCHAR(20),
    				orario VARCHAR(40),
    				scadenza DATE,
    				iscritto_da DATE,
    				sospeso BOOLEAN NOT NULL DEFAULT false,
    				sospeso_da DATE,
    				sospeso_a DATE,
    				note TEXT,

    				PRIMARY KEY(codice_fiscale),
    				FOREIGN KEY(ente_proponente) REFERENCES enti(id_ente)
        				ON UPDATE CASCADE
        				ON DELETE NO ACTION
				);

				CREATE TABLE componenti_famiglie (
    				c_codice_fiscale VARCHAR(20),
    				capofamiglia VARCHAR(20),
    				cognome VARCHAR(255),
    				nome VARCHAR(255),
    				ruolo VARCHAR(100),
    				sesso VARCHAR(15),
    				data_nascita DATE,
    				luogo_nascita VARCHAR(255),
    				nazione VARCHAR(100),
    				nazionalita VARCHAR(100),

    				PRIMARY KEY(c_codice_fiscale),
    				FOREIGN KEY(capofamiglia) REFERENCES famiglie(codice_fiscale)
        				ON UPDATE CASCADE
        				ON DELETE CASCADE
				);

				CREATE TABLE tabella_carbonaro (
    				famigliari INT,
    				punti_famiglia INT,
    				coefficiente REAL,
    				punti_corretti INT
				); 
				";

			if($tipologia == "rete") {
				$sql .=	"CREATE FOREIGN TABLE IF NOT EXISTS um (
    						val_um VARCHAR(10),
    						descrizione VARCHAR(50),
                            tipo VARCHAR(20)
						) SERVER emporio_centrale;

				CREATE FOREIGN TABLE IF NOT EXISTS categorie (
    				id_categoria VARCHAR(50),
    				descrizione_categoria VARCHAR(255),
    				limite_spesa_max INT,
    				limite_mese_max INT,
                    tipo_um VARCHAR(20)
				) SERVER emporio_centrale;

				CREATE FOREIGN TABLE IF NOT EXISTS tipologie (
    				id_tipologia VARCHAR(50),
    				categoria VARCHAR(50),
    				descrizione_tipologia VARCHAR(255),
    				warning_qta_minima INT,
    				danger_qta_minima INT,
    				punti INT,
                    eta_min INT,
                    eta_max INT
				) SERVER emporio_centrale;
				";
			}

			else {
				$sql .= "CREATE TABLE um (
    						val_um VARCHAR(10),
    						descrizione VARCHAR(50),
                            tipo VARCHAR(20),

							PRIMARY KEY(val_um)
						);

						CREATE TABLE categorie (
    						id_categoria VARCHAR(50),
    						descrizione_categoria VARCHAR(255),
    						limite_spesa_max INT,
    						limite_mese_max INT,
							tipo_um VARCHAR(20),

    						PRIMARY KEY(id_categoria),
    						UNIQUE(descrizione_categoria)
						);

						CREATE TABLE tipologie (
    						id_tipologia VARCHAR(50),
    						categoria VARCHAR(50),
    						descrizione_tipologia VARCHAR(255),
    						warning_qta_minima INT,
    						danger_qta_minima INT,
    						punti INT NOT NULL,
                            eta_mix INT,
                            eta_max INT,

    						PRIMARY KEY(id_tipologia),
    						UNIQUE(descrizione_tipologia),
    						FOREIGN KEY(categoria) REFERENCES categorie(id_categoria)
        						ON UPDATE CASCADE
        						ON DELETE CASCADE
						);
						";
			}

			$sql .=	"CREATE TABLE barcodes (
    					barcode VARCHAR(50),
    					tipologia VARCHAR(50),
    					descrizione VARCHAR(255) NOT NULL,
    					um_1 VARCHAR(10),
    					um_2 VARCHAR(10),
    					contenuto_um1 REAL,
    					contenuto_um2 REAL,
    					agea BOOL NOT NULL DEFAULT false,
    					classificato BOOL NOT NULL DEFAULT true,
    					um_stock VARCHAR(10),
    					stock INT,

    				PRIMARY KEY(barcode)";
			if($tipologia != "rete") {
				$sql .= ",
						FOREIGN KEY(tipologia) REFERENCES tipologie(id_tipologia)
        					ON UPDATE CASCADE
        					ON DELETE CASCADE
						";
			}
			
			$sql .=	");

				CREATE TABLE offerte_sconti (
    				id_offerta SERIAL,
					barcode VARCHAR(50),
    				id_tipologia VARCHAR(50),
    				prezzo_offerta INT,
    				offerta_da DATE,
    				offerta_a DATE,

					PRIMARY KEY(id_offerta),
					FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
						ON UPDATE CASCADE
						ON DELETE CASCADE
				);

				CREATE TABLE limiti_nucleo (
    				categoria_merce VARCHAR(50),
    				lim_1c_spesa INT,
    				lim_2c_spesa INT,
    				lim_3c_spesa INT,
    				lim_4c_spesa INT,
    				lim_5c_spesa INT,
    				lim_6c_spesa INT,
    				lim_7c_spesa INT,
    				lim_8c_spesa INT,
    				lim_9c_spesa INT,
    				lim_10c_spesa INT,
    				lim_1c_mese INT,
    				lim_2c_mese INT,
    				lim_3c_mese INT,
    				lim_4c_mese INT,
    				lim_5c_mese INT,
    				lim_6c_mese INT,
    				lim_7c_mese INT,
    				lim_8c_mese INT,
    				lim_9c_mese INT,
    				lim_10c_mese INT,

    				PRIMARY KEY(categoria_merce)";
			if($tipologia != "rete") {
				$sql .= ",
						FOREIGN KEY(categoria_merce) REFERENCES categorie(id_categoria)
        					ON UPDATE CASCADE
        					ON DELETE CASCADE
						";
			}
				
			$sql .=	");

				CREATE TABLE scontrini (
    				id_scontrino VARCHAR(50),
    				codice_fiscale VARCHAR(20),
					cliente_effettivo VARCHAR(50),
    				data TIMESTAMP,
    				totale_punti INT,
    				cassa VARCHAR(20),
    				operatore VARCHAR(50),
                    note TEXT,

    				PRIMARY KEY(id_scontrino),
    				FOREIGN KEY(codice_fiscale) REFERENCES famiglie(codice_fiscale)
        				ON UPDATE CASCADE
        				ON DELETE CASCADE
				);

				CREATE TABLE spesa (
    				id_scontrino VARCHAR(50),
    				barcode VARCHAR(50),
    				tipologia VARCHAR(50),
    				data TIMESTAMP,
    				punti INT,
    				qta INT,
					scontato BOOLEAN DEFAULT false,

    				FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
        				ON UPDATE CASCADE
        				ON DELETE NO ACTION,
    				FOREIGN KEY(id_scontrino) REFERENCES scontrini(id_scontrino)
        				ON UPDATE CASCADE
        				ON DELETE CASCADE";

            if($tipologia != "rete") {
                $sql .= ",
                        FOREIGN KEY(tipologia) REFERENCES tipologie(id_tipologia)
                            ON UPDATE CASCADE
                            ON DELETE CASCADE
                        ";
            }

			$sql .=	");

				CREATE TABLE fornitori (
    				id_fornitore SERIAL,
    				ragione_sociale VARCHAR(100),
    				cognome VARCHAR(50),
    				nome VARCHAR(50),
    				referente VARCHAR(100),
    				indirizzo VARCHAR(255),
    				localita VARCHAR(100),
    				cap VARCHAR(5),
    				comune VARCHAR(10),
    				telefono VARCHAR(50),
   	 				email VARCHAR(255),
    				fornitore_da DATE,
    				donatore BOOLEAN DEFAULT false,
    				note TEXT,
                    cellulare VARCHAR(50),

    				PRIMARY KEY(id_fornitore)
				);

				CREATE TABLE donazioni (
    				id_transazione SERIAL,
    				id_fornitore INT,
    				barcode VARCHAR(50),
    				quantita INT,
    				data_donazione DATE,
    				acquistato BOOL NOT NULL DEFAULT false,
    				ddt VARCHAR(15),
                    num_lotto VARCHAR(20),

    				PRIMARY KEY(id_transazione),
    				FOREIGN KEY(id_fornitore) REFERENCES fornitori(id_fornitore)
        				ON UPDATE CASCADE
        				ON DELETE CASCADE,
    				FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
        				ON UPDATE CASCADE
        				ON DELETE CASCADE
				);

                CREATE TABLE lotti (
                    num_lotto VARCHAR(20),
                    barcode VARCHAR(50),
                    scadenza DATE,
                    qta INT,

                    FOREIGN KEY(barcode) REFERENCES barcodes(barcode)
                        ON UPDATE CASCADE
                        ON DELETE CASCADE
                );

				CREATE TABLE registro_agea (
    				data DATE,
    				ddt VARCHAR(15),
    				carico BOOLEAN default false,
    				id_tipologia VARCHAR(50),
    				um_1 VARCHAR(10),
    				quantita REAL,
    				giacenza REAL,
					indigenti INT,
					cliente VARCHAR(20)
				);

				CREATE TABLE registro_agea_indigenti (
					data DATE,
					cliente VARCHAR(20),
					indigenti INT,

					PRIMARY KEY(data, cliente)
				);

				CREATE TABLE notifiche (
    				id_notifica SERIAL,
    				tipo VARCHAR(100),
    				msg TEXT,
    				data TIMESTAMP,
    				letto BOOLEAN DEFAULT false,

    				PRIMARY KEY(id_notifica)
				);

                CREATE TABLE messaggi (
                    id_conversazione VARCHAR(100),
                    peer VARCHAR(50),
                    id_messaggio SERIAL, 
                    tipo VARCHAR(100), 
                    sender VARCHAR(50), 
                    receiver VARCHAR(50), 
                    msg TEXT, 
                    data TIMESTAMP, 
                    letto BOOLEAN DEFAULT false,

                    PRIMARY KEY(id_conversazione, id_messaggio)
                );

				CREATE TABLE logs (
					log_id SERIAL, 
    				log_data TIMESTAMP, 
    				flag VARCHAR(30), 
    				log_descrizione TEXT, 
    				login VARCHAR(20), 

    				PRIMARY KEY(log_id) 
				);

				CREATE TABLE emporio_config (
					id_emporio BIGINT,
					num_struttura INT,
    				nome_emporio VARCHAR(255),
					cognome_responsabile VARCHAR(100),
					nome_responsabile VARCHAR(100),
					data_nascita DATE,
					comune_nascita VARCHAR(10),
					nome_associazione VARCHAR(255),
					indirizzo_associazione VARCHAR(255),
					localita VARCHAR(100),
					comune VARCHAR(10),
					cap VARCHAR(5),
                    telefono VARCHAR(20),
                    email VARCHAR(255),

					PRIMARY KEY(id_emporio)
				);
				";

			if($tipologia == "rete") {
				$sql .= "CREATE FOREIGN TABLE IF NOT EXISTS lista_empori (
    						id_emporio BIGINT,
							nome_emporio VARCHAR(255),
							dbname VARCHAR(100),
							dbuser VARCHAR(100),
							dbpass VARCHAR(100),
							dbhost VARCHAR(100) DEFAULT 'localhost',
							dbport INT DEFAULT 5432
					) SERVER emporio_centrale;

					CREATE FOREIGN TABLE IF NOT EXISTS centralized_users (
						login VARCHAR(20),
                    	password VARCHAR(100),
                    	ruolo VARCHAR(50),
                    	cognome VARCHAR(100),
                    	nome VARCHAR(100),
                        logged BOOL DEFAULT 'f',
                        last_login TIMESTAMP
					) SERVER emporio_centrale;
				";
			}
			else {
				$sql .= "CREATE TABLE centralized_users (
                        	login VARCHAR(20),
                        	password VARCHAR(100),
                        	ruolo VARCHAR(50),
                        	cognome VARCHAR(100),
                       	 	nome VARCHAR(100),
                            logged BOOL DEFAULT false,
                            last_login TIMESTAMP,

							PRIMARY KEY(login)
                    	);
                        ";
			}

            $sql .= "INSERT INTO users VALUES('admin', 'emporio', 'admin', 'Amministratore locale', '');";
            $sql .= "INSERT INTO enti VALUES('0','Nessun ente', '".date("Y-m-d")."', '');";

            if($tipologia != "rete") {
                $sql .= "INSERT INTO centralized_users VALUES('amministratore', '".pg_escape_string("p4$\$w0Rd")."', 'super', '', '');";
            }

			$sql .= "/*	GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO ".$user.";
				GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO ".$user."; */
			";

			$conn->exec($sql);

			// Inserisco i dati dell'emporio in rete nella lista degli empori
			if($tipologia == "rete") {
				$sql_db = "INSERT INTO lista_empori(id_emporio, nome_emporio, dbname, dbuser, dbpass, dbhost, dbport) 
					   		VALUES('".$id_emporio."',
								'noname',
								'".pg_escape_string(strtolower($db))."', 
								'".pg_escape_string($user)."', 
								'".pg_escape_string($pass)."', 
								'".pg_escape_string(strtolower($host))."', 
								'".$port."')";
				$conn->exec($sql_db);
			}

			// Importo la lista dei comuni, delle nazioni e delle UM per gli empori standalone
			if($tipologia != "rete") {
				$this->importa_comuni($conn);
                $this->importa_nazioni($conn);
                $this->importa_misure($conn);
			}

			header("Location: /setup/config");
		}
		catch(PDOException $e) {
    		echo "Error : " . $e->getMessage() . "<br/>";
    		die();
		}

	}

	public function config($step = '') {
		if($step == '') {
			$this->registry->template->show("setup2");
		}

		if($step == '2') {
			// Salvo il nome dell'emporio nel file di configurazione. Visto che mi serve
			// solo per le navbar, e` quasi inutile richiamare ogni volta il dato dal db..
            $cfg = new iniParser("config.ini");
            $tool = $cfg->get("emporio");
            $cfg->setValue("emporio","nome_emporio", $_REQUEST['nome_emporio']);
            $cfg->setValue("emporio","num_struttura", $_REQUEST['num_struttura']);
            $cfg->save("config.ini");
	
			// Verifico la tipologia dell'eporio
			$config = Config::getInstance();
			$tipologia = $config->config_values['emporio']['tipologia'];
			$id_emporio = $config->config_values['emporio']['idemporio'];

			// Scrivo tutto anche sul db, che non si sa mai
			$sql = "INSERT INTO emporio_config 
					VALUES('".$id_emporio."',
						'".$_REQUEST['num_struttura']."',
						'".pg_escape_string(strtolower($_REQUEST['nome_emporio']))."', 
						'".pg_escape_string(strtolower($_REQUEST['cognome_responsabile']))."',
						'".pg_escape_string(strtolower($_REQUEST['nome_responsabile']))."',
						'".$_REQUEST['data_nascita']."',
						'".$_REQUEST['comune_nascita']."',
						'".pg_escape_string(strtolower($_REQUEST['nome_associazione']))."',
						'".pg_escape_string(strtolower($_REQUEST['indirizzo_associazione']))."',
						'".pg_escape_string(strtolower($_REQUEST['localita']))."',
						'".$_REQUEST['comune_associazione']."',
						'',
						'".$_REQUEST['telefono']."',
						'".$_REQUEST['email']."'
					)";
			$count = $this->registry->db->exec($sql);

			if($tipologia == "rete") {
				//$sql_lastval = $this->registry->db->query("SELECT MAX(id_emporio) AS id_emporio FROM lista_empori");
				//$val = $sql_lastval->fetch();

				$sql_update_nome = "UPDATE lista_empori SET nome_emporio = '".pg_escape_string(strtolower($_REQUEST['nome_emporio']))."' 
									WHERE id_emporio = '".$id_emporio."'";
				$count2 = $this->registry->db->exec($sql_update_nome);
			}

			$this->importa_carbonaro();

			if($count > 0) {
				header("Location: /setup/config/3");
			}
		}

        if($step == '3') {
            if(!empty($_REQUEST['fileupload'])) {
            /*    echo "<pre>";
                print_r($_FILES);
                echo "</pre>"; */
                foreach($_FILES as $filename=>$bla) {
                    if($filename == "prodotti") {
                        if($_FILES[$filename]["error"] == 0) {
                            move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                            $this->import_data($_FILES[$filename]["name"], "barcodes");
                        }
                    }
                    if($filename == "famiglie") {
                        if($_FILES[$filename]["error"] == 0) {
                            move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                            $this->import_data($_FILES[$filename]["name"], "famiglie");
                        }
                    }
                    if($filename == "famigliari") {
                        if($_FILES[$filename]["error"] == 0) {
                            move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                            $this->import_data($_FILES[$filename]["name"], "componenti_famiglie");
                        }
                    }
                    if($filename == "fornitori") {
                        if($_FILES[$filename]["error"] == 0) {
                            move_uploaded_file($_FILES[$filename]["tmp_name"],"/tmp/". $_FILES[$filename]["name"]);
                            $this->import_data($_FILES[$filename]["name"], "fornitori");
                        }
                    }

                //$this->registry->template->show("setup4"); 
                }
				header("Location: /setup/config/4"); 
            }

            $this->registry->template->show("setup3");
        }

		if($step == '4') {
			$this->registry->template->show("setup4");
		}
	}
	
    private function import_data($file = '', $table = '') {
        // $table: 
        //      * barcodes
        //      * famiglie
        //      * componenti_famiglie
        //      * fornitori
        $fd = fopen("/tmp/".$file, "r");

        if($fd) {
            if($table == "barcodes") {
                $um_da_sostituire = array("gr", "lt");
                $um_corrette = array("g", "l");

                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    $sql = "INSERT INTO ".$table."
                            VALUES('".$data[0]."', 
                                    '".$data[6]."', 
                                    '".pg_escape_string($data[1])."', 
                                    '".strtolower(str_replace($um_da_sostituire, $um_corrette, $data[5]))."', '".strtolower(str_replace($um_da_sostituire, $um_corrette, $data[5]))."', 
                                    '".$data[4]."', '".$data[4]."', 
                                    'f', 
                                    'f', 
                                    'pz', 
                                    '".$data[3]."')";
                    $this->registry->db->exec($sql);              
               }
            }

            else if($table == "famiglie") {
                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    $sql = "INSERT INTO ".$table."
                            VALUES('".strtoupper($data[0])."',
                                    '".strtolower(pg_escape_string($data[1]))."',
                                    '".strtolower(pg_escape_string($data[2]))."',
                                    'f',
                                    '".$data[3]."',
                                    '',
                                    '00',
                                    '',
                                    '',
                                    '0',
                                    '".strtolower(pg_escape_string($data[4]))."',
                                    '',
                                    NULL,
                                    '000000',
                                    '',
                                    '".$data[5]."',
                                    '',
                                    '1',
                                    '".$data[8]."',
                                    '".$data[9]."',
                                    'f',
                                    '',
                                    '',
                                    '".$data[7]."',
                                    '".$data[6]."',
                                    'f',
                                    NULL,
                                    NULL,
                                    '')";
                    $this->registry->db->exec($sql);              
               }
            }

            else if($table == "componenti_famiglie") {
                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    $sql = "INSERT INTO ".$table."
                            VALUES('".strtoupper($data[0])."',
                                    '".strtoupper($data[4])."',
                                    '".strtolower(pg_escape_string($data[1]))."',
                                    '".strtolower(pg_escape_string($data[2]))."',
                                    '',
                                    '',
                                    '".$data[3]."',
                                    '',
                                    '',
                                    '')";
                    $this->registry->db->exec($sql);
                }
            }

            else if($table == "fornitori") {
                while(($data = fgetcsv($fd, "|", ";")) !== false) {
                    if(empty($data[4])) {
                        $data[4] = '1970-01-01';
                    }
                    $sql = "INSERT INTO ".$table."(ragione_sociale,cognome,nome,referente,indirizzo,localita,cap,comune,telefono,email,fornitore_da,donatore,note)
                            VALUES('".strtolower(pg_escape_string($data[0]))."',
                                    '',
                                    '',
                                    '',
                                    '".strtolower(pg_escape_string($data[2]))."',
                                    '',
                                    '',
                                    '000000',
                                    '".$data[3]."',
                                    '',
                                    '".$data[4]."',
                                    'f',
                                    '".$data[5]."')";
                    $this->registry->db->exec($sql);
                }
            }
        }

        fclose($fd);
    }

	private function importa_demousers() {
		$fd = fopen("public/files/demousers.txt", "r");
    
        if($fd) {
            while(($data = fgetcsv($fd, "|", ";")) !== false) {
                $sql = "INSERT INTO users VALUES('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."')";
                $this->registry->db->exec($sql);
            }
        }

        fclose($fd);
	}

    private function importa_nazioni($conn) {
        /*
         * Importo l'elenco delle nazioni
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
    }

	private function importa_comuni($conn) {
		/*
 		 * Importo l'elenco dei Comuni italiani
 		 */
		include("/var/www/emporio/script/functions.php");

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
	}

    private function importa_misure($conn) {
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
    }

	private function importa_carbonaro() {
		/*
 		 * Importo la tabella di conversione di Carbonaro, per avere i punti per le famiglie
 		 * a seconda dei componenti del nucleo famigliare
 		 */
		$fd = fopen("/var/www/emporio/public/files/tabella_carbonaro.txt", "r");

		echo "Import tabella Carbonaro .....\n";
		if($fd) {
    		while(($data = fgetcsv($fd, "|", ";")) !== false) {
        		$sql_tabella = "INSERT INTO tabella_carbonaro VALUES('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."')";
        		$this->registry->db->exec($sql_tabella);
    		}

    		echo "Completato\n\n";
		}
		else {
    		echo "Fallito\n\n";
		}

		fclose($fd);
	}

	private function test() {
		echo "Ciao";
	}

}

?>
