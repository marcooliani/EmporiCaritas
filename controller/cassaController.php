<?php
session_start(); // NON CANCELLARE!!

class cassaController extends BaseController {

    /**
     * index()
     *
     * Visualizza la schermata di riconoscimento clienti e
     * resetta tutte le variabili di sessione relative
     * impostate in precedenti sessioni di cassa
     */
	public function index() {

		// Resetto tutte le variabili di sessionei della cassa tra un cliente e l'altro
		unset($_SESSION['tessera_componente']);
		unset($_SESSION['tessera']);
		unset($_SESSION['cognome']);
		unset($_SESSION['nome']);
		unset($_SESSION['componenti']);
		unset($_SESSION['cart']);
		unset($_SESSION['punti_totali']);
		unset($_SESSION['punti_residui']);
		unset($_SESSION['punti_residui_reset']);
		unset($_SESSION['totale_spesa']);
		unset($_SESSION['idscontrino']);
		unset($_SESSION['data_scontrino']);
		unset($_SESSION['notecliente']);
		unset($_SESSION['scadenza']);
		
		$this->registry->template->show("cassa_readuser");
	}
	
    /**
     * nuovo_conto()
     *
     * Viene letto il codice fiscale del cliente: se questo viene trovato
     * nel database e risulta valido, setta il carrello e le variabili di
     * sessione necessarie all'operazione di cassa in corso, visualizzando
     * poi la schermata principale della cassa
     */
	public function nuovo_conto() {

      /* Se non è già settata una sessione di cassa, procedi con il 
       * riconoscimento del cliente, altrimenti visualizza semplicemente
       * la schermata principale della cassa, tornando al carrello */
      if(!isset($_SESSION['tessera'])) {
		$sql = "SELECT famiglie.* FROM famiglie 
				LEFT JOIN componenti_famiglie ON famiglie.codice_fiscale = componenti_famiglie.capofamiglia
				WHERE famiglie.codice_fiscale = '".$_REQUEST['id_tessera']."'
				OR componenti_famiglie.c_codice_fiscale = '".$_REQUEST['id_tessera']."'";
		$ris = $this->registry->db->query($sql);

        // Cliente trovato
		if($ris->rowCount() > 0) { 
			$val = $ris->fetch();

            /* Se il cliente non risulta sospeso, viene eseguito il check sulle
             * condizioni necessarie perchè il cliente sia accettato in casssa */
			if($val['sospeso'] != 't') {

                // Controllo punti residui, escludendo i clienti esentati
				if($val['punti_residui'] <= 0 && $val['punti_residui'] != -1 ) {
                	$this->registry->template->accettato = "no";
                	$this->registry->template->punti_residui = $val['punti_residui'];
				    $this->registry->template->show("cassa_readuser");
            	}

                // Controllo scadenza tessera cliente
                else if($val['scadenza'] < date("Y-m-d")) {
                    $this->registry->template->accettato = "scaduto";
                	$this->registry->template->scadenza = date("d/m/Y", strtotime($val['scadenza']));
				    $this->registry->template->show("cassa_readuser");
                }

                // Se tutto è ok, setta le variabili di sessione e il carrello
			    else {
					if($val['codice_fiscale'] != $_REQUEST['id_tessera']) {
						$_SESSION['tessera_componente'] = $_REQUEST['id_tessera'];
					}

					$_SESSION['tessera'] = $val['codice_fiscale'];
					$_SESSION['cognome'] = $val['cognome'];
					$_SESSION['nome'] = $val['nome'];
					$_SESSION['componenti'] = $val['num_componenti'];

					if($val['esenzione'] != 't') {
						$_SESSION['punti_totali'] = $val['punti_totali'];
						$_SESSION['punti_residui'] = $val['punti_residui'];
						$_SESSION['punti_residui_reset'] = $val['punti_residui']; // Serve per il reset del carrello!
					}

					$_SESSION['totale_spesa'] = 0;
                    $_SESSION['notecliente'] = "";
                    $_SESSION['scadenza'] = $val['scadenza'];

	                // Setta il carrello
    				$cart = ShoppingCart::getInstance();
					$_SESSION['cart'] = $cart;

					$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    			VALUES('".date("Y-m-d H:i:s")."', 
                            	'[CASSA]', 
                            	'Nuova operazione di cassa (cliente ".$_SESSION['tessera'].")', 
                            	'".$_SESSION['username']."')";
        			$count = $this->registry->db->exec($sql_log);

        			$this->registry->template->show("cassa_main");
				}
			}

			else {
				$this->registry->template->sospeso = "1";
				$this->registry->template->show("cassa_readuser");
			}
		}

		else {
			$this->registry->template->no_tessera = "1";
			$this->registry->template->show("cassa_readuser");
		}
      }

      else {
        $this->registry->template->show("cassa_main");
      }
	}

    /**
     * ajax_cercaFamiglia()
     *
     * Nella schermata di accesso clienti, cerca il cliente per nome e ritorna
     * i risultati (cognome, nome e codice fiscale) all'autocomplete sulla pagina
     * per proseguire con il riconoscimento del cliente
     */
	public function ajax_cercaFamiglia() {
		$sql = "SELECT codice_fiscale, cognome, nome FROM famiglie 
				WHERE cognome LIKE '".strtolower($_REQUEST['term'])."%'";
		$sql_m = "SELECT c_codice_fiscale AS codice_fiscale, cognome, nome FROM componenti_famiglie 
				WHERE cognome LIKE '".strtolower($_REQUEST['term'])."%'";

        $res = $this->registry->db->query($sql);
        $res_m = $this->registry->db->query($sql_m);

        $results = array();

        foreach($res as $k=>$row) {
            $row_array['codice_fiscale'] = $row['codice_fiscale'];
            $row_array['nome'] = ucwords($row['cognome'])." ".ucwords($row['nome']);

            array_push($results, $row_array);
        }

        foreach($res_m as $k=>$row) {
            $row_array['codice_fiscale'] = $row['codice_fiscale'];
            $row_array['nome'] = ucwords($row['cognome'])." ".ucwords($row['nome']);

            array_push($results, $row_array);
        }

        echo json_encode($results);
    }

    /**
     * ajax_inserisci()
     *
     * Cerca un prodotto per descrizione e restituisce il relativo barcode
     * una volta selezionato, così da poter effettuare l'inserimento nel
     * carrello
     */
	public function ajax_inserisci() {
		$res = $this->registry->db->query("SELECT barcode, descrizione FROM barcodes WHERE descrizione LIKE '".strtolower($_REQUEST['term'])."%' ");
        $results = array();
      
		  foreach($res as $k=>$row) {
            $row_array['barcode'] = $row['barcode'];
            $row_array['nome'] = ucwords($row['descrizione']);

            array_push($results, $row_array);
            //$results[] =  array(ucwords($row['nome_comune'])." (".$row['provincia'].")", $row['cod_istat']);
        }

        echo json_encode($results);
	}

    /**
     * ajax_notecliente()
     *
     * Inserisce note aggiuntive relative al cliente nella sessione di cassa
     * corrente
     */
    public function ajax_notecliente() {
        $_SESSION['notecliente'] = $_REQUEST['notecliente'];

        echo json_encode("<i class='fa fa-check' aria-hidden='true'></i> Salvato");
    }

    /**
     * inserisci()
     *
     * Inserisce il prodotto nel carrello
     */
	public function inserisci() {

        // Controllo sull'inserimento vuoto, così da prevenire l'HTTP 500
		if(empty($_REQUEST['insert_barcode'])) {
			$this->registry->template->show("cassa_main");
			return;
		}

		$cart = $_SESSION['cart'];

		$sql_barcode = "SELECT barcodes.*, tipologie.*, categorie.* FROM barcodes 
						INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia 
						INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria 
						WHERE barcode = '".$_REQUEST['insert_barcode']."'";
        $sql = $this->registry->db->query($sql_barcode);

        if($sql->rowCount() <= 0) {
            $this->registry->template->notfound = 1;
            $this->registry->template->barcode = $_REQUEST['insert_barcode'];
            $this->registry->template->show("cassa_main");
            return;
        }

		$val = $sql->fetch();

        // Controllo il limite d'eta` per il prodotto acquistato: recupero i famigliari (se ce ne sono)
        // del cliente in corso e verifico se ci sono elementi il range di eta` specificato nella
        // tipologia: se ci sono elementi, allora si puo` procedere all'acquisto, altrimenti si esce dal
        // controller e viene mostrato un errore in cassa
        if(!empty($val['eta_min']) && !empty($val['eta_max'])) {
            $sql_limite_eta = "SELECT data_nascita FROM componenti_famiglie WHERE capofamiglia = '".$_SESSION['tessera']."'";
            $ris_limite_eta = $this->registry->db->query($sql_limite_eta);

            if($ris_limite_eta->rowCount() > 0) {
                $num_bimbi = 0;

                foreach($ris_limite_eta as $z=>$val_limite_eta) {
                    $data_nascita = new DateTime($val_limite_eta['data_nascita']);
                    $oggi = new DateTime(date('Y-m-d'));
                    $diff = $data_nascita->diff($oggi);
                    $eta = $diff->format('%y');
                    error_log("età: ".$eta);

                    if($eta >= $val['eta_min'] && $eta <= $val['eta_max']) {
                        $num_bimbi++;
                    }
                }
        
                if($num_bimbi == "0") {
                    $this->registry->template->agelimit = 1;
                    $this->registry->template->show("cassa_main");
                    return;
                }
            }
            else {
                $this->registry->template->agelimit = 1;
                $this->registry->template->show("cassa_main");
                return;
            }
        }

        // Se si tratta di un prodotto AGEA, aggiungo il flag alla
        // descrizione in cassa
		if($val['agea']) {
			$agea = "[AGEA]";
		}
		else {
			$agea = "";
		}

		$now = date("Y-m-d");
		// Verifico se il prodotto in questione e` in offerta: se lo e`,
		// aggiorno il prezzo di vendita  con quello dell'offerta
		$sql_offerta = "SELECT prezzo_offerta FROM offerte_sconti 
						WHERE barcode = '".$_REQUEST['insert_barcode']."'
						AND offerta_a >= '".$now."'
						AND offerta_da <= '".$now."'";
		$sql2 = $this->registry->db->query($sql_offerta);
		if($sql2->rowCount() > 0) {
			$val2 = $sql2->fetch();
			$val['punti'] = $val2['prezzo_offerta'];
			$discounted = 1;
		}
		else {
			$discounted = 0;
		}

        // Controllo sui limiti per singola spesa e per mese relativi alla categoria
        // del prodotto appena inserito nel carrello
		if(isset($_SESSION['punti_residui']) && !empty($_SESSION['punti_residui'])) {
			$count_lim_mese = 0;

            // Recupero gli eventuali punti già spesi nel mese corrente controllando
            // nella tabella degli acquisti
			$sql_cnt_mese = "SELECT SUM(spesa.punti * spesa.qta) AS punti_cat FROM spesa 
							INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia 
							INNER JOIN scontrini ON spesa.id_scontrino = scontrini.id_scontrino 
							WHERE scontrini.codice_fiscale = '".$_SESSION['tessera']."' 
								AND tipologie.categoria = '".$val['categoria']."' 
                                AND spesa.data >= '".date("Y-m-01 00:00:00")."' ";
			$ris = $this->registry->db->query($sql_cnt_mese);
		
			if($ris->rowCount() > 0) {
				$row = $ris->fetch();
				$count_lim_mese = $row['punti_cat'];
			}

            // Recupero i limiti "custom" per la categoria del prodotto appena inserito
            // e per componenti del nucleo famigliare: se questi sono stati impostati, 
            // setta i limiti per singola spesa e per mese corretti; se invece questi
            // limiti custom non sono stati impostati, utilizza quelli generali definiti 
            // nella categoria
			$sql_limiti_nucleo = "SELECT * FROM limiti_nucleo WHERE categoria_merce = '".$val['categoria']."'";
			$ris = $this->registry->db->query($sql_limiti_nucleo);
	
			if($ris->rowCount() > 0) {
				$limiti_nucleo = $ris->fetch();

				switch($_SESSION['componenti']) {
					case 1:
						$limite_spesa = $limiti_nucleo['lim_1c_spesa'];
						$limite_mese = $limiti_nucleo['lim_1c_mese'];
						break;
					case 2:
						$limite_spesa = $limiti_nucleo['lim_2c_spesa'];
						$limite_mese = $limiti_nucleo['lim_2c_mese'];
						break;
					case 3:
						$limite_spesa = $limiti_nucleo['lim_3c_spesa'];
						$limite_mese = $limiti_nucleo['lim_3c_mese'];
						break;
					case 4:
						$limite_spesa = $limiti_nucleo['lim_4c_spesa'];
						$limite_mese = $limiti_nucleo['lim_4c_mese'];
						break;
					case 5:
						$limite_spesa = $limiti_nucleo['lim_5c_spesa'];
						$limite_mese = $limiti_nucleo['lim_5c_mese'];
						break;
					case 6:
						$limite_spesa = $limiti_nucleo['lim_6c_spesa'];
						$limite_mese = $limiti_nucleo['lim_6c_mese'];
						break;
					case 7:
						$limite_spesa = $limiti_nucleo['lim_7c_spesa'];
						$limite_mese = $limiti_nucleo['lim_7c_mese'];
						break;
					case 8:
						$limite_spesa = $limiti_nucleo['lim_8c_spesa'];
						$limite_mese = $limiti_nucleo['lim_8c_mese'];
						break;
					case 9:
						$limite_spesa = $limiti_nucleo['lim_9c_spesa'];
						$limite_mese = $limiti_nucleo['lim_9c_mese'];
						break;
					case 10:
						$limite_spesa = $limiti_nucleo['lim_10c_spesa'];
						$limite_mese = $limiti_nucleo['lim_10c_mese'];
						break;
					default:
						$limite_spesa = $val['limite_spesa_max'];
						$limite_mese = $val['limite_mese_max'];
						break;	
				}
			}
			else {
				$limite_spesa = $val['limite_spesa_max'];
            	$limite_mese = $val['limite_mese_max'];
			}

            // Calcola i punti per singola spesa e per mese dopo aver 
            // inserito il prodotto
			$count_lim_spesa = 0;
			if (!$cart->isEmpty()) {
				foreach ($cart as $arr) {
					$item_tmp = $arr['item'];

					if($val['categoria'] == $item_tmp->getCategory()) {
						$count_lim_spesa += ($item_tmp->getPrice() * $arr['qty']);
						$count_lim_mese += ($item_tmp->getPrice() * $arr['qty']);
					}
				}
			}

            // Controllo se i limiti sono stati superati. Se questo succede, setta il relativo
            // flag di errore e rimanda alla visualizzazione della cassa, la quale
            // farà comparire un popup e impedirà l'inserimento di ulteriori prodotti di
            // quella categoria. Altrimenti, inserisci il prodotto nel carrello e aggiorna
            // i punti cliente
			if((($count_lim_mese + $val['punti']) > $limite_mese) || (($count_lim_spesa + $val['punti']) > $limite_spesa) || 
				(($_SESSION['punti_residui'] - $val['punti']) < 0) || empty($_REQUEST['insert_barcode']) ) {

				if(($count_lim_mese + $val['punti']) > $limite_mese) {
					$this->registry->template->warning_limite_mese = 1;
				}
				else if (($count_lim_spesa + $val['punti']) > $limite_spesa) {
					$this->registry->template->warning_limite_spesa = 1;
					$this->registry->template->categoria = $val['descrizione_categoria'];
				}
				else if(($_SESSION['punti_residui'] - $val['punti']) < 0) {
					$this->registry->template->warning_credito_esaurito = 1;
					$this->registry->template->categoria = $val['descrizione_categoria'];
				}

				$this->registry->template->show("cassa_main");
			}

			else { 
				$item = new Item($val['barcode'], $val['tipologia'], $val['categoria'], $val['descrizione']." ".$agea, $val['punti'], $discounted);
				$cart->addItem($item);
			
				$punti_residui = $_SESSION['punti_residui'];
				$punti_residui -= $val['punti'];
				$_SESSION['punti_residui'] = $punti_residui;

				$totale_spesa = $_SESSION['totale_spesa'];
				$totale_spesa += $val['punti'];
				$_SESSION['totale_spesa'] = $totale_spesa;

				$_SESSION['cart'] = $cart;

				$this->registry->template->show("cassa_main");
			}
		}

        // Questo inserimento dovrebbe valere per quei clienti che hanno l'esenzione dal contggio dei punti
		else {
				$item = new Item($val['barcode'], $val['tipologia'], $val['categoria'], $val['descrizione']." ".$agea, $val['punti'], $discounted);
				$cart->addItem($item);
			
				$totale_spesa = $_SESSION['totale_spesa'];
				$totale_spesa += $val['punti'];
				$_SESSION['totale_spesa'] = $totale_spesa;

				$_SESSION['cart'] = $cart;

				$this->registry->template->show("cassa_main");
		} 
	}

	/** 
     * rimuovi_articolo()
     *
     * Decrementa di una unita` un articolo dal carrello: nel caso
     * in cui questa quantita` arrivi a zero, elimina il prodotto dal
     * carrello.
     * La funzione e` utile in caso di aggiustamenti manuali della 
     * quantita` di un prodotto a carrello.
     */
	public function rimuovi_articolo() {
		$cart = $_SESSION['cart'];

		foreach($cart as $arr) {
			$item = $arr['item'];

			if($item->getId() == $_REQUEST['barcode']) {
				$punti = $item->getPrice();

				$cart->updateItem($item, ($arr['qty'] - 1));

				$punti_residui = $_SESSION['punti_residui'];
				$punti_residui += $punti;
				$_SESSION['punti_residui'] = $punti_residui;

				$totale_spesa = $_SESSION['totale_spesa'];
				$totale_spesa -= $punti;
				$_SESSION['totale_spesa'] = $totale_spesa;

				$_SESSION['cart'] = $cart;

				$this->registry->template->show("cassa_main");
			}
		}
	}

	/** 
     * incrementa_articolo()
     *
     * Incrementa la quantita` di un articolo nel carrello di una unita`.
     * Utile in caso di aggiustamenti manuali della quantita` acquistata
     * di un prodotto.
     *
     * Il funzionamento di base e` identico alla funzione principale 
     * inserisci()
     */
	public function incrementa_articolo() {
		$cart = $_SESSION['cart'];

		foreach($cart as $arr) {
			$item = $arr['item'];

			if($item->getId() == $_REQUEST['barcode']) {
                $sql_nomecat = "SELECT descrizione_categoria FROM categorie WHERE id_categoria = '".$item->getCategory()."'";
                $ris_nomecat = $this->registry->db->query($sql_nomecat);
                $val_nomecat = $ris_nomecat->fetch();

				$punti = $item->getPrice();

				if(isset($_SESSION['punti_residui']) && !empty($_SESSION['punti_residui'])) {
					$sql_lim_max_cat = "SELECT limite_mese_max, limite_spesa_max FROM categorie where id_categoria = '".$item->getCategory()."'";
					$ris = $this->registry->db->query($sql_lim_max_cat);
					$val = $ris->fetch();

					$count_lim_mese = 0;
					$sql_cnt_mese = "SELECT SUM(spesa.punti * spesa.qta) AS punti_cat FROM spesa 
                            INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia 
                            INNER JOIN scontrini ON spesa.id_scontrino = scontrini.id_scontrino 
                            WHERE scontrini.codice_fiscale = '".$_SESSION['tessera']."' 
                                AND tipologie.categoria = '".$item->getCategory()."' 
                                AND spesa.data >= '".date("Y-m-01 00:00:00")."'";
					$ris = $this->registry->db->query($sql_cnt_mese);
				
					if($ris->rowCount() > 0) {
						$row = $ris->fetch();
						$count_lim_mese = $row['punti_cat'];
					}

					$sql_limiti_nucleo = "SELECT * FROM limiti_nucleo WHERE categoria_merce = '".$item->getCategory()."'";
					$ris = $this->registry->db->query($sql_limiti_nucleo);

					if($ris->rowCount() > 0) {
						$limiti_nucleo = $ris->fetch();

						switch($_SESSION['componenti']) {
							case 1:
								$limite_spesa = $limiti_nucleo['lim_1c_spesa'];
								$limite_mese = $limiti_nucleo['lim_1c_mese'];
								break;
							case 2:
								$limite_spesa = $limiti_nucleo['lim_2c_spesa'];
								$limite_mese = $limiti_nucleo['lim_2c_mese'];
								break;
							case 3:
								$limite_spesa = $limiti_nucleo['lim_3c_spesa'];
								$limite_mese = $limiti_nucleo['lim_3c_mese'];
								break;
							case 4:
								$limite_spesa = $limiti_nucleo['lim_4c_spesa'];
								$limite_mese = $limiti_nucleo['lim_4c_mese'];
								break;
							case 5:
								$limite_spesa = $limiti_nucleo['lim_5c_spesa'];
								$limite_mese = $limiti_nucleo['lim_5c_mese'];
								break;
							case 6:
								$limite_spesa = $limiti_nucleo['lim_6c_spesa'];
								$limite_mese = $limiti_nucleo['lim_6c_mese'];
								break;
							case 7:
								$limite_spesa = $limiti_nucleo['lim_7c_spesa'];
								$limite_mese = $limiti_nucleo['lim_7c_mese'];
								break;
							case 8:
								$limite_spesa = $limiti_nucleo['lim_8c_spesa'];
								$limite_mese = $limiti_nucleo['lim_8c_mese'];
								break;
							case 9:
								$limite_spesa = $limiti_nucleo['lim_9c_spesa'];
								$limite_mese = $limiti_nucleo['lim_9c_mese'];
								break;
							case 10:
								$limite_spesa = $limiti_nucleo['lim_10c_spesa'];
								$limite_mese = $limiti_nucleo['lim_10c_mese'];
								break;
							default:
								$limite_spesa = $val['limite_spesa_max'];
								$limite_mese = $val['limite_mese_max'];
								break;
						}
					}
					else {
						$limite_spesa = $val['limite_spesa_max'];
						$limite_mese = $val['limite_mese_max'];
					}

					$count_lim_spesa = 0;

					if (!$cart->isEmpty()) {
						foreach ($cart as $arr2) {
							$item_tmp = $arr2['item'];

							if($item->getCategory() == $item_tmp->getCategory()) {
								$count_lim_spesa += ($item_tmp->getPrice() * $arr2['qty']);
								$count_lim_mese += ($item_tmp->getPrice() * $arr2['qty']);
							}
						}
					}

					else {
						$count_lim_spesa += $punti;
					}

					if((($count_lim_mese + $punti) > $limite_mese) || (($count_lim_spesa + $punti) > $limite_spesa) || (($_SESSION['punti_residui'] - $punti) < 0)) {
						if(($count_lim_mese + $punti) > $limite_mese) {
							$this->registry->template->warning_limite_mese = 1;
					        $this->registry->template->categoria = $val_nomecat['descrizione_categoria'];
						}
						else if (($count_lim_spesa + $punti) > $limite_spesa) {
							$this->registry->template->warning_limite_spesa = 1;
					        $this->registry->template->categoria = $val_nomecat['descrizione_categoria'];
						}
						else if(($_SESSION['punti_residui'] - $punti) < 0) {
							$this->registry->template->warning_credito_esaurito = 1;
						}

						$this->registry->template->show("cassa_main");
					}

					else {
						$cart->updateItem($item, ($arr['qty'] + 1));

						$punti_residui = $_SESSION['punti_residui'];
						$punti_residui -= $punti;
						$_SESSION['punti_residui'] = $punti_residui;

						$totale_spesa = $_SESSION['totale_spesa'];
						$totale_spesa += $punti;
						$_SESSION['totale_spesa'] = $totale_spesa;

						$_SESSION['cart'] = $cart;

						$this->registry->template->show("cassa_main");
					}
				}

				else {
					$cart->updateItem($item, ($arr['qty'] + 1));

					$totale_spesa = $_SESSION['totale_spesa'];
					$totale_spesa += $punti;
					$_SESSION['totale_spesa'] = $totale_spesa;

					$_SESSION['cart'] = $cart;

					$this->registry->template->show("cassa_main");
				}
			}
		}
	}

    /**
     * cambiaqta()
     *
     * Modifica la quantita` di un prodotto in base al valore inserito
     * dall'operatore in cassa. La funzione e` utile in caso di grandi 
     * volumi di acquisto di uno stesso prodotto, cosi` da non dover
     * leggere ogni volta col lettore di barcode o utilizzare la 
     * funzione incrementa/decrementa.
     *
     * Anche in questo caso il funzionamento di base è lo stesso di inserisci()
     */
	public function cambiaqta() {
		$cart = $_SESSION['cart'];

		foreach($cart as $arr) {
			$item = $arr['item'];

			if($item->getId() == $_REQUEST['barcode']) {
                $sql_nomecat = "SELECT descrizione_categoria FROM categorie WHERE id_categoria = '".$item->getCategory()."'";
                $ris_nomecat = $this->registry->db->query($sql_nomecat);
                $val_nomecat = $ris_nomecat->fetch();

				$punti = $item->getPrice();

				if(isset($_SESSION['punti_residui']) && !empty($_SESSION['punti_residui'])) {
					$sql_lim_max_cat = "SELECT limite_mese_max, limite_spesa_max FROM categorie where id_categoria = '".$item->getCategory()."'";
					$ris = $this->registry->db->query($sql_lim_max_cat);
					$val = $ris->fetch();

					$count_lim_mese = 0;
					$sql_cnt_mese = "SELECT SUM(spesa.punti * spesa.qta) AS punti_cat FROM spesa 
                            INNER JOIN tipologie ON spesa.tipologia = tipologie.id_tipologia 
                            INNER JOIN scontrini ON spesa.id_scontrino = scontrini.id_scontrino 
                            WHERE scontrini.codice_fiscale = '".$_SESSION['tessera']."' 
                                AND tipologie.categoria = '".$item->getCategory()."' 
                                AND spesa.data >= '".date("Y-m-01 00:00:00")."'";
					$ris = $this->registry->db->query($sql_cnt_mese);
				
					if($ris->rowCount() > 0) {
						$row = $ris->fetch();
						$count_lim_mese = $row['punti_cat'];
					}

					$sql_limiti_nucleo = "SELECT * FROM limiti_nucleo WHERE categoria_merce = '".$item->getCategory()."'";
					$ris = $this->registry->db->query($sql_limiti_nucleo);

					if($ris->rowCount() > 0) {
						$limiti_nucleo = $ris->fetch();

						switch($_SESSION['componenti']) {
							case 1:
								$limite_spesa = $limiti_nucleo['lim_1c_spesa'];
								$limite_mese = $limiti_nucleo['lim_1c_mese'];
								break;
							case 2:
								$limite_spesa = $limiti_nucleo['lim_2c_spesa'];
								$limite_mese = $limiti_nucleo['lim_2c_mese'];
								break;
							case 3:
								$limite_spesa = $limiti_nucleo['lim_3c_spesa'];
								$limite_mese = $limiti_nucleo['lim_3c_mese'];
								break;
							case 4:
								$limite_spesa = $limiti_nucleo['lim_4c_spesa'];
								$limite_mese = $limiti_nucleo['lim_4c_mese'];
								break;
							case 5:
								$limite_spesa = $limiti_nucleo['lim_5c_spesa'];
								$limite_mese = $limiti_nucleo['lim_5c_mese'];
								break;
							case 6:
								$limite_spesa = $limiti_nucleo['lim_6c_spesa'];
								$limite_mese = $limiti_nucleo['lim_6c_mese'];
								break;
							case 7:
								$limite_spesa = $limiti_nucleo['lim_7c_spesa'];
								$limite_mese = $limiti_nucleo['lim_7c_mese'];
								break;
							case 8:
								$limite_spesa = $limiti_nucleo['lim_8c_spesa'];
								$limite_mese = $limiti_nucleo['lim_8c_mese'];
								break;
							case 9:
								$limite_spesa = $limiti_nucleo['lim_9c_spesa'];
								$limite_mese = $limiti_nucleo['lim_9c_mese'];
								break;
							case 10:
								$limite_spesa = $limiti_nucleo['lim_10c_spesa'];
								$limite_mese = $limiti_nucleo['lim_10c_mese'];
								break;
							default:
								$limite_spesa = $val['limite_spesa_max'];
								$limite_mese = $val['limite_mese_max'];
								break;
						}
					}
					else {
						$limite_spesa = $val['limite_spesa_max'];
						$limite_mese = $val['limite_mese_max'];
					}

					$count_lim_spesa = 0;
					$count_lim_mese_orig = $count_lim_mese; // tengo traccia dei punti mensili originali per poi fare i conti corretti

					if (!$cart->isEmpty()) {
						foreach ($cart as $arr2) {
							$item_tmp = $arr2['item'];

							if($item->getCategory() == $item_tmp->getCategory()) {
								$count_lim_spesa = ($punti * $_REQUEST['qta']);
								$count_lim_mese = $count_lim_mese_orig + ($punti * $_REQUEST['qta']); // Occhio qui, i conti non so se sono corretti...

								$punti_custom = ($punti * $_REQUEST['qta']);
								error_log("Punti custom: ".$punti_custom);
								error_log("Limite spesa: ".$count_lim_spesa);
							}
						}
					}

					else {
						$count_lim_spesa += $punti;
					}

					// Se i conti sono sbagliati, sono sbagliati qui...
					if((($count_lim_mese ) > $limite_mese) || (($count_lim_spesa ) > $limite_spesa) || (($_SESSION['punti_residui'] - $punti_custom) < 0)) {
						if (($count_lim_spesa ) > $limite_spesa) {
							$this->registry->template->warning_limite_spesa = 1;
					        $this->registry->template->categoria = $val_nomecat['descrizione_categoria'];
						}
						else if(($count_lim_mese ) > $limite_mese) {
							$this->registry->template->warning_limite_mese = 1;
					        $this->registry->template->categoria = $val_nomecat['descrizione_categoria'];
						}
						else if(($_SESSION['punti_residui'] - $punti_custom) < 0) {
							$this->registry->template->warning_credito_esaurito = 1;
						}

						$this->registry->template->show("cassa_main");
					}

					else {
						$cart->updateItem($item, ($_REQUEST['qta']));

						$punti_residui = $_SESSION['punti_residui'];
						$punti_residui -= ($punti * ($_REQUEST['qta'] - $arr['qty']));
						$_SESSION['punti_residui'] = $punti_residui;

						$totale_spesa = $_SESSION['totale_spesa'];
						$totale_spesa += ($punti * ($_REQUEST['qta'] - $arr['qty']));
						$_SESSION['totale_spesa'] = $totale_spesa;

						$_SESSION['cart'] = $cart;

						$this->registry->template->show("cassa_main");
					}
				}

				else {
					$cart->updateItem($item, ($_REQUEST['qta']));

					$totale_spesa = $_SESSION['totale_spesa'];
					$totale_spesa += $punti;
					$_SESSION['totale_spesa'] = $totale_spesa;

					$_SESSION['cart'] = $cart;

					$this->registry->template->show("cassa_main");
				}
			}
		}
	}

	/** 
     * reset_carrello()
     *
     * Resetta il carrello, senza pero` uscire dalla sessione cliente
     */
	public function reset_carrello() {
		$cart = $_SESSION['cart'];
		$cart->emptyCart();

		$_SESSION['cart'] = $cart;
		$_SESSION['totale_spesa'] = "0";

		if(isset($_SESSION['punti_residui']) && !empty($_SESSION['punti_residui'])) {
			$_SESSION['punti_residui'] = $_SESSION['punti_residui_reset'];
		}

		$this->registry->template->show("cassa_main");
	}

    /**
     * segnala_barcode()
     * @param string $barcode
     *
     * In caso di prodotto non trovato a magazzino, ma presente in emporio, invia una
     * segnalazione al backend per le opportune verifiche del caso
     */
    public function segnala_barcode($barcode = '') {
        $msg = "Il prodotto <strong>".$barcode."</strong> &egrave; stato segnalato come <strong>inesistente</strong>. Verificare il corretto carico a magazzino!";

        $sql = "INSERT INTO notifiche(tipo, msg, data, letto) VALUES('inesistente', '".$msg."', '".date("Y-m-d H:i:s")."', false)";
        $count = $this->registry->db->exec($sql);

        $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[CASSA]', 
                            'Prodotto ".$barcode." segnalato come inesistente', 
                            '".$_SESSION['username']."')";
        $count = $this->registry->db->exec($sql_log);

        echo json_encode("Segnalato");
    } 

} 

?>
