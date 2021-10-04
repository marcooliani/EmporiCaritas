<?php
session_start();

class fornitoriController extends BaseController {

	public function index() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM fornitori ORDER BY ragione_sociale ASC");
		$this->registry->template->show("fornitori");
	}

	public function nuovo() {
		$this->registry->template->show("nuovofornitore");
	}

	public function inserisci() {
        if(empty($_REQUEST['comune'])) {
            $_REQUEST['comune'] = "000000";
        }

		$sql = "INSERT INTO fornitori(ragione_sociale, cognome, nome, referente, indirizzo, localita, cap, comune, telefono, email, fornitore_da, donatore, note, cellulare) 
				VALUES('".$_REQUEST['ragione_sociale']."', 
						'".$_REQUEST['cognome']."', 
						'".$_REQUEST['nome']."', 
						'', 
						'".$_REQUEST['indirizzo']."', 
						'".$_REQUEST['localita']."', 
						'".$_REQUEST['cap']."', 
						'".$_REQUEST['comune']."', 
						'".$_REQUEST['telefono']."', 
						'".$_REQUEST['email']."', 
						'".$_REQUEST['iscrittoda']."', 
						'".$_REQUEST['donatore']."', 
						'".$_REQUEST['note']."',
                        '".$_REQUEST['cellulare']."')";

		$count = $this->registry->db->exec($sql);

		if($count > 0) {
			$this->registry->template->insertok = 1;
            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[FORNITORI/DONATORI]', 
                            'Aggiunto nuovo fornitore/donatore: ".pg_escape_string($_REQUEST['ragione_sociale'])."', 
                            '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
		}
		else {
			$this->registry->template->insertok = 0;
		}

		$this->registry->template->show("nuovofornitore");
	}

	public function cerca() {
		$sql = "SELECT * FROM fornitori WHERE 1=1 ";
        if(!empty($_REQUEST['cerca_donatore'])) {
            $sql .= "AND LOWER(ragione_sociale) LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_donatore']))."%' ";
        }
        if(isset($_REQUEST['solo_fornitori'])) {
            $sql .= "AND donatore = 'f' ";
        }
        if(isset($_REQUEST['solo_donatori'])) {
            $sql .= "AND donatore = 't' ";
        }
        $sql .= "ORDER BY ragione_sociale ASC";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->cerca = 1;
	
		$this->registry->template->show("fornitori");
	}

	public function ajax_fornitore() {
		$sql = "SELECT * FROM fornitori WHERE id_fornitore = '".$_REQUEST['id']."'";
		$ris = $this->registry->db->query($sql);

		$val = $ris->fetch();

		if($val['donatore']) 
			$fornitore = "Donatore";
		else
			$fornitore = "Fornitore";

		$results = "<strong>Ragione sociale:</strong> &nbsp;<span class='label label-warning' style='font-size:14px;'>".ucwords($val['ragione_sociale'])."</span>
                    <br><br><strong>Cognome e nome:</strong> ".ucwords($val['cognome'])." ".ucwords($val['nome'])."
                    <br><strong>Donatore/fornitore:</strong> ".$fornitore."
                    <br><strong>Iscritto da:</strong> ".date("d/m/Y", strtotime($val['fornitore_da']))."
					<br><br><strong>Indirizzo:</strong> ".$val['indirizzo']."
                    <br><strong>Localit&agrave;:</strong> ".ucwords($val['localita'])." 
                    <br><strong>Comune:</strong> ".ucwords($val['nome_comune'])." 
                    <br><strong>CAP:</strong> ".$val['cap']." 
                    <br><br><strong>Telefono:</strong> ".$val['telefono']."
                    <br><strong>Cellulare:</strong> ".$val['cellulare']."
                    <br><strong>Email:</strong> ".$val['email']."
					<br><br><strong>Note:</strong> ".$val['note']."
					";

        echo json_encode(array(
            "responseText" => $results
        ));
	}

	public function modifica($fornitore = '') {
		$this->registry->template->fornitore = $fornitore;

		$sql = "SELECT fornitori.*, comuni.nome_comune FROM fornitori
				INNER JOIN comuni ON fornitori.comune = comuni.cod_istat 
				WHERE fornitori.id_fornitore = '".$fornitore."' 
				ORDER BY fornitori.ragione_sociale ASC";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show("modificafornitore");
	} 

	public function update_fornitore($fornitore = '') {
		$sql = "UPDATE fornitori SET(ragione_sociale, cognome, nome, indirizzo, localita, cap, comune, 
				telefono, email, fornitore_da, donatore, note, cellulare) = 
				('".$_REQUEST['ragione_sociale']."', 
                 '".$_REQUEST['cognome']."', 
                 '".$_REQUEST['nome']."', 
                 '".$_REQUEST['indirizzo']."', 
                 '".$_REQUEST['localita']."', 
                 '".$_REQUEST['cap']."', 
                 '".$_REQUEST['comune']."', 
                 '".$_REQUEST['telefono']."', 
                 '".$_REQUEST['email']."', 
                 '".$_REQUEST['iscrittoda']."', 
                 '".$_REQUEST['donatore']."', 
                 '".$_REQUEST['note']."',
                 '".$_REQUEST['cellulare']."')
				WHERE id_fornitore = '".$fornitore."'";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
            $this->registry->template->modifyok = 1;

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[FORNITORI/DONATORI]', 
                            'Fornitore/donatore modificato: ".pg_escape_string($_REQUEST['ragione_sociale'])."', 
                            '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
		}
        else {
            $this->registry->template->modifyok = 0;
        }

        $this->modifica($fornitore);
	} 

	public function rimuovi() {
        $sql_nome = $this->registry->db->query("SELECT ragione_sociale FROM fornitori WHERE id_fornitore = '".$_REQUEST['fake-form-tessera']."'");
        $val = $sql_nome->fetch();

		$sql = "DELETE FROM fornitori WHERE id_fornitore = '".$_REQUEST['fake-form-tessera']."'";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->deleteok = 1;

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                        '[FORNITORI/DONATORI]', 
                        'Rimosso fornitore/donatore: ".ucwords(pg_escape_string($val['ragione_sociale']))."', 
                        '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->deleteok = 0;
        }

		$this->index();
	
	}

    public function esporta() {
        $sql = "SELECT fornitori.*, comuni.nome_comune FROM fornitori  
                INNER JOIN comuni ON fornitori.comune = comuni.cod_istat
                WHERE donatore = 't'";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show_noheader('xls_donatori');
    }
}

?>
