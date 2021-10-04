<?php
session_start();

class scontriniController extends BaseController {

	public function index() {
		// TODO
	}

    /**
     * stampa()
     *
     * Imposta lo scontrino e invia i dati alla pagina di stampa
     */
	public function stampa() {
		$cart = $_SESSION['cart'];

        // Aggiungo un numero random di 4 cifre a quello che sarà l'id dello scontrino.
        // Questo perchè ci sono in giro dei cretini che non solo utilizzano lo stesso
        // account di amministrazione in contemporanea e non usano le funzioni che dovrebbero
        // usare, ma anche per evitare che con suddette situazioni si creino due id scontrino
        // identici...
        $random = mt_rand(0,9999);
        $random = sprintf("%04d", $random);

		$idscontrino = date('ymdHis').$random;
		$data_scontrino = date('Y-m-d H:i:s');
		$_SESSION['idscontrino'] = $idscontrino;
		$_SESSION['data_scontrino'] = $data_scontrino;

		$var_vuota='0';

		$this->registry->template->data_scontrino = $data_scontrino;
		$this->registry->template->scontrino = $idscontrino;

        // Per i motivi di cui sopra, mi tocca aprire la transazione, con
        // commit-rollback al seguito. Manco fossimo in banca...
        $this->registry->db->exec("BEGIN");
		
        // Scala i punti spesa dal credito del cliente, se questo non è un cliente
        // esentato dal limite punti
        if(isset($_SESSION['punti_residui']) && !empty($_SESSION['punti_residui'])) {
            try {
			    $sql_update_famiglia = "UPDATE famiglie SET punti_residui = '".$_SESSION['punti_residui']."' WHERE codice_fiscale = '".$_SESSION['tessera']."'";
			    $count = $this->registry->db->exec($sql_update_famiglia);

                if($count <= 0) {
                    $this->registry->template->errorestampa = 1;
                    $this->registry->template->show('cassa_main');
        
                    $this->registry->db->exec("ROLLBACK");
                    return;
                }
            }
            catch(PDOException $e) {
                $this->registry->template->errorestampa = 1;
                $this->registry->template->show('cassa_main');

                $this->registry->db->exec("ROLLBACK");
                return;
            }
        }

        // Nel caso la spesa sia stata fatta dal capofamiglia, setto la variabile
        // relativa a chi ha effettivamente fatto la spesa col cf del capofamiglia stesso.
		if(!isset($_SESSION['tessera_componente']) || empty($_SESSION['tessera_componente'])) {
	 		$_SESSION['tessera_componente'] = $_SESSION['tessera'];
		}

        // Inserisco i dati dello scontrino nella tabella relativa. Se sbaglia, 
        // viene effettuato il rollback e dovrebbero venire ripristinati anche i 
        // punti alla famiglia
        try {
		    $sql_scontrino = "INSERT INTO scontrini VALUES('".$idscontrino."', '".$_SESSION['tessera']."', '".$_SESSION['tessera_componente']."', '".$data_scontrino."', 
						'".$_SESSION['totale_spesa']."', '0' ,'".$_SESSION['username']."', '".$_SESSION['notecliente']."')";
		    $count = $this->registry->db->exec($sql_scontrino);

            if($count <= 0) {
                $sql_update_famiglia = "UPDATE famiglie SET punti_residui = punti_residui +".$_SESSION['totale_spesa']." WHERE codice_fiscale = '".$_SESSION['tessera']."'";
                $count = $this->registry->db->exec($sql_update_famiglia);

                $this->registry->template->errorestampa = 1;
                $this->registry->template->show('cassa_main');

                $this->registry->db->exec("ROLLBACK");
                return;
            }
        }
        catch(PDOException $e) {
            $this->registry->template->errorestampa = 1;
            $this->registry->template->show('cassa_main');

            $this->registry->db->exec("ROLLBACK");

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                                    '[SCONTRINI]', 
                                    'Stampa scontrino '".$idscontrino."' fallita.', 
                                    '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);

            return;
        }
		
        // Scorro il carrello e verifico i singoli prodotti acquistati. In caso di errore,
        // dovrebbe venire effettuato il rollback, altrimenti la transazione viene eseguita
        // e tutti i dati, anche quelli relativi alle precedenti query, vengono aggiornati...
        try {
		    foreach ($cart as $arr) {
                // Recupera i dati del prodotto
			    $item = $arr['item'];

                // Decrementa la quantita` a stock del prodotto attuale
			    $sql_stock = "UPDATE barcodes SET stock = stock -".$arr['qty']." WHERE barcode = '".$item->getId()."'";
			    $count = $this->registry->db->exec($sql_stock);
			
                // Scrive i dati della spesa nel database
			    $sql_spesa = "INSERT INTO spesa VALUES('".$idscontrino."', '".$item->getId()."', '".$item->getTipology()."', '".$data_scontrino."', 
						'".$item->getPrice()."', '".$arr['qty']."', '".$item->getDiscounted()."')";
			    $count = $this->registry->db->exec($sql_spesa);

                // Verifico se si tratta di un prodotto AGEA: se lo e`, aggiunge una riga
                // al registro di carico/scarico AGEA 
			    $sql_check_agea = $this->registry->db->query("SELECT agea, um_1, contenuto_um1 FROM barcodes WHERE agea = 't' AND barcode = '".$item->getId()."'");
			    if($sql_check_agea->rowCount() > 0) {
				    $val = $sql_check_agea->fetch();

                    // Uniformo le unità di misura tra i vari prodotti/barcode
                    if($val['um_1'] == "g") {
                        $val['um_1'] = "kg";
                        $val['contenuto_um1'] = $val['contenuto_um1'] / 1000;
                    }

                    if($val['um_1'] == "cl") {
                        $val['um_1'] = "l";
                        $val['contenuto_um1'] = $val['contenuto_um1'] / 100;
                    }

                    if($val['um_1'] == "ml") {
                        $val['um_1'] = "l";
                        $val['contenuto_um1'] = $val['contenuto_um1'] / 1000;
                    }

                    if($val['um_1'] == "cm") {
                        $val['um_1'] = "m";
                        $val['contenuto_um1'] = $val['contenuto_um1'] / 100;
                    }

                    if($val['um_1'] == "mm") {
                        $val['um_1'] = "m";
                        $val['contenuto_um1'] = $val['contenuto_um1'] / 1000;
                    }

                    if($val['um_1'] == "pz") {
                        $val['contenuto_um1'] = 1;
                    }

                    // Recupera la quantita` a stock dei prodotti AGEA per la categoria
                    // corrispondente
				    /* $sql_stock = $this->registry->db->query("SELECT SUM(barcodes.stock) AS stock FROM barcodes 
														INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia 
														INNER JOIN categorie on tipologie.categoria = categorie.id_categoria 
														WHERE categorie.id_categoria = '".$item->getCategory()."' 
														AND barcodes.agea = 't'"); */
				    $sql_stock = $this->registry->db->query("SELECT stock FROM barcodes WHERE barcodes.barcode = '".$item->getId()."' AND barcodes.agea = 't'");
				    $val2 = $sql_stock->fetch();

                    // Verifico se il cliente ha già effettuato acquisti AGEA nel giorno:
                    // se non lo ha fatto, lo inserisco nella registro indigenti
				    $sql_check_indigenti = "SELECT EXISTS(SELECT 1 FROM registro_agea_indigenti WHERE cliente = '".$_SESSION['tessera']."' AND data = '".date("Y-m-d")."')";
				    $ris = $this->registry->db->query($sql_check_indigenti);
				    $ans2 = $ris->fetch();

				    if(!$ans2['exists']) {
					    $sql_indigenti = $this->registry->db->query("SELECT num_componenti FROM famiglie WHERE codice_fiscale = '".$_SESSION['tessera']."'");
					    $val3 = $sql_indigenti->fetch();

                        if(empty($val3['num_componenti']) || $val3['num_componenti'] == 0) {
                            $val3['num_componenti'] = 1;
                        }

					    $sql_insert_indigenti = "INSERT INTO registro_agea_indigenti 
					    							VALUES('".date("Y-m-d")."', '".$_SESSION['tessera']."', '".$val3['num_componenti']."')";
					    $count = $this->registry->db->exec($sql_insert_indigenti);
				    }

                    // Verifico se il prodotto in questione e` gia` presente nel registro
                    // carico/scarico AGEA per la data odierna
				    /* $sql_check_insert = "SELECT EXISTS(SELECT 1 FROM registro_agea WHERE data = '".date("Y-m-d")."' AND carico = 'f' AND id_tipologia = '".$item->getCategory()."')"; */
				    $sql_check_insert = "SELECT EXISTS(SELECT 1 FROM registro_agea WHERE data = '".date("Y-m-d")."' AND carico = 'f' AND id_tipologia = '".$item->getId()."')";
				    $ris2 = $this->registry->db->query($sql_check_insert);
				    $ans = $ris2->fetch();

                    // Genero il numero di DDC in base ai giorni effettivi in cui
                    // c'e` stato almeno un acquisto AGEA
				    $sql_ddc = "SELECT COUNT(DISTINCT data) AS cli FROM registro_agea_indigenti WHERE data < '".date("Y-m-d")."'";
				    $ris3 = $this->registry->db->query($sql_ddc);
				    $ddc = $ris3->fetch();
				    $ddc['cli']++;

                    // Se il prodotto non e` nel registro, inseriscilo; altrimenti aggiorna
                    // la quantita` gia` presente
				    if(!$ans['exists']) {
			        /*	$sql_insert_registro = "INSERT INTO registro_agea 
						  					VALUES('".date("Y-m-d")."', 
													'".$ddc['cli']."', 
													'f', 
													'".$item->getCategory()."', 
													'".$val['um_1']."', 
													'".$val['contenuto_um1'] * $arr['qty']."', 
													'".$val2['stock'] * $val['contenuto_um1']."', 
													NULL,
													'')
													"; */
					    $sql_insert_registro = "INSERT INTO registro_agea 
						     					VALUES('".date("Y-m-d")."', 
													'".$ddc['cli']."', 
													'f', 
													'".$item->getId()."', 
													'".$val['um_1']."', 
													'".$val['contenuto_um1'] * $arr['qty']."', 
													'".$val2['stock'] * $val['contenuto_um1']."', 
													NULL,
													'')
													";
					    $count = $this->registry->db->exec($sql_insert_registro);
                        $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                                    VALUES('".date("Y-m-d H:i:s")."', 
                                        '[AGEA]', 
                                        'SCARICO: ".$arr['qty']." x ".$item->getId()." (".pg_escape_string($item->getName()).")', 
                                        '".$_SESSION['username']."')";
                        $count_log = $this->registry->db->exec($sql_log);
				    }

				    else {
				    /*	$sql_update_registro = "UPDATE registro_agea SET quantita = quantita + ".$val['contenuto_um1'] * $arr['qty']." 
									WHERE data = '".date("Y-m-d")."' 
									AND carico = 'f' 
									AND id_tipologia = '".$item->getCategory()."'";
					    $sql_update_giacenza = "UPDATE registro_agea SET giacenza = '".$val2['stock'] * $val['contenuto_um1']."' 
									WHERE data = '".date("Y-m-d")."' 
									AND id_tipologia = '".$item->getCategory()."'"; */
                        $sql_update_registro = "UPDATE registro_agea SET quantita = quantita + ".$val['contenuto_um1'] * $arr['qty']." 
                                       WHERE data = '".date("Y-m-d")."' 
                                        AND carico = 'f' 
                                        AND id_tipologia = '".$item->getId()."'";
                        $sql_update_giacenza = "UPDATE registro_agea SET giacenza = '".$val2['stock'] * $val['contenuto_um1']."' 
                                        WHERE data = '".date("Y-m-d")."' 
                                        AND id_tipologia = '".$item->getId()."'";
					    $count = $this->registry->db->exec($sql_update_registro);
					    $count = $this->registry->db->exec($sql_update_giacenza);

                        $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                                VALUES('".date("Y-m-d H:i:s")."', 
                                '[AGEA]', 
                                'SCARICO: ".$arr['qty']." x ".$item->getId()." (".pg_escape_string($item->getName()).")', 
                                '".$_SESSION['username']."')";
                        $count_log = $this->registry->db->exec($sql_log);
				    }
			    }
            }

            // Se tutte le operazion sono andate fin qui a buon fine, esegui la transazione
            $this->registry->db->exec("COMMIT");

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CASSA]', 
                            'Stampa scontrino ".$idscontrino." (cliente: ".$_SESSION['tessera'].")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }

        catch(PDOException $e) {
            $this->registry->template->errorestampa = 1;
            $this->registry->template->show('cassa_main');

            $this->registry->db->exec("ROLLBACK");

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                                    '[SCONTRINI]', 
                                    'Stampa scontrino '".$idscontrino."' fallita.', 
                                    '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);

            return;
        }

		// Controllo sulle quantita` a stock delle varie tipologie: se sotto una certa quota,
		// parte la notifica per chi gestisce il backend/admin
		$sql_count = "SELECT tipologie.*, SUM(barcodes.stock) as new_stock FROM tipologie
                        INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia
                        GROUP BY tipologie.id_tipologia, 
                                 tipologie.categoria, 
                                 tipologie.descrizione_tipologia, 
                                 tipologie.warning_qta_minima, 
                                 tipologie.danger_qta_minima, 
                                 tipologie.punti,
                                 tipologie.eta_min,
                                 tipologie.eta_max";
        $ris = $this->registry->db->query($sql_count);

		foreach($ris as $key=>$val) {
			if($val['new_stock'] <= $val['warning_qta_minima'] && $val['new_stock'] > $val['danger_qta_minima']) {
            	$msg = "Il livello di <strong>riordino stock</strong> per <strong>".ucwords($val['descrizione_tipologia'])."</strong> &egrave; stato raggiunto.";

            	$sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('riordino', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
        		$count = $this->registry->db->exec($sql);
        	}

        	else if($val['new_stock'] <= $val['danger_qta_minima'] ) {
            	$msg = "Il livello minimo di stock per <strong>".ucwords($val['descrizione_tipologia'])."</strong> &egrave; stato raggiunto: <strong>scorte in esaurimento!</strong>.";

            	$sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('critico', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
        		$count = $this->registry->db->exec($sql);
        	}

		}

        // Controllo se un singolo prodotto è andato in negativo in magazzino. Nel caso, 
        // mando una notifica al backend
        $sql_barcode_negativo = $this->registry->db->query("SELECT stock FROM barcodes WHERE barcode = '".$item->getId()."'");
        $val_stock_barcode = $sql_barcode_negativo->fetch();
        if($val_stock_barcode['stock'] < 0) {
            $msg = "Il prodotto <strong>".pg_escape_string($item->getName())."</strong> (".$item->getId().") ha una quantit&agrave; a stock negativa: verificare il magazzino!";

            $sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('negativo', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
            $count = $this->registry->db->exec($sql);
        }
        else if($val_stock_barcode['stock'] == 0) {
            $msg = "Il prodotto <strong>".pg_escape_string($item->getName())."</strong> (".$item->getId().") &egrave; <strong>esaurito</strong>!";

            $sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('esaurito', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
            $count = $this->registry->db->exec($sql);
        }

		// Vai alla stampa diretta dello scontrino
		$this->registry->template->show_noheader('scontrino');
	}

    /**
     * ristampa()
     *
     * Ristampa l'ultimo scontrino di un dato cliente, senza scrivere i dati della spesa
     * nel database
     */
	public function ristampa() {
		$sql_scontrino = "SELECT * FROM scontrini WHERE codice_fiscale = '".$_SESSION['tessera']."' ORDER BY scontrini.id_scontrino DESC LIMIT 1";
		$ris = $this->registry->db->query($sql_scontrino);

		$val = $ris->fetch();
		$this->registry->template->data_scontrino = $val['data'];
		$this->registry->template->scontrino = $val['id_scontrino'];
		$this->registry->template->totale_spesa = $val['totale_punti'];

		$sql_spesa = "SELECT spesa.*, barcodes.descrizione, barcodes.agea FROM spesa 
						INNER JOIN barcodes ON spesa.barcode = barcodes.barcode
						WHERE id_scontrino = '".$val['id_scontrino']."'";
		$this->registry->template->spesa = $this->registry->db->query($sql_spesa);
		
		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[CASSA]', 
                            'Ristampa scontrino ".$val['id_scontrino']." (cliente: ".$_SESSION['tessera'].")', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

        $this->registry->template->show_noheader('scontrino_ultimo');
	} 

    /**
     * storico()
     *
     * Visualizza lo storico degli scontrini per il mese corrente
     */
	public function storico() {
        if(isset($_SESSION['tessera'])) {
            $and_tessera = " AND scontrini.codice_fiscale = '".$_SESSION['tessera']."' ";

            $sql_cliente = "SELECT DISTINCT famiglie.cognome, famiglie.nome FROM famiglie 
                            LEFT JOIN scontrini ON famiglie.codice_fiscale = scontrini.codice_fiscale
                            WHERE famiglie.codice_fiscale = '".$_SESSION['tessera']."'";
            $ris = $this->registry->db->query($sql_cliente);
            $val = $ris->fetch();
            $this->registry->template->cliente = ucwords($val['cognome'])." ".ucwords($val['nome']);
        }
        else {
            $and_tessera = "";
        }

		$sql = "SELECT scontrini.*, famiglie.cognome, famiglie.nome, SUM(spesa.qta) as num_articoli FROM scontrini 
                INNER JOIN famiglie ON famiglie.codice_fiscale = scontrini.codice_fiscale 
                INNER JOIN spesa ON spesa.id_scontrino = scontrini.id_scontrino
                WHERE 1=1 AND scontrini.data >= '".date("Y-m-01 00:00:00")."' 
                ".$and_tessera."
				GROUP BY scontrini.id_scontrino, famiglie.cognome, famiglie.nome
                ORDER BY scontrini.data DESC";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show('scontrini_storico');
	}

    /**
     * stampastorico()
     * @param string $id
     *
     * Ristampa uno scontrino dallo storico
     */
	public function stampastorico($id = '') {
		$sql_scontrino = "SELECT * FROM scontrini WHERE id_scontrino = '".$id."'";
        $ris = $this->registry->db->query($sql_scontrino);

        $val = $ris->fetch();
        $this->registry->template->data_scontrino = $val['data'];
        $this->registry->template->scontrino = $val['id_scontrino'];
        $this->registry->template->totale_spesa = $val['totale_punti'];
        $this->registry->template->cliente = $val['codice_fiscale'];

        $sql_scadenza = $this->registry->db->query("SELECT scadenza FROM famiglie WHERE codice_fiscale = '".$val['codice_fiscale']."'");
        $val_scadenza = $sql_scadenza->fetch();
        $this->registry->template->scadenza = $val_scadenza['scadenza'];

        $sql_spesa = "SELECT spesa.*, barcodes.descrizione, barcodes.agea FROM spesa 
                        INNER JOIN barcodes ON spesa.barcode = barcodes.barcode
                        WHERE id_scontrino = '".$val['id_scontrino']."'";
        $this->registry->template->spesa = $this->registry->db->query($sql_spesa);

        $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[SCONTRINI]', 
                            'Stampa scontrino ".$val['id_scontrino']." (da storico)', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

        $this->registry->template->show_noheader('scontrino_stampastorico');
	}

    /**
     * cerca()
     *
     * Cerca uno scontrino per data o per famiglia
     */
	public function cerca() {
		$sql = "SELECT scontrini.*, famiglie.cognome, famiglie.nome, SUM(spesa.qta) as num_articoli FROM scontrini 
				INNER JOIN famiglie ON famiglie.codice_fiscale = scontrini.codice_fiscale 
				INNER JOIN spesa ON spesa.id_scontrino = scontrini.id_scontrino
				WHERE 1=1"; 
		if(!empty($_REQUEST['date-start'])) {
            $_REQUEST['date-start'] = str_replace('/', '-', $_REQUEST['date-start']);
			$sql .= "	AND scontrini.data >= '".date("Y-m-d 00:00:00", strtotime($_REQUEST['date-start']))."'";
		}

		if(!empty($_REQUEST['date-end'])) {
            $_REQUEST['date-end'] = str_replace('/', '-', $_REQUEST['date-end']);
			$sql .= " AND scontrini.data <= '".date("Y-m-d 23:59:59", strtotime($_REQUEST['date-end']))."'";
		}

		if(!empty($_REQUEST['tessera_famiglia'])) {
			$sql .= " AND scontrini.codice_fiscale = '".$_REQUEST['tessera_famiglia']."'";
		}

		$sql .= "GROUP BY scontrini.id_scontrino, famiglie.cognome, famiglie.nome
				ORDER BY scontrini.data DESC";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show('scontrini_storico');
	}

    /**
     * ajax_cerca()
     *
     * Recupera il codice fiscale di un cliente dato il nome
     */
	public function ajax_cerca() {
		$res = $this->registry->db->query("SELECT codice_fiscale, cognome, nome FROM famiglie WHERE cognome LIKE '".strtolower($_REQUEST['term'])."%' ");
        $results = array();

        foreach($res as $k=>$row) {
            $row_array['codice_fiscale'] = $row['codice_fiscale'];
            $row_array['nome'] = ucwords($row['cognome'])." ".ucwords($row['nome'])."";

            array_push($results, $row_array);
            //$results[] =  array(ucwords($row['nome_comune'])." (".$row['provincia'].")", $row['cod_istat']);
        }

        echo json_encode($results);	
	}

    /** 
     * ajax_spesa()
     *
     * Visualizza gli articoli acquistati per un dato scontrino
     */
	public function ajax_spesa() {
		$spesa = '<table id="tab_spesa" class="table table-responsive table-hover table-striped">
                        <thead>
                          <tr>
                            <th width="15%">Barcode</th>
                            <th width="5%">Offerta</th>
                            <th width="20%">Descrizione</th>
                            <th width="20%">Tipologia</th>
                            <th width="7%">Punti</th>
                            <th width="7%">Qt&agrave</th>
                            <th width="10%">Totale</th>
                          </tr>
                        </thead>
                        <tbody>';

		$sql_spesa = "SELECT spesa.*, barcodes.descrizione, barcodes.agea, tipologie.descrizione_tipologia, categorie.descrizione_categoria FROM spesa
                        INNER JOIN barcodes ON barcodes.barcode = spesa.barcode 
                        INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia 
						INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
						WHERE spesa.id_scontrino = '".$_REQUEST['id']."'
						ORDER BY barcodes.descrizione ASC";
		$ris = $this->registry->db->query($sql_spesa);

		foreach($ris as $key=>$val) {
			if($val['agea']) {
				$agea = "[AGEA]";
			}
			else { $agea = ""; }

			if($val['scontato']) {
				$scontato = "<span class='label label-warning' style='font-size:14px;'><a style='color:inherit; cursor:pointer; font-size:14px' data-toggle='tooltip' 
                            data-placement='bottom' title='Prodotto in offerta'><i class='fa fa-gift'></i></a></span>";
			}
			else {
				$scontato = "";
			}

            $spesa .= "<tr >";
            $spesa .= "<td>".$val['barcode']."</td>";
            $spesa .= "<td>".$scontato."</td>";
            $spesa .= "<td>".ucwords($val['descrizione'])." ".$agea."</td>";
            $spesa .= "<td>".ucwords($val['descrizione_tipologia'])."</td>";
            $spesa .= "<td>".$val['punti']."</td>";
            $spesa .= "<td>".$val['qta']."</td>";
            $spesa .= "<td>".$val['punti'] * $val['qta']."</td>";
            $spesa .= "</tr>";
        }

        $spesa .= "</tbody></table>";

        echo json_encode(array(
            "responseText" => $spesa,
        ));

	}

    /**
     * ajax_note()
     *
     * Visualizza le note eventualmente presenti in uno scontrino
     */
    public function ajax_note() {
        $sql = $this->registry->db->query("SELECT note FROM scontrini WHERE id_scontrino = '".$_REQUEST['id']."'");
        $val = $sql->fetch();

        echo $val['note'];
    }

    /**
     * annulla()
     *
     * Annulla uno scontrino per un dato cliente, restituendogli i punti e ricaricando le quantita`
     * corrette a stock. L'annullamento dello scontrino si puo` effettuare solo nel mese corrente
     */
	public function annulla() {
		$sql_spesa = "SELECT spesa.*, barcodes.descrizione, tipologie.descrizione_tipologia, categorie.descrizione_categoria FROM spesa
                        INNER JOIN barcodes ON barcodes.barcode = spesa.barcode 
                        INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia 
                        INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
                        WHERE spesa.id_scontrino = '".$_REQUEST['fake-form-tessera']."'
                        ORDER BY barcodes.descrizione ASC";
        $ris = $this->registry->db->query($sql_spesa);

		foreach($ris as $key=>$val) {
			$sql_restore_stock = "UPDATE barcodes SET stock = stock + ".$val['qta']." WHERE barcode = '".$val['barcode']."'";
			$count = $this->registry->db->exec($sql_restore_stock);
		}

		$sql_punti = $this->registry->db->query("SELECT * FROM scontrini WHERE id_scontrino = '".$_REQUEST['fake-form-tessera']."'");
		$val2 = $sql_punti->fetch();

		$sql_restore_punti = "UPDATE famiglie SET punti_residui = punti_residui + ".$val2['totale_punti']." WHERE codice_fiscale = '".$val2['codice_fiscale']."'";
		$count = $this->registry->db->exec($sql_restore_punti);

		$sql_delete_scontrino = "DELETE FROM scontrini WHERE id_scontrino = '".$_REQUEST['fake-form-tessera']."'";
		$count = $this->registry->db->exec($sql_delete_scontrino);

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[CASSA]', 
                            'Scontrino ANNULLATO: ".$_REQUEST['fake-form-tessera']." (cliente: ".$val2['codice_fiscale'].")', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

		header("Location: /scontrini/storico");
	}

}

?>
