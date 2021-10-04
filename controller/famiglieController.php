<?php
session_start();
	
class famiglieController extends BaseController {

	public function index() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM famiglie ORDER BY cognome ASC");
		$this->registry->template->show('famiglie');
	}

	public function anagrafica() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM famiglie ORDER BY cognome ASC");
		$this->registry->template->show('famiglie');
	}

	public function nuovo() {
		$this->registry->template->enti = $this->registry->db->query("SELECT * FROM enti ORDER BY ragione_sociale ASC");
		$this->registry->template->show('nuovafamiglia');
	}

	public function nuovo_capo() {
		if(empty($_REQUEST['num_componenti'])) {
			$_REQUEST['num_componenti'] = 0;
        }

		if(!empty($_REQUEST['natoil'])) {
			$data_nascita = "'".$_REQUEST['natoil']."'";
        }
		else {
			$data_nascita = "NULL";
        }

        if(empty($_REQUEST['comune'])) {
            $_REQUEST['comune'] = '000000';
        }

        if(empty($_REQUEST['nazione'])) {
            $_REQUEST['nazione'] = '00';
        }

        if(empty($_REQUEST['nazionalita'])) {
            $_REQUEST['nazionalita'] = '00';
        }

		$sql = "INSERT INTO famiglie(
					codice_fiscale, cognome, nome, is_ente, data_nascita, luogo_nascita, nazione, 
					nazionalita, sesso, ente_proponente, indirizzo, localita, cap, comune, telefono_1, cellulare,
					email, num_componenti, punti_totali, punti_residui, esenzione, giorno_1,
					orario, scadenza, iscritto_da, sospeso, sospeso_da, sospeso_a, note)
				VALUES('".strtoupper($_REQUEST['tessera'])."', '".strtolower(pg_escape_string($_REQUEST['cognome']))."', '".strtolower(pg_escape_string($_REQUEST['nome']))."', 
						'".$_REQUEST['is_ente']."', ".$data_nascita.", '".strtolower(pg_escape_string($_REQUEST['luogo_nascita']))."', 
						'".pg_escape_string($_REQUEST['nazione'])."', '".pg_escape_string($_REQUEST['nazionalita'])."', 
						'".$_REQUEST['sesso']."', '".$_REQUEST['ente_proponente']."', '".pg_escape_string($_REQUEST['indirizzo'])."', 
						'".strtolower(pg_escape_string($_REQUEST['localita']))."', '".$_REQUEST['cap']."', '".$_REQUEST['comune']."', 
						'".$_REQUEST['telefono']."', '".$_REQUEST['cellulare']."', '".$_REQUEST['email']."', '".$_REQUEST['num_componenti']."', 
						'".$_REQUEST['punti']."', '".$_REQUEST['punti_residui']."', '".$_REQUEST['esenzione']."', '".$_REQUEST['giorno1']."', 
						'".$_REQUEST['orario']."', '".$_REQUEST['scadenza']."', '".$_REQUEST['iscrittoda']."', 'false', NULL, NULL, 
						'".trim(pg_escape_string($_REQUEST['note']))."')";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
			$this->registry->template->insertok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Inserito nuovo cliente: ".strtoupper($_REQUEST['tessera'])." (".strtolower(pg_escape_string($_REQUEST['cognome']))." ".strtolower(pg_escape_string($_REQUEST['nome'])).")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
		}

		else {
			$this->registry->template->insertok = 0;
		}		

        $this->nuovo();
	}

	public function modifica_famiglia($tessera = '') {
		$this->registry->template->tessera = $tessera;

		$sql = "SELECT famiglie.*, comuni.*, nazioni.* FROM famiglie
						INNER JOIN comuni ON famiglie.comune = comuni.cod_istat
                        INNER JOIN nazioni ON famiglie.nazione = nazioni.cod_internazionale
						WHERE famiglie.codice_fiscale = '".$tessera."'";
		$this->registry->template->ris = $this->registry->db->query($sql);
		$this->registry->template->enti = $this->registry->db->query("SELECT * FROM enti ORDER BY ragione_sociale ASC");

		$this->registry->template->show("modificafamiglia");

	}

	public function update_capo($codice_fiscale = '') {
		if(empty($_REQUEST['num_componenti']))
			$_REQUEST['num_componenti'] = 0;

		if(!empty($_REQUEST['natoil']))
			$data_nascita = "'".$_REQUEST['natoil']."'";
		else
			$data_nascita = "NULL";

		$sql = "UPDATE famiglie SET(
					codice_fiscale, cognome, nome, is_ente, data_nascita, luogo_nascita, nazione, 
					nazionalita, sesso, ente_proponente, indirizzo, localita, cap, comune, telefono_1, cellulare,
					email, num_componenti, punti_totali, punti_residui, esenzione, giorno_1,
					orario, scadenza, iscritto_da,  note) =
					('".strtoupper($_REQUEST['tessera'])."', '".strtolower(pg_escape_string($_REQUEST['cognome']))."', '".strtolower(pg_escape_string($_REQUEST['nome']))."', 
						'".$_REQUEST['is_ente']."', ".$data_nascita.", '".strtolower(pg_escape_string($_REQUEST['luogo_nascita']))."', 
						'".pg_escape_string($_REQUEST['nazione'])."', '".pg_escape_string($_REQUEST['nazionalita'])."', 
						'".$_REQUEST['sesso']."', '".$_REQUEST['ente_proponente']."', '".pg_escape_string($_REQUEST['indirizzo'])."', 
						'".strtolower(pg_escape_string($_REQUEST['localita']))."', '".$_REQUEST['cap']."', '".$_REQUEST['comune']."', 
						'".$_REQUEST['telefono']."', '".$_REQUEST['cellulare']."', '".$_REQUEST['email']."', '".$_REQUEST['num_componenti']."', 
						'".$_REQUEST['punti']."', '".$_REQUEST['punti_residui']."', '".$_REQUEST['esenzione']."', '".$_REQUEST['giorno1']."', 
						'".$_REQUEST['orario']."', '".$_REQUEST['scadenza']."', '".$_REQUEST['iscrittoda']."',  
						'".trim(pg_escape_string($_REQUEST['note']))."')
					WHERE codice_fiscale = '".$codice_fiscale."'
					";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
            $this->registry->template->modifyok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Modifica cliente: ".strtoupper($_REQUEST['tessera'])." (".strtolower(pg_escape_string($_REQUEST['cognome']))." ".strtolower(pg_escape_string($_REQUEST['nome'])).")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
		}
        else {
            $this->registry->template->modifyok = 0;
        }

        $this->modifica_famiglia($codice_fiscale);
	}

	public function remove_famiglia () {
        $sql_nome = $this->registry->db->query("SELECT cognome, nome FROM famiglie WHERE codice_fiscale = '".$_REQUEST['fake-form-tessera']."'");
        $val = $sql_nome->fetch();

		$sql = "DELETE FROM famiglie WHERE codice_fiscale = '".$_REQUEST['fake-form-tessera']."'";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->deleteok = 1;

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Rimosso cliente: ".strtoupper($_REQUEST['fake-form-tessera'])." (".ucwords($val['cognome'])." ".ucwords($val['nome']).")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->deleteok = 0;
        }
	
		$this->index();
	}

	public function sospendi() {
        $sql_nome = $this->registry->db->query("SELECT cognome, nome FROM famiglie WHERE codice_fiscale = '".$_REQUEST['fake-form-tessera']."'");
        $val = $sql_nome->fetch();

		$data = date("Y-m-d");
		$msg = "<br>Sospeso in data ".date("d/m/Y", strtotime($data));

		$sql = "UPDATE famiglie SET sospeso = 't', sospeso_da = '".$data."', note = note || '".$msg."' 
				WHERE codice_fiscale = '".$_REQUEST['fake-form-tessera']."'";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->suspendok = 1;

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Sospensione cliente: ".strtoupper($_REQUEST['fake-form-tessera'])." (".ucwords($val['cognome'])." ".ucwords($val['nome']).")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->suspendok = 0;
        }

		$this->index();
	}

	public function abilita() {
        $sql_nome = $this->registry->db->query("SELECT cognome, nome FROM famiglie WHERE codice_fiscale = '".$_REQUEST['fake-form-tessera']."'");
        $val = $sql_nome->fetch();

		$data = date("Y-m-d");
		$msg = "<br>Riabilitato in data ".date("d/m/Y", strtotime($data));

		$sql = "UPDATE famiglie SET sospeso = 'f', sospeso_da = NULL, note = note || '".$msg."' 
				WHERE codice_fiscale = '".$_REQUEST['fake-form-tessera']."'";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->riattivaok = 1;

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Riabilitazione cliente: ".strtoupper($_REQUEST['fake-form-tessera'])." (".ucwords($val['cognome'])." ".ucwords($val['nome']).")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->riabilitaok = 0;
        }

        $this->index();
	}

	public function cerca($param = '') {
		// Qui i dati che vengono dalla view, da processare e da restituire alla view stessa
		// o ad una pagina apposta
		$sql = "SELECT famiglie.* FROM famiglie 
				LEFT JOIN componenti_famiglie ON famiglie.codice_fiscale = componenti_famiglie.capofamiglia 
				WHERE 1=1";

		if(!empty($_REQUEST['cerca_capo'])) {
			$sql .=	" AND (famiglie.codice_fiscale LIKE '%".strtoupper(pg_escape_string($_REQUEST['cerca_capo']))."%'
				OR famiglie.cognome LIKE '%".strtolower(pg_escape_string($_REQUEST['cerca_capo']))."%')";
		}

		if(!empty($_REQUEST['cerca_membro'])) {
			if(!empty($_REQUEST['cerca_capo'])) {
				$cond = "OR";
			}
			else {
				$cond = "AND";
			}
			
			$sql .=	$cond." (componenti_famiglie.c_codice_fiscale LIKE '%".strtoupper(pg_escape_string($_REQUEST['cerca_membro']))."%'
				OR componenti_famiglie.cognome LIKE '".strtolower(pg_escape_string($_REQUEST['cerca_membro']))."%') ";
		}

        if(isset($_REQUEST['credito_zero'])) {
            $sql .= "AND famiglie.punti_residui = '0' ";
        }

        if(isset($_REQUEST['inscadenza']) || $param == 'inscadenza') {
            $sql .= "AND famiglie.scadenza BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d", strtotime("+1 week"))."' ";
        }

        if(isset($_REQUEST['scaduti'])) {
            $sql .= "AND famiglie.scadenza <= '".date("Y-m-d")."' ";
        }

        if(isset($_REQUEST['sospesi'])) {
            $sql .= "AND famiglie.sospeso = 't' ";
        }

		$sql .= "ORDER BY famiglie.cognome ASC";

		$this->registry->template->ris = $this->registry->db->query($sql);
		$this->registry->template->cerca = 1;
		$this->registry->template->show('famiglie');
	}

	public function ajax_membriFamiglia() {
		$sql_membri = $this->registry->db->query("SELECT * FROM componenti_famiglie WHERE c_codice_fiscale = '".$_REQUEST['id']."'");

		$val = $sql_membri->fetch();

		$membro = "<strong>Codice Fiscale:</strong> &nbsp;<span class='label label-warning' style='font-size:14px;'>".strtoupper($val['c_codice_fiscale'])."</span>
					<br><br><strong>Capofamiglia:</strong> &nbsp;<span class='label label-danger' style='font-size:14px;'>".strtoupper($val['capofamiglia'])."</span>
                    <br><br><strong>Cognome e nome:</strong> ".ucwords($val['cognome'])." ".ucwords($val['nome'])."
					<br><strong>Sesso:</strong> ".$val['sesso']."
					<br><strong>Ruolo:</strong> ".$val['ruolo']."
                    <br><br><strong>Data di nascita:</strong> ".date("d/m/Y", strtotime($val['data_nascita']))."
                    <br><strong>Luogo di nascita:</strong> ".ucwords($val['luogo_nascita'])." (".ucwords($val['nazione']).")
                    <br><strong>Nazionalit&agrave:</strong> ".ucfirst($val['nazionalita'])." ";

		echo json_encode(array(
			"responseText1" => $membro
		));
	}

	public function ajax_capiFamiglia() {
		$sql_capi = "SELECT famiglie.*, comuni.nome_comune, enti.ragione_sociale, nazioni.nome_nazione 
                    FROM famiglie 
                    INNER JOIN nazioni on nazioni.cod_internazionale = famiglie.nazione
					INNER JOIN comuni ON famiglie.comune = comuni.cod_istat
					INNER JOIN enti ON enti.id_ente = famiglie.ente_proponente 
					WHERE famiglie.codice_fiscale = '".$_REQUEST['id']."'";
		$row = $this->registry->db->query($sql_capi);

		$val = $row->fetch();

		if($val['is_ente'] == 't')
			$is_ente = "Ente";
		else
			$is_ente = "Famiglia";

		if(!empty($val['data_nascita']))
			$data_nascita = date("d/m/Y", strtotime($val['data_nascita']));
		else
			$data_nascita = "";

		$capofamiglia = "<strong>Codice Fiscale:</strong> &nbsp;<span class='label label-danger' style='font-size:14px;'>".strtoupper($val['codice_fiscale'])."</span>
						<br><br><strong>Famiglia/Ente: </strong> ".$is_ente."
						<br><strong>Ente proponente: </strong> ".ucwords($val['ragione_sociale'])."
						<br><br><strong>Sesso:</strong> ".$val['sesso']."
						<br><strong>Data di nascita:</strong> ".$data_nascita."
						<br><strong>Luogo di nascita:</strong> ".ucwords($val['luogo_nascita'])." (".ucwords($val['nome_nazione']).")
						<br><strong>Nazionalit&agrave:</strong> ".ucwords($val['nome_nazione'])."
						<br><br><strong>Indirizzo:</strong> ".ucwords($val['indirizzo'])."
						<br><strong>Localit&agrave;:</strong> ".ucwords($val['localita'])." 
						<br><strong>Comune:</strong> ".ucwords($val['nome_comune'])." 
						<br><strong>CAP:</strong> ".$val['cap']." 
						<br><br><strong>Telefono:</strong> ".$val['telefono']."
						<br><strong>Cellulare:</strong> ".$val['cellulare']."
						<br><strong>Email:</strong> ".$val['email']."
						<br><br><strong>Iscritto da: </strong> ".date("d/m/Y", strtotime($val['iscritto_da']))."
						<br><strong>Scadenza: </strong> ".date("d/m/Y", strtotime($val['scadenza']))."
						<br><br><strong>Giorno:</strong> ".$val['giorno_1']."
						<br><strong>Orario:</strong> ".$val['orario']."
						<br><br><strong>Note:</strong> ".$val['note']."
						";

		$sql_membri = $this->registry->db->query("SELECT * FROM componenti_famiglie WHERE capofamiglia = '".$_REQUEST['id']."'");
		
		$membri = '<table id="tab_famigliari" class="table table-responsive table-hover table-striped">
                                <thead>
                                    <th>Codice Fiscale</th>
                                    <th>Cognome</th>
                                    <th>Nome</th>
                                    <th>Data di nascita</th>
                                    <th>Sesso</th>
                                    <th>Ruolo</th>
                                    <th>&nbsp;</th>
                                </thead>
                                <tbody>';

		foreach($sql_membri as $key=>$val) {
			$membri .= "<tr class='clickable-row-f' href='#dati_capofamiglia' data-url='/famiglie/ajax_membriFamiglia' tessera_f='".$val['c_codice_fiscale']."'>";
			$membri .= "<td>".strtoupper($val['c_codice_fiscale'])."</td>";
			$membri .= "<td>".ucwords($val['cognome'])."</td>";
            $membri .= "<td>".ucwords($val['nome'])."</td>";
            $membri .= "<td>".date("d/m/Y", strtotime($val['data_nascita']))."</td>";
            $membri .= "<td>".$val['sesso']."</td>";
            $membri .= "<td>".$val['ruolo']."</td>";
            $membri .= "<td style='text-align:right'>
                    <div class=\"btn-group\">
						<button type=\"button\" class=\"btn btn-sm btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                            <i class=\"fa fa-gear\"></i> Opzioni <span class=\"caret\"></span>
                        </button>
                        <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                            <li><a href='' form-action='/famiglie/modifica_famigliare/".$val['c_codice_fiscale']."' class='modify_membro' 
							id=\"".$val['c_codice_fiscale']."\" name=\"".$val['c_codice_fiscale']."\">
                                <span  class='fa fa-edit tooltips fa-fw' >&nbsp;</span> Modifica</a>
                            </li>
                            <li><a href='' form-action='/famiglie/remove_componente' class=\"delete_membro\" data-toggle=\"modal\" 
                                data-target=\"#myModal_m\" name=\"".$val['c_codice_fiscale']."\" id=\"".$val['c_codice_fiscale']."\">
								<span class='fa fa-times fa-fw' >&nbsp;</span> Elimina</a>
                            </li>
                        </ul>
                      </div>
                    </td>";
			$membri .= "</tr>";
		}

		$membri .= "</tbody></table>";
	
		echo json_encode(array(
			"responseText1" => $capofamiglia,
			"responseText2" => $membri
		));
	}

	public function nuovo_componente($codice_fiscale = '') {
		$this->registry->template->res = $this->registry->db->query("SELECT * FROM componenti_famiglie WHERE capofamiglia = '".$codice_fiscale."'");
		$this->registry->template->num_c = $this->registry->db->query("SELECT num_componenti FROM famiglie WHERE codice_fiscale = '".$codice_fiscale."'");

		$this->registry->template->tessera = $codice_fiscale;

		$this->registry->template->show('nuovocomponente');	
	}

	public function inserisci_componente() {
		$sql = "INSERT INTO componenti_famiglie VALUES('".strtoupper(pg_escape_string($_REQUEST['ccf']))."', '".strtoupper(pg_escape_string($_REQUEST['famiglia']))."', 
					'".strtolower(pg_escape_string($_REQUEST['ccognome']))."', '".strtolower(pg_escape_string($_REQUEST['cnome']))."', '".$_REQUEST['cruolo']."', 
					'".$_REQUEST['csesso']."', '".$_REQUEST['cdata_nascita']."', '".strtolower(pg_escape_string($_REQUEST['cluogo_nascita']))."', 
					'".strtolower(pg_escape_string($_REQUEST['cnazione']))."', '".strtolower(pg_escape_string($_REQUEST['cnazionalita']))."')";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->insert_memberok = 1;

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Inserito famigliare: ".strtoupper(pg_escape_string($_REQUEST['ccf']))."  
                            (".ucwords(pg_escape_string($_REQUEST['ccognome']))." ".ucwords(pg_escape_string($_REQUEST['cnome'])).") -
                            Capofamiglia: ".strtoupper(pg_escape_string($_REQUEST['famiglia']))."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->insert_memberok = 0;
        }

        $this->nuovo_componente($_REQUEST['famiglia']);
	}

	public function modifica_famigliare($codice_fiscale = '') {
		$sql = "SELECT * FROM componenti_famiglie WHERE c_codice_fiscale = '".$codice_fiscale."'";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->tessera = $codice_fiscale;

		$this->registry->template->show('modificacomponente');	
	}

	public function update_membro($codice_fiscale = '') {
		$sql = "UPDATE componenti_famiglie SET(
					c_codice_fiscale, cognome, nome, ruolo, sesso, data_nascita, luogo_nascita, nazione, nazionalita) = 
					('".strtoupper(pg_escape_string($_REQUEST['ccf']))."', '".strtolower(pg_escape_string($_REQUEST['ccognome']))."', 
					'".strtolower(pg_escape_string($_REQUEST['cnome']))."', '".$_REQUEST['cruolo']."', 
					'".$_REQUEST['csesso']."', '".$_REQUEST['cdata_nascita']."', '".strtolower(pg_escape_string($_REQUEST['cluogo_nascita']))."', 
					'".strtolower(pg_escape_string($_REQUEST['cnazione']))."', '".strtolower(pg_escape_string($_REQUEST['cnazionalita']))."')
					WHERE c_codice_fiscale = '".$codice_fiscale."'";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
            $this->registry->template->modify_memberok = 1;

			$sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Modifica famigliare: ".strtoupper(pg_escape_string($_REQUEST['ccf']))." 
                            (".ucwords(pg_escape_string($_REQUEST['ccognome']))." ".ucwords(pg_escape_string($_REQUEST['cnome'])).") -  
                            Capofamiglia: ".strtoupper(pg_escape_string($_REQUEST['famiglia']))."', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
		}
        else {
            $this->registry->template->modify_memberok = 0;
        }

        $this->modifica_famigliare($codice_fiscale);
	}

	public function remove_componente() {
        $sql_nome = $this->registry->db->query("SELECT cognome, nome FROM componenti_famiglie WHERE c_codice_fiscale = '".$_REQUEST['fake-form-tessera']."'");
        $val = $sql_nome->fetch();

		$sql = "DELETE FROM componenti_famiglie WHERE c_codice_fiscale = '".$_REQUEST['fake-form-tessera']."'";
        $count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->delete_memberok = 1;

		    $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                        VALUES('".date("Y-m-d H:i:s")."', 
                            '[CLIENTI]', 
                            'Rimosso famigliare: ".strtoupper(pg_escape_string($_REQUEST['fake-form-tessera']))." (".ucwords($val['cognome'])." ".ucwords($val['nome']).")', 
                            '".$_SESSION['username']."')";
            $count = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->delete_memberok = 0;
        }

        $this->index();		
	}

	public function ajax_punti_carbonaro() {
		$sql = "SELECT punti_corretti FROM tabella_carbonaro WHERE famigliari = '".$_REQUEST['id']."'";
		$ris = $this->registry->db->query($sql);

		$val = $ris->fetch();

		echo json_encode(array('responseText'=>$val['punti_corretti']));	
	}

	public function ajax_comuni() {
		$res = $this->registry->db->query("SELECT nome_comune, cod_istat, provincia, cap FROM comuni WHERE nome_comune LIKE '".strtolower($_REQUEST['term'])."%' ");
		$results = array();
		foreach($res as $k=>$row) {
			$row_array['cod_istat'] = $row['cod_istat'];
			$row_array['nome'] = ucwords($row['nome_comune'])." (".$row['provincia'].")";
			$row_array['cap'] = $row['cap'];

			array_push($results, $row_array);
		}

		echo json_encode($results);
	}

	public function ajax_nazioni() {
		$res = $this->registry->db->query("SELECT cod_internazionale, nome_nazione FROM nazioni WHERE nome_nazione LIKE '".strtolower($_REQUEST['term'])."%' ");
		$results = array();
		foreach($res as $k=>$row) {
			$row_array['cod_internazionale'] = $row['cod_internazionale'];
			$row_array['nome'] = ucwords($row['nome_nazione']);

			array_push($results, $row_array);
		}

		echo json_encode($results);
	}

    public function esporta() {
        $sql = "SELECT famiglie.*, comuni.nome_comune FROM famiglie  
                INNER JOIN comuni ON famiglie.comune = comuni.cod_istat
                ORDER BY famiglie.cognome ASC";
        $this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->show_noheader('xls_famiglie');
    }
}

?>
