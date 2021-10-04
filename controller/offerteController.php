<?php
session_start();

class offerteController extends BaseController {

	public function index($barcode = '') {
		$now = date("Y-m-d");
		$sql = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
				INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
				INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE 1=1 AND offerta_a >= '".$now."'";
		if(!empty($barcode)) {
			$sql .= "AND offerte.barcode = '".$barcode."' ";
		}
		
		$sql .= "ORDER BY offerta_a DESC, offerta_da DESC";

		$sql_old = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
                INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE 1=1 AND offerta_a < '".$now."'";
        $sql_old .= "ORDER BY offerta_a DESC LIMIT 20";
		$this->registry->template->ris = $this->registry->db->query($sql);
		$this->registry->template->ris_old = $this->registry->db->query($sql_old);

		$this->registry->template->show('offerte');
	}

	public function nuovo($barcode = '') {
		if(!empty($barcode)) {
			$this->registry->template->barcode = $barcode;
			$this->registry->template->single = 1;

            $sql = "SELECT barcodes.descrizione, 
                            barcodes.tipologia, 
                            tipologie.descrizione_tipologia, 
                            tipologie.punti,
                            categorie.id_categoria, 
                            categorie.descrizione_categoria 
                    FROM barcodes 
                    INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia 
                    INNER JOIN categorie ON tipologie.categoria = categorie.id_categoria
                    WHERE barcodes.barcode = '".$barcode."'";
            $this->registry->template->ris_single = $this->registry->db->query($sql);
		}

        else {
		    $this->registry->template->ris = $this->registry->db->query("SELECT * FROM categorie ORDER BY descrizione_categoria ASC");
        }

		$this->registry->template->show('nuovaofferta');
	}

	public function inserisci() {
		for($i = 0; $i < sizeof($_REQUEST['seleziona']); $i++) {

			$riga = $_REQUEST['seleziona'][$i];
			$sql = "INSERT INTO offerte_sconti(barcode, id_tipologia, prezzo_offerta, offerta_da, offerta_a)
					VALUES('".$_REQUEST['barcode'][$riga]."', 
							'".$_REQUEST['tipologia'][$riga]."', 
							'".$_REQUEST['prezzo_offerta'][$riga]."', 
							'".$_REQUEST['valida_da'][$riga]."', 
							'".$_REQUEST['valida_a'][$riga]."')";
			$count = $this->registry->db->exec($sql);

			if($count > 0) {
				$this->registry->template->insertok = 1;
			}
			else {
				$this->registry->template->insertok = 0;
				break;
			}

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    	VALUES('".date("Y-m-d H:i:s")."', 
                            '[OFFERTE]', 
                            'Nuova offerta (".$_REQUEST['valida_da'][$riga]." - ".$_REQUEST['valida_a'][$riga]."): Prodotto ".$_REQUEST['barcode'][$riga]."  - Prezzo ".$_REQUEST['prezzo_offerta'][$riga]."', 
                            '".$_SESSION['username']."')";
        	$count_log = $this->registry->db->exec($sql_log);
		
  		}

		$this->nuovo();
	}

	public function modifica($id_offerta = '') {
		$sql = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
                INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE offerte_sconti.id_offerta = '".$id_offerta."'";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->id_offerta = $id_offerta;
		$this->registry->template->show('modificaofferta');
	}

	public function update_offerta($id_offerta = '') {
		$sql = "UPDATE offerte_sconti SET prezzo_offerta = '".$_REQUEST['prezzo_offerta']."', offerta_da = '".$_REQUEST['offerta_da']."', offerta_a = '".$_REQUEST['offerta_a']."' 
				WHERE id_offerta = '".$id_offerta."'";
		$count = $this->registry->db->exec($sql);

		$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                    VALUES('".date("Y-m-d H:i:s")."', 
                            '[OFFERTE]', 
                            'Modifica offerta: id ".$_REQUEST['id_offerta']."', 
                            '".$_SESSION['username']."')";
        $count2 = $this->registry->db->exec($sql_log);

		if($count > 0) {
            $this->registry->template->modifyok = 1;
        }
        else {
            $this->registry->template->modifyok = 0;
        }

        $this->modifica($id_offerta);
	}

	public function cerca($barcode = '') {
		$this->registry->template->cerca = 1;

		$sql = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
                INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE 1=1 ";

        $sql_old = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
                INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE 1=1 ";

        if(!empty($barcode)) {
            $sql .= "AND offerte_sconti.barcode = '".$barcode."' ";
            $sql_old .= "AND offerte_sconti.barcode = '".$barcode."' ";
        }

        if(!empty($_REQUEST['cerca_barcode'])) {
            $sql .= "AND offerte_sconti.barcode = '".$_REQUEST['cerca_barcode']."' ";
            $sql_old .= "AND offerte_sconti.barcode = '".$_REQUEST['cerca_barcode']."' ";
        }

		if(empty($_REQUEST['date-end'])) {
			$sql .= "AND offerte_sconti.offerta_a >= '".date("Y-m-d")."' ";
			$sql_old .= "AND offerte_sconti.offerta_a < '".date("Y-m-d")."' ";
		}
		else {
			if($_REQUEST['date-end'] >= date("Y-m-d")) {
				$sql .= "AND offerte_sconti.offerta_a <= '".$_REQUEST['date-end']."' ";
			}
			else {
				$sql_old .= "AND offerte_sconti.offerta_a <= '".$_REQUEST['date-end']."' ";
			}
		}

		if(!empty($_REQUEST['date-start'])) {
			$sql .= "AND offerte_sconti.offerta_da >= '".$_REQUEST['date-start']."' ";
			$sql_old .= "AND offerte_sconti.offerta_da >= '".$_REQUEST['date-start']."' ";
		}
	
        $sql .= "ORDER BY offerte_sconti.offerta_a DESC, offerta_da DESC";
        $sql_old .= "ORDER BY offerte_sconti.offerta_a DESC, offerta_da DESC LIMIT 20";

        $this->registry->template->ris = $this->registry->db->query($sql);
        $this->registry->template->ris_old = $this->registry->db->query($sql_old);

		$this->registry->template->show('offerte');
	}

	public function rimuovi() {
		$sql = "DELETE FROM offerte_sconti WHERE id_offerta = '".$_REQUEST['fake-form-offerta']."'";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
            $this->registry->template->deleteok = 1;

			$now = date("Y-m-d");
			$sql = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
				INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
				INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE 1=1 AND offerta_a >= '".$now."'";
			$sql .= "ORDER BY offerta_a DESC";

			$sql_old = "SELECT offerte_sconti.*, barcodes.descrizione, tipologie.descrizione_tipologia FROM offerte_sconti 
                INNER JOIN barcodes ON offerte_sconti.barcode = barcodes.barcode 
                INNER JOIN tipologie ON barcodes.tipologia = tipologie.id_tipologia WHERE 1=1 AND offerta_a < '".$now."'";
        	$sql_old .= "ORDER BY offerta_a DESC LIMIT 20";
			
			$this->registry->template->ris = $this->registry->db->query($sql);
			$this->registry->template->ris_old = $this->registry->db->query($sql_old);
			
		}
        else {
            $this->registry->template->deleteok = 0;
        }
		
        $this->index();
	}

	public function ajax_offerta() {
		$now = date("Y-m-d");
		$sql = "SELECT offerta_da, offerta_a FROM offerte_sconti WHERE barcode = '".$_REQUEST['id']."' AND offerta_a >= '".$now."' ";
		$ris = $this->registry->db->query($sql);
		$val = $ris->fetch();

		$info_offerta = "[".$_REQUEST['id']."]: Offerta valida dal <strong>".date("d/m/Y", strtotime($val['offerta_da']))."</strong> al <strong>".date("d/m/Y", strtotime($val['offerta_a']))."</strong>";

		echo json_encode(array(
            "responseText1" => $info_offerta,
        ));
	}

    public function ajax_tipologie() {
        $sql = $this->registry->db->query("SELECT id_tipologia, descrizione_tipologia, punti FROM tipologie WHERE categoria = '".$_REQUEST['id']."' ORDER BY descrizione_tipologia ASC");

        print("<option value='' disabled selected>-- Seleziona --</option>");

        foreach($sql as $key=>$val) {
            print("<option punti='".$val['punti']."' value='".$val['id_tipologia']."'>".ucwords($val['descrizione_tipologia'])."</option>");
        }
    }

	public function ajax_barcode() {
        $sql = $this->registry->db->query("SELECT barcode, descrizione FROM barcodes WHERE tipologia = '".$_REQUEST['id']."' ORDER BY descrizione ASC");

		print("<table id='tab_barcodes_offerte' class='table table-responsive table-striped'>
                <thead>
                    <tr>
                        <th width='5%'>&nbsp;</th>
                        <th width='25%'>Prodotto</th>
                        <th width='15%'>Barcode</th>
                        <th width='5%'>Prezzo</th>
                        <th width='7%'>Prezzo offerta</th>
                        <th width='10%'>Valida da</th>
                        <th width='10%'>Valida a</th>
                    </tr>
                </thead>
                <tbody>");

		$riga = 0;
        foreach($sql as $key=>$val) {
            print("<tr>
					<td><input type='checkbox' name='seleziona[]' value='".$riga."'></td>
					<td>".$val['descrizione']."</td>
					<td>".$val['barcode']."<input type='hidden' name='barcode[]' id='".$val['barcode']."' value='".$val['barcode']."'>
					<input type='hidden' name='tipologia[]' value='".$_REQUEST['id']."'>
					</td>
					<td>".$_REQUEST['punti']."<input type='hidden' name='prezzo_pieno[]' value='".$_REQUEST['punti']."'></td>
					<td><input type='text' class='form-control' name='prezzo_offerta[]' id='".$val['barcode']."' value=''></td>
					<td><div class='input-group date' data-provide='datepicker'>
                        <input type='text' class='form-control' id='valida_da' name='valida_da[]' tabindex='8' placeholder='Es. 01/01/1970'>
                        <div class='input-group-addon'>
                            <span class='fa fa-calendar'></span>
                        </div>
                    </div></td>
					<td><div class='input-group date' data-provide='datepicker'>
                        <input type='text' class='form-control' id='valida_a' name='valida_a[]' tabindex='8' placeholder='Es. 01/01/1970'>
                        <div class='input-group-addon'>
                            <span class='fa fa-calendar'></span>
                        </div>
                    </div></td>
				  </tr>");
			$riga++;
        }

		print("</tbody></table>");
    }

}

?>
