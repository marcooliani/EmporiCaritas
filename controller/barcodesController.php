<?php
session_start();

class barcodesController extends BaseController {

    /**
     * index()
     *
     * Mostra l'elenco dei prodotti (o meglio, delle tipologie), la disponibilità a magazzino degli stessi 
     * e le soglie di allerta in caso di esaurimento scorte
     */
    public function index() {
        $sql = "SELECT tipologie.*, categorie.*, SUM(barcodes.stock) AS stock FROM tipologie 
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                GROUP BY tipologie.id_tipologia, 
						tipologie.categoria, 
						tipologie.descrizione_tipologia, 
						tipologie.warning_qta_minima, 
						tipologie.danger_qta_minima, 
						tipologie.punti,
                        tipologie.eta_min, 
                        tipologie.eta_max, 
						categorie.id_categoria, 
						categorie.descrizione_categoria, 
						categorie.limite_spesa_max, 
						categorie.limite_mese_max,
                        categorie.tipo_um
                ORDER BY descrizione_tipologia ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);
        $this->registry->template->show('barcodes');
    }

    /**
     * lista()
     *
     * Alias della funzione precedente
     */
    public function lista() {
        $sql = "SELECT tipologie.*, categorie.*, SUM(barcodes.stock) AS totale FROM tipologie 
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                GROUP BY tipologie.id_tipologia, 
						tipologie.categoria, 
						tipologie.descrizione_tipologia, 
						tipologie.warning_qta_minima, 
						tipologie.danger_qta_minima, 
						tipologie.punti, 
                        tipologie.eta_min,
                        tipologie.eta_max, 
						categorie.id_categoria, 
						categorie.descrizione_categoria, 
						categorie.limite_spesa_max, 
						categorie.limite_mese_max,
                        categorie.tipo_um
                ORDER BY descrizione_tipologia ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);
        $this->registry->template->show('barcodes');
    }

    /**
     * nuovo()
     *
     * Visualizza la pagina per l'inserimento di nuovi codici a barre
     */
    public function nuovo() {
        $this->registry->template->sql_categorie = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");
        $this->registry->template->sql_um = $this->registry->db->query("SELECT * FROM um");
        $this->registry->template->sql_um_1 = $this->registry->db->query("SELECT * FROM um");
        $this->registry->template->sql_donatori = $this->registry->db->query("SELECT * FROM fornitori");

        $this->registry->template->show('nuovobarcode');
    }

	/**
     * ajax_check_barcode()
     *
	 * Verifica se un prodotto e` gia` presente nel database. Se lo e`, riporta tutti
	 * i dati nella pagina di inserimento, disabilitando tutti i campi tranne quelli
	 * strettamente necessari per lo stock a magazzino e le statistiche
	 */
    public function ajax_check_barcode() {

        $sql = "SELECT barcodes.*, tipologie.descrizione_tipologia, categorie.id_categoria, categorie.descrizione_categoria FROM barcodes 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
                WHERE barcodes.barcode LIKE '".$_REQUEST['term']."%'";
        $res = $this->registry->db->query($sql);
        $results = array();

        foreach($res as $k=>$row) {
            $row_array['barcode'] = $row['barcode'];
            $row_array['nome'] = ucwords($row['descrizione']);
            $row_array['id_tipologia'] = $row['tipologia'];
            $row_array['descrizione_tipologia'] = ucwords($row['descrizione_tipologia']);
            $row_array['id_categoria'] = $row['id_categoria'];
            $row_array['descrizione_categoria'] = ucwords($row['descrizione_categoria']);
            $row_array['um_1'] = $row['um_1'];
            $row_array['contenuto_um1'] = $row['contenuto_um1'];
            $row_array['um_stock'] = $row['um_stock'];
            $row_array['stock'] = $row['stock'];
            $row_array['agea'] = $row['agea'];
            $row_array['classificato'] = $row['classificato'];
            $row_array['acquistato'] = $row['acquistato'];

            array_push($results, $row_array);
        }

        echo json_encode($results);
    }

    /**
     * inserisci()
     *
     * Inserisce fisicamente nel database i dati provenienti dalla pagina dei nuovi codici a barre
     */
    public function inserisci() {
        
        // Prodotto gia` esistente a magazzino
        if(isset($_REQUEST['doppione']) && $_REQUEST['doppione'] == "update_stock") {
            $sql = "UPDATE barcodes SET stock = stock + ".$_REQUEST['stock']." WHERE barcode = '".$_REQUEST['barcode']."'";
            $count = $this->registry->db->exec($sql);

			// Controllo se si tratta di un prodotto AGEA
			if($_REQUEST['agea'] == 't') {

				// Uniformo le unità di misura tra i vari prodotti/barcode
				if($_REQUEST['um1'] == "g") {
                    $_REQUEST['um1'] = "kg";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 1000;
                }

                if($_REQUEST['um1'] == "cl") {
                    $_REQUEST['um1'] = "l";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 100;
                }

                if($_REQUEST['um1'] == "ml") {
                    $_REQUEST['um1'] = "l";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 1000;
                }

                if($_REQUEST['um1'] == "cm") {
                    $_REQUEST['um1'] = "m";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 100;
                }

                if($_REQUEST['um1'] == "mm") {
                    $_REQUEST['um1'] = "m";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 1000;
                }

                if($_REQUEST['um1'] == "pz") {
                    $_REQUEST['contenuto_um1'] = 1;
                }

				// Recupero la quantità a stock (in pezzi) dalla tabella barcode 
				// e ne ricavo la giacenza per unità di misura
                /* $sql_stock = $this->registry->db->query("SELECT SUM(barcodes.stock) AS stock FROM barcodes
														 	INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
														 	INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
														 	WHERE categorie.id_categoria = '".$_REQUEST['categoria']."' AND agea = 't'"); */
                $sql_stock = $this->registry->db->query("SELECT stock FROM barcodes WHERE barcode = '".$_REQUEST['barcode']."' AND agea = 't'");
                $val2 = $sql_stock->fetch();
					
				// il "-$_REQUEST['stock']" è necessario perchè a questo punto ho già aggiornato
				// lo stock nella tabella dei barcode e non tornerebbero altrimenti i conti
				$val_giacenza = $val2['stock'] * $_REQUEST['contenuto_um1'];

				// Verifico se è già stato effettuato un carico per la giornata corrente. Se sì,
				// aggiorno il registro con la quantità caricata e la giacenza, altrimenti
				// crea un nuovo record nella tabella
				/* $sql_check_data = "SELECT EXISTS (SELECT 1 FROM registro_agea 
                                        WHERE data = '".date("Y-m-d")."' 
                                        AND carico = 't' 
                                        AND id_tipologia = '".$_REQUEST['categoria']."')"; */
				$sql_check_data = "SELECT EXISTS (SELECT 1 FROM registro_agea WHERE data = '".date("Y-m-d")."' AND carico = 't' AND id_tipologia = '".$_REQUEST['barcode']."')";
				$ris_check = $this->registry->db->query($sql_check_data);
				$val_ris = $ris_check->fetch();
			
				if($val_ris['exists']) {
				/*	$sql_agea = "UPDATE registro_agea 
								SET quantita = quantita + ".$_REQUEST['stock'] *  $_REQUEST['contenuto_um1']." 
								WHERE data = '".date("Y-m-d")."' 
								AND id_tipologia = '".$_REQUEST['categoria']."' 
								AND carico = 't'"; */
					$sql_agea = "UPDATE registro_agea 
								SET quantita = quantita + ".$_REQUEST['stock'] *  $_REQUEST['contenuto_um1']." 
								WHERE data = '".date("Y-m-d")."' 
								AND id_tipologia = '".$_REQUEST['barcode']."' 
								AND carico = 't'";
                	$count_agea = $this->registry->db->exec($sql_agea);

				/*  $sql_agea_giacenza = "UPDATE registro_agea
											SET giacenza = '".$val_giacenza."'
											WHERE data = '".date("Y-m-d")."'
											AND id_tipologia = '".$_REQUEST['categoria']."'"; */
					$sql_agea_giacenza = "UPDATE registro_agea
											SET giacenza = '".$val_giacenza."'
											WHERE data = '".date("Y-m-d")."'
											AND id_tipologia = '".$_REQUEST['barcode']."'";
					$count_agea_giacenza = $this->registry->db->exec($sql_agea_giacenza);
				}
				else {
					// Genero il numero di attestato di consegna, che corrisponde al numero di
					// giorni in cui c'è stato almeno un carico AGEA. Per farlo conto le date
					// uniche che sono inferiori alla data corrente e aggiungo 1 per ottenere
					// il numero corretto di documento
					/* $sql_ddc = $this->registry->db->query("SELECT COUNT(DISTINCT data) AS ddc_carico FROM registro_agea WHERE carico = 't' AND data < '".date("Y-m-d")."'");
                	$val_ddc = $sql_ddc->fetch();
					$val_ddc['ddc_carico']++; */

					/* $sql_agea = "INSERT INTO registro_agea 
                             	VALUES('".$_REQUEST['data_donazione']."', 
                                    '".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                                    't', 
                                    '".$_REQUEST['categoria']."', 
                                    '".$_REQUEST['um1']."', 
                                    '".$_REQUEST['stock'] * $_REQUEST['contenuto_um1']."', 
                                    '".$val_giacenza."', 
                                    NULL,
                                    ''
                                )"; */
					$sql_agea = "INSERT INTO registro_agea 
                             	VALUES('".$_REQUEST['data_donazione']."', 
                                    '".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                                    't', 
                                    '".$_REQUEST['barcode']."', 
                                    '".$_REQUEST['um1']."', 
                                    '".$_REQUEST['stock'] * $_REQUEST['contenuto_um1']."', 
                                    '".$val_giacenza."', 
                                    NULL,
                                    ''
                                )"; 
                	$count_agea = $this->registry->db->exec($sql_agea);

                    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[AGEA]', 
                            'CARICO: ".$_REQUEST['stock']." x ".$_REQUEST['barcode']." (".pg_escape_string($_REQUEST['descrizione']).") - Doc. Cons. ".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                            '".$_SESSION['username']."')";
                    $count_log = $this->registry->db->exec($sql_log);
				}
            }
        }

        // Prodotto non presente in magazzino
        else {
            $sql = "INSERT INTO barcodes 
                    VALUES('".$_REQUEST['barcode']."', '".$_REQUEST['id_tipologia']."', '".pg_escape_string($_REQUEST['descrizione'])."', 
                    '".$_REQUEST['um1']."', '".$_REQUEST['um1']."', '".$_REQUEST['contenuto_um1']."', '".$_REQUEST['contenuto_um1']."', 
                    '".$_REQUEST['agea']."', '".$_REQUEST['classificato']."', '".$_REQUEST['um_stock']."', '".$_REQUEST['stock']."')";
            $count = $this->registry->db->exec($sql);
		
			if($_REQUEST['agea'] == 't') {

				if($_REQUEST['um1'] == "g") {
                    $_REQUEST['um1'] = "kg";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 1000;
                }

                if($_REQUEST['um1'] == "cl") {
                    $_REQUEST['um1'] = "l";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 100;
                }

                if($REQUEST['um_1'] == "ml") {
                    $_REQUEST['um1'] = "l";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 1000;
                }

                if($_REQUEST['um1'] == "cm") {
                    $_REQUEST['um1'] = "m";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 100;
                }

                if($_REQUEST['um1'] == "mm") {
                    $_REQUEST['um1'] = "m";
                    $_REQUEST['contenuto_um1'] = $_REQUEST['contenuto_um1'] / 1000;
                }

                if($_REQUEST['um1'] == "pz") {
                    $_REQUEST['contenuto_um1'] = 1;
                }

                /* $sql_stock = $this->registry->db->query("SELECT SUM(barcodes.stock) AS stock FROM barcodes
														 INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
														 INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
														 WHERE categorie.id_categoria = '".$_REQUEST['categoria']."' AND agea = 't'"); */
                $sql_stock = $this->registry->db->query("SELECT stock FROM barcodes WHERE barcode = '".$_REQUEST['barcode']."' AND agea = 't'");
                $val2 = $sql_stock->fetch();

				/* $sql_ddc = $this->registry->db->query("SELECT COUNT(DISTINCT data) AS ddc_carico FROM registro_agea WHERE carico = 't' AND data < '".date("Y-m-d")."'");
				$val_ddc = $sql_ddc->fetch();
				$val_ddc['ddc_carico']++; */

                /* $sql_agea = "INSERT INTO registro_agea 
                             VALUES('".date('Y-m-d')."', 
                                    '".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                                    't', 
                                    '".$_REQUEST['categoria']."', 
                                    '".$_REQUEST['um1']."', 
                                    '".$_REQUEST['contenuto_um1'] * $_REQUEST['stock']."', 
                                    '".$val2['stock'] * $_REQUEST['contenuto_um1']."', 
                                    NULL,
									''
								)";	*/
                $sql_agea = "INSERT INTO registro_agea 
                             VALUES('".date('Y-m-d')."', 
                                    '".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                                    't', 
                                    '".$_REQUEST['barcode']."', 
                                    '".$_REQUEST['um1']."', 
                                    '".$_REQUEST['contenuto_um1'] * $_REQUEST['stock']."', 
                                    '".$val2['stock'] * $_REQUEST['contenuto_um1']."', 
                                    NULL,
									''
								)";	
				$count_agea = $this->registry->db->exec($sql_agea);

                $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[AGEA]', 
                            'CARICO: ".$_REQUEST['stock']." x ".$_REQUEST['barcode']." (".pg_escape_string($_REQUEST['descrizione']).") - Doc. Cons. ".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                            '".$_SESSION['username']."')";
                $count_log = $this->registry->db->exec($sql_log);
			}
        }

        if($count > 0) {
            $this->registry->template->insertok = 1;
        }
        else {
            $this->registry->template->insertok = 0;
        }

        // Inserisco la transazione nel registro delle donazioni/acquisti
        $sql_transazione = "INSERT INTO donazioni(id_fornitore, barcode, quantita, data_donazione, acquistato, ddt, num_lotto)
                            VALUES('".$_REQUEST['fornitore']."', '".$_REQUEST['barcode']."', '".$_REQUEST['stock']."', 
                                '".$_REQUEST['data_donazione']."', '".$_REQUEST['acquistato']."', '".strtolower(pg_escape_string($_REQUEST['ddt']))."',
                                '".strtolower(pg_escape_string($_REQUEST['lotto']))."')";
        $count = $this->registry->db->exec($sql_transazione);

        if($count > 0) {
            $this->registry->template->insertok_2 = 1;
        }
        else {
            $this->registry->template->insertok_2 = 0;
        }

        $sql_lotti = "INSERT INTO lotti VALUES('".strtolower(pg_escape_string($_REQUEST['lotto']))."', '".$_REQUEST['barcode']."', '".$_REQUEST['scadenza']."', '".$_REQUEST['stock']."')";
        $count = $this->registry->db->exec($sql_lotti);

        if($count > 0) {
            $this->registry->template->insertok_3 = 1;
        }
        else {
            $this->registry->template->insertok_3 = 0;
        }

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[DONAZIONE/ACQUISTO]', 
                            '".$_REQUEST['stock']." x ".$_REQUEST['barcode']." (".pg_escape_string($_REQUEST['descrizione']).") - Doc. Cons. ".strtolower(pg_escape_string($_REQUEST['ddt']))."', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

        $this->nuovo();
    }

	/**
     * cerca()
     *
	 * Cerca singolo prodotto sulla base del proprio barcode o della descrizione associata
	 */
    public function cerca($param = '') {
        $cond = "";
        if(!empty($_REQUEST['cerca_barcode'])) {
            $cond .= "AND barcodes.barcode = '".pg_escape_string($_REQUEST['cerca_barcode'])."' ";
            $this->registry->template->cerca_barcode = pg_escape_string($_REQUEST['cerca_barcode']);
        }

        if(!empty($_REQUEST['cerca_nome'])) {
            $cond .= "AND LOWER(barcodes.descrizione) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_nome']))."%' ";
            $this->registry->template->cerca_nome = pg_escape_string($_REQUEST['cerca_nome']);
        }

        if(isset($_REQUEST['agea'])) {
            $cond .= "AND barcodes.agea = 't' ";   
            $this->registry->template->agea = 't';
        }

        if(isset($_REQUEST['stock_negativo']) || $param == "stock_negativo") {
            $cond .= "AND barcodes.stock < '0' ";
            $this->registry->template->stock_negativo = 't';
        }

        if(isset($_REQUEST['stock_esaurito']) || $param == "stock_esaurito") {
            $cond .= "AND barcodes.stock = '0' ";
            $this->registry->template->stock_esaurito = 't';
        }

		if(isset($_REQUEST['riordino']) || $param == 'riordino') {
            $having="HAVING SUM(barcodes.stock) <= tipologie.warning_qta_minima AND SUM(barcodes.stock) > tipologie.danger_qta_minima ";
		}

		if(isset($_REQUEST['esaurimento']) || $param == 'critico') {
            $having="HAVING SUM(barcodes.stock) <= tipologie.danger_qta_minima ";
		}

        $sql = "SELECT tipologie.*, categorie.*, SUM(barcodes.stock) AS stock FROM tipologie 
                INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
                INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                WHERE 1=1 ".$cond."
                GROUP BY tipologie.id_tipologia, 
						tipologie.categoria, 
						tipologie.descrizione_tipologia, 
						tipologie.warning_qta_minima, 
						tipologie.danger_qta_minima, 
						tipologie.punti,
                        tipologie.eta_min,
                        tipologie.eta_max, 
						categorie.id_categoria, 
						categorie.descrizione_categoria, 
						categorie.limite_spesa_max, 
						categorie.limite_mese_max,
                        categorie.tipo_um 
				".$having."
                ORDER BY descrizione_tipologia ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->cerca = 1;
        $this->registry->template->show('barcodes');
    }

    /**
     * ajax_barcode()
     * @return array
     * 
     * Carica via AJAX l'elenco dei codici a barre associati a una data tipologia di prodotto
     */
    public function ajax_barcodes() {
        $sql_ajax = "SELECT * FROM barcodes WHERE 1=1 ";

        // Questi if servono per mostrare i prodotti in base ai parametri di ricerca
        if(!empty($_REQUEST['id']))
            $sql_ajax .= "AND tipologia = '".$_REQUEST['id']."' ";
        if(!empty($_REQUEST['cerca_barcode']))
            $sql_ajax .= "AND barcode = '".$_REQUEST['cerca_barcode']."' ";
        if(!empty($_REQUEST['cerca_nome']))
            $sql_ajax .= "AND LOWER(descrizione) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_nome']))."%' ";
        if(!empty($_REQUEST['agea']))
            $sql_ajax .= "AND agea = '".$_REQUEST['agea']."' ";
        if(!empty($_REQUEST['stock_negativo']))
            $sql_ajax .= "AND stock < 0 ";
        if(!empty($_REQUEST['stock_esaurito']))
            $sql_ajax .= "AND stock = 0 ";
        $sql_ajax .= "ORDER BY descrizione ASC";

        $sql = $this->registry->db->query($sql_ajax);

        $barcodes = "<table id='tab_barcodes' class='table table-responsive table-hover table-striped'>
                        <thead>
                          <tr>
                            <th width='12%'>Barcode</th>
                            <th width='5%'>Offerta</th>
                            <th width='25%'>Descrizione</th>
                            <th width='10%'>Formato</th>
                            <th width='10%'>Stock (Pz.)</th>
                            <th width='5%'>Lotti</th>
                            <th width='5%'>AGEA</th>
                            <th width='5%'>Cl. Uff.</th>
                            <th width='10%'>&nbsp;</th>
                          </tr>
                        </thead>
                    <tbody>";

        foreach($sql as $key=>$val) {
            if($val['agea'] == "t") {
                $agea = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
            }
            else {
                $agea = "<span class='label label-danger'><i class='fa fa-close'></i> </span>";
            }

            if($val['classificato'] == "t") {
                $classificato = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
            }
            else {
                $classificato = "<span class='label label-danger'><i class='fa fa-close'></i> </span>";
            }

            if($val['acquistato'] == "t") {
                $acquistato = "<span class='label label-success'><i class='fa fa-check'></i> </span>";
            }
            else {
                $acquistato = "<span class='label label-danger'><i class='fa fa-close'></i> </span>";
            }

            // Verifico se il prodotto e` in offerta: se lo e`, mostro un'icona a fianco del prodotto stesso
			$sql_offerta = "SELECT * FROM offerte_sconti WHERE barcode = '".$val['barcode']."' AND offerta_a >= now()";
			$ris_offerta = $this->registry->db->query($sql_offerta);

			if($ris_offerta->rowCount() != 0) {
				$offerta = "<span class='label label-success' style='font-size:14px;'><a style='color:inherit; cursor:pointer; font-size:14px' data-toggle='tooltip' 
                            data-placement='bottom' title='Prodotto in offerta'><i class='fa fa-gift'></i></a></span>";
				$check_offerta = 1;
			}
			else {
				$offerta = "";
				$check_offerta = 0;
			}

            // Conto il numero di lotti registrati per il barcode in questione
            $sql_lotti = "SELECT COUNT(*) AS tot_lotti FROM lotti WHERE barcode = '".$val['barcode']."'";
            $ris_lotti = $this->registry->db->query($sql_lotti);
            $val_lotti = $ris_lotti->fetch();

            if($val_lotti['tot_lotti'] == 0) { 
                $val_lotti['tot_lotti'] = '';
                $inscadenza = $val_lotti['tot_lotti']; 
            }
            else {
                // Controllo se c'è un lotto in scadenza per il barcode selezionato
                $sql_scadenza = "SELECT DISTINCT scadenza FROM lotti 
                                    WHERE barcode = '".$val['barcode']."'
                                    AND scadenza <= '".date("Y-m-d", strtotime(date("Y-m-d")."+ 7 days"))."'";
                $sql_scaduti = "SELECT DISTINCT scadenza FROM lotti 
                                    WHERE barcode = '".$val['barcode']."'
                                    AND scadenza < '".date("Y-m-d")."'";
                $ris_scadenza = $this->registry->db->query($sql_scadenza);
                $ris_scaduti = $this->registry->db->query($sql_scaduti);

                if($ris_scadenza->rowCount() > 0 || $ris_scaduti->rowCount() > 0) {
                    $inscadenza = "<span class='label label-warning' style='font-size:12px;'><a style='color:inherit; cursor:pointer; font-size:12px' data-toggle='tooltip' 
                                data-placement='bottom' title='";
                    if($ris_scadenza->rowCount() > 0) {
                        $inscadenza .= $ris_scadenza->rowCount()." lotti in scadenza\n";
                    }
                    if($ris_scaduti->rowCount() > 0) {
                        $inscadenza .= $ris_scaduti->rowCount()." lotti scaduti";
                    }

                    $inscadenza .= "'><i class='fa fa-exclamation'></i> </a></span>";
                }
            }
            

            // Verifico la quantita` a stock del singolo prodotto: se a zero o negativo,
            // la riga corrispondente nella tabella apparira` rossa
            if($val['stock'] <= 0) {
                $alert_riga = "danger";
            }
            else {
                $alert_riga = "";
            }

            $barcodes .= "<tr class='".$alert_riga."'>
                    <td>".$val['barcode']."</td>
                    <td>".$offerta."</td>
                    <td>".$val['descrizione']."</td>
                    <td>".$val['um_1']." ".$val['contenuto_um1']."</td>
                    <td><span id='ajax_qta'>".$val['stock']."</span></td>
                    <td>".$val_lotti['tot_lotti']." ".$inscadenza."</td>
                    <td>".$agea."</td>
                    <td>".$classificato."</td>
                    <td style='text-align:right'>
                      <div class=\"btn-group\">
                        <button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                            <i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
                        </button>
                        <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                            <li><a href='' form-action='/barcodes/ajax_adegua_qta' class=\"adegua_qta\" data-toggle=\"modal\" 
                                data-target=\"#myModal_m\" qta='".$val['stock']."' name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class='fa fa-refresh fa-fw' >&nbsp;</span> Adegua q.t&agrave;</a>
                            </li>
                            <li><a href='' form-action='/barcodes/modifica/".$val['barcode']."' class='modify_barcode' 
                                 name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class='fa fa-edit fa-fw' >&nbsp;</span> Modifica</a>
                            </li>
                            <li><a href='' form-action='/barcodes/lotti/".$val['barcode']."' class='lotti' 
                                 name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class='fa fa-edit fa-fw' >&nbsp;</span> Visualizza / Modifica lotti</a>
                            </li>";
				if($check_offerta == 0) {
             		$barcodes .= "<li><a href='' form-action='/offerte/nuovo/".$val['barcode']."' class='offer_barcode' 
                                 name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class='fa fa-gift fa-fw' >&nbsp;</span> Nuova offerta</a>
                            </li>";
				}
				else {
					$barcodes .= "<li><a href='' form-action='/offerte/cerca/".$val['barcode']."' class='view_offer_barcode' 
                                 name=\"".$val['barcode']."\" id=\"".$val['barcode']."\">
                                <span class='fa fa-gift fa-fw' >&nbsp;</span> Vedi offerta</a>
                            </li>";
				}
                $barcodes .= "</ul>
                      </div>
                    </td>
                </tr>";
        }

        $barcodes .= "</tbody>
        </table>";

        echo json_encode(array(
            "responseText1" => $barcodes,
        ));
    }

	/**
     * modifica()
     * @param string $barcode
     *
	 * Modifica e aggiorna i dati di un singolo prodotto
	 */
    public function modifica($barcode = '') {
        $sql = "SELECT barcodes.*, tipologie.descrizione_tipologia, tipologie.categoria FROM barcodes 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia 
                WHERE barcode = '".$barcode."'";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->barcode = $barcode;

        $this->registry->template->sql_categorie = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");
        $this->registry->template->sql_um = $this->registry->db->query("SELECT val_um FROM um WHERE tipo = 
                    (SELECT tipo_um FROM categorie WHERE id_categoria = 
                    (SELECT categoria FROM tipologie 
                        INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia 
                        WHERE barcode = '".$barcode."'))");
        $this->registry->template->sql_um_1 = $this->registry->db->query("SELECT * FROM um");

        $this->registry->template->show("modificabarcode");
    }

    /**
     * update_barcode()
     * @param string $barcode
     *
     * Effettua l'update vero e proprio
     */
    public function update_barcode($barcode = '') {
        $sql = "UPDATE barcodes SET (barcode, tipologia, descrizione, um_1, contenuto_um1, agea, classificato, um_stock, stock)  = 
                ('".$_REQUEST['barcode']."', '".$_REQUEST['id_tipologia']."', '".pg_escape_string($_REQUEST['descrizione'])."', 
                    '".$_REQUEST['um1']."', '".$_REQUEST['contenuto_um1']."', '".$_REQUEST['agea']."', '".$_REQUEST['classificato']."', 
                    '".$_REQUEST['um_stock']."', '".$_REQUEST['stock']."') 
                WHERE barcode = '".$barcode."'";
        $count = $this->registry->db->exec($sql);

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[PRODOTTI]', 
                            'Modifica prodotto ".$_REQUEST['barcode']." (".pg_escape_string($_REQUEST['descrizione']).")', 
                            '".$_SESSION['username']."')";
        $count2 = $this->registry->db->exec($sql_log);

        if($count > 0) {
            $this->registry->template->modifyok = 1;
        }
        else {
            $this->registry->template->modifyok = 0;
        }

        $this->modifica($barcode);
    }

	/**
     * ajax_adegua_qta()
     *
	 * Adegua la quantita' a stock di un singolo prodotto. E` utile in caso di pezzi
	 * danneggiati, scaduti o in caso di errori di inserimento a magazzino
	 */
    public function ajax_adegua_qta() {
        $sql = "UPDATE barcodes SET stock = '".$_REQUEST['qta']."' WHERE barcode = '".$_REQUEST['id']."'";
        $count = $this->registry->db->exec($sql);

        $sql_desc = $this->registry->db->query("SELECT descrizione FROM barcodes WHERE barcode = '".$_REQUEST['id']."'");
        $val_desc = $sql_desc->fetch();
        
		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
					VALUES('".date("Y-m-d H:i:s")."', 
							'[STOCK CORRECTION]', 
							'Prodotto ".$_REQUEST['id']." (".$val_desc['descrizione'].") da ".$_REQUEST['old_qta']." a ".$_REQUEST['qta']." - Motivazione: ".pg_escape_string($_REQUEST['motivazione'])."', 
							'".$_SESSION['username']."')";
		$count = $this->registry->db->exec($sql_log);

        // Verifico la quantita` a stock della tipologia corrispondente
		$sql_count = "SELECT tipologie.*, SUM(barcodes.stock) as new_stock FROM tipologie
						INNER JOIN barcodes ON barcodes.tipologia = tipologie.id_tipologia
						WHERE tipologie.id_tipologia = (SELECT barcodes.tipologia FROM barcodes WHERE barcodes.barcode = '".$_REQUEST['id']."')
						GROUP BY tipologie.id_tipologia, tipologie.categoria, tipologie.descrizione_tipologia, tipologie.warning_qta_minima, tipologie.danger_qta_minima, tipologie.punti";
		$ris = $this->registry->db->query($sql_count);
		$val = $ris->fetch();

		if($val['new_stock'] <= $val['warning_qta_minima'] && $val['new_stock'] > $val['danger_qta_minima']) {
			$msg = "Il livello di <strong>riordino stock</strong> per <strong>".ucwords($val['descrizione_tipologia'])."</strong> &egrave; stato raggiunto.";

			$sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('riordino', '".$msg."', '".date("Y-m-d H:i")."', false)";
			$count = $this->registry->db->exec($sql);
		}

		else if($val['new_stock'] <= $val['danger_qta_minima'] ) {
            $msg = "Il livello minimo di stock per <strong>".ucwords($val['descrizione_tipologia'])."</strong> &egrave; stato raggiunto: <strong>scorte in esaurimento!</strong>";
            
            $sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('critico', '".$msg."', '".date("Y-m-d H:i")."', false)";
			$count = $this->registry->db->exec($sql);
        }

        // Controllo se un singolo prodotto è andato esaurito o in negativo in magazzino. Nel caso, 
        // mando una notifica al backend
   /*     $sql_barcode_negativo = $this->registry->db->query("SELECT descrizione, stock FROM barcodes WHERE barcode = '".$_REQUEST['id']."'");
        $val_stock_barcode = $sql_barcode_negativo->fetch();
        if($val_stock_barcode['stock'] < 0) {
            $msg = "Il prodotto <strong>".pg_escape_string($val_stock_barcode['descrizione'])."</strong> (".$_REQUEST['id'].") ha una quantit&agrave; a stock negativa: verificare il magazzino!";

            $sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('negativo', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
            $count = $this->registry->db->exec($sql);
        }
        else if($val_stock_barcode['stock'] == 0) {
            $msg = "Il prodotto <strong>".pg_escape_string($val_stock_barcode['stock'])."</strong> (".$_REQUEST['id'].") &egrave; <strong>esaurito</strong>!";

            $sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('esaurito', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
            $count = $this->registry->db->exec($sql);
        } */


		echo json_encode($_REQUEST['qta']);
    }

    public function esporta() {
        $sql = "SELECT barcodes.*, tipologie.descrizione_tipologia FROM barcodes  
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia
                ORDER BY barcodes.descrizione ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show_noheader('xls_barcodes');
    }
}

?>
