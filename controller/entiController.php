<?php
session_start();

class entiController extends BaseController {

	public function index() {
		$this->registry->template->ris = $this->registry->db->query("SELECT * FROM enti ORDER BY ragione_sociale ASC");
	
		$this->registry->template->show("enti");
	}

	public function nuovo() {
		$this->registry->template->show("nuovoente");
	}

	public function inserisci() {
		$sql = "INSERT INTO enti(ragione_sociale, inserito_il, note) 
				VALUES('".strtolower(pg_escape_string($_REQUEST['ragione_sociale']))."', 
						'".$_REQUEST['inserito_il']."', '".pg_escape_string($_REQUEST['note'])."')";

		$count = $this->registry->db->exec($sql);

		if($count > 0) {
			$this->registry->template->insertok = 1;
            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[ENTI]', 
                            'Aggiunto nuovo ente: ".pg_escape_string($_REQUEST['ragione_sociale'])."', 
                            '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
		}
		else {
			$this->registry->template->insertok = 0;
		}

		$this->nuovo();
	}

	public function cerca() {
		$sql = "SELECT * FROM enti WHERE LOWER(ragione_sociale) LIKE '%".$_REQUEST['cerca_ente']."%' ORDER BY ragione_sociale ASC";
		$this->registry->template->ris = $this->registry->db->query($sql);

        $this->registry->template->cerca = 1;
	
		$this->registry->template->show("enti");
	}

	public function modifica($ente = '') {
		$this->registry->template->ente = $ente;

		$sql = "SELECT * FROM enti WHERE id_ente = '".$ente."'";
		$this->registry->template->ris = $this->registry->db->query($sql);

		$this->registry->template->show("modificaente");
	}

	public function update_ente($ente = '') {
		$sql = "UPDATE enti SET(ragione_sociale, inserito_il, note) =
				('".strtolower(pg_escape_string($_REQUEST['ragione_sociale']))."', 
                        '".$_REQUEST['inserito_il']."', '".pg_escape_string($_REQUEST['note'])."')
				WHERE id_ente = '".$ente."'";
		$count = $this->registry->db->exec($sql);

		if($count > 0) {
            $this->registry->template->modifyok = 1;

            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[ENTI]', 
                            'Ente modificato: ".pg_escape_string($_REQUEST['ragione_sociale'])."', 
                            '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->modifyok = 1;
        }
	    
        $this->modifica($ente);
	}

	public function rimuovi() {
        $sql_nome = $this->registry->db->query("SELECT ragione_sociale FROM enti WHERE id_ente = '".$_REQUEST['fake-form-ente']."'");
        $val = $sql_nome->fetch();

		$sql = "DELETE FROM enti WHERE id_ente = '".$_REQUEST['fake-form-ente']."'";
		$count = $this->registry->db->exec($sql);

        if($count > 0) {
            $this->registry->template->deleteok = 1;
    
            $sql_log = "INSERT INTO logs (log_data, flag, log_descrizione, login) 
                            VALUES('".date("Y-m-d H:i:s")."', 
                            '[ENTI]', 
                            'Rimosso ente: ".ucwords(pg_escape_string($val['ragione_sociale']))."', 
                            '".$_SESSION['username']."')";
            $count_log = $this->registry->db->exec($sql_log);
        }
        else {
            $this->registry->template->deleteok = 0;
        }

		$this->index();
	}

}

?>
